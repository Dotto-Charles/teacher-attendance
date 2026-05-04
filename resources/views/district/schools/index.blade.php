{{-- resources/views/district/schools/index.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shule · District Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        :root {
            --bg:#0f1117;--surface:#181c27;--surface2:#1e2335;--border:#2a2f45;
            --accent:#3b82f6;--accent2:#6366f1;--green:#10b981;--yellow:#f59e0b;
            --red:#ef4444;--text:#e2e8f0;--muted:#64748b;
            --font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;
            --r:14px;--r-sm:8px;
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
        .btn-warning{background:rgba(245,158,11,.15);color:var(--yellow);border:1px solid rgba(245,158,11,.3)}.btn-warning:hover{background:rgba(245,158,11,.25)}
        .btn-danger{background:rgba(239,68,68,.15);color:var(--red);border:1px solid rgba(239,68,68,.3)}.btn-danger:hover{background:rgba(239,68,68,.25)}

        /* CONTENT */
        .content{padding:24px 28px}

        /* FLASH */
        .flash{padding:12px 16px;border-radius:var(--r-sm);font-size:13px;display:flex;align-items:center;gap:10px;margin-bottom:20px;animation:slideIn .3s}
        .flash-success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:var(--green)}
        .flash-error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:var(--red)}
        @keyframes slideIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}

        /* STATS */
        .stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px}
        .mini-stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:18px;display:flex;align-items:center;gap:14px;transition:transform .2s;position:relative;overflow:hidden}
        .mini-stat::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
        .mini-stat.blue::before{background:var(--accent)}.mini-stat.green::before{background:var(--green)}
        .mini-stat.yellow::before{background:var(--yellow)}.mini-stat.purple::before{background:var(--accent2)}
        .mini-stat:hover{transform:translateY(-2px)}
        .mini-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
        .mini-stat.blue .mini-icon{background:rgba(59,130,246,.15);color:var(--accent)}
        .mini-stat.green .mini-icon{background:rgba(16,185,129,.15);color:var(--green)}
        .mini-stat.yellow .mini-icon{background:rgba(245,158,11,.15);color:var(--yellow)}
        .mini-stat.purple .mini-icon{background:rgba(99,102,241,.15);color:var(--accent2)}
        .mini-val{font-size:26px;font-weight:700;font-family:var(--mono);line-height:1}
        .mini-label{font-size:11px;color:var(--muted);margin-top:4px;font-weight:500}

        /* FILTER */
        .filter-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:16px 20px;margin-bottom:20px;display:flex;flex-wrap:wrap;align-items:flex-end;gap:12px}
        .filter-group{display:flex;flex-direction:column;gap:4px;flex:1;min-width:130px}
        .filter-label{font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.7px}
        .form-select,.form-input{background:var(--surface2);border:1px solid var(--border);color:var(--text);border-radius:var(--r-sm);padding:8px 12px;font-size:13px;font-family:var(--font);outline:none;width:100%}
        .form-select:focus,.form-input:focus{border-color:var(--accent)}
        .search-wrap{position:relative;flex:2;min-width:200px}
        .search-wrap .form-input{padding-left:36px}
        .search-icon{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px;pointer-events:none}

        /* SCHOOLS GRID */
        .schools-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;margin-bottom:24px}
        .school-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;transition:transform .2s,box-shadow .2s;cursor:pointer;text-decoration:none;display:block}
        .school-card:hover{transform:translateY(-3px);box-shadow:0 8px 32px rgba(0,0,0,.4);border-color:rgba(59,130,246,.3)}
        .school-card.inactive{opacity:.65}
        .sc-header{padding:18px 18px 14px;display:flex;align-items:flex-start;gap:12px}
        .sc-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;background:rgba(59,130,246,.12);color:var(--accent)}
        .sc-icon.sec{background:rgba(99,102,241,.12);color:var(--accent2)}
        .sc-title{font-size:14px;font-weight:700;line-height:1.3}
        .sc-ward{font-size:12px;color:var(--muted);margin-top:3px}
        .sc-code{font-size:11px;font-family:var(--mono);color:var(--muted);margin-top:2px}
        .sc-body{padding:0 18px 16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px}
        .sc-stat{text-align:center;background:var(--surface2);border-radius:var(--r-sm);padding:8px 6px}
        .sc-stat-val{font-size:18px;font-weight:700;font-family:var(--mono);line-height:1}
        .sc-stat-lbl{font-size:10px;color:var(--muted);margin-top:3px}
        .sc-footer{padding:12px 18px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .rate-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;font-family:var(--mono)}
        .rate-high{background:rgba(16,185,129,.15);color:var(--green)}
        .rate-mid{background:rgba(245,158,11,.15);color:var(--yellow)}
        .rate-low{background:rgba(239,68,68,.15);color:var(--red)}
        .rate-none{background:rgba(100,116,139,.15);color:var(--muted)}
        .status-dot{width:8px;height:8px;border-radius:50%;display:inline-block}
        .dot-active{background:var(--green)}.dot-inactive{background:var(--muted)}

        /* MAP */
        .map-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;margin-bottom:24px}
        .map-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .map-title{font-size:14px;font-weight:700}
        #schoolMap{height:380px;width:100%}
        .leaflet-container{background:#1e2335 !important}

        /* PAGINATION */
        .pagination-wrap{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-top:8px}
        .pagination-wrap .info{font-size:12px;color:var(--muted)}
        .pagination{display:flex;gap:4px}
        .pagination a,.pagination span{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--r-sm);font-size:13px;text-decoration:none;border:1px solid var(--border);color:var(--muted);transition:all .15s}
        .pagination a:hover{background:var(--surface2);color:var(--text)}
        .pagination .active-page{background:var(--accent);border-color:var(--accent);color:#fff;font-weight:700}

        /* MODAL */
        .modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);backdrop-filter:blur(4px);z-index:200;align-items:center;justify-content:center;padding:16px}
        .modal-bg.open{display:flex}
        .modal{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);width:100%;max-width:540px;max-height:92vh;overflow-y:auto;animation:modalIn .25s ease}
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
        .form-hint{font-size:11px;color:var(--muted);margin-top:4px}
        .invalid-feedback{font-size:11px;color:var(--red);margin-top:4px}

        /* OVERLAY */
        .overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99}
        .overlay.open{display:block}

        /* EMPTY */
        .empty{text-align:center;padding:64px 20px;color:var(--muted)}
        .empty i{font-size:48px;margin-bottom:16px;display:block;opacity:.4}
        .empty h3{font-size:16px;margin-bottom:6px;color:var(--text)}
        .empty p{font-size:13px}

        @media(max-width:768px){
            .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}
            .overlay.open{display:block}.main{margin-left:0}
            .hamburger{display:block}.content{padding:16px}.topbar{padding:12px 16px}
            .schools-grid{grid-template-columns:1fr}
            #schoolMap{height:260px}
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
                @if($pendingTeachers > 0)<span class="nav-badge">{{ $pendingTeachers }}</span>@endif
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Usimamizi</div>
            <a href="#" class="nav-item"><i class="fas fa-map-marker-alt"></i> Kata</a>
            <a href="{{ route('district.assignments.index') }}" class="nav-item"><i class="fas fa-exchange-alt"></i> Uhamisho</a>
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
                <span>/</span><strong>Shule</strong>
            </div>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <button class="btn btn-primary" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Ongeza Shule
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost"><i class="fas fa-sign-out-alt"></i></button>
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

        {{-- HEADING --}}
        <div style="margin-bottom:20px;">
            <h1 style="font-size:22px;font-weight:700;">Shule Zote</h1>
            <p style="font-size:13px;color:var(--muted);margin-top:3px;">{{ $officer->council->name ?? 'Halmashauri' }}</p>
        </div>

        {{-- STATS --}}
        <div class="stats-row">
            <div class="mini-stat blue">
                <div class="mini-icon"><i class="fas fa-school"></i></div>
                <div><div class="mini-val">{{ $totalSchools }}</div><div class="mini-label">Shule Zote</div></div>
            </div>
            <div class="mini-stat green">
                <div class="mini-icon"><i class="fas fa-check-circle"></i></div>
                <div><div class="mini-val">{{ $activeSchools }}</div><div class="mini-label">Zinafanya Kazi</div></div>
            </div>
            <div class="mini-stat yellow">
                <div class="mini-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div><div class="mini-val">{{ $withLocation }}</div><div class="mini-label">Zina Ramani</div></div>
            </div>
            <div class="mini-stat purple">
                <div class="mini-icon"><i class="fas fa-user-clock"></i></div>
                <div><div class="mini-val">{{ $pendingTeachers }}</div><div class="mini-label">Walimu Wanaongoja</div></div>
            </div>
        </div>

        {{-- MAP --}}
        <div class="map-card">
            <div class="map-header">
                <div class="map-title">🗺️ Ramani ya Shule</div>
                <span style="font-size:12px;color:var(--muted);">Shule zenye GPS pekee zinaonyeshwa</span>
            </div>
            <div id="schoolMap"></div>
        </div>

        {{-- FILTER --}}
        <form method="GET">
            <div class="filter-bar">
                <div class="filter-group" style="flex:3;min-width:200px;">
                    <label class="filter-label">Tafuta</label>
                    <div class="search-wrap">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="form-input" placeholder="Jina au namba ya shule..." value="{{ $search }}">
                    </div>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Kata</label>
                    <select name="ward_id" class="form-select">
                        <option value="">Zote</option>
                        @foreach($wards as $w)
                        <option value="{{ $w->id }}" {{ $wardId == $w->id ? 'selected':'' }}>{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="min-width:120px;">
                    <label class="filter-label">Hali</label>
                    <select name="status" class="form-select">
                        <option value="">Zote</option>
                        <option value="active"   {{ $status==='active'   ?'selected':'' }}>Zinafanya kazi</option>
                        <option value="inactive" {{ $status==='inactive' ?'selected':'' }}>Zimezimwa</option>
                    </select>
                </div>
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                <div style="display:flex;gap:8px;align-items:flex-end;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Chuja</button>
                    <a href="{{ route('district.schools.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </form>

        {{-- RESULTS INFO --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;font-size:13px;color:var(--muted);">
            <span>Shule <strong style="color:var(--text)">{{ $schools->firstItem() ?? 0 }}–{{ $schools->lastItem() ?? 0 }}</strong> kati ya <strong style="color:var(--text)">{{ $schools->total() }}</strong></span>
            <div style="display:flex;gap:8px;align-items:center;">
                <span style="font-size:12px;">Kwa ukurasa:</span>
                <select class="form-select" style="width:auto;padding:4px 8px;font-size:12px;" onchange="window.location='?per_page='+this.value+'&ward_id={{ $wardId }}&search={{ $search }}&status={{ $status }}'">
                    @foreach([9,15,30,60] as $pp)
                    <option value="{{ $pp }}" {{ $perPage==$pp?'selected':'' }}>{{ $pp }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- GRID --}}
        @if($schools->isEmpty())
        <div class="empty">
            <i class="fas fa-school"></i>
            <h3>Hakuna shule zilizopatikana</h3>
            <p>Jaribu kubadilisha vichujio au ongeza shule mpya</p>
        </div>
        @else
        <div class="schools-grid">
            @foreach($schools as $school)
            @php
                $rate  = $school->attendance_rate;
                $rateClass = $rate >= 80 ? 'rate-high' : ($rate >= 60 ? 'rate-mid' : ($rate > 0 ? 'rate-low' : 'rate-none'));
                $isSecondary = str_contains(strtolower($school->name), 'secondary');
            @endphp
            <a href="{{ route('district.schools.show', $school) }}" class="school-card {{ !$school->is_active ? 'inactive' : '' }}">
                <div class="sc-header">
                    <div class="sc-icon {{ $isSecondary ? 'sec' : '' }}">
                        {{ $isSecondary ? '🎓' : '🏫' }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="sc-title">{{ $school->name }}</div>
                        <div class="sc-ward"><i class="fas fa-map-marker-alt" style="font-size:10px;margin-right:3px"></i>{{ $school->ward->name ?? '—' }}</div>
                        @if($school->code)<div class="sc-code">{{ $school->code }}</div>@endif
                    </div>
                    <span class="status-dot {{ $school->is_active ? 'dot-active' : 'dot-inactive' }}" title="{{ $school->is_active ? 'Inafanya kazi' : 'Imezimwa' }}"></span>
                </div>
                <div class="sc-body">
                    <div class="sc-stat">
                        <div class="sc-stat-val" style="color:var(--accent)">{{ $school->teacher_count }}</div>
                        <div class="sc-stat-lbl">Walimu</div>
                    </div>
                    <div class="sc-stat">
                        <div class="sc-stat-val" style="color:var(--green)">{{ $school->attended_today }}</div>
                        <div class="sc-stat-lbl">Walifika Leo</div>
                    </div>
                    <div class="sc-stat">
                        <div class="sc-stat-val" style="color:{{ $school->latitude ? 'var(--yellow)' : 'var(--muted)' }}">
                            {{ $school->latitude ? '📍' : '—' }}
                        </div>
                        <div class="sc-stat-lbl">GPS</div>
                    </div>
                </div>
                <div class="sc-footer">
                    <span class="rate-badge {{ $rateClass }}">
                        <i class="fas fa-chart-line" style="font-size:10px"></i> {{ $rate }}% leo
                    </span>
                    <span style="font-size:12px;color:var(--muted);">Angalia <i class="fas fa-arrow-right" style="font-size:10px"></i></span>
                </div>
            </a>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="pagination-wrap">
            <span class="info">Ukurasa {{ $schools->currentPage() }} / {{ $schools->lastPage() }}</span>
            <div class="pagination">
                @if($schools->onFirstPage())
                    <span style="opacity:.4"><i class="fas fa-chevron-left" style="font-size:11px"></i></span>
                @else
                    <a href="{{ $schools->previousPageUrl() }}"><i class="fas fa-chevron-left" style="font-size:11px"></i></a>
                @endif
                @foreach($schools->getUrlRange(max(1,$schools->currentPage()-2),min($schools->lastPage(),$schools->currentPage()+2)) as $page => $url)
                    @if($page==$schools->currentPage())
                        <span class="active-page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
                @if($schools->hasMorePages())
                    <a href="{{ $schools->nextPageUrl() }}"><i class="fas fa-chevron-right" style="font-size:11px"></i></a>
                @else
                    <span style="opacity:.4"><i class="fas fa-chevron-right" style="font-size:11px"></i></span>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ADD SCHOOL MODAL --}}
<div class="modal-bg" id="addModalBg" onclick="closeAddModal(event)">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">➕ Ongeza Shule Mpya</div>
            <button class="modal-close" onclick="closeAddModalDirect()"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('district.schools.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Jina la Shule *</label>
                    <input type="text" name="name" class="form-input" placeholder="mf. Chemba Primary School" required value="{{ old('name') }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kata *</label>
                        <select name="ward_id" class="form-select" required>
                            <option value="">Chagua kata...</option>
                            @foreach($wards as $w)
                            <option value="{{ $w->id }}" {{ old('ward_id')==$w->id?'selected':'' }}>{{ $w->name }}</option>
                            @endforeach
                        </select>
                        @error('ward_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Namba ya Shule (Code)</label>
                        <input type="text" name="code" class="form-input" placeholder="mf. SCH-001" value="{{ old('code') }}">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div style="background:var(--surface2);border-radius:var(--r-sm);padding:14px;margin-bottom:16px;">
                    <div style="font-size:12px;font-weight:600;color:var(--muted);margin-bottom:12px;text-transform:uppercase;letter-spacing:.7px;">
                        <i class="fas fa-map-marker-alt" style="margin-right:6px"></i>GPS Location (optional)
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Latitude</label>
                            <input type="number" name="latitude" class="form-input" placeholder="-6.1234567" step="0.0000001" value="{{ old('latitude') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Longitude</label>
                            <input type="number" name="longitude" class="form-input" placeholder="35.1234567" step="0.0000001" value="{{ old('longitude') }}">
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:12px;margin-bottom:0">
                        <label class="form-label">Radius ya Check-in (mita)</label>
                        <input type="number" name="radius" class="form-input" placeholder="500" min="50" max="5000" value="{{ old('radius', 500) }}">
                        <div class="form-hint">Umbali unaoruhusiwa mwalimu kucheki-in (default: 500m)</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeAddModalDirect()">Funga</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Hifadhi Shule</button>
            </div>
        </form>
    </div>
</div>

<script>
// SIDEBAR
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('overlay').classList.toggle('open')}
function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('overlay').classList.remove('open')}

// MODAL
function openAddModal(){document.getElementById('addModalBg').classList.add('open');document.body.style.overflow='hidden'}
function closeAddModal(e){if(e.target===document.getElementById('addModalBg'))closeAddModalDirect()}
function closeAddModalDirect(){document.getElementById('addModalBg').classList.remove('open');document.body.style.overflow=''}

// Flash auto-dismiss
setTimeout(()=>{document.querySelectorAll('.flash').forEach(el=>{el.style.transition='opacity .5s';el.style.opacity='0';setTimeout(()=>el.remove(),500)})},4000)

// MAP
const schools = @json($schools->getCollection()->filter(fn($s) => $s->latitude && $s->longitude)->values());

if(schools.length > 0){
    const map = L.map('schoolMap', {
        zoomControl: true,
        scrollWheelZoom: false,
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',{
        attribution:'&copy; OpenStreetMap &copy; CARTO',maxZoom:18
    }).addTo(map);

    const bounds = [];
    schools.forEach(s => {
        const rate = s.attendance_rate;
        const color = rate >= 80 ? '#10b981' : rate >= 60 ? '#f59e0b' : rate > 0 ? '#ef4444' : '#64748b';
        const marker = L.circleMarker([s.latitude, s.longitude],{
            radius: 10, fillColor: color, color: '#fff',
            weight: 2, opacity: 1, fillOpacity: 0.85
        }).addTo(map);
        marker.bindPopup(`
            <div style="font-family:'DM Sans',sans-serif;min-width:180px;">
                <strong style="font-size:13px">${s.name}</strong><br>
                <span style="color:#64748b;font-size:11px">${s.ward ? s.ward.name : ''}</span><br>
                <div style="margin-top:6px;font-size:12px">
                    👨‍🏫 Walimu: <b>${s.teacher_count}</b> &nbsp;|&nbsp;
                    ✅ Leo: <b>${s.attended_today}</b><br>
                    📊 Kiwango: <b style="color:${color}">${rate}%</b>
                </div>
            </div>
        `);
        bounds.push([s.latitude, s.longitude]);
    });

    if(bounds.length > 0) map.fitBounds(bounds, {padding:[40,40]});
} else {
    document.getElementById('schoolMap').innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;color:#64748b;flex-direction:column;gap:10px;"><i class="fas fa-map" style="font-size:36px;opacity:.4"></i><p style="font-size:13px">Hakuna shule zenye GPS iliyowekwa</p></div>';
}

// Open add modal if validation errors
@if($errors->any())
openAddModal();
@endif
</script>
</body>
</html>