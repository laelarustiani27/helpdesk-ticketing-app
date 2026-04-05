<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teknisi - NetRespond')</title>

    @if(session('theme') === 'dark')
    <style>
        html, body { background: #0f172a !important; color: #e2e8f0 !important; }
    </style>
    @endif

    <script>
        if (localStorage.getItem('sidebarCollapsed') === '1') {
            document.documentElement.classList.add('sidebar-pre-collapsed');
        }
    </script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('head')
    @stack('styles')
</head>
<body class="admin-theme {{ session('theme') === 'dark' ? 'dark-mode' : '' }}">

<div class="app-shell" id="appShell">

    <aside class="sidebar" id="sidebar">

        <div class="sidebar-logo">
            <div class="sidebar-logo-icon" 
                id="sidebarLogoBtn"
                style="cursor:pointer; background:transparent; box-shadow:none;"
                data-href="{{ route('teknisi.dashboard') }}">
                <img src="{{ asset('images/netrespond-cropped.png') }}" alt="Logo" style="width:34px;height:34px;object-fit:contain;">
            </div>
            <div class="sidebar-logo-text">
                <div class="sidebar-app-name">NetRespond</div>
                <div class="sidebar-app-sub">{{ __('app.teknisi_panel') }}</div>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle" title="{{ __('app.close_sidebar') }}">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>

        @php
            $assignedCount = \App\Models\Ticket::where('assigned_to', auth()->user()->id)->where('status', 'open')->count();
            $unreadCount   = \App\Models\Notification::where('notifiable_id', auth()->user()->id)->whereNull('read_at')->count();
        @endphp

        <nav class="sidebar-nav">
            <a href="{{ route('teknisi.dashboard') }}"
            class="sidebar-nav-item {{ request()->routeIs('teknisi.dashboard') ? 'active' : '' }}">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                <span class="nav-label">{{ __('app.dashboard') }}</span>
            </a>

            <a href="{{ route('teknisi.tugas.index') }}"
            class="sidebar-nav-item {{ request()->routeIs('teknisi.tugas*') ? 'active' : '' }}">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span class="nav-label">{{ __('app.tugas_saya') }}</span>
                @if($assignedCount > 0)
                    <span class="sidebar-badge">{{ $assignedCount }}</span>
                @endif
            </a>

            <a href="{{ route('teknisi.laporan') }}"
            class="sidebar-nav-item {{ request()->routeIs('teknisi.laporan') || request()->routeIs('teknisi.laporan.submit') ? 'active' : '' }}">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="nav-label">{{ __('app.tek_laporan_title') }}</span>
            </a>

            <a href="{{ route('teknisi.riwayat') }}"
            class="sidebar-nav-item {{ request()->routeIs('teknisi.riwayat*') ? 'active' : '' }}">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="nav-label">{{ __('app.riwayat_tugas') }}</span>
            </a>

            <a href="{{ route('teknisi.notifications.index') }}"
            class="sidebar-nav-item {{ request()->routeIs('teknisi.notifications*') ? 'active' : '' }}">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="nav-label">{{ __('app.notifikasi') }}</span>
                @if($unreadCount > 0)
                    <span class="sidebar-badge">{{ $unreadCount }}</span>
                @endif
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(auth()->user()->nama_lengkap ?? auth()->user()->name ?? 'T', 0, 2)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->nama_lengkap ?? auth()->user()->name }}</div>
                    <div class="sidebar-user-role">{{ __('app.technician') }}</div>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="nav-label">{{ __('app.logout') }}</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="app-body" id="appBody">

        <header class="topbar">
            <button class="topbar-menu-btn" id="sidebarOpen" title="{{ __('app.open_sidebar') }}">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="topbar-right">
                <div style="position:relative;">
                    <a href="#" class="topbar-icon-btn" id="notifToggle" onclick="toggleNotifDropdown(event)" title="{{ __('app.notifikasi') }}">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span id="notificationBadge" class="topbar-notif-badge" style="display:none;"></span>
                    </a>
                    <div id="notifDropdown" style="display:none; position:absolute; top:calc(100% + 10px); right:0;
                         width:360px; background:#fff; border:1px solid #e2e8f0; border-radius:14px;
                         box-shadow:0 12px 40px rgba(0,0,0,0.15); z-index:9999; overflow:hidden;">
                        <div style="display:flex; justify-content:space-between; align-items:center;
                                    padding:16px 18px 12px; border-bottom:1px solid #f1f5f4;">
                            <span style="font-size:15px; font-weight:700; color:#0f172a;">{{ __('app.notifikasi') }}</span>
                            <a href="{{ route('teknisi.notifications.index') }}"
                               style="font-size:12px; color:#64748b; text-decoration:none; font-weight:600;">
                                {{ __('app.see_all') }}
                            </a>
                        </div>
                        <div id="dropdownList" style="max-height:340px; overflow-y:auto;">
                            <div style="padding:24px; text-align:center; color:#94a3b8; font-size:13px;">{{ __('app.loading') }}</div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('teknisi.settings.index') }}" class="topbar-icon-btn" title="{{ __('app.pengaturan') }}">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </a>

                <a href="{{ route('profile') }}" class="topbar-avatar" title="{{ __('app.my_profile') }}">
                    {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'T', 0, 2)) }}
                </a>
            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>
    </div>
</div>

<script>
window.appConfig = {
    notifications: {
        list   : "{{ route('teknisi.notifications.list') }}",
        unread : "{{ route('teknisi.notifications.unread-count') }}",
        readAll: "{{ route('teknisi.notifications.mark-all-read') }}"
    }
};
</script>

@vite(['resources/js/app.js'])
@stack('scripts')

</body>
</html>