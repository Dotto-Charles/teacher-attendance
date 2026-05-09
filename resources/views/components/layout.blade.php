{{-- resources/views/components/layout.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }} · EduAttend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    {{ $styles ?? '' }}
    <style>
        :root {
            --sidebar-w: 260px;
            --primary:   #0d6efd;
            --primary-d: #0b5ed7;
            --surface:   #ffffff;
            --bg:        #f0f4f8;
            --border:    #e2e8f0;
            --text:      #1e293b;
            --muted:     #64748b;
            --success:   #10b981;
            --warning:   #f59e0b;
            --danger:    #ef4444;
            --font:      'DM Sans', sans-serif;
            --mono:      'DM Mono', monospace;
            --r:         12px;
        }

        * { box-sizing: border-box; }
        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            margin: 0;
            min-height: 100vh;
        }

        /* ─── SIDEBAR ─────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--primary);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform .28s cubic-bezier(.4,0,.2,1), width .28s cubic-bezier(.4,0,.2,1);
            box-shadow: 4px 0 20px rgba(13,110,253,.15);
        }
        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar-header {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,.12);
            flex-shrink: 0;
        }
        .sidebar-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .brand-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,.2);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .brand-text  { font-size: 15px; font-weight: 700; color: #fff; line-height: 1.2; }
        .brand-sub   { font-size: 11px; color: rgba(255,255,255,.6); }
        .sidebar.collapsed .brand-text,
        .sidebar.collapsed .brand-sub { display: none; }
        .sidebar.collapsed .brand-icon { margin: 0 auto; }

        /* User chip */
        .sidebar-user {
            margin: 12px 14px 0;
            padding: 10px 12px;
            background: rgba(255,255,255,.12);
            border-radius: var(--r);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-avatar {
            width: 34px; height: 34px;
            background: rgba(255,255,255,.25);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .sidebar-uname { font-size: 13px; font-weight: 600; color: #fff; line-height: 1.2; }
        .sidebar-urole { font-size: 10px; color: rgba(255,255,255,.6); }
        .sidebar.collapsed .sidebar-uname,
        .sidebar.collapsed .sidebar-urole { display: none; }
        .sidebar.collapsed .sidebar-user { justify-content: center; padding: 10px; }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 14px 10px;
            scrollbar-width: none;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .nav-section-label {
            font-size: 10px; font-weight: 600;
            color: rgba(255,255,255,.45);
            letter-spacing: 1.1px; text-transform: uppercase;
            padding: 0 10px; margin: 14px 0 5px;
        }
        .sidebar.collapsed .nav-section-label { display: none; }
        .nav-section-label:first-child { margin-top: 0; }

        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            border-radius: var(--r);
            color: rgba(255,255,255,.78);
            text-decoration: none;
            font-size: 13.5px; font-weight: 500;
            transition: all .18s;
            margin-bottom: 2px;
            position: relative;
        }
        .sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding: 9px;
        }
        .sidebar-link i { width: 18px; text-align: center; font-size: 15px; flex-shrink: 0; }
        .sidebar.collapsed .sidebar-link span { display: none; }
        .sidebar-link:hover { background: rgba(255,255,255,.15); color: #fff; }
        .sidebar-link.active { background: rgba(255,255,255,.22); color: #fff; font-weight: 600; }
        .sidebar-link.active::before {
            content: '';
            position: absolute; left: 0; top: 20%; bottom: 20%;
            width: 3px; border-radius: 99px;
            background: #fff;
        }
        .sidebar.collapsed .sidebar-link.active::before {
            left: 50%; transform: translateX(-50%);
            width: 4px; height: 60%;
            top: 20%;
        }
        .s-badge {
            margin-left: auto;
            background: var(--danger); color: #fff;
            font-size: 10px; font-weight: 700;
            padding: 2px 6px; border-radius: 20px;
        }

        /* Logout */
        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,.12);
            flex-shrink: 0;
        }
        .logout-btn {
            width: 100%; padding: 9px;
            border-radius: var(--r);
            background: rgba(239,68,68,.2);
            border: 1px solid rgba(239,68,68,.3);
            color: #fca5a5;
            font-size: 13px; font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 7px;
            transition: all .18s; font-family: var(--font);
        }
        .sidebar.collapsed .logout-btn { padding: 9px; }
        .sidebar.collapsed .logout-btn span { display: none; }
        .logout-btn:hover { background: rgba(239,68,68,.35); color: #fff; }

        /* ─── MAIN ────────────────────────────────────────────── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
            transition: margin-left .28s cubic-bezier(.4,0,.2,1);
        }
        .sidebar.collapsed + .main-wrap {
            margin-left: 60px;
        }

        /* ─── TOPBAR ──────────────────────────────────────────── */
        .topbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 10px 24px;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }
        .topbar-left  { display: flex; align-items: center; gap: 12px; }
        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .hamburger {
            display: none;
            background: none; border: none;
            color: var(--text); font-size: 20px;
            cursor: pointer; padding: 4px;
            border-radius: 8px; transition: background .15s;
        }
        .hamburger:hover { background: var(--bg); }

        .collapse-btn {
            display: block;
            background: none; border: none;
            color: var(--text); font-size: 18px;
            cursor: pointer; padding: 4px;
            border-radius: 8px; transition: background .15s;
        }
        .collapse-btn:hover { background: var(--bg); }
        @media (max-width: 991px) {
            .collapse-btn { display: none; }
        }

        .page-title-bar { line-height: 1; }
        .page-title-bar .pg-title { font-size: 16px; font-weight: 700; color: var(--text); }
        .page-title-bar .pg-sub   { font-size: 12px; color: var(--muted); margin-top: 2px; }

        .topbar-date {
            font-size: 12px; color: var(--muted);
            background: var(--bg); border-radius: 20px;
            padding: 4px 12px;
            display: flex; align-items: center; gap: 6px;
        }

        /* ─── PAGE BODY ───────────────────────────────────────── */
        .page-body { padding: 24px; flex: 1; }

        /* ─── OVERLAY ─────────────────────────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 1039;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.show { display: block; }

        /* ─── FLASH ───────────────────────────────────────────── */
        .flash-msg {
            padding: 11px 16px; border-radius: var(--r);
            font-size: 13px; display: flex; align-items: center; gap: 10px;
            margin-bottom: 16px; animation: fadeDown .3s ease;
        }
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .flash-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        @keyframes fadeDown { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }

        /* ─── RESPONSIVE ──────────────────────────────────────── */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrap { margin-left: 0; }
            .hamburger { display: block; }
            .page-body { padding: 16px; }
            .topbar { padding: 10px 16px; }
        }
        @media (max-width: 576px) {
            .topbar-date { display: none; }
        }
    </style>
