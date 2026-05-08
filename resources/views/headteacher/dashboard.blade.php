{{-- resources/views/headteacher/dashboard.blade.php --}}
<x-layout title="Dashboard" subtitle="{{ $school->name }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap');
.ht-dash { font-family:'Sora',sans-serif; }
.hero {
    background:linear-gradient(135deg,#0c1a2e 0%,#1a2f4a 60%,#0c1a2e 100%);
    border-radius:20px; padding:26px; margin-bottom:20px;
    border:1px solid rgba(99,102,241,.2); position:relative; overflow:hidden;
}
.hero::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background:radial-gradient(ellipse at 75% 50%,rgba(99,102,241,.15) 0%,transparent 55%),
               radial-gradient(ellipse at 15% 80%,rgba(16,185,129,.07) 0%,transparent 45%);
}
.hero-inner { position:relative; display:grid; grid-template-columns:1fr auto; gap:20px; align-items:center; }
.hero-greeting { font-size:12px; color:rgba(255,255,255,.45); text-transform:uppercase; letter-spacing:1px; margin-bottom:5px; }
.hero-name { font-size:24px; font-weight:800; color:#fff; line-height:1.2; }
.hero-school { font-size:13px; color:rgba(255,255,255,.5); margin-top:4px; }
.hero-pills { display:flex; gap:8px; margin-top:14px; flex-wrap:wrap; }
.hero-pill {
    padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;
    background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); color:rgba(255,255,255,.7);
    display:flex; align-items:center; gap:5px;
}
.big-ring {
    width:80px; height:80px; flex-shrink:0;
    position:relative; display:flex; align-items:center; justify-content:center;
}
.ring-pct  { font-size:20px; font-weight:800; font-family:'JetBrains Mono',monospace; color:#fff; }
.ring-lbl  { font-size:9px; color:rgba(255,255,255,.5); letter-spacing:.5px; }

/* STAT CARDS */
.s-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:12px; margin-bottom:20px; }
.s-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:16px; transition:transform .2s,box-shadow .2s; position:relative; overflow:hidden; }
.s-card:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,.08); }
.s-card::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; border-radius:0 0 16px 16px; }
.s-c1::after{background:linear-gradient(90deg,#6366f1,#8b5cf6)}
.s-c2::after{background:linear-gradient(90deg,#10b981,#059669)}
.s-c3::after{background:linear-gradient(90deg,#f59e0b,#d97706)}
.s-c4::after{background:linear-gradient(90deg,#ef4444,#dc2626)}
.s-c5::after{background:linear-gradient(90deg,#0d6efd,#6366f1)}
.s-icon { font-size:20px; margin-bottom:8px; }
.s-val  { font-size:28px; font-weight:800; font-family:'JetBrains Mono',monospace; line-height:1; color:#1e293b; }
.s-lbl  { font-size:11px; color:#94a3b8; margin-top:4px; font-weight:500; }

/* CARD */
.card2 { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; margin-bottom:20px; }
.card2-header { padding:14px 18px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; }
.card2-title { font-size:14px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:7px; }
.card2-sub   { font-size:12px; color:#94a3b8; }
.card2-body  { padding:18px; }

/* TABLE */
.t2-wrap { overflow-x:auto; }
table.t2 { width:100%; border-collapse:collapse; font-size:13px; }
table.t2 thead th { padding:10px 14px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.8px; background:#f8fafc; border-bottom:1px solid #f1f5f9; white-space:nowrap; }
table.t2 tbody td { padding:11px 14px; border-bottom:1px solid #f8fafc; vertical-align:middle; }
table.t2 tbody tr:last-child td { border-bottom:none; }
table.t2 tbody tr:hover td { background:#f8fafc; cursor:pointer; }
.av { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0; }
.av-m { background:#eff6ff; color:#1d4ed8; }
.av-f { background:#fdf2f8; color:#9d174d; }
.t-nm { font-weight:600; font-size:13px; color:#1e293b; }
.t-sb { font-size:11px; color:#94a3b8; font-family:'JetBrains Mono',monospace; }
.prog { height:5px; background:#f1f5f9; border-radius:99px; overflow:hidden; margin-top:3px; }
.prog-fill { height:100%; border-radius:99px; }
.bdg { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:20px; font-size:11px; font-weight:600; }
.bdg-g { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
.bdg-r { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
.bdg-y { background:#fffbeb; color:#92400e; border:1px solid #fde68a; }

/* GPS CARD */
.gps-card { background:#fff; border:2px solid #e2e8f0; border-radius:16px; overflow:hidden; margin-bottom:20px; transition:border-color .3s; }
.gps-card.gps-ok { border-color:#10b981; }
.gps-card.gps-done { border-color:#10b981; background:#f0fdf4; }
.gps-card.gps-err { border-color:#ef4444; }
.gps-header { padding:14px 18px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #f1f5f9; flex-wrap:wrap; gap:8px; }
.gps-body { padding:16px 18px; }
.gps-ind { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }
.gi-load { background:#f8fafc; color:#94a3b8; }
.gi-ok   { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
.gi-err  { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
.btn-ci { width:100%; padding:13px; border-radius:12px; font-size:14px; font-weight:700; border:none; cursor:pointer; font-family:'Sora',sans-serif; display:flex; align-items:center; justify-content:center; gap:8px; transition:all .25s; }
.btn-ci-on  { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; box-shadow:0 6px 18px rgba(99,102,241,.3); }
.btn-ci-on:hover { transform:translateY(-2px); box-shadow:0 10px 24px rgba(99,102,241,.4); }
.btn-ci-off { background:#f1f5f9; color:#94a3b8; cursor:not-allowed; }
.btn-ci-done{ background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; cursor:default; }

/* DIST DISPLAY */
.dist-box { background:#f8fafc; border-radius:12px; padding:14px; text-align:center; margin-bottom:14px; }
.dist-num { font-size:32px; font-weight:800; font-family:'JetBrains Mono',monospace; line-height:1; }
.dist-lbl { font-size:12px; color:#64748b; margin-top:4px; }

/* PENDING ALERT */
.pending-alert { background:#fffbeb; border:1px solid #fde68a; border-radius:12px; padding:12px 16px; font-size:13px; color:#92400e; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:16px; }

/* 2-COL */
.g2 { display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:20px; }

/* TOAST */
.toast-wrap { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:8px; pointer-events:none; }
.toast { padding:13px 16px; border-radius:12px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:9px; min-width:260px; max-width:340px; animation:tSlide .3s ease; pointer-events:all; box-shadow:0 8px 24px rgba(0,0,0,.12); }
.t-ok  { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }
.t-err { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }
.t-inf { background:#eff6ff; border:1px solid #bfdbfe; color:#1d4ed8; }
@keyframes tSlide { from{opacity:0;transform:translateX(16px)} to{opacity:1;transform:translateX(0)} }
@keyframes spin { to{transform:rotate(360deg)} }

@media(max-width:768px){
    .hero-inner{grid-template-columns:1fr}
    .g2{grid-template-columns:1fr}
    .s-grid{grid-template-columns:repeat(2,1fr);gap:10px}
}
</style>

<div class="ht-dash">
<div class="toast-wrap" id="toastWrap"></div>

{{-- HERO --}}
<div class="hero">
    <div class="hero-inner">
        <div>
            <div class="hero-greeting">{{ now()->format('l, d M Y') }}</div>
            <div class="hero-name">{{ $ht->first_name }} {{ $ht->last_name }}</div>
            <div class="hero-school">🎓 Mwalimu Mkuu · {{ $school->name }}</div>
            <div class="hero-pills">
                <div class="hero-pill"><span>🗺️</span> {{ $school->ward->name ?? '—' }}</div>
                <div class="hero-pill"><span>👨‍🏫</span> {{ $totalTeachers }} Walimu</div>
                @if($pendingCount > 0)
                <div class="hero-pill" style="background:rgba(245,158,11,.15);border-color:rgba(245,158,11,.3);color:#fbbf24">
                    <span>⏳</span> {{ $pendingCount }} Wanasubiri
                </div>
                @endif
            </div>
        </div>
        <div class="big-ring">
            @php
                $r   = $summary['rate'];
                $c   = $r>=80?'#10b981':($r>=60?'#f59e0b':'#ef4444');
                $circ= 2*M_PI*34;
                $off = $circ - ($r/100*$circ);
            @endphp
            <svg width="80" height="80" viewBox="0 0 80 80">
                <circle cx="40" cy="40" r="34" fill="none" stroke="rgba(255,255,255,.1)" stroke-width="8"/>
                <circle cx="40" cy="40" r="34" fill="none" stroke="{{ $c }}" stroke-width="8"
                    stroke-linecap="round" stroke-dasharray="{{ round($circ,2) }}"
                    stroke-dashoffset="{{ round($off,2) }}" transform="rotate(-90 40 40)"/>
            </svg>
            <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center">
                <div class="ring-pct" style="color:{{ $c }}">{{ $r }}%</div>
                <div class="ring-lbl">LEO</div>
            </div>
        </div>
    </div>
</div>

{{-- PENDING APPROVALS ALERT --}}
@if($pendingCount > 0)
<div class="pending-alert">
    <span><i class="bi bi-person-fill-exclamation me-1"></i>
        Walimu <strong>{{ $pendingCount }}</strong> wanasubiri idhini yako
    </span>
    <a href="{{ route('ht.approvals') }}" class="btn btn-warning btn-sm rounded-pill">
        Kagua Sasa →
    </a>
</div>
@endif

{{-- STATS --}}
<div class="s-grid">
    <div class="s-card s-c1">
        <div class="s-icon">👨‍🏫</div>
        <div class="s-val" style="color:#6366f1">{{ $totalTeachers }}</div>
        <div class="s-lbl">Walimu Wote</div>
    </div>
    <div class="s-card s-c2">
        <div class="s-icon">✅</div>
        <div class="s-val" style="color:#10b981">{{ $summary['present'] }}</div>
        <div class="s-lbl">Walifika Leo</div>
    </div>
    <div class="s-card s-c4">
        <div class="s-icon">❌</div>
        <div class="s-val" style="color:#ef4444">{{ $summary['absent'] }}</div>
        <div class="s-lbl">Hawakuja Leo</div>
    </div>
    <div class="s-card s-c3">
        <div class="s-icon">⏳</div>
        <div class="s-val" style="color:#f59e0b">{{ $pendingCount }}</div>
        <div class="s-lbl">Wanasubiri Idhini</div>
    </div>
    <div class="s-card s-c5">
        <div class="s-icon">🔄</div>
        <div class="s-val" style="color:#0d6efd">{{ $pendingTransfers }}</div>
        <div class="s-lbl">Uhamisho Pending</div>
    </div>
</div>

{{-- GPS CHECK-IN --}}
<div class="gps-card {{ $htAttendedToday ? 'gps-done' : '' }}" id="gpsCard">
    <div class="gps-header">
        <div>
            <div style="font-size:14px;font-weight:700;color:#1e293b">📍 Cheki-in Yangu</div>
            <div style="font-size:12px;color:#64748b;margin-top:2px">{{ $school->name }} · {{ $school->radius }}m</div>
        </div>
        <div class="gps-ind gi-load" id="gpsInd">
            <i class="bi bi-arrow-repeat" style="animation:spin 1s linear infinite"></i> GPS...
        </div>
    </div>
    <div class="gps-body">
        @if($htAttendedToday)
        <div style="text-align:center;padding:14px">
            <div style="font-size:40px;margin-bottom:8px">🎉</div>
            <div style="font-size:15px;font-weight:700;color:#166534">Umecheki-in Leo!</div>
        </div>
        @else
        <div class="dist-box" id="distBox">
            <div id="distLoad" style="color:#94a3b8;font-size:13px">
                <i class="bi bi-geo-alt" style="font-size:24px;display:block;margin-bottom:6px"></i>
                Inatafuta eneo lako...
            </div>
            <div id="distData" style="display:none">
                <div class="dist-num" id="distNum">—</div>
                <div style="font-size:11px;color:#94a3b8">mita kutoka shuleni</div>
                <div class="dist-lbl" id="distMsg"></div>
            </div>
        </div>
        <div id="ciMsg" style="text-align:center;font-size:13px;margin-bottom:12px;min-height:18px"></div>
        <button class="btn-ci btn-ci-off" id="btnCI" disabled onclick="doCI()">
            <i class="bi bi-geo-alt-fill"></i>
            <span id="btnTxt">Inatafuta GPS...</span>
        </button>
        @endif
    </div>
</div>

{{-- TREND + TODAY TABLE --}}
<div class="g2">
    {{-- Trend --}}
    <div class="card2" style="margin-bottom:0">
        <div class="card2-header">
            <div class="card2-title">📈 Mwenendo wa Siku 14</div>
            <div class="card2-sub">{{ $school->name }}</div>
        </div>
        <div class="card2-body">
            <div style="height:190px;position:relative"><canvas id="trendChart"></canvas></div>
        </div>
    </div>
    {{-- Leaderboard --}}
    <div class="card2" style="margin-bottom:0">
        <div class="card2-header">
            <div class="card2-title">🏆 Bora Mwezi Huu</div>
            <a href="{{ route('headteacher.reports') }}" style="font-size:12px;color:#0d6efd;text-decoration:none">Ripoti kamili →</a>
        </div>
        <div class="card2-body" style="padding:10px 14px">
            @forelse($leaderboard as $i => $t)
            <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #f8fafc">
                <div style="width:24px;height:24px;border-radius:6px;background:{{ $i===0?'linear-gradient(135deg,#fbbf24,#f59e0b)':($i===1?'#f1f5f9':($i===2?'#fef3e2':'#f8fafc')) }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:{{ $i===0?'#fff':'#64748b' }};flex-shrink:0">{{ $i+1 }}</div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
    {{ $t['first_name'] }} {{ $t['last_name'] }}
</div>
                    <div style="font-size:10px;color:#94a3b8">
    {{ $t['days_present'] }}/{{ $t['working_days'] }} siku
</div>
               <div style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;
    color:{{ $t['rate']>=80?'#16a34a':($t['rate']>=60?'#ca8a04':'#dc2626') }}">
    {{ $t['rate'] }}%
</div>
            @empty
            <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px">Hakuna data bado</div>
            @endforelse
        </div>
    </div>
</div>

{{-- TODAY's TABLE --}}
<div class="card2">
    <div class="card2-header">
        <div class="card2-title">📋 Walimu Leo — {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</div>
        <div style="display:flex;gap:8px;align-items:center">
            <form method="GET" style="display:inline">
                <input type="date" name="date" class="form-control form-control-sm" style="font-size:12px;width:140px" value="{{ $selectedDate }}" max="{{ now()->toDateString() }}" onchange="this.form.submit()">
            </form>
            <a href="{{ route('headteacher.attendance') }}" class="btn btn-sm btn-outline-primary rounded-pill" style="font-size:12px">
                Maelezo zaidi →
            </a>
        </div>
    </div>
    <div class="t2-wrap">
        <table class="t2">
            <thead><tr>
                <th>#</th><th>Mwalimu</th><th>Jinsia</th><th>Hali Leo</th><th>Wakati</th><th>Mwezi (%)</th>
            </tr></thead>
            <tbody>
                @foreach($todayTeachers as $i => $t)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;font-family:'JetBrains Mono',monospace">{{ $i+1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="av {{ $t->sex==='female'?'av-f':'av-m' }}">{{ strtoupper(substr($t->first_name,0,1)) }}</div>
                            <div>
                                <div class="t-nm">{{ $t->first_name }} {{ $t->last_name }}</div>
                                <div class="t-sb">{{ $t->check_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:12px;color:{{ $t->sex==='female'?'#be185d':'#1d4ed8' }}">{{ $t->sex==='female'?'♀':'♂' }}</td>
                    <td>
                        <span class="bdg {{ $t->is_present?'bdg-g':'bdg-r' }}">
                            {{ $t->is_present?'✅ Alikuja':'❌ Hakuja' }}
                        </span>
                    </td>
                    <td style="font-family:'JetBrains Mono',monospace;font-size:12px;color:#64748b">
                        {{ $t->checked_at ? \Carbon\Carbon::parse($t->checked_at)->format('H:i') : '—' }}
                    </td>
                    <td style="min-width:90px">
                        @php $mr = $t->month_rate ?? 0; @endphp
                        <div style="font-size:12px;font-weight:700;font-family:'JetBrains Mono',monospace;color:{{ $mr>=80?'#16a34a':($mr>=60?'#ca8a04':'#dc2626') }}">{{ $mr }}%</div>
                        <div class="prog"><div class="prog-fill" style="width:{{ $mr }}%;background:{{ $mr>=80?'#10b981':($mr>=60?'#f59e0b':'#ef4444') }}"></div></div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- PENDING TEACHERS PREVIEW --}}
@if($pendingTeachers->count() > 0)
<div class="card2">
    <div class="card2-header">
        <div class="card2-title">⏳ Wanasubiri Idhini ({{ $pendingCount }})</div>
        <a href="{{ route('ht.approvals') }}" class="btn btn-sm btn-warning rounded-pill" style="font-size:12px">
            Kagua Wote →
        </a>
    </div>
    <div class="t2-wrap">
        <table class="t2">
            <thead><tr><th>#</th><th>Mwalimu</th><th>Namba</th><th>Jinsia</th><th>Alijiunga</th><th>Vitendo</th></tr></thead>
            <tbody>
                @foreach($pendingTeachers as $i => $t)
                <tr>
                    <td style="color:#94a3b8;font-size:12px">{{ $i+1 }}</td>
                    <td><div style="display:flex;align-items:center;gap:8px"><div class="av {{ $t->sex==='female'?'av-f':'av-m' }}">{{ strtoupper(substr($t->first_name,0,1)) }}</div><div class="t-nm">{{ $t->first_name }} {{ $t->last_name }}</div></div></td>
                    <td style="font-family:'JetBrains Mono',monospace;font-size:12px">{{ $t->check_number }}</td>
                    <td style="font-size:12px;color:{{ $t->sex==='female'?'#be185d':'#1d4ed8' }}">{{ $t->sex==='female'?'♀ Mke':'♂ Mme' }}</td>
                    <td style="font-size:11px;color:#94a3b8">{{ $t->created_at->diffForHumans() }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <form method="POST" action="{{ route('ht.approve', $t) }}">@csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm rounded-pill" style="font-size:11px"><i class="bi bi-check"></i> Idhinisha</button>
                            </form>
                            <form method="POST" action="{{ route('ht.reject', $t) }}" onsubmit="return confirm('Kataa {{ $t->first_name }}?')">@csrf @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill" style="font-size:11px"><i class="bi bi-x"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

</div>{{-- /ht-dash --}}

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const S_LAT = {{ $school->latitude ?? 'null' }};
const S_LNG = {{ $school->longitude ?? 'null' }};
const S_RAD = {{ $school->radius ?? 500 }};
const DONE  = {{ $htAttendedToday ? 'true':'false' }};
let uLat=null,uLng=null,gpsOk=false,dist=null;

// ── TOAST ───────────────────────────────────────────────────────────
function toast(msg, type='ok', dur=4000){
    const w = document.getElementById('toastWrap');
    const el = document.createElement('div');

    const icons = {
        ok: '✅',
        err: '❌',
        inf: 'ℹ️'
    };

    el.className = `toast t-${type}`;
    el.innerHTML = `<span>${icons[type]}</span><span>${msg}</span>`;

    w.appendChild(el);

    setTimeout(() => {
        el.style.transition = 'opacity .4s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 400);
    }, dur);
}

// ── GPS ─────────────────────────────────────────────────────────────
function initGPS(){
    if(DONE) return;
    const ind=document.getElementById('gpsInd');
    const btn=document.getElementById('btnCI');
    const btnT=document.getElementById('btnTxt');
    const dLoad=document.getElementById('distLoad');
    const dData=document.getElementById('distData');
    const dNum=document.getElementById('distNum');
    const dMsg=document.getElementById('distMsg');
    const ciMsg=document.getElementById('ciMsg');
    if(!navigator.geolocation){
        if(ind){ind.className='gps-ind gi-err';ind.innerHTML='❌ Hakuna GPS';}
        return;
    }
    navigator.geolocation.getCurrentPosition(pos=>{
        uLat=pos.coords.latitude; uLng=pos.coords.longitude;
        const acc=Math.round(pos.coords.accuracy);
        if(ind){ind.className='gps-ind gi-ok';ind.innerHTML=`✅ ±${acc}m`;}
        if(dLoad) dLoad.style.display='none';
        if(dData) dData.style.display='block';
        if(S_LAT&&S_LNG){
            dist=Math.round(haversine(uLat,uLng,S_LAT,S_LNG));
            const ok=dist<=S_RAD;
            if(dNum) dNum.textContent=dist;
            if(dNum) dNum.style.color=ok?'#16a34a':'#dc2626';
            if(dMsg){dMsg.textContent=ok?`✅ Uko karibu (ruhusa ${S_RAD}m)`:`⚠️ Mbali sana. Rudi shuleni.`;dMsg.style.color=ok?'#166534':'#991b1b';}
            if(ok){
                gpsOk=true;
                if(btn){btn.className='btn-ci btn-ci-on';btn.disabled=false;}
                if(btnT) btnT.textContent='Cheki-in Sasa';
            } else {
                gpsOk=false;
                if(btn){btn.className='btn-ci btn-ci-off';btn.disabled=true;}
                if(btnT) btnT.textContent=`Mbali sana (${dist}m)`;
                if(ciMsg){ciMsg.textContent=`Uko ${dist}m mbali. Radius inaruhusu ${S_RAD}m tu.`;ciMsg.style.color='#991b1b';}
            }
        } else {
            gpsOk=true;
            if(dNum) dNum.textContent='—';
            if(dMsg){dMsg.textContent='GPS ya shule haijawekwa. Inaruhusiwa.';dMsg.style.color='#1d4ed8';}
            if(btn){btn.className='btn-ci btn-ci-on';btn.disabled=false;}
            if(btnT) btnT.textContent='Cheki-in Sasa';
        }
    },err=>{
        if(ind){ind.className='gps-ind gi-err';ind.innerHTML='❌ GPS imezuiwa';}
        if(btnT) btnT.textContent='Wezesha GPS';
        if(dLoad) dLoad.innerHTML='<i class="bi bi-geo-alt-slash" style="font-size:22px;display:block;margin-bottom:6px;color:#ef4444"></i><span style="color:#991b1b;font-size:12px">Ruhusu GPS kwenye browser.</span>';
        toast('Wezesha GPS kwenye simu yako.','err',6000);
    },{enableHighAccuracy:true,timeout:12000,maximumAge:5000});
}

async function doCI(){
    if(!gpsOk||!uLat){toast('GPS bado haipo tayari.','err');return;}
    const btn=document.getElementById('btnCI');
    const btnT=document.getElementById('btnTxt');
    btn.disabled=true; btn.className='btn-ci btn-ci-off';
    if(btnT) btnT.textContent='Inatuma...';
    try {
        const r=await fetch('{{ route("headteacher.attendance") }}',{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
            body:JSON.stringify({latitude:uLat,longitude:uLng,accuracy:null})
        });
        const d=await r.json();
        if(d.success){toast(d.message,'ok',5000);setTimeout(()=>location.reload(),2000);}
        else{toast(d.message||'Hitilafu imetokea.','err',6000);btn.className='btn-ci btn-ci-on';btn.disabled=false;if(btnT)btnT.textContent='Jaribu Tena';}
    } catch(e){toast('Hitilafu ya mtandao.','err');btn.className='btn-ci btn-ci-on';btn.disabled=false;if(btnT)btnT.textContent='Jaribu Tena';}
}

function haversine(a,b,c,d){const R=6371000,r=x=>x*Math.PI/180,da=r(c-a),db=r(d-b),e=Math.sin(da/2)**2+Math.cos(r(a))*Math.cos(r(c))*Math.sin(db/2)**2;return R*2*Math.atan2(Math.sqrt(e),Math.sqrt(1-e));}

// ── TREND CHART ─────────────────────────────────────────────────────
const td=@json($trend);
new Chart(document.getElementById('trendChart'),{type:'line',data:{labels:td.map(d=>d.date),datasets:[
    {label:'Kiwango %',data:td.map(d=>d.rate),borderColor:'#6366f1',backgroundColor:'rgba(99,102,241,.08)',fill:true,tension:.4,pointRadius:3,borderWidth:2},
    {label:'Waliofika',data:td.map(d=>d.count),borderColor:'#10b981',tension:.4,pointRadius:3,yAxisID:'y2',borderWidth:2}
]},options:{responsive:true,maintainAspectRatio:false,interaction:{mode:'index',intersect:false},
    plugins:{legend:{labels:{font:{size:11},boxWidth:10}},tooltip:{backgroundColor:'#1e293b',borderColor:'#334155',borderWidth:1}},
    scales:{x:{ticks:{font:{size:10}},grid:{color:'rgba(0,0,0,.04)'}},y:{ticks:{font:{size:10},callback:v=>v+'%'},grid:{color:'rgba(0,0,0,.04)'},max:100,min:0},y2:{position:'right',ticks:{font:{size:10},color:'#10b981'},grid:{display:false}}}}
});

document.addEventListener('DOMContentLoaded',()=>{
    @if(!$htAttendedToday)
    initGPS();
    setInterval(initGPS,30000);
    @endif
});
</script>
@endpush
</x-layout>