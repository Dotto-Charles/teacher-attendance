<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-6 rounded-xl shadow w-full max-w-md">

<h2 class="text-xl font-bold mb-4 text-center">
    {{ auth()->user()->status == 'approved' ? '🔄 Transfer School' : '🏫 Register School' }}
</h2>

@if(session('success'))
<div class="bg-green-100 p-3 mb-3 rounded">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="bg-red-100 p-3 mb-3 rounded">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('teacher.register.store') }}" class="space-y-4">
@csrf

<!-- Council -->
<select id="council" class="w-full p-3 border rounded">
    <option value="">Select Council</option>
    @foreach($councils as $c)
        <option value="{{ $c->id }}">{{ $c->name }}</option>
    @endforeach
</select>

<!-- Ward -->
<select id="ward" class="w-full p-3 border rounded" disabled>
    <option>Select Ward</option>
</select>

<!-- School -->
<select id="school" name="school_id" class="w-full p-3 border rounded" disabled>
    <option>Select School</option>
</select>

<button class="w-full bg-blue-600 text-white p-3 rounded">
    Submit
</button>

</form>

</div>

<script>

// LOAD WARDS
document.getElementById('council').addEventListener('change', function () {
    fetch(`/api/wards/${this.value}`)
    .then(res => res.json())
    .then(data => {
        let ward = document.getElementById('ward');
        ward.disabled = false;
        ward.innerHTML = '<option>Select Ward</option>';

        data.forEach(w => {
            ward.innerHTML += `<option value="${w.id}">${w.name}</option>`;
        });
    });
});

// LOAD SCHOOLS
document.getElementById('ward').addEventListener('change', function () {
    fetch(`/api/schools/${this.value}`)
    .then(res => res.json())
    .then(data => {
        let school = document.getElementById('school');
        school.disabled = false;
        school.innerHTML = '<option>Select School</option>';

        data.forEach(s => {
            school.innerHTML += `<option value="${s.id}">${s.name}</option>`;
        });
    });
});

</script>

</body>
</html>