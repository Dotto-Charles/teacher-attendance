{{-- resources/views/headteacher/reports.blade.php --}}
<x-layout title="Ripoti">

<div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <h1 style="font-size:22px;font-weight:800">Ripoti — {{ $school->name }}</h1>
        <p style="font-size:13px;color:#64748b;margin-top:3px">{{ $dateFrom }} — {{ $dateTo }}</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end">
        <a href="{{ route('headteacher.reports.export.csv', request()->query()) }}" class="btn btn-success btn-sm rounded-pill">
            <i class="bi bi-download me-1"></i> Pakua CSV
        </a>
        <a href="{{ route('headteacher.reports.export.pdf', request()->query()) }}" class="btn btn-danger btn-sm rounded-pill">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Pakua PDF
        </a>
    </div>
</div>

{{-- Filter --}}
<form method="GET" class="mb-4">
    <div class="d-flex flex-wrap gap-2 align-items-end p-3 bg-white rounded-3 shadow-sm">
        <div>
            <label style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase">Tarehe ya Mwanzo</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}" max="{{ now()->toDateString() }}">
        </div>
        <div>
            <label style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase">Tarehe ya Mwisho</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}" max="{{ now()->toDateString() }}">
        </div>
        <div style="display:flex;gap:6px;align-items:flex-end">
            @foreach([['Leo',now()->toDateString(),now()->toDateString()],['Wiki',now()->startOfWeek()->toDateString(),now()->toDateString()],['Mwezi',now()->startOfMonth()->toDateString(),now()->toDateString()]] as [$l,$df,$dt])
            <a href="?date_from={{ $df }}&date_to={{ $dt }}" class="btn btn-outline-secondary btn-sm rounded-pill" style="font-size:11px">{{ $l }}</a>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary btn-sm rounded-pill">Toa Ripoti</button>
    </div>
</form>

{{-- Summary cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius:14px">
            <div style="font-size:24px;font-weight:800;color:#0d6efd;font-family:monospace">{{ $totalTeachers }}</div>
            <div style="font-size:11px;color:#94a3b8">Walimu</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius:14px">
            <div style="font-size:24px;font-weight:800;color:#f59e0b;font-family:monospace">{{ $workDays }}</div>
            <div style="font-size:11px;color:#94a3b8">Siku za Kazi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius:14px;border-top:3px solid {{ $overallRate>=80?'#10b981':($overallRate>=60?'#f59e0b':'#ef4444') }} !important">
            <div style="font-size:24px;font-weight:800;color:{{ $overallRate>=80?'#16a34a':($overallRate>=60?'#ca8a04':'#dc2626') }};font-family:monospace">{{ $overallRate }}%</div>
            <div style="font-size:11px;color:#94a3b8">Kiwango cha Ujumla</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius:14px">
            <div style="font-size:24px;font-weight:800;color:#6366f1;font-family:monospace">{{ $teacherStats->max('rate') ?? 0 }}%</div>
            <div style="font-size:11px;color:#94a3b8">Kiwango cha Juu</div>
        </div>
    </div>
</div>

{{-- Trend chart --}}
@if($trend->count() > 0)
<div class="card border-0 shadow-sm mb-4" style="border-radius:16px">
    <div class="card-body">
        <div style="font-size:14px;font-weight:700;margin-bottom:12px">📈 Mwenendo wa Kipindi</div>
        <div style="height:180px;position:relative"><canvas id="trendChart"></canvas></div>
    </div>
</div>
@endif

{{-- Teacher stats table --}}
<div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden">
    <div class="card-header bg-white py-3">
        <span style="font-size:14px;font-weight:700">👨‍🏫 Kwa Mwalimu ({{ $teacherStats->count() }})</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:13px">
            <thead class="table-light">
                <tr><th>#</th><th>Mwalimu</th><th>Jinsia</th><th>Siku Alifika</th><th>Siku za Kazi</th><th>Kiwango %</th><th>Mwenendo</th></tr>
            </thead>
            <tbody>
                @forelse($teacherStats as $i => $t)
                @php $r=(float)($t->rate??0); @endphp
                <tr>
                    <td style="color:#94a3b8;font-size:11px">{{ $i+1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:30px;height:30px;border-radius:50%;background:{{ $t->sex==='female'?'#fdf2f8':'#eff6ff' }};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;color:{{ $t->sex==='female'?'#9d174d':'#1d4ed8' }};flex-shrink:0">{{ strtoupper(substr($t->first_name,0,1)) }}</div>
                            <div>
                                <div style="font-weight:600">{{ $t->first_name }} {{ $t->last_name }}</div>
                                <div style="font-size:10px;color:#94a3b8;font-family:monospace">{{ $t->check_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:{{ $t->sex==='female'?'#be185d':'#1d4ed8' }};font-size:12px">{{ $t->sex==='female'?'♀':'♂' }}</td>
                    <td style="font-family:monospace;font-weight:600;color:#0d6efd">{{ $t->days_present }}</td>
                    <td style="font-family:monospace;color:#64748b">{{ $t->working_days }}</td>
                    <td>
                        <span class="badge {{ $r>=80?'bg-success':($r>=60?'bg-warning text-dark':'bg-danger') }} rounded-pill">{{ $r }}%</span>
                    </td>
                    <td style="min-width:90px">
                        <div style="height:6px;background:#f1f5f9;border-radius:99px;overflow:hidden">
                            <div style="height:100%;width:{{ min(100,$r) }}%;background:{{ $r>=80?'#10b981':($r>=60?'#f59e0b':'#ef4444') }};border-radius:99px"></div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Hakuna data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
@if($trend->count() > 0)
const td=@json($trend);
new Chart(document.getElementById('trendChart'),{type:'line',data:{labels:td.map(d=>d.date),datasets:[
    {label:'Kiwango %',data:td.map(d=>d.rate),borderColor:'#6366f1',backgroundColor:'rgba(99,102,241,.06)',fill:true,tension:.4,pointRadius:td.length>30?0:3,borderWidth:2},
    {label:'Waliofika',data:td.map(d=>d.count),borderColor:'#10b981',tension:.4,pointRadius:td.length>30?0:3,yAxisID:'y2',borderWidth:2}
]},options:{responsive:true,maintainAspectRatio:false,interaction:{mode:'index',intersect:false},
    plugins:{legend:{labels:{font:{size:11},boxWidth:10}}},
    scales:{x:{ticks:{font:{size:10},maxTicksLimit:12},grid:{color:'rgba(0,0,0,.04)'}},y:{ticks:{font:{size:10},callback:v=>v+'%'},grid:{color:'rgba(0,0,0,.04)'},max:100,min:0},y2:{position:'right',ticks:{font:{size:10},color:'#10b981'},grid:{display:false}}}}
});
@endif
</script>
@endpush
</x-layout>