<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Ripoti ya Mahudhurio</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; margin: 0; padding: 24px; }
        .header { margin-bottom: 20px; }
        .header h1 { font-size: 22px; margin: 0 0 6px; }
        .header p { margin: 0; color: #4b5563; font-size: 12px; }
        .meta { margin: 8px 0 0; font-size: 11px; color: #374151; }
        .summary { margin: 20px 0; width: 100%; }
        .summary-grid { display: table; width: 100%; border-collapse: collapse; }
        .summary-card { display: table-cell; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 12px; width: 33%; vertical-align: top; }
        .summary-card + .summary-card { margin-left: 10px; }
        .summary-label { font-size: 10px; text-transform: uppercase; letter-spacing: .08em; color: #6b7280; margin-bottom: 6px; }
        .summary-value { font-size: 18px; font-weight: 700; color: #111827; }
        table { width:100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #d1d5db; padding: 8px 10px; }
        th { background: #f3f4f6; text-align: left; font-size: 12px; }
        td { font-size: 11px; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 999px; font-size: 10px; font-weight: 700; }
        .badge-on-time { background: #dcfce7; color: #166534; }
        .badge-late { background: #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ripoti ya Mahudhurio</h1>
        <p>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }} | {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
        <div class="meta">
            <strong>Tarehe:</strong> {{ now()->format('d M Y') }}
            @if(!empty($params['start']) && !empty($params['end']))
                | <strong>Kipindi:</strong> {{ $params['start'] }} - {{ $params['end'] }}
            @elseif(!empty($params['month']))
                | <strong>Mwezi:</strong> {{ $params['month'] }}
            @endif
        </div>
    </div>

    @php
        $totalRecords = $attendances->count();
        $onTimeCount = $attendances->filter(function ($att) {
            return \Carbon\Carbon::parse($att->created_at)->format('H:i') <= '08:00';
        })->count();
        $lateCount = $totalRecords - $onTimeCount;
        $onTimeRate = $totalRecords ? round(($onTimeCount / $totalRecords) * 100) : 0;
    @endphp

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">Idadi ya mahudhurio</div>
                <div class="summary-value">{{ $totalRecords }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Hudhurio kwa wakati</div>
                <div class="summary-value">{{ $onTimeRate }}%</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Hudhurio wa kuchelewa</div>
                <div class="summary-value">{{ $lateCount }}</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                @if(auth()->user()->role === 'head_teacher')
                    <th>Mwalimu</th>
                @endif
                <th>Tarehe</th>
                <th>Muda</th>
                <th>Hali</th>
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
                        <td>{{ $att->user->first_name ?? '—' }} {{ $att->user->last_name ?? '' }}</td>
                    @endif
                    <td>{{ $t->format('d M Y') }}</td>
                    <td>{{ $t->format('H:i') }}</td>
                    <td>
                        @if($late)
                            <span class="badge badge-late">Umechelewa</span>
                        @else
                            <span class="badge badge-on-time">Umewahi</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>