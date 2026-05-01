{{-- resources/views/district/schools/show.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $school->name }} · District Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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

        /* SIDEBAR */
        .sidebar{position:fixed;left:0;top:0;bottom:0;width:240px;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;z-index:100;transition:transform .3s}
        .sidebar-logo{padding:24px 20px 20px;border-bottom:1px solid var(--border)}
        .logo-badge{display:flex;align-items:center;gap:10px}
        .logo-icon{width:38px;height:38px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px}
        .logo-text{font-size:14px;font-weight:700;line-height:1.2}.logo-sub{font-size:11px;color:var(--muted)}
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

        /* MAIN */
        .main{margin-left:240px;min-height:100vh}
        .topbar{position:sticky;top:0;z-index:50;background:rgba(15,17,23,.92);backdrop-filter:blur(14px);border-bottom:1px solid var(--border);padding:14px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px}
        .topbar-left{display:flex;align-items:center;gap:14px}
        .hamburger{display:none;background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:4px}
        .breadcrumb{display:flex;align-items:center;gap:8px;font-size:13px}
        .breadcrumb a{color:var(--muted);text-decoration:none}.breadcrumb a:hover{color:var(--text)}
        .breadcrumb span{color:var(--muted)}.breadcrumb strong{color:var(--text);font-weight:600}
        .btn{padding:7px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:600;border:none;cursor:pointer;font-family:var(--font);transition:all .2s;display:inline-flex;align-items:center;gap:6px;text-decoration:none}
        .btn-primary{background:var(--accent);color:#fff}.btn-primary:hover{background:#2563eb}
        .btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}.btn-ghost:hover{background:var(--border)}
        .btn-sm{padding:5px 10px;font-size:12px}
        .btn-warning{background:rgba(245,158,11,.15);color:var(--yellow);border:1px solid rgba(245,158,11,.3)}
        .btn-danger{background:rgba(239,68,68,.15);color:var(--red);border:1px solid rgba(239,68,68,.3)}
        .btn-success{background:rgba(16,185,129,.15);color:var(--green);border:1px solid rgba(16,185,129,.3)}

        .content{padding:24px 28px}

        /* FLASH */
        .flash{padding:12px 16px;border-radius:var(--r-sm);font-size:13px;display:flex;align-items:center;gap:10px;margin-bottom:20px;animation:slideIn .3s}
        .flash-success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:var(--green)}
        .flash-error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:var(--red)}
        @keyframes slideIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}

        /* SCHOOL HERO */
        .school-hero{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:24px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;position:relative;overflow:hidden}
        .school-hero::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--accent),var(--accent2))}
        .hero-left{display:flex;align-items:center;gap:16px}
        .hero-icon{width:60px;height:60px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:28px;background:rgba(59,130,246,.12);flex-shrink:0}
        .hero-name{font-size:22px;font-weight:800;line-height:1.2}
        .hero-meta{display:flex;align-items:center;gap:16px;margin-top:8px;flex-wrap:wrap}
        .hero-meta-item{display:flex;align-items:center;gap:5px;font-size:12px;color:var(--muted)}
        .status-pill{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
        .pill-active{background:rgba(16,185,129,.15);color:var(--green)}
        .pill-inactive{background:rgba(100,116,139,.15);color:var(--muted)}
        .hero-actions{display:flex;gap:8px;flex-wrap:wrap}

        /* STATS GRID */
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:20px}
        .stat-box{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:18px;text-align:center;position:relative;overflow:hidden}
        .stat-box::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px}
        .stat-box.sb-blue::after{background:var(--accent)}.stat-box.sb-green::after{background:var(--green)}
        .stat-box.sb-yellow::after{background:var(--yellow)}.stat-box.sb-red::after{background:var(--red)}
        .stat-box.sb-purple::after{background:var(--accent2)}
        .sb-val{font-size:34px;font-weight:800;font-family:var(--mono);line-height:1}
        .sb-label{font-size:11px;color:var(--muted);margin-top:6px;font-weight:500}

        /* RING */
        .ring-wrap{position:relative;display:inline-flex;align-items:center;justify-content:center}
        .ring-wrap svg{transform:rotate(-90deg)}
        .ring-text{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center}
        .ring-pct{font-size:22px;font-weight:700;font-family:var(--mono)}
        .ring-sub{font-size:9px;color:var(--muted)}

        /* LAYOUT */
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
        .grid-3{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);overflow:hidden}
        .card-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .card-title{font-size:14px;font-weight:700}
        .card-sub{font-size:12px;color:var(--muted);margin-top:2px}
        .card-body{padding:20px}
        .chart-wrap{height:200px;position:relative}

        /* MAP */
        #schoolDetailMap{height:220px;width:100%;border-radius:0}

        /* TEACHER TABLE */
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:13px}
        thead th{padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;background:var(--surface2);border-bottom:1px solid var(--border);white-space:nowrap}
        tbody td{padding:11px 16px;border-bottom:1px solid rgba(42,47,69,.5);vertical-align:middle}
        tbody tr:last-child td{border-bottom:none}
        tbody tr:hover td{background:rgba(30,35,53,.6);cursor:pointer}
        .t-avatar{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0}
        .male-av{background:rgba(99,102,241,.2);color:var(--accent2)}
        .female-av{background:rgba(236,72,153,.2);color:var(--pink)}
        .t-info{display:flex;align-items:center;gap:10px}
        .t-name{font-weight:600;font-size:13px}
        .t-check{font-size:11px;color:var(--muted);font-family:var(--mono)}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600}
        .badge-approved{background:rgba(16,185,129,.15);color:var(--green)}
        .badge-pending{background:rgba(245,158,11,.15);color:var(--yellow)}
        .badge-rejected{background:rgba(239,68,68,.15);color:var(--red)}
        .today-yes{color:var(--green);font-weight:700;font-family:var(--mono)}
        .today-no{color:var(--red);font-family:var(--mono)}
        .att-bar-bg{height:4px;background:var(--surface2);border-radius:99px;overflow:hidden;width:60px;margin-top:3px}
        .att-bar{height:100%;border-radius:99px}

        /* MONTHLY BARS */
        .month-bar-item{display:flex;align-items:center;gap:10px;margin-bottom:10px}
        .month-name{font-size:12px;color:var(--muted);width:60px;flex-shrink:0}
        .month-bar-bg{flex:1;height:8px;background:var(--surface2);border-radius:99px;overflow:hidden}
        .month-bar{height:100%;border-radius:99px;transition:width .8s ease}
        .month-rate{font-size:12px;font-family:var(--mono);font-weight:600;width:38px;text-align:right;flex-shrink:0}

        /* MODAL */
        .modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);backdrop-filter:blur(4px);z-index:200;align-items:center;justify-content:center;padding:16px}
        .modal-bg.open{display:flex}
        .modal{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);width:100%;max-width:500px;max-height:92vh;overflow-y:auto;animation:modalIn .25s ease}
        .modal-lg{max-width:700px}
        @keyframes modalIn{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
        .modal-header{padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .modal-title{font-size:16px;font-weight:700}
        .modal-close{background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px;padding:4px;transition:color .15s}
        .modal-close:hover{color:var(--text)}
        .modal-body{padding:24px}
        .modal-footer{padding:16px 24px;border-top:1px solid var(--border);display:flex;gap:10px;justify-content:flex-end}
        .form-group{margin-bottom:16px}
        .form-label{display:block;font-size:12px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.7px;margin-bottom:6px}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
        .form-input,.form-select{background:var(--surface2);border:1px solid var(--border);color:var(--text);border-radius:var(--r-sm);padding:8px 12px;font-size:13px;font-family:var(--font);outline:none;width:100%}
        .form-input:focus,.form-select:focus{border-color:var(--accent)}
        .form-hint{font-size:11px;color:var(--muted);margin-top:4px}
        .invalid-feedback{font-size:11px;color:var(--red);margin-top:4px}

        /* OVERLAY */
        .overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99}
        .overlay.open{display:block}

        /* TEACHER MODAL */
        .t-modal-avatar{width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;margin:0 auto 16px}
        .detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
        .detail-key{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.7px;margin-bottom:4px}
        .detail-val{font-size:14px;font-weight:600}
        .att-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:16px}
        .att-stat-box{background:var(--surface2);border-radius:var(--r-sm);padding:12px;text-align:center}
        .att-stat-num{font-size:22px;font-weight:700;font-family:var(--mono)}
        .att-stat-lbl{font-size:11px;color:var(--muted);margin-top:4px}

        @media(max-width:1024px){.grid-2,.grid-3{grid-template-columns:1fr}}
        @media(max-width:768px){
            .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}
            .overlay.open{display:block}.main{margin-left:0}
            .hamburger{display:block}.content{padding:16px}.topbar{padding:12px 16px}
            .stats-grid{grid-template-columns:repeat(2,1fr)}
            .school-hero{flex-direction:column}
            .detail-grid{grid-template-columns:1fr}.form-row{grid-template-columns:1fr}
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
            <a href="{{ route('dashboard') }}" class="nav-item"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <a href="#" class="nav-item"><i class="fas fa-calendar-check"></i> Mahudhurio</a>
            <a href="{{ route('district.schools.index') }}" class="nav-item active"><i class="fas fa-school"></i> Shule</a>
            <a href="{{ route('district.teachers.index') }}" class="nav-item">
                <i class="fas fa-chalkboard-teacher"></i> Walimu
                @if($pendingCount > 0)<span class="nav-badge">{{ $pendingCount }}</span>@endif
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Usimamizi</div>
            <a href="#" class="nav-item"><i class="fas fa-map-marker-alt"></i> Kata</a>
            <a href="#" class="nav-item"><i class="fas fa-exchange-alt"></i> Uhamisho</a>
            <a href="#" class="nav-item"><i class="fas fa-file-alt"></i> Ripoti</a>
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
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
                <span>/</span>
                <a href="{{ route('district.schools.index') }}">Shule</a>
                <span>/</span>
                <strong>{{ Str::limit($school->name, 30) }}</strong>
            </div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            <form method="GET" style="display:flex;align-items:center;gap:8px;">
                <input type="date" name="date" class="form-input" style="padding:6px 10px;font-size:12px;"
                       value="{{ $selectedDate }}" max="{{ now()->toDateString() }}" onchange="this.form.submit()">
            </form>
            <button class="btn btn-ghost btn-sm" onclick="openEditModal()"><i class="fas fa-edit"></i> Hariri</button>
            <form method="POST" action="{{ route('district.schools.toggle', $school) }}" style="display:inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $school->is_active ? 'btn-warning' : 'btn-success' }}">
                    <i class="fas fa-{{ $school->is_active ? 'ban' : 'check' }}"></i>
                    {{ $school->is_active ? 'Zima' : 'Washa' }}
                </button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </header>

    <div class="content">

        @if(session('success'))
        <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="flash flash-error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
        @endif

        {{-- HERO --}}
        <div class="school-hero">
            <div class="hero-left">
                <div class="hero-icon">{{ str_contains(strtolower($school->name),'secondary') ? '🎓' : '🏫' }}</div>
                <div>
                    <div class="hero-name">{{ $school->name }}</div>
                    <div class="hero-meta">
                        <div class="hero-meta-item"><i class="fas fa-map-marker-alt"></i> {{ $school->ward->name ?? '—' }}</div>
                        @if($school->code)<div class="hero-meta-item"><i class="fas fa-hashtag"></i> {{ $school->code }}</div>@endif
                        @if($school->latitude)
                        <div class="hero-meta-item"><i class="fas fa-crosshairs"></i> GPS ✅</div>
                        @else
                        <div class="hero-meta-item" style="color:var(--red)"><i class="fas fa-crosshairs"></i> Hakuna GPS</div>
                        @endif
                        <span class="status-pill {{ $school->is_active ? 'pill-active' : 'pill-inactive' }}">
                            <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block"></span>
                            {{ $school->is_active ? 'Inafanya Kazi' : 'Imezimwa' }}
                        </span>
                    </div>
                </div>
            </div>
            <div style="font-size:13px;color:var(--muted);text-align:right;">
                <div>Tarehe: <strong style="color:var(--text)">{{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</strong></div>
                <div style="margin-top:4px">Radius: <strong style="color:var(--text)">{{ $school->radius }}m</strong></div>
            </div>
        </div>

        {{-- STATS --}}
        <div class="stats-grid">
            <div class="stat-box sb-blue">
                <div class="sb-val" style="color:var(--accent)">{{ $teachers->count() }}</div>
                <div class="sb-label">Walimu Wote</div>
            </div>
            <div class="stat-box sb-green">
                <div class="sb-val" style="color:var(--green)">{{ $approvedTeachers }}</div>
                <div class="sb-label">Walioidhinishwa</div>
            </div>
            <div class="stat-box sb-yellow">
                <div class="sb-val" style="color:var(--yellow)">{{ $pendingTeachers }}</div>
                <div class="sb-label">Wanaongoja</div>
            </div>
            <div class="stat-box sb-green">
                <div class="sb-val" style="color:var(--green)">{{ $attendedToday }}</div>
                <div class="sb-label">Walifika Leo</div>
            </div>
            <div class="stat-box {{ $attendanceRate >= 80 ? 'sb-green' : ($attendanceRate >= 60 ? 'sb-yellow' : 'sb-red') }}">
                <div class="ring-wrap" style="margin:0 auto">
                    <svg width="80" height="80" viewBox="0 0 80 80">
                        <circle class="ring-bg" cx="40" cy="40" r="32" style="fill:none;stroke:var(--surface2);stroke-width:8"/>
                        <circle cx="40" cy="40" r="32"
                            style="fill:none;stroke-width:8;stroke-linecap:round;stroke:{{ $attendanceRate >= 80 ? 'var(--green)' : ($attendanceRate >= 60 ? 'var(--yellow)' : 'var(--red)') }};stroke-dasharray:{{ round(($attendanceRate/100)*201.06) }} 201.06"
                        />
                    </svg>
                    <div class="ring-text">
                        <div class="ring-pct" style="font-size:16px;color:{{ $attendanceRate >= 80 ? 'var(--green)' : ($attendanceRate >= 60 ? 'var(--yellow)' : 'var(--red)') }}">{{ $attendanceRate }}%</div>
                        <div class="ring-sub">LEO</div>
                    </div>
                </div>
                <div class="sb-label" style="margin-top:6px">Kiwango Leo</div>
            </div>
        </div>

        {{-- TREND + MONTHLY --}}
        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">📈 Mwenendo — Siku 14</div>
                        <div class="card-sub">Mahudhurio kila siku</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-wrap"><canvas id="trendChart"></canvas></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">📅 Muhtasari wa Miezi 6</div>
                        <div class="card-sub">Kiwango cha kila mwezi</div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($monthlySummary as $m)
                    @php $mc = $m['rate'] >= 80 ? 'var(--green)' : ($m['rate'] >= 60 ? 'var(--yellow)' : 'var(--red)') @endphp
                    <div class="month-bar-item">
                        <div class="month-name">{{ $m['month'] }}</div>
                        <div class="month-bar-bg">
                            <div class="month-bar" style="width:{{ $m['rate'] }}%;background:{{ $mc }}"></div>
                        </div>
                        <div class="month-rate" style="color:{{ $mc }}">{{ $m['rate'] }}%</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- TEACHERS TABLE + MAP --}}
        <div class="grid-3">
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">👨‍🏫 Walimu ({{ $teachers->count() }})</div>
                        <div class="card-sub">Bonyeza mwalimu kuona maelezo</div>
                    </div>
                    <div style="display:flex;gap:6px">
                        <span style="font-size:11px;color:var(--muted);padding:4px 8px;background:var(--surface2);border-radius:20px">
                            {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                        </span>
                    </div>
                </div>
                <div class="table-wrap">
                    @if($teachers->isEmpty())
                    <div style="text-align:center;padding:40px;color:var(--muted)">
                        <i class="fas fa-user-slash" style="font-size:32px;margin-bottom:10px;display:block;opacity:.4"></i>
                        <p>Hakuna walimu waliopo</p>
                    </div>
                    @else
                    <table>
                        <thead><tr>
                            <th>Mwalimu</th>
                            <th>Hali</th>
                            <th>Leo</th>
                            <th>Mahud. (30d)</th>
                        </tr></thead>
                        <tbody>
                            @foreach($teachers as $t)
                            @php $initials = strtoupper(substr($t->first_name,0,1).substr($t->last_name,0,1)) @endphp
                            <tr onclick="openTeacherModal({{ $t->id }})">
                                <td>
                                    <div class="t-info">
                                        <div class="t-avatar {{ $t->sex==='female'?'female-av':'male-av' }}">{{ $initials }}</div>
                                        <div>
                                            <div class="t-name">{{ $t->first_name }} {{ $t->last_name }}</div>
                                            <div class="t-check">{{ $t->check_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-{{ $t->status }}">{{ $t->status==='approved'?'✅':($t->status==='pending'?'⏳':'❌') }}</span></td>
                                <td>
                                    @if($t->came_today)
                                    <span class="today-yes">✓ Alikuja</span>
                                    @else
                                    <span class="today-no">✗ Hakuja</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size:12px;font-family:var(--mono);color:{{ $t->att_rate>=80?'var(--green)':($t->att_rate>=60?'var(--yellow)':'var(--red)') }}">{{ $t->att_rate }}%</div>
                                    <div class="att-bar-bg"><div class="att-bar" style="width:{{ $t->att_rate }}%;background:{{ $t->att_rate>=80?'var(--green)':($t->att_rate>=60?'var(--yellow)':'var(--red)') }}"></div></div>
                                </td>
                            </tr>
                            {{-- Teacher data for modal --}}
                            <script>
                            window.__tdata = window.__tdata || {};
                            window.__tdata[{{ $t->id }}] = {
                                name:"{{ $t->first_name }} {{ $t->middle_name ? $t->middle_name.' ':'' }}{{ $t->last_name }}",
                                check:"{{ $t->check_number }}",email:"{{ $t->email }}",phone:"{{ $t->phone }}",
                                sex:"{{ $t->sex }}",status:"{{ $t->status }}",initials:"{{ $initials }}",
                                attDays:{{ $t->att_days }},workDays:{{ $workingDays }},attRate:{{ $t->att_rate }},
                                cameToday:{{ $t->came_today?'true':'false' }},
                                joined:"{{ $t->created_at?$t->created_at->format('d M Y'):'—' }}"
                            };
                            </script>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>

            {{-- MAP + INFO --}}
            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">📍 Eneo la Shule</div>
                    </div>
                    @if($school->latitude && $school->longitude)
                    <div id="schoolDetailMap"></div>
                    <div style="padding:12px 16px;border-top:1px solid var(--border);font-size:12px;color:var(--muted);display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                        <div>Lat: <span style="font-family:var(--mono);color:var(--text)">{{ $school->latitude }}</span></div>
                        <div>Lng: <span style="font-family:var(--mono);color:var(--text)">{{ $school->longitude }}</span></div>
                        <div>Radius: <span style="font-family:var(--mono);color:var(--text)">{{ $school->radius }}m</span></div>
                    </div>
                    @else
                    <div style="padding:32px;text-align:center;color:var(--muted)">
                        <i class="fas fa-map-marker-slash" style="font-size:28px;margin-bottom:10px;display:block;opacity:.4"></i>
                        <p style="font-size:13px">GPS haijawekwa</p>
                        <button class="btn btn-ghost btn-sm" style="margin-top:12px" onclick="openEditModal()">
                            <i class="fas fa-plus"></i> Weka GPS
                        </button>
                    </div>
                    @endif
                </div>

                <div class="card">
                    <div class="card-header"><div class="card-title">📊 Muhtasari wa Haraka</div></div>
                    <div class="card-body" style="padding:16px;">
                        @php
                            $absent = max(0, $approvedTeachers - $attendedToday);
                        @endphp
                        <div style="display:flex;flex-direction:column;gap:10px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                                <span style="color:var(--muted)">Waliofika</span>
                                <span style="font-weight:700;color:var(--green);font-family:var(--mono)">{{ $attendedToday }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                                <span style="color:var(--muted)">Hawakuja</span>
                                <span style="font-weight:700;color:var(--red);font-family:var(--mono)">{{ $absent }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                                <span style="color:var(--muted)">Wanaongoja idhini</span>
                                <span style="font-weight:700;color:var(--yellow);font-family:var(--mono)">{{ $pendingTeachers }}</span>
                            </div>
                            <div style="height:1px;background:var(--border);margin:4px 0"></div>
                            <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                                <span style="color:var(--muted)">Wastani wiki hii</span>
                                @php
                                    $weekRates = collect($trend)->slice(-7)->pluck('rate');
                                    $weekAvg = $weekRates->count() > 0 ? round($weekRates->avg(), 1) : 0;
                                @endphp
                                <span style="font-weight:700;font-family:var(--mono);color:{{ $weekAvg>=80?'var(--green)':($weekAvg>=60?'var(--yellow)':'var(--red)') }}">{{ $weekAvg }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal-bg" id="editModalBg" onclick="closeEditModal(event)">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">✏️ Hariri Shule</div>
            <button class="modal-close" onclick="closeEditModalDirect()"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('district.schools.update', $school) }}">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Jina la Shule *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $school->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kata *</label>
                        <select name="ward_id" class="form-select" required>
                            @foreach($wards as $w)
                            <option value="{{ $w->id }}" {{ (old('ward_id',$school->ward_id)==$w->id)?'selected':'' }}>{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Namba (Code)</label>
                        <input type="text" name="code" class="form-input" value="{{ old('code', $school->code) }}">
                    </div>
                </div>
                <div style="background:var(--surface2);border-radius:var(--r-sm);padding:14px;margin-bottom:4px;">
                    <div style="font-size:12px;font-weight:600;color:var(--muted);margin-bottom:12px;text-transform:uppercase;letter-spacing:.7px;">
                        <i class="fas fa-map-marker-alt" style="margin-right:6px"></i>GPS Location
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Latitude</label>
                            <input type="number" name="latitude" class="form-input" step="0.0000001" value="{{ old('latitude', $school->latitude) }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Longitude</label>
                            <input type="number" name="longitude" class="form-input" step="0.0000001" value="{{ old('longitude', $school->longitude) }}">
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:12px;margin-bottom:0">
                        <label class="form-label">Radius (mita)</label>
                        <input type="number" name="radius" class="form-input" min="50" max="5000" value="{{ old('radius', $school->radius) }}">
                        <div class="form-hint">Umbali unaoruhusiwa kwa check-in</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeEditModalDirect()">Funga</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Hifadhi</button>
            </div>
        </form>
    </div>
</div>

{{-- TEACHER DETAIL MODAL --}}
<div class="modal-bg" id="teacherModalBg" onclick="closeTeacherModal(event)">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Maelezo ya Mwalimu</div>
            <button class="modal-close" onclick="closeTeacherModalDirect()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="t-modal-avatar" id="tm-avatar"></div>
            <div style="text-align:center;margin-bottom:20px;">
                <div style="font-size:18px;font-weight:700;" id="tm-name"></div>
                <div style="font-size:12px;color:var(--muted);font-family:var(--mono);margin-top:3px;" id="tm-check"></div>
                <div style="margin-top:10px;display:flex;gap:8px;justify-content:center;" id="tm-badges"></div>
            </div>
            <div style="height:1px;background:var(--border);margin-bottom:16px"></div>
            <div class="detail-grid">
                <div><div class="detail-key">Barua pepe</div><div class="detail-val" style="font-size:13px;font-family:var(--mono)" id="tm-email"></div></div>
                <div><div class="detail-key">Simu</div><div class="detail-val" style="font-family:var(--mono)" id="tm-phone"></div></div>
                <div><div class="detail-key">Alijiunga</div><div class="detail-val" id="tm-joined"></div></div>
                <div>
                    <div class="detail-key">Alikuja Leo</div>
                    <div class="detail-val" id="tm-today"></div>
                </div>
            </div>
            <div style="height:1px;background:var(--border);margin:16px 0"></div>
            <div style="font-size:12px;font-weight:600;color:var(--muted);margin-bottom:10px;text-transform:uppercase;letter-spacing:.7px;">Mahudhurio — Siku 30</div>
            <div class="att-stats">
                <div class="att-stat-box"><div class="att-stat-num" id="tm-days" style="color:var(--accent)"></div><div class="att-stat-lbl">Alikuja</div></div>
                <div class="att-stat-box"><div class="att-stat-num" id="tm-absent" style="color:var(--red)"></div><div class="att-stat-lbl">Hakuja</div></div>
                <div class="att-stat-box"><div class="att-stat-num" id="tm-rate"></div><div class="att-stat-lbl">Kiwango</div></div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeTeacherModalDirect()">Funga</button>
        </div>
    </div>
</div>

<script>
// SIDEBAR
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('overlay').classList.toggle('open')}
function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('overlay').classList.remove('open')}

// EDIT MODAL
function openEditModal(){document.getElementById('editModalBg').classList.add('open');document.body.style.overflow='hidden'}
function closeEditModal(e){if(e.target===document.getElementById('editModalBg'))closeEditModalDirect()}
function closeEditModalDirect(){document.getElementById('editModalBg').classList.remove('open');document.body.style.overflow=''}

// TEACHER MODAL
function openTeacherModal(id){
    const t = window.__tdata[id]; if(!t) return;
    const av = document.getElementById('tm-avatar');
    av.textContent = t.initials;
    av.style.background = t.sex==='female'?'linear-gradient(135deg,#ec4899,#be185d)':'linear-gradient(135deg,#6366f1,#3b82f6)';
    av.style.color='#fff';
    document.getElementById('tm-name').textContent = t.name;
    document.getElementById('tm-check').textContent = t.check;
    const sMap={approved:'badge-approved',pending:'badge-pending',rejected:'badge-rejected'};
    const sLbl={approved:'Imeidhinishwa',pending:'Inasubiri',rejected:'Imekataliwa'};
    document.getElementById('tm-badges').innerHTML=`
        <span class="badge ${t.sex==='female'?'badge-approved':'badge-approved'}" style="background:${t.sex==='female'?'rgba(236,72,153,.15)':'rgba(99,102,241,.15)'};color:${t.sex==='female'?'var(--pink)':'var(--accent2)'}">
            ${t.sex==='female'?'♀ Mwanamke':'♂ Mwanaume'}
        </span>
        <span class="badge ${sMap[t.status]}">${sLbl[t.status]}</span>
    `;
    document.getElementById('tm-email').textContent=t.email;
    document.getElementById('tm-phone').textContent=t.phone;
    document.getElementById('tm-joined').textContent=t.joined;
    const todayEl=document.getElementById('tm-today');
    todayEl.textContent=t.cameToday?'✅ Alikuja':'❌ Hakuja';
    todayEl.style.color=t.cameToday?'var(--green)':'var(--red)';
    const absent=Math.max(0,t.workDays-t.attDays);
    const rc=t.attRate>=80?'var(--green)':t.attRate>=60?'var(--yellow)':'var(--red)';
    document.getElementById('tm-days').textContent=t.attDays;
    document.getElementById('tm-absent').textContent=absent;
    const re=document.getElementById('tm-rate');re.textContent=t.attRate+'%';re.style.color=rc;
    document.getElementById('teacherModalBg').classList.add('open');
    document.body.style.overflow='hidden';
}
function closeTeacherModal(e){if(e.target===document.getElementById('teacherModalBg'))closeTeacherModalDirect()}
function closeTeacherModalDirect(){document.getElementById('teacherModalBg').classList.remove('open');document.body.style.overflow=''}

// TREND CHART
const trend = @json($trend);
new Chart(document.getElementById('trendChart').getContext('2d'),{
    type:'line',
    data:{
        labels:trend.map(d=>d.date),
        datasets:[{
            label:'Kiwango (%)',data:trend.map(d=>d.rate),
            borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,.08)',
            fill:true,tension:.4,pointBackgroundColor:'#3b82f6',pointRadius:3,
        },{
            label:'Waliofika',data:trend.map(d=>d.attended),
            borderColor:'#10b981',backgroundColor:'transparent',
            tension:.4,pointBackgroundColor:'#10b981',pointRadius:3,yAxisID:'y2'
        }]
    },
    options:{
        responsive:true,maintainAspectRatio:false,
        interaction:{mode:'index',intersect:false},
        plugins:{legend:{labels:{color:'#94a3b8',font:{size:10},boxWidth:10}},
            tooltip:{backgroundColor:'#1e2335',borderColor:'#2a2f45',borderWidth:1,titleColor:'#e2e8f0',bodyColor:'#94a3b8'}
        },
        scales:{
            x:{ticks:{color:'#64748b',font:{size:9}},grid:{color:'rgba(42,47,69,.5)'}},
            y:{ticks:{color:'#64748b',font:{size:9},callback:v=>v+'%'},grid:{color:'rgba(42,47,69,.5)'},max:100,min:0},
            y2:{position:'right',ticks:{color:'#10b981',font:{size:9}},grid:{display:false}}
        }
    }
});

// MAP
@if($school->latitude && $school->longitude)
const map = L.map('schoolDetailMap',{zoomControl:true,scrollWheelZoom:false});
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',{attribution:'&copy; CARTO',maxZoom:18}).addTo(map);
const lat = {{ $school->latitude }}, lng = {{ $school->longitude }}, radius = {{ $school->radius }};
L.circle([lat,lng],{radius:radius,fillColor:'#3b82f6',fillOpacity:.15,color:'#3b82f6',weight:2}).addTo(map);
L.circleMarker([lat,lng],{radius:10,fillColor:'#3b82f6',color:'#fff',weight:2,fillOpacity:.9})
    .addTo(map).bindPopup('<strong>{{ $school->name }}</strong><br>Radius: {{ $school->radius }}m').openPopup();
map.setView([lat,lng],15);
@endif

// Flash dismiss
setTimeout(()=>{document.querySelectorAll('.flash').forEach(el=>{el.style.transition='opacity .5s';el.style.opacity='0';setTimeout(()=>el.remove(),500)})},4000)

// Open edit modal if errors
@if($errors->any()) openEditModal(); @endif
</script>
</body>
</html>