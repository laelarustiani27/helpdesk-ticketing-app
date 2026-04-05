@extends('layouts.teknisi')

@section('title', __('app.tugas_saya') . ' - NetRespond')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush

@section('content')

<div class="tugas-wrap">

    <div class="tugas-header">
        <div>
            <div class="tugas-title">{{ __('app.tugas_saya') }}</div>
            <div class="tugas-subtitle">{{ __('app.tek_dashboard_sub') }}</div>
        </div>
    </div>

    <div class="stats-bar">
        <div class="stat-chip all active" onclick="filterStatus('all', this)">
            <span class="count">{{ $tickets->count() }}</span>
            <span>{{ __('app.tek_all_tasks') }}</span>
        </div>
        <div class="stat-chip open" onclick="filterStatus('open', this)">
            <span class="count">{{ $tickets->where('status','open')->count() }}</span>
            <span>{{ __('app.tek_open') }}</span>
        </div>
        <div class="stat-chip inprog" onclick="filterStatus('in_progress', this)">
            <span class="count">{{ $tickets->where('status','in_progress')->count() }}</span>
            <span>{{ __('app.tek_in_progress_tab') }}</span>
        </div>
        <div class="stat-chip done" onclick="filterStatus('resolved', this)">
            <span class="count">{{ $tickets->whereIn('status',['resolved','closed'])->count() }}</span>
            <span>{{ __('app.tek_done_tab') }}</span>
        </div>
    </div>

    <div class="toolbar">
        <div class="search-box">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" id="searchInput" placeholder="{{ __('app.tek_search_task') }}" oninput="doSearch()">
        </div>
        <select class="filter-select" id="priorityFilter" onchange="doSearch()">
            <option value="">{{ __('app.all_priority') }}</option>
            <option value="critical">{{ __('app.critical') }}</option>
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
        </select>
        <select class="filter-select" id="sortFilter" onchange="doSearch()">
            <option value="newest">{{ __('app.tek_newest') }}</option>
            <option value="oldest">{{ __('app.tek_oldest') }}</option>
            <option value="priority">{{ __('app.tek_priority') }}</option>
        </select>
    </div>

    <div class="tugas-grid" id="tugasGrid">

        @forelse($tickets as $ticket)
        <div class="tugas-card priority-{{ $ticket->priority }}"
             data-id="{{ $ticket->id }}"
             data-status="{{ $ticket->status }}"
             data-priority="{{ $ticket->priority }}"
             data-title="{{ strtolower($ticket->title) }}"
             data-desc="{{ strtolower($ticket->description) }}"
             data-created="{{ $ticket->created_at->timestamp }}"
             onclick="openDetail({{ $ticket->id }})">

            <div class="card-accent"></div>

            <div class="card-body">
                <div class="card-meta">
                    <span class="ticket-id">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <div style="display:flex;gap:6px;">
                        <span class="badge badge-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span>
                        <span class="badge badge-{{ $ticket->status }}">
                            {{ $ticket->status === 'in_progress' ? __('app.tek_in_progress_tab') : ucfirst($ticket->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-title">{{ $ticket->title }}</div>
                <div class="card-desc">{{ $ticket->description }}</div>
            </div>

            <div class="card-footer" onclick="event.stopPropagation()">
                <div class="card-date">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    {{ $ticket->created_at->format('d M Y') }}
                </div>
                <div class="card-actions">
                    <button class="btn-icon" title="{{ __('app.tek_view_detail') }}" onclick="openDetail({{ $ticket->id }})">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    @if(!in_array($ticket->status, ['resolved','closed']))
                    <button class="btn-icon" title="{{ __('app.tek_start') }}" onclick="window.location='{{ route('teknisi.tugas.kerjakan', $ticket->id) }}'">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.2">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p>{{ __('app.tek_no_task') }}</p>
            <span>{{ __('app.tek_no_task_sub') }}</span>
        </div>
        @endforelse

        <div class="empty-state" id="emptySearch" style="display:none;">
            <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <p>{{ __('app.tek_not_found') }}</p>
            <span>{{ __('app.tek_try_other') }}</span>
        </div>
    </div>
</div>

{{-- DETAIL MODAL --}}
<div class="modal-overlay" id="detailModal">
    <div class="modal-detail" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <div id="modalTicketId" style="font-family:'DM Mono',monospace;font-size:11px;color:#94a3b8;margin-bottom:4px;"></div>
                <div class="modal-title" id="modalTitle"></div>
            </div>
            <button class="modal-close" onclick="closeDetail()">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="modal-body">
            <div class="detail-grid">
                <div>
                    <div class="detail-label">{{ __('app.status') }}</div>
                    <div id="modalStatusBadge"></div>
                </div>
                <div>
                    <div class="detail-label">{{ __('app.tek_priority_col') }}</div>
                    <div id="modalPriority"></div>
                </div>
                <div>
                    <div class="detail-label">{{ __('app.tek_ticket_created') }}</div>
                    <div class="detail-value" id="modalCreated"></div>
                </div>
                <div>
                    <div class="detail-label">{{ __('app.tek_working') }}</div>
                    <div class="detail-value" id="modalUpdated"></div>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-label">{{ __('app.problem_description') }}</div>
                <div class="detail-value" id="modalDesc"></div>
            </div>

            <div class="detail-section" id="statusSection">
                <div class="detail-label">{{ __('app.tek_status_update') }}</div>
                <div style="display:flex;gap:8px;margin-top:2px;">
                    <select class="status-select" id="statusSelect"></select>
                    <button class="btn btn-primary btn-sm" onclick="updateStatus()">{{ __('app.tek_save') }}</button>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-label" style="margin-bottom:12px;">{{ __('app.tek_notes_comments') }}</div>
                <div class="comment-list" id="commentList">
                    <div style="font-size:13px;color:#94a3b8;text-align:center;padding:16px 0;">{{ __('app.tek_loading_comments') }}</div>
                </div>
                <div class="comment-form">
                    <textarea class="comment-input" id="commentInput" placeholder="{{ __('app.tek_write_comment') }}"></textarea>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDetail()">{{ __('app.tek_close') }}</button>
            <button class="btn btn-primary" onclick="submitComment()">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                {{ __('app.tek_send_note') }}
            </button>
            <span id="selesaiBtn"></span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    window.tugasTickets = @json($tickets->keyBy('id'));
</script>
@endpush