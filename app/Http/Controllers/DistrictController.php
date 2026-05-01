<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ward;
use App\Models\School;

class DistrictController extends Controller
{
    // dashboard page
    public function index()
    {
        $wards = Ward::all();
        $users = User::whereIn('role', ['teacher', 'head_teacher'])->get();

        return view('dashboards.district', compact('wards', 'users'));
    }

    // AJAX: get schools by ward
    public function getSchools($ward_id)
    {
        return School::where('ward_id', $ward_id)->get();
    }

    // AJAX: search teachers/head teachers
    public function searchUsers(Request $request)
    {
        $q = $request->q;

        return User::whereIn('role', ['teacher', 'head_teacher'])
            ->where(function ($query) use ($q) {
                $query->where('first_name', 'like', "%$q%")
                      ->orWhere('last_name', 'like', "%$q%")
                      ->orWhere('check_number', 'like', "%$q%");
            })
            ->limit(10)
            ->get();
    }

    // ASSIGN ROLE (CORE LOGIC)
    public function assign(Request $request)
    {
        $request->validate([
            'type' => 'required', // ward_officer | head_teacher
            'user_id' => 'required',
        ]);

        $user = User::findOrFail($request->user_id);

        // =========================
        // WARD OFFICER ASSIGNMENT
        // =========================
        if ($request->type == 'ward_officer') {

            if (!$request->ward_id) {
                return back()->with('error', 'Select ward first');
            }

            // prevent duplicate ward officer
            $exists = User::where('ward_id', $request->ward_id)
                ->where('role', 'ward_officer')
                ->where('id', '!=', $user->id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Ward already has officer');
            }

            $user->ward_id = $request->ward_id;
            $user->school_id = null;
            $user->role = 'ward_officer';
        }

        // =========================
        // HEAD TEACHER ASSIGNMENT
        // =========================
        if ($request->type == 'head_teacher') {

            if (!$request->school_id) {
                return back()->with('error', 'Select school first');
            }

            // prevent duplicate head teacher per school
            $exists = User::where('school_id', $request->school_id)
                ->where('role', 'head_teacher')
                ->where('id', '!=', $user->id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'School already has head teacher');
            }

            $user->school_id = $request->school_id;
            $user->ward_id = $request->ward_id;
            $user->role = 'head_teacher';
        }

        $user->save();

        return back()->with('success', 'Assigned successfully');
    }
}