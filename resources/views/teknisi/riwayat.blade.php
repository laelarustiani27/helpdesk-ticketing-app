@extends('layouts.teknisi')

@section('title', __('app.tek_history_title'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/riwayat.css') }}">
@endpush

@section('content')

<div class="dashboard-container">

    <div class="page-header-row">
        <div class="page-header-left">
            <h1>{{ __('app.tek_history_title') }}</h1>
            <p>{{ __('app.tek_history_sub') }}</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-mini">
            <div class="stat-mini-icon" style="background:rgba(16,185,129,0.1);">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-mini-label">{{ __('app.tek_total_done') }}</p>
                <p class="stat-mini-value">{{ $tickets->count() }}</p>
            </div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-icon" style="background:rgba(239,68,68,0.1);">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-mini-label">{{ __('app.tek_critical_done') }}</p>
                <p class="stat-mini-value">{{ $tickets->where('priority','critical')->count() }}</p>
            </div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-icon" style="background:rgba(43,176,166,0.1);">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#2bb0a6" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="stat-mini-label">{{ __('app.tek_this_month') }}</p>
                <p class="stat-mini-value">{{ $tickets->filter(fn($t) => optional($t->resolved_at)->isCurrentMonth())->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="riwayat-card">
        @if($tickets->isEmpty())
            <div class="empty-state">
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.3">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p>{{ __('app.tek_no_history') }}</p>
                <span>{{ __('app.tek_no_history_sub') }}</span>
            </div>
        @else
            <table class="riwayat-table">
                <thead>
                    <tr>
                        <th>{{ __('app.tek_no') }}</th>
                        <th>{{ __('app.tek_ticket_col') }}</th>
                        <th>{{ __('app.tek_location_col') }}</th>
                        <th>{{ __('app.tek_priority_col') }}</th>
                        <th>{{ __('app.tek_status_col') }}</th>
                        <th>{{ __('app.tek_resolved_col') }}</th>
                        <th style="text-align:center;">{{ __('app.tek_detail_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $i => $ticket)
                    <tr>
                        <td style="color:#94a3b8; font-weight:600;">{{ $i + 1 }}</td>
                        <td>
                            <div style="font-weight:600; color:#1e293b; margin-bottom:2px;">{{ $ticket->title }}</div>
                            <div style="font-size:11px; color:#94a3b8;">#TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td style="color:#64748b; font-size:12.5px;">{{ $ticket->nama_tempat ?? $ticket->location ?? '-' }}</td>
                        <td>
                            <span class="badge-pill badge-{{ $ticket->priority }}">
                                {{ strtoupper($ticket->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-pill badge-{{ $ticket->status }}">
                                {{ ucfirst(str_replace('_',' ',$ticket->status)) }}
                            </span>
                        </td>
                        <td style="color:#94a3b8; font-size:12px; white-space:nowrap;">
                            {{ optional($ticket->resolved_at)->format('d/m/Y H:i') ?? '-' }}
                        </td>
                        <td style="text-align:center;">
                            <a href="{{ route('teknisi.laporan.show', $ticket->id) }}" class="btn-detail">
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('app.tek_view') }}
                            </a>

                            <form action="{{ route('teknisi.riwayat.delete', $ticket->id) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Hapus riwayat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="
                                    background:#fee2e2;
                                    color:#dc2626;
                                    border:none;
                                    padding:5px 12px;
                                    border-radius:6px;
                                    font-size:12px;
                                    font-weight:600;
                                    cursor:pointer;
                                    margin-left:6px;
                                ">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

@endsection