<x-layout>

<div class="container-fluid">

<h3 class="mb-4">🧑‍🏫 Head Teacher Dashboard</h3>

<div class="row g-3">

    <div class="col-md-3">
        <div class="card p-3 text-center shadow-sm">
            <h6>Total Teachers</h6>
            <h3>{{ $totalTeachers }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 text-center shadow-sm">
            <h6>Pending</h6>
            <h3 class="text-warning">{{ $pending }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 text-center shadow-sm">
            <h6>Approved</h6>
            <h3 class="text-success">{{ $approved }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 text-center shadow-sm">
            <h6>Today Attendance</h6>
            <h3>{{ $todayAttendance }}</h3>
        </div>
    </div>

</div>

<hr class="my-4">

<div class="row">

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h5>👨‍🏫 Approve Teachers</h5>
            <a href="{{ route('approvals.index') }}" class="btn btn-success">
                View Requests
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h5>📊 Reports</h5>
            <a href="{{ route('attendance.report') }}" class="btn btn-dark">
                View Reports
            </a>
        </div>
    </div>

</div>

</div>

</x-layout>