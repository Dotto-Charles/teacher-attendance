<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

        // ── TEACHER & HEAD TEACHER ────────────────────────────────────
        if (in_array($user->role, ['teacher', 'head_teacher'])) {
            return (new \App\Http\Controllers\Teacher\TeacherDashboardController)->index($request);
        }

        // ── WARD OFFICER ──────────────────────────────────────────────
        if ($user->role === 'ward_officer') {
            return redirect()->route('ward.dashboard');
        }

        // ── DISTRICT OFFICER ──────────────────────────────────────────
        if ($user->role === 'district_officer') {

            abort_if(!$user->council_id, 403, 'Hakuna halmashauri iliyowekwa.');

            $officer        = $user;
            $councilId      = $user->council_id;
            $today          = Carbon::today();
            $selectedDate   = $request->get('date', $today->toDateString());
            $selectedWardId = $request->get('ward_id', null);

            // ── CACHED: basic counts (refresh kila dakika 10) ─────────
            $totalSchools = Cache::remember("dist_schools_{$councilId}", 600, fn() =>
                School::whereHas('ward', fn($q) => $q->where('council_id', $councilId))->count()
            );

            $totalWards = Cache::remember("dist_wards_{$councilId}", 600, fn() =>
                Ward::where('council_id', $councilId)->count()
            );

            $totalTeachers = Cache::remember("dist_teachers_{$councilId}", 600, fn() =>
                User::where('role', 'teacher')
                    ->where('status', 'approved')
                    ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
                    ->count()
            );

            $pendingTeachers = Cache::remember("dist_pending_{$councilId}", 120, fn() =>
                User::where('role', 'teacher')
                    ->where('status', 'pending')
                    ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
                    ->count()
            );

            // ── WARDS LIST (fresh query - small dataset) ──────────────
            $wards = Ward::where('council_id', $councilId)->orderBy('name')->get();

            // ── ATTENDANCE TODAY (short TTL - 2 min) ─────────────────
            $totalAttendedToday = Cache::remember(
                "dist_att_{$councilId}_{$selectedDate}",
                Carbon::parse($selectedDate)->isToday() ? 120 : 3600,
                fn() => Attendance::whereDate('created_at', $selectedDate)
                    ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
                    ->distinct('user_id')->count('user_id')
            );

            $overallRate = $totalTeachers > 0
                ? round(($totalAttendedToday / $totalTeachers) * 100, 1)
                : 0;

            // ── SCHOOL ATTENDANCE (filtered by ward if selected) ──────
            $schoolQuery = School::with(['ward'])
                ->whereHas('ward', fn($q) => $q->where('council_id', $councilId));
            if ($selectedWardId) {
                $schoolQuery->where('ward_id', $selectedWardId);
            }
            $schools = $schoolQuery->get();

            $schoolAttendance = $schools->map(function ($school) use ($selectedDate) {
                $teacherCount = User::where('role', 'teacher')
                    ->where('status', 'approved')
                    ->where('school_id', $school->id)
                    ->count();

                $attended = Attendance::whereDate('created_at', $selectedDate)
                    ->where('school_id', $school->id)
                    ->distinct('user_id')->count('user_id');

                $rate = $teacherCount > 0 ? round(($attended / $teacherCount) * 100, 1) : 0;

                return [
                    'id'            => $school->id,
                    'name'          => $school->name,
                    'ward'          => $school->ward->name ?? '—',
                    'teacher_count' => $teacherCount,
                    'attended'      => $attended,
                    'absent'        => max(0, $teacherCount - $attended),
                    'rate'          => $rate,
                ];
            })->sortByDesc('rate')->values();

            // ── 7-DAY TREND (cached 5 min) ────────────────────────────
            $trend = Cache::remember("dist_trend_{$councilId}", 300, function () use ($councilId, $totalTeachers) {
                $trend = collect();
                for ($i = 6; $i >= 0; $i--) {
                    $date     = Carbon::today()->subDays($i);
                    $attended = Attendance::whereDate('created_at', $date)
                        ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
                        ->distinct('user_id')->count('user_id');
                    $rate = $totalTeachers > 0 ? round(($attended / $totalTeachers) * 100, 1) : 0;
                    $trend->push([
                        'date'     => $date->format('D, d M'),
                        'attended' => $attended,
                        'rate'     => $rate,
                    ]);
                }
                return $trend;
            });

            // ── WARD SUMMARY (cached 5 min) ───────────────────────────
            $wardSummary = Cache::remember(
                "dist_ward_summary_{$councilId}_{$selectedDate}",
                Carbon::parse($selectedDate)->isToday() ? 300 : 3600,
                function () use ($councilId, $selectedDate) {

    $wards = Ward::where('council_id', $councilId)->get();

    return $wards->map(function ($ward) use ($selectedDate) {
                        $wardTeachers = User::where('role', 'teacher')
                            ->where('status', 'approved')
                            ->whereHas('school', fn($q) => $q->where('ward_id', $ward->id))
                            ->count();

                        $wardAttended = Attendance::whereDate('created_at', $selectedDate)
                            ->whereHas('school', fn($q) => $q->where('ward_id', $ward->id))
                            ->distinct('user_id')->count('user_id');

                        $rate = $wardTeachers > 0
                            ? round(($wardAttended / $wardTeachers) * 100, 1) : 0;

                        return [
                            'id'       => $ward->id,
                            'name'     => $ward->name,
                            'teachers' => $wardTeachers,
                            'attended' => $wardAttended,
                            'rate'     => $rate,
                        ];
                    })->sortByDesc('rate')->values();
                }
            );

            // ── TOP & BOTTOM SCHOOLS ──────────────────────────────────
            $topSchools    = $schoolAttendance->take(5);
            $bottomSchools = $schoolAttendance->sortBy('rate')->take(5)->values();

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

        // ── ADMIN ─────────────────────────────────────────────────────
          if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        abort(403, 'Unauthorized role');
    }
}