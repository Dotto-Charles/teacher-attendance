<x-ward-layout title="Teacher History">

<h1>{{ $user->full_name }}</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>Date</th>
        <th>Status</th>
        <th>Time</th>
    </tr>

    @foreach($records as $date => $att)
    <tr>
        <td>{{ $date }}</td>
        <td style="color:green">Present</td>
        <td>{{ $att->first()->created_at->format('H:i') }}</td>
    </tr>
    @endforeach
</table>

</x-ward-layout>