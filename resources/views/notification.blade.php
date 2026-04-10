@extends('layouts.admin')

@section('title', __('app.notifications'))

@section('content')
<div class="notif-container">

    <div class="notif-page-header">
        <div>
            <h1 class="riwayat-title" style="font-size:22px;">{{ __('app.notifications') }}</h1>
            <p style="font-size:13px; color:#64748b; margin:2px 0 0 0;">{{ __('app.notifications_page_desc') }}</p>
        </div>
        <div class="notif-header-actions">
            <button class="btn-mark-all" id="btnMarkAll">{{ __('app.mark_all_read') }}</button>
            <button class="btn-clear-all" id="btnClearAll">{{ __('app.delete_all') }}</button>
        </div>
    </div>

    <div class="notif-filter-tabs">
        <button class="notif-tab active" data-filter="all">
            {{ __('app.all') }} <span class="tab-count" id="countAll">0</span>
        </button>
        <button class="notif-tab" data-filter="pelanggan">
            {{ __('pelanggan') }} <span class="tab-count" id="countPelanggan">0</span>
        </button>
        <button class="notif-tab" data-filter="teknisi">
            {{ __('app.technician') }} <span class="tab-count" id="countTeknisi">0</span>
        </button>
        <button class="notif-tab" data-filter="unread">
            {{ __('app.unread') }} <span class="tab-count unread-count" id="countUnread">0</span>
        </button>
    </div>

    <div class="notif-list" id="notifList">
        @forelse($notifications as $n)
        @php
            $data   = $n->data;
            $type   = $data['type'] ?? 'sistem';
            $isRead = !is_null($n->read_at);
        @endphp

        <div class="notif-item {{ $isRead ? 'read' : 'unread' }}"
             data-type="{{ $type }}"
             data-id="{{ $n->id }}">

            <div class="notif-item-indicator {{ $type }}"></div>

            <div class="notif-item-icon {{ $type }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                     fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
            </div>

            <div class="notif-item-content">
                <div class="notif-item-header">
                    <div class="notif-item-title-group">
                        <span class="notif-source-badge {{ $type }}">
                            {{ ucfirst($type) }}
                        </span>
                        @if(!$isRead)
                            <span class="notif-unread-dot"></span>
                        @endif
                    </div>
                    <span class="notif-item-time">
                        {{ $n->created_at->format('H:i • d M Y') }}
                    </span>
                </div>

                <h3 class="notif-item-title">
                    {{ $data['title'] ?? __('app.notifications') }}
                </h3>

                @if(!empty($data['location']))
                <div class="notif-item-meta">
                    <span class="meta-chip location">{{ $data['location'] }}</span>
                    @if(!empty($data['priority']))
                        <span class="meta-chip priority-{{ $data['priority'] }}">
                            {{ strtoupper($data['priority']) }}
                        </span>
                    @endif
                </div>
                @endif

                <div class="notif-item-actions">
                    @if(!empty($data['ticket_id']))
                        <button class="btn-assign"
                                data-id="{{ $n->id }}"
                                data-ticket="{{ $data['ticket_id'] }}">
                            {{ __('app.assign_technician') }}
                        </button>
                        <a href="{{ route('admin.tickets.show', $data['ticket_id']) }}"
                           class="btn-lihat-tiket">
                            {{ __('app.view_ticket') }}
                        </a>
                    @endif

                    @if(!$isRead)
                        <button class="btn-mark-read" data-id="{{ $n->id }}">
                            {{ __('app.mark_read') }}
                        </button>
                    @endif

                    <button class="btn-delete" data-id="{{ $n->id }}">
                        {{ __('app.delete') }}
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="notif-empty">
            <div class="empty-icon-wrapper">
                <svg class="empty-icon" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
            </div>
            <p class="empty-state-text">{{ __('app.no_notifications') }}</p>
        </div>
        @endforelse
    </div>

    <div class="notif-empty" id="notifEmpty" style="display:none;">
        <p class="empty-state-text">{{ __('app.no_notifications') }}</p>
    </div>

</div>

<div class="modal-overlay" id="assignModal" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('app.assign_technician') }}</h2>
            <button class="modal-close" id="closeModal">×</button>
        </div>
        <div class="teknisi-list">
            @foreach($teknisi_list as $tek)
            <label class="teknisi-option">
                <input type="radio" name="teknisi" value="{{ $tek->id }}">
                <div class="teknisi-option-content">
                    <div class="teknisi-avatar">
                        {{ strtoupper(substr($tek->nama_lengkap, 0, 2)) }}
                    </div>
                    <div>
                        <div class="teknisi-name">{{ $tek->nama_lengkap }}</div>
                        <div class="teknisi-status available">{{ __('app.available') }}</div>
                    </div>
                </div>
            </label>
            @endforeach
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" id="cancelAssign">{{ __('app.cancel') }}</button>
            <button class="btn-confirm" id="confirmAssign">{{ __('app.assign') }}</button>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

@endsection