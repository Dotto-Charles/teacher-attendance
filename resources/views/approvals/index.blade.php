<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<title>Approvals</title>
</head>

<body class="bg-gray-100 p-4">

<div class="max-w-6xl mx-auto">

<h2 class="text-2xl font-bold mb-4">👨‍🏫 Pending Teachers</h2>

@if(session('success'))
<div class="bg-green-100 p-3 mb-3 rounded">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="bg-red-100 p-3 mb-3 rounded">{{ session('error') }}</div>
@endif

<div class="bg-white rounded shadow overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-200">
<tr>
<th class="p-3">Name</th>
<th class="p-3">School</th>
<th class="p-3">Requested At</th>
<th class="p-3 text-center">Action</th>
</tr>
</thead>

<tbody>

@forelse($teachers as $t)

<tr class="border-b">

<td class="p-3">
{{ $t->first_name }} {{ $t->last_name }}
</td>

<td class="p-3">
{{ $t->school->name ?? 'N/A' }}
</td>

<td class="p-3">
{{ $t->created_at->format('d M Y H:i') }}
</td>

<td class="p-3 text-center">

<form method="POST" action="{{ route('approvals.approve', $t->id) }}" class="inline">
@csrf
<button class="bg-green-600 text-white px-3 py-1 rounded">
Approve
</button>
</form>

<form method="POST" action="{{ route('approvals.reject', $t->id) }}" class="inline">
@csrf
<button class="bg-red-600 text-white px-3 py-1 rounded">
Reject
</button>
</form>

</td>

</tr>

@empty

<tr>
<td colspan="4" class="text-center p-4">
No pending teachers
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>

</body>
</html>