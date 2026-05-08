<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    /**
     * Show school location form.
     * Head Teacher anaona shule yake tu.
     */
    public function create()
    {
        $user = Auth::user();

        // Hakikisha ni head_teacher
        abort_unless($user->role === 'head_teacher', 403, 'Ni kwa Walimu Wakuu tu.');
        abort_unless($user->school_id, 403, 'Huna shule iliyopangwa.');

        // Pata shule ya HT hii peke yake
        $school = School::with('ward.council')->findOrFail($user->school_id);

        return view('schools.register', compact('school'));
    }

    /**
     * Save school GPS location.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->role === 'head_teacher', 403);
        abort_unless($user->school_id, 403);

        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius'    => 'nullable|integer|min:50|max:5000',
        ]);

        $school = School::findOrFail($user->school_id);

        $school->update([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'radius'    => $request->radius ?? $school->radius,
        ]);

        return redirect()->route('schools.create')
            ->with('success', "✅ Eneo la {$school->name} limehifadhiwa! ({$request->latitude}, {$request->longitude})");
    }
}