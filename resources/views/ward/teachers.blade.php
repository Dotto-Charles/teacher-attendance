{{-- resources/views/ward/teachers.blade.php --}}
<x-ward-layout title="Walimu">
    <x-slot name="actions">
        <a href="{{ route('ward.approvals.index') }}" class="btn btn-warning btn-sm">
            @php $pc = \App\Models\User::whereIn('school_id', \App\Models\School::where('ward_id', auth()->user()->ward_id)->pluck('id'))->where('role','teacher')->where('status','pending')->count(); @endphp
            @if($pc > 0)<span style="background:var(--red);color:#fff;font-size:10px;padding:1px 6px;border-radius:10px;margin-right:4px">{{ $pc }}</span>@endif
            <i class="fas fa-user-check"></i> Idhini
        </a>
    </x-slot>

    <div style="margin-bottom:20px">
        <h1 style="font-size:22px;font-weight:800">Walimu — Kata ya {{ $ward->name }}</h1>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card s-blue"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-val" style="color:var(--blue)">{{ $totalCount }}</div><div class="stat-label">Jumla</div></div>
        <div class="stat-card s-green"><div class="stat-icon"><i class="fas fa-user-check"></i></div><div class="stat-val" style="color:var(--accent)">{{ $approvedCount }}</div><div class="stat-label">Walioidhinishwa</div></div>
        <div class="stat-card s-yellow"><div class="stat-icon"><i class="fas fa-user-clock"></i></div><div class="stat-val" style="color:var(--yellow)">{{ $pendingCount }}</div><div class="stat-label">Wanasubiri</div></div>
        <div class="stat-card s-purple"><div class="stat-icon"><i class="fas fa-mars"></i></div><div class="stat-val" style="color:var(--purple)">{{ $maleCount }}</div><div class="stat-label">Wanaume</div></div>
        <div class="stat-card s-green"><div class="stat-icon"><i class="fas fa-venus"></i></div><div class="stat-val" style="color:var(--pink)">{{ $femaleCount }}</div><div class="stat-label">Wanawake</div></div>
    </div>

    {{-- Filter --}}
    <form method="GET">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:14px 18px;margin-bottom:16px;display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
            <div class="form-group" style="flex:2;min-width:180px">
                <label class="form-label">Tafuta</label>
                <input type="text" name="search" class="form-input" placeholder="Jina au namba..." value="{{ $search ?? '' }}">
            </div>
            <div class="form-group" style="min-width:130px">
                <label class="form-label">Shule</label>
                <select name="school_id" class="form-select">
                    <option value="">Zote</option>
                    @foreach($schools as $sc)
                    <option value="{{ $sc->id }}" {{ $schoolId==$sc->id?'selected':'' }}>{{ $sc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="min-width:110px">
                <label class="form-label">Hali</label>
                <select name="status" class="form-select">
                    <option value="">Zote</option>
                    <option value="approved" {{ $status==='approved'?'selected':'' }}>Walioidhinishwa</option>
                    <option value="pending"  {{ $status==='pending' ?'selected':'' }}>Wanasubiri</option>
                    <option value="rejected" {{ $status==='rejected'?'selected':'' }}>Walikataliwa</option>
                </select>
            </div>
            <div class="form-group" style="min-width:100px">
                <label class="form-label">Jinsia</label>
                <select name="sex" class="form-select">
                    <option value="">Zote</option>
                    <option value="male"   {{ $sex==='male'  ?'selected':'' }}>Wanaume</option>
                    <option value="female" {{ $sex==='female'?'selected':'' }}>Wanawake</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:flex-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Chuja</button>
                <a href="{{ route('ward.teachers.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i></a>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-header">
            <div><div class="card-title">👨‍🏫 Walimu ({{ $teachers->total() }})</div></div>
            <select class="form-select" style="width:auto;padding:5px 10px;font-size:12px" onchange="location='?per_page='+this.value+'&school_id={{ $schoolId }}&status={{ $status }}&sex={{ $sex }}'">
                @foreach([15,20,50] as $pp)<option value="{{ $pp }}" {{ $perPage==$pp?'selected':'' }}>{{ $pp }}/ukurasa</option>@endforeach
            </select>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Mwalimu</th><th>Shule</th><th>Jinsia</th><th>Hali</th><th>Nafasi</th><th>Mahudhurio (30d)</th></tr></thead>
                <tbody>
                    @forelse($teachers as $i => $t)
                    <tr>
                        <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $teachers->firstItem()+$i }}</td>
                        <td><div class="t-info"><div class="t-av {{ $t->sex==='female'?'female':'' }}">{{ strtoupper(substr($t->first_name,0,1)) }}</div><div><div class="t-name">{{ $t->first_name }} {{ $t->last_name }}</div><div class="t-sub">{{ $t->check_number }}</div></div></div></td>
                        <td style="font-size:12px">{{ $t->school->name??'—' }}</td>
                        <td style="font-size:12px;color:{{ $t->sex==='female'?'var(--pink)':'var(--blue)' }}">{{ $t->sex==='female'?'♀ Mke':'♂ Mme' }}</td>
                        <td><span class="badge {{ $t->status==='approved'?'b-green':($t->status==='pending'?'b-yellow':'b-red') }}">{{ $t->status }}</span></td>
                        <td><span class="badge {{ $t->role==='head_teacher'?'b-blue':'b-muted' }}">{{ $t->role==='head_teacher'?'HT':'Mwalimu' }}</span></td>
                        <td>
                            <div style="font-size:12px;font-family:var(--mono);font-weight:700;color:{{ $t->att_rate>=80?'var(--accent)':($t->att_rate>=60?'var(--yellow)':'var(--red)') }}">{{ $t->att_rate }}%</div>
                            <div class="prog-bg" style="width:70px;margin-top:3px"><div class="prog" style="width:{{ $t->att_rate }}%;background:{{ $t->att_rate>=80?'var(--accent)':($t->att_rate>=60?'var(--yellow)':'var(--red)') }}"></div></div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--muted)">Hakuna walimu</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pag-wrap">
            <span class="pag-info">Ukurasa {{ $teachers->currentPage() }}/{{ $teachers->lastPage() }}</span>
            <div class="pag">
                @if(!$teachers->onFirstPage())<a href="{{ $teachers->previousPageUrl() }}"><i class="fas fa-chevron-left" style="font-size:11px"></i></a>@else<span style="opacity:.4"><i class="fas fa-chevron-left" style="font-size:11px"></i></span>@endif
                @foreach($teachers->getUrlRange(max(1,$teachers->currentPage()-2),min($teachers->lastPage(),$teachers->currentPage()+2)) as $pg=>$url)
                    @if($pg==$teachers->currentPage())<span class="cur">{{ $pg }}</span>@else<a href="{{ $url }}">{{ $pg }}</a>@endif
                @endforeach
                @if($teachers->hasMorePages())<a href="{{ $teachers->nextPageUrl() }}"><i class="fas fa-chevron-right" style="font-size:11px"></i></a>@else<span style="opacity:.4"><i class="fas fa-chevron-right" style="font-size:11px"></i></span>@endif
            </div>
        </div>
    </div>
</x-ward-layout>