<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Council;
use App\Models\Ward;
use App\Models\School;

class TeacherRegistrationController extends Controller
{
    public function create()
    {
        return view('teacher.register-school', [
            'councils' => Council::select('id','name')->orderBy('name')->get(),
            'wards'    => Ward::select('id','name','council_id')->orderBy('name')->get(),
            'schools'  => School::select('id','name','ward_id')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_id' => ['required', 'exists:schools,id']
        ]);

        $user = auth()->user();

        if ($user->status === 'pending') {
            return back()->with('error', 'Unasubiri approval');
        }

        if ($user->school_id == $request->school_id) {
            return back()->with('error', 'Tayari uko kwenye hii shule');
        }

        $user->update([
            'school_id' => $request->school_id,
            'status'    => 'pending'
        ]);

        return back()->with('success', 'Ombi limetumwa, subiri approval');
    }
}