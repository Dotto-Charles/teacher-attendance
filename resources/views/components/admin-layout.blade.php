{{-- resources/views/components/admin-layout.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} · EduAttend Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    {{ $styles ?? '' }}
    <style>
        :root {
            --bg:        #070a0f;
            --surface:   #0d1117;
            --surface2:  #161b22;
            --surface3:  #1c2128;
            --border:    #21262d;
            --border2:   #30363d;
            --accent:    #f97316;
            --accent2:   #fb923c;
            --blue:      #3b82f6;
            --green:     #22c55e;
            --red:       #ef4444;
            --yellow:    #eab308;
            --purple:    #a855f7;
            --pink:      #ec4899;
            --text:      #e6edf3;
            --text2:     #8b949e;
            --text3:     #484f58;
            --font:      'Space Grotesk', sans-serif;
            --mono:      'JetBrains Mono', monospace;
            --r:         10px;
            --r-sm:      6px;
            --sidebar-w: 256px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: var(--font); background: var(--bg); color: var(--text); min-height: 100vh; }

        /* ── SIDEBAR ── */
        .admin-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 200;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }

        .sb-header {
            padding: 20px 18px 16px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .sb-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .sb-brand-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px; flex-shrink: 0;
            box-shadow: 0 0 16px rgba(249,115,22,.3);
        }
        .sb-brand-name { font-size: 16px; font-weight: 700; color: var(--text); }
        .sb-brand-sub  { font-size: 10px; color: var(--accent); font-weight: 600; letter-spacing: .8px; text-transform: uppercase; }

        .sb-user {
            margin: 12px 14px 0;
            padding: 10px 12px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--r);
            display: flex; align-items: center; gap: 10px;
        }
        .sb-av {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--accent), #c2410c);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .sb-uname { font-size: 13px; font-weight: 600; color: var(--text); line-height: 1.2; }
        .sb-urole { font-size: 10px; color: var(--accent); font-weight: 600; letter-spacing: .5px; text-transform: uppercase; }

        .sb-nav { flex: 1; overflow-y: auto; padding: 12px 10px; scrollbar-width: none; }
        .sb-nav::-webkit-scrollbar { display: none; }

        .sb-section-label {
            font-size: 9px; font-weight: 700; color: var(--text3);
            letter-spacing: 1.4px; text-transform: uppercase;
            padding: 0 10px; margin: 16px 0 6px; display: block;
        }

        .sb-link {
            display: flex; align-items: center; gap: 9px;
            padding: 8px 10px;
            border-radius: var(--r-sm);
            color: var(--text2);
            text-decoration: none;
            font-size: 13.5px; font-weight: 500;
            transition: all .15s;
            margin-bottom: 1px;
            position: relative;
        }
        .sb-link i { width: 17px; text-align: center; font-size: 14px; flex-shrink: 0; }
        .sb-link:hover { background: var(--surface2); color: var(--text); }
        .sb-link.active { background: rgba(249,115,22,.1); color: var(--accent); font-weight: 600; }
        .sb-link.active::before {
            content: ''; position: absolute; left: 0; top: 25%; bottom: 25%;
            width: 2px; border-radius: 99px; background: var(--accent);
        }
        .sb-badge {
            margin-left: auto; background: var(--red); color: #fff;
            font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 99px;
            font-family: var(--mono);
        }
        .sb-badge.green { background: var(--green); }
        .sb-badge.blue  { background: var(--blue); }

        .sb-footer { padding: 12px; border-top: 1px solid var(--border); flex-shrink: 0; }
        .sb-logout {
            width: 100%; padding: 9px; border-radius: var(--r);
            background: rgba(239,68,68,.08);
            border: 1px solid rgba(239,68,68,.2);
            color: #f87171; font-size: 13px; font-weight: 600;
            cursor: pointer; font-family: var(--font);
            display: flex; align-items: center; justify-content: center; gap: 7px;
            transition: all .15s;
        }
        .sb-logout:hover { background: rgba(239,68,68,.18); color: #fff; }

        /* ── MAIN ── */
        .admin-main { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

        /* ── TOPBAR ── */
        .admin-topbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(7,10,15,.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 12px 28px;
            display: flex; align-items: center; justify-content: space-between; gap: 14px;
        }
        .at-left { display: flex; align-items: center; gap: 12px; }
        .at-right { display: flex; align-items: center; gap: 10px; }
        .hamburger { display: none; background: none; border: none; color: var(--text); font-size: 20px; cursor: pointer; padding: 4px; border-radius: 6px; }
        .hamburger:hover { background: var(--surface2); }
        .at-title { font-size: 16px; font-weight: 700; color: var(--text); }
        .at-breadcrumb { font-size: 12px; color: var(--text2); margin-top: 1px; }

        /* ── PAGE CONTENT ── */
        .admin-content { padding: 24px 28px; flex: 1; }

        /* ── FLASH ── */
        .admin-flash {
            padding: 11px 16px; border-radius: var(--r); font-size: 13px;
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 18px; animation: flashIn .3s ease;
        }
        .flash-ok  { background: rgba(34,197,94,.08); border: 1px solid rgba(34,197,94,.25); color: #4ade80; }
        .flash-err { background: rgba(239,68,68,.08);  border: 1px solid rgba(239,68,68,.25);  color: #f87171; }
        @keyframes flashIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }

        /* ── STAT CARDS ── */
        .admin-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 14px; margin-bottom: 24px; }
        .astat {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: 18px;
            position: relative; overflow: hidden;
            transition: border-color .2s, transform .2s;
        }
        .astat:hover { border-color: var(--border2); transform: translateY(-2px); }
        .astat::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px; }
        .astat.ao::after  { background: var(--accent); }
        .astat.ab::after  { background: var(--blue); }
        .astat.ag::after  { background: var(--green); }
        .astat.ay::after  { background: var(--yellow); }
        .astat.ar::after  { background: var(--red); }
        .astat.ap::after  { background: var(--purple); }
        .astat-icon { font-size: 20px; margin-bottom: 10px; }
        .astat-val  { font-size: 30px; font-weight: 700; font-family: var(--mono); line-height: 1; color: var(--text); }
        .astat-lbl  { font-size: 11px; color: var(--text2); margin-top: 5px; font-weight: 500; }

        /* ── CARD ── */
        .acard { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden; margin-bottom: 20px; }
        .acard-header { padding: 14px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        .acard-title { font-size: 14px; font-weight: 700; color: var(--text); }
        .acard-sub   { font-size: 12px; color: var(--text2); margin-top: 2px; }
        .acard-body  { padding: 18px; }

        /* ── BUTTONS ── */
        .abtn { padding: 7px 14px; border-radius: var(--r-sm); font-size: 12px; font-weight: 600; border: none; cursor: pointer; font-family: var(--font); transition: all .15s; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; }
        .abtn-primary { background: var(--accent); color: #fff; }
        .abtn-primary:hover { background: var(--accent2); }
        .abtn-ghost  { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
        .abtn-ghost:hover { background: var(--surface3); }
        .abtn-green  { background: rgba(34,197,94,.1); color: #4ade80; border: 1px solid rgba(34,197,94,.25); }
        .abtn-green:hover { background: rgba(34,197,94,.2); }
        .abtn-red    { background: rgba(239,68,68,.1); color: #f87171; border: 1px solid rgba(239,68,68,.25); }
        .abtn-red:hover { background: rgba(239,68,68,.2); }
        .abtn-yellow { background: rgba(234,179,8,.1); color: #facc15; border: 1px solid rgba(234,179,8,.25); }
        .abtn-yellow:hover { background: rgba(234,179,8,.2); }
        .abtn-blue   { background: rgba(59,130,246,.1); color: #60a5fa; border: 1px solid rgba(59,130,246,.25); }
        .abtn-blue:hover { background: rgba(59,130,246,.2); }
        .abtn-sm { padding: 4px 10px; font-size: 11px; }

        /* ── TABLE ── */
        .atable-wrap { overflow-x: auto; }
        .atable { width: 100%; border-collapse: collapse; font-size: 13px; }
        .atable thead th { padding: 10px 14px; text-align: left; font-size: 10px; font-weight: 700; color: var(--text2); text-transform: uppercase; letter-spacing: .9px; background: var(--surface2); border-bottom: 1px solid var(--border); white-space: nowrap; }
        .atable tbody td { padding: 11px 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        .atable tbody tr:last-child td { border-bottom: none; }
        .atable tbody tr:hover td { background: rgba(255,255,255,.02); }

        /* ── BADGES ── */
        .abadge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 99px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
        .ab-green  { background: rgba(34,197,94,.1);  color: #4ade80; border: 1px solid rgba(34,197,94,.2); }
        .ab-yellow { background: rgba(234,179,8,.1);  color: #facc15; border: 1px solid rgba(234,179,8,.2); }
        .ab-red    { background: rgba(239,68,68,.1);  color: #f87171; border: 1px solid rgba(239,68,68,.2); }
        .ab-blue   { background: rgba(59,130,246,.1); color: #60a5fa; border: 1px solid rgba(59,130,246,.2); }
        .ab-orange { background: rgba(249,115,22,.1); color: #fb923c; border: 1px solid rgba(249,115,22,.2); }
        .ab-purple { background: rgba(168,85,247,.1); color: #c084fc; border: 1px solid rgba(168,85,247,.2); }
        .ab-gray   { background: rgba(139,148,158,.1); color: var(--text2); border: 1px solid rgba(139,148,158,.2); }

        /* ── AVATAR ── */
        .aav { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0; }

        /* ── FORM ── */
        .aform-group { margin-bottom: 16px; }
        .aform-label { display: block; font-size: 11px; font-weight: 600; color: var(--text2); text-transform: uppercase; letter-spacing: .7px; margin-bottom: 6px; }
        .aform-input, .aform-select, .aform-textarea {
            width: 100%; padding: 9px 12px;
            background: var(--surface2); border: 1px solid var(--border);
            color: var(--text); border-radius: var(--r-sm);
            font-size: 13px; font-family: var(--font); outline: none;
            transition: border-color .15s;
        }
        .aform-input:focus, .aform-select:focus, .aform-textarea:focus { border-color: var(--accent); }
        .aform-textarea { resize: vertical; min-height: 80px; }
        .aform-hint { font-size: 11px; color: var(--text2); margin-top: 4px; }
        .aform-error { font-size: 11px; color: #f87171; margin-top: 4px; }
        .aform-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .aform-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }

        /* ── MODAL ── */
        .amodal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.75); backdrop-filter: blur(4px); z-index: 300; align-items: center; justify-content: center; padding: 16px; }
        .amodal-bg.open { display: flex; }
        .amodal { background: var(--surface); border: 1px solid var(--border2); border-radius: 14px; width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto; animation: modalIn .2s ease; }
        .amodal-lg { max-width: 720px; }
        @keyframes modalIn { from{opacity:0;transform:scale(.96) translateY(8px)} to{opacity:1;transform:scale(1) translateY(0)} }
        .amodal-header { padding: 18px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .amodal-title  { font-size: 15px; font-weight: 700; }
        .amodal-close  { background: none; border: none; color: var(--text2); cursor: pointer; font-size: 17px; padding: 4px; border-radius: 6px; transition: all .15s; }
        .amodal-close:hover { background: var(--surface2); color: var(--text); }
        .amodal-body   { padding: 20px; }
        .amodal-footer { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; gap: 8px; justify-content: flex-end; }

        /* ── TABS ── */
        .atabs { display: flex; gap: 2px; background: var(--surface2); border-radius: var(--r-sm); padding: 3px; margin-bottom: 20px; overflow-x: auto; }
        .atab  { padding: 8px 16px; border-radius: 5px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; font-family: var(--font); transition: all .15s; color: var(--text2); background: transparent; white-space: nowrap; display: flex; align-items: center; gap: 6px; text-decoration: none; }
        .atab:hover { color: var(--text); }
        .atab.active { background: var(--surface); color: var(--text); box-shadow: 0 1px 6px rgba(0,0,0,.4); }

        /* ── SEARCH ── */
        .asearch-wrap { position: relative; }
        .asearch-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text2); font-size: 13px; pointer-events: none; }
        .asearch-wrap .aform-input { padding-left: 32px; }

        /* ── PAGINATION ── */
        .apag-wrap { padding: 12px 18px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        .apag-info { font-size: 12px; color: var(--text2); }
        .apag { display: flex; gap: 3px; }
        .apag a, .apag span { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: var(--r-sm); font-size: 12px; text-decoration: none; border: 1px solid var(--border); color: var(--text2); transition: all .15s; }
        .apag a:hover { background: var(--surface2); color: var(--text); }
        .apag .cur { background: var(--accent); border-color: var(--accent); color: #fff; font-weight: 700; }

        /* ── OVERLAY ── */
        .aoverlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.6); z-index: 199; }
        .aoverlay.show { display: block; }

        /* ── EMPTY ── */
        .aempty { text-align: center; padding: 48px 20px; color: var(--text2); }
        .aempty i { font-size: 36px; margin-bottom: 12px; display: block; opacity: .4; }
        .aempty p { font-size: 13px; }

        /* ── PROGRESS ── */
        .aprog-bg { height: 4px; background: var(--surface3); border-radius: 99px; overflow: hidden; }
        .aprog    { height: 100%; border-radius: 99px; }

        /* ── RESPONSIVE ── */
        @media(max-width: 991px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
            .aoverlay.show { display: block; }
            .admin-main { margin-left: 0; }
            .hamburger { display: block; }
            .admin-content { padding: 14px; }
            .admin-topbar { padding: 10px 14px; }
            .admin-stats { grid-template-columns: repeat(2,1fr); gap: 10px; }
            .aform-grid-2, .aform-grid-3 { grid-template-columns: 1fr; }
        }
        @media(max-width: 480px) {
            .admin-stats { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<div class="aoverlay" id="aOverlay" onclick="closeSidebar()"></div>

{{-- ═══ SIDEBAR ═══ --}}
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sb-header">
        <a href="{{ route('admin.dashboard') }}" class="sb-brand">
            <div class="sb-brand-icon">⚡</div>
            <div>
                <div class="sb-brand-name">EduAttend</div>
                <div class="sb-brand-sub">Admin Panel</div>
            </div>
        </a>
    </div>

    <div class="sb-user">
        <div class="sb-av">{{ strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1)) }}</div>
        <div>
            <div class="sb-uname">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
            <div class="sb-urole">Administrator</div>
        </div>
    </div>

    <nav class="sb-nav">
        @php
            $pendingUsers  = \App\Models\User::where('status','pending')->count();
            $totalUsers    = \App\Models\User::count();
        @endphp

        <span class="sb-section-label">Mwelekeo</span>
        <a href="{{ route('admin.dashboard') }}"
           class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active':'' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <span class="sb-section-label">Watumiaji</span>
        <a href="{{ route('admin.users.index') }}"
           class="sb-link {{ request()->routeIs('admin.users.*') ? 'active':'' }}">
            <i class="bi bi-people-fill"></i> Watumiaji Wote
            <span class="sb-badge blue">{{ $totalUsers }}</span>
        </a>
        <a href="{{ route('admin.users.pending') }}"
           class="sb-link {{ request()->routeIs('admin.users.pending') ? 'active':'' }}">
            <i class="bi bi-person-exclamation"></i> Wanasubiri Idhini
            @if($pendingUsers > 0)
            <span class="sb-badge">{{ $pendingUsers }}</span>
            @endif
        </a>
        <a href="{{ route('admin.users.roles') }}"
           class="sb-link {{ request()->routeIs('admin.users.roles') ? 'active':'' }}">
            <i class="bi bi-shield-shaded"></i> Assign Roles
        </a>

        <span class="sb-section-label">Mfumo</span>
        <a href="{{ route('admin.schools.index') }}"
           class="sb-link {{ request()->routeIs('admin.schools.*') ? 'active':'' }}">
            <i class="bi bi-building"></i> Shule
        </a>
        <a href="{{ route('admin.reports') }}"
           class="sb-link {{ request()->routeIs('admin.reports') ? 'active':'' }}">
            <i class="bi bi-bar-chart-fill"></i> Taarifa za Mfumo
        </a>
        <a href="{{ route('admin.activity') }}"
           class="sb-link {{ request()->routeIs('admin.activity') ? 'active':'' }}">
            <i class="bi bi-activity"></i> Shughuli za Hivi Karibuni
        </a>

        <span class="sb-section-label">Akaunti</span>
        <a href="{{ route('profile.edit') }}"
           class="sb-link {{ request()->routeIs('profile.*') ? 'active':'' }}">
            <i class="bi bi-person-circle"></i> Wasifu Wangu
        </a>
    </nav>

    <div class="sb-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-logout">
                <i class="bi bi-box-arrow-right"></i> Toka
            </button>
        </form>
    </div>
</aside>

{{-- ═══ MAIN ═══ --}}
<div class="admin-main">
    <header class="admin-topbar">
        <div class="at-left">
            <button class="hamburger" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
            <div>
                <div class="at-title">{{ $title ?? 'Dashboard' }}</div>
                @isset($breadcrumb)
                <div class="at-breadcrumb">{{ $breadcrumb }}</div>
                @endisset
            </div>
        </div>
        <div class="at-right">
            @if(isset($pendingUsers) && $pendingUsers > 0)
            <a href="{{ route('admin.users.pending') }}"
               style="display:flex;align-items:center;gap:6px;padding:5px 12px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:99px;font-size:12px;font-weight:600;color:#f87171;text-decoration:none">
                <i class="bi bi-bell-fill" style="font-size:11px"></i>
                {{ $pendingUsers }} wanasubiri
            </a>
            @endif
            {{ $actions ?? '' }}
        </div>
    </header>

    <main class="admin-content">
        @if(session('success'))
        <div class="admin-flash flash-ok">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="admin-flash flash-err">
            <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
        </div>
        @endif

        {{ $slot }}
    </main>
</div>

{{ $scripts ?? '' }}
@stack('scripts')
<script>
function toggleSidebar(){
    document.getElementById('adminSidebar').classList.toggle('open');
    document.getElementById('aOverlay').classList.toggle('show');
    document.body.style.overflow = document.getElementById('adminSidebar').classList.contains('open') ? 'hidden':'';
}
function closeSidebar(){
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('aOverlay').classList.remove('show');
    document.body.style.overflow = '';
}
function openModal(id){ document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id){ document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
function closeBg(e,id){ if(e.target===document.getElementById(id)) closeModal(id); }
window.addEventListener('resize', ()=>{ if(window.innerWidth>991) closeSidebar(); });
setTimeout(()=>{
    document.querySelectorAll('.admin-flash').forEach(el=>{
        el.style.transition='opacity .5s'; el.style.opacity='0';
        setTimeout(()=>el.remove(), 500);
    });
}, 4000);
</script>
</body>
</html>