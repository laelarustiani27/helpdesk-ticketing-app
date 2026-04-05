@extends('layouts.pelanggan')

@section('title', 'Buat Laporan')

@section('content')
<div style="margin-bottom:22px;">
    <h1 style="font-size:22px; font-weight:800; color:#0f172a; margin:0 0 4px;">Buat Laporan</h1>
    <p style="font-size:13px; color:#64748b; margin:0;">Laporkan masalah jaringan Anda</p>
</div>

<div class="plg-card">
    <div class="plg-card-header">
        <div class="plg-card-title">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Form Laporan
        </div>
    </div>
    <div class="plg-card-body">

        {{-- Info pelanggan (readonly) --}}
        <div style="background:#f8faf9; border:1px solid #e2e8f0; border-radius:10px; padding:14px 16px; margin-bottom:20px;">
            <div style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Data Pelapor</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; font-size:13px;">
                <div><span style="color:#64748b;">Nama:</span> <strong style="color:#0f172a;">{{ $pelanggan->nama }}</strong></div>
                <div><span style="color:#64748b;">No. Pelanggan:</span> <strong style="color:#0f172a;">{{ $pelanggan->no_pelanggan }}</strong></div>
                <div><span style="color:#64748b;">No. Telepon:</span> <strong style="color:#0f172a;">{{ $pelanggan->no_telepon }}</strong></div>
                <div><span style="color:#64748b;">Alamat:</span> <strong style="color:#0f172a;">{{ $pelanggan->alamat }}</strong></div>
            </div>
        </div>

        <form action="{{ route('pelanggan.laporan.store') }}" method="POST">
            @csrf

            <div class="plg-form-group">
                <label for="jenis_masalah">Jenis Masalah <span style="color:#ef4444;">*</span></label>
                <select id="jenis_masalah" name="jenis_masalah" required>
                    <option value="">Pilih jenis masalah</option>
                    <option value="Internet Tidak Terhubung"   {{ old('jenis_masalah') === 'Internet Tidak Terhubung'   ? 'selected' : '' }}>Internet Tidak Terhubung</option>
                    <option value="Koneksi Lambat"             {{ old('jenis_masalah') === 'Koneksi Lambat'             ? 'selected' : '' }}>Koneksi Lambat</option>
                    <option value="Gangguan Intermiten"        {{ old('jenis_masalah') === 'Gangguan Intermiten'        ? 'selected' : '' }}>Gangguan Intermiten</option>
                    <option value="Perangkat Rusak"            {{ old('jenis_masalah') === 'Perangkat Rusak'            ? 'selected' : '' }}>Perangkat Rusak</option>
                    <option value="Lainnya"                    {{ old('jenis_masalah') === 'Lainnya'                    ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('jenis_masalah') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="plg-form-group">
                <label for="deskripsi">Deskripsi Masalah <span style="color:#ef4444;">*</span></label>
                <textarea id="deskripsi" name="deskripsi" required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:8px;">
                <a href="{{ route('pelanggan.dashboard') }}" class="plg-btn plg-btn-ghost">Batal</a>
                <button type="submit" class="plg-btn plg-btn-primary">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection