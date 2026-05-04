{{-- resources/views/ward/attendance.blade.php --}}
<x-ward-layout title="Mahudhurio">
    <x-slot name="actions">
        <form method="GET" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <input type="hidden" name="school_id" value="{{ $schoolFilter }}">
            <input type="hidden" name="status"    value="{{ $statusFilter }}">
            <input type="date" name="date" class="form-input" style="padding:6px 10px;font-size:12px;width:auto"
                   value="{{ $selectedDate }}" max="{{ now()->toDateString() }}" onchange="this.form.submit()">
        </form>
        <a href="{{ route('ward.attendance.export.csv', request()->query()) }}" class="btn btn-success btn-sm">
            <i class="fas fa-download"></i> CSV
        </a>
    </x-slot>

    <div style="margin-bottom:20px">
        <h1 style="font-size:22px;font-weight:800">Mahudhurio</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ \Carbon\Carbon::parse($selectedDate)->format('l, d M Y') }}</p>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card s-blue"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-val" style="color:var(--blue)">{{ $allTeachers }}</div><div class="stat-label">Walimu Wote</div></div>
        <div class="stat-card s-green"><div class="stat-icon"><i class="fas fa-user-check"></i></div><div class="stat-val" style="color:var(--accent)">{{ $presentCount }}</div><div class="stat-label">Walifika</div></div>
        <div class="stat-card s-red"><div class="stat-icon"><i class="fas fa-user-times"></i></div><div class="stat-val" style="color:var(--red)">{{ $absentCount }}</div><div class="stat-label">Hawakuja</div></div>
        <div class="stat-card {{ $rate>=80?'s-green':($rate>=60?'s-yellow':'s-red') }}">
            <div class="stat-icon"><i class="fas fa-percentage"></i></div>
            <div class="stat-val" style="color:{{ $rate>=80?'var(--accent)':($rate>=60?'var(--yellow)':'var(--red)') }}">{{ $rate }}%</div>
            <div class="stat-label">Kiwango</div>
        </div>
    </div>

    {{-- Hourly chart --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px">
        <div class="card" style="margin-bottom:0">
            <div class="card-header"><div class="card-title">⏰ Wakati wa Kufika</div></div>
            <div class="card-body"><div style="height:180px;position:relative"><canvas id="hourlyChart"></canvas></div></div>
        </div>
        <div class="card" style="margin-bottom:0">
            <div class="card-header"><div class="card-title" style="color:var(--red)">❌ Hawakuja ({{ $absentList->count() }})</div></div>
            <div style="padding:12px 16px;max-height:220px;overflow-y:auto">
                @forelse($absentList as $t)
                <div style="display:flex;align-items:center;gap:10px;padding:7px 0;border-bottom:1px solid rgba(31,51,41,.4)">
                    <div class="t-av {{ $t->sex==='female'?'female':'' }}">{{ strtoupper(substr($t->first_name,0,1)) }}</div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $t->first_name }} {{ $t->last_name }}</div>
                        <div style="font-size:11px;color:var(--muted)">{{ $t->school->name??'—' }}</div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:20px;color:var(--accent);font-size:13px">🎉 Wote walifika!</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET">
        <input type="hidden" name="date" value="{{ $selectedDate }}">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:14px 18px;margin-bottom:16px;display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
            <div class="form-group" style="flex:2;min-width:180px">
                <label class="form-label">Tafuta</label>
                <input type="text" name="search" class="form-input" placeholder="Jina au namba..." value="{{ $search ?? '' }}">
            </div>
            <div class="form-group" style="min-width:130px">
                <label class="form-label">Shule</label>
                <select name="school_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Zote</option>
                    @foreach($schools as $sc)
                    <option value="{{ $sc->id }}" {{ $schoolFilter==$sc->id?'selected':'' }}>{{ $sc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="min-width:120px">
                <label class="form-label">Hali</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Wote</option>
                    <option value="present" {{ $statusFilter==='present'?'selected':'' }}>✅ Walifika</option>
                    <option value="absent"  {{ $statusFilter==='absent' ?'selected':'' }}>❌ Hawakuja</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:flex-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Chuja</button>
                <a href="{{ route('ward.attendance.index', ['date'=>$selectedDate]) }}" class="btn btn-ghost"><i class="fas fa-times"></i></a>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="card">
        <div class="card-header">
            <div><div class="card-title">📋 Walimu ({{ $teachers->total() }})</div></div>
            <select class="form-select" style="width:auto;padding:5px 10px;font-size:12px" onchange="location='?per_page='+this.value+'&date={{ $selectedDate }}&school_id={{ $schoolFilter }}&status={{ $statusFilter }}'">
                @foreach([15,20,50] as $pp)<option value="{{ $pp }}" {{ $perPage==$pp?'selected':'' }}>{{ $pp }}/ukurasa</option>@endforeach
            </select>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Mwalimu</th><th>Shule</th><th>Jinsia</th><th>Hali</th><th>Wakati</th></tr></thead>
                <tbody>
                    @forelse($teachers as $i => $t)
                    <tr>
                        <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $teachers->firstItem()+$i }}</td>
                        <td><div class="t-info"><div class="t-av {{ $t->sex==='female'?'female':'' }}">{{ strtoupper(substr($t->first_name,0,1)) }}</div><div><div class="t-name">{{ $t->first_name }} {{ $t->last_name }}</div><div class="t-sub">{{ $t->check_number }}</div></div></div></td>
                        <td style="font-size:12px">{{ $t->school->name??'—' }}</td>
                        <td style="font-size:12px;color:{{ $t->sex==='female'?'var(--pink)':'var(--blue)' }}">{{ $t->sex==='female'?'♀ Mke':'♂ Mme' }}</td>
                        <td><span class="badge {{ $t->is_present?'b-green':'b-red' }}">{{ $t->is_present?'✅ Alikuja':'❌ Hakuja' }}</span></td>
                        <td>@if($t->check_in_time)<span style="background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:2px 8px;font-size:12px;font-family:var(--mono)">{{ $t->check_in_time }}</span>@else<span style="color:var(--muted);font-size:12px">—</span>@endif</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--muted)">Hakuna walimu</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pag-wrap">
            <span class="pag-info">Ukurasa {{ $teachers->currentPage() }}/{{ $teachers->lastPage() }}</span>
            <div class="pag">
                @if(!$teachers->onFirstPage())<a href="{{ $teachers->previousPageUrl() }}"><i class="fas fa-chevron-left" style="font-size:11px"></i></a>@else<span style="opacity:.4"><i class="fas fa-chevron-left" style="font-size:11px"></i></span>@endif
                @foreach($teachers->getUrlRange(max(1,$teachers->currentPage()-2),min($teachers->lastPage(),$teachers->currentPage()+2)) as $pg=>$url)
                    @if($pg==$teachers->currentPage())<span class="cur">{{ $pg }}</span>@else<a href="{{ $url }}">{{ $pg }}</a>@endif
                @endforeach
                @if($teachers->hasMorePages())<a href="{{ $teachers->nextPageUrl() }}"><i class="fas fa-chevron-right" style="font-size:11px"></i></a>@else<span style="opacity:.4"><i class="fas fa-chevron-right" style="font-size:11px"></i></span>@endif
            </div>
        </div>
    </div>

    <x-slot name="scripts">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    const h = @json($hourlyData);
    new Chart(document.getElementById('hourlyChart'),{type:'bar',data:{labels:h.map(d=>d.hour),datasets:[{label:'Waliofika',data:h.map(d=>d.count),backgroundColor:h.map(d=>{const hr=parseInt(d.hour);return hr<8?'rgba(245,158,11,.7)':hr<=9?'rgba(16,185,129,.7)':'rgba(59,130,246,.5)';}),borderRadius:5,borderSkipped:false}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'#172420',borderColor:'#1f3329',borderWidth:1,titleColor:'#e2faf3',bodyColor:'#4d7a68'}},scales:{x:{ticks:{color:'#4d7a68',font:{size:10}},grid:{color:'rgba(31,51,41,.4)'}},y:{ticks:{color:'#4d7a68',font:{size:10},stepSize:1},grid:{color:'rgba(31,51,41,.4)'},beginAtZero:true}}}});
    </script>
    </x-slot>
</x-ward-layout>