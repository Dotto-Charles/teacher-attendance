{{-- resources/views/district/teachers.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walimu · District Officer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --bg:        #0f1117;
            --surface:   #181c27;
            --surface2:  #1e2335;
            --border:    #2a2f45;
            --accent:    #3b82f6;
            --accent2:   #6366f1;
            --green:     #10b981;
            --yellow:    #f59e0b;
            --red:       #ef4444;
            --pink:      #ec4899;
            --text:      #e2e8f0;
            --muted:     #64748b;
            --font:      'DM Sans', sans-serif;
            --mono:      'DM Mono', monospace;
            --r:         14px;
            --r-sm:      8px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── SIDEBAR (same as dashboard) ── */
        .sidebar {
            position: fixed; left: 0; top: 0; bottom: 0; width: 240px;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 100;
            transition: transform .3s ease;
        }
        .sidebar-logo { padding: 24px 20px 20px; border-bottom: 1px solid var(--border); }
        .logo-badge { display: flex; align-items: center; gap: 10px; }
        .logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .logo-text { font-size: 14px; font-weight: 700; line-height: 1.2; }
        .logo-sub  { font-size: 11px; color: var(--muted); }

        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }
        .nav-section { margin-bottom: 24px; }
        .nav-label {
            font-size: 10px; font-weight: 600; color: var(--muted);
            letter-spacing: 1.2px; text-transform: uppercase;
            padding: 0 8px; margin-bottom: 8px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: var(--r-sm);
            font-size: 13.5px; font-weight: 500; color: var(--muted);
            text-decoration: none; transition: all .2s; margin-bottom: 2px;
        }
        .nav-item:hover { background: var(--surface2); color: var(--text); }
        .nav-item.active { background: rgba(59,130,246,.15); color: var(--accent); }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; }
        .nav-badge {
            margin-left: auto; background: var(--red); color: #fff;
            font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 20px;
        }
        .sidebar-footer { padding: 16px 12px; border-top: 1px solid var(--border); }
        .user-card {
            display: flex; align-items: center; gap: 10px; padding: 10px;
            background: var(--surface2); border-radius: var(--r-sm);
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 600; }
        .user-role { font-size: 11px; color: var(--muted); }

        /* ── MAIN ── */
        .main { margin-left: 240px; min-height: 100vh; }

        /* ── TOPBAR ── */
        .topbar {
            position: sticky; top: 0; z-index: 50;
            background: rgba(15,17,23,.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; }
        .hamburger {
            display: none; background: none; border: none;
            color: var(--text); font-size: 20px; cursor: pointer; padding: 4px;
        }
        .breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 13px; }
        .breadcrumb a { color: var(--muted); text-decoration: none; }
        .breadcrumb a:hover { color: var(--text); }
        .breadcrumb span { color: var(--muted); }
        .breadcrumb strong { color: var(--text); font-weight: 600; }

        .btn {
            padding: 7px 16px; border-radius: var(--r-sm);
            font-size: 13px; font-weight: 600;
            border: none; cursor: pointer; font-family: var(--font);
            transition: all .2s; display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: #2563eb; }
        .btn-ghost { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
        .btn-ghost:hover { background: var(--border); }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-success { background: rgba(16,185,129,.15); color: var(--green); border: 1px solid rgba(16,185,129,.3); }
        .btn-success:hover { background: rgba(16,185,129,.25); }
        .btn-danger  { background: rgba(239,68,68,.15); color: var(--red); border: 1px solid rgba(239,68,68,.3); }
        .btn-danger:hover  { background: rgba(239,68,68,.25); }

        /* ── CONTENT ── */
        .content { padding: 24px 28px; }

        /* ── FLASH MESSAGES ── */
        .flash {
            padding: 12px 16px; border-radius: var(--r-sm);
            font-size: 13px; display: flex; align-items: center; gap: 10px;
            margin-bottom: 20px; animation: slideIn .3s ease;
        }
        .flash-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.3); color: var(--green); }
        .flash-error   { background: rgba(239,68,68,.1);  border: 1px solid rgba(239,68,68,.3);  color: var(--red); }
        @keyframes slideIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }

        /* ── STATS ROW ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }
        .mini-stat {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: 16px 18px;
            display: flex; align-items: center; gap: 14px;
            transition: transform .2s;
        }
        .mini-stat:hover { transform: translateY(-2px); }
        .mini-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }
        .mini-stat.total   .mini-icon { background: rgba(59,130,246,.15);  color: var(--accent); }
        .mini-stat.active  .mini-icon { background: rgba(16,185,129,.15);  color: var(--green); }
        .mini-stat.pending .mini-icon { background: rgba(245,158,11,.15);  color: var(--yellow); }
        .mini-stat.male    .mini-icon { background: rgba(99,102,241,.15);  color: var(--accent2); }
        .mini-stat.female  .mini-icon { background: rgba(236,72,153,.15);  color: var(--pink); }
        .mini-stat.today   .mini-icon { background: rgba(16,185,129,.15);  color: var(--green); }

        .mini-val   { font-size: 24px; font-weight: 700; font-family: var(--mono); line-height: 1; }
        .mini-label { font-size: 11px; color: var(--muted); margin-top: 4px; font-weight: 500; }

        /* ── FILTER BAR ── */
        .filter-bar {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px;
        }
        .filter-group { display: flex; flex-direction: column; gap: 4px; flex: 1; min-width: 130px; }
        .filter-label { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .7px; }
        .form-select, .form-input {
            background: var(--surface2); border: 1px solid var(--border);
            color: var(--text); border-radius: var(--r-sm);
            padding: 8px 12px; font-size: 13px; font-family: var(--font);
            outline: none; width: 100%;
        }
        .form-select:focus, .form-input:focus { border-color: var(--accent); }
        .search-wrap { position: relative; flex: 2; min-width: 200px; }
        .search-wrap .form-input { padding-left: 36px; }
        .search-icon {
            position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
            color: var(--muted); font-size: 13px; pointer-events: none;
        }
        .filter-actions { display: flex; gap: 8px; align-items: flex-end; }

        /* ── RESULTS INFO ── */
        .results-info {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 12px; font-size: 13px; color: var(--muted);
        }
        .results-info strong { color: var(--text); }

        /* ── TABLE CARD ── */
        .table-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            overflow: hidden;
        }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th {
            padding: 11px 16px;
            text-align: left;
            font-size: 11px; font-weight: 600; color: var(--muted);
            text-transform: uppercase; letter-spacing: .8px;
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        thead th.sort { cursor: pointer; user-select: none; }
        thead th.sort:hover { color: var(--text); }
        tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid rgba(42,47,69,.5);
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background .15s; }
        tbody tr:hover td { background: rgba(30,35,53,.7); cursor: pointer; }

        /* ── AVATAR ── */
        .t-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; flex-shrink: 0;
        }
        .male-av   { background: rgba(99,102,241,.2);  color: var(--accent2); }
        .female-av { background: rgba(236,72,153,.2);  color: var(--pink); }
        .t-info { display: flex; align-items: center; gap: 10px; }
        .t-name  { font-weight: 600; font-size: 13px; }
        .t-check { font-size: 11px; color: var(--muted); font-family: var(--mono); margin-top: 2px; }

        /* ── BADGES ── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 9px; border-radius: 20px;
            font-size: 11px; font-weight: 600; white-space: nowrap;
        }
        .badge-approved { background: rgba(16,185,129,.15); color: var(--green); }
        .badge-pending  { background: rgba(245,158,11,.15); color: var(--yellow); }
        .badge-rejected { background: rgba(239,68,68,.15);  color: var(--red); }
        .badge-male     { background: rgba(99,102,241,.15); color: var(--accent2); }
        .badge-female   { background: rgba(236,72,153,.15); color: var(--pink); }

        /* ── ATTENDANCE MINI ── */
        .att-bar-bg {
            height: 4px; background: var(--surface2);
            border-radius: 99px; overflow: hidden; width: 70px; margin-top: 3px;
        }
        .att-bar { height: 100%; border-radius: 99px; }
        .att-pct { font-size: 12px; font-family: var(--mono); font-weight: 600; }

        /* ── ACTION BUTTONS ── */
        .actions { display: flex; gap: 6px; align-items: center; }

        /* ── PAGINATION ── */
        .pagination-wrap {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px;
        }
        .pagination-wrap .info { font-size: 12px; color: var(--muted); }
        .pagination {
            display: flex; gap: 4px;
        }
        .pagination a, .pagination span {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: var(--r-sm);
            font-size: 13px; text-decoration: none;
            border: 1px solid var(--border); color: var(--muted);
            transition: all .15s;
        }
        .pagination a:hover { background: var(--surface2); color: var(--text); }
        .pagination .active-page {
            background: var(--accent); border-color: var(--accent); color: #fff; font-weight: 700;
        }
        .pagination .dots { border: none; background: none; }

        /* ── EMPTY ── */
        .empty { text-align: center; padding: 56px 20px; color: var(--muted); }
        .empty i { font-size: 44px; margin-bottom: 14px; display: block; opacity: .5; }
        .empty h3 { font-size: 16px; margin-bottom: 6px; color: var(--text); }
        .empty p  { font-size: 13px; }

        /* ── MODAL ── */
        .modal-bg {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.7); backdrop-filter: blur(4px);
            z-index: 200; align-items: center; justify-content: center;
            padding: 16px;
        }
        .modal-bg.open { display: flex; }
        .modal {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            width: 100%; max-width: 520px;
            max-height: 90vh; overflow-y: auto;
            animation: modalIn .25s ease;
        }
        @keyframes modalIn { from { opacity:0; transform:scale(.95); } to { opacity:1; transform:scale(1); } }
        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-title { font-size: 16px; font-weight: 700; }
        .modal-close {
            background: none; border: none; color: var(--muted); cursor: pointer;
            font-size: 18px; padding: 4px; transition: color .15s;
        }
        .modal-close:hover { color: var(--text); }
        .modal-body { padding: 24px; }

        .modal-avatar {
            width: 64px; height: 64px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: 700; margin: 0 auto 20px;
        }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .detail-item {}
        .detail-key   { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: .7px; margin-bottom: 4px; }
        .detail-val   { font-size: 14px; font-weight: 600; }
        .detail-val.mono { font-family: var(--mono); }
        .detail-divider { height: 1px; background: var(--border); margin: 16px 0; }
        .att-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; }
        .att-stat-box {
            background: var(--surface2); border-radius: var(--r-sm);
            padding: 12px; text-align: center;
        }
        .att-stat-num  { font-size: 22px; font-weight: 700; font-family: var(--mono); }
        .att-stat-lbl  { font-size: 11px; color: var(--muted); margin-top: 4px; }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            display: flex; gap: 10px; justify-content: flex-end;
        }

        /* ── OVERLAY (sidebar mobile) ── */
        .overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.6); z-index: 99;
        }
        .overlay.open { display: block; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .stats-row { grid-template-columns: repeat(3,1fr); }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .overlay.open { display: block; }
            .main { margin-left: 0; }
            .hamburger { display: block; }
            .content { padding: 16px; }
            .topbar { padding: 12px 16px; }
            .stats-row { grid-template-columns: repeat(2,1fr); gap: 10px; }
            .filter-bar { gap: 10px; }
            .detail-grid { grid-template-columns: 1fr; }
            .att-stats { grid-template-columns: repeat(3,1fr); }
            /* hide less important columns */
            .col-phone, .col-school { display: none; }
        }
        @media (max-width: 480px) {
            .stats-row { grid-template-columns: 1fr 1fr; }
            .col-ward { display: none; }
        }
    </style>
