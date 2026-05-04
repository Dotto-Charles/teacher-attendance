<?php

namespace App\Http\Controllers\Ward;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\School;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class WardOfficerController extends Controller
{
    private function ward(): Ward
    {
        $user = Auth::user();
        abort_unless($user->ward_id, 403, 'Hauna kata iliyopangwa.');
        return Ward::findOrFail($user->ward_id);
    }

    private function schoolIds(): \Illuminate\Support\Collection
    {
        return School::where('ward_id', $this->ward()->id)->pluck('id');
    }

    // ─────────────────────────────────────────────────────────────────
    // DASHBOARD
    // ─────────────────────────────────────────────────────────────────
    public function dashboard(Request $request)
    {
        $ward      = $this->ward();
        $schoolIds = $this->schoolIds();
        $today     = Carbon::today();
        $selectedDate = $request->get('date', $today->toDateString());

        $totalSchools   = $schoolIds->count();
        $totalTeachers  = User::whereIn('school_id', $schoolIds)->where('role','teacher')->where('status','approved')->count();
        $pendingCount   = User::whereIn('school_id', $schoolIds)->where('role','teacher')->where('status','pending')->count();

        $presentToday   = Attendance::whereIn('school_id', $schoolIds)
            ->whereDate('created_at', $selectedDate)
            ->distinct('user_id')->count('user_id');
        $overallRate    = $totalTeachers > 0 ? round(($presentToday / $totalTeachers) * 100, 1) : 0;

        // Per-school attendance for selected date
        $schools = School::where('ward_id', $ward->id)->get()->map(function ($sc) use ($selectedDate) {
            $total   = User::where('school_id', $sc->id)->where('role','teacher')->where('status','approved')->count();
            $present = Attendance::where('school_id', $sc->id)->whereDate('created_at', $selectedDate)->distinct('user_id')->count('user_id');
            $rate    = $total > 0 ? round(($present / $total) * 100, 1) : 0;
            return ['id'=>$sc->id,'name'=>$sc->name,'total'=>$total,'present'=>$present,'absent'=>max(0,$total-$present),'rate'=>$rate];
        })->sortByDesc('rate')->values();

        // Last 7 days trend
        $trend = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $cnt  = Attendance::whereIn('school_id', $schoolIds)->whereDate('created_at', $date)->distinct('user_id')->count('user_id');
            $rate = $totalTeachers > 0 ? round(($cnt / $totalTeachers) * 100) : 0;
            $trend->push(['date' => $date->format('D, d M'), 'count' => $cnt, 'rate' => $rate]);
        }

        // Pending transfers
        $pendingTransfers = Transfer::with(['user','fromSchool','toSchool'])
            ->where('status','pending')
            ->whereHas('user', fn($q) => $q->whereIn('school_id', $schoolIds))
            ->count();

