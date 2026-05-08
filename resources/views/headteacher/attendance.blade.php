{{-- ═══════════════════════════════════════════════════
     resources/views/headteacher/attendance.blade.php
════════════════════════════════════════════════════ --}}
<x-layout title="Mahudhurio">
<style>
.bdg{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600}
.bdg-g{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0}
.bdg-r{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
.bdg-y{background:#fffbeb;color:#92400e;border:1px solid #fde68a}
.prog{height:4px;background:#f1f5f9;border-radius:99px;overflow:hidden;margin-top:3px;width:70px}
.prog-f{height:100%;border-radius:99px}
.av{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0}
.av-m{background:#eff6ff;color:#1d4ed8}.av-f{background:#fdf2f8;color:#9d174d}
</style>

<div style="margin-bottom:20px">
    <h1 style="font-size:22px;font-weight:800">Mahudhurio — {{ $school->name }}</h1>
    <p style="font-size:13px;color:#64748b;margin-top:3px">{{ \Carbon\Carbon::parse($selectedDate)->format('l, d M Y') }}</p>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @php $s = $summary; @endphp
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius:14px">
            <div class="card-body text-center py-3">
                <div style="font-size:28px;font-weight:800;color:#0d6efd;font-family:'JetBrains Mono',monospace">{{ $s['total'] }}</div>
                <div style="font-size:11px;color:#94a3b8">Walimu Wote</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius:14px">
            <div class="card-body text-center py-3">
                <div style="font-size:28px;font-weight:800;color:#10b981;font-family:'JetBrains Mono',monospace">{{ $s['present'] }}</div>
                <div style="font-size:11px;color:#94a3b8">Walifika</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius:14px">
            <div class="card-body text-center py-3">
                <div style="font-size:28px;font-weight:800;color:#ef4444;font-family:'JetBrains Mono',monospace">{{ $s['absent'] }}</div>
                <div style="font-size:11px;color:#94a3b8">Hawakuja</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius:14px;border-left:4px solid {{ $s['rate']>=80?'#10b981':($s['rate']>=60?'#f59e0b':'#ef4444') }} !important">
            <div class="card-body text-center py-3">
                <div style="font-size:28px;font-weight:800;color:{{ $s['rate']>=80?'#16a34a':($s['rate']>=60?'#ca8a04':'#dc2626') }};font-family:'JetBrains Mono',monospace">{{ $s['rate'] }}%</div>
                <div style="font-size:11px;color:#94a3b8">Kiwango</div>
            </div>
        </div>
    </div>
</div>

{{-- Hourly chart --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius:16px">
            <div class="card-body">
                <div style="font-size:14px;font-weight:700;margin-bottom:12px">⏰ Wakati wa Kufika</div>
                <div style="height:160px;position:relative"><canvas id="hourlyChart"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px">
            <div class="card-body">
                <div style="font-size:14px;font-weight:700;margin-bottom:10px;color:#dc2626">❌ Hawakuja ({{ $summary['absent'] }})</div>
                <div style="max-height:160px;overflow-y:auto">
                    @foreach($teachers->filter(fn($t)=>!$t->is_present)->take(8) as $t)
                    <div style="display:flex;align-items:center;gap:8px;padding:5px 0;border-bottom:1px solid #f8fafc">
                        <div class="av {{ $t->sex==='female'?'av-f':'av-m' }}" style="width:26px;height:26px;font-size:10px">{{ strtoupper(substr($t->first_name,0,1)) }}</div>
                        <div style="font-size:12px;font-weight:600">{{ $t->first_name }} {{ $t->last_name }}</div>
                    </div>
                    @endforeach
                    @if($summary['absent'] === 0)
                    <div style="text-align:center;padding:16px;color:#10b981;font-size:13px;font-weight:600">🎉 Wote walifika!</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<form method="GET" class="mb-3">
    <input type="hidden" name="date" value="{{ $selectedDate }}">
    <div class="d-flex flex-wrap gap-2 align-items-end p-3 bg-white rounded-3 shadow-sm">
        <div>
            <label style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase">Tafuta</label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Jina au namba..." value="{{ $search ?? '' }}" style="min-width:180px">
        </div>
        <div>
            <label style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase">Hali</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Wote</option>
                <option value="present" {{ $statusFilter==='present'?'selected':'' }}>✅ Walifika</option>
                <option value="absent"  {{ $statusFilter==='absent' ?'selected':'' }}>❌ Hawakuja</option>
            </select>
        </div>
        <div>
            <label style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase">Tarehe</label>
            <input type="date" name="date" class="form-control form-control-sm" value="{{ $selectedDate }}" max="{{ now()->toDateString() }}" onchange="this.form.submit()">
        </div>
        <button type="submit" class="btn btn-primary btn-sm rounded-pill">Chuja</button>
        <a href="{{ route('headteacher.attendance') }}" class="btn btn-outline-secondary btn-sm rounded-pill">✕</a>
    </div>
</form>

{{-- Table --}}
<div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <span style="font-size:14px;font-weight:700">📋 Walimu ({{ $teachers->total() }})</span>
        <select class="form-select form-select-sm" style="width:auto" onchange="location='?per_page='+this.value+'&date={{ $selectedDate }}&status={{ $statusFilter }}'">
            @foreach([15,25,50] as $pp)<option value="{{ $pp }}" {{ $perPage==$pp?'selected':'' }}>{{ $pp }}/ukurasa</option>@endforeach
        </select>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:13px">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Mwalimu</th><th>Jinsia</th><th>Hali Leo</th><th>Wakati</th><th>Mwezi %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $i => $t)
                <tr>
                    <td style="color:#94a3b8;font-size:11px">{{ $teachers->firstItem()+$i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av {{ $t->sex==='female'?'av-f':'av-m' }}">{{ strtoupper(substr($t->first_name,0,1)) }}</div>
                            <div>
                                <div style="font-weight:600">{{ $t->first_name }} {{ $t->last_name }}</div>
                                <div style="font-size:11px;color:#94a3b8;font-family:monospace">{{ $t->check_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:{{ $t->sex==='female'?'#be185d':'#1d4ed8' }};font-size:12px">{{ $t->sex==='female'?'♀':'♂' }}</td>
                    <td><span class="bdg {{ $t->is_present?'bdg-g':'bdg-r' }}">{{ $t->is_present?'✅ Alikuja':'❌ Hakuja' }}</span></td>
                    <td style="font-family:monospace;font-size:12px;color:#64748b">{{ $t->checked_at ? \Carbon\Carbon::parse($t->checked_at)->format('H:i') : '—' }}</td>
                    <td>
                        @php $mr=$t->month_rate??0; @endphp
                        <span style="font-size:12px;font-weight:700;color:{{ $mr>=80?'#16a34a':($mr>=60?'#ca8a04':'#dc2626') }}">{{ $mr }}%</span>
                        <div class="prog"><div class="prog-f" style="width:{{ $mr }}%;background:{{ $mr>=80?'#10b981':($mr>=60?'#f59e0b':'#ef4444') }}"></div></div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Hakuna walimu</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($teachers->hasPages())
    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">Ukurasa {{ $teachers->currentPage() }}/{{ $teachers->lastPage() }}</small>
        {{ $teachers->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const h=@json($hourlyData);
new Chart(document.getElementById('hourlyChart'),{type:'bar',data:{labels:h.map(d=>d.hour),datasets:[{label:'Waliofika',data:h.map(d=>d.count),backgroundColor:h.map(d=>{const hr=parseInt(d.hour);return hr<8?'rgba(245,158,11,.7)':hr<=9?'rgba(16,185,129,.7)':'rgba(99,102,241,.5)';}),borderRadius:5,borderSkipped:false}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{ticks:{font:{size:10}}},y:{ticks:{font:{size:10},stepSize:1},beginAtZero:true}}}});
</script>
@endpush
</x-layout>


{{-- ═══════════════════════════════════════════════════
     resources/views/headteacher/approvals.blade.php
════════════════════════════════════════════════════ --}}
{{-- NOTE: Save as separate file --}}