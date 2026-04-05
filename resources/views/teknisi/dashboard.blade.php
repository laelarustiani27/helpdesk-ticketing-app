@extends('layouts.teknisi')

@section('title', __('app.dashboard') . ' - Net Respond')

@section('content')
<div class="dashboard-container">

    {{-- ═══ Header ═══ --}}
    <div class="page-header">
        <h1>{{ __('app.dashboard') }}</h1>
        <p class="page-sub">{{ __('app.tek_dashboard_sub') }}</p>
    </div>

    {{-- ═══ Stats Cards ═══ --}}
    <div class="stats-grid">

        <div class="stat-card stat-card-critical">
            <div class="stat-card-body">
                <div class="stat-label">{{ __('app.critical') }}</div>
                <div class="stat-value">{{ $criticalCount }}</div>
                <div class="stat-desc">{{ __('app.critical_desc') }}</div>
            </div>
            <div class="stat-icon-wrap stat-icon-critical">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
        </div>

        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="stat-label">{{ __('app.warning') }}</div>
                <div class="stat-value">{{ $warningCount }}</div>
                <div class="stat-desc">{{ __('app.warning_desc') }}</div>
            </div>
            <div class="stat-icon-wrap stat-icon-warning">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
        </div>

        <div class="stat-card stat-card-resolved">
            <div class="stat-card-body">
                <div class="stat-label">{{ __('app.resolved') }}</div>
                <div class="stat-value">{{ $resolvedCount }}</div>
                <div class="stat-desc">{{ __('app.resolved_desc') }}</div>
            </div>
            <div class="stat-icon-wrap stat-icon-resolved">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <div class="stat-card stat-card-total">
            <div class="stat-card-body">
                <div class="stat-label">{{ __('app.total_issues') }}</div>
                <div class="stat-value">{{ $totalIssues }}</div>
                <div class="stat-desc">{{ __('app.total_issues_desc') }}</div>
            </div>
            <div class="stat-icon-wrap stat-icon-total">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- ═══ Main Content + Sidebar ═══ --}}
    <div class="dashboard-body">

        {{-- ── Kiri: Daftar Tiket ── --}}
        <div class="dashboard-main">

            {{-- Filter Bar --}}
            <div class="filter-bar">
                <span class="filter-label">{{ __('app.filter') }}</span>
                <div class="search-wrap">
                    <input type="text" id="searchInput" class="search-input"
                           placeholder="{{ __('app.search_issues') }}">
                </div>
                <select id="filterStatus" class="filter-select">
                    <option value="">{{ __('app.all_status') }}</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
                <select id="filterPriority" class="filter-select">
                    <option value="">{{ __('app.all_priority') }}</option>
                    <option value="critical">{{ __('app.critical') }}</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>

            {{-- Daftar Tiket --}}
            <div class="ticket-list-card">
                <div class="ticket-list-header">
                    <h2 class="ticket-list-title">{{ __('app.tek_my_tasks') }}</h2>
                    <span class="ticket-count">{{ $totalIssues }} {{ __('app.tek_issues') }}</span>
                </div>

                @if($tickets->isEmpty())
                    <div class="empty-state">
                        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.3">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p>{{ __('app.tek_no_ticket') }}</p>
                    </div>
                @else
                    <div class="ticket-list" id="ticketList">
                        @foreach($tickets as $ticket)
                        <div class="ticket-item"
                             data-title="{{ strtolower($ticket->title) }}"
                             data-location="{{ strtolower($ticket->location ?? '') }}"
                             data-status="{{ $ticket->status }}"
                             data-priority="{{ $ticket->priority }}">

                            <div class="ticket-priority-bar ticket-priority-{{ $ticket->priority }}"></div>

                            <div class="ticket-content">

                                <div class="ticket-top">
                                    <span class="ticket-title">{{ $ticket->title }}</span>
                                    <span class="badge badge-priority badge-priority-{{ $ticket->priority }}">
                                        {{ strtoupper($ticket->priority) }}
                                    </span>
                                    <span class="badge badge-status badge-status-{{ $ticket->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </div>

                                <div class="ticket-meta">
                                    <span>{{ $ticket->location ?? '-' }}</span>
                                    <span>{{ $ticket->reported_at ? \Carbon\Carbon::parse($ticket->reported_at)->format('Y-m-d H:i') : '-' }}</span>
                                    <span class="ticket-id">#TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>

                                <div class="ticket-footer">
                                    <span class="ticket-reporter">
                                        {{ __('app.reported_by') }}
                                        <strong>{{ $ticket->reporter->name ?? 'Unknown' }}</strong>
                                    </span>
                                    <div class="ticket-actions">
                                        <a href="{{ route('teknisi.laporan.show', $ticket->id) }}"
                                           class="btn-detail">{{ __('app.tek_view_detail') }}</a>

                                        @if($ticket->status === 'open')
                                            <a href="{{ route('teknisi.tugas.kerjakan', $ticket->id) }}"
                                               class="btn-kerjakan">{{ __('app.tek_start') }}</a>
                                        @elseif($ticket->status === 'in_progress')
                                            <a href="{{ route('teknisi.tugas.selesai', $ticket->id) }}"
                                               class="btn-selesai">{{ __('app.tek_mark_done') }}</a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- ── Kanan: Sidebar ── --}}
        <div class="dashboard-sidebar">

            {{-- Aktivitas Terbaru --}}
            <div class="sidebar-widget">
                <div class="widget-header">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ __('app.tek_recent_activity') }}</span>
                </div>
                <div class="widget-body">
                    @php
                        $recent = $tickets->whereNotIn('status', ['open'])->take(4);
                    @endphp
                    @if($recent->isEmpty())
                        <p class="widget-empty">{{ __('app.tek_no_activity') }}</p>
                    @else
                        @foreach($recent as $t)
                        <div class="activity-item">
                            <span class="activity-dot activity-dot-{{ $t->status }}"></span>
                            <div class="activity-info">
                                <span class="activity-title">{{ Str::limit($t->title, 30) }}</span>
                                <span class="activity-time">
                                    {{ \Carbon\Carbon::parse($t->updated_at)->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Ringkasan Hari Ini --}}
            <div class="sidebar-widget">
                <div class="widget-header">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>{{ __('app.tek_today_summary') }}</span>
                </div>
                <div class="widget-body">
                    @php
                        $today      = now()->toDateString();
                        $openCount  = $tickets->where('status', 'open')->count();
                        $inProgress = $tickets->where('status', 'in_progress')->count();
                        $doneToday  = $tickets->whereIn('status', ['resolved', 'closed'])
                                              ->filter(fn($t) => optional($t->updated_at)->toDateString() === $today)
                                              ->count();
                    @endphp
                    <div class="summary-row">
                        <span class="summary-label">{{ __('app.tek_unfinished') }}</span>
                        <span class="summary-val summary-red">{{ $openCount }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">{{ __('app.tek_in_progress') }}</span>
                        <span class="summary-val summary-orange">{{ $inProgress }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">{{ __('app.tek_done_today') }}</span>
                        <span class="summary-val summary-green">{{ $doneToday }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">{{ __('app.tek_avg_time') }}</span>
                        <span class="summary-val">-</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection