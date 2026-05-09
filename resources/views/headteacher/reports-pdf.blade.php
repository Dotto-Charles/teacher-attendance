<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Ripoti ya Mahudhurio — {{ $school->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; margin: 0; padding: 24px; }
        .header { margin-bottom: 20px; }
        .header h1 { font-size: 22px; margin: 0 0 4px; }
        .header p { margin: 0; color: #4b5563; font-size: 13px; }
        .meta { margin-top: 8px; font-size: 11px; color: #475569; }
        .summary-grid { display: table; width: 100%; border-collapse: collapse; margin: 20px 0; }
        .summary-card { display: table-cell; background: #f8fafc; border: 1px solid #e2e8eb; border-radius: 8px; padding: 12px 14px; width: 33%; vertical-align: top; }
        .summary-label { font-size: 10px; text-transform: uppercase; letter-spacing: .08em; color: #64748b; margin-bottom: 6px; }
        .summary-value { font-size: 18px; font-weight: 700; color: #111827; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #d1d5db; padding: 8px 10px; }
        th { background: #f3f4f6; text-align: left; font-size: 11px; }
        td { font-size: 11px; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 999px; font-size: 10px; font-weight: 700; }
        .badge-good { background: #dcfce7; color: #166534; }
        .badge-bad { background: #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ripoti ya Mahudhurio</h1>
        <p>{{ $school->name }}</p>
        <div class="meta">Kipindi: {{ $dateFrom }} — {{ $dateTo }}</div>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-label">Walimu</div>
            <div class="summary-value">{{ $totalTeachers }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Siku za Kazi</div>
            <div class="summary-value">{{ $workDays }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Kiwango cha Ujumla</div>
            <div class="summary-value">{{ $overallRate }}%</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Jina Kamili</th>
                <th>Namba</th>
                <th>Jinsia</th>
                <th>Siku</th>
                <th>Siku za Kazi</th>
                <th>Kiwango %</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teacherStats as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $t->first_name }} {{ $t->last_name }}</td>
                    <td>{{ $t->check_number }}</td>
                    <td>{{ $t->sex === 'female' ? 'Mwanamke' : 'Mwanaume' }}</td>
                    <td>{{ $t->days_present }}</td>
                    <td>{{ $t->working_days }}</td>
                    <td>{{ $t->rate }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:#64748b;padding:18px">Hakuna data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>