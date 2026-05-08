
{{-- resources/views/dashboards/district.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>District Officer Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root{--bg:#0f1117;--surface:#181c27;--surface2:#1e2335;--border:#2a2f45;--accent:#3b82f6;--accent2:#6366f1;--green:#10b981;--yellow:#f59e0b;--red:#ef4444;--text:#e2e8f0;--muted:#64748b;--font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;--radius:14px;--radius-sm:8px;--shadow:0 4px 24px rgba(0,0,0,0.4)}
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:var(--font);background:var(--bg);color:var(--text);min-height:100vh;overflow-x:hidden}
        .sidebar{position:fixed;left:0;top:0;bottom:0;width:240px;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;z-index:100;transition:transform .3s ease}
        .sidebar-logo{padding:24px 20px 20px;border-bottom:1px solid var(--border)}
        .logo-badge{display:flex;align-items:center;gap:10px}
        .logo-icon{width:38px;height:38px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px}
        .logo-text{font-size:14px;font-weight:700;line-height:1.2}.logo-sub{font-size:11px;color:var(--muted)}
        .sidebar-nav{flex:1;padding:16px 12px;overflow-y:auto}
        .nav-section{margin-bottom:24px}
        .nav-label{font-size:10px;font-weight:600;color:var(--muted);letter-spacing:1.2px;text-transform:uppercase;padding:0 8px;margin-bottom:8px}
        .nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:var(--radius-sm);font-size:13.5px;font-weight:500;color:var(--muted);text-decoration:none;transition:all .2s;margin-bottom:2px}
        .nav-item:hover{background:var(--surface2);color:var(--text)}
        .nav-item.active{background:rgba(59,130,246,.15);color:var(--accent)}
        .nav-item i{width:18px;text-align:center;font-size:14px}
        .nav-badge{margin-left:auto;background:var(--red);color:#fff;font-size:10px;font-weight:700;padding:2px 6px;border-radius:20px}
        .sidebar-footer{padding:16px 12px;border-top:1px solid var(--border)}
        .user-card{display:flex;align-items:center;gap:10px;padding:10px;background:var(--surface2);border-radius:var(--radius-sm)}
        .user-avatar{width:34px;height:34px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0}
        .user-name{font-size:13px;font-weight:600}.user-role{font-size:11px;color:var(--muted)}
        .main{margin-left:240px;min-height:100vh;display:flex;flex-direction:column}
        .topbar{position:sticky;top:0;z-index:50;background:rgba(15,17,23,.9);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);padding:14px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px}
        .topbar-left{display:flex;align-items:center;gap:16px}
        .hamburger{display:none;background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:4px}
        .page-title{font-size:18px;font-weight:700}.page-sub{font-size:12px;color:var(--muted);margin-top:1px}
        .topbar-right{display:flex;align-items:center;gap:12px}
        .filter-form{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
        .form-select,.form-input{background:var(--surface);border:1px solid var(--border);color:var(--text);border-radius:var(--radius-sm);padding:7px 12px;font-size:13px;font-family:var(--font);outline:none;cursor:pointer}
        .form-select:focus,.form-input:focus{border-color:var(--accent)}
        .btn{padding:7px 16px;border-radius:var(--radius-sm);font-size:13px;font-weight:600;border:none;cursor:pointer;font-family:var(--font);transition:all .2s;display:flex;align-items:center;gap:6px}
        .btn-primary{background:var(--accent);color:#fff}.btn-primary:hover{background:#2563eb}
        .btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}.btn-ghost:hover{background:var(--border)}
        .content{padding:28px;flex:1}
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:28px}
        .stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:22px;position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s}
        .stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow)}
        .stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
        .stat-card.blue::before{background:var(--accent)}.stat-card.green::before{background:var(--green)}
        .stat-card.yellow::before{background:var(--yellow)}.stat-card.purple::before{background:var(--accent2)}
        .stat-card.red::before{background:var(--red)}
        .stat-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:14px}
        .stat-card.blue .stat-icon{background:rgba(59,130,246,.15);color:var(--accent)}
        .stat-card.green .stat-icon{background:rgba(16,185,129,.15);color:var(--green)}
        .stat-card.yellow .stat-icon{background:rgba(245,158,11,.15);color:var(--yellow)}
        .stat-card.purple .stat-icon{background:rgba(99,102,241,.15);color:var(--accent2)}
        .stat-card.red .stat-icon{background:rgba(239,68,68,.15);color:var(--red)}
        .stat-value{font-size:32px;font-weight:700;line-height:1;font-family:var(--mono)}
        .stat-label{font-size:12px;color:var(--muted);margin-top:6px;font-weight:500}
        .stat-sub{font-size:11px;color:var(--muted);margin-top:8px}
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
        .grid-3{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden}
        .card-header{padding:18px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .card-title{font-size:14px;font-weight:700}.card-sub{font-size:12px;color:var(--muted);margin-top:2px}
        .card-body{padding:20px}
        .chart-wrap{position:relative;height:220px}
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:13px}
        thead th{padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;background:var(--surface2);border-bottom:1px solid var(--border)}
        tbody td{padding:12px 16px;border-bottom:1px solid rgba(42,47,69,.6)}
        tbody tr:last-child td{border-bottom:none}
        tbody tr:hover td{background:rgba(30,35,53,.5)}
        .rate-pill{display:inline-flex;align-items:center;gap:6px;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;font-family:var(--mono)}
        .rate-high{background:rgba(16,185,129,.15);color:var(--green)}
        .rate-mid{background:rgba(245,158,11,.15);color:var(--yellow)}
        .rate-low{background:rgba(239,68,68,.15);color:var(--red)}
        .rate-none{background:rgba(100,116,139,.15);color:var(--muted)}
        .prog-bar-bg{height:6px;background:var(--surface2);border-radius:99px;overflow:hidden;margin-top:4px}
        .prog-bar{height:100%;border-radius:99px;transition:width .8s ease}
        .ward-item{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid rgba(42,47,69,.5)}
        .ward-item:last-child{border-bottom:none}
        .ward-rank{width:24px;height:24px;border-radius:6px;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:var(--muted);flex-shrink:0}
        .ward-info{flex:1;min-width:0}
        .ward-name{font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .ward-meta{font-size:11px;color:var(--muted)}
        .ward-rate{font-family:var(--mono);font-size:13px;font-weight:700;flex-shrink:0}
        .date-badge{background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:4px 10px;font-size:12px;color:var(--muted);display:flex;align-items:center;gap:6px}
        .alert{padding:12px 16px;border-radius:var(--radius-sm);font-size:13px;display:flex;align-items:center;gap:10px;margin-bottom:20px}
        .alert-warning{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:var(--yellow)}
        .alert a{color:var(--yellow);font-weight:600;text-decoration:underline}
        .empty{text-align:center;padding:48px 20px;color:var(--muted)}
        .empty i{font-size:40px;margin-bottom:12px;display:block}
        .empty p{font-size:14px}
        .overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99}
        .mobile-filter{display:none}
        @media(max-width:1024px){.grid-2,.grid-3{grid-template-columns:1fr}}
        @media(max-width:768px){
            .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}
            .overlay.open{display:block}.main{margin-left:0}.hamburger{display:block}
            .content{padding:16px}.topbar{padding:12px 16px}
            .stats-grid{grid-template-columns:repeat(2,1fr);gap:12px}
        }
        @media(max-width:480px){
            .stats-grid{grid-template-columns:1fr}
            .topbar-right .filter-form{display:none}
            .mobile-filter{display:block!important;margin-bottom:16px}
        }
    </style>
