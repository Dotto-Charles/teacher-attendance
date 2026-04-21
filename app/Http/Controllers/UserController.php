<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Show pending teachers
    public function pending()
    {
        $user = auth()->user();

        // Only head teacher
        if ($user->role !== 'head_teacher') {
            abort(403);
        }

        $teachers = User::where('school_id', $user->school_id)
            ->where('status', 'pending')
            ->get();

        return view('users.pending', compact('teachers'));
    }

    // Approve teacher
    public function approve($id)
    {
        $teacher = User::findOrFail($id);

        $teacher->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Teacher approved');
    }

    // Reject teacher
    public function reject($id)
    {
        $teacher = User::findOrFail($id);

        $teacher->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Teacher rejected');
    }
}