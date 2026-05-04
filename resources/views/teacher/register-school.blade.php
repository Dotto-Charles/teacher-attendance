<x-layout title="Register School">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h5 class="fw-bold mb-3 text-center">
                        🏫 Register / Transfer School
                    </h5>

                    {{-- ALERTS --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('teacher.register.store') }}">
                        @csrf

                        {{-- COUNCIL --}}
                        <div class="mb-3">
                            <label class="form-label">Council</label>
                            <select id="council" class="form-select">
                                <option value="">-- Select Council --</option>
                                @foreach($councils as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- WARD --}}
                        <div class="mb-3">
                            <label class="form-label">Ward</label>
                            <select id="ward" class="form-select">
                                <option value="">-- Select Ward --</option>
                            </select>
                        </div>

                        {{-- SCHOOL --}}
                        <div class="mb-3">
                            <label class="form-label">School</label>
                            <select id="school" name="school_id" class="form-select" required>
                                <option value="">-- Select School --</option>
                            </select>
                        </div>

                        <button class="btn btn-primary w-100">
                            Submit
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

    {{-- 🔥 ALL DATA LOADED ONCE --}}
    <x-slot name="scripts">
    <script>

        const WARDS = @json($wards);
        const SCHOOLS = @json($schools);

        const councilEl = document.getElementById('council');
        const wardEl    = document.getElementById('ward');
        const schoolEl  = document.getElementById('school');

        // COUNCIL CHANGE
        councilEl.addEventListener('change', function () {

            let councilId = this.value;

            wardEl.innerHTML = '<option value="">-- Select Ward --</option>';
            schoolEl.innerHTML = '<option value="">-- Select School --</option>';

            if (!councilId) return;

            let filteredWards = WARDS.filter(w => w.council_id == councilId);

            filteredWards.forEach(w => {
                wardEl.innerHTML += `<option value="${w.id}">${w.name}</option>`;
            });

        });

        // WARD CHANGE
        wardEl.addEventListener('change', function () {

            let wardId = this.value;

            schoolEl.innerHTML = '<option value="">-- Select School --</option>';

            if (!wardId) return;

            let filteredSchools = SCHOOLS.filter(s => s.ward_id == wardId);

            filteredSchools.forEach(s => {
                schoolEl.innerHTML += `<option value="${s.id}">${s.name}</option>`;
            });

        });

    </script>
    </x-slot>

</x-layout>