<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NetRespond</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">

        <div class="header">
            <h1>Selamat Datang</h1>
            <p class="subtitle">Masuk ke NetRespond</p>
        </div>

        <div class="login-card">

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- STEP 1: Pilih Role --}}
            <div class="role-select-screen" id="roleSelectScreen">
                <div class="role-select-buttons">

                    <button type="button" class="role-pick-btn admin" onclick="selectRole('admin')">
                        <div class="role-icon">
                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0
                                       0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02
                                       12.02 0 003 9c0 5.591 3.824 10.29 9 11.622
                                       5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="role-label">Admin</span>
                        <span class="role-desc">Kelola sistem</span>
                    </button>

                    <button type="button" class="role-pick-btn teknisi" onclick="selectRole('teknisi')">
                        <div class="role-icon">
                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724
                                       1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724
                                       1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724
                                       1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724
                                       1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724
                                       1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724
                                       1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724
                                       1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608
                                       2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="role-label">Teknisi</span>
                        <span class="role-desc">Tangani laporan</span>
                    </button>

                    <button type="button" class="role-pick-btn pelanggan" onclick="selectRole('pelanggan')">
                        <div class="role-icon">
                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="role-label">Pelanggan</span>
                        <span class="role-desc">Laporkan masalah</span>
                    </button>

                </div>
            </div>

            {{-- STEP 2: Form Login --}}
            <div class="login-form-screen" id="loginFormScreen">

                <button type="button" class="back-btn" onclick="backToRole()">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Ganti role
                </button>

                <div id="roleBadge" class="selected-role-badge admin">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span id="roleBadgeText">Admin</span>
                </div>

                {{-- Form Admin & Teknisi --}}
                <div id="formStaff">
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="role" id="roleInput" value="admin">

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username"
                                   placeholder="Masukkan username"
                                   value="{{ old('username') }}" required>
                            @error('username')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <div style="position: relative;">
                                <input type="password" id="password" name="password"
                                       placeholder="Masukkan password" required>
                                <span class="password-toggle" onclick="togglePassword('password')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0
                                               8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542
                                               7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </span>
                            </div>
                            @error('password')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="login-btn">Masuk</button>
                    </form>
                </div>

                {{-- Form Pelanggan --}}
                <div id="formPelanggan" style="display:none;">
                    <form action="{{ route('pelanggan.login.post') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="no_pelanggan">Nomor Pelanggan</label>
                            <input type="text" id="no_pelanggan" name="no_pelanggan"
                                   placeholder="Contoh: PLG-0001"
                                   value="{{ old('no_pelanggan') }}" required>
                            @error('no_pelanggan')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_pelanggan">Password</label>
                            <div style="position: relative;">
                                <input type="password" id="password_pelanggan" name="password"
                                       placeholder="Masukkan password" required>
                                <span class="password-toggle" onclick="togglePassword('password_pelanggan')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0
                                               8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542
                                               7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </span>
                            </div>
                            @error('password')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="login-btn login-btn--pelanggan">
                            Masuk
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>

   <script>
        window.loginErrors = {{ json_encode($errors->any()) }};
        window.oldRole     = "{{ old('role', 'admin') }}";
    </script>
    @vite(['resources/js/app.js'])
</body>
</html>