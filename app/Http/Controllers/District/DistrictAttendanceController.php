<?php

namespace App\Http\Controllers\District;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\School;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DistrictAttendanceController extends Controller
{
    private function councilId(): int
    {
        return Auth::user()->council_id;
    }

    // ─────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $officer   = Auth::user();
        $councilId = $this->councilId();

        // ── Filters ──────────────────────────────────────────────────
        $selectedDate  = $request->get('date', Carbon::today()->toDateString());
        $selectedWard  = $request->get('ward_id');
        $selectedSchool= $request->get('school_id');
        $statusFilter  = $request->get('status'); // present | absent
        $search        = $request->get('search');
        $perPage       = $request->get('per_page', 25);

        // ── Ward & School lists for dropdowns ─────────────────────────
        $wards = Ward::where('council_id', $councilId)->orderBy('name')->get();

        $schools = School::whereHas('ward', fn($q) => $q->where('council_id', $councilId))
            ->when($selectedWard, fn($q) => $q->where('ward_id', $selectedWard))
            ->orderBy('name')->get();

        // ── Teachers + head teachers base query ───────────────────────
        $teacherRoles = ['teacher', 'head_teacher'];
        $teacherQuery = User::with(['school.ward'])
            ->whereIn('role', $teacherRoles)
            ->where('status', 'approved')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId));

        if ($selectedWard)   $teacherQuery->whereHas('school', fn($q) => $q->where('ward_id', $selectedWard));
        if ($selectedSchool) $teacherQuery->where('school_id', $selectedSchool);
        if ($search) {
            $teacherQuery->where(fn($q) => $q
                ->where('first_name',    'like', "%$search%")
                ->orWhere('last_name',   'like', "%$search%")
                ->orWhere('check_number','like', "%$search%")
            );
        }

        // Attendance IDs for selected date
        $presentIds = Attendance::whereDate('created_at', $selectedDate)
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->distinct('user_id')
            ->pluck('user_id');

        if ($statusFilter === 'present') $teacherQuery->whereIn('id', $presentIds);
        if ($statusFilter === 'absent')  $teacherQuery->whereNotIn('id', $presentIds);

        $teachers = $teacherQuery->orderBy('first_name')->paginate($perPage)->withQueryString();

        // Attach presence flag & check-in time
        $checkInTimes = Attendance::whereDate('created_at', $selectedDate)
            ->whereIn('user_id', $presentIds)
            ->selectRaw('user_id, MIN(created_at) as check_in_time')
            ->groupBy('user_id')
            ->pluck('check_in_time', 'user_id');

        $teachers->getCollection()->transform(function ($t) use ($presentIds, $checkInTimes) {
            $t->is_present   = $presentIds->contains($t->id);
            $t->check_in_time = $t->is_present
                ? Carbon::parse($checkInTimes[$t->id])->format('H:i')
                : null;
            return $t;
        });

        // ── Summary stats (ALL teachers in council for selected date) ─
        $allTeachersCount = User::whereIn('role', $teacherRoles)->where('status','approved')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->count();

        $presentCount = Attendance::whereDate('created_at', $selectedDate)
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->distinct('user_id')->count('user_id');

        $absentCount    = max(0, $allTeachersCount - $presentCount);
        $overallRate    = $allTeachersCount > 0 ? round(($presentCount / $allTeachersCount) * 100, 1) : 0;

        // ── School cards (attendance per school) ──────────────────────
        $schoolQuery = School::with('ward')
            ->whereHas('ward', fn($q) => $q->where('council_id', $councilId))
            ->where('is_active', 1);

        if ($selectedWard)   $schoolQuery->where('ward_id', $selectedWard);
        if ($selectedSchool) $schoolQuery->where('id', $selectedSchool);

        $schoolCards = $schoolQuery->get()->map(function ($school) use ($selectedDate, $teacherRoles) {
            $total = User::where('school_id', $school->id)->whereIn('role', $teacherRoles)->where('status','approved')->count();
            $came  = Attendance::where('school_id', $school->id)
                ->whereDate('created_at', $selectedDate)
                ->distinct('user_id')->count('user_id');
            $rate  = $total > 0 ? round(($came / $total) * 100, 1) : 0;
            return [
                'id'     => $school->id,
                'name'   => $school->name,
                'ward'   => $school->ward->name ?? '—',
                'total'  => $total,
                'came'   => $came,
                'absent' => max(0, $total - $came),
                'rate'   => $rate,
            ];
        })->sortByDesc('rate')->values();

        // ── Absent teachers (full list for export/view) ───────────────
        $absentTeachers = User::with(['school.ward'])
            ->whereIn('role', $teacherRoles)->where('status','approved')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->when($selectedWard,    fn($q) => $q->whereHas('school', fn($q2) => $q2->where('ward_id', $selectedWard)))
            ->when($selectedSchool,  fn($q) => $q->where('school_id', $selectedSchool))
            ->whereNotIn('id', $presentIds)
            ->orderBy('first_name')
            ->get();

        // ── Hourly check-in distribution ─────────────────────────────
        $hourly = Attendance::whereDate('created_at', $selectedDate)
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->selectRaw('HOUR(created_at) as hour, COUNT(DISTINCT user_id) as cnt')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('cnt', 'hour');

        $hourlyData = [];
        for ($h = 5; $h <= 18; $h++) {
            $hourlyData[] = ['hour' => sprintf('%02d:00', $h), 'count' => $hourly[$h] ?? 0];
        }

        $pendingTeachers = User::where('role','teacher')->where('status','pending')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))->count();

        return view('district.attendance.index', compact(
            'officer', 'wards', 'schools',
            'teachers', 'schoolCards',
            'absentTeachers',
            'allTeachersCount', 'presentCount', 'absentCount', 'overallRate',
            'hourlyData',
            'selectedDate', 'selectedWard', 'selectedSchool', 'statusFilter', 'search', 'perPage',
            'pendingTeachers',
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // EXPORT CSV
    // ─────────────────────────────────────────────────────────────────
    public function exportCsv(Request $request)
    {
        $officer   = Auth::user();
        $councilId = $this->councilId();
        $date      = $request->get('date', Carbon::today()->toDateString());
        $wardId    = $request->get('ward_id');
        $schoolId  = $request->get('school_id');
        $teacherRoles = ['teacher', 'head_teacher'];

        $presentIds = Attendance::whereDate('created_at', $date)
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->distinct('user_id')->pluck('user_id');

        $checkInTimes = Attendance::whereDate('created_at', $date)
            ->whereIn('user_id', $presentIds)
            ->selectRaw('user_id, MIN(created_at) as check_in_time')
            ->groupBy('user_id')
            ->pluck('check_in_time', 'user_id');

        $teachers = User::with(['school.ward'])
            ->whereIn('role', $teacherRoles)->where('status','approved')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->when($wardId,   fn($q) => $q->whereHas('school', fn($q2) => $q2->where('ward_id', $wardId)))
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->orderBy('first_name')->get();

        $filename = "mahudhurio_{$date}.csv";
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $rows = [];
        $rows[] = implode(',', [
            '#', 'Jina Kamili', 'Namba ya Cheki', 'Jinsia',
            'Shule', 'Kata', 'Hali', 'Wakati wa Kufika', "Tarehe: $date"
        ]);

        foreach ($teachers as $i => $t) {
            $present  = $presentIds->contains($t->id);
            $checkIn  = $present ? Carbon::parse($checkInTimes[$t->id])->format('H:i') : '—';
            $rows[] = implode(',', [
                $i + 1,
                '"' . $t->first_name . ' ' . $t->last_name . '"',
                $t->check_number,
                $t->sex === 'female' ? 'Mwanamke' : 'Mwanaume',
                '"' . ($t->school->name ?? '—') . '"',
                '"' . ($t->school->ward->name ?? '—') . '"',
                $present ? 'Alikuja' : 'Hakuja',
                $checkIn,
            ]);
        }

        return response(implode("\n", $rows), 200, $headers);
    }
}