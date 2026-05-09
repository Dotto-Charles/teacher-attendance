<?php

namespace App\Http\Controllers\District;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\School;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistrictReportController extends Controller
{
    private function councilId(): int { return Auth::user()->council_id; }

    public function index(Request $request)
    {
        $officer    = Auth::user();
        $councilId  = $this->councilId();
        $dateFrom   = $request->get('date_from', Carbon::today()->startOfMonth()->toDateString());
        $dateTo     = $request->get('date_to',   Carbon::today()->toDateString());
        $reportType = $request->get('report_type', 'attendance');
        $wardId     = $request->get('ward_id');
        $schoolId   = $request->get('school_id');
        $from = Carbon::parse($dateFrom)->startOfDay();
        $to   = Carbon::parse($dateTo)->endOfDay();

        $wards   = Ward::where('council_id', $councilId)->orderBy('name')->get();
        $schools = School::whereHas('ward', fn($q) => $q->where('council_id', $councilId))
            ->when($wardId, fn($q) => $q->where('ward_id', $wardId))
            ->orderBy('name')->get();

        $attendanceReport = $reportType === 'attendance' ? $this->buildAttendanceReport($councilId, $from, $to, $wardId, $schoolId) : null;
        $teachersReport   = $reportType === 'teachers'   ? $this->buildTeachersReport($councilId, $wardId, $schoolId) : null;
        $schoolsReport    = $reportType === 'schools'    ? $this->buildSchoolsReport($councilId, $from, $to, $wardId) : null;
        $wardsReport      = $reportType === 'wards'      ? $this->buildWardsReport($councilId, $from, $to) : null;
        $transfersReport  = $reportType === 'transfers'  ? $this->buildTransfersReport($councilId, $from, $to) : null;

        $pendingTeachers = User::whereIn('role',['teacher','head_teacher'])->where('status','pending')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))->count();

        return view('district.reports.index', compact(
            'officer','wards','schools','dateFrom','dateTo','reportType',
            'wardId','schoolId','attendanceReport','teachersReport',
            'schoolsReport','wardsReport','transfersReport','pendingTeachers',
        ));
    }

    public function exportCsv(Request $request)
    {
        $councilId  = $this->councilId();
        $reportType = $request->get('report_type','attendance');
        $dateFrom   = $request->get('date_from', Carbon::today()->startOfMonth()->toDateString());
        $dateTo     = $request->get('date_to',   Carbon::today()->toDateString());
        $wardId     = $request->get('ward_id');
        $schoolId   = $request->get('school_id');
        $from = Carbon::parse($dateFrom)->startOfDay();
        $to   = Carbon::parse($dateTo)->endOfDay();
        $rows = [];
        $filename = "ripoti_{$reportType}_{$dateFrom}_{$dateTo}.csv";

        switch ($reportType) {
            case 'attendance':
                $d = $this->buildAttendanceReport($councilId, $from, $to, $wardId, $schoolId);
                $rows[] = ['"Ripoti ya Mahudhurio"', "\"Kipindi: {$dateFrom} hadi {$dateTo}\"",'','','','','','"Kiwango Ujumla: '.$d['overall_rate'].'%"'];
                $rows[] = [];
                $rows[] = ['"#"','"Jina Kamili"','"Namba"','"Jinsia"','"Shule"','"Kata"','"Siku Alikuja"','"Siku za Kazi"','"Kiwango %"'];
                foreach ($d['teachers'] as $i => $t) {
                    $rows[] = [$i+1,'"'.$t['name'].'"','"'.$t['check'].'"',$t['sex']==='female'?'"Mwanamke"':'"Mwanaume"','"'.$t['school'].'"','"'.$t['ward'].'"',$t['days_present'],$t['working_days'],$t['rate'].'%'];
                }
                break;
            case 'teachers':
                $d = $this->buildTeachersReport($councilId, $wardId, $schoolId);
                $rows[] = ['"Ripoti ya Walimu"'];
                $rows[] = [];
                $rows[] = ['"#"','"Jina Kamili"','"Namba"','"Jinsia"','"Shule"','"Kata"','"Hali"','"Nafasi"','"Tarehe"'];
                foreach ($d['teachers'] as $i => $t) {
                    $rows[] = [$i+1,'"'.$t->full_name.'"','"'.$t->check_number.'"',$t->sex==='female'?'"Mwanamke"':'"Mwanaume"','"'.($t->school->name??'—').'"','"'.($t->school->ward->name??'—').'"','"'.$t->status.'"','"'.$t->role.'"','"'.($t->created_at?$t->created_at->format('d/m/Y'):'—').'"'];
                }
                break;
            case 'schools':
                $d = $this->buildSchoolsReport($councilId, $from, $to, $wardId);
                $rows[] = ['"Ripoti ya Shule"', "\"Kipindi: {$dateFrom} hadi {$dateTo}\""];
                $rows[] = [];
                $rows[] = ['"#"','"Shule"','"Kata"','"Walimu"','"Siku za Data"','"Wastani Mahudhurio"','"Kiwango %"','"Hali"','"GPS"'];
                foreach ($d['schools'] as $i => $s) {
                    $rows[] = [$i+1,'"'.$s['name'].'"','"'.$s['ward'].'"',$s['teacher_count'],$s['days_with_data'],$s['avg_attendance'],$s['avg_rate'].'%',$s['is_active']?'"Inafanya kazi"':'"Imezimwa"',$s['has_gps']?'"✅"':'"—"'];
                }
                break;
            case 'wards':
                $d = $this->buildWardsReport($councilId, $from, $to);
                $rows[] = ['"Ripoti ya Kata"', "\"Kipindi: {$dateFrom} hadi {$dateTo}\""];
                $rows[] = [];
                $rows[] = ['"#"','"Kata"','"Shule"','"Walimu"','"Ward Officer"','"Wastani %"'];
                foreach ($d['wards'] as $i => $w) {
                    $rows[] = [$i+1,'"'.$w['name'].'"',$w['school_count'],$w['teacher_count'],'"'.$w['ward_officer'].'"',$w['avg_rate'].'%'];
                }
                break;
            case 'transfers':
                $d = $this->buildTransfersReport($councilId, $from, $to);
                $rows[] = ['"Ripoti ya Uhamisho"', "\"Kipindi: {$dateFrom} hadi {$dateTo}\""];
                $rows[] = [];
                $rows[] = ['"#"','"Mwalimu"','"Kutoka"','"Kwenda"','"Hali"','"Omliwasilisha"','"Tarehe"'];
                foreach ($d['transfers'] as $i => $t) {
                    $rows[] = [$i+1,'"'.($t->user->full_name??'—').'"','"'.($t->fromSchool->name??'—').'"','"'.($t->toSchool->name??'—').'"','"'.$t->status.'"','"'.($t->requester->full_name??'—').'"','"'.$t->created_at->format('d/m/Y').'"'];
                }
                break;
        }
        $csv = implode("\n", array_map(fn($r) => implode(',', $r), $rows));
        return response($csv, 200, ['Content-Type'=>'text/csv; charset=UTF-8','Content-Disposition'=>"attachment; filename=\"{$filename}\""]);
    }

    public function exportPdf(Request $request)
    {
        $councilId  = $this->councilId();
        $officer    = Auth::user();
        $reportType = $request->get('report_type','attendance');
        $dateFrom   = $request->get('date_from', Carbon::today()->startOfMonth()->toDateString());
        $dateTo     = $request->get('date_to',   Carbon::today()->toDateString());
        $wardId     = $request->get('ward_id');
        $schoolId   = $request->get('school_id');
        $from = Carbon::parse($dateFrom)->startOfDay();
        $to   = Carbon::parse($dateTo)->endOfDay();

        $data = match($reportType) {
            'attendance' => $this->buildAttendanceReport($councilId, $from, $to, $wardId, $schoolId),
            'teachers'   => $this->buildTeachersReport($councilId, $wardId, $schoolId),
            'schools'    => $this->buildSchoolsReport($councilId, $from, $to, $wardId),
            'wards'      => $this->buildWardsReport($councilId, $from, $to),
            'transfers'  => $this->buildTransfersReport($councilId, $from, $to),
            default      => [],
        };

        $councilName = $officer->council->name ?? 'Halmashauri';
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('district.reports.pdf', compact(
            'officer','reportType','dateFrom','dateTo','councilName','data',
        ))->setPaper('a4','landscape');

        return $pdf->download("ripoti_{$reportType}_{$dateFrom}_{$dateTo}.pdf");
    }

    // ── BUILDERS ─────────────────────────────────────────────────────

    private function buildAttendanceReport($councilId,$from,$to,$wardId,$schoolId): array
    {
        $workingDays = 0;
        $d = $from->copy();
        while ($d->lte($to)) { if ($d->isWeekday()) $workingDays++; $d->addDay(); }

        $teacherRoles = ['teacher','head_teacher'];
        $teachers = User::with(['school.ward'])->whereIn('role',$teacherRoles)->where('status','approved')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->when($wardId,   fn($q) => $q->whereHas('school', fn($q2) => $q2->where('ward_id', $wardId)))
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->orderBy('first_name')->get();

        $attMap = Attendance::whereBetween('created_at',[$from,$to])->whereIn('user_id',$teachers->pluck('id'))
            ->selectRaw('user_id, COUNT(DISTINCT DATE(created_at)) as days')->groupBy('user_id')->pluck('days','user_id');

        $rows = $teachers->map(function ($t) use ($attMap,$workingDays) {
            $days = $attMap[$t->id] ?? 0;
            $rate = $workingDays > 0 ? round(($days/$workingDays)*100,1) : 0;
            return ['name'=>$t->full_name,'check'=>$t->check_number,'school'=>$t->school->name??'—','ward'=>$t->school->ward->name??'—','sex'=>$t->sex,'days_present'=>$days,'working_days'=>$workingDays,'rate'=>$rate];
        });

        $totalActual = $attMap->sum(); $totalExpected = $teachers->count() * $workingDays;
        $overallRate = $totalExpected > 0 ? round(($totalActual/$totalExpected)*100,1) : 0;

        $dailyTrend = [];
        $d = $from->copy();
        while ($d->lte($to) && count($dailyTrend) <= 60) {
            if ($d->isWeekday()) {
                $cnt = Attendance::whereDate('created_at',$d->toDateString())
                    ->whereHas('school.ward', fn($q) => $q->where('council_id',$councilId))
                    ->when($wardId,   fn($q) => $q->whereHas('school', fn($q2) => $q2->where('ward_id',$wardId)))
                    ->when($schoolId, fn($q) => $q->where('school_id',$schoolId))
                    ->distinct('user_id')->count('user_id');
                $rate = $teachers->count() > 0 ? round(($cnt/$teachers->count())*100) : 0;
                $dailyTrend[] = ['date'=>$d->format('d/m'),'count'=>$cnt,'rate'=>$rate];
            }
            $d->addDay();
        }

        return ['teachers'=>$rows,'total'=>$teachers->count(),'working_days'=>$workingDays,'overall_rate'=>$overallRate,'daily_trend'=>$dailyTrend,'top_present'=>$rows->sortByDesc('rate')->take(5)->values(),'top_absent'=>$rows->sortBy('rate')->take(5)->values()];
    }

    private function buildTeachersReport($councilId,$wardId,$schoolId): array
    {
        $teachers = User::with(['school.ward'])->whereIn('role',['teacher','head_teacher'])
            ->whereHas('school.ward', fn($q) => $q->where('council_id',$councilId))
            ->when($wardId,   fn($q) => $q->whereHas('school', fn($q2) => $q2->where('ward_id',$wardId)))
            ->when($schoolId, fn($q) => $q->where('school_id',$schoolId))
            ->orderBy('first_name')->get();
        return ['teachers'=>$teachers,'total'=>$teachers->count(),'approved'=>$teachers->where('status','approved')->count(),'pending'=>$teachers->where('status','pending')->count(),'rejected'=>$teachers->where('status','rejected')->count(),'male'=>$teachers->where('sex','male')->count(),'female'=>$teachers->where('sex','female')->count(),'head_teachers'=>$teachers->where('role','head_teacher')->count(),'by_school'=>$teachers->groupBy(fn($t)=>$t->school->name??'—')->map(fn($g)=>$g->count())->sortDesc()];
    }

    private function buildSchoolsReport($councilId,$from,$to,$wardId): array
    {
        $schools = School::with('ward')->whereHas('ward', fn($q) => $q->where('council_id',$councilId))
            ->when($wardId, fn($q) => $q->where('ward_id',$wardId))->orderBy('name')->get();
        $workingDays = 0; $d = $from->copy();
        while ($d->lte($to)) { if ($d->isWeekday()) $workingDays++; $d->addDay(); }

        $rows = $schools->map(function ($sc) use ($from,$to,$workingDays) {
            $teachers = User::where('school_id',$sc->id)->whereIn('role',['teacher','head_teacher'])->where('status','approved')->count();
            $daysWithData = Attendance::where('school_id',$sc->id)->whereBetween('created_at',[$from,$to])->selectRaw('COUNT(DISTINCT DATE(created_at)) as days')->value('days') ?? 0;
            $avgAttendance = $daysWithData > 0 ? round(Attendance::where('school_id',$sc->id)->whereBetween('created_at',[$from,$to])->selectRaw('DATE(created_at) as d, COUNT(DISTINCT user_id) as cnt')->groupBy('d')->get()->avg('cnt'),1) : 0;
            $expected = $teachers * $workingDays;
            $actual = Attendance::where('school_id',$sc->id)->whereBetween('created_at',[$from,$to])->selectRaw('COUNT(DISTINCT DATE(created_at), user_id) as cnt')->value('cnt') ?? 0;
            $avgRate = $expected > 0 ? round(($actual/$expected)*100,1) : 0;
            return ['id'=>$sc->id,'name'=>$sc->name,'ward'=>$sc->ward->name??'—','teacher_count'=>$teachers,'days_with_data'=>$daysWithData,'avg_attendance'=>$avgAttendance,'avg_rate'=>$avgRate,'is_active'=>$sc->is_active,'has_gps'=>!is_null($sc->latitude)];
        });

        return ['schools'=>$rows,'total'=>$schools->count(),'active'=>$schools->where('is_active',1)->count(),'with_gps'=>$schools->whereNotNull('latitude')->count(),'working_days'=>$workingDays,'avg_rate'=>$rows->count()>0?round($rows->avg('avg_rate'),1):0,'top_schools'=>$rows->sortByDesc('avg_rate')->take(5)->values(),'low_schools'=>$rows->sortBy('avg_rate')->take(5)->values()];
    }

    private function buildWardsReport($councilId,$from,$to): array
    {
        $wards = Ward::where('council_id',$councilId)->orderBy('name')->get();
        $workingDays = 0; $d = $from->copy();
        while ($d->lte($to)) { if ($d->isWeekday()) $workingDays++; $d->addDay(); }

        $rows = $wards->map(function ($ward) use ($from,$to,$workingDays) {
            $schoolIds = School::where('ward_id',$ward->id)->pluck('id');
            $teachers  = User::whereIn('school_id',$schoolIds)->whereIn('role',['teacher','head_teacher'])->where('status','approved')->count();
            $wo = User::where('role','ward_officer')->where('ward_id',$ward->id)->first();
            $expected = $teachers * $workingDays;
            $actual = Attendance::whereIn('school_id',$schoolIds)->whereBetween('created_at',[$from,$to])->selectRaw('COUNT(DISTINCT DATE(created_at), user_id) as cnt')->value('cnt') ?? 0;
            $avgRate = $expected > 0 ? round(($actual/$expected)*100,1) : 0;
            return ['id'=>$ward->id,'name'=>$ward->name,'school_count'=>$schoolIds->count(),'teacher_count'=>$teachers,'ward_officer'=>$wo?$wo->full_name:'— Hana —','has_officer'=>!is_null($wo),'avg_rate'=>$avgRate];
        });

        return ['wards'=>$rows,'total'=>$wards->count(),'with_officer'=>$rows->where('has_officer',true)->count(),'without_officer'=>$rows->where('has_officer',false)->count(),'avg_rate'=>$rows->count()>0?round($rows->avg('avg_rate'),1):0];
    }

    private function buildTransfersReport($councilId,$from,$to): array
    {
        $transfers = Transfer::with(['user','fromSchool.ward','toSchool.ward','requester'])
            ->whereBetween('created_at',[$from,$to])
            ->whereHas('toSchool.ward', fn($q) => $q->where('council_id',$councilId))
            ->latest()->get();
        return ['transfers'=>$transfers,'total'=>$transfers->count(),'approved'=>$transfers->where('status','approved')->count(),'rejected'=>$transfers->where('status','rejected')->count(),'pending'=>$transfers->where('status','pending')->count()];
    }
}