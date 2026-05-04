<x-ward-layout title="Shule za Kata">

    {{-- HEADER --}}
    <div style="margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <div>
            <h1 style="font-size:22px;font-weight:800">🏫 Shule za Kata</h1>
            <p style="font-size:13px;color:var(--muted)">
                Kata ya <strong style="color:var(--accent3)">{{ $ward->name }}</strong>
            </p>
        </div>

        {{-- SEARCH --}}
        <form method="GET" style="display:flex;gap:8px">
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Tafuta shule..."
                   class="form-input"
                   style="padding:6px 10px;font-size:12px">
            <button class="btn btn-sm">Search</button>
        </form>
    </div>

    {{-- ALERTS / WARNINGS --}}
    @php
        $lowSchools = $schools->where('rate_today','<',60);
    @endphp

    @if($lowSchools->count())
        <div style="background:red;border:1px solid #ffeeba;padding:12px;border-radius:8px;margin-bottom:15px">
            ⚠️ <strong>Tahadhari:</strong> Shule {{ $lowSchools->count() }} zina mahudhurio chini ya 60%
        </div>
    @endif

    {{-- STATS --}}
    @php
        $totalSchools = $schools->count();
        $totalTeachers = $schools->sum('teacher_count');
        $totalPresent = $schools->sum('present_today');
        $overallRate = $totalTeachers > 0 ? round(($totalPresent / $totalTeachers) * 100, 1) : 0;
    @endphp

    <div class="stats-grid" style="margin-bottom:20px">
        <div class="stat-card s-green">
            <div class="stat-val">{{ $totalSchools }}</div>
            <div class="stat-label">Shule</div>
        </div>

        <div class="stat-card s-blue">
            <div class="stat-val">{{ $totalTeachers }}</div>
            <div class="stat-label">Walimu</div>
        </div>

        <div class="stat-card s-green">
            <div class="stat-val">{{ $totalPresent }}</div>
            <div class="stat-label">Waliofika Leo</div>
        </div>

        <div class="stat-card {{ $overallRate>=80?'s-green':($overallRate>=60?'s-yellow':'s-red') }}">
            <div class="stat-val">{{ $overallRate }}%</div>
            <div class="stat-label">Kiwango cha Kata</div>
        </div>
    </div>

    {{-- SCHOOLS GRID --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">

        @forelse($schools as $school)

            @php
                $color = $school->rate_today >= 80
                    ? 'var(--accent)'
                    : ($school->rate_today >= 60 ? 'var(--yellow)' : 'var(--red)');
            @endphp

            <div style="background:var(--surface);border-radius:var(--r-md);padding:16px;box-shadow:var(--shadow)">

                {{-- NAME + RATE --}}
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                    <a href="{{ route('ward.schools.show', $school->id) }}"
                       style="font-size:14px;font-weight:700;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $school->name }}
                    </a>

                    <span style="font-weight:700;font-size:13px;color:{{ $color }}">
                        {{ $school->rate_today }}%
                    </span>
                </div>

                {{-- HEAD TEACHER --}}
                <div style="font-size:12px;color:var(--muted);margin-bottom:8px">
                    👨‍🏫 Mkuu:
                    <strong>{{ $school->head_teacher->full_name ?? '—' }}</strong>
                </div>

                {{-- STATS --}}
                <div style="font-size:12px;margin-bottom:10px;color:var(--muted)">
                    👥 {{ $school->teacher_count }} walimu<br>
                    ✅ {{ $school->present_today }} waliopo<br>
                    ❌ {{ max(0, $school->teacher_count - $school->present_today) }} hawapo
                </div>

                {{-- PROGRESS --}}
                <div class="prog-bg">
                    <div class="prog" style="width:{{ $school->rate_today }}%;background:{{ $color }}"></div>
                </div>

                {{-- ACTIONS --}}
                <div style="margin-top:12px;display:flex;gap:6px;flex-wrap:wrap">

                    {{-- DETAILS --}}
                    <a href="{{ route('ward.schools.show', $school->id) }}"
                       class="btn btn-sm">
                        👁 View
                    </a>

                    {{-- PDF EXPORT --}}
                    <a href="{{ route('ward.schools.export.pdf', $school->id) }}"
                       class="btn btn-success btn-sm">
                        📄 PDF
                    </a>

                </div>

            </div>

        @empty
            <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--muted)">
                Hakuna shule zilizopatikana.
            </div>
        @endforelse

    </div>

</x-ward-layout>