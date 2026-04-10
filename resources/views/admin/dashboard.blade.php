@extends('layouts.admin')

@section('title', __('app.dashboard'))

@section('content')
<div class="dashboard-container">

    <div style="margin-bottom:24px;">
        <h2 style="font-size:20px; font-weight:700; color:#1e293b; margin:0 0 4px;">{{ __('app.dashboard') }}</h2>
        <p style="font-size:13px; color:#94a3b8; margin:0;">{{ __('app.dashboard_sub') }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card critical">
            <div>
                <div class="stat-label">{{ __('app.critical') }}</div>
                <div class="stat-number">{{ $stats['critical'] ?? 0 }}</div>
                <div class="stat-trend">{{ __('app.critical_desc') }}</div>
            </div>
            <div class="stat-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
        </div>
        <div class="stat-card warning">
            <div>
                <div class="stat-label">{{ __('app.warning') }}</div>
                <div class="stat-number">{{ $stats['warning'] ?? 0 }}</div>
                <div class="stat-trend">{{ __('app.warning_desc') }}</div>
            </div>
            <div class="stat-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
        </div>
        <div class="stat-card resolved">
            <div>
                <div class="stat-label">{{ __('app.resolved') }}</div>
                <div class="stat-number">{{ $stats['resolved'] ?? 0 }}</div>
                <div class="stat-trend">{{ __('app.resolved_desc') }}</div>
            </div>
            <div class="stat-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
        <div class="stat-card total">
            <div>
                <div class="stat-label">{{ __('app.total_issues') }}</div>
                <div class="stat-number">{{ $stats['total_issues'] ?? 0 }}</div>
                <div class="stat-trend">{{ __('app.total_issues_desc') }}</div>
            </div>
            <div class="stat-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#2bb0a6" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="content-layout">
        <div>
            <div class="filter-bar">
                <div class="filter-label">{{ __('app.filter') }}</div>
                <div class="search-box">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#7d8590" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input type="text" placeholder="{{ __('app.search_issues') }}" id="searchInput">
                </div>
                <select class="filter-select" id="statusFilter">
                    <option value="">{{ __('app.all_status') }}</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
                <select class="filter-select" id="priorityFilter">
                    <option value="">{{ __('app.all_priority') }}</option>
                    <option value="critical">Critical</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
                <select class="filter-select" id="teknisiFilter">
                    <option value="">{{ __('app.all_technician') }}</option>
                    @foreach($teknisi_list ?? [] as $tek)
                        <option value="{{ $tek->id }}">{{ $tek->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="issues-panel">
                <div class="panel-header">
                    <div class="panel-title">{{ __('app.issue_list') }}</div>
                    <span class="count-badge" id="issueCount">{{ $issues->count() }} issues</span>
                </div>
                <div id="issuesList">
                    @forelse($issues as $issue)
                    <div class="issue-card priority-{{ $issue->priority ?? 'medium' }}"
                        data-status="{{ $issue->status ?? 'open' }}"
                        data-priority="{{ $issue->priority ?? 'medium' }}"
                        data-teknisi="{{ $issue->teknisi_id ?? $issue->assigned_to ?? '' }}">

                        <div class="issue-top">
                            <div class="issue-title-wrap">
                                <div class="issue-title">{{ $issue->judul ?? $issue->title ?? $issue->subject }}</div>
                                <span class="priority-pill pill-{{ $issue->priority ?? 'medium' }}">
                                    {{ strtoupper($issue->priority ?? 'MEDIUM') }}
                                </span>
                                <span class="status-pill status-{{ $issue->status ?? 'open' }}">
                                    {{ ucfirst(str_replace('_', ' ', $issue->status ?? 'open')) }}
                                </span>
                            </div>
                        </div>

                        <div class="issue-meta">
                            <span class="meta-item">{{ $issue->lokasi ?? $issue->location ?? '-' }}</span>
                            <span class="meta-item">{{ ($issue->created_at ?? now())->format('Y-m-d H:i') }}</span>
                            <span class="meta-item ticket-id">#TKT-{{ str_pad($issue->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <div class="issue-footer">
                            <span class="reporter-tag">
                                {{ __('app.reported_by') }} <strong>{{ $issue->pelapor->nama_lengkap ?? $issue->reporter->nama_lengkap ?? $issue->reported_by ?? 'Unknown' }}</strong>
                            </span>
                            @if($issue->teknisi_id ?? $issue->assigned_to ?? false)
                                <button class="assign-btn assigned btn-assign"
                                    data-id="{{ $issue->id }}"
                                    data-title="{{ $issue->judul ?? $issue->title ?? '' }}">
                                    {{ $issue->teknisi->nama_lengkap ?? 'Ditugaskan' }}
                                </button>
                            @else
                                <button class="assign-btn unassigned btn-assign"
                                    data-id="{{ $issue->id }}"
                                    data-title="{{ $issue->judul ?? $issue->title ?? '' }}">
                                    {{ __('app.assign_technician') }}
                                </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="empty-message">
                        <p>{{ __('app.no_issues') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="right-panel">
            <div class="panel-card">
                <div class="panel-card-header">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    {{ __('app.technician_team') }}
                </div>
                <div class="panel-card-body">
                    @forelse($teknisi_list ?? [] as $tek)
                    <div class="assign-panel-item">
                        <div class="tech-avatar">{{ strtoupper(substr($tek->name, 0, 2)) }}</div>
                        <div>
                            <div class="tech-name">{{ $tek->name }}</div>
                            <div class="tech-status">{{ $tek->active_tickets_count ?? 0 }} {{ __('app.active_tickets') }}</div>
                        </div>
                        <div class="tech-dot {{ ($tek->active_tickets_count ?? 0) > 0 ? 'dot-busy' : 'dot-available' }}"></div>
                    </div>
                    @empty
                    <p style="font-size:12px;color:#64748b">{{ __('app.no_technician') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="panel-card">
                <div class="panel-card-header">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('app.recent_activity') }}
                </div>
                <div class="panel-card-body">
                    @forelse($activities ?? [] as $activity)
                    <div class="activity-item">
                        <div class="activity-dot" data-color="{{ $activity->color ?? '' }}"></div>
                        <div>
                            <div class="activity-text">{!! $activity->description !!}</div>
                            <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @empty
                    <p style="font-size:12px;color:#64748b">{{ __('app.no_activity') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="panel-card">
                <div class="panel-card-header">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    {{ __('app.today_summary') }}
                </div>
                <div class="panel-card-body">
                    <div class="summary-row">
                        <span class="summary-label">{{ __('app.unassigned') }}</span>
                        <span class="summary-value" style="color:#ef4444">{{ $stats['unassigned'] ?? 0 }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">{{ __('app.in_progress') }}</span>
                        <span class="summary-value" style="color:#f59e0b">{{ $stats['in_progress'] ?? 0 }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">{{ __('app.resolved_today') }}</span>
                        <span class="summary-value" style="color:#10b981">{{ $stats['resolved_today'] ?? 0 }}</span>
                    </div>
                    <div class="summary-row" style="border-bottom:none">
                        <span class="summary-label">{{ __('app.avg_resolve_time') }}</span>
                        <span class="summary-value" style="color:#2bb0a6">{{ $stats['avg_resolve_time'] ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="assignModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">{{ __('app.assign_technician') }}</div>
            <div class="modal-close" id="btnCloseModal">✕</div>
        </div>
        <div class="modal-body">
            <div class="modal-issue-info">
                <div class="modal-issue-label">{{ __('app.ticket') }}</div>
                <div class="modal-issue-name" id="modalIssueName">-</div>
            </div>
            <div class="assign-section">
                <div class="assign-section-title">{{ __('app.select_technician') }}</div>
                <div class="tech-select-list" id="techSelectList">
                    @foreach($teknisi_list ?? [] as $tek)
                    <div class="tech-select-item"
                        data-tech-id="{{ $tek->user_id }}"
                        data-tech-name="{{ $tek->name }}">
                        <div class="tech-avatar">{{ strtoupper(substr($tek->name, 0, 2)) }}</div>
                        <div class="tech-info">
                            <div class="tech-name">{{ $tek->name }}</div>
                            <div class="tech-sub">
                                @if(($tek->active_tickets_count ?? 0) > 0)
                                    {{ $tek->active_tickets_count }} {{ __('app.active_tickets') }} &middot; {{ __('app.busy') }}
                                @else
                                    {{ __('app.available') }} &middot; 0 {{ __('app.active_tickets') }}
                                @endif
                            </div>
                        </div>
                        <div class="check-circle">
                            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" id="btnBatal">{{ __('app.cancel') }}</button>
            <button class="btn btn-primary" id="confirmAssignBtn" disabled>{{ __('app.assign_technician') }}</button>
        </div>
    </div>
</div>

<div class="toast" id="toast">
    <span id="toastMsg">{{ __('app.assign_success') }}</span>
</div>

@endsection

@push('scripts')
<script>
    window.assignRouteBase = "{{ url('admin/tickets') }}";
    window.csrfToken       = "{{ csrf_token() }}";
</script>
@endpush