</head>
<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-badge">
            <div class="logo-icon">🏫</div>
            <div>
                <div class="logo-text">Kabodo</div>
                <div class="logo-sub">District Officer</div>
            </div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-label">Mwelekeo</div>
            <a href="{{ route('district.dashboard') }}" class="nav-item">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('district.attendance.index') }}" class="nav-item">
                <i class="fas fa-calendar-check"></i> Mahudhurio
            </a>
            <a href="{{ route('district.schools.index') }}" class="nav-item">
                <i class="fas fa-school"></i> Shule
            </a>
            <a href="{{ route('district.teachers.index') }}" class="nav-item active">
                <i class="fas fa-chalkboard-teacher"></i> Walimu
                @if(isset($pendingCount) && $pendingCount > 0)
                    <span class="nav-badge">{{ $pendingCount }}</span>
                @endif
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Usimamizi</div>
            <a href="#" class="nav-item"><i class="fas fa-map-marker-alt"></i> Kata</a>
            <a href="{{ route('district.assignments.index') }}" class="nav-item"><i class="fas fa-exchange-alt"></i> Uhamisho</a>
            <a href="{{ route('district.reports.index') }}"     class="nav-item "><i class="fas fa-file-alt"></i> Ripoti</a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Mfumo</div>
            <a href="#" class="nav-item"><i class="fas fa-cog"></i> Mipangilio</a>
        </div>
    </nav>
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr($officer->first_name,0,1)) }}</div>
            <div>
                <div class="user-name">{{ $officer->first_name }} {{ $officer->last_name }}</div>
                <div class="user-role">District Officer</div>
            </div>
        </div>
    </div>
