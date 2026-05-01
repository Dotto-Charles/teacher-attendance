<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ward;

class WardOfficerAssignmentController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->get();
        $wards = Ward::all();

        $assigned = User::where('role', 'ward_officer')
            ->with('ward')
            ->get();

        return view('admin.assign_ward_officer', compact(
            'teachers',
            'wards',
            'assigned'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ward_id' => 'required|exists:wards,id',
        ]);

        $user = User::findOrFail($request->user_id);

        $user->update([
            'role' => 'ward_officer',
            'ward_id' => $request->ward_id
        ]);

        return back()->with('success', 'Teacher assigned as Ward Officer successfully');
    }
}