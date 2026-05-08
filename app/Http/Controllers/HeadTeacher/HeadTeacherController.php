<?php

namespace App\Http\Controllers\HeadTeacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\School;
use App\Models\Transfer;
use App\Models\User;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

class HeadTeacherController extends Controller
{
    public function __construct(private AttendanceService $attService)
    {
    }

    private function school(): School
{
    $user = Auth::user();

    abort_unless($user && $user->school_id, 403, 'Huna shule.');

    $schoolId = Cache::remember(
        "user_school_id:{$user->id}",
        300,
        fn() => $user->school_id
    );

    return School::with(['ward.council'])->findOrFail($schoolId);
}

    // ─────────────────────────────────────────────────────────────────
    // DASHBOARD
    // ─────────────────────────────────────────────────────────────────
    public function dashboard(Request $request)
    {
        $ht           = Auth::user();
        $school       = $this->school();
        $selectedDate = $request->get('date', today()->toDateString());

        // ── Summary (cached) ─────────────────────────────────────────
        $summary = $this->attService->schoolDailySummary($school->id, $selectedDate);

        // ── Teacher counts (cached 10 min) ───────────────────────────
        [$totalTeachers, $pendingCount, $approvedCount] = Cache::remember(
            "ht:teacher_counts:{$school->id}", 600,
            fn() => [
                User::where('school_id', $school->id)->whereIn('role',['teacher','head_teacher'])->count(),
                User::where('school_id', $school->id)->where('role','teacher')->where('status','pending')->count(),
                User::where('school_id', $school->id)->where('role','teacher')->where('status','approved')->count(),
            ]
        );

        // ── 14-day trend ─────────────────────────────────────────────
        $trend = $this->attService->dailyTrend('school', $school->id, 14);

        // ── Today's teachers with presence ───────────────────────────
        $todayTeachers = DB::select("
    SELECT 
        u.id, 
        u.first_name, 
        u.last_name, 
        u.check_number, 
        u.sex, 
        u.role, 
        u.status,
        MAX(a.created_at) AS checked_at,
        CASE WHEN MAX(a.user_id) IS NOT NULL THEN 1 ELSE 0 END AS is_present

    FROM users u

    LEFT JOIN attendances a
        ON a.user_id   = u.id
       AND a.school_id = ?
       AND DATE(a.created_at) = ?

    WHERE u.school_id = ?
      AND u.role IN ('teacher','head_teacher')
      AND u.status = 'approved'

    GROUP BY 
        u.id,
        u.first_name,
        u.last_name,
        u.check_number,
        u.sex,
        u.role,
        u.status

    ORDER BY is_present DESC, u.first_name
", [
    $school->id,
    now()->toDateString(),
    $school->id
]);

        // ── Monthly leaderboard (cached) ─────────────────────────────
        $monthFrom = today()->startOfMonth()->toDateString();
        $monthTo   = today()->toDateString();
        $leaderboard = $this->attService->schoolTeacherStats($school->id, $monthFrom, $monthTo)->take(5);

        // ── Pending approvals ─────────────────────────────────────────
        $pendingTeachers = User::where('school_id', $school->id)
            ->where('role','teacher')->where('status','pending')
            ->orderBy('created_at','desc')
            ->limit(5)->get(['id','first_name','last_name','check_number','sex','created_at']);

        // ── Pending transfers ─────────────────────────────────────────
        $pendingTransfers = Transfer::with(['user:id,first_name,last_name','toSchool:id,name'])
            ->where('status','pending')
            ->whereHas('user', fn($q) => $q->where('school_id', $school->id))
            ->count();

        // ── HT own attendance today ───────────────────────────────────
        $htAttendedToday = Attendance::where('user_id', $ht->id)
            ->whereDate('created_at', $selectedDate)->exists();