</head>
<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

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
            <a href="{{ route('district.dashboard') }}" class="nav-item active">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('district.attendance.index') }}" class="nav-item">
                <i class="fas fa-calendar-check"></i> Mahudhurio
            </a>
            <a href="{{ route('district.schools.index') }}" class="nav-item">
                <i class="fas fa-school"></i> Shule
            </a>
            <a href="{{ route('district.teachers.index') }}" class="nav-item">
                <i class="fas fa-chalkboard-teacher"></i> Walimu
                @if(($pendingTeachers ?? 0) > 0)
                    <span class="nav-badge">{{ $pendingTeachers }}</span>
                @endif
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Usimamizi</div>
            <a href="{{ route('district.assignments.index') }}" class="nav-item">
                <i class="fas fa-exchange-alt"></i> Uhamisho
                @if(($pendingTeachers ?? 0) > 0)
                    <span class="nav-badge">{{ $pendingTeachers }}</span>
                @endif
            </a>
            <a href="{{ route('district.reports.index') }}" class="nav-item">
                <i class="fas fa-file-alt"></i> Ripoti
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                <div class="user-role">District Officer</div>
            </div>
        </div>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div>
                <div class="page-title">Dashboard</div>
                <div class="page-sub">Halmashauri · Mahudhurio ya Walimu</div>
            </div>
        </div>
        <div class="topbar-right">
            <form method="GET" class="filter-form" id="mainFilter">
                <select name="ward_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Kata Zote</option>
                    @foreach($wards as $w)
                        <option value="{{ $w->id }}" {{ $selectedWardId == $w->id ? 'selected' : '' }}>
                            {{ $w->name }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="date" class="form-input"
                       value="{{ $selectedDate }}" max="{{ now()->toDateString() }}"
                       onchange="this.form.submit()">
            </form>
            <form method="POST" action="{{ route('logout') }}" style="margin-left:8px">
                @csrf
                <button type="submit" class="btn btn-ghost">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </header>

    <div class="content">

        {{-- MOBILE FILTER --}}
        <form method="GET" class="filter-form mobile-filter">
            <select name="ward_id" class="form-select" onchange="this.form.submit()" style="flex:1">
                <option value="">Kata Zote</option>
                @foreach($wards as $w)
                    <option value="{{ $w->id }}" {{ $selectedWardId == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date" class="form-input" value="{{ $selectedDate }}"
                   max="{{ now()->toDateString() }}" onchange="this.form.submit()">
        </form>

        {{-- PENDING ALERT --}}
        @if(($pendingTeachers ?? 0) > 0)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Kuna <strong>{{ $pendingTeachers }}</strong> walimu wanaongoja idhini.
                <a href="{{ route('district.teachers.index') }}">Kagua sasa →</a>
            </span>
        </div>
        @endif

        {{-- STATS CARDS --}}
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-school"></i></div>
                <div class="stat-value">{{ $totalSchools }}</div>
                <div class="stat-label">Jumla ya Shule</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="stat-value">{{ $totalWards }}</div>
                <div class="stat-label">Jumla ya Kata</div>
            </div>
            <div class="stat-card yellow">
                <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-value">{{ $totalTeachers }}</div>
                <div class="stat-label">Jumla ya Walimu</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                <div class="stat-value">{{ $totalAttendedToday }}</div>
                <div class="stat-label">Walifika Leo</div>
                <div class="stat-sub">{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</div>
            </div>
            <div class="stat-card {{ $overallRate >= 80 ? 'green' : ($overallRate >= 60 ? 'yellow' : 'red') }}">
                <div class="stat-icon"><i class="fas fa-percentage"></i></div>
                <div class="stat-value">{{ $overallRate }}%</div>
                <div class="stat-label">Kiwango cha Mahudhurio</div>
                <div class="stat-sub">{{ $overallRate >= 80 ? '✅ Vizuri' : ($overallRate >= 60 ? '⚠️ Wastani' : '❌ Chini') }}</div>
            </div>
        </div>

        {{-- TREND + WARD SUMMARY --}}
        <div class="grid-3">
            <div class="card">
                <div class="card-header">
                    <div><div class="card-title">📈 Mwenendo wa Mahudhurio</div><div class="card-sub">Siku 7 zilizopita</div></div>
                </div>
                <div class="card-body">
                    <div class="chart-wrap"><canvas id="trendChart"></canvas></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div><div class="card-title">🏆 Kata Bora</div></div>
                </div>
                <div class="card-body" style="padding:16px 20px">
                    @forelse($wardSummary->take(6) as $i => $ward)
                    <div class="ward-item">
                        <div class="ward-rank">{{ $i + 1 }}</div>
                        <div class="ward-info">
                            <div class="ward-name">{{ $ward['name'] }}</div>
                            <div class="ward-meta">{{ $ward['attended'] }}/{{ $ward['teachers'] }} walimu</div>
                            <div class="prog-bar-bg">
                                <div class="prog-bar" style="width:{{ $ward['rate'] }}%;background:{{ $ward['rate'] >= 80 ? 'var(--green)' : ($ward['rate'] >= 60 ? 'var(--yellow)' : 'var(--red)') }}"></div>
                            </div>
                        </div>
                        <div class="ward-rate" style="color:{{ $ward['rate'] >= 80 ? 'var(--green)' : ($ward['rate'] >= 60 ? 'var(--yellow)' : 'var(--red)') }}">{{ $ward['rate'] }}%</div>
                    </div>
                    @empty
                    <div class="empty"><i class="fas fa-map"></i><p>Hakuna data ya kata</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- TOP vs BOTTOM SCHOOLS --}}
        <div class="grid-2">
            <div class="card">
                <div class="card-header"><div><div class="card-title">🥇 Shule Bora Zaidi</div></div></div>
                <div class="card-body" style="padding:0">
                    @if($topSchools->isEmpty())
                        <div class="empty"><i class="fas fa-school"></i><p>Hakuna data</p></div>
                    @else
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>#</th><th>Shule</th><th>Kata</th><th>Waliofika</th><th>Kiwango</th></tr></thead>
                            <tbody>
                                @foreach($topSchools as $i => $s)
                                <tr>
                                    <td style="color:var(--muted);font-family:var(--mono)">{{ $i+1 }}</td>
                                    <td style="font-weight:600">{{ $s['name'] }}</td>
                                    <td style="color:var(--muted)">{{ $s['ward'] }}</td>
                                    <td>{{ $s['attended'] }}/{{ $s['teacher_count'] }}</td>
                                    <td><span class="rate-pill {{ $s['rate'] >= 80 ? 'rate-high' : ($s['rate'] >= 60 ? 'rate-mid' : ($s['rate'] > 0 ? 'rate-low' : 'rate-none')) }}">{{ $s['rate'] }}%</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-header"><div><div class="card-title">⚠️ Zinahitaji Msaada</div></div></div>
                <div class="card-body" style="padding:0">
                    @if($bottomSchools->isEmpty())
                        <div class="empty"><i class="fas fa-school"></i><p>Hakuna data</p></div>
                    @else
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>Shule</th><th>Kata</th><th>Hawakuja</th><th>Kiwango</th></tr></thead>
                            <tbody>
                                @foreach($bottomSchools as $s)
                                <tr>
                                    <td style="font-weight:600">{{ $s['name'] }}</td>
                                    <td style="color:var(--muted)">{{ $s['ward'] }}</td>
                                    <td style="color:var(--red)">{{ $s['absent'] }}</td>
                                    <td><span class="rate-pill {{ $s['rate'] >= 80 ? 'rate-high' : ($s['rate'] >= 60 ? 'rate-mid' : ($s['rate'] > 0 ? 'rate-low' : 'rate-none')) }}">{{ $s['rate'] }}%</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ALL SCHOOLS TABLE --}}
        <div class="card" style="margin-top:4px">
            <div class="card-header">
                <div>
                    <div class="card-title">🏫 Mahudhurio ya Shule Zote</div>
                    <div class="card-sub">Tarehe {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</div>
                </div>
                <button class="btn btn-ghost" onclick="exportTable()"><i class="fas fa-download"></i> Export</button>
            </div>
            <div class="card-body" style="padding:0">
                @if($schoolAttendance->isEmpty())
                    <div class="empty"><i class="fas fa-school"></i><p>Hakuna shule zilizopatikana</p></div>
                @else
                <div class="table-wrap">
                    <table id="schoolTable">
                        <thead><tr><th>#</th><th>Shule</th><th>Kata</th><th>Walimu</th><th>Waliofika</th><th>Hawakuja</th><th>Kiwango</th><th>Mwenendo</th></tr></thead>
                        <tbody>
                            @foreach($schoolAttendance as $i => $s)
                            <tr>
                                <td style="color:var(--muted);font-family:var(--mono);font-size:12px">{{ $i+1 }}</td>
                                <td style="font-weight:600">{{ $s['name'] }}</td>
                                <td style="color:var(--muted);font-size:12px">{{ $s['ward'] }}</td>
                                <td style="font-family:var(--mono)">{{ $s['teacher_count'] }}</td>
                                <td style="font-family:var(--mono);color:var(--green)">{{ $s['attended'] }}</td>
                                <td style="font-family:var(--mono);color:{{ $s['absent'] > 0 ? 'var(--red)' : 'var(--muted)' }}">{{ $s['absent'] }}</td>
                                <td><span class="rate-pill {{ $s['rate'] >= 80 ? 'rate-high' : ($s['rate'] >= 60 ? 'rate-mid' : ($s['rate'] > 0 ? 'rate-low' : 'rate-none')) }}">{{ $s['rate'] }}%</span></td>
                                <td style="min-width:80px">
                                    <div class="prog-bar-bg">
                                        <div class="prog-bar" style="width:{{ $s['rate'] }}%;background:{{ $s['rate'] >= 80 ? 'var(--green)' : ($s['rate'] >= 60 ? 'var(--yellow)' : 'var(--red)') }}"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('overlay').classList.toggle('open')}
