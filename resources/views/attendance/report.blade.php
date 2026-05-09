<x-layout title="Ripoti">

<x-slot name="styles">
    <style>
        .report-header {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            color: #fff;
            border-radius: 1.4rem;
        }
        .report-header .icon-box {
            width: 52px;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            background: rgba(255,255,255,.18);
            font-size: 1.3rem;
        }
        .filter-panel {
            background: #ffffff;
            border-radius: 1.2rem;
            border: 1px solid #e2e8f0;
            padding: 1.25rem;
        }
        .filter-panel .form-control,
        .filter-panel .btn {
            border-radius: .85rem;
        }
        .summary-card {
            border-radius: 1.2rem;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            padding: 1.3rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
        }
        .summary-card .label {
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #64748b;
            font-size: .78rem;
            margin-bottom: .5rem;
            display: block;
        }
        .summary-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
        }
        .table-card {
            border: 1px solid #e5e7eb;
            border-radius: 1.2rem;
            overflow: hidden;
            box-shadow: 0 16px 34px rgba(15, 23, 42, .06);
        }
        .table-card thead {
            background: #f8fafc;
        }
        .table-card th,
        .table-card td {
            border-top: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .badge-status {
            border-radius: 999px;
            font-size: .82rem;
            font-weight: 600;
            padding: .45rem .85rem;
        }
        .badge-on-time {
            background: #dcfce7;
            color: #166534;
        }
        .badge-late {
            background: #fee2e2;
            color: #b91c1c;
        }
    </style>
</x-slot>

<div class="container-fluid py-4">
    <div class="row align-items-center mb-4">
        <div class="col-lg-8">
            <div class="report-header p-4 d-flex align-items-center gap-3">
                <div class="icon-box shadow-sm">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <div>
                    <h1 class="h4 mb-1">Ripoti ya Mahudhurio</h1>
                    <p class="mb-0 opacity-75">Muhtasari wa mahudhurio kwa kipindi chako kinachofuata.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('attendance.export.pdf', request()->query()) }}" class="btn btn-light border">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Pakua PDF
            </a>
        </div>
    </div>

    <div class="filter-panel mb-4">
        <form class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label text-muted small">Kuanzia</label>
                <input type="date" name="start" class="form-control" value="{{ request('start') }}" />
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label text-muted small">Hadi</label>
                <input type="date" name="end" class="form-control" value="{{ request('end') }}" />
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label text-muted small">Mwezi</label>
                <select name="month" class="form-control">
                    <option value="">Chagua mwezi</option>
                    @for($i=1;$i<=12;$i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-12 col-md-1 d-grid">
                <button class="btn btn-primary btn-lg">Tafuta</button>
            </div>
        </form>
    </div>

    @php
        $totalRecords = $attendances->count();
        $onTimeCount = $attendances->filter(function ($att) {
            return \Carbon\Carbon::parse($att->created_at)->format('H:i') <= '08:00';
        })->count();
        $lateCount = $totalRecords - $onTimeCount;
        $onTimeRate = $totalRecords ? round(($onTimeCount / $totalRecords) * 100) : 0;
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="summary-card">
                <span class="label">Idadi ya mahudhurio</span>
                <div class="value">{{ $totalRecords }}</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="summary-card">
                <span class="label">Hudhurio kwa wakati</span>
                <div class="value">{{ $onTimeRate }}%</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="summary-card">
                <span class="label">Hudhurio wa kuchelewa</span>
                <div class="value">{{ $lateCount }}</div>
            </div>
        </div>
    </div>

    <div class="card table-card mb-5">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        @if(auth()->user()->role === 'head_teacher')
                            <th class="border-bottom-0">Mwalimu</th>
                        @endif
                        <th class="border-bottom-0">Tarehe</th>
                        <th class="border-bottom-0">Muda</th>
                        <th class="border-bottom-0">Hali</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $att)
                        @php
                            $t = \Carbon\Carbon::parse($att->created_at);
                            $late = $t->format('H:i') > '08:00';
                        @endphp
                        <tr>
                            @if(auth()->user()->role === 'head_teacher')
                                <td>{{ $att->user->first_name ?? '—' }}</td>
                            @endif
                            <td>{{ $t->format('d M Y') }}</td>
                            <td>{{ $t->format('H:i') }}</td>
                            <td>
                                @if($late)
                                    <span class="badge-status badge-late">Umechelewa</span>
                                @else
                                    <span class="badge-status badge-on-time">Umewahi</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

</x-layout>