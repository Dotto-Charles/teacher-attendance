<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background:#f4f6f9; }

/* Sidebar */
.sidebar {
    width:250px;
    height:100vh;
    position:fixed;
    background:#0d6efd;
    color:white;
    padding-top:20px;
    transition:0.3s;
}

/* Hidden sidebar mobile */
.sidebar.hide {
    margin-left:-250px;
}

.sidebar a {
    color:white;
    display:block;
    padding:12px 20px;
    text-decoration:none;
}

.sidebar a:hover {
    background:rgba(255,255,255,0.2);
}

.main {
    margin-left:250px;
    padding:20px;
    transition:0.3s;
}

.main.full {
    margin-left:0;
}

/* Topbar */
.topbar {
    background:white;
    padding:10px;
    border-radius:10px;
    margin-bottom:20px;
}

/* Mobile */
@media(max-width:768px){
    .sidebar {
        margin-left:-250px;
    }
    .sidebar.show {
        margin-left:0;
    }
    .main {
        margin-left:0;
    }
}
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .main {
        margin-left: 0;
    }
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div id="sidebar" class="sidebar">

<h4 class="text-center">🏫 Attendance</h4>
<hr>

<a href="{{ route('dashboard') }}"><i class="bi bi-house"></i> Dashboard</a>

@if(auth()->user()->role === 'teacher')

<a href="{{ route('attendance.index') }}">
<i class="bi bi-calendar-check"></i> Attendance
</a>

<a href="{{ route('attendance.report') }}">
<i class="bi bi-bar-chart"></i> Reports
</a>

@endif

@if(auth()->user()->role === 'head_teacher')

<a href="{{ route('approvals.index') }}">
<i class="bi bi-check-circle"></i> Approvals
</a>
<!-- SCHOOL REGISTRATION -->
    <a href="{{ route('schools.create') }}"
       class="{{ request()->routeIs('schools.create') ? 'active' : '' }}">
        <i class="bi bi-building"></i> Register School
    </a>

<a href="{{ route('attendance.report') }}">
<i class="bi bi-bar-chart"></i> Reports
</a>

@endif

<a href="{{ route('profile.edit') }}">
<i class="bi bi-person"></i> Profile
</a>

@if(auth()->user()->role === 'teacher')

    <a href="{{ route('teacher.register.school') }}"
       class="{{ request()->routeIs('teacher.register.school') ? 'active' : '' }}">

        <i class="bi bi-arrow-left-right"></i>

        @if(auth()->user()->status === 'approved')
            Transfer School
        @else
            Register School
        @endif

    </a>

@endif

<form method="POST" action="{{ route('logout') }}" class="px-3">
@csrf
<button class="btn btn-danger w-100 mt-3">Logout</button>
</form>

</div>

<!-- MAIN -->
<div id="main" class="main">

<!-- TOPBAR -->
<div class="topbar d-flex justify-content-between align-items-center">

<button class="btn btn-primary d-md-none" onclick="toggleSidebar()">
☰
</button>

<h5>Welcome, {{ auth()->user()->first_name }}</h5>

</div>

{{ $slot }}

</div>

<script>
function toggleSidebar(){
    let sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}
</script>

</body>
</html>