</aside>

<div class="main">

    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div class="breadcrumb">
                <a href="{{ route('district.dashboard') }}"><i class="fas fa-home"></i></a>
                <span>/</span>
                <strong>Walimu</strong>
            </div>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </header>

    <div class="content">

        {{-- FLASH --}}
        @if(session('success'))
            <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
        @endif

        {{-- PAGE HEADING --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 style="font-size:22px;font-weight:700;">Walimu Wote</h1>
                <p style="font-size:13px;color:var(--muted);margin-top:3px;">
                    Halmashauri · {{ $officer->council->name ?? 'Halmashauri' }}
                </p>
            </div>
        </div>

        {{-- STATS ROW --}}
        <div class="stats-row">
            <div class="mini-stat total">
                <div class="mini-icon"><i class="fas fa-users"></i></div>
                <div>
                    <div class="mini-val">{{ $totalTeachers }}</div>
                    <div class="mini-label">Walimu Wote</div>
                </div>
            </div>
            <div class="mini-stat active">
                <div class="mini-icon"><i class="fas fa-user-check"></i></div>
                <div>
                    <div class="mini-val">{{ $approvedCount }}</div>
                    <div class="mini-label">Walioidhinishwa</div>
                </div>
            </div>
            <div class="mini-stat pending">
                <div class="mini-icon"><i class="fas fa-user-clock"></i></div>
                <div>
                    <div class="mini-val">{{ $pendingCount }}</div>
                    <div class="mini-label">Wanaosubiri</div>
                </div>
            </div>
            <div class="mini-stat male">
                <div class="mini-icon"><i class="fas fa-mars"></i></div>
                <div>
                    <div class="mini-val">{{ $maleCount }}</div>
                    <div class="mini-label">Wanaume</div>
                </div>
            </div>
            <div class="mini-stat female">
                <div class="mini-icon"><i class="fas fa-venus"></i></div>
                <div>
                    <div class="mini-val">{{ $femaleCount }}</div>
                    <div class="mini-label">Wanawake</div>
                </div>
            </div>
            <div class="mini-stat today">
                <div class="mini-icon"><i class="fas fa-calendar-day"></i></div>
                <div>
                    <div class="mini-val">{{ $attendedToday }}</div>
                    <div class="mini-label">Waliofika Leo</div>
                </div>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <form method="GET" id="filterForm">
            <div class="filter-bar">
                {{-- Search --}}
                <div class="filter-group" style="flex:3;min-width:220px;">
                    <label class="filter-label">Tafuta</label>
                    <div class="search-wrap">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="form-input"
                               placeholder="Jina, namba ya cheki, simu..."
                               value="{{ $search }}">
                    </div>
                </div>

                {{-- Ward --}}
                <div class="filter-group">
                    <label class="filter-label">Kata</label>
                    <select name="ward_id" class="form-select" onchange="updateSchools(this.value)">
                        <option value="">Zote</option>
                        @foreach($wards as $w)
                            <option value="{{ $w->id }}" {{ $wardId == $w->id ? 'selected' : '' }}>
                                {{ $w->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- School --}}
                <div class="filter-group">
                    <label class="filter-label">Shule</label>
                    <select name="school_id" class="form-select" id="schoolSelect">
                        <option value="">Zote</option>
                        @foreach($schools as $sc)
                            <option value="{{ $sc->id }}" {{ $schoolId == $sc->id ? 'selected' : '' }}>
                                {{ $sc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sex --}}
                <div class="filter-group" style="min-width:110px;">
                    <label class="filter-label">Jinsi</label>
                    <select name="sex" class="form-select">
                        <option value="">Zote</option>
                        <option value="male"   {{ $sex === 'male'   ? 'selected' : '' }}>Mwanaume</option>
                        <option value="female" {{ $sex === 'female' ? 'selected' : '' }}>Mwanamke</option>
                    </select>
                </div>

                {{-- Status --}}
                <div class="filter-group" style="min-width:120px;">
                    <label class="filter-label">Hali</label>
                    <select name="status" class="form-select">
                        <option value="">Zote</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Imeidhinishwa</option>
                        <option value="pending"  {{ $status === 'pending'  ? 'selected' : '' }}>Inasubiri</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Imekataliwa</option>
                    </select>
                </div>

                <input type="hidden" name="per_page" value="{{ $perPage }}">

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Chuja
                    </button>
                    <a href="{{ route('district.teachers.index') }}" class="btn btn-ghost">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>

        {{-- RESULTS INFO --}}
        <div class="results-info">
            <span>
                Wameonyesha <strong>{{ $teachers->firstItem() ?? 0 }}–{{ $teachers->lastItem() ?? 0 }}</strong>
                kati ya <strong>{{ $teachers->total() }}</strong>
            </span>
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="font-size:12px;">Idadi kwa ukurasa:</span>
                <select class="form-select" style="width:auto;padding:4px 8px;font-size:12px;"
                        onchange="changePerPage(this.value)">
                    @foreach([10,20,50,100] as $pp)
                        <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-card">
            @if($teachers->isEmpty())
                <div class="empty">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>Hakuna walimu waliopatikana</h3>
                    <p>Jaribu kubadilisha vichujio vya utafutaji</p>
                </div>
            @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mwalimu</th>
                            <th class="col-phone">Simu</th>
                            <th class="col-school">Shule</th>
                            <th class="col-ward">Kata</th>
                            <th>Jinsi</th>
                            <th>Hali</th>
                            <th>Mahudhurio (30d)</th>
                            <th>Vitendo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $i => $t)
                        @php
                            $days    = $attendanceMap[$t->id] ?? 0;
                            $attRate = $workingDays > 0 ? round(($days / $workingDays) * 100) : 0;
                            $attColor = $attRate >= 80 ? 'var(--green)' : ($attRate >= 60 ? 'var(--yellow)' : 'var(--red)');
                            $initials = strtoupper(substr($t->first_name,0,1) . substr($t->last_name,0,1));
                        @endphp
                        <tr onclick="openModal({{ $t->id }})" style="cursor:pointer;">
                            <td style="color:var(--muted);font-family:var(--mono);font-size:12px">
                                {{ $teachers->firstItem() + $i }}
                            </td>
                            <td>
                                <div class="t-info">
                                    <div class="t-avatar {{ $t->sex === 'female' ? 'female-av' : 'male-av' }}">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="t-name">{{ $t->first_name }} {{ $t->middle_name ? $t->middle_name.' ' : '' }}{{ $t->last_name }}</div>
                                        <div class="t-check">{{ $t->check_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="col-phone" style="font-family:var(--mono);font-size:12px;color:var(--muted)">
                                {{ $t->phone }}
                            </td>
                            <td class="col-school" style="font-size:12px;">
                                {{ $t->school->name ?? '—' }}
                            </td>
                            <td class="col-ward" style="font-size:12px;color:var(--muted);">
                                {{ $t->school->ward->name ?? '—' }}
                            </td>
                            <td>
                                <span class="badge {{ $t->sex === 'female' ? 'badge-female' : 'badge-male' }}">
                                    <i class="fas fa-{{ $t->sex === 'female' ? 'venus' : 'mars' }}"></i>
                                    {{ $t->sex === 'female' ? 'Mke' : 'Mme' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $t->status }}">
                                    @if($t->status === 'approved') <i class="fas fa-check-circle"></i> Imeidhinishwa
                                    @elseif($t->status === 'pending') <i class="fas fa-clock"></i> Inasubiri
                                    @else <i class="fas fa-times-circle"></i> Imekataliwa
                                    @endif
                                </span>
                            </td>
                            <td onclick="event.stopPropagation()">
                                <div class="att-pct" style="color:{{ $attColor }}">{{ $attRate }}%</div>
                                <div class="att-bar-bg">
                                    <div class="att-bar" style="width:{{ $attRate }}%;background:{{ $attColor }}"></div>
                                </div>
                                <div style="font-size:10px;color:var(--muted);margin-top:2px;">{{ $days }}/{{ $workingDays }} siku</div>
                            </td>
                            <td onclick="event.stopPropagation()">
                                <div class="actions">
                                    <button class="btn btn-ghost btn-sm" onclick="openModal({{ $t->id }})"
                                            title="Angalia maelezo">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($t->status === 'pending')
                                    <form method="POST" action="{{ route('district.teachers.approve', $t) }}" style="display:inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" title="Idhinisha">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('district.teachers.reject', $t) }}" style="display:inline"
                                          onsubmit="return confirm('Una uhakika wa kukataa mwalimu huyu?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Kataa">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Hidden modal data --}}
                        <script>
                            window.__teachers = window.__teachers || {};
                            window.__teachers[{{ $t->id }}] = {
                                id:         {{ $t->id }},
                                name:       "{{ $t->first_name }} {{ $t->middle_name ? $t->middle_name.' ' : '' }}{{ $t->last_name }}",
                                check:      "{{ $t->check_number }}",
                                email:      "{{ $t->email }}",
                                phone:      "{{ $t->phone }}",
                                sex:        "{{ $t->sex }}",
                                status:     "{{ $t->status }}",
                                school:     "{{ $t->school->name ?? '—' }}",
                                ward:       "{{ $t->school->ward->name ?? '—' }}",
                                initials:   "{{ $initials }}",
                                days:       {{ $days }},
                                workDays:   {{ $workingDays }},
                                rate:       {{ $attRate }},
                                joined:     "{{ $t->created_at ? $t->created_at->format('d M Y') : '—' }}",
                            };
                        </script>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="pagination-wrap">
                <span class="info">
                    Ukurasa {{ $teachers->currentPage() }} kati ya {{ $teachers->lastPage() }}
                </span>
                <div class="pagination">
                    @if($teachers->onFirstPage())
                        <span style="opacity:.4;"><i class="fas fa-chevron-left" style="font-size:11px"></i></span>
                    @else
                        <a href="{{ $teachers->previousPageUrl() }}"><i class="fas fa-chevron-left" style="font-size:11px"></i></a>
                    @endif

                    @foreach($teachers->getUrlRange(max(1,$teachers->currentPage()-2), min($teachers->lastPage(),$teachers->currentPage()+2)) as $page => $url)
                        @if($page == $teachers->currentPage())
                            <span class="active-page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($teachers->hasMorePages())
                        <a href="{{ $teachers->nextPageUrl() }}"><i class="fas fa-chevron-right" style="font-size:11px"></i></a>
                    @else
                        <span style="opacity:.4;"><i class="fas fa-chevron-right" style="font-size:11px"></i></span>
                    @endif
                </div>
            </div>
            @endif
        </div>

    </div>{{-- /content --}}
