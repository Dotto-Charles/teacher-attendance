<x-admin-layout title="Pending Users">

<div class="acard">

<div class="atable-wrap">

<table class="atable">

<thead>

<tr>
<th>Name</th>
<th>Email</th>
<th>Action</th>
</tr>

</thead>

<tbody>

@foreach($users as $user)

<tr>

<td>
{{ $user->first_name }}
{{ $user->last_name }}
</td>

<td>{{ $user->email }}</td>

<td>

<form
method="POST"
action="{{ route('admin.users.approve',$user) }}"
>
@csrf
@method('PUT')

<button class="abtn abtn-green">
Approve
</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</x-admin-layout>