{{-- resources/views/headteacher/teachers.blade.php --}}
<x-layout title="Walimu">

<div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <h1 style="font-size:22px;font-weight:800">Walimu — {{ $school->name }}</h1>
    </div>
    <a href="{{ route('headteacher.approvals') }}" class="btn btn-warning btn-sm rounded-pill">
        @if($pendingCount > 0)
        <span class="badge bg-white text-dark me-1" style="font-size:10px">{{ $pendingCount }}</span>
        @endif
        ⏳ Idhini za Walimu
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        [$totalCount,   '#0d6efd', 'Jumla',            '👥'],
        [$approvedCount,'#10b981', 'Walioidhinishwa',  '✅'],
        [$pendingCount, '#f59e0b', 'Wanasubiri',       '⏳'],
        [$maleCount,    '#6366f1', 'Wanaume',          '♂'],
        [$femaleCount,  '#ec4899', 'Wanawake',         '♀'],
    ] as [$val,$color,$lbl,$icon])
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius:14px">
            <div style="font-size:22px;margin-bottom:4px">{{ $icon }}</div>
            <div style="font-size:24px;font-weight:800;color:{{ $color }};font-family:monospace">{{ $val }}</div>
            <div style="font-size:11px;color:#94a3b8">{{ $lbl }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<form method="GET" class="mb-3">
    <div class="d-flex flex-wrap gap-2 align-items-end p-3 bg-white rounded-3 shadow-sm">
        <div style="flex:2;min-width:180px">
            <label style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase">Tafuta</label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Jina, namba, email..." value="{{ $search ?? '' }}">
        </div>
        <div>
            <label style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase">Hali</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Zote</option>
                <option value="approved" {{ $status==='approved'?'selected':'' }}>Walioidhinishwa</option>
                <option value="pending"  {{ $status==='pending' ?'selected':'' }}>Wanasubiri</option>
                <option value="rejected" {{ $status==='rejected'?'selected':'' }}>Walikataliwa</option>
            </select>
        </div>
        <div>
            <label style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase">Jinsia</label>
            <select name="sex" class="form-select form-select-sm">
                <option value="">Zote</option>
                <option value="male"   {{ $sex==='male'  ?'selected':'' }}>Wanaume</option>
                <option value="female" {{ $sex==='female'?'selected':'' }}>Wanawake</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm rounded-pill">Chuja</button>
        <a href="{{ route('headteacher.teachers') }}" class="btn btn-outline-secondary btn-sm rounded-pill">✕</a>
    </div>
</form>

{{-- Table --}}
<div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <span style="font-size:14px;font-weight:700">
            Walimu {{ $teachers->firstItem() }}–{{ $teachers->lastItem() }} kati ya {{ $teachers->total() }}
        </span>
        <select class="form-select form-select-sm" style="width:auto" onchange="location='?per_page='+this.value+'&search={{ $search }}&status={{ $status }}&sex={{ $sex }}'">
            @foreach([15,20,50,100] as $pp)<option value="{{ $pp }}" {{ $perPage==$pp?'selected':'' }}>{{ $pp }}/ukurasa</option>@endforeach
        </select>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:13px">
            <thead class="table-light">
                <tr><th>#</th><th>Mwalimu</th><th>Namba</th><th>Jinsia</th><th>Hali</th><th>Nafasi</th><th>Mahudhurio (mwezi)</th></tr>
            </thead>
            <tbody>
                @forelse($teachers as $i => $t)
                <tr>
                    <td style="color:#94a3b8;font-size:11px">{{ $teachers->firstItem()+$i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;border-radius:50%;background:{{ $t->sex==='female'?'#fdf2f8':'#eff6ff' }};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:{{ $t->sex==='female'?'#9d174d':'#1d4ed8' }};flex-shrink:0">{{ strtoupper(substr($t->first_name,0,1)) }}</div>
                            <div>
                                <div style="font-weight:600">{{ $t->first_name }} {{ $t->last_name }}</div>
                                <div style="font-size:11px;color:#94a3b8">{{ $t->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace;font-size:12px">{{ $t->check_number }}</td>
                    <td style="font-size:12px;color:{{ $t->sex==='female'?'#be185d':'#1d4ed8' }}">{{ $t->sex==='female'?'♀ Mke':'♂ Mme' }}</td>
                    <td>
                        <span class="badge {{ $t->status==='approved'?'bg-success':($t->status==='pending'?'bg-warning text-dark':'bg-danger') }} rounded-pill">
                            {{ $t->status }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $t->role==='head_teacher'?'bg-primary':'bg-secondary' }} rounded-pill">
                            {{ $t->role==='head_teacher'?'Mwalimu Mkuu':'Mwalimu' }}
                        </span>
                    </td>
                    <td style="min-width:110px">
                        @php $r=(int)($t->att_rate??0); @endphp
                        <div style="font-size:12px;font-weight:700;color:{{ $r>=80?'#16a34a':($r>=60?'#ca8a04':'#dc2626') }}">{{ $r }}%</div>
                        <div style="height:4px;background:#f1f5f9;border-radius:99px;overflow:hidden;width:80px;margin-top:3px">
                            <div style="height:100%;width:{{ $r }}%;background:{{ $r>=80?'#10b981':($r>=60?'#f59e0b':'#ef4444') }};border-radius:99px"></div>
                        </div>
                        <div style="font-size:10px;color:#94a3b8">{{ $t->att_days }}/{{ $workDays }} siku</div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Hakuna walimu</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($teachers->hasPages())
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Ukurasa {{ $teachers->currentPage() }}/{{ $teachers->lastPage() }}</small>
        {{ $teachers->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
</x-layout>


{{-- ═══════════════════════════════════════════════════
     resources/views/headteacher/reports.blade.php
════════════════════════════════════════════════════ --}}
{{-- Save as separate file --}}