        return view('ward.dashboard', compact(
            'ward','schools','totalSchools','totalTeachers','pendingCount',
            'presentToday','overallRate','trend','selectedDate','pendingTransfers',
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // ATTENDANCE
    // ─────────────────────────────────────────────────────────────────
    public function attendanceIndex(Request $request)
    {
        $ward         = $this->ward();
        $schoolIds    = $this->schoolIds();
        $selectedDate = $request->get('date', Carbon::today()->toDateString());
        $schoolFilter = $request->get('school_id');
        $statusFilter = $request->get('status');
        $search       = $request->get('search');
        $perPage      = $request->get('per_page', 20);

        $presentIds = Attendance::whereIn('school_id', $schoolIds)
            ->whereDate('created_at', $selectedDate)
            ->distinct('user_id')->pluck('user_id');

        $checkInTimes = Attendance::whereIn('school_id', $schoolIds)
            ->whereDate('created_at', $selectedDate)
            ->whereIn('user_id', $presentIds)
            ->selectRaw('user_id, MIN(created_at) as t')->groupBy('user_id')
            ->pluck('t','user_id');

        $query = User::with(['school'])
            ->whereIn('school_id', $schoolIds)
            ->where('role','teacher')->where('status','approved');

        if ($schoolFilter) $query->where('school_id', $schoolFilter);
        if ($statusFilter === 'present') $query->whereIn('id', $presentIds);
        if ($statusFilter === 'absent')  $query->whereNotIn('id', $presentIds);
        if ($search) $query->where(fn($q) => $q->where('first_name','like',"%$search%")->orWhere('last_name','like',"%$search%")->orWhere('check_number','like',"%$search%"));

        $teachers = $query->orderBy('first_name')->paginate($perPage)->withQueryString();
        $teachers->getCollection()->transform(function ($t) use ($presentIds, $checkInTimes) {
            $t->is_present    = $presentIds->contains($t->id);
            $t->check_in_time = $t->is_present ? Carbon::parse($checkInTimes[$t->id])->format('H:i') : null;
            return $t;
        });

        $allTeachers  = User::whereIn('school_id', $schoolIds)->where('role','teacher')->where('status','approved')->count();
        $presentCount = $presentIds->count();
        $absentCount  = max(0, $allTeachers - $presentCount);
        $rate         = $allTeachers > 0 ? round(($presentCount / $allTeachers) * 100, 1) : 0;

        $absentList = User::with(['school'])->whereIn('school_id', $schoolIds)
            ->where('role','teacher')->where('status','approved')
            ->whereNotIn('id', $presentIds)->when($schoolFilter, fn($q) => $q->where('school_id',$schoolFilter))->get();

        $schools = School::where('ward_id', $ward->id)->orderBy('name')->get();

        $hourly = Attendance::whereIn('school_id', $schoolIds)->whereDate('created_at', $selectedDate)
            ->selectRaw('HOUR(created_at) as h, COUNT(DISTINCT user_id) as cnt')
            ->groupBy('h')->orderBy('h')->pluck('cnt','h');
        $hourlyData = [];
        for ($h=5; $h<=18; $h++) $hourlyData[] = ['hour'=>sprintf('%02d:00',$h),'count'=>$hourly[$h]??0];

        return view('ward.attendance', compact(
            'ward','teachers','schools','absentList',
            'allTeachers','presentCount','absentCount','rate',
            'hourlyData','selectedDate','schoolFilter','statusFilter','search','perPage',
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // SCHOOLS
    // ─────────────────────────────────────────────────────────────────
    public function schoolsIndex(Request $request)
    {
        $ward   = $this->ward();
        $search = $request->get('search');
        $today  = Carbon::today()->toDateString();

        $schools = School::where('ward_id', $ward->id)
            ->when($search, fn($q) => $q->where('name','like',"%$search%"))
            ->orderBy('name')->get()
            ->map(function ($sc) use ($today) {
                $sc->teacher_count   = User::where('school_id',$sc->id)->where('role','teacher')->where('status','approved')->count();
                $sc->pending_count   = User::where('school_id',$sc->id)->where('role','teacher')->where('status','pending')->count();
                $sc->present_today   = Attendance::where('school_id',$sc->id)->whereDate('created_at',$today)->distinct('user_id')->count('user_id');
                $sc->rate_today      = $sc->teacher_count > 0 ? round(($sc->present_today / $sc->teacher_count)*100,1) : 0;
                $sc->head_teacher    = User::where('school_id',$sc->id)->where('role','head_teacher')->first();
                return $sc;
            });

        return view('ward.schools', compact('ward','schools','search'));
    }

    // ─────────────────────────────────────────────────────────────────
    // TEACHERS
    // ─────────────────────────────────────────────────────────────────
    public function teachersIndex(Request $request)
    {
        $ward      = $this->ward();
        $schoolIds = $this->schoolIds();
        $search    = $request->get('search');
        $schoolId  = $request->get('school_id');
        $status    = $request->get('status');
        $sex       = $request->get('sex');
        $perPage   = $request->get('per_page', 20);

        $query = User::with(['school'])
            ->whereIn('school_id', $schoolIds)
            ->whereIn('role', ['teacher','head_teacher']);

        if ($search)   $query->where(fn($q) => $q->where('first_name','like',"%$search%")->orWhere('last_name','like',"%$search%")->orWhere('check_number','like',"%$search%"));
        if ($schoolId) $query->where('school_id', $schoolId);
        if ($status)   $query->where('status', $status);
        if ($sex)      $query->where('sex', $sex);

        $teachers = $query->orderBy('first_name')->paginate($perPage)->withQueryString();

        // 30-day attendance
        $attMap = Attendance::whereIn('school_id', $schoolIds)
            ->whereBetween('created_at', [Carbon::today()->subDays(30), Carbon::today()])
            ->selectRaw('user_id, COUNT(DISTINCT DATE(created_at)) as d')->groupBy('user_id')
            ->pluck('d','user_id');
        $workDays = 0;
        for ($i=0;$i<30;$i++) { if(Carbon::today()->subDays($i)->isWeekday()) $workDays++; }

        $teachers->getCollection()->transform(function ($t) use ($attMap,$workDays) {
            $t->att_days = $attMap[$t->id] ?? 0;
            $t->att_rate = $workDays > 0 ? round(($t->att_days/$workDays)*100) : 0;
            return $t;
        });

        $totalCount    = User::whereIn('school_id',$schoolIds)->whereIn('role',['teacher','head_teacher'])->count();
        $approvedCount = User::whereIn('school_id',$schoolIds)->whereIn('role',['teacher','head_teacher'])->where('status','approved')->count();
        $pendingCount  = User::whereIn('school_id',$schoolIds)->whereIn('role',['teacher','head_teacher'])->where('status','pending')->count();
        $maleCount     = User::whereIn('school_id',$schoolIds)->whereIn('role',['teacher','head_teacher'])->where('sex','male')->count();
        $femaleCount   = User::whereIn('school_id',$schoolIds)->whereIn('role',['teacher','head_teacher'])->where('sex','female')->count();

        $schools = School::where('ward_id', $ward->id)->orderBy('name')->get();

        return view('ward.teachers', compact(
            'ward','teachers','schools','workDays',
            'totalCount','approvedCount','pendingCount','maleCount','femaleCount',
            'search','schoolId','status','sex','perPage',
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // APPROVALS
    // ─────────────────────────────────────────────────────────────────
    public function approvalsIndex(Request $request)
    {
        $ward      = $this->ward();
        $schoolIds = $this->schoolIds();
        $tab       = $request->get('tab','pending');

        $pending  = User::with('school')->whereIn('school_id',$schoolIds)->where('role','teacher')->where('status','pending')->orderBy('created_at','desc')->get();
        $approved = User::with('school')->whereIn('school_id',$schoolIds)->where('role','teacher')->where('status','approved')->orderBy('first_name')->get();
        $rejected = User::with('school')->whereIn('school_id',$schoolIds)->where('role','teacher')->where('status','rejected')->orderBy('first_name')->get();

        return view('ward.approvals', compact('ward','pending','approved','rejected','tab'));
    }

    public function approveTeacher(User $user)
    {
        $schoolIds = $this->schoolIds();
        abort_unless($schoolIds->contains($user->school_id), 403);
        $user->update(['status'=>'approved']);
        return back()->with('success', "{$user->first_name} {$user->last_name} ameidhinishwa.");
    }

    public function rejectTeacher(User $user)
    {
        $schoolIds = $this->schoolIds();
        abort_unless($schoolIds->contains($user->school_id), 403);
        $user->update(['status'=>'rejected']);
        return back()->with('error', "{$user->first_name} {$user->last_name} amekataliwa.");
    }

    // ─────────────────────────────────────────────────────────────────
    // TRANSFERS
    // ─────────────────────────────────────────────────────────────────
    public function transfersIndex(Request $request)
    {
        $ward      = $this->ward();
        $schoolIds = $this->schoolIds();
        $tab       = $request->get('tab','pending');

        $pending = Transfer::with(['user.school','fromSchool','toSchool','requester'])
            ->where('status','pending')
            ->whereHas('user', fn($q) => $q->whereIn('school_id', $schoolIds))
            ->latest()->get();

        $history = Transfer::with(['user','fromSchool','toSchool'])
            ->whereIn('status',['approved','rejected'])
            ->whereHas('user', fn($q) => $q->whereIn('school_id', $schoolIds))
            ->latest()->limit(30)->get();

        $allSchools = School::whereHas('ward', fn($q) => $q->where('council_id', $ward->council_id ?? 0))->orderBy('name')->get();

        $teachers = User::with('school')->whereIn('school_id',$schoolIds)
            ->whereIn('role',['teacher','head_teacher'])->where('status','approved')
            ->orderBy('first_name')->get();

        return view('ward.transfers', compact('ward','pending','history','teachers','allSchools','tab'));
    }

    public function requestTransfer(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|exists:users,id',
            'to_school_id' => 'required|exists:schools,id',
            'reason'       => 'nullable|string|max:500',
        ]);

        $schoolIds = $this->schoolIds();
        $user      = User::findOrFail($request->user_id);
        abort_unless($schoolIds->contains($user->school_id), 403);

        if (Transfer::where('user_id',$user->id)->where('status','pending')->exists()) {
            return back()->with('error', 'Mwalimu huyu tayari ana ombi linalosubiri.');
        }

        Transfer::create([
            'user_id'       => $user->id,
            'from_school_id'=> $user->school_id,
            'to_school_id'  => $request->to_school_id,
            'requested_by'  => Auth::id(),
            'status'        => 'pending',
            'reason'        => $request->reason,
        ]);

        return back()->with('success', "Ombi la uhamisho limewasilishwa kwa afisa elimu wilaya.");
    }

    // ─────────────────────────────────────────────────────────────────
    // REPORTS
    // ─────────────────────────────────────────────────────────────────
    public function reportsIndex(Request $request)
    {
        $ward      = $this->ward();
        $schoolIds = $this->schoolIds();
        $dateFrom  = $request->get('date_from', Carbon::today()->startOfMonth()->toDateString());
        $dateTo    = $request->get('date_to',   Carbon::today()->toDateString());
        $schoolId  = $request->get('school_id');
        $from = Carbon::parse($dateFrom)->startOfDay();
        $to   = Carbon::parse($dateTo)->endOfDay();

        // Working days
        $workDays = 0; $d = $from->copy();
        while ($d->lte($to)) { if ($d->isWeekday()) $workDays++; $d->addDay(); }

        // Teachers summary
        $teachers = User::with('school')->whereIn('school_id', $schoolIds)
            ->when($schoolId, fn($q) => $q->where('school_id',$schoolId))
            ->where('role','teacher')->where('status','approved')->get();

        $attMap = Attendance::whereIn('school_id', $schoolIds)
            ->whereBetween('created_at',[$from,$to])
            ->selectRaw('user_id, COUNT(DISTINCT DATE(created_at)) as days')
            ->groupBy('user_id')->pluck('days','user_id');

        $teacherRows = $teachers->map(function ($t) use ($attMap,$workDays) {
            $days = $attMap[$t->id] ?? 0;
            $rate = $workDays > 0 ? round(($days/$workDays)*100,1) : 0;
            return ['name'=>$t->full_name,'check'=>$t->check_number,'school'=>$t->school->name??'—','days'=>$days,'work_days'=>$workDays,'rate'=>$rate,'sex'=>$t->sex];
        })->sortByDesc('rate')->values();

        $overallRate = $workDays > 0 && $teachers->count() > 0
            ? round(($attMap->sum() / ($teachers->count() * $workDays)) * 100, 1) : 0;

        // Per school
        $schoolReport = School::where('ward_id',$ward->id)
            ->when($schoolId, fn($q) => $q->where('id',$schoolId))->get()->map(function ($sc) use ($from,$to,$workDays) {
            $tc = User::where('school_id',$sc->id)->where('role','teacher')->where('status','approved')->count();
            $actual = Attendance::where('school_id',$sc->id)->whereBetween('created_at',[$from,$to])->selectRaw('COUNT(DISTINCT DATE(created_at), user_id) as cnt')->value('cnt')??0;
            $rate = ($tc*$workDays)>0 ? round(($actual/($tc*$workDays))*100,1) : 0;
            return ['name'=>$sc->name,'teachers'=>$tc,'rate'=>$rate];
        })->sortByDesc('rate')->values();

        // Daily trend
        $dailyTrend = [];
        $d = $from->copy();
        while ($d->lte($to) && count($dailyTrend)<=60) {
            if ($d->isWeekday()) {
                $cnt = Attendance::whereIn('school_id',$schoolIds)->whereDate('created_at',$d)->distinct('user_id')->count('user_id');
                $rate= $teachers->count()>0 ? round(($cnt/$teachers->count())*100) : 0;
                $dailyTrend[] = ['date'=>$d->format('d/m'),'count'=>$cnt,'rate'=>$rate];
            }
            $d->addDay();
        }

        $schools = School::where('ward_id',$ward->id)->orderBy('name')->get();

        return view('ward.reports', compact(
            'ward','schools','teacherRows','schoolReport','dailyTrend',
            'overallRate','workDays','dateFrom','dateTo','schoolId',
        ));
    }

    public function exportCsv(Request $request)
    {
        $ward      = $this->ward();
        $schoolIds = $this->schoolIds();
        $dateFrom  = $request->get('date_from', Carbon::today()->startOfMonth()->toDateString());
        $dateTo    = $request->get('date_to',   Carbon::today()->toDateString());
        $from = Carbon::parse($dateFrom)->startOfDay();
        $to   = Carbon::parse($dateTo)->endOfDay();

        $workDays = 0; $d = $from->copy();
        while ($d->lte($to)) { if ($d->isWeekday()) $workDays++; $d->addDay(); }

        $teachers = User::with('school')->whereIn('school_id',$schoolIds)->where('role','teacher')->where('status','approved')->orderBy('first_name')->get();
        $attMap   = Attendance::whereIn('school_id',$schoolIds)->whereBetween('created_at',[$from,$to])->selectRaw('user_id, COUNT(DISTINCT DATE(created_at)) as days')->groupBy('user_id')->pluck('days','user_id');

        $rows = [['"Ripoti ya Mahudhurio — Kata ya '.$ward->name.'"',"\"Kipindi: {$dateFrom} — {$dateTo}\""]];
        $rows[] = [];
        $rows[] = ['"#"','"Jina"','"Namba"','"Jinsia"','"Shule"','"Siku"','"Siku za Kazi"','"Kiwango %"'];
        foreach ($teachers as $i => $t) {
            $days = $attMap[$t->id]??0;
            $rate = $workDays>0 ? round(($days/$workDays)*100,1) : 0;
            $rows[] = [$i+1,'"'.$t->full_name.'"','"'.$t->check_number.'"',$t->sex==='female'?'"Mwanamke"':'"Mwanaume"','"'.($t->school->name??'—').'"',$days,$workDays,$rate.'%'];
        }
        $csv = implode("\n", array_map(fn($r)=>implode(',',$r), $rows));
        return response($csv,200,['Content-Type'=>'text/csv; charset=UTF-8','Content-Disposition'=>"attachment; filename=\"ripoti_{$ward->name}_{$dateFrom}.csv\""]);
    }

    public function exportAttendanceCsv(Request $request)
{
    $schoolIds    = $this->schoolIds();
    $selectedDate = $request->get('date', now()->toDateString());

    $teachers = User::with('school')
        ->whereIn('school_id', $schoolIds)
        ->where('role', 'teacher')
        ->where('status', 'approved')
        ->orderBy('first_name')
        ->get();

    $presentIds = Attendance::whereIn('school_id', $schoolIds)
        ->whereDate('created_at', $selectedDate)
        ->distinct()
        ->pluck('user_id');

    $rows = [
        ['#', 'Jina', 'Namba', 'Shule', 'Status']
    ];

    foreach ($teachers as $i => $t) {
        $status = $presentIds->contains($t->id) ? 'Present' : 'Absent';

        $rows[] = [
            $i + 1,
            $t->full_name,
            $t->check_number,
            $t->school->name ?? '-',
            $status
        ];
    }

    $csv = implode("\n", array_map(fn($r) => implode(',', $r), $rows));

    return response($csv, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=attendance_{$selectedDate}.csv"
    ]);
}

public function schoolShow(Request $request, School $school)
{
    $ward = $this->ward();

    // Hakikisha school ni ya ward hii
    abort_unless($school->ward_id == $ward->id, 403);

    $today = now()->toDateString();

    $teachers = User::where('school_id', $school->id)
        ->where('role', 'teacher')
        ->where('status', 'approved')
        ->get();

    $presentIds = Attendance::where('school_id', $school->id)
        ->whereDate('created_at', $today)
        ->distinct('user_id')
        ->pluck('user_id');

    $presentCount = $presentIds->count();
    $totalTeachers = $teachers->count();
    $absentCount = max(0, $totalTeachers - $presentCount);
    $rate = $totalTeachers > 0 ? round(($presentCount / $totalTeachers) * 100, 1) : 0;

    // Trend last 7 days
    $trend = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i);
        $count = Attendance::where('school_id', $school->id)
            ->whereDate('created_at', $date)
            ->distinct('user_id')
            ->count('user_id');

        $trend[] = [
            'date' => $date->format('D'),
            'count' => $count
        ];
    }

    $alerts = [];

foreach ($teachers as $t) {
    $days = Attendance::where('user_id',$t->id)
        ->whereBetween('created_at',[now()->subDays(30), now()])
        ->distinct()
        ->count('created_at');

    $rate = $days > 0 ? ($days / 22) * 100 : 0;

    if ($rate < 50) {
        $alerts[] = [
            'name' => $t->full_name,
            'rate' => round($rate,1)
        ];
    }
}

    return view('ward.school-show', compact(
        'ward',
        'school',
        'teachers',
        'presentIds',
        'presentCount',
        'totalTeachers',
        'absentCount',
        'rate',
        'trend',
        'alerts'
    ));
}



public function exportSchoolPdf(School $school)
{
    $ward = $this->ward();
    abort_unless($school->ward_id == $ward->id, 403);

    $today = now()->toDateString();

    $teachers = User::where('school_id', $school->id)
        ->where('role', 'teacher')
        ->where('status', 'approved')
        ->get();

    $presentIds = Attendance::where('school_id', $school->id)
        ->whereDate('created_at', $today)
        ->distinct()
        ->pluck('user_id');

    $data = compact('ward','school','teachers','presentIds','today');

    $pdf = Pdf::loadView('ward.school-pdf', $data);

    return $pdf->download("school_report_{$school->name}.pdf");
}

public function teacherHistory(User $user)
{
    $ward = $this->ward();

    abort_unless($user->school && $user->school->ward_id == $ward->id, 403);

    $records = Attendance::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->limit(30)
        ->get()
        ->groupBy(function($a){
            return \Carbon\Carbon::parse($a->created_at)->format('Y-m-d');
        });

    return view('ward.teacher-history', compact('user','records'));
}
}
