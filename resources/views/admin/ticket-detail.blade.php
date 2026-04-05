@extends('layouts.admin')

@section('title', $ticket->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ticket-detail.css') }}">
@endpush

@section('content')

<div class="detail-page-header">
    <a href="{{ url()->previous() }}" class="riwayat-detail-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div class="detail-page-title-group">
        <h2 class="detail-page-title">{{ $ticket->title }}</h2>
        <p class="detail-page-sub">{{ __('app.ticket_detail_sub') }}</p>
    </div>
</div>

<div class="detail-layout">

    <div class="detail-main-card priority-{{ $ticket->priority }}">

        <div class="detail-card-header">
            <div class="detail-header-left">
                @if($ticket->priority === 'critical')
                    <div class="detail-status-icon status-open">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        </svg>
                    </div>
                @elseif($ticket->priority === 'high')
                    <div class="detail-status-icon status-in_progress">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        </svg>
                    </div>
                @elseif($ticket->priority === 'medium')
                    <div class="detail-status-icon status-resolved">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/>
                        </svg>
                    </div>
                @else
                    <div class="detail-status-icon status-closed">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/>
                        </svg>
                    </div>
                @endif

                <div class="detail-status-group">
                    <span class="detail-status-label">{{ __('app.status') }}</span>
                    @if($ticket->status === 'open')
                        <span class="detail-status-value status-open">Open</span>
                    @elseif($ticket->status === 'in_progress')
                        <span class="detail-status-value status-in_progress">In Progress</span>
                    @elseif($ticket->status === 'resolved')
                        <span class="detail-status-value status-resolved">Resolved</span>
                    @else
                        <span class="detail-status-value status-closed">Closed</span>
                    @endif
                </div>
            </div>

            @if($ticket->priority === 'critical')
                <span class="detail-priority-badge priority-badge-critical">Priority: CRITICAL</span>
            @elseif($ticket->priority === 'high')
                <span class="detail-priority-badge priority-badge-high">Priority: HIGH</span>
            @elseif($ticket->priority === 'medium')
                <span class="detail-priority-badge priority-badge-medium">Priority: MEDIUM</span>
            @else
                <span class="detail-priority-badge priority-badge-low">Priority: LOW</span>
            @endif
        </div>

        <div class="detail-card-body">
            <div>
                <p class="detail-section-label">{{ __('app.problem_description') }}</p>
                <p class="detail-description">{{ $ticket->description }}</p>
            </div>

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
                    <div class="detail-field-value {{ optional($ticket->pelapor)->nama_lengkap ? '' : 'empty' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/>
                        </svg>
                        {{ optional($ticket->pelapor)->nama_lengkap ?? '-' }}
                    </div>
                </div>

                <hr class="detail-panel-divider">

                <div class="detail-field">
                    <span class="detail-field-label">{{ __('app.assigned_officer') }}</span>
                    @if($ticket->assigned_to)
                        <div class="detail-field-highlight">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/>
                            </svg>
                            {{ optional($ticket->teknisi)->nama_lengkap ?? '-' }}
                        </div>
                    @else
                        <div class="detail-field-highlight empty">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/>
                            </svg>
                            {{ __('app.no_officer') }}
                        </div>
                    @endif
                </div>

                <hr class="detail-panel-divider">

                <div class="detail-field">
                    <span class="detail-field-label">{{ __('app.ever_handled_by') }}</span>
                    <div class="detail-field-value">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/>
                        </svg>
                        {{ optional($ticket->teknisi)->nama_lengkap ?? optional($ticket->pelapor)->nama_lengkap ?? '-' }}
                    </div>
                </div>

                @if($ticket->resolved_at)
                <hr class="detail-panel-divider">
                <div class="detail-field">
                    <span class="detail-field-label">{{ __('app.resolved_at') }}</span>
                    <span class="detail-resolved-date">{{ $ticket->resolved_at->format('Y-m-d H:i') }}</span>
                </div>
                @endif
            </div>
        </div>

        @if(!in_array($ticket->status, ['resolved', 'closed']))
        <div class="detail-panel-card">
            <div class="detail-panel-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ __('app.action') }}
            </div>
            <div class="detail-panel-body">
            @if(!$ticket->assigned_to)
            <form action="{{ route('admin.tickets.assign', $ticket->id) }}" method="POST">
                @csrf
                <div class="detail-field" style="margin-bottom:12px;">
                    <label class="detail-field-label" for="teknisi_id">{{ __('app.select_technician') }}</label>
                    <select name="teknisi_id" id="teknisi_id" required class="filter-select" style="width:100%; margin-top:6px;">
                        <option value="">{{ __('app.select_technician') }}</option>
                        @foreach($teknisi_list as $tek)
                            <option value="{{ $tek->id }}">{{ $tek->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="detail-assign-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ __('app.assign_officer') }}
                </button>
            </form>
            @else
            <div style="background:rgba(43,176,166,0.08); border:1px solid rgba(43,176,166,0.2); border-radius:10px; padding:12px 14px; margin-bottom:12px; display:flex; align-items:center; gap:10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#2bb0a6" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span style="font-size:13px; font-weight:600; color:#2bb0a6;">{{ optional($ticket->teknisi)->nama_lengkap }} sudah ditugaskan</span>
            </div>
            <form action="{{ route('admin.tickets.assign', $ticket->id) }}" method="POST" style="margin-bottom:10px;">
                @csrf
                <div class="detail-field" style="margin-bottom:12px;">
                    <label class="detail-field-label" for="teknisi_id">{{ __('app.change_technician') }}</label>
                    <select name="teknisi_id" id="teknisi_id" required class="filter-select" style="width:100%; margin-top:6px;">
                        <option value="">{{ __('app.select_technician') }}</option>
                        @foreach($teknisi_list as $tek)
                            <option value="{{ $tek->id }}" {{ $ticket->assigned_to == $tek->id ? 'selected' : '' }}>
                                {{ $tek->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="detail-assign-btn" style="background:#f8fafc; color:#64748b; border:1px solid #e2e8f0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ __('app.change_technician') }}
                </button>
            </form>
            <form action="{{ route('admin.tickets.resolve', $ticket->id) }}" method="POST">
                @csrf
                <button type="submit" class="detail-status-btn"
                        onclick="return confirm('{{ __('app.confirm_resolve') }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('app.mark_done') }}
                </button>
            </form>
            @endif
        </div>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush