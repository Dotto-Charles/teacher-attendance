<x-layout title="Mahudhurio">

<div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">

    <div class="bg-white p-4 p-md-5 rounded-xl shadow w-100" style="max-width: 420px;">

        <h3 class="text-center mb-3">📍 Attendance Check-in</h3>

        <p class="text-center text-muted mb-3">
            School:
            <b>{{ $school->name ?? 'Not assigned' }}</b>
        </p>

        <!-- STATUS -->
        <div id="result" class="text-center mb-4 text-secondary" aria-live="polite" style="font-size: 1rem; line-height: 1.4;">
            Tayari kwa check-in
        </div>

        <!-- BUTTON -->
        <button id="btn"
                type="button"
                class="btn btn-primary w-100 py-3"
                style="font-size: 1.1rem; touch-action: manipulation;">

            📍 Check In
        </button>

    </div>

</div>

<script>
const btn = document.getElementById('btn');
const result = document.getElementById('result');
let checking = false;
const CHECKIN_URL = '{{ route('attendance.check') }}';
const GPS_TIMEOUT = 20000;

const updateResult = (text, type = 'secondary') => {
    result.textContent = text;
    result.className = `text-center text-sm mb-3 text-${type}`;
};

const setBusy = (message) => {
    checking = true;
    btn.disabled = true;
    btn.innerHTML = `⏳ ${message}`;
};

const reset = () => {
    checking = false;
    btn.disabled = false;
    btn.innerHTML = '📍 Check In';
};

const handleGeoError = (err) => {
    let message = '❌ GPS error. Jaribu tena.';
    if (err?.code === 1) {
        message = '❌ Ruhusu eneo kwenye browser.';
    } else if (err?.code === 2) {
        message = '❌ Eneo haipatikani. Jaribu tena nje au chukua location mpya.';
    } else if (err?.code === 3) {
        message = '❌ GPS imechoka. Jaribu tena.';
    }
    updateResult(message, 'danger');
    btn.innerHTML = '🔄 Jaribu Tena';
    reset();
};

const checkIn = async () => {
    if (checking) return;
    if (!navigator.geolocation) {
        updateResult('❌ GPS haipatikani kwenye kifaa hiki.', 'danger');
        return;
    }

    setBusy('Inapata eneo...');
    updateResult('⏳ Inapata eneo kwa GPS...', 'secondary');

    let timeoutHandle;
    const locationPromise = new Promise((resolve, reject) => {
        timeoutHandle = setTimeout(() => reject({ code: 3 }), GPS_TIMEOUT);
        navigator.geolocation.getCurrentPosition(resolve, reject, {
            enableHighAccuracy: true,
            timeout: GPS_TIMEOUT,
            maximumAge: 10000
        });
    });

    try {
        const pos = await locationPromise;
        clearTimeout(timeoutHandle);

        const latitude = pos.coords.latitude;
        const longitude = pos.coords.longitude;
        const accuracy = Math.round(pos.coords.accuracy);

        updateResult(`📍 Eneo limepatikana (±${accuracy}m). Inatumia sasa...`, 'secondary');

        const response = await fetch(CHECKIN_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ latitude, longitude, accuracy }),
            keepalive: true
        });

        const data = await response.json();
        const success = data.status === 'success' || data.success === true;

        updateResult(data.message || (success ? '✅ Attendance imechukuliwa' : '❌ Tatizo limejitokeza'), success ? 'success' : 'danger');
    } catch (error) {
        if (error?.code) {
            handleGeoError(error);
        } else {
            updateResult('❌ Tatizo la mtandao au server. Jaribu tena.', 'danger');
            btn.innerHTML = '🔄 Jaribu Tena';
            reset();
        }
    }
};

btn.addEventListener('click', checkIn);
</script>

</x-layout>