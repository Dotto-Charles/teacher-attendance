<x-layout>

<h3 class="mb-3">🏢 Ward Officer Dashboard</h3>

<!-- STATS -->
<div class="row g-3 mb-4">

    <div class="col-6 col-md-4">
        <div class="card p-3 text-center shadow-sm">
            <h6>🏫 Schools</h6>
            <h3>{{ $totalSchools }}</h3>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <div class="card p-3 text-center shadow-sm">
            <h6>👨‍🏫 Teachers</h6>
            <h3>{{ $totalTeachers }}</h3>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card p-3 text-center shadow-sm">
            <h6>📍 Today Attendance</h6>
            <h3>{{ $todayAttendance }}</h3>
        </div>
    </div>

</div>

<!-- FILTERS -->
<form method="GET" class="card p-3 mb-4 shadow-sm row g-2">

    <div class="col-md-3">
        <select name="school_id" class="form-control">
            <option value="">All Schools</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}">
                    {{ $school->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <select name="user_id" class="form-control">
            <option value="">All Teachers</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}">
                    {{ $teacher->full_name ?? $teacher->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <input type="date" name="start" class="form-control">
    </div>

    <div class="col-md-2">
        <input type="date" name="end" class="form-control">
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">Filter</button>
    </div>

</form>

<!-- TABLE -->
<div class="card shadow-sm">

<div class="table-responsive">

<table class="table table-bordered">

<thead class="table-light">
<tr>
    <th>Teacher</th>
    <th>School</th>
    <th>Date</th>
    <th>Time</th>
    <th>Status</th>
</tr>
</thead>

<tbody>

@forelse($attendances as $att)

@php
    $time = \Carbon\Carbon::parse($att->created_at);
    $late = $time->format('H:i') > '08:00';
@endphp

<tr>
    <td>{{ $att->user->full_name ?? $att->user->name }}</td>
    <td>{{ $att->school->name ?? '' }}</td>
    <td>{{ $time->format('d M Y') }}</td>
    <td>{{ $time->format('H:i') }}</td>

    <td>
        @if($late)
            <span class="badge bg-danger">Late</span>
        @else
            <span class="badge bg-success">On Time</span>
        @endif
    </td>
</tr>

@empty

<tr>
    <td colspan="5" class="text-center text-muted">
        No records found
    </td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>

</x-layout>