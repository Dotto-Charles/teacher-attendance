<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Location Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md md:max-w-2xl bg-white rounded-2xl shadow-lg p-6 md:p-8">

        <!-- Header -->
        <h1 class="text-xl md:text-2xl font-bold text-gray-700 mb-4 text-center">
            🏫 Set School Location
        </h1>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-3 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 mb-3 rounded text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="/schools/register" id="form" class="space-y-4">
            @csrf

            <!-- Council -->
            <div>
                <label class="text-sm text-gray-600">Council</label>
                <select id="council"
                        class="w-full mt-1 p-3 border rounded-lg focus:ring-2 focus:ring-blue-400"
                        required>
                    <option value="">Select Council</option>
                    @foreach($councils as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Ward -->
            <div>
                <label class="text-sm text-gray-600">Ward</label>
                <select id="ward"
                        class="w-full mt-1 p-3 border rounded-lg"
                        disabled required>
                    <option>Select Ward</option>
                </select>
            </div>

            <!-- School -->
            <div>
                <label class="text-sm text-gray-600">School</label>
                <select id="school" name="school_id"
                        class="w-full mt-1 p-3 border rounded-lg"
                        disabled required>
                    <option>Select School</option>
                </select>
            </div>

            <!-- Location Inputs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="text-sm text-gray-600">Latitude</label>
                    <input type="text" id="latitude" name="latitude"
                           class="w-full mt-1 p-3 border rounded-lg"
                           placeholder="e.g -6.12345" required>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Longitude</label>
                    <input type="text" id="longitude" name="longitude"
                           class="w-full mt-1 p-3 border rounded-lg"
                           placeholder="e.g 35.12345" required>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col md:flex-row gap-3">

                <!-- GPS -->
                <button type="button"
                        onclick="getLocation()"
                        class="w-full md:w-1/2 bg-gray-700 text-white py-3 rounded-lg hover:bg-gray-800 transition">
                    📍 Get GPS Location
                </button>

                <!-- Submit -->
                <button id="submitBtn"
                        class="w-full md:w-1/2 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                    💾 Save Location
                </button>

            </div>

        </form>
    </div>
</div>

<script>
// LOAD WARDS
document.getElementById('council').addEventListener('change', function () {
    fetch(`/api/wards/${this.value}`)
        .then(res => res.json())
        .then(data => {
            let ward = document.getElementById('ward');
            let school = document.getElementById('school');

            ward.disabled = false;
            ward.innerHTML = '<option>Select Ward</option>';

            school.disabled = true;
            school.innerHTML = '<option>Select Ward first</option>';

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
                let disabled = (s.latitude !== null || s.longitude !== null) ? 'disabled' : '';
                let label = (s.latitude !== null || s.longitude !== null)
                    ? `${s.name} (Locked)`
                    : s.name;

                school.innerHTML += `<option value="${s.id}" ${disabled}>${label}</option>`;
            });
        });
});

// GPS
function getLocation() {
    navigator.geolocation.getCurrentPosition(function(pos) {
        document.getElementById('latitude').value = pos.coords.latitude;
        document.getElementById('longitude').value = pos.coords.longitude;
    }, function() {
        alert("❌ Unable to get location");
    });
}

// VALIDATION
document.getElementById('form').addEventListener('submit', function(e) {
    let lat = document.getElementById('latitude').value;
    let lng = document.getElementById('longitude').value;

    if (!lat || !lng) {
        e.preventDefault();
        alert("❌ Please enter or fetch GPS location");
    }
});
</script>

</body>
</html>