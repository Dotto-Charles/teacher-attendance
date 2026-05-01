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

class DistrictTeacherController extends Controller
{
    public function index(Request $request)
    {
        $officer   = Auth::user();
        $councilId = $officer->council_id;

        // ── FILTERS ─────────────────────────────────────────────────────
        $search   = $request->get('search');
        $wardId   = $request->get('ward_id');
        $schoolId = $request->get('school_id');
        $sex      = $request->get('sex');
        $status   = $request->get('status');
        $perPage  = $request->get('per_page', 20);

        // ── QUERY ────────────────────────────────────────────────────────
        $query = User::with(['school.ward'])
            ->where('role', 'teacher')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId));

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name',   'like', "%$search%")
                  ->orWhere('middle_name','like', "%$search%")
                  ->orWhere('last_name',  'like', "%$search%")
                  ->orWhere('check_number','like', "%$search%")
                  ->orWhere('email',      'like', "%$search%")
                  ->orWhere('phone',      'like', "%$search%");
            });
        }

        if ($wardId) {
            $query->whereHas('school', fn($q) => $q->where('ward_id', $wardId));
        }

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        if ($sex) {
            $query->where('sex', $sex);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $teachers = $query->orderBy('first_name')->paginate($perPage)->withQueryString();

        // ── ATTENDANCE STATS (last 30 days) for each teacher ─────────────
        $today     = Carbon::today();
        $month_ago = Carbon::today()->subDays(30);

        $attendanceMap = Attendance::whereBetween('created_at', [$month_ago, $today])
            ->whereIn('user_id', $teachers->pluck('id'))
            ->selectRaw('user_id, COUNT(DISTINCT DATE(created_at)) as days_count')
            ->groupBy('user_id')
            ->pluck('days_count', 'user_id');

        // Working days in last 30 days (Mon-Fri only, rough estimate)
        $workingDays = 0;
        for ($d = 0; $d < 30; $d++) {
            $day = Carbon::today()->subDays($d)->dayOfWeek;
            if ($day >= 1 && $day <= 5) $workingDays++;
        }

        // ── SUMMARY STATS ────────────────────────────────────────────────
        $totalTeachers   = User::where('role','teacher')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->count();
        $approvedCount   = User::where('role','teacher')->where('status','approved')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->count();
        $pendingCount    = User::where('role','teacher')->where('status','pending')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->count();
        $maleCount       = User::where('role','teacher')->where('sex','male')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->count();
        $femaleCount     = User::where('role','teacher')->where('sex','female')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->count();

        // Attended today
        $attendedToday = Attendance::whereDate('created_at', $today)
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->distinct('user_id')
            ->count('user_id');

        // ── WARDS & SCHOOLS for filters ──────────────────────────────────
        $wards   = Ward::where('council_id', $councilId)->orderBy('name')->get();
        $schools = School::whereHas('ward', fn($q) => $q->where('council_id', $councilId))
            ->when($wardId, fn($q) => $q->where('ward_id', $wardId))
            ->orderBy('name')
            ->get();

        return view('district.teachers', compact(
            'officer',
            'teachers',
            'attendanceMap',
            'workingDays',
            'totalTeachers',
            'approvedCount',
            'pendingCount',
            'maleCount',
            'femaleCount',
            'attendedToday',
            'wards',
            'schools',
            'search',
            'wardId',
            'schoolId',
            'sex',
            'status',
            'perPage',
        ));
    }

    /**
     * Idhinisha mwalimu (approve)
     */
    public function approve(User $user)
    {
        $officer   = Auth::user();
        $councilId = $officer->council_id;

        // Hakikisha mwalimu yupo chini ya halmashauri hii
        $belongs = $user->school?->ward?->council_id === $councilId;
        abort_unless($belongs, 403);

        $user->update(['status' => 'approved']);

        return back()->with('success', "Mwalimu {$user->first_name} {$user->last_name} ameidhinishwa.");
    }

    /**
     * Kataa mwalimu (reject)
     */
    public function reject(User $user)
    {
        $officer   = Auth::user();
        $councilId = $officer->council_id;

        $belongs = $user->school?->ward?->council_id === $councilId;
        abort_unless($belongs, 403);

        $user->update(['status' => 'rejected']);

        return back()->with('error', "Mwalimu {$user->first_name} {$user->last_name} amekataliwa.");
    }
}