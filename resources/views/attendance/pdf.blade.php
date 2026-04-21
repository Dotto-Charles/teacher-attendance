<!DOCTYPE html>
<html>
<head>
<style>
body { font-family: DejaVu Sans; }
table { width:100%; border-collapse: collapse; }
td, th { border:1px solid #000; padding:5px; }
</style>
</head>
<body>

<h2>Attendance Report</h2>

<table>
<tr>
<th>Date</th>
<th>Time</th>
<th>Status</th>
</tr>

@foreach($attendances as $att)
@php
$time = \Carbon\Carbon::parse($att->created_at);
@endphp

<tr>
<td>{{ $time->format('d M Y') }}</td>
<td>{{ $time->format('H:i') }}</td>
<td>{{ $time->format('H:i') > '08:00' ? 'Late' : 'On Time' }}</td>
</tr>
@endforeach

</table>

</body>
</html>