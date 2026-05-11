<x-admin-layout title="Assign Roles">

<div class="acard">

<div class="atable-wrap">

<table class="atable">

<thead>

<tr>
<th>User</th>
<th>Current Role</th>
<th>Assign New Role</th>
</tr>

</thead>

<tbody>

@foreach($users as $user)

<tr>

<td>
{{ $user->first_name }}
{{ $user->last_name }}
</td>

<td>{{ $user->role }}</td>

<td>

<form
method="POST"
action="{{ route('admin.users.role',$user) }}"
style="display:flex;gap:8px"
>

@csrf
@method('PUT')

<select
name="role"
class="aform-select"
>

<option value="teacher">
Teacher
</option>

<option value="head_teacher">
Head Teacher
</option>

<option value="ward_officer">
Ward Officer
</option>

<option value="district_officer">
District Officer
</option>

</select>

<button class="abtn abtn-primary">
Save
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