function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('overlay').classList.remove('open')}

const trendData = @json($trend);
new Chart(document.getElementById('trendChart').getContext('2d'),{
    type:'line',
    data:{
        labels:trendData.map(d=>d.date),
        datasets:[{
            label:'Kiwango (%)',data:trendData.map(d=>d.rate),
            borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,0.08)',
            fill:true,tension:.4,pointBackgroundColor:'#3b82f6',pointRadius:4,pointHoverRadius:6
        },{
            label:'Walimu Waliofika',data:trendData.map(d=>d.attended),
            borderColor:'#10b981',backgroundColor:'transparent',
            tension:.4,pointBackgroundColor:'#10b981',pointRadius:3,pointHoverRadius:5,yAxisID:'y2'
        }]
    },
    options:{
        responsive:true,maintainAspectRatio:false,
        interaction:{mode:'index',intersect:false},
        plugins:{legend:{labels:{color:'#94a3b8',font:{size:11},boxWidth:12}},tooltip:{backgroundColor:'#1e2335',borderColor:'#2a2f45',borderWidth:1,titleColor:'#e2e8f0',bodyColor:'#94a3b8'}},
        scales:{
            x:{ticks:{color:'#64748b',font:{size:10}},grid:{color:'rgba(42,47,69,0.5)'}},
            y:{ticks:{color:'#64748b',font:{size:10},callback:v=>v+'%'},grid:{color:'rgba(42,47,69,0.5)'},max:100,min:0},
            y2:{position:'right',ticks:{color:'#10b981',font:{size:10}},grid:{display:false}}
        }
    }
});

function exportTable(){
    const rows=[];const table=document.getElementById('schoolTable');if(!table)return;
    for(const row of table.rows){const cols=[];for(const cell of row.cells){cols.push('"'+cell.innerText.replace(/\n/g,' ').trim()+'"');}rows.push(cols.join(','));}
    const blob=new Blob([rows.join('\n')],{type:'text/csv;charset=utf-8;'});
    const link=document.createElement('a');link.href=URL.createObjectURL(blob);
    link.download='mahudhurio_{{ $selectedDate }}.csv';link.click();
}
</script>
</body>
</html>