<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\UpdatesPreferences;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    use UpdatesPreferences;

    public function index()
    {
        $user = Auth::user();

        $settings = [
            'notif_enabled' => $user->notif_enabled ?? true,
            'notif_ticket'  => $user->notif_ticket  ?? true,
            'notif_assign'  => $user->notif_assign  ?? true,
            'language'      => $user->language      ?? 'id',
            'theme'         => $user->theme         ?? 'light',
        ];

        $autoBackup = file_exists(config_path('backup.php'))
            ? config('backup.auto_backup', false)
            : false;

        return view('admin.settings', compact('settings', 'autoBackup'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function updateNotifications(Request $request)
    {
        Auth::user()->update([
            'notif_enabled' => $request->has('notif_enabled'),
            'notif_ticket'  => $request->has('notif_ticket'),
            'notif_assign'  => $request->has('notif_assign'),
        ]);

        return back()->with('success', 'Pengaturan notifikasi disimpan.');
    }

    public function exportCsv()
    {
        $tickets  = Ticket::with(['pelapor', 'teknisi'])->orderBy('created_at', 'desc')->get();
        $filename = 'tickets_semua_' . now()->format('Ymd_His') . '.csv';
        return $this->streamCsv($tickets, $filename);
    }

    public function exportByMonth(Request $request)
    {
        $request->validate(['bulan' => 'required|date_format:Y-m']);

        [$year, $month] = explode('-', $request->bulan);

        $tickets = Ticket::with(['pelapor', 'teknisi'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'tickets_' . $request->bulan . '_' . now()->format('His') . '.csv';
        return $this->streamCsv($tickets, $filename);
    }

    public function toggleBackup()
    {
        $configPath = config_path('backup.php');
        $current    = file_exists($configPath) ? config('backup.auto_backup', false) : false;
        $new        = ! $current;

        file_put_contents(
            $configPath,
            "<?php\n\nreturn [\n    'auto_backup' => " . ($new ? 'true' : 'false') . ",\n];\n"
        );

        return back()->with('success', $new ? 'Auto Backup diaktifkan.' : 'Auto Backup dinonaktifkan.');
    }

    private function streamCsv($tickets, string $filename)
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($tickets) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'No', 'Waktu Dibuat', 'Nama Tempat', 'Alamat', 'Koordinat',
                'No Telepon', 'No Lain', 'Email', 'Jenis Pemesanan',
                'Pelapor', 'Teknisi', 'Status', 'Prioritas', 'Lokasi',
                'Waktu Perangkat Mati', 'Waktu Selesai',
            ]);

            foreach ($tickets as $i => $ticket) {
                fputcsv($handle, [
                    $i + 1,
                    optional($ticket->created_at)->format('d/m/Y H:i:s'),
                    $ticket->nama_tempat     ?? '-',
                    $ticket->alamat          ?? '-',
                    $ticket->koordinat       ?? '-',
                    $ticket->no_telepon      ?? '-',
                    $ticket->no_lain         ?? '-',
                    $ticket->email           ?? '-',
                    $ticket->jenis_pemesanan ?? '-',
                    optional($ticket->pelapor)->nama_lengkap ?? optional($ticket->pelapor)->name ?? '-',
                    optional($ticket->teknisi)->name          ?? '-',
                    $ticket->status          ?? '-',
                    $ticket->priority        ?? '-',
                    $ticket->location        ?? '-',
                    optional($ticket->reported_at)->format('d/m/Y H:i:s') ?? '-',
                    optional($ticket->resolved_at)->format('d/m/Y H:i:s') ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}