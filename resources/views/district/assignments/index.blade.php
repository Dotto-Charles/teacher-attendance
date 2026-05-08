{{-- resources/views/district/assignments/index.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kupanga & Uhamisho</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

        /* MAIN */
        .main{margin-left:240px;min-height:100vh}
        .topbar{position:sticky;top:0;z-index:50;background:rgba(15,17,23,.92);backdrop-filter:blur(14px);border-bottom:1px solid var(--border);padding:14px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px}
        .topbar-left{display:flex;align-items:center;gap:14px}
        .hamburger{display:none;background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:4px}
        .breadcrumb{display:flex;align-items:center;gap:8px;font-size:13px}
        .breadcrumb a{color:var(--muted);text-decoration:none}.breadcrumb a:hover{color:var(--text)}
        .breadcrumb span{color:var(--muted)}.breadcrumb strong{color:var(--text);font-weight:600}
        .content{padding:24px 28px}

        /* BUTTONS */
        .btn{padding:7px 16px;border-radius:var(--r-sm);font-size:13px;font-weight:600;border:none;cursor:pointer;font-family:var(--font);transition:all .2s;display:inline-flex;align-items:center;gap:6px;text-decoration:none}
        .btn-primary{background:var(--accent);color:#fff}.btn-primary:hover{background:#2563eb}
        .btn-ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}.btn-ghost:hover{background:var(--border)}
        .btn-success{background:rgba(16,185,129,.15);color:var(--green);border:1px solid rgba(16,185,129,.3)}.btn-success:hover{background:rgba(16,185,129,.25)}
        .btn-danger{background:rgba(239,68,68,.15);color:var(--red);border:1px solid rgba(239,68,68,.3)}.btn-danger:hover{background:rgba(239,68,68,.25)}
        .btn-warning{background:rgba(245,158,11,.15);color:var(--yellow);border:1px solid rgba(245,158,11,.3)}.btn-warning:hover{background:rgba(245,158,11,.25)}
        .btn-orange{background:rgba(249,115,22,.15);color:var(--orange);border:1px solid rgba(249,115,22,.3)}.btn-orange:hover{background:rgba(249,115,22,.25)}
        .btn-sm{padding:5px 12px;font-size:12px}

        /* FLASH */
        .flash{padding:12px 16px;border-radius:var(--r-sm);font-size:13px;display:flex;align-items:center;gap:10px;margin-bottom:20px;animation:fadeIn .3s}
        .flash-success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:var(--green)}
        .flash-error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:var(--red)}
        @keyframes fadeIn{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}

        /* STATS */
        .stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:24px}
        .stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:18px;position:relative;overflow:hidden;transition:transform .2s}
        .stat-card:hover{transform:translateY(-2px)}
        .stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
        .stat-card.s-blue::before{background:var(--accent)}.stat-card.s-green::before{background:var(--green)}
        .stat-card.s-yellow::before{background:var(--yellow)}.stat-card.s-red::before{background:var(--red)}
        .stat-card.s-purple::before{background:var(--accent2)}.stat-card.s-orange::before{background:var(--orange)}
        .stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;margin-bottom:12px}
        .s-blue .stat-icon{background:rgba(59,130,246,.15);color:var(--accent)}
        .s-green .stat-icon{background:rgba(16,185,129,.15);color:var(--green)}
        .s-yellow .stat-icon{background:rgba(245,158,11,.15);color:var(--yellow)}
        .s-red .stat-icon{background:rgba(239,68,68,.15);color:var(--red)}
        .s-purple .stat-icon{background:rgba(99,102,241,.15);color:var(--accent2)}
        .s-orange .stat-icon{background:rgba(249,115,22,.15);color:var(--orange)}
        .stat-val{font-size:28px;font-weight:800;font-family:var(--mono);line-height:1}
        .stat-lbl{font-size:11px;color:var(--muted);margin-top:5px;font-weight:500}

        /* PAGE TABS */
        .page-tabs{display:flex;gap:2px;background:var(--surface2);border-radius:var(--r-sm);padding:3px;margin-bottom:24px;overflow-x:auto}
        .page-tab{padding:9px 20px;border-radius:6px;font-size:13px;font-weight:600;border:none;cursor:pointer;font-family:var(--font);transition:all .2s;color:var(--muted);background:transparent;white-space:nowrap;display:flex;align-items:center;gap:7px;text-decoration:none}
        .page-tab:hover{color:var(--text)}
        .page-tab.active{background:var(--surface);color:var(--text);box-shadow:0 1px 8px rgba(0,0,0,.35)}
        .tab-count{background:var(--red);color:#fff;font-size:10px;padding:1px 6px;border-radius:10px;font-weight:700}
        .tab-count.green{background:var(--green)}
        .tab-count.blue{background:var(--accent)}

        /* TAB PANES */
        .tab-pane{display:none}.tab-pane.active{display:block}

        /* CARD */
        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;margin-bottom:20px}
        .card-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
        .card-title{font-size:14px;font-weight:700}
        .card-sub{font-size:12px;color:var(--muted);margin-top:2px}
        .card-body{padding:20px}

        /* FORM */
        .form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;align-items:end}
        .form-group{display:flex;flex-direction:column;gap:5px}
        .form-label{font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.7px}
        .form-select,.form-input,.form-textarea{background:var(--surface2);border:1px solid var(--border);color:var(--text);border-radius:var(--r-sm);padding:9px 12px;font-size:13px;font-family:var(--font);outline:none;width:100%}
        .form-select:focus,.form-input:focus,.form-textarea:focus{border-color:var(--accent)}
        .form-textarea{resize:vertical;min-height:70px}
        .form-hint{font-size:11px;color:var(--muted);margin-top:3px}
        .invalid-feedback{font-size:11px;color:var(--red);margin-top:3px}

        /* ALERT BOX */
        .info-box{padding:12px 16px;border-radius:var(--r-sm);font-size:12px;display:flex;align-items:flex-start;gap:10px;margin-bottom:16px}
        .info-box.warn{background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);color:var(--yellow)}
        .info-box.info{background:rgba(59,130,246,.08);border:1px solid rgba(59,130,246,.2);color:#93c5fd}

        /* ASSIGNMENT LIST */
        .assign-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
        .assign-item{display:flex;align-items:center;gap:12px;padding:12px;background:var(--surface2);border-radius:var(--r-sm);border:1px solid var(--border);transition:border-color .2s}
        .assign-item:hover{border-color:rgba(59,130,246,.3)}
        .a-avatar{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;flex-shrink:0}
        .a-avatar.wo{background:rgba(59,130,246,.2);color:var(--accent)}
        .a-avatar.ht{background:rgba(99,102,241,.2);color:var(--accent2)}
        .a-info{flex:1;min-width:0}
        .a-name{font-size:13px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .a-meta{font-size:11px;color:var(--muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .a-actions{display:flex;gap:6px;flex-shrink:0}

        /* EMPTY BADGE */
        .empty-ward{display:flex;align-items:center;gap:8px;padding:8px 12px;background:rgba(245,158,11,.06);border:1px dashed rgba(245,158,11,.3);border-radius:var(--r-sm);font-size:12px;color:var(--yellow)}

        /* TABLE */
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:13px}
        thead th{padding:10px 16px;text-align:left;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;background:var(--surface2);border-bottom:1px solid var(--border);white-space:nowrap}
        tbody td{padding:12px 16px;border-bottom:1px solid rgba(42,47,69,.5);vertical-align:middle}
        tbody tr:last-child td{border-bottom:none}
        tbody tr:hover td{background:rgba(30,35,53,.5)}
        .t-info{display:flex;align-items:center;gap:10px}
        .t-av{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;background:rgba(59,130,246,.15);color:var(--accent)}
        .t-name{font-weight:600;font-size:13px}
        .t-sub{font-size:11px;color:var(--muted);font-family:var(--mono)}

        /* BADGE */
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600}
        .b-pending{background:rgba(245,158,11,.15);color:var(--yellow)}
        .b-approved{background:rgba(16,185,129,.15);color:var(--green)}
        .b-rejected{background:rgba(239,68,68,.15);color:var(--red)}
        .b-teacher{background:rgba(100,116,139,.15);color:var(--muted)}
        .b-wo{background:rgba(59,130,246,.15);color:var(--accent)}
        .b-ht{background:rgba(99,102,241,.15);color:var(--accent2)}

        /* TRANSFER CARD */
        .transfer-card{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:16px;margin-bottom:12px;transition:border-color .2s}
        .transfer-card:hover{border-color:rgba(59,130,246,.3)}
        .tc-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px}
        .tc-person{display:flex;align-items:center;gap:10px}
        .tc-route{display:flex;align-items:center;gap:8px;font-size:13px;background:var(--surface);border-radius:var(--r-sm);padding:8px 12px;margin-bottom:10px;flex-wrap:wrap}
        .tc-school{font-weight:600}
        .tc-arrow{color:var(--accent);font-size:16px}
        .tc-meta{font-size:11px;color:var(--muted);display:flex;gap:14px;flex-wrap:wrap}
        .tc-actions{display:flex;gap:8px;flex-wrap:wrap}

        /* HISTORY */
        .history-row{display:flex;align-items:center;gap:14px;padding:11px 16px;border-bottom:1px solid rgba(42,47,69,.4)}
        .history-row:last-child{border-bottom:none}
        .h-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
        .h-dot.approved{background:var(--green)}.h-dot.rejected{background:var(--red)}
        .h-info{flex:1;min-width:0}
        .h-name{font-size:13px;font-weight:600}
        .h-route{font-size:11px;color:var(--muted);margin-top:2px}
        .h-date{font-size:11px;color:var(--muted);flex-shrink:0;font-family:var(--mono)}

        /* MODAL */
        .modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);backdrop-filter:blur(4px);z-index:200;align-items:center;justify-content:center;padding:16px}
        .modal-bg.open{display:flex}
        .modal{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);width:100%;max-width:480px;max-height:90vh;overflow-y:auto;animation:modalIn .25s}
        @keyframes modalIn{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
        .modal-header{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .modal-title{font-size:15px;font-weight:700}
        .modal-close{background:none;border:none;color:var(--muted);cursor:pointer;font-size:17px;padding:4px;transition:color .15s}
        .modal-close:hover{color:var(--text)}
        .modal-body{padding:22px}
        .modal-footer{padding:14px 22px;border-top:1px solid var(--border);display:flex;gap:10px;justify-content:flex-end}

        /* SEPARATOR */
        .sep{height:1px;background:var(--border);margin:20px 0}

        /* UNASSIGNED ALERT */
        .unassigned-list{display:flex;flex-wrap:wrap;gap:8px;padding:14px}
        .unassigned-chip{display:flex;align-items:center;gap:6px;padding:5px 12px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);border-radius:20px;font-size:12px;color:var(--yellow)}

        /* OVERLAY */
        .overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99}
        .overlay.open{display:block}

        @media(max-width:900px){.assign-grid{grid-template-columns:1fr}}
        @media(max-width:768px){
            .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}
            .overlay.open{display:block}.main{margin-left:0}
            .hamburger{display:block}.content{padding:14px}.topbar{padding:12px 16px}
            .stats-row{grid-template-columns:repeat(2,1fr);gap:10px}
            .form-grid{grid-template-columns:1fr}
            .page-tabs{gap:1px}
            .page-tab{padding:8px 14px;font-size:12px}
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
            <a href="{{ route('district.attendance.index') }}"   class="nav-item"><i class="fas fa-calendar-check"></i> Mahudhurio</a>
            <a href="{{ route('district.schools.index') }}"      class="nav-item"><i class="fas fa-school"></i> Shule</a>
            <a href="{{ route('district.teachers.index') }}"     class="nav-item">
                <i class="fas fa-chalkboard-teacher"></i> Walimu
                @if($pendingTeachers > 0)<span class="nav-badge">{{ $pendingTeachers }}</span>@endif
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Usimamizi</div>
            <a href="#" class="nav-item"><i class="fas fa-map-marker-alt"></i> Kata</a>
            <a href="{{ route('district.assignments.index') }}" class="nav-item active">
                <i class="fas fa-exchange-alt"></i> Uhamisho
                @if($stats['pending_transfers'] > 0)<span class="nav-badge">{{ $stats['pending_transfers'] }}</span>@endif
            </a>
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
    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
                <span>/</span><strong>Assignments & Uhamisho</strong>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i></button>
        </form>
    </header>

    <div class="content">

        @if(session('success'))
        <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="flash flash-error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
        @endif

        {{-- HEADING --}}
        <div style="margin-bottom:20px">
            <h1 style="font-size:22px;font-weight:800">Assignments & Uhamisho</h1>
            <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ $officer->council->name ?? 'Halmashauri' }} · Usimamizi wa nafasi na uhamisho</p>
        </div>

        {{-- STATS --}}
        <div class="stats-row">
            <div class="stat-card s-blue">
                <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                <div class="stat-val" style="color:var(--accent)">{{ $stats['ward_officers'] }}</div>
                <div class="stat-lbl">Afisa Elimu Kata</div>
            </div>
            <div class="stat-card s-purple">
                <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-val" style="color:var(--accent2)">{{ $stats['head_teachers'] }}</div>
                <div class="stat-lbl">Walimu Wakuu</div>
            </div>
            <div class="stat-card s-yellow">
                <div class="stat-icon"><i class="fas fa-map-marker-slash"></i></div>
                <div class="stat-val" style="color:var(--yellow)">{{ $stats['unassigned_wards'] }}</div>
                <div class="stat-lbl">Kata Bila Afisa</div>
            </div>
            <div class="stat-card s-orange">
                <div class="stat-icon"><i class="fas fa-school"></i></div>
                <div class="stat-val" style="color:var(--orange)">{{ $stats['unassigned_schools'] }}</div>
                <div class="stat-lbl">Shule Bila Mkuu</div>
            </div>
            <div class="stat-card s-red">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-val" style="color:var(--red)">{{ $stats['pending_transfers'] }}</div>
                <div class="stat-lbl">Maombi Yanayosubiri</div>
            </div>
            <div class="stat-card s-green">
                <div class="stat-icon"><i class="fas fa-exchange-alt"></i></div>
                <div class="stat-val" style="color:var(--green)">{{ $stats['total_transfers'] }}</div>
                <div class="stat-lbl">Uhamisho Wote</div>
            </div>
        </div>

        {{-- PAGE TABS --}}
        <div class="page-tabs">
            <a href="?tab=overview" class="page-tab {{ $tab==='overview'?'active':'' }}">
                <i class="fas fa-th-large"></i> Muhtasari
            </a>
            <a href="?tab=ward_officers" class="page-tab {{ $tab==='ward_officers'?'active':'' }}">
                <i class="fas fa-user-tie"></i> Afisa Elimu Kata
                @if($stats['unassigned_wards'] > 0)<span class="tab-count">{{ $stats['unassigned_wards'] }}</span>@endif
            </a>
            <a href="?tab=head_teachers" class="page-tab {{ $tab==='head_teachers'?'active':'' }}">
                <i class="fas fa-chalkboard-teacher"></i> Walimu Wakuu
                @if($stats['unassigned_schools'] > 0)<span class="tab-count">{{ $stats['unassigned_schools'] }}</span>@endif
            </a>
            <a href="?tab=transfers" class="page-tab {{ $tab==='transfers'?'active':'' }}">
                <i class="fas fa-exchange-alt"></i> Uhamisho
                @if($stats['pending_transfers'] > 0)<span class="tab-count">{{ $stats['pending_transfers'] }}</span>@endif
            </a>
            <a href="?tab=history" class="page-tab {{ $tab==='history'?'active':'' }}">
                <i class="fas fa-history"></i> Historia
                <span class="tab-count blue">{{ $stats['total_transfers'] }}</span>
            </a>
        </div>

        {{-- ══════════════════ TAB: OVERVIEW ══════════════════ --}}
        @if($tab === 'overview')

        {{-- Unassigned alerts --}}
        @if($unassignedWards->count() > 0)
        <div class="info-box warn">
            <i class="fas fa-exclamation-triangle" style="margin-top:1px;flex-shrink:0"></i>
            <div>
                <strong>Kata {{ $unassignedWards->count() }} hazina Afisa Elimu:</strong>
                {{ $unassignedWards->pluck('name')->join(', ') }}
            </div>
        </div>
        @endif
        @if($unassignedSchools->count() > 0)
        <div class="info-box warn">
            <i class="fas fa-exclamation-triangle" style="margin-top:1px;flex-shrink:0"></i>
            <div>
                <strong>Shule {{ $unassignedSchools->count() }} Hazina Mwalimu Mkuu:</strong>
                {{ $unassignedSchools->pluck('name')->join(', ') }}
            </div>
        </div>
        @endif

        {{-- Quick summary 2-col --}}
        <div class="assign-grid">
            {{-- Ward Officers --}}
            <div class="card" style="margin-bottom:0">
                <div class="card-header">
                    <div><div class="card-title">🗺️ Afisa Elimu Kata ({{ $wardOfficers->count() }})</div><div class="card-sub">Maafisa wa kata</div></div>
                    <a href="?tab=ward_officers" class="btn btn-ghost btn-sm">Simamia <i class="fas fa-arrow-right"></i></a>
                </div>
                <div style="padding:12px 16px;display:flex;flex-direction:column;gap:8px;max-height:280px;overflow-y:auto">
                    @forelse($wardOfficers->take(6) as $wo)
                    <div class="assign-item" style="padding:10px">
                        <div class="a-avatar wo">{{ strtoupper(substr($wo->first_name,0,1)) }}</div>
                        <div class="a-info">
                            <div class="a-name">{{ $wo->first_name }} {{ $wo->last_name }}</div>
                            <div class="a-meta"><i class="fas fa-map-marker-alt" style="font-size:9px;margin-right:3px"></i>{{ $wo->ward->name ?? '—' }}</div>
                        </div>
                        <span class="badge b-wo" style="font-size:10px">WO</span>
                    </div>
                    @empty
                    <div style="text-align:center;padding:24px;color:var(--muted);font-size:13px">Hakuna Ward Officers</div>
                    @endforelse
                </div>
            </div>

            {{-- Head Teachers --}}
            <div class="card" style="margin-bottom:0">
                <div class="card-header">
                    <div><div class="card-title">🎓 Walimu Wakuu ({{ $headTeachers->count() }})</div><div class="card-sub">Wakuu wa shule</div></div>
                    <a href="?tab=head_teachers" class="btn btn-ghost btn-sm">Simamia <i class="fas fa-arrow-right"></i></a>
                </div>
                <div style="padding:12px 16px;display:flex;flex-direction:column;gap:8px;max-height:280px;overflow-y:auto">
                    @forelse($headTeachers->take(6) as $ht)
                    <div class="assign-item" style="padding:10px">
                        <div class="a-avatar ht">{{ strtoupper(substr($ht->first_name,0,1)) }}</div>
                        <div class="a-info">
                            <div class="a-name">{{ $ht->first_name }} {{ $ht->last_name }}</div>
                            <div class="a-meta"><i class="fas fa-school" style="font-size:9px;margin-right:3px"></i>{{ $ht->school->name ?? '—' }}</div>
                        </div>
                        <span class="badge b-ht" style="font-size:10px">HT</span>
                    </div>
                    @empty
                    <div style="text-align:center;padding:24px;color:var(--muted);font-size:13px">Hakuna Walimu Wakuu</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Pending transfers preview --}}
        @if($pendingTransfers->count() > 0)
        <div class="card" style="margin-top:20px">
            <div class="card-header">
                <div><div class="card-title">⏳ Maombi ya Uhamisho Yanayosubiri ({{ $pendingTransfers->count() }})</div></div>
                <a href="?tab=transfers" class="btn btn-warning btn-sm">Kagua Yote <i class="fas fa-arrow-right"></i></a>
            </div>
            <div style="padding:12px 16px">
                @foreach($pendingTransfers->take(3) as $tr)
                <div style="display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:1px solid rgba(42,47,69,.4)">
                    <div style="width:8px;height:8px;border-radius:50%;background:var(--yellow);flex-shrink:0"></div>
                    <div style="flex:1;font-size:13px">
                        <strong>{{ $tr->user->first_name }} {{ $tr->user->last_name }}</strong>
                        <span style="color:var(--muted)"> · {{ $tr->fromSchool->name ?? '—' }} → {{ $tr->toSchool->name ?? '—' }}</span>
                    </div>
                    <span style="font-size:11px;color:var(--muted)">{{ $tr->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @endif {{-- end overview --}}

        {{-- ══════════════════ TAB: WARD OFFICERS ══════════════════ --}}
        @if($tab === 'ward_officers')

        {{-- Assign form --}}
        <div class="card">
            <div class="card-header">
                <div><div class="card-title">➕ Panga / Badilisha Afisa Elimu Kata</div><div class="card-sub">Chagua mwalimu na kata unayotaka</div></div>
            </div>
            <div class="card-body">
                <div class="info-box warn">
                    <i class="fas fa-info-circle" style="flex-shrink:0;margin-top:1px"></i>
                    <span>Kama kata tayari ina AEK, atashushwa kuwa mwalimu wa kawaida katika shule ya kata hiyo moja kwa moja.</span>
                </div>
                <form method="POST" action="{{ route('district.assignments.assign-ward-officer') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Chagua Mwalimu *</label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Chagua mwalimu --</option>
                                @foreach($eligibleTeachers as $t)
                                <option value="{{ $t->id }}">
                                    {{ $t->first_name }} {{ $t->last_name }}
                                    ({{ $t->role === 'ward_officer' ? 'WO' : ($t->role === 'head_teacher' ? 'HT' : 'Mwalimu') }}
                                    · {{ $t->school->name ?? $t->ward->name ?? '—' }})
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Chagua Kata *</label>
                            <select name="ward_id" class="form-select" required>
                                <option value="">-- Chagua kata --</option>
                                @foreach($wards as $w)
                                @php $hasOfficer = $wardOfficers->where('ward_id', $w->id)->first() @endphp
                                <option value="{{ $w->id }}">
                                    {{ $w->name }}
                                    @if($hasOfficer) ⚠️ (ina: {{ $hasOfficer->first_name }}) @else ✅ (haina AEK) @endif
                                </option>
                                @endforeach
                            </select>
                            @error('ward_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="justify-content:flex-end">
                            <button type="submit" class="btn btn-primary" style="width:100%">
                                <i class="fas fa-user-tie"></i> Panga AEK
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Unassigned wards --}}
        @if($unassignedWards->count() > 0)
        <div class="card">
            <div class="card-header"><div class="card-title" style="color:var(--yellow)">⚠️ Kata Hazina Ward Officer ({{ $unassignedWards->count() }})</div></div>
            <div class="unassigned-list">
                @foreach($unassignedWards as $w)
                <div class="unassigned-chip"><i class="fas fa-map-marker-slash" style="font-size:11px"></i> {{ $w->name }}</div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Current Ward Officers --}}
        <div class="card">
            <div class="card-header">
                <div><div class="card-title">📋 AEK Waliopo ({{ $wardOfficers->count() }})</div></div>
            </div>
            @if($wardOfficers->isEmpty())
            <div style="text-align:center;padding:40px;color:var(--muted)"><i class="fas fa-user-slash" style="font-size:32px;margin-bottom:10px;display:block;opacity:.4"></i><p>Hakuna Ward Officers</p></div>
            @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>#</th><th>Jina</th><th>Namba</th><th>Kata</th><th>Jinsi</th><th>Vitendo</th></tr></thead>
                    <tbody>
                        @foreach($wardOfficers as $i => $wo)
                        <tr>
                            <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                            <td>
                                <div class="t-info">
                                    <div class="t-av" style="background:rgba(59,130,246,.15);color:var(--accent)">{{ strtoupper(substr($wo->first_name,0,1)) }}</div>
                                    <div>
                                        <div class="t-name">{{ $wo->first_name }} {{ $wo->last_name }}</div>
                                        <div class="t-sub">{{ $wo->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-family:var(--mono);font-size:12px">{{ $wo->check_number }}</td>
                            <td><span class="badge b-wo"><i class="fas fa-map-marker-alt" style="font-size:9px"></i> {{ $wo->ward->name ?? '—' }}</span></td>
                            <td style="font-size:12px;color:{{ $wo->sex==='female'?'var(--pink)':'var(--accent2)' }}">{{ $wo->sex==='female'?'♀ Mke':'♂ Mme' }}</td>
                            <td>
                                <form method="POST" action="{{ route('district.assignments.remove-ward-officer', $wo) }}"
                                      onsubmit="return confirm('Una uhakika wa kumshushia {{ $wo->first_name }} nafasi ya AEK?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-user-minus"></i> Ondoa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endif {{-- end ward_officers --}}

        {{-- ══════════════════ TAB: HEAD TEACHERS ══════════════════ --}}
        @if($tab === 'head_teachers')

        <div class="card">
            <div class="card-header">
                <div><div class="card-title">➕ Panga / Badilisha Mwalimu Mkuu</div><div class="card-sub">Chagua mwalimu na shule</div></div>
            </div>
            <div class="card-body">
                <div class="info-box warn">
                    <i class="fas fa-info-circle" style="flex-shrink:0;margin-top:1px"></i>
                    <span>Kama shule tayari ina Mwalimu Mkuu, atashushwa kuwa mwalimu wa kawaida katika hiyo shule moja kwa moja.</span>
                </div>
                <form method="POST" action="{{ route('district.assignments.assign-head-teacher') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Chagua Mwalimu *</label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Chagua mwalimu --</option>
                                @foreach($eligibleTeachers as $t)
                                <option value="{{ $t->id }}">
                                    {{ $t->first_name }} {{ $t->last_name }}
                                    ({{ $t->role === 'ward_officer' ? 'WO' : ($t->role === 'head_teacher' ? 'HT' : 'Mwalimu') }}
                                    · {{ $t->school->name ?? $t->ward->name ?? '—' }})
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Chagua Shule *</label>
                            <select name="school_id" class="form-select" required>
                                <option value="">-- Chagua shule --</option>
                                @foreach($schools as $sc)
                                @php $hasHT = $headTeachers->where('school_id', $sc->id)->first() @endphp
                                <option value="{{ $sc->id }}">
                                    {{ $sc->name }} ({{ $sc->ward->name ?? '—' }})
                                    @if($hasHT) ⚠️ (ina: {{ $hasHT->first_name }}) @else ✅ (haina HT) @endif
                                </option>
                                @endforeach
                            </select>
                            @error('school_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="justify-content:flex-end">
                            <button type="submit" class="btn btn-primary" style="width:100%">
                                <i class="fas fa-chalkboard-teacher"></i> Panga Mwalimu Mkuu
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($unassignedSchools->count() > 0)
        <div class="card">
            <div class="card-header"><div class="card-title" style="color:var(--yellow)">⚠️ Shule Hazina Mwalimu Mkuu ({{ $unassignedSchools->count() }})</div></div>
            <div class="unassigned-list">
                @foreach($unassignedSchools as $sc)
                <div class="unassigned-chip"><i class="fas fa-school" style="font-size:11px"></i> {{ $sc->name }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div><div class="card-title">📋 Walimu Wakuu Waliopo ({{ $headTeachers->count() }})</div></div>
            </div>
            @if($headTeachers->isEmpty())
            <div style="text-align:center;padding:40px;color:var(--muted)"><i class="fas fa-chalkboard-teacher" style="font-size:32px;margin-bottom:10px;display:block;opacity:.4"></i><p>Hakuna Walimu Wakuu</p></div>
            @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>#</th><th>Jina</th><th>Namba</th><th>Shule</th><th>Kata</th><th>Jinsi</th><th>Vitendo</th></tr></thead>
                    <tbody>
                        @foreach($headTeachers as $i => $ht)
                        <tr>
                            <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                            <td>
                                <div class="t-info">
                                    <div class="t-av" style="background:rgba(99,102,241,.15);color:var(--accent2)">{{ strtoupper(substr($ht->first_name,0,1)) }}</div>
                                    <div>
                                        <div class="t-name">{{ $ht->first_name }} {{ $ht->last_name }}</div>
                                        <div class="t-sub">{{ $ht->check_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-family:var(--mono);font-size:12px">{{ $ht->check_number }}</td>
                            <td><span class="badge b-ht"><i class="fas fa-school" style="font-size:9px"></i> {{ $ht->school->name ?? '—' }}</span></td>
                            <td style="font-size:12px;color:var(--muted)">{{ $ht->school->ward->name ?? '—' }}</td>
                            <td style="font-size:12px;color:{{ $ht->sex==='female'?'var(--pink)':'var(--accent2)' }}">{{ $ht->sex==='female'?'♀ Mke':'♂ Mme' }}</td>
                            <td>
                                <form method="POST" action="{{ route('district.assignments.remove-head-teacher', $ht) }}"
                                      onsubmit="return confirm('Una uhakika wa kumshushia {{ $ht->first_name }} nafasi ya Mwalimu Mkuu?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-user-minus"></i> Ondoa</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endif {{-- end head_teachers --}}

        {{-- ══════════════════ TAB: TRANSFERS ══════════════════ --}}
        @if($tab === 'transfers')

        {{-- Request new transfer --}}
        <div class="card">
            <div class="card-header">
                <div><div class="card-title">📤 Omba Uhamisho Mpya</div><div class="card-sub">Ombi litasubiri idhini yako</div></div>
            </div>
            <div class="card-body">
                <div class="info-box info">
                    <i class="fas fa-info-circle" style="flex-shrink:0;margin-top:1px"></i>
                    <span>Uhamisho wa mwalimu mkuu au AEK utashushwa kwenye nafasi yao wakati ombi linapoidhinishwa.</span>
                </div>
                <form method="POST" action="{{ route('district.assignments.request-transfer') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Chagua Mwalimu / AEK / HT *</label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Chagua mtu --</option>
                                @foreach($eligibleTeachers as $t)
                                <option value="{{ $t->id }}">
                                    {{ $t->first_name }} {{ $t->last_name }}
                                    ({{ $t->role === 'ward_officer' ? 'WO' : ($t->role === 'head_teacher' ? 'HT' : 'Mwalimu') }}
                                    · {{ $t->school->name ?? $t->ward->name ?? '—' }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Shule Inayolengwa *</label>
                            <select name="to_school_id" class="form-select" required>
                                <option value="">-- Shule mpya --</option>
                                @foreach($schools as $sc)
                                <option value="{{ $sc->id }}">{{ $sc->name }} ({{ $sc->ward->name ?? '—' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="grid-column:1/-1">
                            <label class="form-label">Sababu ya Uhamisho</label>
                            <textarea name="reason" class="form-textarea" placeholder="Eleza sababu ya uhamisho (si lazima)..."></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Wasilisha Ombi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Pending transfers --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">⏳ Maombi Yanayosubiri Idhini ({{ $pendingTransfers->count() }})</div>
                    <div class="card-sub">Idhini au kataa kila ombi</div>
                </div>
            </div>
            @if($pendingTransfers->isEmpty())
            <div style="text-align:center;padding:40px;color:var(--muted)">
                <i class="fas fa-check-circle" style="font-size:36px;margin-bottom:12px;display:block;color:var(--green);opacity:.6"></i>
                <p style="font-size:14px;font-weight:600;color:var(--green)">Hakuna maombi yanayosubiri!</p>
            </div>
            @else
            <div style="padding:16px">
                @foreach($pendingTransfers as $tr)
                <div class="transfer-card">
                    <div class="tc-header">
                        <div class="tc-person">
                            <div class="t-av" style="width:38px;height:38px;font-size:14px">{{ strtoupper(substr($tr->user->first_name,0,1)) }}</div>
                            <div>
                                <div style="font-size:14px;font-weight:700">{{ $tr->user->first_name }} {{ $tr->user->last_name }}</div>
                                <div style="font-size:11px;color:var(--muted)">{{ $tr->user->check_number }}</div>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px">
                            <span class="badge b-pending"><i class="fas fa-clock" style="font-size:9px"></i> Inasubiri</span>
                            @php
                                $role = $tr->user->role;
                                $roleClass = $role === 'ward_officer' ? 'b-wo' : ($role === 'head_teacher' ? 'b-ht' : 'b-teacher');
                                $roleLbl   = $role === 'ward_officer' ? 'WO' : ($role === 'head_teacher' ? 'HT' : 'Mwalimu');
                            @endphp
                            <span class="badge {{ $roleClass }}">{{ $roleLbl }}</span>
                        </div>
                    </div>
                    <div class="tc-route">
                        <i class="fas fa-school" style="color:var(--muted);font-size:11px"></i>
                        <span class="tc-school">{{ $tr->fromSchool->name ?? '(Hana shule)' }}</span>
                        <span class="tc-arrow">→</span>
                        <span class="tc-school" style="color:var(--accent)">{{ $tr->toSchool->name ?? '—' }}</span>
                        <span style="font-size:11px;color:var(--muted);margin-left:auto">{{ $tr->toSchool->ward->name ?? '—' }}</span>
                    </div>
                    @if($tr->reason)
                    <div style="font-size:12px;color:var(--muted);margin-bottom:10px;font-style:italic;">
                        <i class="fas fa-comment" style="font-size:10px;margin-right:4px"></i>{{ $tr->reason }}
                    </div>
                    @endif
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                        <div class="tc-meta">
                            <span><i class="fas fa-user" style="font-size:10px;margin-right:3px"></i>Ombi na: {{ $tr->requester->first_name ?? 'System' }}</span>
                            <span><i class="fas fa-clock" style="font-size:10px;margin-right:3px"></i>{{ $tr->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="tc-actions">
                            <form method="POST" action="{{ route('district.assignments.approve-transfer', $tr) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm"
                                        onclick="return confirm('Idhinisha uhamisho huu?')">
                                    <i class="fas fa-check"></i> Idhinisha
                                </button>
                            </form>
                            <button class="btn btn-danger btn-sm" onclick="openRejectModal({{ $tr->id }})">
                                <i class="fas fa-times"></i> Kataa
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endif {{-- end transfers --}}

        {{-- ══════════════════ TAB: HISTORY ══════════════════ --}}
        @if($tab === 'history')
        <div class="card">
            <div class="card-header">
                <div><div class="card-title">📜 Historia ya Uhamisho</div><div class="card-sub">Uhamisho 50 wa mwisho</div></div>
            </div>
            @if($transferHistory->isEmpty())
            <div style="text-align:center;padding:48px;color:var(--muted)">
                <i class="fas fa-history" style="font-size:36px;margin-bottom:12px;display:block;opacity:.4"></i>
                <p>Hakuna historia ya uhamisho bado</p>
            </div>
            @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>#</th><th>Mwalimu</th><th>Kutoka</th><th>Kwenda</th><th>Hali</th><th>Mliwasilisha</th><th>Tarehe</th></tr></thead>
                    <tbody>
                        @foreach($transferHistory as $i => $tr)
                        <tr>
                            <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                            <td>
                                <div class="t-info">
                                    <div class="t-av">{{ strtoupper(substr($tr->user->first_name ?? 'U', 0, 1)) }}</div>
                                    <div>
                                        <div class="t-name">{{ $tr->user->first_name ?? '—' }} {{ $tr->user->last_name ?? '' }}</div>
                                        <div class="t-sub">{{ $tr->user->check_number ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:12px;color:var(--muted)">{{ $tr->fromSchool->name ?? '—' }}</td>
                            <td style="font-size:12px;font-weight:600">{{ $tr->toSchool->name ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $tr->status==='approved'?'b-approved':'b-rejected' }}">
                                    {{ $tr->status==='approved'?'✅ Imeidhinishwa':'❌ Imekataliwa' }}
                                </span>
                            </td>
                            <td style="font-size:12px;color:var(--muted)">{{ $tr->requester->first_name ?? '—' }}</td>
                            <td style="font-size:11px;color:var(--muted);font-family:var(--mono)">{{ $tr->updated_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endif {{-- end history --}}

    </div>{{-- /content --}}
</div>{{-- /main --}}

{{-- REJECT MODAL --}}
<div class="modal-bg" id="rejectModalBg" onclick="closeRejectModal(event)">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">❌ Kataa Ombi la Uhamisho</div>
            <button class="modal-close" onclick="closeRejectModalDirect()"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" id="rejectForm">
            @csrf @method('PATCH')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Sababu ya Kukataa (optional)</label>
                    <textarea name="rejection_reason" class="form-textarea" placeholder="Eleza sababu ya kukataa ombi hili..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeRejectModalDirect()">Funga</button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Kataa Ombi</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('overlay').classList.toggle('open')}
function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('overlay').classList.remove('open')}

function openRejectModal(id){
    document.getElementById('rejectForm').action = `/district/assignments/transfers/${id}/reject`;
    document.getElementById('rejectModalBg').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeRejectModal(e){if(e.target===document.getElementById('rejectModalBg'))closeRejectModalDirect()}
function closeRejectModalDirect(){document.getElementById('rejectModalBg').classList.remove('open');document.body.style.overflow=''}

setTimeout(()=>{
    document.querySelectorAll('.flash').forEach(el=>{
        el.style.transition='opacity .5s';el.style.opacity='0';
        setTimeout(()=>el.remove(),500);
    });
},4000);
</script>
</body>
</html>