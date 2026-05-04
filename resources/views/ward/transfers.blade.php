{{-- resources/views/ward/transfers.blade.php --}}
<x-ward-layout title="Uhamisho">

    <div style="margin-bottom:20px">
        <h1 style="font-size:22px;font-weight:800">Maombi ya Uhamisho</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:3px">Kata ya {{ $ward->name }}</p>
    </div>

    {{-- New transfer form --}}
    <div class="card">
        <div class="card-header">
            <div><div class="card-title">📤 Omba Uhamisho Mpya</div><div class="card-sub">Ombi litapelekwa kwa District Officer</div></div>
        </div>
        <div class="card-body">
            <div style="background:rgba(59,130,246,.06);border:1px solid rgba(59,130,246,.15);border-radius:var(--r-sm);padding:10px 14px;font-size:12px;color:#93c5fd;margin-bottom:16px">
                <i class="fas fa-info-circle" style="margin-right:6px"></i>Uhamisho wa mwalimu mkuu au ward officer utashushwa nafasi zao pale ombi litakapoidhinishwa na District Officer.
            </div>
            <form method="POST" action="{{ route('ward.transfers.request') }}">
                @csrf
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;align-items:end">
                    <div class="form-group">
                        <label class="form-label">Chagua Mwalimu *</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Chagua --</option>
                            @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->first_name }} {{ $t->last_name }} ({{ $t->school->name??'—' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Shule Inayolengwa *</label>
                        <select name="to_school_id" class="form-select" required>
                            <option value="">-- Shule mpya --</option>
                            @foreach($allSchools as $sc)
                            <option value="{{ $sc->id }}">{{ $sc->name }} ({{ $sc->ward->name??'—' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Sababu (si lazima)</label>
                        <textarea name="reason" class="form-textarea" placeholder="Eleza sababu ya uhamisho..."></textarea>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Wasilisha Ombi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabs --}}
    <div style="display:flex;gap:2px;background:var(--surface2);border-radius:var(--r-sm);padding:3px;margin-bottom:20px">
        <a href="?tab=pending"
           style="padding:8px 18px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;color:{{ $tab==='pending'?'var(--text)':'var(--muted)' }};background:{{ $tab==='pending'?'var(--surface)':'transparent' }};{{ $tab==='pending'?'box-shadow:0 1px 6px rgba(0,0,0,.3)':'' }}">
            ⏳ Pending
            @if($pending->count() > 0)<span style="background:var(--yellow);color:#fff;font-size:10px;padding:1px 6px;border-radius:10px;margin-left:4px">{{ $pending->count() }}</span>@endif
        </a>
        <a href="?tab=history"
           style="padding:8px 18px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s;color:{{ $tab==='history'?'var(--text)':'var(--muted)' }};background:{{ $tab==='history'?'var(--surface)':'transparent' }};{{ $tab==='history'?'box-shadow:0 1px 6px rgba(0,0,0,.3)':'' }}">
            📜 Historia
        </a>
    </div>

    @if($tab === 'pending')
        @if($pending->isEmpty())
        <div class="empty"><i class="fas fa-check-circle" style="color:var(--accent)"></i><h3 style="color:var(--accent)">Hakuna maombi yanayosubiri</h3></div>
        @else
        @foreach($pending as $tr)
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-sm);padding:16px;margin-bottom:12px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px">
                <div style="display:flex;align-items:center;gap:10px">
                    <div class="t-av">{{ strtoupper(substr($tr->user->first_name??'U',0,1)) }}</div>
                    <div>
                        <div style="font-size:14px;font-weight:700">{{ $tr->user->full_name??'—' }}</div>
                        <div style="font-size:11px;color:var(--muted)">{{ $tr->user->check_number??'—' }}</div>
                    </div>
                </div>
                <span class="badge b-yellow"><i class="fas fa-clock" style="font-size:9px"></i> Inasubiri District</span>
            </div>
            <div style="background:var(--surface);border-radius:var(--r-sm);padding:8px 12px;font-size:13px;margin-bottom:8px;display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                <i class="fas fa-school" style="color:var(--muted);font-size:11px"></i>
                <span style="font-weight:600">{{ $tr->fromSchool->name??'—' }}</span>
                <span style="color:var(--accent)">→</span>
                <span style="font-weight:600;color:var(--accent)">{{ $tr->toSchool->name??'—' }}</span>
            </div>
            @if($tr->reason)
            <div style="font-size:12px;color:var(--muted);font-style:italic"><i class="fas fa-comment" style="font-size:10px;margin-right:4px"></i>{{ $tr->reason }}</div>
            @endif
            <div style="font-size:11px;color:var(--muted);margin-top:6px"><i class="fas fa-clock" style="font-size:10px;margin-right:4px"></i>{{ $tr->created_at->diffForHumans() }}</div>
        </div>
        @endforeach
        @endif
    @else
        <div class="card">
            <div class="card-header"><div class="card-title">📜 Historia ya Uhamisho</div></div>
            @if($history->isEmpty())
            <div class="empty"><i class="fas fa-history"></i><p>Hakuna historia bado</p></div>
            @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>#</th><th>Mwalimu</th><th>Kutoka</th><th>Kwenda</th><th>Hali</th><th>Tarehe</th></tr></thead>
                    <tbody>
                        @foreach($history as $i => $tr)
                        <tr>
                            <td style="color:var(--muted);font-size:12px;font-family:var(--mono)">{{ $i+1 }}</td>
                            <td><div class="t-info"><div class="t-av">{{ strtoupper(substr($tr->user->first_name??'U',0,1)) }}</div><div><div class="t-name">{{ $tr->user->full_name??'—' }}</div></div></div></td>
                            <td style="font-size:12px;color:var(--muted)">{{ $tr->fromSchool->name??'—' }}</td>
                            <td style="font-size:12px;font-weight:600;color:var(--accent)">{{ $tr->toSchool->name??'—' }}</td>
                            <td><span class="badge {{ $tr->status==='approved'?'b-green':'b-red' }}">{{ $tr->status==='approved'?'✅ Imeidhinishwa':'❌ Imekataliwa' }}</span></td>
                            <td style="font-size:11px;color:var(--muted);font-family:var(--mono)">{{ $tr->updated_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    @endif
</x-ward-layout>