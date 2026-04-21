<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ward;
use App\Models\School;

class LocationController extends Controller
{
    public function wards($council_id)
    {
        return Ward::where('council_id', $council_id)->get();
    }

    public function schools($ward_id)
    {
        return School::where('ward_id', $ward_id)->get();
    }
}