        return view('headteacher.dashboard', compact(
            'ht','school','selectedDate',
            'summary','totalTeachers','pendingCount','approvedCount',
            'trend','todayTeachers','leaderboard',
            'pendingTeachers','pendingTransfers','htAttendedToday',
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // TEACHERS LIST (paginated, filtered)
    // ─────────────────────────────────────────────────────────────────
    public function teachers(Request $request)
    {
        $school  = $this->school();
        $search  = $request->get('search');
        $status  = $request->get('status');
        $sex     = $request->get('sex');
        $perPage = $request->get('per_page', 20);

        // Working days this month for attendance rate
        $workDays  = $this->attService->countWorkDays(
            today()->startOfMonth()->toDateString(), today()->toDateString()
        );

        // Optimized: single query via raw JOIN
        $baseQuery = User::select([
                'users.id','users.first_name','users.middle_name','users.last_name',
                'users.check_number','users.sex','users.role','users.status',
                'users.phone','users.email','users.created_at',
                DB::raw('COUNT(DISTINCT DATE(a.created_at)) as att_days'),
                DB::raw("ROUND(COUNT(DISTINCT DATE(a.created_at)) / NULLIF({$workDays},0) * 100) as att_rate"),
            ])
            ->leftJoin('attendances as a', function ($join) {
                $join->on('a.user_id', '=', 'users.id')
                     ->whereRaw('YEAR(a.created_at) = ?', [now()->year])
                     ->whereRaw('MONTH(a.created_at) = ?', [now()->month]);
            })
            ->where('users.school_id', $school->id)
            ->whereIn('users.role', ['teacher','head_teacher'])
            ->groupBy([
    'users.id',
    'users.first_name',
    'users.middle_name',
    'users.last_name',
    'users.check_number',
    'users.sex',
    'users.role',
    'users.status',
    'users.phone',
    'users.email',
    'users.created_at',
]);

        if ($search) {
            $baseQuery->where(fn($q) => $q
                ->where('users.first_name',    'like', "%{$search}%")
                ->orWhere('users.last_name',   'like', "%{$search}%")
                ->orWhere('users.check_number','like', "%{$search}%")
                ->orWhere('users.email',       'like', "%{$search}%")
            );
        }
        if ($status) $baseQuery->where('users.status', $status);
        if ($sex)    $baseQuery->where('users.sex',    $sex);

        $teachers = $baseQuery->orderByDesc('att_rate')->paginate($perPage)->withQueryString();

        // Counts (cached)
        [$totalCount,$approvedCount,$pendingCount,$maleCount,$femaleCount] = Cache::remember(
            "ht:teacher_counts_full:{$school->id}", 300,
            fn() => [
                User::where('school_id',$school->id)->whereIn('role',['teacher','head_teacher'])->count(),
                User::where('school_id',$school->id)->whereIn('role',['teacher','head_teacher'])->where('status','approved')->count(),
                User::where('school_id',$school->id)->whereIn('role',['teacher','head_teacher'])->where('status','pending')->count(),
                User::where('school_id',$school->id)->whereIn('role',['teacher','head_teacher'])->where('sex','male')->count(),
                User::where('school_id',$school->id)->whereIn('role',['teacher','head_teacher'])->where('sex','female')->count(),
            ]
        );

        return view('headteacher.teachers', compact(
            'school','teachers','workDays',
            'totalCount','approvedCount','pendingCount','maleCount','femaleCount',
            'search','status','sex','perPage',
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // ATTENDANCE PAGE
    // ─────────────────────────────────────────────────────────────────
    public function attendance(Request $request)
    {
        $school       = $this->school();
        $selectedDate = $request->get('date', today()->toDateString());
        $schoolId     = $school->id;
        $statusFilter = $request->get('status');
        $search       = $request->get('search');
        $perPage      = $request->get('per_page', 25);

        // Summary (cached)
        $summary = $this->attService->schoolDailySummary($schoolId, $selectedDate);

        // Teacher list with presence — single JOIN query
        $query = User::select([
                'users.id','users.first_name','users.last_name','users.check_number',
                'users.sex','users.role','users.status',
                DB::raw('MAX(a.created_at) as checked_at'),
                DB::raw('CASE WHEN MAX(a.user_id) IS NOT NULL THEN 1 ELSE 0 END as is_present'),
                DB::raw('ROUND(a2.days_count / NULLIF(?,0) * 100) as month_rate'),
            ])
            ->leftJoin('attendances as a', function ($join) use ($schoolId, $selectedDate) {
                $join->on('a.user_id','=','users.id')
                     ->where('a.school_id', $schoolId)
                     ->whereRaw('DATE(a.created_at) = ?', [$selectedDate]);
            })
            ->leftJoinSub(
                Attendance::selectRaw('user_id, COUNT(DISTINCT DATE(created_at)) as days_count')
                    ->where('school_id', $schoolId)
                    ->whereRaw('YEAR(created_at) = ?', [now()->year])
                    ->whereRaw('MONTH(created_at) = ?', [now()->month])
                    ->groupBy('user_id'),
                'a2', 'a2.user_id', '=', 'users.id'
            )
            ->where('users.school_id', $schoolId)
            ->whereIn('users.role', ['teacher','head_teacher'])
            ->where('users.status', 'approved')
            ->addBinding($this->attService->countWorkDays(today()->startOfMonth()->toDateString(), today()->toDateString()), 'select')
            ->groupBy([
    'users.id',
    'users.first_name',
    'users.last_name',
    'users.check_number',
    'users.sex',
    'users.role',
    'users.status',
    'a2.days_count'
]);

        if ($statusFilter === 'present') $query->having('is_present', 1);
        if ($statusFilter === 'absent')  $query->having('is_present', 0);
        if ($search) {
            $query->where(fn($q) => $q
                ->where('users.first_name','like',"%{$search}%")
                ->orWhere('users.last_name','like',"%{$search}%")
                ->orWhere('users.check_number','like',"%{$search}%")
            );
        }

        $teachers = $query->orderByDesc('is_present')->orderBy('users.first_name')
            ->paginate($perPage)->withQueryString();

        // Hourly distribution (cached 2 min)
        $hourlyData = Cache::remember("att:hourly:{$schoolId}:{$selectedDate}", 120, function () use ($schoolId, $selectedDate) {
            $hourly = Attendance::where('school_id', $schoolId)
                ->whereDate('created_at', $selectedDate)
                ->selectRaw('HOUR(created_at) as h, COUNT(DISTINCT user_id) as cnt')
                ->groupBy('h')->orderBy('h')->pluck('cnt','h');
            $data = [];
            for ($h = 5; $h <= 18; $h++) {
                $data[] = ['hour' => sprintf('%02d:00',$h), 'count' => $hourly[$h] ?? 0];
            }
            return $data;
        });

        return view('headteacher.attendance', compact(
            'school','selectedDate','summary',
            'teachers','hourlyData','statusFilter','search','perPage',
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // APPROVALS
    // ─────────────────────────────────────────────────────────────────
    public function approvals(Request $request)
    {
        $school = $this->school();
        $tab    = $request->get('tab','pending');

        // Load each tab only when needed
        $pending  = $tab === 'pending'  ? User::where('school_id',$school->id)->where('role','teacher')->where('status','pending')->orderByDesc('created_at')->get() : collect();
        $approved = $tab === 'approved' ? User::where('school_id',$school->id)->where('role','teacher')->where('status','approved')->orderBy('first_name')->get() : collect();
        $rejected = $tab === 'rejected' ? User::where('school_id',$school->id)->where('role','teacher')->where('status','rejected')->orderBy('first_name')->get() : collect();

        // Counts for tab badges
        $counts = Cache::remember("ht:approval_counts:{$school->id}", 120, fn() => [
            'pending'  => User::where('school_id',$school->id)->where('role','teacher')->where('status','pending')->count(),
            'approved' => User::where('school_id',$school->id)->where('role','teacher')->where('status','approved')->count(),
            'rejected' => User::where('school_id',$school->id)->where('role','teacher')->where('status','rejected')->count(),
        ]);

        return view('headteacher.approvals', compact('school','pending','approved','rejected','tab','counts'));
    }

    public function approve(User $user)
    {
        $school = $this->school();
        abort_unless($user->school_id === $school->id && $user->role === 'teacher', 403);

        $user->update(['status' => 'approved']);

        // Bust cache
        Cache::forget("ht:teacher_counts:{$school->id}");
        Cache::forget("ht:teacher_counts_full:{$school->id}");
        Cache::forget("ht:approval_counts:{$school->id}");

        // Dispatch notification (queued)
        // ApprovalNotificationJob::dispatch($user); // uncomment when job exists

        return back()->with('success', "{$user->first_name} {$user->last_name} ameidhinishwa.");
    }

    public function reject(User $user)
    {
        $school = $this->school();
        abort_unless($user->school_id === $school->id && $user->role === 'teacher', 403);

        $user->update(['status' => 'rejected']);

        Cache::forget("ht:teacher_counts:{$school->id}");
        Cache::forget("ht:teacher_counts_full:{$school->id}");
        Cache::forget("ht:approval_counts:{$school->id}");

        return back()->with('error', "{$user->first_name} {$user->last_name} amekataliwa.");
    }

    // ─────────────────────────────────────────────────────────────────
    // REPORTS
    // ─────────────────────────────────────────────────────────────────
    public function reports(Request $request)
    {
        $school   = $this->school();
        $dateFrom = $request->get('date_from', today()->startOfMonth()->toDateString());
        $dateTo   = $request->get('date_to',   today()->toDateString());

        // Single optimized query for all teacher stats
        $teacherStats = $this->attService->schoolTeacherStats($school->id, $dateFrom, $dateTo);
        $workDays     = $this->attService->countWorkDays($dateFrom, $dateTo);

        $totalTeachers = $teacherStats->count();
        $overallRate   = $totalTeachers > 0
            ? round($teacherStats->avg('rate'), 1) : 0;

        $trend = $this->attService->dailyTrend('school', $school->id,
            min(60, Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1)
        );

        return view('headteacher.reports', compact(
            'school','dateFrom','dateTo',
            'teacherStats','workDays','totalTeachers','overallRate','trend',
        ));
    }

    public function exportCsv(Request $request)
    {
        $school   = $this->school();
        $dateFrom = $request->get('date_from', today()->startOfMonth()->toDateString());
        $dateTo   = $request->get('date_to',   today()->toDateString());
        $rows     = [
            ['"Ripoti ya Mahudhurio — ' . $school->name . '"', '"Kipindi: ' . $dateFrom . ' — ' . $dateTo . '"'],
            [],
            ['"#"','"Jina Kamili"','"Namba"','"Jinsia"','"Siku"','"Siku za Kazi"','"Kiwango %"'],
        ];

        $stats = $this->attService->schoolTeacherStats($school->id, $dateFrom, $dateTo);
        foreach ($stats as $i => $t) {
            $rows[] = [
                $i+1,
                '"'.trim($t->first_name.' '.$t->last_name).'"',
                '"'.$t->check_number.'"',
                $t->sex === 'female' ? '"Mwanamke"' : '"Mwanaume"',
                $t->days_present,
                $t->working_days,
                $t->rate.'%',
            ];
        }

        $csv = implode("\n", array_map(fn($r) => implode(',', $r), $rows));
        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"ripoti_{$school->name}_{$dateFrom}.csv\"",
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // CHECK-IN (rate limited)
    // ─────────────────────────────────────────────────────────────────
    public function checkIn(Request $request)
    {
        $ht = Auth::user();

        // Rate limiting: max 3 attempts per 5 minutes per user
        $key = "checkin:{$ht->id}";
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Umejaribu mara nyingi. Subiri sekunde {$seconds}.",
            ], 429);
        }
        RateLimiter::hit($key, 300);

        if ($ht->status !== 'approved') {
            return response()->json(['success'=>false,'message'=>'Akaunti haijaidhinishwa.'], 403);
        }
        if (!$ht->school_id) {
            return response()->json(['success'=>false,'message'=>'Huna shule.'], 403);
        }
        if (Attendance::where('user_id',$ht->id)->whereDate('created_at',today())->exists()) {
            return response()->json(['success'=>false,'message'=>'Umeshacheki-in leo!'], 409);
        }

        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy'  => 'nullable|numeric|min:0',
        ]);

        $school = $this->school();

        // GPS distance validation
        $distance = null;
        if ($school->latitude && $school->longitude) {
            $distance = $this->haversine(
                $request->latitude, $request->longitude,
                $school->latitude,  $school->longitude
            );
            if ($distance > $school->radius) {
                return response()->json([
                    'success'  => false,
                    'message'  => "Uko mbali sana ({$distance}m). Rudi shuleni ({$school->radius}m).",
                    'distance' => $distance,
                    'radius'   => $school->radius,
                ], 422);
            }
        }

        DB::transaction(function () use ($ht, $school, $request, $distance) {
            Attendance::create([
                'user_id'   => $ht->id,
                'school_id' => $school->id,
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
                'accuracy'  => $request->accuracy,
                'distance'  => $distance,
            ]);

            // Invalidate relevant cache
            $this->attService->invalidateAfterCheckIn($ht->id, $school->id);
        });

        RateLimiter::clear($key);

        return response()->json([
            'success'  => true,
            'message'  => '✅ Mahudhurio yamewekwa!',
            'time'     => now()->format('H:i'),
            'distance' => $distance,
        ]);
    }

    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $R    = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a    = sin($dLat/2)**2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;
        return (int) ($R * 2 * atan2(sqrt($a), sqrt(1-$a)));
    }
}