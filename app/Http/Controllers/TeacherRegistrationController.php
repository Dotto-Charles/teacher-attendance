<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Council;
use App\Models\School;

class TeacherRegistrationController extends Controller
{
    public function create()
    {
        return view('teacher.register-school', [
            'councils' => Council::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required'
        ]);

        $user = auth()->user();

        // 🚫 already pending
        if ($user->status == 'pending') {
            return back()->with('error', 'Unasubiri approval');
        }

        // 🔄 transfer or first time
        $user->update([
            'school_id' => $request->school_id,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Ombi limetumwa, subiri approval');
    }
}