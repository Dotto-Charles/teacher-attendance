{{-- resources/views/ward/dashboard.blade.php --}}
<x-ward-layout title="Dashboard">
    <x-slot name="actions">
        <input type="date" class="form-input" style="padding:6px 10px;font-size:12px;width:auto"
               id="dashDate" value="{{ $selectedDate }}" max="{{ now()->toDateString() }}"
               onchange="window.location='?date='+this.value">
    </x-slot>

    {{-- Heading --}}
    <div style="margin-bottom:20px">
        <h1 style="font-size:22px;font-weight:800">Dashboard</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">
            Kata ya <strong style="color:var(--accent3)">{{ $ward->name }}</strong> ·
            {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
        </p>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card s-green">
            <div class="stat-icon"><i class="fas fa-school"></i></div>
            <div class="stat-val" style="color:var(--accent)">{{ $totalSchools }}</div>
            <div class="stat-label">Shule</div>
        </div>
        <div class="stat-card s-blue">
            <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-val" style="color:var(--blue)">{{ $totalTeachers }}</div>
            <div class="stat-label">Walimu</div>
        </div>
        <div class="stat-card s-green">
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            <div class="stat-val" style="color:var(--accent)">{{ $presentToday }}</div>
            <div class="stat-label">Walifika Leo</div>
        </div>
        <div class="stat-card {{ $overallRate>=80?'s-green':($overallRate>=60?'s-yellow':'s-red') }}">
            <div class="stat-icon"><i class="fas fa-percentage"></i></div>
            <div class="stat-val" style="color:{{ $overallRate>=80?'var(--accent)':($overallRate>=60?'var(--yellow)':'var(--red)') }}">{{ $overallRate }}%</div>
            <div class="stat-label">Kiwango Leo</div>
        </div>
        <div class="stat-card s-yellow">
            <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
            <div class="stat-val" style="color:var(--yellow)">{{ $pendingCount }}</div>
            <div class="stat-label">Wanasubiri Idhini</div>
        </div>
        <div class="stat-card s-purple">
            <div class="stat-icon"><i class="fas fa-exchange-alt"></i></div>
            <div class="stat-val" style="color:var(--purple)">{{ $pendingTransfers }}</div>
            <div class="stat-label">Uhamisho Pending</div>
        </div>
    </div>

    {{-- Trend chart + School cards --}}
    <div style="display:grid;grid-template-columns:1.6fr 1fr;gap:20px;margin-bottom:20px">
        <div class="card" style="margin-bottom:0">
            <div class="card-header">
                <div><div class="card-title">📈 Mwenendo wa Siku 7</div><div class="card-sub">Mahudhurio kila siku</div></div>
            </div>
            <div class="card-body">
                <div style="height:200px;position:relative"><canvas id="trendChart"></canvas></div>
            </div>
        </div>
        <div class="card" style="margin-bottom:0">
            <div class="card-header">
                <div><div class="card-title">🏫 Shule Zote</div><div class="card-sub">Kiwango cha leo</div></div>
            </div>
            <div style="padding:14px 16px;display:flex;flex-direction:column;gap:10px;max-height:270px;overflow-y:auto">
                @foreach($schools as $sc)
                @php $c = $sc['rate']>=80?'var(--accent)':($sc['rate']>=60?'var(--yellow)':'var(--red)') @endphp
                <div style="background:var(--surface2);border-radius:var(--r-sm);padding:10px 12px">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px">
                        <span style="font-size:13px;font-weight:600;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $sc['name'] }}</span>
                        <span style="font-family:var(--mono);font-size:13px;font-weight:700;color:{{ $c }};margin-left:8px">{{ $sc['rate'] }}%</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--muted);margin-bottom:5px">
                        <span>✅ {{ $sc['present'] }} / ❌ {{ $sc['absent'] }} / 👥 {{ $sc['total'] }}</span>
                    </div>
                    <div class="prog-bg"><div class="prog" style="width:{{ $sc['rate'] }}%;background:{{ $c }}"></div></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick links --}}
    @if($pendingCount > 0)
    <div style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:var(--r-sm);padding:12px 16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:20px">
        <span style="font-size:13px;color:var(--yellow)"><i class="fas fa-exclamation-triangle" style="margin-right:6px"></i>Walimu <strong>{{ $pendingCount }}</strong> wanasubiri idhini yako</span>
        <a href="{{ route('ward.approvals.index') }}" class="btn btn-warning btn-sm">Kagua Sasa →</a>
    </div>
    @endif

    <x-slot name="scripts">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    const trend = @json($trend);
    new Chart(document.getElementById('trendChart').getContext('2d'),{
        type:'line',
        data:{labels:trend.map(d=>d.date),datasets:[
            {label:'Kiwango %',data:trend.map(d=>d.rate),borderColor:'#10b981',backgroundColor:'rgba(16,185,129,.08)',fill:true,tension:.4,pointRadius:4,borderWidth:2},
            {label:'Waliofika',data:trend.map(d=>d.count),borderColor:'#3b82f6',backgroundColor:'transparent',tension:.4,pointRadius:3,yAxisID:'y2',borderWidth:2}
        ]},
        options:{responsive:true,maintainAspectRatio:false,interaction:{mode:'index',intersect:false},
            plugins:{legend:{labels:{color:'#4d7a68',font:{size:11},boxWidth:10}},tooltip:{backgroundColor:'#172420',borderColor:'#1f3329',borderWidth:1,titleColor:'#e2faf3',bodyColor:'#4d7a68'}},
            scales:{x:{ticks:{color:'#4d7a68',font:{size:10}},grid:{color:'rgba(31,51,41,.5)'}},y:{ticks:{color:'#4d7a68',font:{size:10},callback:v=>v+'%'},grid:{color:'rgba(31,51,41,.5)'},max:100,min:0},y2:{position:'right',ticks:{color:'#3b82f6',font:{size:10}},grid:{display:false}}}}
    });
    </script>
    </x-slot>
</x-ward-layout>