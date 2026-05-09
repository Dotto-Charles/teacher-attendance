{{-- resources/views/district/attendance/index.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahudhurio · District Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root{
            --bg:#0f1117;--surface:#181c27;--surface2:#1e2335;--border:#2a2f45;
            --accent:#3b82f6;--accent2:#6366f1;--green:#10b981;--yellow:#f59e0b;
            --red:#ef4444;--pink:#ec4899;--text:#e2e8f0;--muted:#64748b;
            --font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;--r:14px;--r-sm:8px;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:var(--font);background:var(--bg);color:var(--text);min-height:100vh}

        /* ── SIDEBAR ── */
        .sidebar{position:fixed;left:0;top:0;bottom:0;width:240px;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;z-index:100;transition:transform .3s}
        .sidebar-logo{padding:24px 20px 20px;border-bottom:1px solid var(--border)}
        .logo-badge{display:flex;align-items:center;gap:10px}
        .logo-icon{width:38px;height:38px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px}
        .logo-text{font-size:14px;font-weight:700}.logo-sub{font-size:11px;color:var(--muted)}
        .sidebar-nav{flex:1;padding:16px 12px;overflow-y:auto}
        .nav-section{margin-bottom:24px}
        .nav-label{font-size:10px;font-weight:600;color:var(--muted);letter-spacing:1.2px;text-transform:uppercase;padding:0 8px;margin-bottom:8px}
        .nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:var(--r-sm);font-size:13.5px;font-weight:500;color:var(--muted);text-decoration:none;transition:all .2s;margin-bottom:2px}
        .nav-item:hover{background:var(--surface2);color:var(--text)}
        .nav-item.active{background:rgba(59,130,246,.15);color:var(--accent)}
        .nav-item i{width:18px;text-align:center;font-size:14px}
        .nav-badge{margin-left:auto;background:var(--red);color:#fff;font-size:10px;font-weight:700;padding:2px 6px;border-radius:20px}
        .sidebar-footer{padding:16px 12px;border-top:1px solid var(--border)}
        .user-card{display:flex;align-items:center;gap:10px;padding:10px;background:var(--surface2);border-radius:var(--r-sm)}
        .user-avatar{width:34px;height:34px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0}
        .user-name{font-size:13px;font-weight:600}.user-role{font-size:11px;color:var(--muted)}

        /* ── LAYOUT ── */
        .main{margin-left:240px;min-height:100vh}
        .topbar{position:sticky;top:0;z-index:50;background:rgba(15,17,23,.92);backdrop-filter:blur(14px);border-bottom:1px solid var(--border);padding:14px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px}
        .topbar-left{display:flex;align-items:center;gap:14px}
        .hamburger{display:none;background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:4px}
        .breadcrumb{display:flex;align-items:center;gap:8px;font-size:13px}
        .breadcrumb a{color:var(--muted);text-decoration:none}.breadcrumb a:hover{color:var(--text)}
        .breadcrumb span{color:var(--muted)}.breadcrumb strong{color:var(--text);font-weight:600}
        .content{padding:24px 28px}

        /* ── BUTTONS ── */
        .btn{padding:7px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:600;border:none;cursor:pointer;font-family:var(--font);transition:all .2s;display:inline-flex;align-items:center;gap:6px;text-decoration:none}
        .btn-primary{background:var(--accent);color:#fff}.btn-primary:hover{background:#2563eb}
        .btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}.btn-ghost:hover{background:var(--border)}
        .btn-success{background:rgba(16,185,129,.15);color:var(--green);border:1px solid rgba(16,185,129,.3)}.btn-success:hover{background:rgba(16,185,129,.25)}
        .btn-danger{background:rgba(239,68,68,.15);color:var(--red);border:1px solid rgba(239,68,68,.3)}.btn-danger:hover{background:rgba(239,68,68,.25)}
        .btn-sm{padding:5px 12px;font-size:12px}

        /* ── DATE HERO ── */
        .date-hero{background:linear-gradient(135deg,rgba(59,130,246,.12),rgba(99,102,241,.08));border:1px solid rgba(59,130,246,.2);border-radius:var(--r);padding:20px 24px;margin-bottom:22px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px}
        .date-label{font-size:13px;color:var(--muted);margin-bottom:4px}
        .date-value{font-size:26px;font-weight:800;background:linear-gradient(135deg,#e2e8f0,#94a3b8);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .date-day{font-size:13px;color:var(--muted);margin-top:2px}
        .date-actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
        .date-nav-btn{width:32px;height:32px;border-radius:var(--r-sm);background:var(--surface2);border:1px solid var(--border);color:var(--text);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;transition:all .2s;text-decoration:none}
        .date-nav-btn:hover{background:var(--border)}

        /* ── SUMMARY CARDS ── */
        .summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:14px;margin-bottom:22px}
        .sum-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:20px;position:relative;overflow:hidden;transition:transform .2s}
        .sum-card:hover{transform:translateY(-2px)}
        .sum-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
        .sum-card.sc-blue::before{background:var(--accent)}.sum-card.sc-green::before{background:var(--green)}
        .sum-card.sc-red::before{background:var(--red)}.sum-card.sc-yellow::before{background:var(--yellow)}
        .sum-card.sc-purple::before{background:var(--accent2)}
        .sc-icon-wrap{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:14px}
        .sc-blue .sc-icon-wrap{background:rgba(59,130,246,.15);color:var(--accent)}
        .sc-green .sc-icon-wrap{background:rgba(16,185,129,.15);color:var(--green)}
        .sc-red .sc-icon-wrap{background:rgba(239,68,68,.15);color:var(--red)}
        .sc-yellow .sc-icon-wrap{background:rgba(245,158,11,.15);color:var(--yellow)}
        .sc-purple .sc-icon-wrap{background:rgba(99,102,241,.15);color:var(--accent2)}
        .sc-val{font-size:36px;font-weight:800;font-family:var(--mono);line-height:1}
        .sc-label{font-size:12px;color:var(--muted);margin-top:6px;font-weight:500}
        .sc-sub{font-size:11px;color:var(--muted);margin-top:4px}

        /* ── RATE RING (big) ── */
        .rate-ring-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:20px;display:flex;align-items:center;gap:20px;flex-wrap:wrap}
        .ring-outer{position:relative;flex-shrink:0}
        .ring-outer svg{transform:rotate(-90deg)}
        .ring-center{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center}
        .ring-pct{font-size:28px;font-weight:800;font-family:var(--mono)}
        .ring-lbl{font-size:10px;color:var(--muted);letter-spacing:.5px}
        .ring-info{flex:1}
        .ring-info-title{font-size:16px;font-weight:700;margin-bottom:12px}
        .ring-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;font-size:13px}
        .ring-row-bar{height:6px;background:var(--surface2);border-radius:99px;overflow:hidden;margin-top:3px}
        .ring-row-fill{height:100%;border-radius:99px}

        /* ── 2-COL GRID ── */
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:22px}
        .grid-65{display:grid;grid-template-columns:1.6fr 1fr;gap:20px;margin-bottom:22px}

        /* ── CARD ── */
        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);overflow:hidden}
        .card-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px}
        .card-title{font-size:14px;font-weight:700}
        .card-sub{font-size:12px;color:var(--muted);margin-top:2px}
        .card-body{padding:20px}

        /* ── SCHOOL CARDS STRIP ── */
        .school-strip{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;padding:16px}
        .school-att-card{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:14px;transition:border-color .2s}
        .school-att-card:hover{border-color:rgba(59,130,246,.4)}
        .sac-name{font-size:13px;font-weight:700;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .sac-ward{font-size:11px;color:var(--muted);margin-bottom:10px}
        .sac-nums{display:flex;justify-content:space-between;font-size:12px;margin-bottom:6px}
        .sac-bar-bg{height:6px;background:var(--bg);border-radius:99px;overflow:hidden}
        .sac-bar{height:100%;border-radius:99px;transition:width .8s ease}
        .sac-rate{font-size:13px;font-weight:700;font-family:var(--mono);margin-top:6px;text-align:right}

        /* ── CHART ── */
        .chart-wrap{height:200px;position:relative}

        /* ── FILTER BAR ── */
        .filter-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px 20px;margin-bottom:20px;display:flex;flex-wrap:wrap;align-items:flex-end;gap:12px}
        .filter-group{display:flex;flex-direction:column;gap:5px;flex:1;min-width:130px}
        .filter-label{font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.7px}
        .form-select,.form-input{background:var(--surface2);border:1px solid var(--border);color:var(--text);border-radius:var(--r-sm);padding:8px 12px;font-size:13px;font-family:var(--font);outline:none;width:100%}
        .form-select:focus,.form-input:focus{border-color:var(--accent)}
        .search-wrap{position:relative;flex:2;min-width:200px}
        .search-icon{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px;pointer-events:none}
        .search-wrap .form-input{padding-left:36px}

        /* ── TABS ── */
        .tabs{display:flex;gap:2px;background:var(--surface2);border-radius:var(--r-sm);padding:3px;margin-bottom:0}
        .tab-btn{padding:7px 18px;border-radius:6px;font-size:13px;font-weight:600;border:none;cursor:pointer;font-family:var(--font);transition:all .2s;color:var(--muted);background:transparent}
        .tab-btn.active{background:var(--surface);color:var(--text);box-shadow:0 1px 6px rgba(0,0,0,.3)}
        .tab-pane{display:none}.tab-pane.active{display:block}

        /* ── TABLE ── */
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:13px}
        thead th{padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;background:var(--surface2);border-bottom:1px solid var(--border);white-space:nowrap}
        tbody td{padding:12px 16px;border-bottom:1px solid rgba(42,47,69,.5);vertical-align:middle}
        tbody tr:last-child td{border-bottom:none}
        tbody tr:hover td{background:rgba(30,35,53,.6)}
        .t-avatar{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0}
        .male-av{background:rgba(99,102,241,.2);color:var(--accent2)}
        .female-av{background:rgba(236,72,153,.2);color:var(--pink)}
        .t-info{display:flex;align-items:center;gap:10px}
        .t-name{font-weight:600}.t-check{font-size:11px;color:var(--muted);font-family:var(--mono)}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600}
        .badge-present{background:rgba(16,185,129,.15);color:var(--green)}
        .badge-absent{background:rgba(239,68,68,.15);color:var(--red)}
        .time-chip{background:var(--surface2);border:1px solid var(--border);border-radius:6px;padding:2px 8px;font-size:12px;font-family:var(--mono);color:var(--text)}

        /* ── ABSENT LIST ── */
        .absent-item{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid rgba(42,47,69,.4)}
        .absent-item:last-child{border-bottom:none}
        .absent-info{flex:1;min-width:0}
        .absent-name{font-size:13px;font-weight:600}
        .absent-meta{font-size:11px;color:var(--muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

        /* ── PAGINATION ── */
        .pag-wrap{padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
        .pag-info{font-size:12px;color:var(--muted)}
        .pag{display:flex;gap:4px}
        .pag a,.pag span{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--r-sm);font-size:13px;text-decoration:none;border:1px solid var(--border);color:var(--muted);transition:all .15s}
        .pag a:hover{background:var(--surface2);color:var(--text)}
        .pag .cur{background:var(--accent);border-color:var(--accent);color:#fff;font-weight:700}

        /* ── EMPTY ── */
        .empty{text-align:center;padding:48px 20px;color:var(--muted)}
        .empty i{font-size:40px;margin-bottom:12px;display:block;opacity:.4}
        .empty p{font-size:13px}

        /* ── OVERLAY ── */
        .overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99}
        .overlay.open{display:block}

        @media(max-width:1100px){.grid-65{grid-template-columns:1fr}}
        @media(max-width:900px){.grid-2{grid-template-columns:1fr}}
        @media(max-width:768px){
            .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}
            .overlay.open{display:block}.main{margin-left:0}
            .hamburger{display:block}.content{padding:14px}.topbar{padding:12px 16px}
            .summary-grid{grid-template-columns:repeat(2,1fr);gap:10px}
            .date-hero{flex-direction:column;align-items:flex-start}
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
            <div><div class="logo-text">EduAttend</div><div class="logo-sub">District Portal</div></div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-label">Mwelekeo</div>
            <a href="{{ route('district.dashboard') }}" class="nav-item">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('district.attendance.index') }}" class="nav-item active"><i class="fas fa-calendar-check"></i> Mahudhurio</a>
            <a href="{{ route('district.schools.index') }}" class="nav-item"><i class="fas fa-school"></i> Shule</a>
            <a href="{{ route('district.teachers.index') }}" class="nav-item">
                <i class="fas fa-chalkboard-teacher"></i> Walimu
                @if($pendingTeachers > 0)<span class="nav-badge">{{ $pendingTeachers }}</span>@endif
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Usimamizi</div>
            <a href="{{ route('district.wards.index') }}" class="nav-item {{ request()->routeIs('district.wards.index') ? 'active' : '' }}"><i class="fas fa-map-marker-alt"></i> Kata</a>
            <a href="{{ route('district.assignments.index') }}" class="nav-item"><i class="fas fa-exchange-alt"></i> Uhamisho</a>
            <a href="{{ route('district.reports.index') }}" class="nav-item"><i class="fas fa-file-alt"></i> Ripoti</a>
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
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
                <span>/</span><strong>Mahudhurio</strong>
            </div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <a href="{{ route('district.attendance.export.csv', request()->query()) }}" class="btn btn-ghost btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </header>

    <div class="content">

        {{-- DATE HERO --}}
        @php
            $dateObj   = \Carbon\Carbon::parse($selectedDate);
            $prevDate  = $dateObj->copy()->subDay()->toDateString();
            $nextDate  = $dateObj->copy()->addDay()->toDateString();
            $isToday   = $dateObj->isToday();
            $swahiliDays = ['Jumapili','Jumatatu','Jumanne','Jumatano','Alhamisi','Ijumaa','Jumamosi'];
            $swahiliMonths = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ago','Sep','Okt','Nov','Des'];
            $dayName   = $swahiliDays[$dateObj->dayOfWeek];
            $monthName = $swahiliMonths[$dateObj->month - 1];
        @endphp
        <div class="date-hero">
            <div>
                <div class="date-label">Tarehe Iliyochaguliwa</div>
                <div class="date-value">{{ $dayName }}, {{ $dateObj->day }} {{ $monthName }} {{ $dateObj->year }}</div>
                <div class="date-day">
                    @if($isToday)
                        <span style="color:var(--green);font-weight:600">✅ Leo</span>
                    @else
                        <span style="color:var(--muted)">Siku iliyopita</span>
                    @endif
                    &nbsp;·&nbsp; Walimu wote: <strong style="color:var(--text)">{{ $allTeachersCount }}</strong>
                </div>
            </div>
            <div class="date-actions">
                <a href="?date={{ $prevDate }}&ward_id={{ $selectedWard }}&school_id={{ $selectedSchool }}" class="date-nav-btn" title="Siku iliyopita">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <form method="GET" style="display:flex;gap:6px;align-items:center">
                    <input type="hidden" name="ward_id"   value="{{ $selectedWard }}">
                    <input type="hidden" name="school_id" value="{{ $selectedSchool }}">
                    <input type="date" name="date" class="form-input" style="padding:6px 10px;font-size:13px;"
                           value="{{ $selectedDate }}" max="{{ now()->toDateString() }}" onchange="this.form.submit()">
                </form>
                @if(!$isToday)
                <a href="?date={{ $nextDate }}&ward_id={{ $selectedWard }}&school_id={{ $selectedSchool }}" class="date-nav-btn" title="Siku inayofuata">
                    <i class="fas fa-chevron-right"></i>
                </a>
                @endif
                <a href="?date={{ now()->toDateString() }}" class="btn btn-primary btn-sm">Leo</a>
            </div>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="summary-grid">
            <div class="sum-card sc-blue">
                <div class="sc-icon-wrap"><i class="fas fa-users"></i></div>
                <div class="sc-val" style="color:var(--accent)">{{ $allTeachersCount }}</div>
                <div class="sc-label">Walimu Wote</div>
                <div class="sc-sub">Walioidhinishwa</div>
            </div>
            <div class="sum-card sc-green">
                <div class="sc-icon-wrap"><i class="fas fa-user-check"></i></div>
                <div class="sc-val" style="color:var(--green)">{{ $presentCount }}</div>
                <div class="sc-label">Walifika</div>
                <div class="sc-sub">{{ $selectedDate }}</div>
            </div>
            <div class="sum-card sc-red">
                <div class="sc-icon-wrap"><i class="fas fa-user-times"></i></div>
                <div class="sc-val" style="color:var(--red)">{{ $absentCount }}</div>
                <div class="sc-label">Hawakuja</div>
                <div class="sc-sub">Kutokuwepo</div>
            </div>
            <div class="sum-card {{ $overallRate >= 80 ? 'sc-green' : ($overallRate >= 60 ? 'sc-yellow' : 'sc-red') }}">
                <div class="sc-icon-wrap"><i class="fas fa-percentage"></i></div>
                <div class="sc-val" style="color:{{ $overallRate >= 80 ? 'var(--green)' : ($overallRate >= 60 ? 'var(--yellow)' : 'var(--red)') }}">
                    {{ $overallRate }}%
                </div>
                <div class="sc-label">Kiwango</div>
                <div class="sc-sub">{{ $overallRate >= 80 ? '✅ Vizuri' : ($overallRate >= 60 ? '⚠️ Wastani' : '❌ Chini') }}</div>
            </div>
            <div class="sum-card sc-purple">
                <div class="sc-icon-wrap"><i class="fas fa-school"></i></div>
                <div class="sc-val" style="color:var(--accent2)">{{ $schoolCards->count() }}</div>
                <div class="sc-label">Shule</div>
                <div class="sc-sub">Zinazofanya kazi</div>
            </div>
        </div>

        {{-- RATE RING + HOURLY CHART --}}
        <div class="grid-65">
            {{-- Attendance rate ring --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">📊 Uchambuzi wa Mahudhurio</div>
                        <div class="card-sub">Kiwango cha ujumla — {{ $selectedDate }}</div>
                    </div>
                </div>
                <div class="card-body" style="display:flex;align-items:center;gap:28px;flex-wrap:wrap">
                    {{-- Ring --}}
                    <div class="ring-outer">
                        @php
                            $circumference = 2 * M_PI * 52;
                            $offset = $circumference - ($overallRate / 100 * $circumference);
                            $ringColor = $overallRate >= 80 ? '#10b981' : ($overallRate >= 60 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <svg width="130" height="130" viewBox="0 0 130 130">
                            <circle cx="65" cy="65" r="52" fill="none" stroke="var(--surface2)" stroke-width="12"/>
                            <circle cx="65" cy="65" r="52" fill="none"
                                stroke="{{ $ringColor }}" stroke-width="12" stroke-linecap="round"
                                stroke-dasharray="{{ round($circumference, 2) }}"
                                stroke-dashoffset="{{ round($offset, 2) }}"
                                transform="rotate(-90 65 65)"/>
                        </svg>
                        <div class="ring-center">
                            <div class="ring-pct" style="color:{{ $ringColor }}">{{ $overallRate }}%</div>
                            <div class="ring-lbl">KIWANGO</div>
                        </div>
                    </div>
                    {{-- Stats --}}
                    <div class="ring-info" style="flex:1;min-width:180px">
                        <div class="ring-info-title">Mgawanyo wa Mahudhurio</div>
                        <div style="margin-bottom:14px">
                            <div class="ring-row">
                                <span style="color:var(--muted)">Walifika</span>
                                <span style="font-weight:700;color:var(--green);font-family:var(--mono)">{{ $presentCount }}</span>
                            </div>
                            <div class="ring-row-bar"><div class="ring-row-fill" style="width:{{ $overallRate }}%;background:var(--green)"></div></div>
                        </div>
                        <div style="margin-bottom:14px">
                            <div class="ring-row">
                                <span style="color:var(--muted)">Hawakuja</span>
                                <span style="font-weight:700;color:var(--red);font-family:var(--mono)">{{ $absentCount }}</span>
                            </div>
                            <div class="ring-row-bar"><div class="ring-row-fill" style="width:{{ 100 - $overallRate }}%;background:var(--red)"></div></div>
                        </div>
                        @php
                            $bestSchool  = $schoolCards->first();
                            $worstSchool = $schoolCards->last();
                        @endphp
                        @if($bestSchool)
                        <div style="background:var(--surface2);border-radius:var(--r-sm);padding:10px;font-size:12px;">
                            <div style="color:var(--muted);margin-bottom:4px;">Shule Bora: <strong style="color:var(--green)">{{ $bestSchool['name'] }}</strong> ({{ $bestSchool['rate'] }}%)</div>
                            <div style="color:var(--muted);">Chini Zaidi: <strong style="color:var(--red)">{{ $worstSchool['name'] }}</strong> ({{ $worstSchool['rate'] }}%)</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Hourly chart --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">⏰ Wakati wa Kufika</div>
                        <div class="card-sub">Walimu walifika saa ngapi</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-wrap"><canvas id="hourlyChart"></canvas></div>
                </div>
            </div>
        </div>

        {{-- SCHOOL CARDS STRIP --}}
        <div class="card" style="margin-bottom:22px">
            <div class="card-header">
                <div>
                    <div class="card-title">🏫 Mahudhurio kwa Shule</div>
                    <div class="card-sub">{{ $schoolCards->count() }} shule — {{ $selectedDate }}</div>
                </div>
                <span style="font-size:12px;color:var(--muted)">Rangi: 🟢 ≥80% · 🟡 ≥60% · 🔴 &lt;60%</span>
            </div>
            @if($schoolCards->isEmpty())
            <div class="empty"><i class="fas fa-school"></i><p>Hakuna data ya shule</p></div>
            @else
            <div class="school-strip">
                @foreach($schoolCards as $sc)
                @php $sc_color = $sc['rate'] >= 80 ? 'var(--green)' : ($sc['rate'] >= 60 ? 'var(--yellow)' : ($sc['rate'] > 0 ? 'var(--red)' : 'var(--muted)')) @endphp
                <div class="school-att-card">
                    <div class="sac-name" title="{{ $sc['name'] }}">{{ $sc['name'] }}</div>
                    <div class="sac-ward"><i class="fas fa-map-marker-alt" style="font-size:10px;margin-right:3px"></i>{{ $sc['ward'] }}</div>
                    <div class="sac-nums">
                        <span style="color:var(--green)"><i class="fas fa-check" style="font-size:10px;margin-right:2px"></i>{{ $sc['came'] }}</span>
                        <span style="color:var(--red)"><i class="fas fa-times" style="font-size:10px;margin-right:2px"></i>{{ $sc['absent'] }}</span>
                        <span style="color:var(--muted)">/ {{ $sc['total'] }}</span>
                    </div>
                    <div class="sac-bar-bg"><div class="sac-bar" style="width:{{ $sc['rate'] }}%;background:{{ $sc_color }}"></div></div>
                    <div class="sac-rate" style="color:{{ $sc_color }}">{{ $sc['rate'] }}%</div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- FILTER BAR --}}
        <form method="GET" id="filterForm">
            <input type="hidden" name="date" value="{{ $selectedDate }}">
            <div class="filter-bar">
                <div class="filter-group" style="flex:2;min-width:180px">
                    <label class="filter-label">Tafuta Mwalimu</label>
                    <div class="search-wrap">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="form-input" placeholder="Jina au namba ya cheki..." value="{{ $search }}">
                    </div>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Kata</label>
                    <select name="ward_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Zote</option>
                        @foreach($wards as $w)
                        <option value="{{ $w->id }}" {{ $selectedWard == $w->id ? 'selected':'' }}>{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Shule</label>
                    <select name="school_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Zote</option>
                        @foreach($schools as $sc)
                        <option value="{{ $sc->id }}" {{ $selectedSchool == $sc->id ? 'selected':'' }}>{{ $sc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="min-width:120px">
                    <label class="filter-label">Hali</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Wote</option>
                        <option value="present" {{ $statusFilter==='present'?'selected':'' }}>✅ Walifika</option>
                        <option value="absent"  {{ $statusFilter==='absent' ?'selected':'' }}>❌ Hawakuja</option>
                    </select>
                </div>
                <div style="display:flex;gap:8px;align-items:flex-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Chuja</button>
                    <a href="{{ route('district.attendance.index', ['date' => $selectedDate]) }}" class="btn btn-ghost"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </form>

        {{-- TABS: Wote / Hawakuja --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">📋 Orodha ya Walimu</div>
                    <div class="card-sub">
                        Walimuonyeshwa: <strong style="color:var(--text)">{{ $teachers->firstItem() ?? 0 }}–{{ $teachers->lastItem() ?? 0 }}</strong> kati ya <strong style="color:var(--text)">{{ $teachers->total() }}</strong>
                    </div>
                </div>
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                    <div class="tabs">
                        <button class="tab-btn active" onclick="switchTab('all', this)">Wote</button>
                        <button class="tab-btn" onclick="switchTab('absent', this)">
                            Hawakuja <span style="background:var(--red);color:#fff;font-size:10px;padding:1px 6px;border-radius:10px;margin-left:4px">{{ $absentTeachers->count() }}</span>
                        </button>
                    </div>
                    <select class="form-select" style="width:auto;padding:5px 10px;font-size:12px"
                        onchange="changePerPage(this.value)">
                        @foreach([15,25,50,100] as $pp)
                        <option value="{{ $pp }}" {{ $perPage==$pp?'selected':'' }}>{{ $pp }}/ukurasa</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- TAB: WOTE --}}
            <div id="tab-all" class="tab-pane active">
                @if($teachers->isEmpty())
                <div class="empty"><i class="fas fa-users"></i><p>Hakuna walimu waliopatikana</p></div>
                @else
                <div class="table-wrap">
                    <table>
                        <thead><tr>
                            <th>#</th>
                            <th>Mwalimu</th>
                            <th>Shule</th>
                            <th>Kata</th>
                            <th>Jinsia</th>
                            <th>Hali</th>
                            <th>Wakati</th>
                        </tr></thead>
                        <tbody>
                            @foreach($teachers as $i => $t)
                            @php $initials = strtoupper(substr($t->first_name,0,1).substr($t->last_name,0,1)) @endphp
                            <tr>
                                <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $teachers->firstItem()+$i }}</td>
                                <td>
                                    <div class="t-info">
                                        <div class="t-avatar {{ $t->sex==='female'?'female-av':'male-av' }}">{{ $initials }}</div>
                                        <div>
                                            <div class="t-name">{{ $t->first_name }} {{ $t->last_name }}</div>
                                            <div class="t-check">
                                                {{ $t->check_number }}
                                                @if($t->role === 'head_teacher')
                                                    · <span style="font-size:11px;color:var(--muted)">Mwalimu Mkuu</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:12px;">{{ $t->school->name ?? '—' }}</td>
                                <td style="font-size:12px;color:var(--muted)">{{ $t->school->ward->name ?? '—' }}</td>
                                <td>
                                    <span style="font-size:12px;color:{{ $t->sex==='female'?'var(--pink)':'var(--accent2)' }}">
                                        {{ $t->sex==='female'?'♀ Mke':'♂ Mme' }}
                                    </span>
                                </td>
                                <td>
                                    @if($t->is_present)
                                    <span class="badge badge-present"><i class="fas fa-check-circle" style="font-size:10px"></i> Alikuja</span>
                                    @else
                                    <span class="badge badge-absent"><i class="fas fa-times-circle" style="font-size:10px"></i> Hakuja</span>
                                    @endif
                                </td>
                                <td>
                                    @if($t->check_in_time)
                                    <span class="time-chip">{{ $t->check_in_time }}</span>
                                    @else
                                    <span style="color:var(--muted);font-size:12px">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- PAGINATION --}}
                <div class="pag-wrap">
                    <span class="pag-info">Ukurasa {{ $teachers->currentPage() }} / {{ $teachers->lastPage() }}</span>
                    <div class="pag">
                        @if($teachers->onFirstPage())
                            <span style="opacity:.4"><i class="fas fa-chevron-left" style="font-size:11px"></i></span>
                        @else
                            <a href="{{ $teachers->previousPageUrl() }}"><i class="fas fa-chevron-left" style="font-size:11px"></i></a>
                        @endif
                        @foreach($teachers->getUrlRange(max(1,$teachers->currentPage()-2),min($teachers->lastPage(),$teachers->currentPage()+2)) as $page => $url)
                            @if($page==$teachers->currentPage())
                                <span class="cur">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                        @if($teachers->hasMorePages())
                            <a href="{{ $teachers->nextPageUrl() }}"><i class="fas fa-chevron-right" style="font-size:11px"></i></a>
                        @else
                            <span style="opacity:.4"><i class="fas fa-chevron-right" style="font-size:11px"></i></span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- TAB: HAWAKUJA --}}
            <div id="tab-absent" class="tab-pane">
                @if($absentTeachers->isEmpty())
                <div class="empty">
                    <i class="fas fa-check-double" style="color:var(--green)"></i>
                    <p style="color:var(--green);font-weight:600;font-size:14px">Walimu wote walifika! 🎉</p>
                </div>
                @else
                <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:13px;color:var(--red);font-weight:600">
                        <i class="fas fa-exclamation-triangle" style="margin-right:6px"></i>
                        {{ $absentTeachers->count() }} walimu hawakufika leo
                    </span>
                    <a href="{{ route('district.attendance.export.csv', array_merge(request()->query(), ['status'=>'absent'])) }}"
                       class="btn btn-ghost btn-sm">
                        <i class="fas fa-download"></i> Export Orodha
                    </a>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead><tr>
                            <th>#</th><th>Mwalimu</th><th>Shule</th><th>Kata</th><th>Jinsia</th><th>Simu</th>
                        </tr></thead>
                        <tbody>
                            @foreach($absentTeachers as $i => $t)
                            @php $initials = strtoupper(substr($t->first_name,0,1).substr($t->last_name,0,1)) @endphp
                            <tr>
                                <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                                <td>
                                    <div class="t-info">
                                        <div class="t-avatar {{ $t->sex==='female'?'female-av':'male-av' }}" style="opacity:.7">{{ $initials }}</div>
                                        <div>
                                            <div class="t-name">{{ $t->first_name }} {{ $t->last_name }}</div>
                                            <div class="t-check">{{ $t->check_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:12px">{{ $t->school->name ?? '—' }}</td>
                                <td style="font-size:12px;color:var(--muted)">{{ $t->school->ward->name ?? '—' }}</td>
                                <td style="font-size:12px;color:{{ $t->sex==='female'?'var(--pink)':'var(--accent2)' }}">
                                    {{ $t->sex==='female'?'♀ Mke':'♂ Mme' }}
                                </td>
                                <td style="font-family:var(--mono);font-size:12px;color:var(--muted)">{{ $t->phone }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

    </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
// SIDEBAR
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('overlay').classList.toggle('open')}
function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('overlay').classList.remove('open')}

// TABS
function switchTab(tab, btn){
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-'+tab).classList.add('active');
    btn.classList.add('active');
}

// PER PAGE
function changePerPage(val){
    const url = new URL(window.location);
    url.searchParams.set('per_page', val);
    url.searchParams.set('page', 1);
    window.location = url;
}

// HOURLY CHART
const hourlyData = @json($hourlyData);
new Chart(document.getElementById('hourlyChart').getContext('2d'),{
    type: 'bar',
    data:{
        labels: hourlyData.map(d => d.hour),
        datasets:[{
            label:'Walimu waliofika',
            data: hourlyData.map(d => d.count),
            backgroundColor: hourlyData.map(d => {
                const h = parseInt(d.hour);
                if(h < 8)  return 'rgba(245,158,11,0.7)';
                if(h <= 9) return 'rgba(16,185,129,0.7)';
                return 'rgba(59,130,246,0.5)';
            }),
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{
            legend:{display:false},
            tooltip:{
                backgroundColor:'#1e2335',borderColor:'#2a2f45',borderWidth:1,
                titleColor:'#e2e8f0',bodyColor:'#94a3b8',
                callbacks:{label: ctx => ` Walimu ${ctx.parsed.y}`}
            }
        },
        scales:{
            x:{ticks:{color:'#64748b',font:{size:10}},grid:{color:'rgba(42,47,69,.4)'}},
            y:{ticks:{color:'#64748b',font:{size:10},stepSize:1},grid:{color:'rgba(42,47,69,.4)'},beginAtZero:true}
        }
    }
});
</script>
</body>
</html>