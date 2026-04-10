@extends('layouts.admin')

@section('title', $ticket->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ticket-detail.css') }}">
@endpush

@section('content')

<div class="detail-page-header">
    <a href="{{ route('admin.riwayat') }}" class="riwayat-detail-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div class="detail-page-title-group">
        <h2 class="detail-page-title">{{ $ticket->title }}</h2>
        <p class="detail-page-sub">{{ __('app.history_detail_sub') }}</p>
    </div>
</div>

<div class="detail-layout">

    <div class="detail-main-card priority-{{ $ticket->priority }}">

        <div class="detail-card-header">
            <div class="detail-header-left">
                <div class="detail-status-icon status-resolved">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="detail-status-group">
                    <span class="detail-status-label">{{ __('app.status') }}</span>
                    @if($ticket->status === 'resolved')
                        <span class="detail-status-value status-resolved">{{ __('app.resolved') }}</span>
                    @else
                        <span class="detail-status-value status-closed">{{ __('app.done') }}</span>
                    @endif
                </div>
            </div>
            <span class="detail-priority-badge priority-badge-{{ $ticket->priority }}">
                {{ __('app.tek_priority_col') }}: {{ strtoupper($ticket->priority) }}
            </span>
        </div>

        <div class="detail-card-body">
            <div>
                <p class="detail-section-label">{{ __('app.problem_description') }}</p>
                <p class="detail-description">{{ $ticket->description }}</p>
            </div>

            @if($ticket->foto_bukti)
            <hr class="detail-divider">
            <div>
                <p class="detail-section-label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:4px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                    {{ __('app.tek_foto_bukti') }}
                </p>
                <a href="{{ Storage::url($ticket->foto_bukti) }}" target="_blank" title="{{ __('app.tek_laporan_foto_hint') }}">
                    <img src="{{ Storage::url($ticket->foto_bukti) }}"
                         alt="{{ __('app.tek_foto_bukti') }}"
                         style="max-width:100%; max-height:380px; object-fit:contain;
                                border-radius:10px; border:1px solid #e2e8f0;
                                margin-top:8px; cursor:zoom-in; display:block;">
                </a>
                <p style="font-size:11px; color:#94a3b8; margin-top:6px;">
                    {{ __('app.tek_laporan_foto_hint') }}
                </p>
            </div>
            @endif

            <hr class="detail-divider">

            <div class="detail-info-grid">
                <div class="detail-info-item">
                    <div class="detail-info-item-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ __('app.location') }}
                    </div>
                    <div class="detail-info-item-value">{{ $ticket->location ?? '-' }}</div>
                </div>
                <div class="detail-info-item">
                    <div class="detail-info-item-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0z"/>
                        </svg>
                        {{ __('app.report_time') }}
                    </div>
                    <div class="detail-info-item-value">
                        {{ optional($ticket->reported_at ?? $ticket->created_at)->format('Y-m-d H:i') }}
                    </div>
                </div>
                <div class="detail-info-item">
                    <div class="detail-info-item-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('app.resolved_time') }}
                    </div>
                    <div class="detail-info-item-value">
                        {{ optional($ticket->resolved_at)->format('Y-m-d H:i') ?? '-' }}
                    </div>
                </div>
            </div>

            @if($ticket->resolution_notes)
            <div class="detail-resolution-box">
                <div class="detail-resolution-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('app.resolution_notes') }}
                </div>
                <p class="detail-resolution-text">{{ $ticket->resolution_notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="detail-side-panel">
        <div class="detail-panel-card">
            <div class="detail-panel-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                {{ __('app.officer_info') }}
            </div>
            <div class="detail-panel-body">
                <div class="detail-field">
                    <span class="detail-field-label">{{ __('app.reported_by') }}</span>
                    <div class="detail-field-value">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/>
                        </svg>
                        {{ $ticket->pelapor->nama_lengkap ?? $ticket->pelapor->name ?? $ticket->reported_by ?? '-' }}
                    </div>
                </div>
                <hr class="detail-panel-divider">
                <div class="detail-field">
                    <span class="detail-field-label">{{ __('app.handled_by') }}</span>
                    <div class="detail-field-highlight">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/>
                        </svg>
                        {{ $ticket->teknisi->nama_lengkap ?? $ticket->teknisi->name ?? '-' }}
                    </div>
                </div>
                <hr class="detail-panel-divider">
                <div class="detail-field">
                    <span class="detail-field-label">{{ __('app.resolved_at') }}</span>
                    <span class="detail-resolved-date">
                        {{ optional($ticket->resolved_at)->format('Y-m-d H:i') ?? '-' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="detail-panel-card" style="text-align:center; padding:20px;">
            <div style="color:var(--resolved,#2bb0a6); font-weight:700; font-size:15px; display:flex; align-items:center; justify-content:center; gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ __('app.ticket_resolved') }}
            </div>
            <p style="font-size:12px; color:#94a3b8; margin-top:8px;">{{ __('app.no_further_action') }}</p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush