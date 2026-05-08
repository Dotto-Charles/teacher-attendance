{{-- resources/views/headteacher/approvals.blade.php --}}
<x-layout title="Idhini za Walimu">

<div style="margin-bottom:20px">
    <h1 style="font-size:22px;font-weight:800">Idhini za Walimu</h1>
    <p style="font-size:13px;color:#64748b;margin-top:3px">{{ $school->name }}</p>
</div>

{{-- Tabs --}}
<ul class="nav nav-pills mb-4 gap-2">
    @foreach([
        ['pending',  '⏳ Wanasubiri', $counts['pending']],
        ['approved', '✅ Walioidhinishwa', $counts['approved']],
        ['rejected', '❌ Walikataliwa',    $counts['rejected']],
    ] as [$key,$label,$count])
    <li class="nav-item">
        <a href="?tab={{ $key }}" class="nav-link {{ $tab===$key?'active':'' }} rounded-pill">
            {{ $label }}
            @if($count > 0)
            <span class="badge {{ $tab===$key?'bg-white text-dark':'bg-primary' }} ms-1" style="font-size:10px">{{ $count }}</span>
            @endif
        </a>
    </li>
    @endforeach
</ul>

@php $list = $$tab; @endphp

@if($list->isEmpty())
<div class="text-center py-5 text-muted">
    <div style="font-size:48px;margin-bottom:12px">{{ $tab==='approved'?'✅':($tab==='pending'?'⏳':'❌') }}</div>
    <h5>{{ $tab==='pending'?'Hakuna wanasubiri':($tab==='approved'?'Hakuna walioidhinishwa':'Hakuna walikataliwa') }}</h5>
</div>
@else
<div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:13px">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Mwalimu</th><th>Namba</th><th>Jinsia</th><th>Simu</th><th>Alijiunga</th>
                    @if($tab==='pending')<th>Vitendo</th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach($list as $i => $t)
                <tr>
                    <td style="color:#94a3b8;font-size:11px">{{ $i+1 }}</td>
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
                    <td style="color:{{ $t->sex==='female'?'#be185d':'#1d4ed8' }};font-size:12px">{{ $t->sex==='female'?'♀ Mwanamke':'♂ Mwanaume' }}</td>
                    <td style="font-size:12px;color:#64748b">{{ $t->phone }}</td>
                    <td style="font-size:11px;color:#94a3b8">{{ $t->created_at?->diffForHumans() }}</td>
                    @if($tab==='pending')
                    <td>
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('ht.approve', $t) }}">@csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                    <i class="bi bi-check-lg"></i> Idhinisha
                                </button>
                            </form>
                            <form method="POST" action="{{ route('ht.reject', $t) }}"
                                  onsubmit="return confirm('Kataa {{ $t->first_name }} {{ $t->last_name }}?')">@csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
</x-layout>


{{-- ═══════════════════════════════════════════════════
     resources/views/headteacher/teachers.blade.php
════════════════════════════════════════════════════ --}}
{{-- Save as separate file --}}