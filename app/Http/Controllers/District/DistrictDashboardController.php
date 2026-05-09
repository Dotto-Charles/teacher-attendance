<?php

namespace App\Http\Controllers\District;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\School;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DistrictDashboardController extends Controller
{
    public function index(Request $request)
    {
        $officer = Auth::user();
        $councilId = $officer->council_id;
        $today = Carbon::today();
        $selectedDate = $request->get('date', $today->toDateString());
        $selectedWardId = $request->get('ward_id', null);

        $teacherRoles = ['teacher', 'head_teacher'];

        // ─── SUMMARY CARDS ───────────────────────────────────────────────
        $totalSchools = School::whereHas('ward', fn($q) => $q->where('council_id', $councilId))->count();
        $totalWards   = Ward::where('council_id', $councilId)->count();
        $totalTeachers = User::whereIn('role', $teacherRoles)
            ->where('status', 'approved')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->count();

        // Teachers who attended today (overall)
        $totalAttendedToday = Attendance::whereDate('created_at', $selectedDate)
            ->whereHas('user', fn($q) => $q->where('role', 'teacher')->where('status', 'approved'))
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->distinct('user_id')
            ->count('user_id');

        $overallRate = $totalTeachers > 0
            ? round(($totalAttendedToday / $totalTeachers) * 100, 1)
            : 0;

        // ─── ATTENDANCE PER SCHOOL (for selected date & optional ward) ──
        $schoolsQuery = School::with(['ward'])
            ->whereHas('ward', fn($q) => $q->where('council_id', $councilId));

        if ($selectedWardId) {
            $schoolsQuery->where('ward_id', $selectedWardId);
        }

        $schools = $schoolsQuery->get();

        $schoolAttendance = $schools->map(function ($school) use ($selectedDate, $teacherRoles) {
            $teacherCount = User::whereIn('role', $teacherRoles)
                ->where('status', 'approved')
                ->where('school_id', $school->id)
                ->count();

            $attended = Attendance::whereDate('created_at', $selectedDate)
                ->where('school_id', $school->id)
                ->whereHas('user', fn($q) => $q->whereIn('role', $teacherRoles)->where('status', 'approved'))
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

        // ─── LAST 7 DAYS TREND ───────────────────────────────────────────
        $trend = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $attended = Attendance::whereDate('created_at', $date)
                ->whereHas('user', fn($q) => $q->whereIn('role', $teacherRoles)->where('status', 'approved'))
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

        // ─── WARD SUMMARY ────────────────────────────────────────────────
        $wards = Ward::where('council_id', $councilId)->get();

        $wardSummary = $wards->map(function ($ward) use ($selectedDate, $teacherRoles) {
            $wardTeachers = User::whereIn('role', $teacherRoles)
                ->where('status', 'approved')
                ->whereHas('school', fn($q) => $q->where('ward_id', $ward->id))
                ->count();

            $wardAttended = Attendance::whereDate('created_at', $selectedDate)
                ->whereHas('user', fn($q) => $q->whereIn('role', $teacherRoles)->where('status', 'approved'))
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

        // ─── TOP & BOTTOM SCHOOLS ────────────────────────────────────────
        $topSchools    = $schoolAttendance->take(5);
        $bottomSchools = $schoolAttendance->sortBy('rate')->take(5)->values();

        // ─── PENDING APPROVALS (teachers awaiting approval) ──────────────
        $pendingTeachers = User::whereIn('role', $teacherRoles)
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
}