@extends('layouts.pelanggan')

@section('title', 'Profil Saya')

@section('content')
<div style="margin-bottom:22px;">
    <h1 style="font-size:22px; font-weight:800; color:#0f172a; margin:0 0 4px;">Profil Saya</h1>
    <p style="font-size:13px; color:#64748b; margin:0;">Kelola informasi akun Anda</p>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start;">

    {{-- Update Profil --}}
    <div class="plg-card">
        <div class="plg-card-header">
            <div class="plg-card-title">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Pribadi
            </div>
        </div>
        <div class="plg-card-body">
            @if(session('success'))
                <div class="plg-alert plg-alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>
            @endif

            <form action="{{ route('pelanggan.profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="plg-form-group">
                    <label>No. Pelanggan</label>
                    <input type="text" value="{{ $pelanggan->no_pelanggan }}" disabled style="background:#f1f5f9; color:#94a3b8; cursor:not-allowed;">
                </div>

                <div class="plg-form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required>
                    @error('nama') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="plg-form-group">
                    <label for="no_telepon">No. Telepon</label>
                    <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $pelanggan->no_telepon) }}" required>
                    @error('no_telepon') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="plg-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $pelanggan->email) }}" placeholder="Opsional">
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="plg-form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" required style="min-height:80px;">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                    @error('alamat') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div style="display:flex; justify-content:flex-end;">
                    <button type="submit" class="plg-btn plg-btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ganti Password --}}
    <div class="plg-card">
        <div class="plg-card-header">
            <div class="plg-card-title">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Ganti Password
            </div>
        </div>
        <div class="plg-card-body">
            <form action="{{ route('pelanggan.profil.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="plg-form-group">
                    <label for="password_lama">Password Lama</label>
                    <input type="password" id="password_lama" name="password_lama" required placeholder="Masukkan password lama">
                    @error('password_lama') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="plg-form-group">
                    <label for="password_baru">Password Baru</label>
                    <input type="password" id="password_baru" name="password_baru" required placeholder="Minimal 6 karakter">
                    @error('password_baru') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="plg-form-group">
                    <label for="password_baru_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" id="password_baru_confirmation" name="password_baru_confirmation" required placeholder="Ulangi password baru">
                </div>

                <div style="display:flex; justify-content:flex-end;">
                    <button type="submit" class="plg-btn plg-btn-primary">Ganti Password</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection