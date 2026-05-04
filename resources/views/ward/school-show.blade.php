<x-ward-layout title="School Details">

    <div style="margin-bottom:15px">
        <h1 style="font-size:22px;font-weight:800">{{ $school->name }}</h1>
        <p style="color:var(--muted)">Taarifa kamili ya shule</p>
    </div>

    {{-- ACTIONS --}}
    <div style="display:flex;gap:8px;margin-bottom:15px">

        <a href="{{ route('ward.schools.export.pdf', $school->id) }}" class="btn btn-success btn-sm">
            📄 Download PDF
        </a>

        <a href="{{ route('ward.teachers.index', ['school_id'=>$school->id]) }}" class="btn btn-sm">
            👨‍🏫 Walimu
        </a>

    </div>

    {{-- STATS --}}
    <div class="stats-grid">

        <div class="stat-card s-blue">
            <div class="stat-val">{{ $school->teacher_count }}</div>
            <div class="stat-label">Walimu</div>
        </div>

        <div class="stat-card s-green">
            <div class="stat-val">{{ $school->present_today }}</div>
            <div class="stat-label">Waliofika Leo</div>
        </div>

        <div class="stat-card s-yellow">
            <div class="stat-val">{{ $school->pending_count }}</div>
            <div class="stat-label">Pending</div>
        </div>

        <div class="stat-card s-purple">
            <div class="stat-val">{{ $school->rate_today }}%</div>
            <div class="stat-label">Kiwango</div>
        </div>

    </div>

    {{-- TEACHERS LIST --}}
    <div style="margin-top:20px">
        <h3>👨‍🏫 Walimu</h3>

        @foreach($teachers as $t)
            <div style="padding:10px;border-bottom:1px solid #eee;display:flex;justify-content:space-between">

                <div>
                    {{ $t->full_name }}
                    <div style="font-size:12px;color:gray">{{ $t->check_number }}</div>
                </div>

                <div style="display:flex;gap:8px">

                    {{-- HISTORY LINK --}}
                    <a href="{{ route('ward.teachers.history', $t->id) }}">
                        View History
                    </a>

                </div>

            </div>
        @endforeach

    </div>

</x-ward-layout>