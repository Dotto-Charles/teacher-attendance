<?php

namespace App\Http\Controllers\District;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\School;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DistrictWardController extends Controller
{
    private function councilId(): int
    {
        return Auth::user()->council_id;
    }

    public function index(Request $request)
    {
        $officer      = Auth::user();
        $councilId    = $this->councilId();
        $search       = $request->get('search');
        $perPage      = $request->get('per_page', 20);
        $today        = Carbon::today()->toDateString();

        $query = Ward::where('council_id', $councilId);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $wards = $query->orderBy('name')->paginate($perPage)->withQueryString();

        $wards->getCollection()->transform(function ($ward) use ($today) {
            $schoolIds = School::where('ward_id', $ward->id)->pluck('id');

            $ward->school_count = $schoolIds->count();
            $ward->teacher_count = User::whereIn('role', ['teacher', 'head_teacher'])
                ->where('status', 'approved')
                ->whereIn('school_id', $schoolIds)
                ->count();

            $ward->pending_teachers = User::whereIn('role', ['teacher', 'head_teacher'])
                ->where('status', 'pending')
                ->whereIn('school_id', $schoolIds)
                ->count();

            $ward->attended_today = Attendance::whereIn('school_id', $schoolIds)
                ->whereDate('created_at', $today)
                ->distinct('user_id')
                ->count('user_id');

            $ward->attendance_rate = $ward->teacher_count > 0
                ? round(($ward->attended_today / $ward->teacher_count) * 100, 1)
                : 0;

            $officer = User::where('role', 'ward_officer')
                ->where('ward_id', $ward->id)
                ->first();

            $ward->ward_officer = $officer ? $officer->full_name : null;

            return $ward;
        });

        $totalWards = Ward::where('council_id', $councilId)->count();
        $wardsWithOfficer = User::where('role', 'ward_officer')
            ->whereHas('ward', fn($q) => $q->where('council_id', $councilId))
            ->count();

        $totalTeachers = User::whereIn('role', ['teacher', 'head_teacher'])
            ->where('status', 'approved')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->count();

        $pendingTeachers = User::whereIn('role', ['teacher', 'head_teacher'])
            ->where('status', 'pending')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->count();

        return view('district.wards.index', compact(
            'officer',
            'wards',
            'totalWards',
            'wardsWithOfficer',
            'totalTeachers',
            'pendingTeachers',
            'search',
            'perPage'
        ));
    }
}