</div>{{-- /main --}}

{{-- MODAL --}}
<div class="modal-bg" id="modalBg" onclick="closeModal(event)">
    <div class="modal" id="teacherModal">
        <div class="modal-header">
            <div class="modal-title">Maelezo ya Mwalimu</div>
            <button class="modal-close" onclick="closeModalDirect()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="modal-avatar" id="m-avatar"></div>
            <div style="text-align:center;margin-bottom:20px;">
                <div style="font-size:18px;font-weight:700;" id="m-name"></div>
                <div style="font-size:12px;color:var(--muted);font-family:var(--mono);margin-top:4px;" id="m-check"></div>
                <div style="margin-top:10px;display:flex;gap:8px;justify-content:center;" id="m-badges"></div>
            </div>
            <div class="detail-divider"></div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-key"><i class="fas fa-envelope" style="margin-right:4px"></i>Barua pepe</div>
                    <div class="detail-val mono" id="m-email"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-key"><i class="fas fa-phone" style="margin-right:4px"></i>Simu</div>
                    <div class="detail-val mono" id="m-phone"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-key"><i class="fas fa-school" style="margin-right:4px"></i>Shule</div>
                    <div class="detail-val" id="m-school"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-key"><i class="fas fa-map-marker-alt" style="margin-right:4px"></i>Kata</div>
                    <div class="detail-val" id="m-ward"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-key"><i class="fas fa-calendar" style="margin-right:4px"></i>Alijiunga</div>
                    <div class="detail-val" id="m-joined"></div>
                </div>
            </div>
            <div class="detail-divider"></div>
            <div style="margin-bottom:12px;font-size:13px;font-weight:600;color:var(--muted);">
                <i class="fas fa-calendar-check" style="margin-right:6px"></i>
                Mahudhurio — Siku 30 Zilizopita
            </div>
            <div class="att-stats">
                <div class="att-stat-box">
                    <div class="att-stat-num" id="m-days" style="color:var(--accent)"></div>
                    <div class="att-stat-lbl">Siku Alikuja</div>
                </div>
                <div class="att-stat-box">
                    <div class="att-stat-num" id="m-absent" style="color:var(--red)"></div>
                    <div class="att-stat-lbl">Siku Hakuja</div>
                </div>
                <div class="att-stat-box">
                    <div class="att-stat-num" id="m-rate"></div>
                    <div class="att-stat-lbl">Kiwango</div>
                </div>
            </div>
        </div>
        <div class="modal-footer" id="m-footer"></div>
    </div>
