<!DOCTYPE html>
<html lang="sw">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>{{ $title ?? config('app.name') }} · EduAttend</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root{
    --primary:#6366f1;
    --primary2:#4f46e5;
    --bg:#f1f5f9;
    --card:#ffffff;
    --text:#0f172a;
    --muted:#64748b;
    --radius:16px;
}

body{
    margin:0;
    font-family:system-ui, -apple-system, sans-serif;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
}

/* SIDEBAR */
.sidebar{
    position:fixed;
    top:0;left:0;bottom:0;
    width:250px;
    background:linear-gradient(180deg,var(--primary),var(--primary2));
    color:white;
    padding:20px;
    display:flex;
    flex-direction:column;
    transition:.3s;
    z-index:1000;
}

.brand{
    font-size:20px;
    font-weight:800;
    margin-bottom:25px;
}

.nav a{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px;
    border-radius:10px;
    color:#e0e7ff;
    text-decoration:none;
    margin-bottom:5px;
}

.nav a:hover{
    background:rgba(255,255,255,.15);
    color:white;
}

.nav a.active{
    background:white;
    color:var(--primary);
    font-weight:600;
}

/* MAIN */
.main{
    margin-left:250px;
    padding:20px;
}

/* TOPBAR */
.topbar{
    background:rgba(255,255,255,.7);
    backdrop-filter:blur(10px);
    padding:12px 20px;
    border-radius:var(--radius);
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

/* CARD */
.card-ui{
    background:white;
    border-radius:var(--radius);
    padding:18px;
    box-shadow:0 10px 25px rgba(0,0,0,.05);
}

/* MOBILE */
.hamburger{
    display:none;
    font-size:22px;
    background:none;
    border:none;
}

.overlay{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.4);
}

@media(max-width:991px){
    .sidebar{
        transform:translateX(-100%);
    }
    .sidebar.show{
        transform:translateX(0);
    }
    .main{
        margin-left:0;
    }
    .hamburger{
        display:block;
    }
    .overlay.show{
        display:block;
    }
}
</style>
</head>

<body>

<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

{{-- SIDEBAR --}}
<div class="sidebar" id="sidebar">

<div class="brand">🏫 EduAttend</div>

<div class="nav">

<a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard')?'active':'' }}">
<i class="bi bi-house"></i> Dashboard
</a>

<a href="{{ route('attendance.index') }}">
<i class="bi bi-check2-circle"></i> Attendance
</a>

<a href="{{ route('attendance.report') }}">
<i class="bi bi-bar-chart"></i> Reports
</a>

@if(auth()->user()->role=='teacher')
<a href="{{ route('teacher.register.school') }}">
<i class="bi bi-arrow-left-right"></i> Change School
</a>
@endif

<a href="{{ route('profile.edit') }}">
<i class="bi bi-person"></i> Profile
</a>

</div>

<div style="margin-top:auto">
<form method="POST" action="{{ route('logout') }}">
@csrf
<button class="btn btn-light w-100">Logout</button>
</form>
</div>

</div>

{{-- MAIN --}}
<div class="main">

<div class="topbar">
<div style="display:flex;align-items:center;gap:10px">
<button class="hamburger" onclick="toggleSidebar()">
<i class="bi bi-list"></i>
</button>
<strong>{{ $title }}</strong>
</div>

<div style="font-size:13px;color:gray">
{{ now()->format('d M Y') }}
</div>
</div>

{{-- FLASH --}}
@if(session('success'))
<div class="card-ui" style="background:#dcfce7;color:#166534">
{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="card-ui" style="background:#fee2e2;color:#991b1b">
{{ session('error') }}
</div>
@endif

{{-- CONTENT --}}
<div style="margin-top:15px">
{{ $slot }}
</div>

</div>

<script>
function toggleSidebar(){
document.getElementById('sidebar').classList.toggle('show');
document.getElementById('overlay').classList.toggle('show');
}
</script>

</body>
</html>