{{-- resources/views/ward/reports.blade.php --}}
<x-ward-layout title="Ripoti">
    <x-slot name="actions">
        <a href="{{ route('ward.reports.export.csv', request()->query()) }}" class="btn btn-success btn-sm">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </x-slot>

    <div style="margin-bottom:20px">
        <h1 style="font-size:22px;font-weight:800">Ripoti — Kata ya {{ $ward->name }}</h1>
    </div>

    {{-- Filter --}}
    <form method="GET">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px 20px;margin-bottom:20px">
            <div style="font-size:13px;font-weight:700;margin-bottom:14px;color:var(--text);display:flex;align-items:center;gap:8px">
                <i class="fas fa-filter" style="color:var(--accent)"></i> Chagua Kipindi
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;align-items:end">
                <div class="form-group">
                    <label class="form-label">Tarehe ya Mwanzo</label>
                    <input type="date" name="date_from" class="form-input" value="{{ $dateFrom }}" max="{{ now()->toDateString() }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tarehe ya Mwisho</label>
                    <input type="date" name="date_to" class="form-input" value="{{ $dateTo }}" max="{{ now()->toDateString() }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Shule</label>
                    <select name="school_id" class="form-select">
                        <option value="">Zote</option>
                        @foreach($schools as $sc)
                        <option value="{{ $sc->id }}" {{ $schoolId==$sc->id?'selected':'' }}>{{ $sc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Vipindi vya Haraka</label>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        @foreach([['Leo',now()->toDateString(),now()->toDateString()],['Wiki',now()->startOfWeek()->toDateString(),now()->toDateString()],['Mwezi',now()->startOfMonth()->toDateString(),now()->toDateString()]] as [$l,$df,$dt])
                        <a href="?date_from={{ $df }}&date_to={{ $dt }}&school_id={{ $schoolId }}" class="btn btn-ghost btn-sm" style="padding:4px 10px;font-size:11px">{{ $l }}</a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" style="width:100%"><i class="fas fa-search"></i> Toa Ripoti</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Summary stats --}}
    <div class="stats-grid" style="margin-bottom:20px">
        <div class="stat-card s-blue"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-val" style="color:var(--blue)">{{ count($teacherRows) }}</div><div class="stat-label">Walimu</div></div>
        <div class="stat-card s-yellow"><div class="stat-icon"><i class="fas fa-calendar-week"></i></div><div class="stat-val" style="color:var(--yellow)">{{ $workDays }}</div><div class="stat-label">Siku za Kazi</div></div>
        <div class="stat-card {{ $overallRate>=80?'s-green':($overallRate>=60?'s-yellow':'s-red') }}">
            <div class="stat-icon"><i class="fas fa-percentage"></i></div>
            <div class="stat-val" style="color:{{ $overallRate>=80?'var(--accent)':($overallRate>=60?'var(--yellow)':'var(--red)') }}">{{ $overallRate }}%</div>
            <div class="stat-label">Kiwango cha Ujumla</div>
        </div>
    </div>

    {{-- Trend chart --}}
    @if(count($dailyTrend) > 0)
    <div class="card">
        <div class="card-header"><div class="card-title">📈 Mwenendo wa Mahudhurio</div><div class="card-sub">{{ $dateFrom }} — {{ $dateTo }}</div></div>
        <div class="card-body"><div style="height:200px;position:relative"><canvas id="trendChart"></canvas></div></div>
    </div>
    @endif

    {{-- Per school --}}
    <div class="card">
        <div class="card-header"><div class="card-title">🏫 Kwa Shule</div></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Shule</th><th>Walimu</th><th>Kiwango %</th><th>Mwenendo</th></tr></thead>
                <tbody>
                    @forelse($schoolReport as $i => $s)
                    @php $sc = $s['rate']>=80?'var(--accent)':($s['rate']>=60?'var(--yellow)':'var(--red)') @endphp
                    <tr>
                        <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                        <td style="font-weight:600">{{ $s['name'] }}</td>
                        <td style="font-family:var(--mono)">{{ $s['teachers'] }}</td>
                        <td><span class="badge {{ $s['rate']>=80?'b-green':($s['rate']>=60?'b-yellow':'b-red') }}">{{ $s['rate'] }}%</span></td>
                        <td><div class="prog-bg" style="width:100px"><div class="prog" style="width:{{ $s['rate'] }}%;background:{{ $sc }}"></div></div></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted)">Hakuna data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Per teacher --}}
    <div class="card">
        <div class="card-header">
            <div><div class="card-title">👨‍🏫 Kwa Mwalimu ({{ count($teacherRows) }})</div></div>
            <a href="{{ route('ward.reports.export.csv', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="fas fa-download"></i> CSV
            </a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Mwalimu</th><th>Jinsia</th><th>Shule</th><th>Siku Alifika</th><th>Siku za Kazi</th><th>Kiwango</th></tr></thead>
                <tbody>
                    @forelse($teacherRows as $i => $t)
                    @php $rc = $t['rate']>=80?'var(--accent)':($t['rate']>=60?'var(--yellow)':'var(--red)') @endphp
                    <tr>
                        <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                        <td><div class="t-info">
                            <div class="t-av {{ $t['sex']==='female'?'female':'' }}">{{ strtoupper(substr($t['name'],0,1)) }}</div>
                            <div><div class="t-name">{{ $t['name'] }}</div><div class="t-sub">{{ $t['check'] }}</div></div>
                        </div></td>
                        <td style="font-size:12px;color:{{ $t['sex']==='female'?'var(--pink)':'var(--blue)' }}">{{ $t['sex']==='female'?'♀':'♂' }}</td>
                        <td style="font-size:12px">{{ $t['school'] }}</td>
                        <td style="font-family:var(--mono);color:var(--accent)">{{ $t['days'] }}</td>
                        <td style="font-family:var(--mono);color:var(--muted)">{{ $t['work_days'] }}</td>
                        <td>
                            <span class="badge {{ $t['rate']>=80?'b-green':($t['rate']>=60?'b-yellow':'b-red') }}">{{ $t['rate'] }}%</span>
                            <div class="prog-bg" style="width:60px;margin-top:3px"><div class="prog" style="width:{{ $t['rate'] }}%;background:{{ $rc }}"></div></div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">Hakuna data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-slot name="scripts">
    @if(count($dailyTrend) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    new Chart(document.getElementById('trendChart'),{
        type:'line',
        data:{labels:@json(array_column($dailyTrend,'date')),datasets:[
            {label:'Kiwango %',data:@json(array_column($dailyTrend,'rate')),borderColor:'#10b981',backgroundColor:'rgba(16,185,129,.08)',fill:true,tension:.4,pointRadius:3,borderWidth:2},
            {label:'Waliofika',data:@json(array_column($dailyTrend,'count')),borderColor:'#3b82f6',backgroundColor:'transparent',tension:.4,pointRadius:3,yAxisID:'y2',borderWidth:2}
        ]},
        options:{responsive:true,maintainAspectRatio:false,interaction:{mode:'index',intersect:false},
            plugins:{legend:{labels:{color:'#4d7a68',font:{size:11},boxWidth:10}},tooltip:{backgroundColor:'#172420',borderColor:'#1f3329',borderWidth:1,titleColor:'#e2faf3',bodyColor:'#4d7a68'}},
            scales:{x:{ticks:{color:'#4d7a68',font:{size:10},maxTicksLimit:12},grid:{color:'rgba(31,51,41,.5)'}},y:{ticks:{color:'#4d7a68',font:{size:10},callback:v=>v+'%'},grid:{color:'rgba(31,51,41,.5)'},max:100,min:0},y2:{position:'right',ticks:{color:'#3b82f6',font:{size:10}},grid:{display:false}}}}
    });
    </script>
    @endif
    </x-slot>
</x-ward-layout>