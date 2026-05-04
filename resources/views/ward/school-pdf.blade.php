<h2>Ripoti ya Shule</h2>
<p>Kata: {{ $ward->name }}</p>
<p>Shule: {{ $school->name }}</p>
<p>Tarehe: {{ $today }}</p>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th>Jina</th>
        <th>Namba</th>
        <th>Status</th>
    </tr>

    @foreach($teachers as $t)
    <tr>
        <td>{{ $t->full_name }}</td>
        <td>{{ $t->check_number }}</td>
        <td>
            {{ $presentIds->contains($t->id) ? 'Present' : 'Absent' }}
        </td>
    </tr>
    @endforeach
</table>