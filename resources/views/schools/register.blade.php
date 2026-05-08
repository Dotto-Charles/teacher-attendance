{{-- resources/views/schools/create.blade.php --}}
<x-layout title="Weka Eneo la Shule" subtitle="{{ $school->name }}">

<x-slot name="styles">
<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap');
.sl-wrap { font-family:'Sora',sans-serif; max-width: 680px; margin: 0 auto; }

/* ── SCHOOL HERO ── */
.school-hero {
    background: linear-gradient(135deg, #0f172a, #1e3a5f);
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid rgba(59,130,246,.2);
    position: relative; overflow: hidden;
}
.school-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background: radial-gradient(ellipse at 80% 50%, rgba(59,130,246,.15) 0%, transparent 60%);
}
.sh-inner { position: relative; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
.sh-icon { width: 56px; height: 56px; border-radius: 14px; background: rgba(255,255,255,.1); display: flex; align-items: center; justify-content: center; font-size: 26px; flex-shrink: 0; }
.sh-name { font-size: 18px; font-weight: 800; color: #fff; line-height: 1.2; }
.sh-ward { font-size: 13px; color: rgba(255,255,255,.55); margin-top: 4px; }
.sh-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-top: 8px; }
.sh-has-gps { background: rgba(16,185,129,.2); border: 1px solid rgba(16,185,129,.3); color: #6ee7b7; }
.sh-no-gps  { background: rgba(245,158,11,.15); border: 1px solid rgba(245,158,11,.3); color: #fcd34d; }

/* ── GPS STATUS CARD ── */
.gps-status-card {
    background: #fff;
    border: 2px solid #e2e8f0;
    border-radius: 18px;
    padding: 20px;
    margin-bottom: 20px;
    transition: border-color .3s;
}
.gps-status-card.finding  { border-color: #bfdbfe; }
.gps-status-card.found    { border-color: #6ee7b7; }
.gps-status-card.error    { border-color: #fca5a5; }

.gps-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 8px; }
.gps-title { font-size: 14px; font-weight: 700; color: #1e293b; }
.gps-pill { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.gp-idle    { background: #f8fafc; color: #94a3b8; }
.gp-finding { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.gp-found   { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.gp-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

/* ── COORDS DISPLAY ── */
.coords-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
.coord-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; }
.coord-label { font-size: 10px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .7px; margin-bottom: 4px; }
.coord-val { font-size: 16px; font-weight: 700; font-family: 'DM Mono', monospace; color: #1e293b; }
.coord-val.active { color: #0d6efd; }

/* ── ACCURACY BAR ── */
.acc-row { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; font-size: 12px; color: #64748b; }
.acc-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.acc-good   { background: #10b981; }
.acc-medium { background: #f59e0b; }
.acc-bad    { background: #ef4444; }

/* ── BIG GPS BUTTON ── */
.btn-gps {
    width: 100%; padding: 14px;
    border-radius: 14px; border: none; cursor: pointer;
    font-size: 15px; font-weight: 700; font-family: 'Sora', sans-serif;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all .25s; margin-bottom: 10px;
}
.btn-gps-primary { background: linear-gradient(135deg, #0d6efd, #6366f1); color: #fff; box-shadow: 0 6px 18px rgba(13,110,253,.3); }
.btn-gps-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(13,110,253,.4); }
.btn-gps-watching { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
.btn-gps-disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }

/* ── FORM CARD ── */
.form-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 22px; margin-bottom: 20px; }
.form-section-title { font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 7px; }
.form-label-s { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .7px; display: block; margin-bottom: 6px; }
.form-ctrl { width: 100%; padding: 11px 14px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-family: 'Sora', sans-serif; color: #1e293b; outline: none; transition: border-color .2s; background: #fff; }
.form-ctrl:focus { border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13,110,253,.08); }
.form-ctrl.has-val { border-color: #10b981; background: #f0fdf4; }
.form-hint { font-size: 11px; color: #94a3b8; margin-top: 5px; }

/* ── RADIUS SLIDER ── */
.radius-wrap { display: flex; align-items: center; gap: 12px; }
.radius-slider { flex: 1; accent-color: #0d6efd; }
.radius-display { width: 70px; text-align: center; padding: 8px; background: #eff6ff; border-radius: 10px; font-weight: 700; font-family: 'DM Mono', monospace; font-size: 14px; color: #0d6efd; flex-shrink: 0; }

/* ── SAVE BUTTON ── */
.btn-save {
    width: 100%; padding: 15px;
    border-radius: 14px; border: none; cursor: pointer;
    font-size: 16px; font-weight: 700; font-family: 'Sora', sans-serif;
    background: linear-gradient(135deg, #0d6efd, #6366f1);
    color: #fff; box-shadow: 0 6px 18px rgba(13,110,253,.3);
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all .25s;
}
.btn-save:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(13,110,253,.4); }
.btn-save:disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; box-shadow: none; transform: none; }

/* ── EXISTING LOCATION ── */
.existing-box {
    background: #f0fdf4; border: 1px solid #bbf7d0;
    border-radius: 14px; padding: 16px; margin-bottom: 16px;
}
.existing-title { font-size: 13px; font-weight: 700; color: #166534; margin-bottom: 10px; }
.existing-coords { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.ex-coord { font-size: 12px; color: #166534; }
.ex-coord span { display: block; font-family: 'DM Mono', monospace; font-size: 14px; font-weight: 700; }

/* ── TOAST ── */
.toast-wrap { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; pointer-events: none; }
.toast2 { padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; min-width: 250px; max-width: 320px; pointer-events: all; box-shadow: 0 8px 24px rgba(0,0,0,.12); animation: tSlide .3s ease; }
.t2-ok  { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.t2-err { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
.t2-inf { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; }
@keyframes tSlide { from{opacity:0;transform:translateX(16px)} to{opacity:1;transform:translateX(0)} }
@keyframes spin { to { transform: rotate(360deg); } }

/* RESPONSIVE */
@media(max-width: 576px) {
    .coords-grid { grid-template-columns: 1fr; }
    .existing-coords { grid-template-columns: 1fr; }
}
</style>
</x-slot>

<div class="sl-wrap">
<div class="toast-wrap" id="toastWrap"></div>

{{-- SCHOOL HERO --}}
<div class="school-hero">
    <div class="sh-inner">
        <div class="sh-icon">🏫</div>
        <div>
            <div class="sh-name">{{ $school->name }}</div>
            <div class="sh-ward">
                📍 {{ $school->ward->name ?? '—' }}
                @if($school->ward->council ?? null)
                · {{ $school->ward->council->name }}
                @endif
            </div>
            @if($school->latitude && $school->longitude)
            <span class="sh-badge sh-has-gps">
                <i class="bi bi-geo-alt-fill"></i> GPS Imewekwa
            </span>
            @else
            <span class="sh-badge sh-no-gps">
                <i class="bi bi-exclamation-triangle"></i> GPS Haijawekwa Bado
            </span>
            @endif
        </div>
    </div>
</div>

{{-- EXISTING LOCATION (ikiwepo) --}}
@if($school->latitude && $school->longitude)
<div class="existing-box">
    <div class="existing-title">✅ Eneo la Sasa</div>
    <div class="existing-coords">
        <div class="ex-coord">Latitude <span>{{ $school->latitude }}</span></div>
        <div class="ex-coord">Longitude <span>{{ $school->longitude }}</span></div>
        <div class="ex-coord">Radius <span>{{ $school->radius }}m</span></div>
    </div>
    <div style="font-size:11px;color:#166534;margin-top:8px">
        Unaweza kusasisha eneo kwa kujaza fomu hapa chini.
    </div>
</div>
@endif

{{-- GPS AUTO-DETECT CARD --}}
<div class="gps-status-card" id="gpsCard">
    <div class="gps-top">
        <div class="gps-title">📍 Pata Eneo kwa GPS</div>
        <div class="gps-pill gp-idle" id="gpsPill">
            <i class="bi bi-geo-alt"></i> Tayari
        </div>
    </div>

    {{-- Coords display --}}
    <div class="coords-grid">
        <div class="coord-box">
            <div class="coord-label">Latitude</div>
            <div class="coord-val" id="dispLat">—</div>
        </div>
        <div class="coord-box">
            <div class="coord-label">Longitude</div>
            <div class="coord-val" id="dispLng">—</div>
        </div>
    </div>

    {{-- Accuracy --}}
    <div class="acc-row" id="accRow" style="display:none!important">
        <div class="acc-dot" id="accDot"></div>
        <span id="accText"></span>
    </div>

    {{-- Get GPS button --}}
    <button type="button" class="btn-gps btn-gps-primary" id="btnGetGps" onclick="startGPS()">
        <i class="bi bi-crosshair2"></i> Pata Eneo Kiotomatiki
    </button>

    <div style="font-size:12px;color:#94a3b8;text-align:center">
        au weka manually kwenye fomu hapa chini
    </div>
</div>

{{-- MANUAL FORM --}}
<div class="form-card">
    <form method="POST" action="{{ route('schools.store') }}" id="locationForm">
        @csrf

        <div class="form-section-title">
            <i class="bi bi-pencil-fill text-primary"></i> Weka Eneo Manually
        </div>

        @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 14px;font-size:13px;color:#991b1b;margin-bottom:16px">
            <i class="bi bi-exclamation-circle me-1"></i>
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        {{-- Latitude --}}
        <div style="margin-bottom:14px">
            <label class="form-label-s">Latitude *</label>
            <input type="number" name="latitude" id="inpLat" class="form-ctrl {{ old('latitude') ? 'has-val' : '' }}"
                   placeholder="mf. -6.8270000"
                   step="0.0000001" min="-90" max="90" required
                   value="{{ old('latitude', $school->latitude) }}">
            <div class="form-hint">Thamani kati ya -90 na 90</div>
        </div>

        {{-- Longitude --}}
        <div style="margin-bottom:14px">
            <label class="form-label-s">Longitude *</label>
            <input type="number" name="longitude" id="inpLng" class="form-ctrl {{ old('longitude') ? 'has-val' : '' }}"
                   placeholder="mf. 39.2675000"
                   step="0.0000001" min="-180" max="180" required
                   value="{{ old('longitude', $school->longitude) }}">
            <div class="form-hint">Thamani kati ya -180 na 180</div>
        </div>

        {{-- Radius --}}
        <div style="margin-bottom:20px">
            <label class="form-label-s">Radius ya Check-in (mita)</label>
            <div class="radius-wrap">
                <input type="range" name="radius" id="radiusSlider" class="radius-slider"
                       min="50" max="2000" step="50"
                       value="{{ old('radius', $school->radius ?? 500) }}"
                       oninput="document.getElementById('radiusVal').textContent=this.value+'m'">
                <div class="radius-display" id="radiusVal">{{ old('radius', $school->radius ?? 500) }}m</div>
            </div>
            <div class="form-hint">Umbali unaoruhusiwa mwalimu kucheki-in (kawaida 500m)</div>
        </div>

        {{-- Preview map placeholder --}}
        <div id="mapPreview" style="display:none;background:#f8fafc;border:1px dashed #e2e8f0;border-radius:12px;height:180px;margin-bottom:16px;overflow:hidden;position:relative">
            <div id="mapMsg" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;color:#94a3b8;font-size:13px">
                <i class="bi bi-map" style="font-size:32px"></i>
                <span>Weka eneo kuona kwenye ramani</span>
            </div>
            <div id="miniMap" style="height:100%;width:100%"></div>
        </div>

        <button type="submit" class="btn-save" id="btnSave">
            <i class="bi bi-save-fill"></i> Hifadhi Eneo la Shule
        </button>
    </form>
</div>

{{-- INFO CARD --}}
<div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:14px;padding:16px;margin-bottom:20px;font-size:13px;color:#1d4ed8">
    <div style="font-weight:700;margin-bottom:8px"><i class="bi bi-info-circle-fill me-1"></i> Maelezo Muhimu</div>
    <ul style="margin:0;padding-left:18px;line-height:1.8">
        <li>Wewe peke yako (Mwalimu Mkuu) unaweza kuweka eneo la <strong>{{ $school->name }}</strong></li>
        <li>GPS ya simu lazima iwe imewezeshwa kupata eneo kiotomatiki</li>
        <li>Walimu wanaweza kucheki-in ndani ya radius uliyoweka tu</li>
        <li>Radius ndogo = usahihi zaidi, lakini walimu lazima wawe karibu sana</li>
    </ul>
</div>

</div>{{-- /sl-wrap --}}

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<x-slot name="scripts">

<script>
let watchId = null;
let miniMap = null;
let marker  = null;
let circle  = null;

// ── TOAST ────────────────────────────────────────────────────────────
function toast(msg, type='ok', dur=4000) {
    const w = document.getElementById('toastWrap');
    const el = document.createElement('div');
    el.className = `toast2 t2-${type}`;
  const icons = {
    ok: '✅',
    err: '❌',
    inf: 'ℹ️'
};

el.innerHTML = `<span>${icons[type]}</span><span>${msg}</span>`;
    w.appendChild(el);
    setTimeout(() => { el.style.transition='opacity .4s'; el.style.opacity='0'; setTimeout(()=>el.remove(),400); }, dur);
}

// ── GPS ──────────────────────────────────────────────────────────────
function startGPS() {
    if (!navigator.geolocation) {
        toast('GPS haipo kwenye kifaa hiki.', 'err', 6000);
        return;
    }

    const pill  = document.getElementById('gpsPill');
    const btn   = document.getElementById('btnGetGps');
    const card  = document.getElementById('gpsCard');

    pill.className = 'gps-pill gp-finding';
    pill.innerHTML = '<i class="bi bi-arrow-repeat" style="animation:spin 1s linear infinite"></i> Inatafuta...';
    btn.className  = 'btn-gps btn-gps-watching';
    btn.innerHTML  = '<i class="bi bi-broadcast" style="animation:pulse 1s infinite"></i> Inatafuta GPS...';
    btn.disabled   = true;
    card.className = 'gps-status-card finding';

    toast('Inatafuta eneo lako... Subiri kidogo.', 'inf');

    // Stop previous watch
    if (watchId) navigator.geolocation.clearWatch(watchId);

    watchId = navigator.geolocation.watchPosition(
        (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            const acc = Math.round(pos.coords.accuracy);

            // Update display
            document.getElementById('dispLat').textContent = lat.toFixed(7);
            document.getElementById('dispLat').className   = 'coord-val active';
            document.getElementById('dispLng').textContent = lng.toFixed(7);
            document.getElementById('dispLng').className   = 'coord-val active';

            // Fill form inputs
            document.getElementById('inpLat').value = lat.toFixed(7);
            document.getElementById('inpLng').value = lng.toFixed(7);
            document.getElementById('inpLat').className = 'form-ctrl has-val';
            document.getElementById('inpLng').className = 'form-ctrl has-val';

            // Accuracy
            const accRow  = document.getElementById('accRow');
            const accDot  = document.getElementById('accDot');
            const accText = document.getElementById('accText');
            accRow.style.display  = 'flex';
            accText.textContent   = `Usahihi: ±${acc} mita`;
            accDot.className      = acc <= 20 ? 'acc-dot acc-good' : acc <= 50 ? 'acc-dot acc-medium' : 'acc-dot acc-bad';

            // Pill & card
            pill.className = 'gps-pill gp-found';
            pill.innerHTML = `✅ ±${acc}m`;
            card.className = 'gps-status-card found';

            btn.className  = 'btn-gps btn-gps-primary';
            btn.innerHTML  = '<i class="bi bi-arrow-clockwise"></i> Sasisha GPS';
            btn.disabled   = false;
            btn.onclick    = startGPS;

            toast(`✅ Eneo limepatikana! Usahihi: ±${acc}m`, 'ok');

            // Update mini map
            updateMiniMap(lat, lng);

            // Stop watching after good accuracy
            if (acc <= 50) {
                navigator.geolocation.clearWatch(watchId);
            }
        },
        (err) => {
            pill.className = 'gps-pill gp-error';
            pill.innerHTML = '❌ Imeshindwa';
            card.className = 'gps-status-card error';
            btn.className  = 'btn-gps btn-gps-primary';
            btn.innerHTML  = '<i class="bi bi-crosshair2"></i> Jaribu Tena';
            btn.disabled   = false;
            btn.onclick    = startGPS;

            const msgs = {
                1: 'Umezuia GPS. Ruhusu GPS kwenye browser yako.',
                2: 'GPS haiwezi kupata eneo lako sasa.',
                3: 'Muda umekwisha. Jaribu tena.',
            };
            toast(msgs[err.code] || 'GPS imeshindwa.', 'err', 7000);
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
    );
}

// ── MINI MAP ─────────────────────────────────────────────────────────
function updateMiniMap(lat, lng) {
    const preview = document.getElementById('mapPreview');
    const radius  = parseInt(document.getElementById('radiusSlider').value);
    preview.style.display = 'block';

    if (!miniMap) {
        miniMap = L.map('miniMap', { zoomControl: true, scrollWheelZoom: false });
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© CARTO', maxZoom: 18
        }).addTo(miniMap);
    }

    miniMap.setView([lat, lng], 16);

    if (marker) miniMap.removeLayer(marker);
    if (circle) miniMap.removeLayer(circle);

    marker = L.marker([lat, lng]).addTo(miniMap)
        .bindPopup(`<strong>{{ $school->name }}</strong><br>Radius: ${radius}m`).openPopup();

    circle = L.circle([lat, lng], {
        radius: radius,
        fillColor: '#0d6efd', fillOpacity: .1,
        color: '#0d6efd', weight: 2
    }).addTo(miniMap);
}

// ── RADIUS UPDATE → update map circle ────────────────────────────────
document.getElementById('radiusSlider').addEventListener('input', function() {
    if (circle && miniMap) {
        circle.setRadius(parseInt(this.value));
    }
});

// ── INPUT CHANGE → update display ────────────────────────────────────
['inpLat','inpLng'].forEach(id => {
    document.getElementById(id).addEventListener('input', function() {
        const lat = parseFloat(document.getElementById('inpLat').value);
        const lng = parseFloat(document.getElementById('inpLng').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            document.getElementById('dispLat').textContent = lat.toFixed(7);
            document.getElementById('dispLng').textContent = lng.toFixed(7);
            document.getElementById('dispLat').className   = 'coord-val active';
            document.getElementById('dispLng').className   = 'coord-val active';
            updateMiniMap(lat, lng);
        }
        this.className = this.value ? 'form-ctrl has-val' : 'form-ctrl';
    });
});

// ── FORM SUBMIT VALIDATION ────────────────────────────────────────────
document.getElementById('locationForm').addEventListener('submit', function(e) {
    const lat = document.getElementById('inpLat').value;
    const lng = document.getElementById('inpLng').value;
    if (!lat || !lng) {
        e.preventDefault();
        toast('Tafadhali weka latitude na longitude kwanza.', 'err');
        return;
    }
    const btn = document.getElementById('btnSave');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Inahifadhi...';
});

// ── INIT — ikiwa shule ina GPS tayari, onyesha kwenye ramani ─────────
document.addEventListener('DOMContentLoaded', () => {
    @if($school->latitude && $school->longitude)
    const lat = {{ $school->latitude }};
    const lng = {{ $school->longitude }};
    document.getElementById('dispLat').textContent = lat.toFixed(7);
    document.getElementById('dispLng').textContent = lng.toFixed(7);
    document.getElementById('dispLat').className   = 'coord-val active';
    document.getElementById('dispLng').className   = 'coord-val active';
    setTimeout(() => updateMiniMap(lat, lng), 300);
    @endif
});
</script>
</x-slot>
</x-layout>




