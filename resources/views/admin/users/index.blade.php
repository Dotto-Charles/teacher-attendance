<x-admin-layout title="All Users">

<div class="acard">

<div class="acard-header">

<form>

<div class="asearch-wrap">

<i class="bi bi-search asearch-icon"></i>

<input
type="text"
name="search"
placeholder="Search user..."
class="aform-input"
value="{{ request('search') }}"
>

</div>

</form>

</div>

<div class="atable-wrap">

<table class="atable">

<thead>
<tr>
<th>User</th>
<th>Email</th>
<th>Role</th>
<th>Status</th>
<th>Password</th>
<th>Actions</th>
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
<span class="abadge ab-blue">
{{ $user->role }}
</span>
</td>

<td>

@if($user->status == 'approved')
<span class="abadge ab-green">
Approved
</span>
@elseif($user->status == 'pending')
<span class="abadge ab-yellow">
Pending
</span>
@else
<span class="abadge ab-red">
Blocked
</span>
@endif

</td>

<td>

<div style="display:flex;gap:6px;flex-wrap:wrap">

<button
onclick="openModal('password{{ $user->id }}')"
class="abtn abtn-yellow abtn-sm"
>
Change
</button>

<form
method="POST"
action="{{ route('admin.users.reset.password',$user) }}"
>

@csrf
@method('PUT')

<button
onclick="return confirm('Reset password?')"
class="abtn abtn-red abtn-sm"
>
Reset
</button>

</form>

</div>

</td>

<td style="display:flex;gap:6px">

<button
onclick="openModal('edit{{ $user->id }}')"
class="abtn abtn-blue abtn-sm"
>
Edit
</button>

{{-- PASSWORD MODAL --}}

<div
class="amodal-bg"
id="password{{ $user->id }}"
onclick="closeBg(event,'password{{ $user->id }}')"
>

<div class="amodal">

<div class="amodal-header">

<div class="amodal-title">
Change Password
</div>

<button
class="amodal-close"
onclick="closeModal('password{{ $user->id }}')"
>
✕
</button>

</div>

<form
method="POST"
action="{{ route('admin.users.password',$user) }}"
>

@csrf
@method('PUT')

<div class="amodal-body">

<div class="aform-group">

<label class="aform-label">
New Password
</label>

<input
type="password"
name="password"
class="aform-input"
required
>

</div>

<div class="aform-group">

<label class="aform-label">
Confirm Password
</label>

<input
type="password"
name="password_confirmation"
class="aform-input"
required
>

</div>

</div>

<div class="amodal-footer">

<button
type="button"
onclick="closeModal('password{{ $user->id }}')"
class="abtn abtn-ghost"
>
Cancel
</button>

<button
type="submit"
class="abtn abtn-primary"
>
Update Password
</button>

</div>

</form>

</div>

</div>

<form
method="POST"
action="{{ route('admin.users.destroy',$user) }}"
>
@csrf
@method('DELETE')

<button
onclick="return confirm('Delete user?')"
class="abtn abtn-red abtn-sm"
>
Delete
</button>

</form>

</td>

</tr>

{{-- EDIT MODAL --}}

<div
class="amodal-bg"
id="edit{{ $user->id }}"
onclick="closeBg(event,'edit{{ $user->id }}')"
>

<div class="amodal">

<div class="amodal-header">

<div class="amodal-title">
Edit User
</div>

<button
class="amodal-close"
onclick="closeModal('edit{{ $user->id }}')"
>
✕
</button>

</div>

<form
method="POST"
action="{{ route('admin.users.update',$user) }}"
>

@csrf
@method('PUT')

<div class="amodal-body">

<div class="aform-group">

<label class="aform-label">
First Name
</label>

<input
type="text"
name="first_name"
value="{{ $user->first_name }}"
class="aform-input"
>

</div>

<div class="aform-group">

<label class="aform-label">
Last Name
</label>

<input
type="text"
name="last_name"
value="{{ $user->last_name }}"
class="aform-input"
>

</div>

<div class="aform-group">

<label class="aform-label">
Email
</label>

<input
type="email"
name="email"
value="{{ $user->email }}"
class="aform-input"
>

</div>

<div class="aform-group">

<label class="aform-label">
New Password
</label>

<input
type="text"
name="password"
class="aform-input"
>

</div>

</div>

<div class="amodal-footer">

<button
type="button"
onclick="closeModal('edit{{ $user->id }}')"
class="abtn abtn-ghost"
>
Cancel
</button>

<button
type="submit"
class="abtn abtn-primary"
>
Save Changes
</button>

</div>

</form>

</div>

</div>

@endforeach

</tbody>

</table>

</div>

<div class="apag-wrap">
{{ $users->links() }}
</div>

</div>

</x-admin-layout>