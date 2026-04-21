<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Council;
use App\Models\School;

class SchoolController extends Controller
{
    public function create()
    {
        return view('schools.register', [
            'councils' => Council::all()
        ]);
    }

    public function store(Request $request)
    {
        // ✅ VALIDATION STRICT
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $school = School::findOrFail($request->school_id);

        // 🚫 BLOCK IF LOCATION ALREADY EXISTS (STRONG)
        if ($school->latitude !== null || $school->longitude !== null) {
            return back()->with('error', '❌ School already has location. Cannot update.');
        }

        // ✅ SAVE
        $school->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('success', '✅ Location saved successfully');
    }
}