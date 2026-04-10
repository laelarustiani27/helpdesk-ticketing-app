<?php

namespace App\Http\Controllers;

use App\Models\LaporanPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class PelangganController extends Controller
{
    // ─── Dashboard ────
    public function dashboard()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        $laporan   = LaporanPelanggan::where('pelanggan_id', $pelanggan->id)->latest()->get();

        $total    = $laporan->count();
        $proses   = $laporan->whereIn('status', ['disetujui', 'diproses'])->count();
        $selesai  = $laporan->where('status', 'selesai')->count();
        $terbaru  = $laporan->take(5);

        return view('pelanggan.dashboard', compact('pelanggan', 'laporan', 'total', 'proses', 'selesai', 'terbaru'));
    }

    // ─── Form Laporan ──────
    public function index()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        return view('pelanggan.laporan', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $request->validate([
            'jenis_masalah' => 'required|string|max:255',
            'deskripsi'     => 'required|string|max:2000',
        ], [
            'jenis_masalah.required' => 'Jenis masalah wajib dipilih.',
            'deskripsi.required'     => 'Deskripsi masalah wajib diisi.',
        ]);

        $laporan = LaporanPelanggan::create([
            'pelanggan_id'  => $pelanggan->id,
            'nomor_laporan' => LaporanPelanggan::generateNomor(),
            'nama_pelapor'  => $pelanggan->nama,
            'no_telepon'    => $pelanggan->no_telepon,
            'email'         => $pelanggan->email,
            'alamat'        => $pelanggan->alamat,
            'jenis_masalah' => $request->jenis_masalah,
            'deskripsi'     => $request->deskripsi,
            'status'        => 'menunggu',
        ]);

       try {
            \App\Helpers\NotificationHelper::notifyCustomerReport($laporan, $pelanggan);
        } catch (\Exception $e) {
            dd('ERROR NOTIFIKASI: ' . $e->getMessage());
        }

        return redirect()->route('pelanggan.dashboard')
                        ->with('success', 'Laporan berhasil dikirim! Menunggu persetujuan admin.');
    }

    // ─── Profil ─────────
    public function profil()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        return view('pelanggan.profil', compact('pelanggan'));
    }

    public function updateProfil(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $request->validate([
            'nama'      => 'required|string|max:255',
            'no_telepon'=> 'required|string|max:20',
            'email'     => 'nullable|email|max:255',
            'alamat'    => 'required|string|max:500',
        ]);

        $pelanggan->update($request->only('nama', 'no_telepon', 'email', 'alamat'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min'      => 'Password baru minimal 6 karakter.',
            'password_baru.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->password_lama, $pelanggan->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $pelanggan->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function deleteLaporan($id)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $laporan = LaporanPelanggan::where('id', $id)
            ->where('pelanggan_id', $pelanggan->id)
            ->firstOrFail();

        $laporan->delete();

        return redirect()->route('pelanggan.dashboard')
            ->with('success', 'Laporan berhasil dihapus.');
    }

   public function downloadLaporan()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        $laporan = LaporanPelanggan::where('pelanggan_id', $pelanggan->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $html = '
        <style>
            body { font-family: Arial, sans-serif; font-size: 13px; color: #333; }
            h2 { color: #2bb0a6; margin-bottom: 4px; }
            p { margin: 4px 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 16px; }
            th { background: #f8faf9; padding: 10px; text-align: left; font-size: 11px; color: #666; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
            td { padding: 10px; border-bottom: 1px solid #f1f5f9; font-size: 12px; }
        </style>
        <h2>NetRespond — Riwayat Laporan</h2>
        <p>Nama: <strong>' . $pelanggan->nama . '</strong> &nbsp;|&nbsp; No. Pelanggan: <strong>' . $pelanggan->no_pelanggan . '</strong></p>
        <p>Dicetak: ' . now()->format('d M Y, H:i') . '</p>
        <table>
            <thead>
                <tr>
                    <th>No. Laporan</th>
                    <th>Jenis Masalah</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Catatan Admin</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($laporan as $item) {
            $html .= '<tr>
                <td>' . $item->nomor_laporan . '</td>
                <td>' . $item->jenis_masalah . '</td>
                <td>' . $item->created_at->format('d M Y') . '</td>
                <td>' . ucfirst($item->status) . '</td>
                <td>' . ($item->catatan_admin ?? '-') . '</td>
            </tr>';
        }

        $html .= '</tbody></table>';

        $pdf = Pdf::loadHTML($html);
        return $pdf->download('laporan-' . $pelanggan->no_pelanggan . '.pdf');
    }
    // ─── Logout 
    public function logout(Request $request)
    {
        Auth::guard('pelanggan')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('pelanggan.login');
    }
}