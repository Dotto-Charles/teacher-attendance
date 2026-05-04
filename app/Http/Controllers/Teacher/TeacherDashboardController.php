<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $today   = Carbon::today();

        // ── Attendance stats ──────────────────────────────────────────
        $attendedToday = Attendance::where('user_id', $teacher->id)
            ->whereDate('created_at', $today)->exists();

        $todayRecord = Attendance::where('user_id', $teacher->id)
            ->whereDate('created_at', $today)->first();

        $monthCount = Attendance::where('user_id', $teacher->id)
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at',  $today->year)
            ->selectRaw('COUNT(DISTINCT DATE(created_at)) as days')
            ->value('days') ?? 0;

        $yearCount = Attendance::where('user_id', $teacher->id)
            ->whereYear('created_at', $today->year)
            ->selectRaw('COUNT(DISTINCT DATE(created_at)) as days')
            ->value('days') ?? 0;

        // Working days this month so far
        $workDaysMonth = 0;
        for ($i = 0; $i < $today->day; $i++) {
            if (Carbon::create($today->year, $today->month, 1)->addDays($i)->isWeekday())
                $workDaysMonth++;
        }
        $monthRate = $workDaysMonth > 0 ? round(($monthCount / $workDaysMonth) * 100) : 0;

        // ── Last 14 days ──────────────────────────────────────────────
        $recentDays = collect();
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $came = Attendance::where('user_id', $teacher->id)
                ->whereDate('created_at', $date)->exists();
            $recentDays->push([
                'date'    => $date->format('d'),
                'month'   => $date->format('M'),
                'day'     => $date->format('D'),
                'came'    => $came,
                'weekend' => $date->isWeekend(),
                'today'   => $date->isToday(),
                'future'  => $date->isFuture(),
            ]);
        }

        // ── School info ───────────────────────────────────────────────
        $school         = $teacher->school;
        $schoolTeachers = $school
            ? User::where('school_id', $school->id)->where('role', 'teacher')->where('status', 'approved')->count()
            : 0;
        $headTeacher = $school
            ? User::where('school_id', $school->id)->where('role', 'head_teacher')->first()
            : null;

        // ── Available schools (for registration) ──────────────────────
        $availableSchools = School::with('ward')
            ->where('is_active', 1)
            ->orderBy('name')->get();

        // ── Rank within school (attendance) ───────────────────────────
        $rank = null;
        if ($school && $teacher->status === 'approved') {
            $schoolmates = User::where('school_id', $school->id)
                ->where('role', 'teacher')->where('status', 'approved')->pluck('id');

            $leaderboard = Attendance::whereIn('user_id', $schoolmates)
                ->whereMonth('created_at', $today->month)
                ->whereYear('created_at',  $today->year)
                ->selectRaw('user_id, COUNT(DISTINCT DATE(created_at)) as days')
                ->groupBy('user_id')
                ->orderByDesc('days')
                ->pluck('user_id')->toArray();

            $pos = array_search($teacher->id, $leaderboard);
            $rank = $pos !== false ? $pos + 1 : null;
        }

        // ── Pending transfer ──────────────────────────────────────────
        $pendingTransfer = \App\Models\Transfer::where('user_id', $teacher->id)
            ->where('status', 'pending')->first();

        return view('dashboards.teacher', compact(
            'teacher',
            'attendedToday',
            'todayRecord',
            'monthCount',
            'yearCount',
            'monthRate',
            'workDaysMonth',
            'recentDays',
            'school',
            'schoolTeachers',
            'headTeacher',
            'availableSchools',
            'rank',
            'pendingTransfer',
        ));
    }

    // ── Quick check-in (GPS validation happens client-side + here) ────
    public function checkIn(Request $request)
    {
        $teacher = Auth::user();

        if ($teacher->status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Akaunti yako haijaidhinishwa bado.'], 403);
        }
        if (!$teacher->school_id) {
            return response()->json(['success' => false, 'message' => 'Hujajisajili kwenye shule yoyote.'], 403);
        }

        // Already checked in today?
        if (Attendance::where('user_id', $teacher->id)->whereDate('created_at', today())->exists()) {
            return response()->json(['success' => false, 'message' => 'Umeshacheki-in leo!'], 409);
        }

        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy'  => 'nullable|numeric',
        ]);

        $school = $teacher->school;

        // ── GPS distance check ────────────────────────────────────────
        if ($school->latitude && $school->longitude) {
            $distance = $this->haversine(
                $request->latitude,  $request->longitude,
                $school->latitude,   $school->longitude
            );

            if ($distance > $school->radius) {
                return response()->json([
                    'success'  => false,
                    'message'  => "Uko mbali sana na shule. Umbali wako: {$distance}m. Unaruhusiwa: {$school->radius}m.",
                    'distance' => $distance,
                    'radius'   => $school->radius,
                ], 422);
            }
        }

        Attendance::create([
            'user_id'   => $teacher->id,
            'school_id' => $school->id,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy'  => $request->accuracy,
            'distance'  => isset($distance) ? (int) $distance : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => '✅ Mahudhurio yamewekwa! Asante.',
            'time'    => now()->format('H:i'),
        ]);
    }

    // ── Haversine formula (meters) ────────────────────────────────────
    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $R    = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a    = sin($dLat/2)**2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;
        return (int) ($R * 2 * atan2(sqrt($a), sqrt(1-$a)));
    }
}