{{-- resources/views/district/wards/index.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kata · District Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root{--bg:#0f1117;--surface:#181c27;--surface2:#1e2335;--border:#2a2f45;--accent:#3b82f6;--accent2:#6366f1;--green:#10b981;--yellow:#f59e0b;--red:#ef4444;--text:#e2e8f0;--muted:#64748b;--font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;--r:14px;--r-sm:8px}
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:var(--font);background:var(--bg);color:var(--text);min-height:100vh}
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
        .main{margin-left:240px;min-height:100vh}
        .topbar{position:sticky;top:0;z-index:50;background:rgba(15,17,23,.92);backdrop-filter:blur(14px);border-bottom:1px solid var(--border);padding:14px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px}
        .topbar-left{display:flex;align-items:center;gap:14px}
        .hamburger{display:none;background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:4px}
        .breadcrumb{display:flex;align-items:center;gap:8px;font-size:13px}
        .breadcrumb a{color:var(--muted);text-decoration:none}.breadcrumb a:hover{color:var(--text)}.breadcrumb span{color:var(--muted)}.breadcrumb strong{color:var(--text);font-weight:600}
        .btn{padding:7px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:600;border:none;cursor:pointer;font-family:var(--font);transition:all .2s;display:inline-flex;align-items:center;gap:6px;text-decoration:none}
        .btn-primary{background:var(--accent);color:#fff}.btn-primary:hover{background:#2563eb}
        .btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}.btn-ghost:hover{background:var(--border)}
        .content{padding:24px 28px}
        .stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:24px}
        .stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:18px}
        .stat-title{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:6px}
        .stat-value{font-size:28px;font-weight:800;font-family:var(--mono)}
        .stat-tag{font-size:12px;color:var(--muted);margin-top:4px}
        .filter-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px 18px;margin-bottom:20px;display:flex;flex-wrap:wrap;gap:12px}
        .filter-group{display:flex;flex-direction:column;gap:6px;min-width:160px;flex:1}
        .filter-label{font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.7px}
        .form-input,.form-select{background:var(--surface2);border:1px solid var(--border);color:var(--text);border-radius:var(--r-sm);padding:10px 12px;font-size:13px;font-family:var(--font);outline:none;width:100%}
        .form-input:focus,.form-select:focus{border-color:var(--accent)}
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:13px}
        thead th{padding:12px 14px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;border-bottom:1px solid var(--border);background:var(--surface2)}
        tbody td{padding:12px 14px;border-bottom:1px solid rgba(42,47,69,.5)}
        tbody tr:hover td{background:rgba(30,35,53,.5)}
        .badge{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:700}
        .badge-primary{background:rgba(59,130,246,.12);color:var(--accent)}
        .badge-success{background:rgba(16,185,129,.12);color:var(--green)}
        .badge-warning{background:rgba(245,158,11,.12);color:var(--yellow)}
        .badge-muted{background:rgba(100,116,139,.12);color:var(--muted)}
        .badge-red{background:rgba(239,68,68,.12);color:var(--red)}
        .overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99}
        .overlay.open{display:block}
        @media(max-width:768px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.hamburger{display:block}.content{padding:16px}}
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
            <a href="{{ route('district.dashboard') }}" class="nav-item"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <a href="{{ route('district.attendance.index') }}" class="nav-item"><i class="fas fa-calendar-check"></i> Mahudhurio</a>
            <a href="{{ route('district.schools.index') }}" class="nav-item"><i class="fas fa-school"></i> Shule</a>
            <a href="{{ route('district.teachers.index') }}" class="nav-item"><i class="fas fa-chalkboard-teacher"></i> Walimu
                @if($pendingTeachers > 0)<span class="nav-badge">{{ $pendingTeachers }}</span>@endif
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Usimamizi</div>
            <a href="{{ route('district.wards.index') }}" class="nav-item active"><i class="fas fa-map-marker-alt"></i> Kata</a>
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
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
                <span>/</span><strong>Kata</strong>
            </div>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </header>
    <div class="content">
        <div style="margin-bottom:20px;">
            <h1 style="font-size:22px;font-weight:700;">Kata Zote</h1>
            <p style="font-size:13px;color:var(--muted);margin-top:3px;">{{ $officer->council->name ?? 'Halmashauri' }}</p>
        </div>
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-title">Kata Zote</div>
                <div class="stat-value">{{ $totalWards }}</div>
                <div class="stat-tag">Kata zote chini ya halmashauri</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Waliopewa Afisa</div>
                <div class="stat-value">{{ $wardsWithOfficer }}</div>
                <div class="stat-tag">Kata zilizo na Afisa Elimu Kata</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Bila Afisa</div>
                <div class="stat-value">{{ $totalWards - $wardsWithOfficer }}</div>
                <div class="stat-tag">Kata zitakazohitaji mpangaji</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Walimu Waliidhinishwa</div>
                <div class="stat-value">{{ $totalTeachers }}</div>
                <div class="stat-tag">Walimu na Walimu Wakuu</div>
            </div>
        </div>
        <form method="GET" class="filter-bar">
            <div class="filter-group">
                <label class="filter-label">Tafuta kata</label>
                <input type="text" name="search" class="form-input" placeholder="Jina la kata" value="{{ $search }}">
            </div>
            <div class="filter-group" style="flex:0 0 120px">
                <label class="filter-label">Per Page</label>
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([10,20,50] as $count)
                        <option value="{{ $count }}" {{ $perPage == $count ? 'selected' : '' }}>{{ $count }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;align-items:flex-end;gap:10px">
                <button type="submit" class="btn btn-primary">Tafuta</button>
            </div>
        </form>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kata</th>
                        <th>Shule</th>
                        <th>Walimu</th>
                        <th>Wanaosubiri</th>
                        <th>Afisa Kata</th>
                        <th>Kiwango %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wards as $i => $ward)
                        <tr>
                            <td>{{ $wards->firstItem() + $i }}</td>
                            <td>{{ $ward->name }}</td>
                            <td>{{ $ward->school_count }}</td>
                            <td>{{ $ward->teacher_count }}</td>
                            <td>{{ $ward->pending_teachers }}</td>
                            <td>{{ $ward->ward_officer ?? '—' }}</td>
                            <td><span class="badge {{ $ward->attendance_rate >= 80 ? 'badge-success' : ($ward->attendance_rate >= 60 ? 'badge-warning' : 'badge-red') }}">{{ $ward->attendance_rate }}%</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="color:var(--muted);padding:20px;text-align:center">Hakuna kata zilizopatikana.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap" style="margin-top:18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
            <div class="info">Onyesha {{ $wards->firstItem() ?? 0 }} hadi {{ $wards->lastItem() ?? 0 }} kutoka {{ $wards->total() }}</div>
            <div class="pagination">{{ $wards->links() }}</div>
        </div>
    </div>
</div>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('overlay').classList.toggle('open')}
function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('overlay').classList.remove('open')}
</script>
</body>
</html>
