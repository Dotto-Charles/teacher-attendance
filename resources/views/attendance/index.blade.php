<x-layout>

<div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">

    <div class="bg-white p-4 p-md-5 rounded-xl shadow w-100" style="max-width: 420px;">

        <h3 class="text-center mb-3">📍 Attendance Check-in</h3>

        <p class="text-center text-muted mb-3">
            School:
            <b>{{ $school->name ?? 'Not assigned' }}</b>
        </p>

        <!-- STATUS -->
        <div id="result" class="text-center text-sm mb-4 text-secondary">
            Ready to check-in
        </div>

        <!-- BUTTON -->
        <button id="btn"
                onclick="checkIn()"
                class="btn btn-primary w-100 py-2">

            📍 Check In
        </button>

    </div>

</div>

<script>

function checkIn() {

    const btn = document.getElementById('btn');
    const result = document.getElementById('result');

    btn.disabled = true;
    btn.innerHTML = "⏳ Getting location...";
    result.innerHTML = "⏳ Requesting GPS...";

    if (!navigator.geolocation) {
        result.innerHTML = "❌ GPS not supported";
        reset();
        return;
    }

    let timeout = setTimeout(() => {
        result.innerHTML = "⛔ GPS timeout (try again)";
        reset();
    }, 10000);

    navigator.geolocation.getCurrentPosition(

        function (pos) {

            clearTimeout(timeout);

            let lat = pos.coords.latitude;
            let lng = pos.coords.longitude;

            result.innerHTML = "📍 Location received...";

            fetch("{{ route('attendance.check') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng
                })
            })
            .then(res => res.json())
            .then(data => {

                result.innerHTML = data.message;

                result.className = data.status === 'success'
                    ? "text-success text-center mb-3"
                    : "text-danger text-center mb-3";

                reset();
            })
            .catch(() => {
                result.innerHTML = "❌ Server error";
                reset();
            });
        },

        function (err) {

            clearTimeout(timeout);

            if (err.code === 1) {
                result.innerHTML = "❌ Please allow location permission";
            } else if (err.code === 2) {
                result.innerHTML = "❌ Location unavailable";
            } else {
                result.innerHTML = "❌ GPS error";
            }

            reset();
        },

        {
            enableHighAccuracy: false,
            timeout: 8000,
            maximumAge: 60000
        }
    );
}

function reset() {
    const btn = document.getElementById('btn');
    btn.disabled = false;
    btn.innerHTML = "📍 Check In";
}

</script>

</x-layout>