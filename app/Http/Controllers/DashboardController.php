<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Attendance;
use App\Models\School;
use App\Models\Ward;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | TEACHER DASHBOARD
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'teacher') {
            return view('dashboards.teacher');
        }

        /*
        |--------------------------------------------------------------------------
        | DISTRICT OFFICER DASHBOARD
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'district_officer') {

            if (!$user->council_id) {
                abort(403, 'Hakuna halmashauri iliyowekwa kwa akaunti hii.');
            }

            $officer       = $user;
            $councilId     = $user->council_id;
            $today         = Carbon::today();
            $selectedDate  = $request->get('date', $today->toDateString());
            $selectedWardId = $request->get('ward_id', null);

            // ── Base data ──────────────────────────────────────────────
            $totalSchools  = School::whereHas('ward', fn($q) => $q->where('council_id', $councilId))->count();
            $totalWards    = Ward::where('council_id', $councilId)->count();
            $totalTeachers = User::where('role', 'teacher')
                ->where('status', 'approved')
                ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
                ->count();

            $totalAttendedToday = Attendance::whereDate('created_at', $selectedDate)
                ->whereHas('user', fn($q) => $q->where('role', 'teacher')->where('status', 'approved'))
                ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
                ->distinct('user_id')
                ->count('user_id');

            $overallRate = $totalTeachers > 0
                ? round(($totalAttendedToday / $totalTeachers) * 100, 1)
                : 0;

            // ── Attendance per school ──────────────────────────────────
            $schoolsQuery = School::with(['ward'])
                ->whereHas('ward', fn($q) => $q->where('council_id', $councilId));

            if ($selectedWardId) {
                $schoolsQuery->where('ward_id', $selectedWardId);
            }

            $schools = $schoolsQuery->get();

            $schoolAttendance = $schools->map(function ($school) use ($selectedDate) {
                $teacherCount = User::where('role', 'teacher')
                    ->where('status', 'approved')
                    ->where('school_id', $school->id)
                    ->count();

                $attended = Attendance::whereDate('created_at', $selectedDate)
                    ->where('school_id', $school->id)
                    ->distinct('user_id')
                    ->count('user_id');

                $rate = $teacherCount > 0 ? round(($attended / $teacherCount) * 100, 1) : 0;

                return [
                    'id'            => $school->id,
                    'name'          => $school->name,
                    'ward'          => $school->ward->name ?? '-',
                    'teacher_count' => $teacherCount,
                    'attended'      => $attended,
                    'absent'        => max(0, $teacherCount - $attended),
                    'rate'          => $rate,
                ];
            })->sortByDesc('rate')->values();

            // ── Last 7 days trend ──────────────────────────────────────
            $trend = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $attended = Attendance::whereDate('created_at', $date)
                    ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
                    ->distinct('user_id')
                    ->count('user_id');

                $rate = $totalTeachers > 0 ? round(($attended / $totalTeachers) * 100, 1) : 0;
                $trend->push([
                    'date'     => $date->format('D, d M'),
                    'attended' => $attended,
                    'rate'     => $rate,
                ]);
            }

            // ── Ward summary ───────────────────────────────────────────
            $wards = Ward::where('council_id', $councilId)->get();

            $wardSummary = $wards->map(function ($ward) use ($selectedDate) {
                $wardTeachers = User::where('role', 'teacher')
                    ->where('status', 'approved')
                    ->whereHas('school', fn($q) => $q->where('ward_id', $ward->id))
                    ->count();

                $wardAttended = Attendance::whereDate('created_at', $selectedDate)
                    ->whereHas('school', fn($q) => $q->where('ward_id', $ward->id))
                    ->distinct('user_id')
                    ->count('user_id');

                $rate = $wardTeachers > 0 ? round(($wardAttended / $wardTeachers) * 100, 1) : 0;

                return [
                    'id'       => $ward->id,
                    'name'     => $ward->name,
                    'teachers' => $wardTeachers,
                    'attended' => $wardAttended,
                    'rate'     => $rate,
                ];
            })->sortByDesc('rate')->values();

            // ── Top & Bottom schools ───────────────────────────────────
            $topSchools    = $schoolAttendance->take(5);
            $bottomSchools = $schoolAttendance->sortBy('rate')->take(5)->values();

            // ── Pending teachers ───────────────────────────────────────
            $pendingTeachers = User::where('role', 'teacher')
                ->where('status', 'pending')
                ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
                ->count();

            return view('dashboards.district', compact(
                'officer',
                'selectedDate',
                'selectedWardId',
                'totalSchools',
                'totalWards',
                'totalTeachers',
                'totalAttendedToday',
                'overallRate',
                'schoolAttendance',
                'trend',
                'wardSummary',
                'topSchools',
                'bottomSchools',
                'wards',
                'pendingTeachers',
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | HEAD TEACHER DASHBOARD
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'head_teacher') {

            if (!$user->school_id) {
                abort(403, 'No school assigned');
            }

            $schoolId        = $user->school_id;
            $totalTeachers   = User::where('role', 'teacher')->where('school_id', $schoolId)->count();
            $pending         = User::where('role', 'teacher')->where('school_id', $schoolId)->where('status', 'pending')->count();
            $approved        = User::where('role', 'teacher')->where('school_id', $schoolId)->where('status', 'approved')->count();
            $todayAttendance = Attendance::where('school_id', $schoolId)->whereDate('created_at', today())->count();

            return view('dashboards.head_teacher', compact(
                'totalTeachers', 'pending', 'approved', 'todayAttendance'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | WARD OFFICER DASHBOARD
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'ward_officer') {

            if (!$user->ward_id) {
                abort(403, 'No ward assigned');
            }

            $schools   = School::where('ward_id', $user->ward_id)->get();
            $schoolIds = $schools->pluck('id');

            $baseQuery = Attendance::with(['user', 'school'])->whereIn('school_id', $schoolIds);

            if ($request->school_id) $baseQuery->where('school_id', $request->school_id);
            if ($request->user_id)   $baseQuery->where('user_id', $request->user_id);
            if ($request->start && $request->end) {
                $baseQuery->whereBetween('created_at', [
                    Carbon::parse($request->start)->startOfDay(),
                    Carbon::parse($request->end)->endOfDay(),
                ]);
            }

            $attendances     = (clone $baseQuery)->latest()->paginate(20);
            $totalSchools    = $schools->count();
            $totalTeachers   = User::whereIn('school_id', $schoolIds)->where('role', 'teacher')->count();
            $todayAttendance = (clone $baseQuery)->whereDate('created_at', today())->count();
            $teachers        = User::whereIn('school_id', $schoolIds)->where('role', 'teacher')->get();

            return view('dashboards.officer', compact(
                'schools', 'teachers', 'attendances',
                'totalSchools', 'totalTeachers', 'todayAttendance'
            ));
        }

        abort(403, 'Unauthorized role');
    }
}