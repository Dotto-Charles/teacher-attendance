{{-- resources/views/district/reports/index.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ripoti · District Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root{
            --bg:#0f1117;--surface:#181c27;--surface2:#1e2335;--border:#2a2f45;
            --accent:#3b82f6;--accent2:#6366f1;--green:#10b981;--yellow:#f59e0b;
            --red:#ef4444;--pink:#ec4899;--orange:#f97316;--text:#e2e8f0;--muted:#64748b;
            --font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;--r:14px;--r-sm:8px;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:var(--font);background:var(--bg);color:var(--text);min-height:100vh}

        /* SIDEBAR */
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

        .main{margin-left:240px;min-height:100vh}
        .topbar{position:sticky;top:0;z-index:50;background:rgba(15,17,23,.92);backdrop-filter:blur(14px);border-bottom:1px solid var(--border);padding:14px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
        .topbar-left{display:flex;align-items:center;gap:14px}
        .hamburger{display:none;background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:4px}
        .breadcrumb{display:flex;align-items:center;gap:8px;font-size:13px}
        .breadcrumb a{color:var(--muted);text-decoration:none}.breadcrumb a:hover{color:var(--text)}
        .breadcrumb span{color:var(--muted)}.breadcrumb strong{color:var(--text);font-weight:600}
        .content{padding:24px 28px}

        .btn{padding:7px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:600;border:none;cursor:pointer;font-family:var(--font);transition:all .2s;display:inline-flex;align-items:center;gap:6px;text-decoration:none}
        .btn-primary{background:var(--accent);color:#fff}.btn-primary:hover{background:#2563eb}
        .btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}.btn-ghost:hover{background:var(--border)}
        .btn-success{background:rgba(16,185,129,.15);color:var(--green);border:1px solid rgba(16,185,129,.3)}
        .btn-danger{background:rgba(239,68,68,.15);color:var(--red);border:1px solid rgba(239,68,68,.3)}
        .btn-pdf{background:rgba(239,68,68,.15);color:var(--red);border:1px solid rgba(239,68,68,.3)}.btn-pdf:hover{background:rgba(239,68,68,.25)}
        .btn-csv{background:rgba(16,185,129,.15);color:var(--green);border:1px solid rgba(16,185,129,.3)}.btn-csv:hover{background:rgba(16,185,129,.25)}
        .btn-sm{padding:5px 12px;font-size:12px}

        /* FILTER PANEL */
        .filter-panel{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:20px;margin-bottom:24px}
        .filter-title{font-size:13px;font-weight:700;margin-bottom:16px;display:flex;align-items:center;gap:8px}
        .filter-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;align-items:end}
        .form-group{display:flex;flex-direction:column;gap:5px}
        .form-label{font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.7px}
        .form-select,.form-input{background:var(--surface2);border:1px solid var(--border);color:var(--text);border-radius:var(--r-sm);padding:9px 12px;font-size:13px;font-family:var(--font);outline:none;width:100%}
        .form-select:focus,.form-input:focus{border-color:var(--accent)}

        /* REPORT TYPE SELECTOR */
        .report-types{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;margin-bottom:24px}
        .rtype-card{background:var(--surface);border:2px solid var(--border);border-radius:var(--r);padding:16px;cursor:pointer;transition:all .2s;text-align:center;text-decoration:none;display:block}
        .rtype-card:hover{border-color:rgba(59,130,246,.4);transform:translateY(-2px)}
        .rtype-card.active{border-color:var(--accent);background:rgba(59,130,246,.08)}
        .rtype-icon{font-size:26px;margin-bottom:8px}
        .rtype-label{font-size:13px;font-weight:700;color:var(--text)}
        .rtype-sub{font-size:11px;color:var(--muted);margin-top:3px}

        /* SUMMARY CARDS */
        .sum-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:24px}
        .sum-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:18px;position:relative;overflow:hidden}
        .sum-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
        .sum-card.sc-blue::before{background:var(--accent)}.sum-card.sc-green::before{background:var(--green)}
        .sum-card.sc-red::before{background:var(--red)}.sum-card.sc-yellow::before{background:var(--yellow)}
        .sum-card.sc-purple::before{background:var(--accent2)}.sum-card.sc-orange::before{background:var(--orange)}
        .sum-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;margin-bottom:12px}
        .sc-blue .sum-icon{background:rgba(59,130,246,.15);color:var(--accent)}
        .sc-green .sum-icon{background:rgba(16,185,129,.15);color:var(--green)}
        .sc-red .sum-icon{background:rgba(239,68,68,.15);color:var(--red)}
        .sc-yellow .sum-icon{background:rgba(245,158,11,.15);color:var(--yellow)}
        .sc-purple .sum-icon{background:rgba(99,102,241,.15);color:var(--accent2)}
        .sc-orange .sum-icon{background:rgba(249,115,22,.15);color:var(--orange)}
        .sum-val{font-size:30px;font-weight:800;font-family:var(--mono);line-height:1}
        .sum-lbl{font-size:11px;color:var(--muted);margin-top:5px;font-weight:500}

        /* CARD */
        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;margin-bottom:20px}
        .card-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
        .card-title{font-size:14px;font-weight:700}
        .card-sub{font-size:12px;color:var(--muted);margin-top:2px}
        .card-body{padding:20px}
        .chart-wrap{height:220px;position:relative}

        /* GRID */
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
        .grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:20px}

        /* TABLE */
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:13px}
        thead th{padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;background:var(--surface2);border-bottom:1px solid var(--border);white-space:nowrap}
        tbody td{padding:11px 16px;border-bottom:1px solid rgba(42,47,69,.5);vertical-align:middle}
        tbody tr:last-child td{border-bottom:none}
        tbody tr:hover td{background:rgba(30,35,53,.5)}
        .t-info{display:flex;align-items:center;gap:10px}
        .t-av{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;background:rgba(59,130,246,.15);color:var(--accent)}
        .t-name{font-weight:600;font-size:13px}
        .t-sub{font-size:11px;color:var(--muted);font-family:var(--mono)}

        /* BADGE */
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600}
        .b-green{background:rgba(16,185,129,.15);color:var(--green)}
        .b-yellow{background:rgba(245,158,11,.15);color:var(--yellow)}
        .b-red{background:rgba(239,68,68,.15);color:var(--red)}
        .b-blue{background:rgba(59,130,246,.15);color:var(--accent)}
        .b-muted{background:rgba(100,116,139,.15);color:var(--muted)}

        /* RATE BAR */
        .rate-bar-bg{height:5px;background:var(--surface2);border-radius:99px;overflow:hidden;margin-top:4px;width:80px}
        .rate-bar{height:100%;border-radius:99px}

        /* EXPORT BAR */
        .export-bar{background:linear-gradient(135deg,rgba(59,130,246,.08),rgba(99,102,241,.06));border:1px solid rgba(59,130,246,.2);border-radius:var(--r);padding:14px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px}
        .export-info{font-size:13px}
        .export-info strong{color:var(--accent)}
        .export-actions{display:flex;gap:8px;flex-wrap:wrap}

        /* DONUT */
        .donut-wrap{display:flex;align-items:center;gap:24px;flex-wrap:wrap}
        .donut-chart-wrap{position:relative;width:140px;height:140px;flex-shrink:0}
        .donut-legend{display:flex;flex-direction:column;gap:8px}
        .legend-item{display:flex;align-items:center;gap:8px;font-size:13px}
        .legend-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}

        /* TOP/BOTTOM LIST */
        .rank-list{display:flex;flex-direction:column;gap:8px}
        .rank-item{display:flex;align-items:center;gap:10px;padding:8px 12px;background:var(--surface2);border-radius:var(--r-sm)}
        .rank-num{width:22px;height:22px;border-radius:6px;background:var(--surface);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:var(--muted);flex-shrink:0}
        .rank-name{flex:1;font-size:13px;font-weight:600;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .rank-rate{font-family:var(--mono);font-size:13px;font-weight:700;flex-shrink:0}

        /* OVERLAY */
        .overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99}
        .overlay.open{display:block}

        /* EMPTY */
        .empty{text-align:center;padding:48px;color:var(--muted)}
        .empty i{font-size:40px;margin-bottom:12px;display:block;opacity:.4}

        @media(max-width:1024px){.grid-2,.grid-3{grid-template-columns:1fr}}
        @media(max-width:768px){
            .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}
            .overlay.open{display:block}.main{margin-left:0}
            .hamburger{display:block}.content{padding:14px}.topbar{padding:12px 16px}
            .sum-row{grid-template-columns:repeat(2,1fr);gap:10px}
            .report-types{grid-template-columns:repeat(3,1fr)}
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
            <div><div class="logo-text">Kabodo</div><div class="logo-sub">District Portal</div></div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-label">Mwelekeo</div>
            <a href="{{ route('district.dashboard') }}" class="nav-item">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('district.attendance.index') }}"  class="nav-item"><i class="fas fa-calendar-check"></i> Mahudhurio</a>
            <a href="{{ route('district.schools.index') }}"     class="nav-item"><i class="fas fa-school"></i> Shule</a>
            <a href="{{ route('district.teachers.index') }}"    class="nav-item">
                <i class="fas fa-chalkboard-teacher"></i> Walimu
                @if($pendingTeachers > 0)<span class="nav-badge">{{ $pendingTeachers }}</span>@endif
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Usimamizi</div>
            <a href="#" class="nav-item"><i class="fas fa-map-marker-alt"></i> Kata</a>
            <a href="{{ route('district.assignments.index') }}" class="nav-item"><i class="fas fa-exchange-alt"></i> Uhamisho</a>
            <a href="{{ route('district.reports.index') }}"     class="nav-item active"><i class="fas fa-file-alt"></i> Ripoti</a>
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
                <span>/</span><strong>Ripoti</strong>
            </div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <a href="{{ route('district.reports.export.csv', request()->query()) }}" class="btn btn-csv btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('district.reports.export.pdf', request()->query()) }}" class="btn btn-pdf btn-sm">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </header>

    <div class="content">

        {{-- HEADING --}}
        <div style="margin-bottom:20px">
            <h1 style="font-size:22px;font-weight:800">Ripoti</h1>
            <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ $officer->council->name ?? 'Halmashauri' }} · Taarifa za mfumo</p>
        </div>

        {{-- REPORT TYPE CARDS --}}
        <div class="report-types">
            @php
                $types = [
                    ['attendance', '📅', 'Mahudhurio',  'Kwa tarehe/kipindi'],
                    ['teachers',   '👨‍🏫', 'Walimu',      'Idadi, jinsia, hali'],
                    ['schools',    '🏫', 'Shule',       'Mahudhurio kwa shule'],
                    ['wards',      '🗺️', 'Kata',        'Muhtasari wa kata'],
                    ['transfers',  '🔄', 'Uhamisho',    'Maombi ya uhamisho'],
                ];
            @endphp
            @foreach($types as [$key, $icon, $label, $sub])
            <a href="{{ request()->fullUrlWithQuery(['report_type' => $key]) }}"
               class="rtype-card {{ $reportType === $key ? 'active' : '' }}">
                <div class="rtype-icon">{{ $icon }}</div>
                <div class="rtype-label">{{ $label }}</div>
                <div class="rtype-sub">{{ $sub }}</div>
            </a>
            @endforeach
        </div>

        {{-- FILTER PANEL --}}
        <form method="GET" id="filterForm">
            <input type="hidden" name="report_type" value="{{ $reportType }}">
            <div class="filter-panel">
                <div class="filter-title">
                    <i class="fas fa-filter" style="color:var(--accent)"></i> Chagua Kipindi na Vichujio
                </div>
                <div class="filter-grid">
                    <div class="form-group">
                        <label class="form-label">Tarehe ya Mwanzo</label>
                        <input type="date" name="date_from" class="form-input" value="{{ $dateFrom }}" max="{{ now()->toDateString() }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tarehe ya Mwisho</label>
                        <input type="date" name="date_to" class="form-input" value="{{ $dateTo }}" max="{{ now()->toDateString() }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kata</label>
                        <select name="ward_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Zote</option>
                            @foreach($wards as $w)
                            <option value="{{ $w->id }}" {{ $wardId == $w->id ? 'selected':'' }}>{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(!in_array($reportType, ['wards','transfers']))
                    <div class="form-group">
                        <label class="form-label">Shule</label>
                        <select name="school_id" class="form-select">
                            <option value="">Zote</option>
                            @foreach($schools as $sc)
                            <option value="{{ $sc->id }}" {{ $schoolId == $sc->id ? 'selected':'' }}>{{ $sc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Vipindi vya Haraka</label>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            @php
                                $presets = [
                                    ['Leo',    now()->toDateString(), now()->toDateString()],
                                    ['Wiki',   now()->startOfWeek()->toDateString(), now()->toDateString()],
                                    ['Mwezi',  now()->startOfMonth()->toDateString(), now()->toDateString()],
                                    ['Robo',   now()->startOfQuarter()->toDateString(), now()->toDateString()],
                                    ['Mwaka',  now()->startOfYear()->toDateString(), now()->toDateString()],
                                ];
                            @endphp
                            @foreach($presets as [$lbl, $df, $dt])
                            <a href="{{ request()->fullUrlWithQuery(['date_from'=>$df,'date_to'=>$dt]) }}"
                               class="btn btn-ghost btn-sm" style="padding:4px 10px;font-size:11px">{{ $lbl }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group" style="justify-content:flex-end">
                        <button type="submit" class="btn btn-primary" style="width:100%">
                            <i class="fas fa-search"></i> Toa Ripoti
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- EXPORT BAR --}}
        <div class="export-bar">
            <div class="export-info">
                📊 Ripoti ya <strong>{{ ['attendance'=>'Mahudhurio','teachers'=>'Walimu','schools'=>'Shule','wards'=>'Kata','transfers'=>'Uhamisho'][$reportType] }}</strong>
                · Kipindi: <strong>{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</strong>
                hadi <strong>{{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</strong>
            </div>
            <div class="export-actions">
                <a href="{{ route('district.reports.export.csv', request()->query()) }}" class="btn btn-csv btn-sm">
                    <i class="fas fa-download"></i> CSV
                </a>
                <a href="{{ route('district.reports.export.pdf', request()->query()) }}" class="btn btn-pdf btn-sm">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        {{-- ════════════════ ATTENDANCE REPORT ════════════════ --}}
        @if($reportType === 'attendance' && $attendanceReport)
        @php $ar = $attendanceReport @endphp

        <div class="sum-row">
            <div class="sum-card sc-blue"><div class="sum-icon"><i class="fas fa-users"></i></div><div class="sum-val" style="color:var(--accent)">{{ $ar['total'] }}</div><div class="sum-lbl">Walimu Wote</div></div>
            <div class="sum-card sc-yellow"><div class="sum-icon"><i class="fas fa-calendar-week"></i></div><div class="sum-val" style="color:var(--yellow)">{{ $ar['working_days'] }}</div><div class="sum-lbl">Siku za Kazi</div></div>
            <div class="sum-card {{ $ar['overall_rate'] >= 80 ? 'sc-green' : ($ar['overall_rate'] >= 60 ? 'sc-yellow' : 'sc-red') }}">
                <div class="sum-icon"><i class="fas fa-percentage"></i></div>
                <div class="sum-val" style="color:{{ $ar['overall_rate'] >= 80 ? 'var(--green)' : ($ar['overall_rate'] >= 60 ? 'var(--yellow)' : 'var(--red)') }}">{{ $ar['overall_rate'] }}%</div>
                <div class="sum-lbl">Kiwango cha Ujumla</div>
            </div>
            <div class="sum-card sc-green"><div class="sum-icon"><i class="fas fa-trophy"></i></div><div class="sum-val" style="color:var(--green)">{{ $ar['top_present']->first()['rate'] ?? 0 }}%</div><div class="sum-lbl">Mwalimu Bora</div></div>
        </div>

        {{-- Trend chart + top/bottom --}}
        <div class="grid-2">
            <div class="card" style="margin-bottom:0">
                <div class="card-header"><div class="card-title">📈 Mwenendo wa Mahudhurio</div><div class="card-sub">Kila siku ya kazi</div></div>
                <div class="card-body"><div class="chart-wrap"><canvas id="trendChart"></canvas></div></div>
            </div>
            <div class="card" style="margin-bottom:0">
                <div class="card-header"><div class="card-title">🏆 Top 5 vs Chini 5</div></div>
                <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div>
                        <div style="font-size:11px;font-weight:700;color:var(--green);margin-bottom:8px;text-transform:uppercase">Bora Zaidi</div>
                        <div class="rank-list">
                            @foreach($ar['top_present'] as $i => $t)
                            <div class="rank-item">
                                <div class="rank-num">{{ $i+1 }}</div>
                                <div class="rank-name" title="{{ $t['name'] }}">{{ Str::limit($t['name'],18) }}</div>
                                <div class="rank-rate" style="color:var(--green)">{{ $t['rate'] }}%</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:var(--red);margin-bottom:8px;text-transform:uppercase">Chini Zaidi</div>
                        <div class="rank-list">
                            @foreach($ar['top_absent'] as $i => $t)
                            <div class="rank-item">
                                <div class="rank-num">{{ $i+1 }}</div>
                                <div class="rank-name" title="{{ $t['name'] }}">{{ Str::limit($t['name'],18) }}</div>
                                <div class="rank-rate" style="color:var(--red)">{{ $t['rate'] }}%</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div><div class="card-title">📋 Walimu Wote ({{ $ar['total'] }})</div><div class="card-sub">Mahudhurio kwa kipindi chote</div></div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>#</th><th>Mwalimu</th><th>Shule</th><th>Kata</th><th>Jinsia</th><th>Siku Alifika</th><th>Siku za Kazi</th><th>Kiwango</th><th>Mwenendo</th></tr></thead>
                    <tbody>
                        @foreach($ar['teachers'] as $i => $t)
                        @php $rc = $t['rate']>=80?'var(--green)':($t['rate']>=60?'var(--yellow)':'var(--red)') @endphp
                        <tr>
                            <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                            <td><div class="t-info"><div class="t-av" style="background:{{ $t['sex']==='female'?'rgba(236,72,153,.2)':'rgba(99,102,241,.2)' }};color:{{ $t['sex']==='female'?'var(--pink)':'var(--accent2)' }}">{{ strtoupper(substr($t['name'],0,1)) }}</div><div><div class="t-name">{{ $t['name'] }}</div><div class="t-sub">{{ $t['check'] }}</div></div></div></td>
                            <td style="font-size:12px">{{ Str::limit($t['school'],22) }}</td>
                            <td style="font-size:12px;color:var(--muted)">{{ $t['ward'] }}</td>
                            <td style="font-size:12px;color:{{ $t['sex']==='female'?'var(--pink)':'var(--accent2)' }}">{{ $t['sex']==='female'?'♀':'♂' }}</td>
                            <td style="font-family:var(--mono);color:var(--green)">{{ $t['days_present'] }}</td>
                            <td style="font-family:var(--mono);color:var(--muted)">{{ $t['working_days'] }}</td>
                            <td><span class="badge {{ $t['rate']>=80?'b-green':($t['rate']>=60?'b-yellow':'b-red') }}">{{ $t['rate'] }}%</span></td>
                            <td><div class="rate-bar-bg"><div class="rate-bar" style="width:{{ $t['rate'] }}%;background:{{ $rc }}"></div></div></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- ════════════════ TEACHERS REPORT ════════════════ --}}
        @if($reportType === 'teachers' && $teachersReport)
        @php $tr = $teachersReport @endphp

        <div class="sum-row">
            <div class="sum-card sc-blue"><div class="sum-icon"><i class="fas fa-users"></i></div><div class="sum-val" style="color:var(--accent)">{{ $tr['total'] }}</div><div class="sum-lbl">Jumla</div></div>
            <div class="sum-card sc-green"><div class="sum-icon"><i class="fas fa-user-check"></i></div><div class="sum-val" style="color:var(--green)">{{ $tr['approved'] }}</div><div class="sum-lbl">Walioidhinishwa</div></div>
            <div class="sum-card sc-yellow"><div class="sum-icon"><i class="fas fa-user-clock"></i></div><div class="sum-val" style="color:var(--yellow)">{{ $tr['pending'] }}</div><div class="sum-lbl">Wanaongoja</div></div>
            <div class="sum-card sc-purple"><div class="sum-icon"><i class="fas fa-mars"></i></div><div class="sum-val" style="color:var(--accent2)">{{ $tr['male'] }}</div><div class="sum-lbl">Wanaume</div></div>
            <div class="sum-card sc-orange"><div class="sum-icon"><i class="fas fa-venus"></i></div><div class="sum-val" style="color:var(--orange)">{{ $tr['female'] }}</div><div class="sum-lbl">Wanawake</div></div>
            <div class="sum-card sc-blue"><div class="sum-icon"><i class="fas fa-chalkboard-teacher"></i></div><div class="sum-val" style="color:var(--accent)">{{ $tr['head_teachers'] }}</div><div class="sum-lbl">Walimu Wakuu</div></div>
        </div>

        <div class="grid-2">
            <div class="card" style="margin-bottom:0">
                <div class="card-header"><div class="card-title">📊 Mgawanyo wa Jinsia</div></div>
                <div class="card-body">
                    <div class="donut-wrap">
                        <div class="donut-chart-wrap"><canvas id="genderChart"></canvas></div>
                        <div class="donut-legend">
                            <div class="legend-item"><div class="legend-dot" style="background:var(--accent2)"></div><span>Wanaume: <strong>{{ $tr['male'] }}</strong></span></div>
                            <div class="legend-item"><div class="legend-dot" style="background:var(--pink)"></div><span>Wanawake: <strong>{{ $tr['female'] }}</strong></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" style="margin-bottom:0">
                <div class="card-header"><div class="card-title">🏫 Walimu kwa Shule</div></div>
                <div class="card-body" style="max-height:220px;overflow-y:auto">
                    @foreach($tr['by_school'] as $school => $count)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:6px 0;border-bottom:1px solid rgba(42,47,69,.4);font-size:13px">
                        <span style="color:var(--muted)">{{ Str::limit($school,28) }}</span>
                        <span style="font-weight:700;font-family:var(--mono);color:var(--accent)">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title">📋 Orodha ya Walimu ({{ $tr['total'] }})</div></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>#</th><th>Jina</th><th>Namba</th><th>Jinsia</th><th>Shule</th><th>Kata</th><th>Hali</th><th>Nafasi</th><th>Alijiunga</th></tr></thead>
                    <tbody>
                        @foreach($tr['teachers'] as $i => $t)
                        <tr>
                            <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                            <td><div class="t-info"><div class="t-av" style="background:{{ $t->sex==='female'?'rgba(236,72,153,.2)':'rgba(99,102,241,.2)' }};color:{{ $t->sex==='female'?'var(--pink)':'var(--accent2)' }}">{{ strtoupper(substr($t->first_name,0,1)) }}</div><div><div class="t-name">{{ $t->full_name }}</div><div class="t-sub">{{ $t->email }}</div></div></div></td>
                            <td style="font-family:var(--mono);font-size:12px">{{ $t->check_number }}</td>
                            <td style="font-size:12px;color:{{ $t->sex==='female'?'var(--pink)':'var(--accent2)' }}">{{ $t->sex==='female'?'♀ Mke':'♂ Mme' }}</td>
                            <td style="font-size:12px">{{ $t->school->name ?? '—' }}</td>
                            <td style="font-size:12px;color:var(--muted)">{{ $t->school->ward->name ?? '—' }}</td>
                            <td><span class="badge {{ $t->status==='approved'?'b-green':($t->status==='pending'?'b-yellow':'b-red') }}">{{ $t->status }}</span></td>
                            <td><span class="badge {{ $t->role==='head_teacher'?'b-blue':'b-muted' }}">{{ $t->role==='head_teacher'?'HT':'Mwalimu' }}</span></td>
                            <td style="font-size:11px;color:var(--muted);font-family:var(--mono)">{{ $t->created_at?$t->created_at->format('d/m/Y'):'—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- ════════════════ SCHOOLS REPORT ════════════════ --}}
        @if($reportType === 'schools' && $schoolsReport)
        @php $sr = $schoolsReport @endphp

        <div class="sum-row">
            <div class="sum-card sc-blue"><div class="sum-icon"><i class="fas fa-school"></i></div><div class="sum-val" style="color:var(--accent)">{{ $sr['total'] }}</div><div class="sum-lbl">Shule Zote</div></div>
            <div class="sum-card sc-green"><div class="sum-icon"><i class="fas fa-check-circle"></i></div><div class="sum-val" style="color:var(--green)">{{ $sr['active'] }}</div><div class="sum-lbl">Zinafanya Kazi</div></div>
            <div class="sum-card sc-yellow"><div class="sum-icon"><i class="fas fa-map-marker-alt"></i></div><div class="sum-val" style="color:var(--yellow)">{{ $sr['with_gps'] }}</div><div class="sum-lbl">Zina GPS</div></div>
            <div class="sum-card {{ $sr['avg_rate']>=80?'sc-green':($sr['avg_rate']>=60?'sc-yellow':'sc-red') }}">
                <div class="sum-icon"><i class="fas fa-percentage"></i></div>
                <div class="sum-val" style="color:{{ $sr['avg_rate']>=80?'var(--green)':($sr['avg_rate']>=60?'var(--yellow)':'var(--red)') }}">{{ $sr['avg_rate'] }}%</div>
                <div class="sum-lbl">Wastani Mahudhurio</div>
            </div>
        </div>

        <div class="grid-2">
            <div class="card" style="margin-bottom:0">
                <div class="card-header"><div class="card-title">🥇 Shule Bora 5</div></div>
                <div class="card-body">
                    <div class="rank-list">
                        @foreach($sr['top_schools'] as $i => $s)
                        <div class="rank-item">
                            <div class="rank-num" style="{{ $i===0?'background:rgba(245,158,11,.2);color:var(--yellow)':'' }}">{{ $i+1 }}</div>
                            <div class="rank-name">{{ Str::limit($s['name'],24) }}</div>
                            <span style="font-size:11px;color:var(--muted);margin-right:8px">{{ $s['ward'] }}</span>
                            <div class="rank-rate" style="color:var(--green)">{{ $s['avg_rate'] }}%</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card" style="margin-bottom:0">
                <div class="card-header"><div class="card-title">⚠️ Shule Zinahitaji Msaada</div></div>
                <div class="card-body">
                    <div class="rank-list">
                        @foreach($sr['low_schools'] as $i => $s)
                        <div class="rank-item">
                            <div class="rank-num">{{ $i+1 }}</div>
                            <div class="rank-name">{{ Str::limit($s['name'],24) }}</div>
                            <span style="font-size:11px;color:var(--muted);margin-right:8px">{{ $s['ward'] }}</span>
                            <div class="rank-rate" style="color:var(--red)">{{ $s['avg_rate'] }}%</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title">📋 Shule Zote ({{ $sr['total'] }})</div></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>#</th><th>Shule</th><th>Kata</th><th>Walimu</th><th>Siku za Data</th><th>Wastani/siku</th><th>Kiwango %</th><th>GPS</th><th>Hali</th></tr></thead>
                    <tbody>
                        @foreach($sr['schools'] as $i => $s)
                        @php $sc = $s['avg_rate']>=80?'var(--green)':($s['avg_rate']>=60?'var(--yellow)':'var(--red)') @endphp
                        <tr>
                            <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                            <td style="font-weight:600">{{ $s['name'] }}</td>
                            <td style="font-size:12px;color:var(--muted)">{{ $s['ward'] }}</td>
                            <td style="font-family:var(--mono)">{{ $s['teacher_count'] }}</td>
                            <td style="font-family:var(--mono)">{{ $s['days_with_data'] }}</td>
                            <td style="font-family:var(--mono)">{{ $s['avg_attendance'] }}</td>
                            <td><span class="badge {{ $s['avg_rate']>=80?'b-green':($s['avg_rate']>=60?'b-yellow':'b-red') }}">{{ $s['avg_rate'] }}%</span></td>
                            <td>{{ $s['has_gps'] ? '📍' : '—' }}</td>
                            <td><span class="badge {{ $s['is_active']?'b-green':'b-muted' }}">{{ $s['is_active']?'Active':'Imezimwa' }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- ════════════════ WARDS REPORT ════════════════ --}}
        @if($reportType === 'wards' && $wardsReport)
        @php $wr = $wardsReport @endphp

        <div class="sum-row">
            <div class="sum-card sc-blue"><div class="sum-icon"><i class="fas fa-map"></i></div><div class="sum-val" style="color:var(--accent)">{{ $wr['total'] }}</div><div class="sum-lbl">Kata Zote</div></div>
            <div class="sum-card sc-green"><div class="sum-icon"><i class="fas fa-user-tie"></i></div><div class="sum-val" style="color:var(--green)">{{ $wr['with_officer'] }}</div><div class="sum-lbl">Zina WO</div></div>
            <div class="sum-card sc-red"><div class="sum-icon"><i class="fas fa-user-slash"></i></div><div class="sum-val" style="color:var(--red)">{{ $wr['without_officer'] }}</div><div class="sum-lbl">Hazina WO</div></div>
            <div class="sum-card {{ $wr['avg_rate']>=80?'sc-green':($wr['avg_rate']>=60?'sc-yellow':'sc-red') }}">
                <div class="sum-icon"><i class="fas fa-percentage"></i></div>
                <div class="sum-val" style="color:{{ $wr['avg_rate']>=80?'var(--green)':($wr['avg_rate']>=60?'var(--yellow)':'var(--red)') }}">{{ $wr['avg_rate'] }}%</div>
                <div class="sum-lbl">Wastani Mahudhurio</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title">📋 Kata Zote ({{ $wr['total'] }})</div></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>#</th><th>Kata</th><th>Shule</th><th>Walimu</th><th>Ward Officer</th><th>Kiwango %</th><th>Mwenendo</th></tr></thead>
                    <tbody>
                        @foreach($wr['wards'] as $i => $w)
                        @php $wc = $w['avg_rate']>=80?'var(--green)':($w['avg_rate']>=60?'var(--yellow)':'var(--red)') @endphp
                        <tr>
                            <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                            <td style="font-weight:700">{{ $w['name'] }}</td>
                            <td style="font-family:var(--mono)">{{ $w['school_count'] }}</td>
                            <td style="font-family:var(--mono)">{{ $w['teacher_count'] }}</td>
                            <td>
                                @if($w['has_officer'])
                                <span class="badge b-blue"><i class="fas fa-user-tie" style="font-size:9px"></i> {{ $w['ward_officer'] }}</span>
                                @else
                                <span class="badge b-red"><i class="fas fa-user-slash" style="font-size:9px"></i> Hana</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $w['avg_rate']>=80?'b-green':($w['avg_rate']>=60?'b-yellow':'b-red') }}">{{ $w['avg_rate'] }}%</span></td>
                            <td><div class="rate-bar-bg" style="width:100px"><div class="rate-bar" style="width:{{ $w['avg_rate'] }}%;background:{{ $wc }}"></div></div></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- ════════════════ TRANSFERS REPORT ════════════════ --}}
        @if($reportType === 'transfers' && $transfersReport)
        @php $tfr = $transfersReport @endphp

        <div class="sum-row">
            <div class="sum-card sc-blue"><div class="sum-icon"><i class="fas fa-exchange-alt"></i></div><div class="sum-val" style="color:var(--accent)">{{ $tfr['total'] }}</div><div class="sum-lbl">Maombi Yote</div></div>
            <div class="sum-card sc-green"><div class="sum-icon"><i class="fas fa-check-circle"></i></div><div class="sum-val" style="color:var(--green)">{{ $tfr['approved'] }}</div><div class="sum-lbl">Yaliidhinishwa</div></div>
            <div class="sum-card sc-red"><div class="sum-icon"><i class="fas fa-times-circle"></i></div><div class="sum-val" style="color:var(--red)">{{ $tfr['rejected'] }}</div><div class="sum-lbl">Yalikataliwa</div></div>
            <div class="sum-card sc-yellow"><div class="sum-icon"><i class="fas fa-clock"></i></div><div class="sum-val" style="color:var(--yellow)">{{ $tfr['pending'] }}</div><div class="sum-lbl">Yanasubiri</div></div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title">📋 Maombi ya Uhamisho ({{ $tfr['total'] }})</div></div>
            @if($tfr['transfers']->isEmpty())
            <div class="empty"><i class="fas fa-exchange-alt"></i><p>Hakuna uhamisho katika kipindi hiki</p></div>
            @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>#</th><th>Mwalimu</th><th>Kutoka</th><th>Kwenda</th><th>Hali</th><th>Omliwasilisha</th><th>Sababu</th><th>Tarehe</th></tr></thead>
                    <tbody>
                        @foreach($tfr['transfers'] as $i => $t)
                        <tr>
                            <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                            <td><div class="t-info"><div class="t-av">{{ strtoupper(substr($t->user->first_name??'U',0,1)) }}</div><div><div class="t-name">{{ $t->user->full_name??'—' }}</div><div class="t-sub">{{ $t->user->check_number??'—' }}</div></div></div></td>
                            <td style="font-size:12px;color:var(--muted)">{{ $t->fromSchool->name??'—' }}</td>
                            <td style="font-size:12px;font-weight:600;color:var(--accent)">{{ $t->toSchool->name??'—' }}</td>
                            <td><span class="badge {{ $t->status==='approved'?'b-green':($t->status==='pending'?'b-yellow':'b-red') }}">{{ $t->status }}</span></td>
                            <td style="font-size:12px;color:var(--muted)">{{ $t->requester->full_name??'—' }}</td>
                            <td style="font-size:11px;color:var(--muted);max-width:150px">{{ Str::limit($t->reason??'—',40) }}</td>
                            <td style="font-size:11px;color:var(--muted);font-family:var(--mono)">{{ $t->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endif

    </div>
</div>

<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('overlay').classList.toggle('open')}
function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('overlay').classList.remove('open')}

