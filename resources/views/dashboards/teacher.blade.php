<x-layout>

<!-- HEADER -->
<div class="mb-4">

    <h3 class="fw-bold">👨‍🏫 Teacher Dashboard</h3>

    <p class="text-muted mb-0">
        Welcome back, {{ auth()->user()->first_name ?? auth()->user()->name }}
    </p>

</div>

<!-- QUICK STATS -->
<div class="row g-3 mb-4">

    <div class="col-6 col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5>📅 Today</h5>
                <h3 class="text-primary">{{ $today ?? 0 }}</h3>
                <small class="text-muted">Attendance</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5>📊 Month</h5>
                <h3 class="text-success">{{ $month ?? 0 }}</h3>
                <small class="text-muted">Records</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5>📌 Status</h5>

                <span class="badge bg-{{ auth()->user()->status === 'approved' ? 'success' : 'warning' }} fs-6">
                    {{ auth()->user()->status }}
                </span>

                <div class="mt-2 text-muted small">
                    Account status
                </div>

            </div>
        </div>
    </div>

</div>

<!-- MAIN ACTION CARDS -->
<div class="row g-3">

    <!-- ATTENDANCE -->
    <div class="col-12 col-md-4">
        <div class="card shadow-sm border-0 h-100 hover-card">

            <div class="card-body text-center p-4">

                <div class="display-6 mb-2">📍</div>

                <h5 class="fw-bold">Attendance</h5>

                <p class="text-muted small">
                    Mark your daily attendance using GPS
                </p>

                <a href="{{ route('attendance.index') }}" class="btn btn-primary w-100">
                    Start Check-in
                </a>

            </div>

        </div>
    </div>

    <!-- REPORTS -->
    <div class="col-12 col-md-4">
        <div class="card shadow-sm border-0 h-100 hover-card">

            <div class="card-body text-center p-4">

                <div class="display-6 mb-2">📊</div>

                <h5 class="fw-bold">Reports</h5>

                <p class="text-muted small">
                    View your attendance history
                </p>

                <a href="{{ route('attendance.report') }}" class="btn btn-success w-100">
                    View Reports
                </a>

            </div>

        </div>
    </div>

    <!-- PROFILE -->
    <div class="col-12 col-md-4">
        <div class="card shadow-sm border-0 h-100 hover-card">

            <div class="card-body text-center p-4">

                <div class="display-6 mb-2">👤</div>

                <h5 class="fw-bold">Profile</h5>

                <p class="text-muted small">
                    Manage your account details
                </p>

                <a href="{{ route('profile.edit') }}" class="btn btn-info w-100 text-white">
                    Open Profile
                </a>

            </div>

        </div>
    </div>

</div>

<!-- TEACHER INFO -->
<div class="mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h5 class="fw-bold mb-3">👨‍🏫 Teacher Information</h5>

            <div class="row">

                <div class="col-12 col-md-4 mb-2">
                    <b>Name:</b><br>
                    <span class="text-muted">
                        {{ auth()->user()->full_name ?? auth()->user()->name }}
                    </span>
                </div>

                <div class="col-12 col-md-4 mb-2">
                    <b>Role:</b><br>
                    <span class="text-muted">{{ auth()->user()->role }}</span>
                </div>

                <div class="col-12 col-md-4 mb-2">
                    <b>Status:</b><br>

                    <span class="badge bg-{{ auth()->user()->status === 'approved' ? 'success' : 'warning' }}">
                        {{ auth()->user()->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- STYLE -->
<style>
.hover-card {
    transition: 0.3s ease;
    border-radius: 12px;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
</style>

</x-layout>