</div>

<script>
// ── SIDEBAR ──
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('open');
}

// ── MODAL ──
function openModal(id) {
    const t = window.__teachers[id];
    if (!t) return;

    // Avatar
    const av = document.getElementById('m-avatar');
    av.textContent = t.initials;
    av.style.background = t.sex === 'female'
        ? 'linear-gradient(135deg,#ec4899,#be185d)'
        : 'linear-gradient(135deg,#6366f1,#3b82f6)';
    av.style.color = '#fff';

    document.getElementById('m-name').textContent  = t.name;
    document.getElementById('m-check').textContent = t.check;

    // Badges
    const badgesEl = document.getElementById('m-badges');
    const statusMap = { approved:'badge-approved', pending:'badge-pending', rejected:'badge-rejected' };
    const statusLbl = { approved:'Imeidhinishwa', pending:'Inasubiri', rejected:'Imekataliwa' };
    badgesEl.innerHTML = `
        <span class="badge ${t.sex==='female'?'badge-female':'badge-male'}">
            <i class="fas fa-${t.sex==='female'?'venus':'mars'}"></i>
            ${t.sex==='female'?'Mwanamke':'Mwanaume'}
        </span>
        <span class="badge ${statusMap[t.status]}">${statusLbl[t.status]}</span>
    `;

    document.getElementById('m-email').textContent  = t.email;
    document.getElementById('m-phone').textContent  = t.phone;
    document.getElementById('m-school').textContent = t.school;
    document.getElementById('m-ward').textContent   = t.ward;
    document.getElementById('m-joined').textContent = t.joined;

    const absent = Math.max(0, t.workDays - t.days);
    const rateColor = t.rate >= 80 ? 'var(--green)' : (t.rate >= 60 ? 'var(--yellow)' : 'var(--red)');

    document.getElementById('m-days').textContent   = t.days;
    document.getElementById('m-absent').textContent = absent;
    const rateEl = document.getElementById('m-rate');
    rateEl.textContent = t.rate + '%';
    rateEl.style.color = rateColor;

    // Footer
    document.getElementById('m-footer').innerHTML = `
        <a href="mailto:${t.email}" class="btn btn-ghost" style="font-size:12px;">
            <i class="fas fa-envelope"></i> Tuma Barua
        </a>
        <button class="btn btn-ghost" style="font-size:12px;" onclick="closeModalDirect()">
            Funga
        </button>
    `;

    document.getElementById('modalBg').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal(e) {
    if (e.target === document.getElementById('modalBg')) closeModalDirect();
}
function closeModalDirect() {
    document.getElementById('modalBg').classList.remove('open');
    document.body.style.overflow = '';
}

// ── PER PAGE ──
function changePerPage(val) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', val);
    url.searchParams.set('page', 1);
    window.location = url;
}

// ── SCHOOLS BY WARD (AJAX) ──
function updateSchools(wardId) {
    const select = document.getElementById('schoolSelect');
    select.innerHTML = '<option value="">Zote</option>';
    if (!wardId) return;

    fetch(`/district/schools-by-ward?ward_id=${wardId}`)
        .then(r => r.json())
        .then(schools => {
            schools.forEach(s => {
                select.innerHTML += `<option value="${s.id}">${s.name}</option>`;
            });
        }).catch(() => {});
}

// Auto-dismiss flash
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