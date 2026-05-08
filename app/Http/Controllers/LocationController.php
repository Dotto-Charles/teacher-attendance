<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ward;
use App\Models\School;

class LocationController extends Controller
{
    use Illuminate\Support\Facades\Cache;

public function wards($councilId)
{
    return Cache::remember(
        "wards:council:$councilId",
        600, // 10 minutes
        function () use ($councilId) {
            return Ward::select('id', 'name')
                ->where('council_id', $councilId)
                ->orderBy('name')
                ->get();
        }
    );
}

    use Illuminate\Support\Facades\Cache;

public function schools($wardId)
{
    return Cache::remember(
        "schools:ward:$wardId",
        600,
        function () use ($wardId) {
            return School::select('id','name','latitude','longitude')
                ->where('ward_id', $wardId)
                ->orderBy('name')
                ->get();
        }
    );
}
}
