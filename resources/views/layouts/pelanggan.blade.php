<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Pelanggan') - NetRespond</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="plg-body">
    <div class="plg-topbar">
        <a href="{{ route('pelanggan.dashboard') }}" class="plg-logo">
            <img src="{{ asset('images/netrespond-cropped.png') }}" alt="Logo" style="width:28px;height:28px;object-fit:contain;">
            <span class="plg-logo-name">NetRespond</span>
        </a>
        <div class="plg-topbar-right">
            <div class="plg-avatar-wrap">
                <div class="plg-avatar" onclick="toggleDropdown()" id="plgAvatar">
                    {{ strtoupper(substr(Auth::guard('pelanggan')->user()->nama, 0, 2)) }}
                </div>
                <div class="plg-dropdown" id="plgDropdown">
                    <div class="plg-dropdown-header">
                        <div class="plg-dropdown-name">{{ Auth::guard('pelanggan')->user()->nama }}</div>
                        <div class="plg-dropdown-no">{{ Auth::guard('pelanggan')->user()->no_pelanggan }}</div>
                    </div>
                    <a href="{{ route('pelanggan.profil') }}" class="plg-dropdown-item">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Saya
                    </a>
                    <div class="plg-dropdown-divider"></div>
                    <a href="{{ route('pelanggan.laporan.download') }}" class="plg-dropdown-item">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download pdf
                    </a>
                    <div class="plg-dropdown-divider"></div>
                    <form action="{{ route('pelanggan.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="plg-dropdown-item danger">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="plg-main">
        @if(session('success'))
            <div class="plg-alert plg-alert-success">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="plg-alert plg-alert-error">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    @vite(['resources/js/app.js'])
    <script>
        function toggleDropdown() {
            document.getElementById('plgDropdown').classList.toggle('show');
        }

        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('plgAvatar');
            const drop = document.getElementById('plgDropdown');
            if (wrap && !wrap.contains(e.target) && drop && !drop.contains(e.target)) {
                drop.classList.remove('show');
            }
        });
    </script>
</body>
</html>