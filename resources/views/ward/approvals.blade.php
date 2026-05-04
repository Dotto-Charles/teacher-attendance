{{-- resources/views/ward/approvals.blade.php --}}
<x-ward-layout title="Idhini za Walimu">

    <div style="margin-bottom:20px">
        <h1 style="font-size:22px;font-weight:800">Idhini za Walimu</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">Idhinisha au kataa walimu wapya wa kata ya {{ $ward->name }}</p>
    </div>

    {{-- Tabs --}}
    <div style="display:flex;gap:2px;background:var(--surface2);border-radius:var(--r-sm);padding:3px;margin-bottom:20px;overflow-x:auto">
        @foreach([
            ['pending',  '⏳ Wanasubiri', $pending->count(),  'var(--yellow)'],
            ['approved', '✅ Walioidhinishwa', $approved->count(), 'var(--accent)'],
            ['rejected', '❌ Walikataliwa',  $rejected->count(),  'var(--red)'],
        ] as [$key, $label, $count, $color])
        <a href="?tab={{ $key }}"
           style="padding:8px 18px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;white-space:nowrap;display:flex;align-items:center;gap:7px;color:{{ $tab===$key?'var(--text)':'var(--muted)' }};background:{{ $tab===$key?'var(--surface)':'transparent' }};{{ $tab===$key?'box-shadow:0 1px 6px rgba(0,0,0,.3)':'' }}">
            {{ $label }}
            @if($count > 0)
            <span style="background:{{ $color }};color:#fff;font-size:10px;padding:1px 6px;border-radius:10px">{{ $count }}</span>
            @endif
        </a>
        @endforeach
    </div>

    @php $list = $$tab; @endphp

    @if($list->isEmpty())
    <div class="empty">
        <i class="fas fa-{{ $tab==='pending'?'user-clock':($tab==='approved'?'user-check':'user-times') }}" style="color:{{ $tab==='approved'?'var(--accent)':'var(--muted)' }}"></i>
        <h3>{{ $tab==='pending'?'Hakuna wanasubiri':($tab==='approved'?'Hakuna walioidhinishwa':'Hakuna walikataliwa') }}</h3>
    </div>
    @else
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                {{ $tab==='pending'?'Wanasubiri Idhini':($tab==='approved'?'Walioidhinishwa':'Walikataliwa') }}
                ({{ $list->count() }})
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr>
                    <th>#</th><th>Mwalimu</th><th>Namba</th><th>Shule</th><th>Jinsia</th><th>Alijiunga</th>
                    @if($tab==='pending')<th>Vitendo</th>@endif
                </tr></thead>
                <tbody>
                    @foreach($list as $i => $t)
                    <tr>
                        <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                        <td><div class="t-info">
                            <div class="t-av {{ $t->sex==='female'?'female':'' }}">{{ strtoupper(substr($t->first_name,0,1)) }}</div>
                            <div>
                                <div class="t-name">{{ $t->first_name }} {{ $t->last_name }}</div>
                                <div class="t-sub">{{ $t->email }}</div>
                            </div>
                        </div></td>
                        <td style="font-family:var(--mono);font-size:12px">{{ $t->check_number }}</td>
                        <td style="font-size:12px">{{ $t->school->name??'—' }}</td>
                        <td style="font-size:12px;color:{{ $t->sex==='female'?'var(--pink)':'var(--blue)' }}">{{ $t->sex==='female'?'♀ Mke':'♂ Mme' }}</td>
                        <td style="font-size:11px;color:var(--muted);font-family:var(--mono)">{{ $t->created_at?$t->created_at->format('d/m/Y'):'—' }}</td>
                        @if($tab==='pending')
                        <td>
                            <div style="display:flex;gap:6px">
                                <form method="POST" action="{{ route('ward.approvals.approve', $t) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm" title="Idhinisha">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('ward.approvals.reject', $t) }}"
                                      onsubmit="return confirm('Kataa {{ $t->first_name }}?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Kataa">
                                        <i class="fas fa-times"></i>
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
</x-ward-layout>