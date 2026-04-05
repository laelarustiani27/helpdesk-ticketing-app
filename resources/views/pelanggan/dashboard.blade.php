@extends('layouts.pelanggan')

@section('title', 'Dashboard')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; flex-wrap:wrap; gap:12px;">
    <div>
        <h1 style="font-size:22px; font-weight:800; color:#0f172a; margin:0;">
            Selamat datang, {{ $pelanggan->nama }}
        </h1>
        <p style="font-size:13px; color:#64748b; margin:4px 0 0;">
            No. Pelanggan: <strong>{{ $pelanggan->no_pelanggan }}</strong>
        </p>
    </div>
    <a href="{{ route('pelanggan.laporan.index') }}" class="plg-btn plg-btn-primary">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Laporan
    </a>
</div>

@if($total > 0)
    <div class="plg-stats">
        <div class="plg-stat">
            <div class="plg-stat-icon" style="background:rgba(43,176,166,0.1);">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#2bb0a6" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <div class="plg-stat-label">Total Laporan</div>
                <div class="plg-stat-value" style="color:#2bb0a6;">{{ $total }}</div>
            </div>
        </div>
        <div class="plg-stat">
            <div class="plg-stat-icon" style="background:rgba(245,158,11,0.1);">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="plg-stat-label">Sedang Diproses</div>
                <div class="plg-stat-value" style="color:#f59e0b;">{{ $proses }}</div>
            </div>
        </div>
        <div class="plg-stat">
            <div class="plg-stat-icon" style="background:rgba(16,185,129,0.1);">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="plg-stat-label">Selesai</div>
                <div class="plg-stat-value" style="color:#10b981;">{{ $selesai }}</div>
            </div>
        </div>
    </div>

    <div class="plg-card">
        <div class="plg-card-header">
            <div class="plg-card-title">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Laporan Saya
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr style="background:#f8faf9;">
                        <th style="padding:12px 18px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #e2e8f0;">No. Laporan</th>
                        <th style="padding:12px 18px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #e2e8f0;">Jenis Masalah</th>
                        <th style="padding:12px 18px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #e2e8f0;">Tanggal</th>
                        <th style="padding:12px 18px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #e2e8f0;">Status</th>
                        <th style="padding:12px 18px; text-align:left; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #e2e8f0;">Tracking</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($terbaru as $item)
                    <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                        onmouseover="this.style.background='#f8faf9'"
                        onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 18px;">
                            <span style="font-family:monospace; font-size:12px; color:#64748b;">{{ $item->nomor_laporan }}</span>
                        </td>
                        <td style="padding:14px 18px; font-weight:600; color:#0f172a;">{{ $item->jenis_masalah }}</td>
                        <td style="padding:14px 18px; color:#64748b;">{{ $item->created_at->format('d M Y') }}</td>
                        <td style="padding:14px 18px;">
                            <span class="plg-badge plg-badge-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td style="padding:14px 18px; display:flex; gap:8px; align-items:center;">
                            <button onclick="toggleTracking({{ $item->id }})"
                                class="plg-btn plg-btn-ghost"
                                style="padding:5px 12px; font-size:12px;">
                                Lihat Detail
                            </button>

                            <form action="{{ route('pelanggan.laporan.delete', $item->id) }}"
                                method="POST"
                                onsubmit="return confirm('Hapus laporan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="
                                    background:#fee2e2;
                                    color:#dc2626;
                                    border:none;
                                    padding:5px 12px;
                                    border-radius:6px;
                                    font-size:12px;
                                    font-weight:600;
                                    cursor:pointer;
                                ">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:24px;">
        <h2 style="font-size:16px; font-weight:700; color:#0f172a; margin:0 0 16px;">Tracking Laporan</h2>
        @foreach($terbaru as $item)
        <div class="plg-card" style="margin-bottom:16px; display:none;" id="tracking-{{ $item->id }}">
            <div class="plg-card-header">
                <div class="plg-card-title">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    {{ $item->nomor_laporan }} — {{ $item->jenis_masalah }}
                </div>
                <span class="plg-badge plg-badge-{{ $item->status }}">{{ ucfirst($item->status) }}</span>
            </div>
            <div class="plg-card-body">
                <div class="plg-timeline">

                    <div class="plg-timeline-item">
                        <div class="plg-timeline-dot done">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="plg-timeline-content">
                            <div class="plg-timeline-title">Laporan Dikirim</div>
                            <div class="plg-timeline-desc">{{ $item->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    <div class="plg-timeline-item">
                        <div class="plg-timeline-dot {{ in_array($item->status, ['disetujui','diproses','selesai']) ? 'done' : ($item->status === 'ditolak' ? 'reject' : 'pending') }}">
                            @if($item->status === 'ditolak')
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @else
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="{{ in_array($item->status, ['disetujui','diproses','selesai']) ? '#fff' : '#94a3b8' }}" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </div>
                        <div class="plg-timeline-content">
                            <div class="plg-timeline-title">
                                @if($item->status === 'ditolak') Ditolak Admin
                                @elseif(in_array($item->status, ['disetujui','diproses','selesai'])) Disetujui Admin
                                @else Menunggu Persetujuan Admin
                                @endif
                            </div>
                            @if($item->catatan_admin)
                                <div class="plg-timeline-desc">Catatan: {{ $item->catatan_admin }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="plg-timeline-item">
                        <div class="plg-timeline-dot {{ in_array($item->status, ['diproses','selesai']) ? 'active' : 'pending' }}">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="{{ in_array($item->status, ['diproses','selesai']) ? '#fff' : '#94a3b8' }}" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="plg-timeline-content">
                            <div class="plg-timeline-title">Sedang Diproses Teknisi</div>
                            <div class="plg-timeline-desc">Teknisi sedang menangani laporan Anda</div>
                        </div>
                    </div>

                    <div class="plg-timeline-item">
                        <div class="plg-timeline-dot {{ $item->status === 'selesai' ? 'done' : 'pending' }}">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="{{ $item->status === 'selesai' ? '#fff' : '#94a3b8' }}" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="plg-timeline-content">
                            <div class="plg-timeline-title">Selesai</div>
                            <div class="plg-timeline-desc">Masalah telah diselesaikan</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>

@else
    <div class="plg-card">
        <div class="plg-empty">
            <div class="plg-empty-icon">
                <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="plg-empty-title">Belum ada laporan</p>
            <p class="plg-empty-desc">Buat laporan pertama Anda jika ada masalah jaringan</p>
            <a href="{{ route('pelanggan.laporan.index') }}" class="plg-btn plg-btn-primary">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Laporan Pertama
            </a>
        </div>
    </div>
@endif

<script>
function toggleTracking(id) {
    const el = document.getElementById('tracking-' + id);
    if (el.style.display === 'none' || el.style.display === '') {
        el.style.display = 'block';
        setTimeout(() => el.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
    } else {
        el.style.display = 'none';
    }
}
</script>
@endsection