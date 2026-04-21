<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Attendance;

class DashboardController extends Controller
{
    public function index()
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
        | HEAD TEACHER DASHBOARD (WITH STATS)
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'head_teacher') {

            // 🚨 hakikisha ana school
            if (!$user->school_id) {
                abort(403, 'No school assigned');
            }

            $schoolId = $user->school_id;

            // 📊 TOTAL TEACHERS
            $totalTeachers = User::where('role', 'teacher')
                ->where('school_id', $schoolId)
                ->count();

            // ⏳ PENDING
            $pending = User::where('role', 'teacher')
                ->where('school_id', $schoolId)
                ->where('status', 'pending')
                ->count();

            // ✅ APPROVED
            $approved = User::where('role', 'teacher')
                ->where('school_id', $schoolId)
                ->where('status', 'approved')
                ->count();

            // 📅 TODAY ATTENDANCE
            $todayAttendance = Attendance::where('school_id', $schoolId)
                ->whereDate('created_at', now())
                ->count();

            return view('dashboards.head_teacher', compact(
                'totalTeachers',
                'pending',
                'approved',
                'todayAttendance'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | OFFICER DASHBOARD
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'officer') {
            return view('dashboards.officer');
        }

        /*
        |--------------------------------------------------------------------------
        | UNKNOWN ROLE
        |--------------------------------------------------------------------------
        */
        abort(403, 'Unauthorized role');
    }
}