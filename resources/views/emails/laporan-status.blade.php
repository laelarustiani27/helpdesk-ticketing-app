<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DM Sans', Arial, sans-serif; background:#f8fafc; margin:0; padding:0; }
        .container { max-width:560px; margin:40px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
        .header { background:linear-gradient(135deg,#2bb0a6,#059669); padding:32px 40px; }
        .header h1 { color:#fff; font-size:22px; margin:0; font-weight:700; }
        .header p { color:rgba(255,255,255,0.8); margin:6px 0 0; font-size:14px; }
        .body { padding:32px 40px; }
        .status-badge { display:inline-block; padding:6px 18px; border-radius:20px; font-size:13px; font-weight:700; margin:16px 0; }
        .status-selesai { background:rgba(16,185,129,0.1); color:#10b981; }
        .status-diproses { background:rgba(245,158,11,0.1); color:#f59e0b; }
        .status-disetujui { background:rgba(43,176,166,0.1); color:#2bb0a6; }
        .status-ditolak { background:rgba(239,68,68,0.1); color:#ef4444; }
        .info-box { background:#f8fafc; border-radius:10px; padding:16px 20px; margin:20px 0; }
        .info-row { display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px; }
        .info-label { color:#94a3b8; }
        .info-value { color:#1e293b; font-weight:600; }
        .footer { padding:20px 40px; background:#f8fafc; border-top:1px solid #f1f5f9; font-size:12px; color:#94a3b8; text-align:center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NetRespond</h1>
            <p>Update Status Laporan Anda</p>
        </div>
        <div class="body">
            <p style="color:#1e293b; font-size:15px;">Halo, <strong>{{ $laporan->nama_pelapor }}</strong>!</p>
            <p style="color:#64748b; font-size:14px; line-height:1.6;">
                Status laporan Anda telah diperbarui. Berikut informasi terbaru:
            </p>
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Nomor Laporan</span>
                    <span class="info-value">{{ $laporan->nomor_laporan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jenis Masalah</span>
                    <span class="info-value">{{ $laporan->jenis_masalah }}</span>
                </div>
                <div class="info-row" style="margin-bottom:0;">
                    <span class="info-label">Status Terbaru</span>
                    <span class="status-badge status-{{ $laporan->status }}">
                        {{ ucfirst($laporan->status) }}
                    </span>
                </div>
            </div>
            @if($laporan->catatan_admin)
            <div style="background:rgba(239,68,68,0.05); border:1px solid rgba(239,68,68,0.15); border-radius:10px; padding:16px 20px; margin-top:16px;">
                <p style="font-size:13px; font-weight:600; color:#ef4444; margin:0 0 6px;">Catatan Admin:</p>
                <p style="font-size:13px; color:#64748b; margin:0;">{{ $laporan->catatan_admin }}</p>
            </div>
            @endif
            <p style="color:#64748b; font-size:13px; margin-top:24px;">
                Anda dapat melihat detail laporan dengan login ke portal pelanggan NetRespond.
            </p>
        </div>
        <div class="footer">
            © {{ date('Y') }} NetRespond. Email ini dikirim otomatis, mohon tidak membalas.
        </div>
    </div>
</body>
</html>