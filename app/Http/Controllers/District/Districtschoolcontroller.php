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

class DistrictSchoolController extends Controller
{
    private function councilId(): int
    {
        return Auth::user()->council_id;
    }

    // ─────────────────────────────────────────────────────────────────
    // INDEX — orodha ya shule zote
    // ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $officer   = Auth::user();
        $councilId = $this->councilId();

        $search   = $request->get('search');
        $wardId   = $request->get('ward_id');
        $status   = $request->get('status');   // active | inactive
        $perPage  = $request->get('per_page', 15);

        $query = School::with(['ward'])
            ->whereHas('ward', fn($q) => $q->where('council_id', $councilId));

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%");
            });
        }
        if ($wardId) $query->where('ward_id', $wardId);
        if ($status === 'active')   $query->where('is_active', 1);
        if ($status === 'inactive') $query->where('is_active', 0);

        $schools = $query->orderBy('name')->paginate($perPage)->withQueryString();

        // Attach quick stats to each school
        $today = Carbon::today()->toDateString();
        $schools->getCollection()->transform(function ($school) use ($today) {
            $school->teacher_count = User::where('school_id', $school->id)
                ->where('role', 'teacher')->where('status', 'approved')->count();

            $school->attended_today = Attendance::where('school_id', $school->id)
                ->whereDate('created_at', $today)
                ->distinct('user_id')->count('user_id');

            $school->attendance_rate = $school->teacher_count > 0
                ? round(($school->attended_today / $school->teacher_count) * 100, 1)
                : 0;

            return $school;
        });

        // Summary cards
        $totalSchools   = School::whereHas('ward', fn($q) => $q->where('council_id', $councilId))->count();
        $activeSchools  = School::whereHas('ward', fn($q) => $q->where('council_id', $councilId))->where('is_active', 1)->count();
        $withLocation   = School::whereHas('ward', fn($q) => $q->where('council_id', $councilId))->whereNotNull('latitude')->count();
        $pendingTeachers = User::where('role', 'teacher')->where('status', 'pending')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))->count();

        $wards = Ward::where('council_id', $councilId)->orderBy('name')->get();

        return view('district.schools.index', compact(
            'officer', 'schools', 'wards',
            'totalSchools', 'activeSchools', 'withLocation', 'pendingTeachers',
            'search', 'wardId', 'status', 'perPage',
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // SHOW — ukurasa wa shule moja (detail)
    // ─────────────────────────────────────────────────────────────────
    public function show(Request $request, School $school)
    {
        $officer   = Auth::user();
        $councilId = $this->councilId();

        // Security check
        abort_unless($school->ward->council_id === $councilId, 403);

        $selectedDate = $request->get('date', Carbon::today()->toDateString());

        // ── Teachers ────────────────────────────────────────────────
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->get();

        $approvedTeachers = $teachers->where('status', 'approved')->count();
        $pendingTeachers  = $teachers->where('status', 'pending')->count();

        // ── Today attendance ─────────────────────────────────────────
        $attendedToday = Attendance::where('school_id', $school->id)
            ->whereDate('created_at', $selectedDate)
            ->distinct('user_id')->count('user_id');

        $attendanceRate = $approvedTeachers > 0
            ? round(($attendedToday / $approvedTeachers) * 100, 1) : 0;

        // ── Last 14 days trend ───────────────────────────────────────
        $trend = collect();
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $cnt  = Attendance::where('school_id', $school->id)
                ->whereDate('created_at', $date)
                ->distinct('user_id')->count('user_id');
            $rate = $approvedTeachers > 0 ? round(($cnt / $approvedTeachers) * 100) : 0;
            $trend->push([
                'date'     => $date->format('d M'),
                'attended' => $cnt,
                'rate'     => $rate,
            ]);
        }

        // ── Per-teacher attendance (last 30 days) ────────────────────
        $workingDays = 0;
        for ($d = 0; $d < 30; $d++) {
            $day = Carbon::today()->subDays($d)->dayOfWeek;
            if ($day >= 1 && $day <= 5) $workingDays++;
        }

        $attendanceMap = Attendance::where('school_id', $school->id)
            ->whereBetween('created_at', [Carbon::today()->subDays(30), Carbon::today()])
            ->selectRaw('user_id, COUNT(DISTINCT DATE(created_at)) as days_count')
            ->groupBy('user_id')
            ->pluck('days_count', 'user_id');

        // Attach attendance info to each teacher
        $teachers = $teachers->map(function ($t) use ($attendanceMap, $workingDays, $selectedDate) {
            $days = $attendanceMap[$t->id] ?? 0;
            $t->att_days = $days;
            $t->att_rate = $workingDays > 0 ? round(($days / $workingDays) * 100) : 0;
            $t->came_today = Attendance::where('school_id', $t->school_id)
                ->where('user_id', $t->id)
                ->whereDate('created_at', $selectedDate)
                ->exists();
            return $t;
        });

        // ── Monthly summary (last 6 months) ─────────────────────────
        $monthlySummary = collect();
        for ($m = 5; $m >= 0; $m--) {
            $month     = Carbon::today()->subMonths($m);
            $monthDays = 0;
            for ($d = 0; $d < $month->daysInMonth; $d++) {
                $day = Carbon::create($month->year, $month->month, 1)->addDays($d)->dayOfWeek;
                if ($day >= 1 && $day <= 5) $monthDays++;
            }
            $attended = Attendance::where('school_id', $school->id)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->distinct('user_id')->count('user_id');

            $expected = $approvedTeachers * $monthDays;
            $total    = Attendance::where('school_id', $school->id)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $rate = $expected > 0 ? round(($total / $expected) * 100) : 0;
            $monthlySummary->push([
                'month'    => $month->format('M Y'),
                'total'    => $total,
                'rate'     => min(100, $rate),
            ]);
        }

        $wards = Ward::where('council_id', $councilId)->orderBy('name')->get();
        $pendingCount = $pendingTeachers;

        return view('district.schools.show', compact(
            'officer', 'school',
            'teachers', 'approvedTeachers', 'pendingTeachers', 'pendingCount',
            'attendedToday', 'attendanceRate',
            'trend', 'monthlySummary',
            'workingDays', 'selectedDate',
            'wards',
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // STORE — ongeza shule mpya
    // ─────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $councilId = $this->councilId();

        $request->validate([
            'name'      => 'required|string|max:255',
            'ward_id'   => 'required|exists:wards,id',
            'code'      => 'nullable|string|max:50|unique:schools,code',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius'    => 'nullable|integer|min:50|max:5000',
        ]);

        // Verify ward belongs to this council
        $ward = Ward::findOrFail($request->ward_id);
        abort_unless($ward->council_id === $councilId, 403);

        School::create([
            'name'      => $request->name,
            'ward_id'   => $request->ward_id,
            'code'      => $request->code,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'radius'    => $request->radius ?? 500,
            'is_active' => 1,
        ]);

        return redirect()->route('district.schools.index')
            ->with('success', "Shule \"{$request->name}\" imeongezwa.");
    }

    // ─────────────────────────────────────────────────────────────────
    // UPDATE — hariri taarifa za shule
    // ─────────────────────────────────────────────────────────────────
    public function update(Request $request, School $school)
    {
        $councilId = $this->councilId();
        abort_unless($school->ward->council_id === $councilId, 403);

        $request->validate([
            'name'      => 'required|string|max:255',
            'ward_id'   => 'required|exists:wards,id',
            'code'      => 'nullable|string|max:50|unique:schools,code,' . $school->id,
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius'    => 'nullable|integer|min:50|max:5000',
        ]);

        $school->update([
            'name'      => $request->name,
            'ward_id'   => $request->ward_id,
            'code'      => $request->code,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'radius'    => $request->radius ?? $school->radius,
        ]);

        return redirect()->route('district.schools.show', $school)
            ->with('success', "Taarifa za \"{$school->name}\" zimehifadhiwa.");
    }

    // ─────────────────────────────────────────────────────────────────
    // TOGGLE — washa / zima shule
    // ─────────────────────────────────────────────────────────────────
    public function toggle(School $school)
    {
        $councilId = $this->councilId();
        abort_unless($school->ward->council_id === $councilId, 403);

        $school->update(['is_active' => !$school->is_active]);

        $status = $school->is_active ? 'imewashwa' : 'imezimwa';
        return back()->with('success', "Shule \"{$school->name}\" $status.");
    }
}