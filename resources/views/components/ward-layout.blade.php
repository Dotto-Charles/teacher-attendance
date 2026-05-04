{{-- resources/views/components/ward-layout.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Ward Officer' }} · EduAttend</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{ $styles ?? '' }}
    <style>
        :root {
            --bg:       #0b1512;
            --surface:  #111d19;
            --surface2: #172420;
            --border:   #1f3329;
            --accent:   #10b981;
            --accent2:  #059669;
            --accent3:  #34d399;
            --blue:     #3b82f6;
            --yellow:   #f59e0b;
            --red:      #ef4444;
            --pink:     #ec4899;
            --purple:   #8b5cf6;
            --text:     #e2faf3;
            --muted:    #4d7a68;
            --font:     'DM Sans', sans-serif;
            --mono:     'DM Mono', monospace;
            --r:        14px;
            --r-sm:     8px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: var(--font); background: var(--bg); color: var(--text); min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed; left: 0; top: 0; bottom: 0; width: 240px;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 100; transition: transform .3s;
        }
        .sidebar-logo {
            padding: 22px 20px 18px;
            border-bottom: 1px solid var(--border);
        }
        .logo-badge { display: flex; align-items: center; gap: 10px; }
        .logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .logo-text { font-size: 14px; font-weight: 700; color: var(--text); line-height: 1.2; }
        .logo-sub  { font-size: 11px; color: var(--muted); }

        .sidebar-ward {
            margin: 0 12px;
            padding: 10px 12px;
            background: rgba(16,185,129,.08);
            border: 1px solid rgba(16,185,129,.15);
            border-radius: var(--r-sm);
            margin-top: 12px;
        }
        .ward-label { font-size: 10px; color: var(--muted); text-transform: uppercase; letter-spacing: .8px; font-weight: 600; }
        .ward-name  { font-size: 13px; font-weight: 700; color: var(--accent3); margin-top: 2px; }

        .sidebar-nav { flex: 1; padding: 14px 12px; overflow-y: auto; }
        .nav-section { margin-bottom: 22px; }
        .nav-label {
            font-size: 10px; font-weight: 600; color: var(--muted);
            letter-spacing: 1.2px; text-transform: uppercase;
            padding: 0 8px; margin-bottom: 6px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: var(--r-sm);
            font-size: 13.5px; font-weight: 500; color: var(--muted);
            text-decoration: none; transition: all .2s; margin-bottom: 2px;
        }
        .nav-item:hover { background: var(--surface2); color: var(--text); }
        .nav-item.active { background: rgba(16,185,129,.12); color: var(--accent); }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; }
        .nav-badge {
            margin-left: auto; background: var(--red); color: #fff;
            font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 20px;
        }
        .nav-badge.green { background: var(--accent); }

        .sidebar-footer { padding: 14px 12px; border-top: 1px solid var(--border); }
        .user-card {
            display: flex; align-items: center; gap: 10px; padding: 10px;
            background: var(--surface2); border-radius: var(--r-sm);
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; flex-shrink: 0; color: #fff;
        }
        .user-name { font-size: 13px; font-weight: 600; color: var(--text); }
        .user-role { font-size: 11px; color: var(--accent); }

        /* ── MAIN ── */
        .main { margin-left: 240px; min-height: 100vh; display: flex; flex-direction: column; }

        /* ── TOPBAR ── */
        .topbar {
            position: sticky; top: 0; z-index: 50;
            background: rgba(11,21,18,.92); backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            padding: 13px 28px;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
        }
        .topbar-left  { display: flex; align-items: center; gap: 14px; }
        .hamburger {
            display: none; background: none; border: none;
            color: var(--text); font-size: 20px; cursor: pointer; padding: 4px;
        }
        .breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 13px; }
        .breadcrumb a { color: var(--muted); text-decoration: none; transition: color .15s; }
        .breadcrumb a:hover { color: var(--text); }
        .breadcrumb span { color: var(--muted); }
        .breadcrumb strong { color: var(--text); font-weight: 600; }
        .topbar-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

        /* ── PAGE CONTENT ── */
        .page-content { padding: 24px 28px; flex: 1; }

        /* ── BUTTONS ── */
        .btn {
            padding: 7px 16px; border-radius: var(--r-sm);
            font-size: 13px; font-weight: 600; border: none; cursor: pointer;
            font-family: var(--font); transition: all .2s;
            display: inline-flex; align-items: center; gap: 6px; text-decoration: none;
        }
        .btn-primary  { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent2); }
        .btn-ghost    { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
        .btn-ghost:hover { background: var(--border); }
        .btn-success  { background: rgba(16,185,129,.15); color: var(--accent); border: 1px solid rgba(16,185,129,.3); }
        .btn-success:hover { background: rgba(16,185,129,.25); }
        .btn-danger   { background: rgba(239,68,68,.15); color: var(--red); border: 1px solid rgba(239,68,68,.3); }
        .btn-danger:hover { background: rgba(239,68,68,.25); }
        .btn-warning  { background: rgba(245,158,11,.15); color: var(--yellow); border: 1px solid rgba(245,158,11,.3); }
        .btn-warning:hover { background: rgba(245,158,11,.25); }
        .btn-sm { padding: 5px 12px; font-size: 12px; }

        /* ── FLASH MESSAGES ── */
        .flash {
            padding: 12px 16px; border-radius: var(--r-sm); font-size: 13px;
            display: flex; align-items: center; gap: 10px; margin-bottom: 18px;
            animation: slideIn .3s ease;
        }
        .flash-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.3); color: var(--accent); }
        .flash-error   { background: rgba(239,68,68,.1);  border: 1px solid rgba(239,68,68,.3);  color: var(--red); }
        @keyframes slideIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }

        /* ── CARDS ── */
        .card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--r); overflow: hidden; margin-bottom: 20px;
        }
        .card-header {
            padding: 15px 20px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
        }
        .card-title { font-size: 14px; font-weight: 700; color: var(--text); }
        .card-sub   { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .card-body  { padding: 20px; }

        /* ── STAT CARDS ── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 14px; margin-bottom: 22px; }
        .stat-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--r); padding: 18px; position: relative; overflow: hidden;
            transition: transform .2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .stat-card.s-green::before  { background: var(--accent); }
        .stat-card.s-blue::before   { background: var(--blue); }
        .stat-card.s-yellow::before { background: var(--yellow); }
        .stat-card.s-red::before    { background: var(--red); }
        .stat-card.s-purple::before { background: var(--purple); }
        .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 17px; margin-bottom: 12px; }
        .s-green  .stat-icon { background: rgba(16,185,129,.15); color: var(--accent); }
        .s-blue   .stat-icon { background: rgba(59,130,246,.15);  color: var(--blue); }
        .s-yellow .stat-icon { background: rgba(245,158,11,.15);  color: var(--yellow); }
        .s-red    .stat-icon { background: rgba(239,68,68,.15);   color: var(--red); }
        .s-purple .stat-icon { background: rgba(139,92,246,.15);  color: var(--purple); }
        .stat-val   { font-size: 32px; font-weight: 800; font-family: var(--mono); line-height: 1; }
        .stat-label { font-size: 12px; color: var(--muted); margin-top: 6px; font-weight: 500; }

        /* ── BADGES ── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600;
        }
        .b-green  { background: rgba(16,185,129,.15); color: var(--accent); }
        .b-yellow { background: rgba(245,158,11,.15); color: var(--yellow); }
        .b-red    { background: rgba(239,68,68,.15);  color: var(--red); }
        .b-blue   { background: rgba(59,130,246,.15); color: var(--blue); }
        .b-purple { background: rgba(139,92,246,.15); color: var(--purple); }
        .b-muted  { background: rgba(77,122,104,.15); color: var(--muted); }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th {
            padding: 10px 16px; text-align: left;
            font-size: 11px; font-weight: 600; color: var(--muted);
            text-transform: uppercase; letter-spacing: .8px;
            background: var(--surface2); border-bottom: 1px solid var(--border); white-space: nowrap;
        }
        tbody td { padding: 12px 16px; border-bottom: 1px solid rgba(31,51,41,.6); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: rgba(23,36,32,.6); }
        .t-info { display: flex; align-items: center; gap: 10px; }
        .t-av {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; flex-shrink: 0;
            background: rgba(16,185,129,.15); color: var(--accent);
        }
        .t-av.female { background: rgba(236,72,153,.15); color: var(--pink); }
        .t-name { font-weight: 600; font-size: 13px; }
        .t-sub  { font-size: 11px; color: var(--muted); font-family: var(--mono); }

        /* ── RATE BAR ── */
        .prog-bg { height: 6px; background: var(--surface2); border-radius: 99px; overflow: hidden; }
        .prog    { height: 100%; border-radius: 99px; transition: width .8s ease; }

        /* ── FORM ── */
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-label { font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .7px; }
        .form-input, .form-select, .form-textarea {
            background: var(--surface2); border: 1px solid var(--border);
            color: var(--text); border-radius: var(--r-sm);
            padding: 9px 12px; font-size: 13px; font-family: var(--font); outline: none; width: 100%;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--accent); }
        .form-textarea { resize: vertical; min-height: 80px; }
        .form-hint { font-size: 11px; color: var(--muted); margin-top: 3px; }
        .invalid-feedback { font-size: 11px; color: var(--red); margin-top: 3px; }

        /* ── MODAL ── */
        .modal-bg {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.75); backdrop-filter: blur(4px);
            z-index: 200; align-items: center; justify-content: center; padding: 16px;
        }
        .modal-bg.open { display: flex; }
        .modal {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--r); width: 100%; max-width: 480px;
            max-height: 90vh; overflow-y: auto;
            animation: modalIn .25s ease;
        }
        @keyframes modalIn { from { opacity:0; transform:scale(.95); } to { opacity:1; transform:scale(1); } }
        .modal-header { padding: 18px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .modal-title  { font-size: 15px; font-weight: 700; }
        .modal-close  { background: none; border: none; color: var(--muted); cursor: pointer; font-size: 17px; padding: 4px; }
        .modal-close:hover { color: var(--text); }
        .modal-body   { padding: 22px; }
        .modal-footer { padding: 14px 22px; border-top: 1px solid var(--border); display: flex; gap: 10px; justify-content: flex-end; }

        /* ── PAGINATION ── */
        .pag-wrap { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        .pag-info { font-size: 12px; color: var(--muted); }
        .pag { display: flex; gap: 4px; }
        .pag a, .pag span { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: var(--r-sm); font-size: 13px; text-decoration: none; border: 1px solid var(--border); color: var(--muted); transition: all .15s; }
        .pag a:hover { background: var(--surface2); color: var(--text); }
        .pag .cur { background: var(--accent); border-color: var(--accent); color: #fff; font-weight: 700; }

        /* ── EMPTY ── */
        .empty { text-align: center; padding: 48px 20px; color: var(--muted); }
        .empty i { font-size: 40px; margin-bottom: 12px; display: block; opacity: .4; }
        .empty h3 { font-size: 15px; margin-bottom: 6px; color: var(--text); }
        .empty p  { font-size: 13px; }

        /* ── OVERLAY (mobile) ── */
        .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.65); z-index: 99; }
        .overlay.open { display: block; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .overlay.open { display: block; }
            .main { margin-left: 0; }
            .hamburger { display: block; }
            .page-content { padding: 14px; }
            .topbar { padding: 12px 16px; }
            .stats-grid { grid-template-columns: repeat(2,1fr); gap: 10px; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-badge">
            <div class="logo-icon">🗺️</div>
            <div>
                <div class="logo-text">EduAttend</div>
                <div class="logo-sub">Ward Officer Portal</div>
            </div>
        </div>
    </div>

    {{-- Ward badge --}}
    <div class="sidebar-ward">
        <div class="ward-label"><i class="fas fa-map-marker-alt" style="margin-right:4px"></i>Kata Yako</div>
        <div class="ward-name">{{ auth()->user()->ward->name ?? '—' }}</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-label">Mwelekeo</div>
            <a href="{{ route('ward.dashboard') }}"
               class="nav-item {{ request()->routeIs('ward.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('ward.attendance.index') }}"
               class="nav-item {{ request()->routeIs('ward.attendance.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Mahudhurio
            </a>
            <a href="{{ route('ward.schools.index') }}"
               class="nav-item {{ request()->routeIs('ward.schools.*') ? 'active' : '' }}">
                <i class="fas fa-school"></i> Shule
            </a>
            <a href="{{ route('ward.teachers.index') }}"
               class="nav-item {{ request()->routeIs('ward.teachers.*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i> Walimu
                @php $pendingCount = \App\Models\User::where('role','teacher')->where('status','pending')->whereHas('school', fn($q) => $q->where('ward_id', auth()->user()->ward_id))->count(); @endphp
                @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
                @endif
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Usimamizi</div>
            <a href="{{ route('ward.approvals.index') }}"
               class="nav-item {{ request()->routeIs('ward.approvals.*') ? 'active' : '' }}">
                <i class="fas fa-user-check"></i> Idhini
                @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('ward.transfers.index') }}"
               class="nav-item {{ request()->routeIs('ward.transfers.*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i> Uhamisho
            </a>
            <a href="{{ route('ward.reports.index') }}"
               class="nav-item {{ request()->routeIs('ward.reports.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i> Ripoti
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Akaunti</div>
            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i> Wasifu Wangu
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                <div class="user-role">Ward Officer</div>
            </div>
        </div>
    </div>
</aside>

{{-- MAIN --}}
<div class="main">
    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="breadcrumb">
                <a href="{{ route('ward.dashboard') }}"><i class="fas fa-home"></i></a>
                @isset($breadcrumbs)
                    @foreach($breadcrumbs as $label => $url)
                    <span>/</span>
                    @if($loop->last)
                        <strong>{{ $label }}</strong>
                    @else
                        <a href="{{ $url }}">{{ $label }}</a>
                    @endif
                    @endforeach
                @else
                    <span>/</span>
                    <strong>{{ $title ?? 'Dashboard' }}</strong>
                @endisset
            </div>
        </div>
        <div class="topbar-right">
            {{ $actions ?? '' }}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </header>

    {{-- PAGE CONTENT --}}
    <div class="page-content">
        @if(session('success'))
        <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="flash flash-error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
        @endif

        {{ $slot }}
    </div>
</div>

{{ $scripts ?? '' }}
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('open');
}
// Modal helpers (reusable across pages)
function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
function closeBg(e, id) { if(e.target===document.getElementById(id)) closeModal(id); }
// Flash dismiss
setTimeout(() => {
    document.querySelectorAll('.flash').forEach(el => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 4000);
</script>
</body>
</html>