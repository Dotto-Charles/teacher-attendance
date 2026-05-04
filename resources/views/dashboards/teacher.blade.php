{{-- resources/views/dashboards/teacher.blade.php --}}
<x-layout title="Dashboard">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
/* ── FONTS & RESET ── */
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap');

.teacher-dash { font-family:'Sora',sans-serif; }

/* ── HERO BANNER ── */
.hero-banner {
    background: linear-gradient(135deg,#0f172a 0%,#1e3a5f 50%,#0f172a 100%);
    border-radius: 20px;
    padding: 28px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(59,130,246,.2);
}
.hero-banner::before {
    content:'';
    position:absolute;top:0;left:0;right:0;bottom:0;
    background: radial-gradient(ellipse at 70% 50%, rgba(59,130,246,.15) 0%, transparent 60%),
                radial-gradient(ellipse at 10% 80%, rgba(16,185,129,.08) 0%, transparent 50%);
    pointer-events:none;
}
.hero-grid { display:grid; grid-template-columns:1fr auto; gap:20px; align-items:center; position:relative; }
.hero-greeting { font-size:13px; color:rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; }
.hero-name { font-size:26px; font-weight:800; color:#fff; line-height:1.2; margin-bottom:4px; }
.hero-role { font-size:13px; color:rgba(255,255,255,.55); }
.hero-meta { display:flex; gap:14px; margin-top:14px; flex-wrap:wrap; }
.hero-meta-item { display:flex; align-items:center; gap:6px; font-size:12px; color:rgba(255,255,255,.55); }
.hero-meta-item strong { color:rgba(255,255,255,.85); }
.hero-checkin-status { text-align:center; }
.big-check {
    width:70px; height:70px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:28px; margin:0 auto 8px;
    transition: transform .3s, box-shadow .3s;
}
.big-check.done   { background:rgba(16,185,129,.2); border:2px solid rgba(16,185,129,.4); box-shadow:0 0 24px rgba(16,185,129,.2); }
.big-check.pending{ background:rgba(245,158,11,.15); border:2px dashed rgba(245,158,11,.4); animation:pulse 2s infinite; }
.big-check.no-school{ background:rgba(100,116,139,.15); border:2px dashed rgba(100,116,139,.3); }
@keyframes pulse { 0%,100%{box-shadow:0 0 0 0 rgba(245,158,11,.3)} 50%{box-shadow:0 0 0 12px rgba(245,158,11,0)} }
.checkin-label { font-size:12px; color:rgba(255,255,255,.5); }
.checkin-time  { font-size:18px; font-weight:700; color:#fff; font-family:'JetBrains Mono',monospace; }

/* ── STAT CARDS ── */
.stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:12px; margin-bottom:20px; }
.s-card {
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:16px;
    padding:16px;
    position:relative;
    overflow:hidden;
    transition:transform .2s, box-shadow .2s;
}
.s-card:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,.08); }
.s-card::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; }
.s-card.c-blue::after   { background:linear-gradient(90deg,#3b82f6,#6366f1); }
.s-card.c-green::after  { background:linear-gradient(90deg,#10b981,#059669); }
.s-card.c-yellow::after { background:linear-gradient(90deg,#f59e0b,#d97706); }
.s-card.c-purple::after { background:linear-gradient(90deg,#8b5cf6,#7c3aed); }
.s-card-icon { font-size:20px; margin-bottom:8px; }
.s-card-val  { font-size:28px; font-weight:800; font-family:'JetBrains Mono',monospace; line-height:1; color:#1e293b; }
.s-card-lbl  { font-size:11px; color:#94a3b8; margin-top:4px; font-weight:500; }
.s-card-sub  { font-size:11px; margin-top:6px; font-weight:600; }

/* ── GPS CHECK-IN CARD ── */
.checkin-card {
    background:#fff;
    border:2px solid #e2e8f0;
    border-radius:20px;
    overflow:hidden;
    margin-bottom:20px;
    transition:border-color .3s;
}
.checkin-card.ready    { border-color:#10b981; }
.checkin-card.error    { border-color:#ef4444; }
.checkin-card.done     { border-color:#10b981; background:#f0fdf4; }
.checkin-header {
    padding:18px 20px;
    display:flex; align-items:center; justify-content:space-between;
    border-bottom:1px solid #f1f5f9;
    flex-wrap:wrap; gap:10px;
}
.checkin-title { font-size:15px; font-weight:700; color:#1e293b; }
.checkin-body  { padding:20px; }

/* GPS Status indicator */
.gps-indicator {
    display:inline-flex; align-items:center; gap:6px;
    padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;
}
.gps-loading  { background:#f8fafc; color:#94a3b8; }
.gps-found    { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
.gps-error    { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

/* Distance meter */
.distance-meter {
    background:#f8fafc;
    border-radius:14px;
    padding:16px;
    text-align:center;
    margin:14px 0;
}
.dist-ring-wrap { position:relative; display:inline-block; }
.dist-ring-text { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; }
.dist-val   { font-size:20px; font-weight:800; font-family:'JetBrains Mono',monospace; }
.dist-unit  { font-size:10px; color:#94a3b8; }
.dist-label { font-size:12px; color:#64748b; margin-top:6px; }

/* Check-in button */
.btn-checkin {
    width:100%; padding:14px;
    border-radius:14px;
    font-size:15px; font-weight:700;
    border:none; cursor:pointer;
    font-family:'Sora',sans-serif;
    display:flex; align-items:center; justify-content:center; gap:8px;
    transition:all .25s;
}
.btn-checkin.active {
    background:linear-gradient(135deg,#0d6efd,#6366f1);
    color:#fff;
    box-shadow:0 6px 20px rgba(13,110,253,.3);
}
.btn-checkin.active:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(13,110,253,.4); }
.btn-checkin.disabled-btn { background:#f1f5f9; color:#94a3b8; cursor:not-allowed; }
.btn-checkin.done-btn     { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; cursor:default; }

/* ── STATUS ALERT ── */
.status-card {
    border-radius:16px;
    padding:20px;
    margin-bottom:20px;
    display:flex; gap:16px; align-items:flex-start;
}
.status-pending  { background:#fffbeb; border:1px solid #fde68a; }
.status-rejected { background:#fef2f2; border:1px solid #fecaca; }
.status-noschool { background:#eff6ff; border:1px solid #bfdbfe; }
.status-icon  { font-size:28px; flex-shrink:0; }
.status-title { font-size:15px; font-weight:700; margin-bottom:4px; }
.status-desc  { font-size:13px; opacity:.8; }

/* ── 14-DAY CALENDAR ── */
.cal-grid {
    display:grid; grid-template-columns:repeat(7,1fr); gap:6px;
}
.cal-day {
    border-radius:12px;
    padding:8px 4px;
    text-align:center;
    position:relative;
    transition:transform .15s;
}
.cal-day:hover { transform:scale(1.05); }
.cal-day.came     { background:#eff6ff; border:1px solid #bfdbfe; }
.cal-day.missed   { background:#fef2f2; border:1px solid #fecaca; }
.cal-day.weekend  { background:#f8fafc; border:1px dashed #e2e8f0; opacity:.6; }
.cal-day.today    { border:2px solid #0d6efd !important; }
.cal-day-label { font-size:9px; color:#94a3b8; font-weight:600; text-transform:uppercase; }
.cal-day-num   { font-size:14px; font-weight:700; color:#1e293b; line-height:1; margin:2px 0; }
.cal-day-icon  { font-size:13px; }

/* ── SCHOOL CARD ── */
.school-info-card {
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:16px;
    overflow:hidden;
    margin-bottom:20px;
}
.school-info-header {
    padding:16px 18px;
    background:linear-gradient(135deg,#f8fafc,#eff6ff);
    border-bottom:1px solid #e2e8f0;
    display:flex; align-items:center; gap:12px;
}
.school-logo {
    width:46px; height:46px; border-radius:12px;
    background:linear-gradient(135deg,#0d6efd,#6366f1);
    display:flex; align-items:center; justify-content:center;
    font-size:20px; color:#fff; flex-shrink:0;
}
.school-name { font-size:15px; font-weight:700; color:#1e293b; }
.school-ward { font-size:12px; color:#64748b; margin-top:2px; }
.school-stats { display:grid; grid-template-columns:repeat(3,1fr); }
.school-stat { padding:14px; text-align:center; border-right:1px solid #f1f5f9; }
.school-stat:last-child { border-right:none; }
.school-stat-val { font-size:20px; font-weight:800; font-family:'JetBrains Mono',monospace; color:#1e293b; }
.school-stat-lbl { font-size:10px; color:#94a3b8; margin-top:3px; }

/* ── REGISTER SCHOOL ── */
.register-card {
    background:#fff;
    border:2px dashed #bfdbfe;
    border-radius:20px;
    padding:28px;
    text-align:center;
    margin-bottom:20px;
}
.register-icon { font-size:48px; margin-bottom:14px; display:block; }
.register-title{ font-size:18px; font-weight:700; color:#1e293b; margin-bottom:8px; }
.register-desc { font-size:13px; color:#64748b; margin-bottom:20px; }

/* ── SELECT SCHOOL ── */
.school-option {
    display:flex; align-items:center; gap:12px;
    padding:12px 16px;
    border:1px solid #e2e8f0;
    border-radius:12px;
    cursor:pointer;
    transition:all .2s;
    margin-bottom:8px;
    background:#fff;
}
.school-option:hover, .school-option.selected { border-color:#0d6efd; background:#eff6ff; }
.school-option input[type=radio] { width:16px; height:16px; accent-color:#0d6efd; flex-shrink:0; }
.so-name { font-size:13px; font-weight:600; color:#1e293b; }
.so-meta { font-size:11px; color:#94a3b8; margin-top:2px; }

/* ── QUICK LINKS ── */
.quick-links { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:12px; margin-bottom:20px; }
.ql-card {
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:16px;
    padding:18px 14px;
    text-align:center;
    text-decoration:none;
    transition:all .2s;
    display:block;
}
.ql-card:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,.08); border-color:#0d6efd; text-decoration:none; }
.ql-icon  { font-size:30px; margin-bottom:8px; display:block; }
.ql-label { font-size:12px; font-weight:700; color:#1e293b; }

/* ── TOAST ── */
.toast-wrap {
    position:fixed; top:20px; right:20px; z-index:9999;
    display:flex; flex-direction:column; gap:8px; pointer-events:none;
}
.toast {
    padding:14px 18px;
    border-radius:12px;
    font-size:13px; font-weight:600;
    display:flex; align-items:center; gap:10px;
    min-width:280px; max-width:360px;
    animation:slideIn .3s ease;
    pointer-events:all;
    box-shadow:0 8px 24px rgba(0,0,0,.12);
}
.toast-success { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }
.toast-error   { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }
.toast-info    { background:#eff6ff; border:1px solid #bfdbfe; color:#1d4ed8; }
@keyframes slideIn { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:translateX(0)} }

/* ── SECTION TITLE ── */
.sec-title { font-size:14px; font-weight:700; color:#1e293b; margin-bottom:14px; display:flex; align-items:center; gap:8px; }

/* ── RANK BADGE ── */
.rank-badge {
    display:inline-flex; align-items:center; gap:6px;
    padding:4px 14px;
    border-radius:20px;
    font-size:12px; font-weight:700;
    background:linear-gradient(135deg,#fbbf24,#f59e0b);
    color:#fff;
}

/* ── RESPONSIVE ── */
@media(max-width:576px){
    .hero-grid { grid-template-columns:1fr; }
    .hero-checkin-status { display:flex; align-items:center; gap:14px; justify-content:flex-start; }
    .big-check { margin:0; }
    .cal-grid { grid-template-columns:repeat(7,1fr); gap:3px; }
    .cal-day { padding:5px 2px; border-radius:8px; }
    .cal-day-label { font-size:7px; }
    .cal-day-num   { font-size:11px; }
}
</style>

<div class="teacher-dash">
{{-- TOAST CONTAINER --}}
<div class="toast-wrap" id="toastWrap"></div>

{{-- ══════════════════════════════════════════════════════
     HERO BANNER
════════════════════════════════════════════════════════ --}}
<div class="hero-banner">
    <div class="hero-grid">
        <div>
            <div class="hero-greeting">{{ now()->format('l, d M Y') }}</div>
            <div class="hero-name">{{ $teacher->first_name }} {{ $teacher->last_name }}</div>
            <div class="hero-role">
                {{ $teacher->role === 'head_teacher' ? '🎓 Mwalimu Mkuu' : '👨‍🏫 Mwalimu' }}
                @if($teacher->school)
                · {{ $teacher->school->name }}
                @endif
            </div>
            <div class="hero-meta">
                <div class="hero-meta-item">
                    <span>📊</span>
                    <span>Mwezi huu: <strong>{{ $monthCount }}/{{ $workDaysMonth }}</strong> siku</span>
                </div>
                @if($rank)
                <div class="hero-meta-item">
                    <span>🏆</span>
                    <span>Nafasi shuleni: <strong>#{{ $rank }}</strong></span>
                </div>
                @endif
                <div class="hero-meta-item">
                    <span>📅</span>
                    <span>Mwaka: <strong>{{ $yearCount }}</strong> siku</span>
                </div>
            </div>
        </div>
        <div class="hero-checkin-status">
            @if(!$teacher->school)
            <div class="big-check no-school">🏫</div>
            <div class="checkin-label" style="color:rgba(255,255,255,.5)">Huna shule</div>
            @elseif($attendedToday)
            <div class="big-check done">✅</div>
            <div class="checkin-time">{{ $todayRecord ? \Carbon\Carbon::parse($todayRecord->created_at)->format('H:i') : '' }}</div>
            <div class="checkin-label">Umesaini</div>
            @else
            <div class="big-check pending">⏰</div>
            <div class="checkin-label">Bado haujasaini</div>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     STATUS ALERTS
════════════════════════════════════════════════════════ --}}
@if($teacher->status === 'pending')
<div class="status-card status-pending">
    <div class="status-icon">⏳</div>
    <div>
        <div class="status-title">Akaunti yako inasubiri idhini</div>
        <div class="status-desc">
            @if($teacher->school)
            Mwalimu Mkuu wa <strong>{{ $teacher->school->name }}</strong> atachukulia hatua hivi karibuni.
            Utapata taarifa utakapoidhinishwa.
            @else
            Jisajili kwenye shule kwanza ili mwalimu mkuu aweze kukuona na kukuidhinisha.
            @endif
        </div>
    </div>
</div>
@elseif($teacher->status === 'rejected')
<div class="status-card status-rejected">
    <div class="status-icon">❌</div>
    <div>
        <div class="status-title">Akaunti yako imekataliwa</div>
        <div class="status-desc">Wasiliana na Mwalimu Mkuu au AEK wa shule yako kwa maelezo zaidi.</div>
    </div>
</div>
@elseif(!$teacher->school_id)
<div class="status-card status-noschool">
    <div class="status-icon">🏫</div>
    <div>
        <div class="status-title">Bado hujajisajili kwenye shule</div>
        <div class="status-desc">Jisajili kwenye shule yako ili uweze kuanza kusaini kila siku. Baada ya kujisajili, subiri idhini ya Mwalimu Mkuu.</div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════
     STATS CARDS (approved only)
════════════════════════════════════════════════════════ --}}
@if($teacher->status === 'approved')
<div class="stat-grid">
    <div class="s-card c-blue">
        <div class="s-card-icon">📅</div>
        <div class="s-card-val" style="color:{{ $attendedToday?'#16a34a':'#1e293b' }}">
            {{ $attendedToday ? '✅' : '⏰' }}
        </div>
        <div class="s-card-lbl">Leo</div>
        <div class="s-card-sub" style="color:{{ $attendedToday?'#16a34a':'#ca8a04' }}">
            {{ $attendedToday ? 'Umecheki-in' : 'Bado' }}
        </div>
    </div>
    <div class="s-card c-green">
        <div class="s-card-icon">📊</div>
        <div class="s-card-val" style="color:#0d6efd">{{ $monthCount }}</div>
        <div class="s-card-lbl">Siku Mwezi</div>
        <div class="s-card-sub" style="color:{{ $monthRate>=80?'#16a34a':($monthRate>=60?'#ca8a04':'#dc2626') }}">
            {{ $monthRate }}%
        </div>
    </div>
    <div class="s-card c-yellow">
        <div class="s-card-icon">📈</div>
        <div class="s-card-val" style="color:#d97706">{{ $yearCount }}</div>
        <div class="s-card-lbl">Siku Mwaka</div>
        <div class="s-card-sub" style="color:#94a3b8">{{ now()->year }}</div>
    </div>
    @if($rank)
    <div class="s-card c-purple">
        <div class="s-card-icon">🏆</div>
        <div class="s-card-val" style="color:#7c3aed">#{{ $rank }}</div>
        <div class="s-card-lbl">Nafasi Shuleni</div>
        <div class="s-card-sub" style="color:#94a3b8">Mwezi huu</div>
    </div>
    @endif
</div>
@endif

{{-- ══════════════════════════════════════════════════════
     GPS CHECK-IN CARD
════════════════════════════════════════════════════════ --}}
@if($teacher->school_id && $teacher->status === 'approved')
<div class="checkin-card {{ $attendedToday ? 'done' : '' }}" id="checkinCard">
    <div class="checkin-header">
        <div>
            <div class="checkin-title">📍 Cheki-in ya GPS</div>
            <div style="font-size:12px;color:#64748b;margin-top:2px">
                {{ $teacher->school->name }} · Radius: {{ $teacher->school->radius ?? 50 }}m
            </div>
        </div>
        <div class="gps-indicator gps-loading" id="gpsStatus">
            <i class="bi bi-arrow-repeat" style="animation:spin 1s linear infinite"></i>
            Inatafuta GPS...
        </div>
    </div>
    <div class="checkin-body">

        @if($attendedToday)
        {{-- Already done --}}
        <div style="text-align:center;padding:20px">
            <div style="font-size:48px;margin-bottom:10px">🎉</div>
            <div style="font-size:16px;font-weight:700;color:#166534">Umesaini Leo!</div>
            <div style="font-size:13px;color:#64748b;margin-top:4px">
                Saa ya kufika:
                <strong style="font-family:'JetBrains Mono',monospace">
                    {{ $todayRecord ? \Carbon\Carbon::parse($todayRecord->created_at)->format('H:i') : '' }}
                </strong>
                @if($todayRecord?->distance)
                · Umbali: <strong>{{ $todayRecord->distance }}m</strong>
                @endif
            </div>
        </div>
        @else
        {{-- Distance meter --}}
        <div class="distance-meter" id="distanceMeter">
            <div id="distLoading" style="color:#94a3b8;font-size:13px">
                <i class="bi bi-geo-alt" style="font-size:24px;display:block;margin-bottom:8px"></i>
                Inangoja GPS...
            </div>
            <div id="distData" style="display:none">
                <div class="dist-ring-wrap">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="42" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                        <circle id="distRing" cx="50" cy="50" r="42" fill="none" stroke="#0d6efd"
                            stroke-width="8" stroke-linecap="round"
                            stroke-dasharray="264" stroke-dashoffset="264"
                            transform="rotate(-90 50 50)" style="transition:stroke-dashoffset 1s ease, stroke .5s"/>
                    </svg>
                    <div class="dist-ring-text">
                        <div class="dist-val" id="distVal">—</div>
                        <div class="dist-unit">mita</div>
                    </div>
                </div>
                <div class="dist-label" id="distLabel">Umbali wako kutoka shuleni</div>
            </div>
        </div>

        {{-- Status message --}}
        <div id="gpsMsg" style="text-align:center;font-size:13px;margin-bottom:14px;min-height:20px"></div>

        {{-- Check-in button --}}
        <button class="btn-checkin disabled-btn" id="btnCheckin" disabled onclick="doCheckIn()">
            <i class="bi bi-geo-alt-fill"></i>
            <span id="btnText">Inatafuta eneo lako...</span>
        </button>
        @endif
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════
     REGISTER SCHOOL (no school yet)
════════════════════════════════════════════════════════ --}}
@if(!$teacher->school_id)
<div class="register-card" id="registerSection">
    <span class="register-icon">🏫</span>
    <div class="register-title">Jisajili kwenye Shule yako</div>
    <div class="register-desc">Chagua shule unayofanya kazi. Baada ya kujisajili utasubiri idhini ya Mwalimu Mkuu wa shule hiyo.</div>

    <form action="{{ route('teacher.register.store') }}" method="POST">
        @csrf
        <div style="max-height:280px;overflow-y:auto;text-align:left;margin-bottom:16px" id="schoolList">
            @foreach($availableSchools as $sc)
            <label class="school-option">
                <input type="radio" name="school_id" value="{{ $sc->id }}" required>
                <div style="flex:1">
                    <div class="so-name">{{ $sc->name }}</div>
                    <div class="so-meta">📍 {{ $sc->ward->name ?? '—' }} @if($sc->code)· {{ $sc->code }}@endif</div>
                </div>
                @if(!$sc->latitude)
                <span style="font-size:10px;color:#94a3b8">Hakuna GPS</span>
                @endif
            </label>
            @endforeach
        </div>

        {{-- Search schools --}}
        <div style="position:relative;margin-bottom:12px">
            <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
            <input type="text" placeholder="Tafuta shule..." id="schoolSearch"
                   style="width:100%;padding:10px 12px 10px 36px;border:1px solid #e2e8f0;border-radius:10px;font-size:13px;outline:none;font-family:'Sora',sans-serif"
                   oninput="filterSchools(this.value)">
        </div>

        @error('school_id')
        <div style="color:#dc2626;font-size:12px;margin-bottom:10px">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-send-fill me-1"></i> Wasilisha Ombi
        </button>
    </form>
</div>
@endif

{{-- ══════════════════════════════════════════════════════
     SCHOOL INFO (has school)
════════════════════════════════════════════════════════ --}}
@if($teacher->school)
<div class="school-info-card">
    <div class="school-info-header">
        <div class="school-logo">🏫</div>
        <div>
            <div class="school-name">{{ $teacher->school->name }}</div>
            <div class="school-ward">📍 {{ $teacher->school->ward->name ?? '—' }}</div>
        </div>
        @if($teacher->status === 'approved')
        <span style="margin-left:auto" class="rank-badge">
            <i class="bi bi-patch-check-fill"></i> Idhinishwa
        </span>
        @else
        <span style="margin-left:auto;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:600;background:#fffbeb;border:1px solid #fde68a;color:#92400e">
            ⏳ Inasubiri
        </span>
        @endif
    </div>
    <div class="school-stats">
        <div class="school-stat">
            <div class="school-stat-val" style="color:#0d6efd">{{ $schoolTeachers }}</div>
            <div class="school-stat-lbl">Walimu</div>
        </div>
        <div class="school-stat">
            <div class="school-stat-val" style="color:#10b981">
                {{ $teacher->school->radius ?? 50 }}m
            </div>
            <div class="school-stat-lbl">Radius ya GPS</div>
        </div>
        <div class="school-stat">
            <div class="school-stat-val" style="font-size:14px;color:{{ $headTeacher?'#7c3aed':'#94a3b8' }}">
                {{ $headTeacher ? $headTeacher->first_name : '—' }}
            </div>
            <div class="school-stat-lbl">Mwalimu Mkuu</div>
        </div>
    </div>
</div>

{{-- Transfer pending notice --}}
@if($pendingTransfer)
<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:12px 16px;font-size:13px;color:#92400e;margin-bottom:20px;display:flex;align-items:center;gap:10px">
    <i class="bi bi-arrow-left-right" style="font-size:18px;flex-shrink:0"></i>
    <span>Una ombi la uhamisho linalosubiri kwenda <strong>{{ $pendingTransfer->toSchool->name ?? '—' }}</strong>.</span>
</div>
@endif
@endif

{{-- ══════════════════════════════════════════════════════
     14-DAY ATTENDANCE CALENDAR
════════════════════════════════════════════════════════ --}}
@if($teacher->status === 'approved')
<div class="sec-title"><i class="bi bi-calendar-week-fill text-primary"></i> Mahudhurio — Siku 14 Zilizopita</div>
<div class="cal-grid" style="margin-bottom:24px">
    @foreach($recentDays as $day)
    <div class="cal-day {{ $day['weekend']?'weekend':($day['came']?'came':'missed') }} {{ $day['today']?'today':'' }}"
         title="{{ $day['date'] }} {{ $day['month'] }}">
        <div class="cal-day-label">{{ $day['day'] }}</div>
        <div class="cal-day-num">{{ $day['date'] }}</div>
        <div class="cal-day-icon">
            @if($day['weekend']) —
            @elseif($day['came']) ✅
            @else ❌
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Month progress --}}
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:16px 18px;margin-bottom:24px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
        <span style="font-size:13px;font-weight:700;color:#1e293b">Kiwango cha Mwezi — {{ now()->format('F Y') }}</span>
        <span style="font-size:18px;font-weight:800;font-family:'JetBrains Mono',monospace;color:{{ $monthRate>=80?'#16a34a':($monthRate>=60?'#ca8a04':'#dc2626') }}">{{ $monthRate }}%</span>
    </div>
    <div style="height:10px;background:#f1f5f9;border-radius:99px;overflow:hidden">
        <div style="height:100%;width:{{ $monthRate }}%;background:{{ $monthRate>=80?'linear-gradient(90deg,#10b981,#059669)':($monthRate>=60?'linear-gradient(90deg,#f59e0b,#d97706)':'linear-gradient(90deg,#ef4444,#dc2626)') }};border-radius:99px;transition:width 1s ease"></div>
    </div>
    <div style="display:flex;justify-content:space-between;margin-top:8px;font-size:11px;color:#94a3b8">
        <span>{{ $monthCount }} siku kati ya {{ $workDaysMonth }}</span>
        <span>{{ $workDaysMonth - $monthCount }} zilizokosekana</span>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════
     QUICK LINKS
════════════════════════════════════════════════════════ --}}
<div class="sec-title"><i class="bi bi-lightning-charge-fill text-warning"></i> Vitendo vya Haraka</div>
<div class="quick-links">
    <a href="{{ route('attendance.report') }}" class="ql-card">
        <span class="ql-icon">📊</span>
        <div class="ql-label">Historia ya Mahudhurio</div>
    </a>
    <a href="{{ route('profile.edit') }}" class="ql-card">
        <span class="ql-icon">👤</span>
        <div class="ql-label">Wasifu Wangu</div>
    </a>
    @if($teacher->school_id)
    <a href="{{ route('teacher.register.school') }}" class="ql-card">
        <span class="ql-icon">🔄</span>
        <div class="ql-label">Omba Uhamisho</div>
    </a>
    @endif
    <a href="{{ route('attendance.export.pdf', ['user_id' => $teacher->id]) }}" class="ql-card"
       onclick="showToast('Inatengeneza PDF...','info'); return true;">
        <span class="ql-icon">📄</span>
        <div class="ql-label">Export PDF</div>
    </a>
</div>

</div>{{-- /teacher-dash --}}

@push('scripts')
<script>
const SCHOOL_LAT    = {{ $teacher->school?->latitude ?? 'null' }};
const SCHOOL_LNG    = {{ $teacher->school?->longitude ?? 'null' }};
const SCHOOL_RADIUS = {{ $teacher->school?->radius ?? 500 }};
const ALREADY_DONE  = {{ $attendedToday ? 'true' : 'false' }};

let userLat = null, userLng = null, currentDistance = null, gpsReady = false;

// ── TOAST ────────────────────────────────────────────────────────────
function showToast(msg, type='success', duration=4000) {
    const wrap = document.getElementById('toastWrap');
    const t = document.createElement('div');
    t.className = `toast toast-${type}`;
    const icons = {success:'✅', error:'❌', info:'ℹ️'};
    t.innerHTML = `<span>${icons[type]??'ℹ️'}</span><span>${msg}</span>`;
    wrap.appendChild(t);
    setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .4s'; setTimeout(()=>t.remove(),400); }, duration);
}

// ── GPS ──────────────────────────────────────────────────────────────
function updateGPS() {
    if (ALREADY_DONE) return;
    const statusEl = document.getElementById('gpsStatus');
    const btnEl    = document.getElementById('btnCheckin');
    const btnText  = document.getElementById('btnText');
    const msgEl    = document.getElementById('gpsMsg');
    const distData = document.getElementById('distData');
    const distLoad = document.getElementById('distLoading');
    const distVal  = document.getElementById('distVal');
    const distLabel= document.getElementById('distLabel');
    const distRing = document.getElementById('distRing');

    if (!navigator.geolocation) {
        if(statusEl) { statusEl.className='gps-indicator gps-error'; statusEl.innerHTML='❌ GPS haipo'; }
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            userLat = pos.coords.latitude;
            userLng = pos.coords.longitude;
            const acc = Math.round(pos.coords.accuracy);

            if(statusEl) { statusEl.className='gps-indicator gps-found'; statusEl.innerHTML=`✅ GPS (±${acc}m)`; }
            if(distLoad) distLoad.style.display='none';
            if(distData) distData.style.display='block';

            if (SCHOOL_LAT && SCHOOL_LNG) {
                currentDistance = haversine(userLat, userLng, SCHOOL_LAT, SCHOOL_LNG);
                const dist = Math.round(currentDistance);
                const ok = dist <= SCHOOL_RADIUS;

                if(distVal)   distVal.textContent = dist;
                if(distLabel) {
                    distLabel.textContent = ok
                        ? `✅ Uko karibu! (${SCHOOL_RADIUS}m inaruhusiwa)`
                        : `⚠️ Mbali sana. Rudi shuleni. (${SCHOOL_RADIUS}m inaruhusiwa)`;
                    distLabel.style.color = ok ? '#166534' : '#991b1b';
                }

                // Update ring
                if(distRing) {
                    const pct    = Math.min(dist / SCHOOL_RADIUS, 1);
                    const circ   = 2 * Math.PI * 42;
                    const offset = circ * (1 - (ok ? 1 - pct : pct));
                    distRing.style.strokeDashoffset = offset;
                    distRing.style.stroke = ok ? '#10b981' : '#ef4444';
                }

                if (ok) {
                    gpsReady = true;
                    if(btnEl)   { btnEl.className='btn-checkin active'; btnEl.disabled=false; }
                    if(btnText) btnText.textContent='Cheki-in Sasa';
                    if(msgEl)   { msgEl.textContent=''; }
                } else {
                    gpsReady = false;
                    if(btnEl)   { btnEl.className='btn-checkin disabled-btn'; btnEl.disabled=true; }
                    if(btnText) btnText.textContent=`Mbali sana (${dist}m)`;
                    if(msgEl)   { msgEl.style.color='#991b1b'; msgEl.textContent=`Uko ${dist}m mbali. Rudi shuleni kwanza.`; }
                }
            } else {
                // No school GPS set — allow anyway
                gpsReady = true;
                currentDistance = null;
                if(distVal)   distVal.textContent='—';
                if(distLabel) { distLabel.textContent='GPS ya shule haijawekwa. Cheki-in inaruhusiwa.'; distLabel.style.color='#1d4ed8'; }
                if(btnEl)     { btnEl.className='btn-checkin active'; btnEl.disabled=false; }
                if(btnText)   btnText.textContent='Cheki-in Sasa';
            }
        },
        (err) => {
            if(statusEl) { statusEl.className='gps-indicator gps-error'; statusEl.innerHTML='❌ GPS imezuiwa'; }
            if(btnText)  btnText.textContent='Wezesha GPS kwenye simu';
            if(document.getElementById('distLoading')) {
                document.getElementById('distLoading').innerHTML='<i class="bi bi-geo-alt-slash" style="font-size:24px;display:block;margin-bottom:8px;color:#ef4444"></i><span style="color:#991b1b;font-size:13px">GPS imezuiwa. Ruhusu GPS kwenye browser yako.</span>';
            }
            showToast('Washa GPS kwenye simu yako ili uweze kusaini.', 'error', 6000);
        },
        { enableHighAccuracy:true, timeout:12000, maximumAge:5000 }
    );
}

// ── CHECK-IN ─────────────────────────────────────────────────────────
async function doCheckIn() {
    if (!gpsReady || !userLat) {
        showToast('GPS bado haipo tayari. Subiri kidogo.', 'error');
        return;
    }

    const btnEl  = document.getElementById('btnCheckin');
    const btnTxt = document.getElementById('btnText');
    btnEl.disabled = true;
    btnEl.className = 'btn-checkin disabled-btn';
    if(btnTxt) btnTxt.textContent = 'Inatuma...';

    try {
        const res = await fetch('{{ route("teacher.checkin") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: JSON.stringify({
                latitude:  userLat,
                longitude: userLng,
                accuracy:  null,
            })
        });

        const data = await res.json();

        if (data.success) {
            showToast(data.message, 'success', 5000);
            // Refresh page after 2s
            setTimeout(() => location.reload(), 2000);
        } else {
            showToast(data.message || 'Hitilafu imetokea.', 'error', 6000);
            btnEl.className = 'btn-checkin active';
            btnEl.disabled  = false;
            if(btnTxt) btnTxt.textContent = 'Jaribu Tena';
        }
    } catch (e) {
        showToast('Hitilafu ya mtandao. Jaribu tena.', 'error');
        btnEl.className = 'btn-checkin active';
        btnEl.disabled  = false;
        if(btnTxt) btnTxt.textContent = 'Jaribu Tena';
    }
}

// ── HAVERSINE ────────────────────────────────────────────────────────
function haversine(lat1,lon1,lat2,lon2) {
    const R = 6371000, toRad = d => d * Math.PI / 180;
    const dLat = toRad(lat2-lat1), dLon = toRad(lon2-lon1);
    const a = Math.sin(dLat/2)**2 + Math.cos(toRad(lat1))*Math.cos(toRad(lat2))*Math.sin(dLon/2)**2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

// ── SCHOOL SEARCH ────────────────────────────────────────────────────
function filterSchools(q) {
    document.querySelectorAll('#schoolList .school-option').forEach(el => {
        el.style.display = el.querySelector('.so-name').textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
    });
}

// ── INIT ─────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    @if($teacher->school_id && $teacher->status === 'approved' && !$attendedToday)
    updateGPS();
    // Refresh GPS every 30s
    setInterval(updateGPS, 30000);
    @endif

    // School option highlight on click
    document.querySelectorAll('.school-option').forEach(el => {
        el.addEventListener('click', () => {
            document.querySelectorAll('.school-option').forEach(o => o.classList.remove('selected'));
            el.classList.add('selected');
        });
    });
});

// CSS spin animation
const style = document.createElement('style');
style.textContent = '@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}';
document.head.appendChild(style);
</script>
@endpush

</x-layout>