@if($reportType === 'attendance' && $attendanceReport)
const trendData = @json($attendanceReport['daily_trend']);
if(trendData.length > 0 && document.getElementById('trendChart')) {
    new Chart(document.getElementById('trendChart').getContext('2d'),{
        type:'line',
        data:{
            labels:trendData.map(d=>d.date),
            datasets:[{label:'Kiwango %',data:trendData.map(d=>d.rate),borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,.08)',fill:true,tension:.4,pointRadius:trendData.length>30?0:3,borderWidth:2},
            {label:'Waliofika',data:trendData.map(d=>d.count),borderColor:'#10b981',backgroundColor:'transparent',tension:.4,pointRadius:trendData.length>30?0:3,yAxisID:'y2',borderWidth:2}]
        },
        options:{responsive:true,maintainAspectRatio:false,interaction:{mode:'index',intersect:false},
            plugins:{legend:{labels:{color:'#94a3b8',font:{size:11},boxWidth:12}},tooltip:{backgroundColor:'#1e2335',borderColor:'#2a2f45',borderWidth:1,titleColor:'#e2e8f0',bodyColor:'#94a3b8'}},
            scales:{x:{ticks:{color:'#64748b',font:{size:10},maxTicksLimit:15},grid:{color:'rgba(42,47,69,.4)'}},y:{ticks:{color:'#64748b',font:{size:10},callback:v=>v+'%'},grid:{color:'rgba(42,47,69,.4)'},max:100,min:0},y2:{position:'right',ticks:{color:'#10b981',font:{size:10}},grid:{display:false}}}}
    });
}
@endif

@if($reportType === 'teachers' && $teachersReport)
if(document.getElementById('genderChart')) {
    new Chart(document.getElementById('genderChart').getContext('2d'),{
        type:'doughnut',
        data:{labels:['Wanaume','Wanawake'],datasets:[{data:[{{ $teachersReport['male'] }},{{ $teachersReport['female'] }}],backgroundColor:['rgba(99,102,241,.7)','rgba(236,72,153,.7)'],borderColor:['#6366f1','#ec4899'],borderWidth:2,hoverOffset:6}]},
        options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'#1e2335',borderColor:'#2a2f45',borderWidth:1,titleColor:'#e2e8f0',bodyColor:'#94a3b8'}},cutout:'65%'}
    });
}
@endif
</script>
</body>
</html>