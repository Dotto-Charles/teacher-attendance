<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    // 📋 LIST OF PENDING TEACHERS
    public function index()
    {
        $headmaster = auth()->user();

        // 🚫 only headmaster
        if ($headmaster->role !== 'head_teacher') {
            abort(403);
        }

        // 🚫 must have school
        if (!$headmaster->school_id) {
            abort(403, 'Headmaster has no school');
        }

        // 🔥 GET ONLY TEACHERS OF SAME SCHOOL
        $teachers = User::with('school')
            ->where('role', 'teacher')
            ->where('status', 'pending')
            ->where('school_id', $headmaster->school_id)
            ->latest()
            ->get();

        return view('approvals.index', compact('teachers'));
    }

    // ✅ APPROVE
    public function approve($id)
    {
        $headmaster = auth()->user();

        $teacher = User::findOrFail($id);

        // 🚫 SECURITY CHECK
        if ($teacher->school_id != $headmaster->school_id) {
            abort(403);
        }

        $teacher->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Teacher approved');
    }

    // ❌ REJECT
    public function reject($id)
    {
        $headmaster = auth()->user();

        $teacher = User::findOrFail($id);

        // 🚫 SECURITY CHECK
        if ($teacher->school_id != $headmaster->school_id) {
            abort(403);
        }

        $teacher->update([
            'status' => 'pending',
            'school_id' => null
        ]);

        return back()->with('error', 'Teacher rejected');
    }
}