</head>
<body>

{{-- OVERLAY --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ═══ SIDEBAR ═══ --}}
<aside class="sidebar" id="appSidebar">

    {{-- Brand --}}
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">🏫</div>
            <div>
                <div class="brand-text">EduAttend</div>
                <div class="brand-sub">Attendance System</div>
            </div>
        </a>
    </div>

    {{-- User chip --}}
    <div class="sidebar-user">
        <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1)) }}</div>
        <div>
            <div class="sidebar-uname">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
            <div class="sidebar-urole">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav">

        <div class="nav-section-label">Mwelekeo</div>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i> <span>Dashboard</span>
        </a>

        @if(in_array(auth()->user()->role, ['teacher', 'head_teacher']))
        <a href="{{ route('attendance.index') }}"
           class="sidebar-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i> <span>Mahudhurio</span>
        </a>
        @endif

        <a href="{{ route('attendance.report') }}"
           class="sidebar-link {{ request()->routeIs('attendance.report') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> <span>Ripoti Yako</span>
        </a>

        @if(auth()->user()->role === 'teacher')
        <div class="nav-section-label">Shule</div>

        <a href="{{ route('teacher.register.school') }}"
           class="sidebar-link {{ request()->routeIs('teacher.register.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i>
            <span>{{ auth()->user()->school_id ? 'Hamisha Shule' : 'Jisajili Shule' }}</span>
        </a>
        @endif

        @if(auth()->user()->role === 'head_teacher')
        <div class="nav-section-label">Usimamizi</div>
        <a href="{{ route('headteacher.teachers') }}"
           class="sidebar-link {{ request()->routeIs('headteacher.teachers') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> <span>Walimu</span>
        </a>

        <a href="{{ route('headteacher.reports') }}"
           class="sidebar-link {{ request()->routeIs('headteacher.reports') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> <span>Ripoti ya Walimu</span>
        </a>

        <a href="{{ route('headteacher.approvals') }}"
           class="sidebar-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}">
            <i class="bi bi-check-circle-fill"></i> <span>Idhini za Walimu</span>
            @php $pendingCount = \App\Models\User::where('school_id', auth()->user()->school_id)->where('role','teacher')->where('status','pending')->count(); @endphp
            @if($pendingCount > 0)
            <span class="s-badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('schools.create') }}"
           class="sidebar-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">
            <i class="bi bi-building-fill-add"></i> <span>Sajili Shule</span>
        </a>
        @endif

        <div class="nav-section-label">Akaunti</div>

        <a href="{{ route('profile.edit') }}"
           class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> <span>Wasifu Wangu</span>
        </a>

    </nav>

    {{-- Logout --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i> <span>Toka</span>
            </button>
        </form>
    </div>
</aside>

{{-- ═══ MAIN ═══ --}}
<div class="main-wrap" id="mainWrap">

    {{-- Topbar --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()" aria-label="Menu">
                <i class="bi bi-list"></i>
            </button>
            <button class="collapse-btn" onclick="toggleCollapse()" aria-label="Collapse Sidebar">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="page-title-bar">
                <div class="pg-title">{{ $title ?? 'Dashboard' }}</div>
                @isset($subtitle)
                <div class="pg-sub">{{ $subtitle }}</div>
                @endisset
            </div>
        </div>
        <div class="topbar-right">
            <div class="topbar-date">
                <i class="bi bi-calendar3"></i>
                {{ now()->format('d M Y') }}
            </div>
            {{ $topbarActions ?? '' }}
        </div>
    </header>

    {{-- Body --}}
    <main class="page-body">
        @if(session('success'))
        <div class="flash-msg flash-success">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flash-msg flash-error">
            <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
        </div>
        @endif

        {{ $slot }}
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
{{ $scripts ?? '' }}
<script>
function toggleSidebar() {
    document.getElementById('appSidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
    document.body.style.overflow = document.getElementById('appSidebar').classList.contains('open') ? 'hidden' : '';
}
function closeSidebar() {
    document.getElementById('appSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('show');
    document.body.style.overflow = '';
}
function toggleCollapse() {
    const sidebar = document.getElementById('appSidebar');
    const btn = document.querySelector('.collapse-btn i');
    sidebar.classList.toggle('collapsed');
    btn.classList.toggle('bi-chevron-left');
    btn.classList.toggle('bi-chevron-right');
}
// Close sidebar on resize to desktop
window.addEventListener('resize', () => {
    if (window.innerWidth > 991) closeSidebar();
});
// Auto-dismiss flash
setTimeout(() => {
    document.querySelectorAll('.flash-msg').forEach(el => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 4000);
</script>
</body>
</html>