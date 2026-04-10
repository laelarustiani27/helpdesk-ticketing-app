@extends('layouts.admin')

@section('title', __('app.ticket_list'))

@section('content')
<div style="max-width:1400px; margin:0 auto; padding:24px;">

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:28px;">
    <div>
        <h2 style="font-size:22px; font-weight:700; color:#1e293b; margin:0 0 2px;">{{ __('app.ticket_list') }}</h2>
        <p style="font-size:13px; color:#94a3b8; margin:0;">{{ __('app.ticket_list_sub') }}</p>
    </div>
    <button id="btnBuatTiket" style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:linear-gradient(135deg,#2bb0a6,#059669); color:#fff; border-radius:10px; font-size:13px; font-weight:600; border:none; cursor:pointer;">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M12 4v16m8-8H4"/>
        </svg>
        {{ __('app.create_ticket') }}
    </button>
</div>

<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-bottom:28px;">
    <div style="background:#fff; border-radius:16px; padding:22px 24px; box-shadow:0 1px 4px rgba(0,0,0,0.06); display:flex; align-items:center; gap:18px;">
        <div style="width:52px; height:52px; border-radius:50%; background:rgba(43,176,166,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#2bb0a6" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9l-4-4z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 5v4h4M9 13h6M9 17h4"/>
            </svg>
        </div>
        <div>
            <p style="font-size:12px; color:#94a3b8; margin:0 0 4px; font-weight:500;">{{ __('app.total_ticket') }}</p>
            <h3 style="font-size:30px; font-weight:700; color:#1e293b; margin:0; line-height:1;">{{ $stats['total'] ?? 0 }}</h3>
            <p style="font-size:12px; color:#2bb0a6; margin:4px 0 0; font-weight:500;">↑ {{ __('app.all_status') }}</p>
        </div>
    </div>
    <div style="background:#fff; border-radius:16px; padding:22px 24px; box-shadow:0 1px 4px rgba(0,0,0,0.06); display:flex; align-items:center; gap:18px;">
        <div style="width:52px; height:52px; border-radius:50%; background:rgba(245,158,11,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0z"/>
            </svg>
        </div>
        <div>
            <p style="font-size:12px; color:#94a3b8; margin:0 0 4px; font-weight:500;">{{ __('app.waiting') }}</p>
            <h3 style="font-size:30px; font-weight:700; color:#f59e0b; margin:0; line-height:1;">{{ $stats['open'] ?? 0 }}</h3>
            <p style="font-size:12px; color:#94a3b8; margin:4px 0 0; font-weight:500;">{{ __('app.not_processed') }}</p>
        </div>
    </div>
    <div style="background:#fff; border-radius:16px; padding:22px 24px; box-shadow:0 1px 4px rgba(0,0,0,0.06); display:flex; align-items:center; gap:18px;">
        <div style="width:52px; height:52px; border-radius:50%; background:rgba(16,185,129,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a10 10 0 1 1-20 0 10 10 0 0 1 20 0z"/>
            </svg>
        </div>
        <div>
            <p style="font-size:12px; color:#94a3b8; margin:0 0 4px; font-weight:500;">{{ __('app.done') }}</p>
            <h3 style="font-size:30px; font-weight:700; color:#10b981; margin:0; line-height:1;">{{ $stats['resolved'] ?? 0 }}</h3>
            <p style="font-size:12px; color:#94a3b8; margin:4px 0 0; font-weight:500;">{{ __('app.already_resolved') }}</p>
        </div>
    </div>
</div>

<div style="background:#fff; border-radius:16px; box-shadow:0 1px 4px rgba(0,0,0,0.06); padding:24px;">

    <div style="display:flex; gap:8px; margin-bottom:20px;">
        <button onclick="switchTab('tiket')" id="tabTiket"
            style="padding:8px 20px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; border:none; background:linear-gradient(135deg,#2bb0a6,#059669); color:#fff; font-family:inherit;">
            Tiket
        </button>
        <button onclick="switchTab('laporan')" id="tabLaporan"
            style="padding:8px 20px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; border:1px solid #e2e8f0; background:#fff; color:#64748b; font-family:inherit; display:flex; align-items:center; gap:6px;">
            Laporan Pelanggan
            @if(($laporanStats['menunggu'] ?? 0) > 0)
                <span style="background:#ef4444; color:#fff; font-size:10px; font-weight:700; padding:1px 7px; border-radius:20px;">{{ $laporanStats['menunggu'] }}</span>
            @endif
        </button>
    </div>

    <div id="sectionTiket">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:14px;">
            <div>
                <h3 style="font-size:16px; font-weight:700; color:#1e293b; margin:0 0 2px;">{{ __('app.ticket_list') }}</h3>
                @if(request('status'))
                    <span style="font-size:13px; color:#2bb0a6; font-weight:500;">{{ ucfirst(str_replace('_',' ',request('status'))) }}</span>
                @else
                    <span style="font-size:13px; color:#94a3b8;">{{ __('app.all_status') }}</span>
                @endif
            </div>
            <form method="GET" action="{{ route('admin.tickets.index') }}" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                <div style="position:relative;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); pointer-events:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_ticket') }}"
                           style="padding:8px 12px 8px 32px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; color:#1e293b; outline:none; width:190px; background:#f8fafc;"
                           onfocus="this.style.borderColor='#2bb0a6'" onblur="this.style.borderColor='#e2e8f0'">
                </div>
               <select name="status" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; color:#1e293b; background:#f8fafc; outline:none; cursor:pointer;">
                    <option value="">{{ __('app.all_status') }}</option>
                    <option value="open"        {{ request('status')=='open'        ?'selected':'' }}>Open</option>
                    <option value="in_progress" {{ request('status')=='in_progress' ?'selected':'' }}>In Progress</option>
                </select>
                <select name="priority" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; color:#1e293b; background:#f8fafc; outline:none; cursor:pointer;">
                    <option value="">{{ __('app.all_priority') }}</option>
                    <option value="low"      {{ request('priority')=='low'      ?'selected':'' }}>Low</option>
                    <option value="medium"   {{ request('priority')=='medium'   ?'selected':'' }}>Medium</option>
                    <option value="high"     {{ request('priority')=='high'     ?'selected':'' }}>High</option>
                    <option value="critical" {{ request('priority')=='critical' ?'selected':'' }}>Critical</option>
                </select>
                <button type="submit" style="padding:8px 18px; background:linear-gradient(135deg,#2bb0a6,#059669); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                    {{ __('app.search') }}
                </button>
                @if(request()->hasAny(['search','status','priority']))
                    <a href="{{ route('admin.tickets.index') }}" style="padding:8px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; color:#64748b; text-decoration:none; background:#fff;">✕ Reset</a>
                @endif
            </form>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="text-align:left; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase; letter-spacing:0.05em;">No</th>
                        <th style="text-align:left; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase; letter-spacing:0.05em;">{{ __('app.ticket_created_time') }}</th>
                        <th style="text-align:left; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase; letter-spacing:0.05em;">{{ __('app.device') }}</th>
                        <th style="text-align:left; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase; letter-spacing:0.05em;">{{ __('app.device_status') }}</th>
                        <th style="text-align:left; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">{{ __('app.device_off_time') }}</th>
                        <th style="text-align:left; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase; letter-spacing:0.05em;">{{ __('app.ticket_status') }}</th>
                        <th style="text-align:center; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase; letter-spacing:0.05em;">{{ __('app.detail') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                        onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                        <td style="padding:14px 16px; color:#64748b; font-weight:600; font-size:13px;">{{ $loop->iteration }}</td>
                        <td style="padding:14px 16px; color:#94a3b8; font-size:12.5px; white-space:nowrap;">{{ optional($ticket->created_at)->format('m/d/Y g:i:s A') }}</td>
                        <td style="padding:14px 16px;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:#fff; flex-shrink:0;">
                                    {{ strtoupper(substr($ticket->reported_by ?? 'U', 0, 1)) }}
                                </div>
                                <span style="color:#374151; font-weight:500; font-size:13px;">{{ $ticket->reported_by ?? '-' }}</span>
                            </div>
                        </td>
                        <td style="padding:14px 16px; color:#64748b; font-size:13px;">{{ $ticket->device_status ?? '-' }}</td>
                        <td style="padding:14px 16px; color:#94a3b8; font-size:12.5px; white-space:nowrap;">{{ optional($ticket->reported_at)->format('m/d/Y g:i:s A') ?? '-' }}</td>
                        <td style="padding:14px 16px;">
                            @if($ticket->status === 'open')
                                <span style="display:inline-flex; align-items:center; gap:5px; background:rgba(245,158,11,0.1); color:#d97706; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#d97706; flex-shrink:0;"></span>Open
                                </span>
                            @elseif($ticket->status === 'in_progress')
                                <span style="display:inline-flex; align-items:center; gap:5px; background:rgba(99,102,241,0.1); color:#6366f1; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#6366f1; flex-shrink:0;"></span>In Progress
                                </span>
                            @elseif($ticket->status === 'resolved')
                                <span style="display:inline-flex; align-items:center; gap:5px; background:rgba(16,185,129,0.1); color:#10b981; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#10b981; flex-shrink:0;"></span>Resolved
                                </span>
                            @else
                                <span style="display:inline-flex; align-items:center; gap:5px; background:rgba(100,116,139,0.1); color:#64748b; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#64748b; flex-shrink:0;"></span>Closed
                                </span>
                            @endif
                        </td>
                        <td style="padding:14px 16px; text-align:center;">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}"
                               style="display:inline-flex; align-items:center; gap:5px; padding:6px 14px; border-radius:8px; text-decoration:none; background:rgba(43,176,166,0.1); color:#2bb0a6; font-size:12px; font-weight:600; white-space:nowrap;"
                               onmouseover="this.style.background='rgba(43,176,166,0.22)'"
                               onmouseout="this.style.background='rgba(43,176,166,0.1)'">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('app.manage') }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding:60px 20px; text-align:center;">
                            <p style="font-size:14px; font-weight:600; color:#64748b; margin:0 0 4px;">{{ __('app.no_ticket_found') }}</p>
                            <p style="font-size:12px; color:#94a3b8; margin:0;">{{ __('app.try_change_filter') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px; flex-wrap:wrap; gap:12px;">
            <p style="font-size:13px; color:#94a3b8; margin:0;">
                {{ __('app.showing') }} <strong style="color:#1e293b;">{{ $tickets->firstItem() }}</strong>
                – <strong style="color:#1e293b;">{{ $tickets->lastItem() }}</strong>
                {{ __('app.of') }} <strong style="color:#1e293b;">{{ $tickets->total() }}</strong> {{ __('app.ticket') }}
            </p>
            <div style="display:flex; gap:5px; align-items:center;">
                @if($tickets->onFirstPage())
                    <span style="width:34px; height:34px; display:flex; align-items:center; justify-content:center; border-radius:8px; background:#f1f5f9; color:#cbd5e1; font-size:16px; cursor:not-allowed;">‹</span>
                @else
                    <a href="{{ $tickets->previousPageUrl() }}" style="width:34px; height:34px; display:flex; align-items:center; justify-content:center; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#64748b; font-size:16px; text-decoration:none;">‹</a>
                @endif
                @foreach($tickets->getUrlRange(max(1,$tickets->currentPage()-2), min($tickets->lastPage(),$tickets->currentPage()+2)) as $page => $url)
                    @if($page == $tickets->currentPage())
                        <span style="width:34px; height:34px; display:flex; align-items:center; justify-content:center; border-radius:8px; background:linear-gradient(135deg,#2bb0a6,#059669); color:#fff; font-weight:700; font-size:13px;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="width:34px; height:34px; display:flex; align-items:center; justify-content:center; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#64748b; font-size:13px; text-decoration:none;">{{ $page }}</a>
                    @endif
                @endforeach
                @if($tickets->hasMorePages())
                    <a href="{{ $tickets->nextPageUrl() }}" style="width:34px; height:34px; display:flex; align-items:center; justify-content:center; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#64748b; font-size:16px; text-decoration:none;">›</a>
                @else
                    <span style="width:34px; height:34px; display:flex; align-items:center; justify-content:center; border-radius:8px; background:#f1f5f9; color:#cbd5e1; font-size:16px; cursor:not-allowed;">›</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div id="sectionLaporan" style="display:none;">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="text-align:left; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase;">No</th>
                        <th style="text-align:left; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase;">Pelanggan</th>
                        <th style="text-align:left; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase;">Jenis Masalah</th>
                        <th style="text-align:left; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase;">Tanggal</th>
                        <th style="text-align:left; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase;">Status</th>
                        <th style="text-align:center; padding:11px 16px; color:#94a3b8; font-weight:600; font-size:11.5px; text-transform:uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanPelanggan ?? [] as $laporan)
                    <tr style="border-bottom:1px solid #f1f5f9;"
                        onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                        <td style="padding:14px 16px; color:#64748b; font-weight:600;">{{ $loop->iteration }}</td>
                        <td style="padding:14px 16px;">
                            <div style="font-weight:600; color:#1e293b; font-size:13px;">{{ $laporan->pelanggan->nama ?? $laporan->nama_pelapor }}</div>
                            <div style="font-size:11px; color:#94a3b8;">{{ $laporan->pelanggan->no_pelanggan ?? '-' }}</div>
                        </td>
                        <td style="padding:14px 16px; color:#374151; font-size:13px;">{{ $laporan->jenis_masalah }}</td>
                        <td style="padding:14px 16px; color:#94a3b8; font-size:12px;">{{ $laporan->created_at->format('d M Y, H:i') }}</td>
                        <td style="padding:14px 16px;">
                            @if($laporan->status === 'menunggu')
                                <span style="background:rgba(100,116,139,0.1); color:#64748b; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700;">Menunggu</span>
                            @elseif($laporan->status === 'disetujui')
                                <span style="background:rgba(16,185,129,0.1); color:#10b981; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700;">Disetujui</span>
                            @elseif($laporan->status === 'ditolak')
                                <span style="background:rgba(239,68,68,0.1); color:#ef4444; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700;">Ditolak</span>
                            @elseif($laporan->status === 'diproses')
                                <span style="background:rgba(245,158,11,0.1); color:#f59e0b; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700;">Diproses</span>
                            @elseif($laporan->status === 'selesai')
                                <span style="background:rgba(43,176,166,0.1); color:#2bb0a6; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700;">Selesai</span>
                            @endif
                        </td>
                        <td style="padding:14px 16px; text-align:center;">
                            @if($laporan->status === 'menunggu')
                                <div style="display:flex; gap:6px; justify-content:center;">
                                    <form action="{{ route('admin.laporan-pelanggan.approve', $laporan->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            style="padding:6px 14px; background:rgba(16,185,129,0.1); color:#10b981; border:1px solid rgba(16,185,129,0.2); border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; font-family:inherit;"
                                            onmouseover="this.style.background='rgba(16,185,129,0.2)'"
                                            onmouseout="this.style.background='rgba(16,185,129,0.1)'">
                                            Setujui
                                        </button>
                                    </form>
                                    <button type="button"
                                        onclick="openRejectModal({{ $laporan->id }})"
                                        style="padding:6px 14px; background:rgba(239,68,68,0.08); color:#ef4444; border:1px solid rgba(239,68,68,0.2); border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; font-family:inherit;"
                                        onmouseover="this.style.background='rgba(239,68,68,0.15)'"
                                        onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                                        Tolak
                                    </button>
                                </div>
                            @elseif($laporan->ticket_id)
                                <a href="{{ route('admin.tickets.show', $laporan->ticket_id) }}"
                                   style="padding:6px 14px; background:rgba(43,176,166,0.1); color:#2bb0a6; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none;">
                                   Lihat Tiket
                                </a>
                            @else
                                <span style="font-size:12px; color:#94a3b8;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:60px; text-align:center; color:#94a3b8; font-size:13px;">Belum ada laporan masuk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
@endsection

@push('modals')
<div id="tiketModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(15,23,42,0.55); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:20px; box-sizing:border-box;">
    <div id="tiketModalBox" style="background:#fff; border-radius:20px; width:100%; max-width:680px; box-shadow:0 24px 64px rgba(0,0,0,0.2); overflow-y:auto; max-height:95vh;">
        <div style="padding:20px 24px 16px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; background:#fff; z-index:1;">
            <div style="font-size:17px; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:10px;">
                <span style="display:block; width:4px; height:18px; background:linear-gradient(135deg,#2bb0a6,#059669); border-radius:2px;"></span>
                {{ __('app.create_ticket') }}
            </div>
            <button id="tiketModalClose" style="width:30px; height:30px; border-radius:8px; background:#f8faf9; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#64748b; font-size:16px;">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.tickets.store') }}">
            @csrf
            <div style="padding:20px 24px; display:flex; flex-direction:column; gap:18px;">
                <div style="font-size:11px; font-weight:700; color:#2bb0a6; text-transform:uppercase; letter-spacing:0.08em; padding-bottom:6px; border-bottom:1px solid #f1f5f9;">{{ __('app.general_info') }}</div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label style="font-size:13px; font-weight:600; color:#374151;">{{ __('app.place_name') }}</label>
                    <input type="text" name="nama_tempat" style="height:40px; padding:0 14px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; background:#f9fafb; outline:none; width:100%; box-sizing:border-box;">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label style="font-size:13px; font-weight:600; color:#374151;">Nama Pelapor</label>
                    <input type="text" name="nama_pelapor" style="height:40px; padding:0 14px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; background:#f9fafb; outline:none; width:100%; box-sizing:border-box;">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label style="font-size:13px; font-weight:600; color:#374151;">{{ __('app.address') }}</label>
                    <textarea name="alamat" rows="3" style="padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; background:#f9fafb; outline:none; width:100%; box-sizing:border-box; resize:vertical; font-family:inherit;"></textarea>
                </div>
                <div style="display:flex; flex-direction:column; gap:6px;">
                    <label style="font-size:13px; font-weight:600; color:#374151;">{{ __('app.coordinate') }}</label>
                    <input type="text" name="koordinat" style="height:40px; padding:0 14px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; background:#f9fafb; outline:none; width:100%; box-sizing:border-box;">
                </div>
                <div style="font-size:11px; font-weight:700; color:#2bb0a6; text-transform:uppercase; letter-spacing:0.08em; padding-bottom:6px; border-bottom:1px solid #f1f5f9; margin-top:4px;">{{ __('app.contact_info') }}</div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start;">
    
                <div style="display:flex; flex-direction:column; gap:14px;">
                    <div style="font-size:11px; font-weight:700; color:#2bb0a6; text-transform:uppercase; letter-spacing:0.08em; padding-bottom:6px; border-bottom:1px solid #f1f5f9;">{{ __('app.contact_info') }}</div>
                    
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:13px; font-weight:600; color:#374151;">ID Pelanggan</label>
                        <select name="pelanggan_id" id="selectPelanggan"
                            style="height:40px; padding:0 14px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; background:#f9fafb; outline:none; width:100%; box-sizing:border-box; cursor:pointer;">
                            <option value="">Pilih Pelanggan</option>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}"
                                    data-nama="{{ $p->nama }}"
                                    data-telepon="{{ $p->no_telepon }}"
                                    data-alamat="{{ $p->alamat }}"
                                    data-email="{{ $p->email ?? '' }}">
                                    {{ $p->no_pelanggan }} - {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:13px; font-weight:600; color:#374151;">{{ __('app.phone') }}</label>
                        <input type="tel" name="no_telepon" style="height:40px; padding:0 14px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; background:#f9fafb; outline:none; width:100%; box-sizing:border-box;">
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:13px; font-weight:600; color:#374151;">{{ __('app.email') }}</label>
                        <input type="email" name="email" style="height:40px; padding:0 14px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; background:#f9fafb; outline:none; width:100%; box-sizing:border-box;">
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:14px;">
                    <div style="font-size:11px; font-weight:700; color:#2bb0a6; text-transform:uppercase; letter-spacing:0.08em; padding-bottom:6px; border-bottom:1px solid #f1f5f9;">{{ __('app.order_detail') }}</div>
                    
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <label style="font-size:13px; font-weight:600; color:#374151;">{{ __('app.order_type') }}</label>
                        <select name="jenis_pemesanan" style="height:40px; padding:0 14px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; background:#f9fafb; outline:none; width:100%; box-sizing:border-box; cursor:pointer;">
                            <option value="">{{ __('app.select') }}</option>
                            <option value="instalasi">{{ __('app.installation') }}</option>
                            <option value="perbaikan">{{ __('app.repair') }}</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="konsultasi">{{ __('app.consultation') }}</option>
                            <option value="lainnya">{{ __('app.other') }}</option>
                        </select>
                    </div>
                </div>
            </div> 
            </div> 
            <div style="padding:16px 24px; border-top:1px solid #f1f5f9; display:flex; gap:10px; justify-content:flex-end; background:#fafafa;">
                <button type="button" id="tiketModalBatal" style="padding:9px 20px; background:#fff; color:#64748b; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit;">
                    {{ __('app.cancel') }}
                </button>
                <button type="submit" style="padding:9px 22px; background:linear-gradient(135deg,#2bb0a6,#059669); color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; display:flex; align-items:center; gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ __('app.create_ticket') }}
                </button>
            </div>
        </form>
    </div>
</div>

<div id="rejectModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(15,23,42,0.55); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:20px; box-sizing:border-box;">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:440px; box-shadow:0 24px 64px rgba(0,0,0,0.2); overflow:hidden;">
        <div style="padding:18px 22px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
            <div style="font-size:16px; font-weight:700; color:#0f172a;">Tolak Laporan</div>
            <button onclick="closeRejectModal()" style="background:none; border:none; cursor:pointer; color:#94a3b8; font-size:18px;">✕</button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div style="padding:20px 22px;">
                <label style="font-size:13px; font-weight:600; color:#374151; display:block; margin-bottom:8px;">Alasan Penolakan</label>
                <textarea name="catatan_admin" rows="4" required
                    style="width:100%; padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; font-family:inherit; resize:vertical; outline:none; box-sizing:border-box;"
                    placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div style="padding:14px 22px; border-top:1px solid #f1f5f9; display:flex; gap:8px; justify-content:flex-end; background:#fafafa;">
                <button type="button" onclick="closeRejectModal()"
                    style="padding:8px 18px; background:#fff; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; color:#64748b; cursor:pointer; font-family:inherit;">
                    Batal
                </button>
                <button type="submit"
                    style="padding:8px 18px; background:#ef4444; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit;">
                    Tolak Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    @if(session('tab') === 'laporan')
        switchTab('laporan');
    @endif
</script>
@endpush