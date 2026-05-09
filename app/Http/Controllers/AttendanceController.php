<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $school = School::find($user->school_id);

        return view('attendance.index', compact('school'));
    }

    public function check(Request $request)
    {
        try {

            $request->validate([
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            $user = Auth::user();

            if (!$user || !$user->school_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not assigned to school'
                ]);
            }

            $school = School::find($user->school_id);

            if (!$school || !$school->latitude || !$school->longitude) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'School location not set'
                ]);
            }

            // 🚫 BLOCK DOUBLE ATTENDANCE
            $already = Attendance::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->exists();

            if ($already) {
                return response()->json([
                    'status' => 'error',
                    'message' => '⚠️ Tayari umechukua attendance leo'
                ]);
            }


                    $key = "checkin_attempt:" . Auth::id();


 if (RateLimiter::tooManyAttempts($key, 3)) {
    $seconds = RateLimiter::availableIn($key);
      return response()->json([
          'success' => false,
          'message' => "Umejaribu mara nyingi. Subiri sekunde {$seconds}.",
 ], 429);
  }
 RateLimiter::hit($key, 300);

            // 📏 DISTANCE
            $distance = $this->distance(
                $request->latitude,
                $request->longitude,
                $school->latitude,
                $school->longitude
            );

            if ($distance > 500) {
                return response()->json([
                    'status' => 'error',
                    'message' => "❌ Upo mbali ({$distance}m)"
                ]);
            }

            Attendance::create([
                'user_id' => $user->id,
                'school_id' => $school->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => '✅ Attendance imechukuliwa'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REPORT (TEACHER + HEADTEACHER)
    |--------------------------------------------------------------------------
    */
    public function report(Request $request)
{
    $user = Auth::user();

    // HEAD TEACHER VIEW (ALL SCHOOL DATA)
    if ($user->role === 'head_teacher') {

        $query = Attendance::with(['user', 'school'])
            ->where('school_id', $user->school_id);

    } else {

        // TEACHER VIEW (OWN DATA ONLY)
        $query = Attendance::with(['user', 'school'])
            ->where('user_id', $user->id);
    }

    // FILTER BY DATE RANGE
    if ($request->start && $request->end) {
        $query->whereBetween('created_at', [
            Carbon::parse($request->start)->startOfDay(),
            Carbon::parse($request->end)->endOfDay()
        ]);
    }

    // FILTER BY MONTH
    if ($request->month) {
        $query->whereMonth('created_at', $request->month);
    }

    $attendances = $query->latest()->get();

    // STATS
    if ($user->role === 'head_teacher') {

        $today = Attendance::where('school_id', $user->school_id)
            ->whereDate('created_at', today())
            ->count();

        $month = Attendance::where('school_id', $user->school_id)
            ->whereMonth('created_at', now()->month)
            ->count();

        $totalTeachers = \App\Models\User::where('school_id', $user->school_id)
            ->where('role', 'teacher')
            ->count();

    } else {

        $today = Attendance::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        $month = Attendance::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->count();

        $totalTeachers = null;
    }

    return view('attendance.report', compact(
        'attendances',
        'today',
        'month',
        'totalTeachers'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | PDF EXPORT
    |--------------------------------------------------------------------------
    */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'head_teacher') {
            $query = Attendance::with('user')
                ->where('school_id', $user->school_id);
        } else {
            $query = Attendance::with('user')
                ->where('user_id', $user->id);
        }

        if ($request->start && $request->end) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start)->startOfDay(),
                Carbon::parse($request->end)->endOfDay(),
            ]);
        }

        if ($request->month) {
            $query->whereMonth('created_at', $request->month);
        }

        if (!$request->start && !$request->end && !$request->month) {
            if ($request->type === 'day') {
                $query->whereDate('created_at', today());
            }

            if ($request->type === 'week') {
                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
            }

            if ($request->type === 'month') {
                $query->whereMonth('created_at', now()->month);
            }
        }

        $attendances = $query->latest()->get();

        $params = $request->only(['start', 'end', 'month']);
        $filename = 'ripoti-mahudhurio';

        if (!empty($params['start']) && !empty($params['end'])) {
            $filename .= '-' . $params['start'] . '-to-' . $params['end'];
        } elseif (!empty($params['month'])) {
            $filename .= '-month-' . $params['month'];
        } else {
            $filename .= '-' . now()->format('Y-m-d');
        }

        $filename .= '.pdf';

        $pdf = Pdf::loadView('attendance.pdf', compact('attendances', 'params'));

        return $pdf->download($filename);
    }

    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $earth = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2)*sin($dLat/2) +
             cos(deg2rad($lat1))*cos(deg2rad($lat2))*
             sin($dLon/2)*sin($dLon/2);

        return round($earth * 2 * atan2(sqrt($a), sqrt(1-$a)));
    }
}