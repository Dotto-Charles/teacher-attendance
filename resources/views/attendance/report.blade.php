<x-layout>

<div class="container-fluid">

<h4 class="mb-4">📊 Attendance Report</h4>

<!-- FILTER -->
<form class="row g-2 mb-4">

<input type="date" name="start" class="form-control col">
<input type="date" name="end" class="form-control col">

<select name="month" class="form-control col">
<option value="">Month</option>
@for($i=1;$i<=12;$i++)
<option value="{{ $i }}">{{ $i }}</option>
@endfor
</select>

<button class="btn btn-primary col">Filter</button>

</form>

<!-- STATS -->
<div class="row mb-4">

<div class="col-md-3">
<div class="card p-3 text-center">
<h6>Today</h6>
<h3>{{ $today }}</h3>
</div>
</div>

<div class="col-md-3">
<div class="card p-3 text-center">
<h6>This Month</h6>
<h3>{{ $month }}</h3>
</div>
</div>

@if($totalTeachers !== null)
<div class="col-md-3">
<div class="card p-3 text-center">
<h6>Total Teachers</h6>
<h3>{{ $totalTeachers }}</h3>
</div>
</div>
@endif

</div>

<!-- TABLE -->
<div class="card">

<div class="table-responsive">

<table class="table">

<thead>
<tr>
@if(auth()->user()->role === 'head_teacher')
<th>Teacher</th>
@endif
<th>Date</th>
<th>Time</th>
<th>Status</th>
</tr>
</thead>

<tbody>

@foreach($attendances as $att)

@php
$t = \Carbon\Carbon::parse($att->created_at);
$late = $t->format('H:i') > '08:00';
@endphp

<tr>

@if(auth()->user()->role === 'head_teacher')
<td>{{ $att->user->first_name ?? '' }}</td>
@endif

<td>{{ $t->format('d M Y') }}</td>
<td>{{ $t->format('H:i') }}</td>

<td>
@if($late)
<span class="badge bg-danger">Late</span>
@else
<span class="badge bg-success">On Time</span>
@endif
</td>

</tr>

@endforeach

</tbody>

</table>

</div>
</div>

</div>

</x-layout>