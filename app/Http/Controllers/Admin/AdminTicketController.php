<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Pelanggan;
use App\Mail\LaporanStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminTicketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->format === 'json') {
            $tickets = Ticket::where('status', 'open')
                ->whereNull('assigned_to')
                ->get(['id', 'title', 'priority']);
            return response()->json($tickets);
        }

        $stats = [
            'total'       => Ticket::count(),
            'open'        => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved'    => Ticket::where('status', 'resolved')->count(),
            'critical'    => Ticket::where('priority', 'critical')->count(),
        ];

        $tickets = Ticket::with(['pelapor', 'teknisi', 'pelanggan'])
            ->when(!$request->status, fn($q) => $q->whereNotIn('status', ['resolved', 'closed']))
            ->when($request->search,   fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->status,   fn($q) => $q->where('status', $request->status))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $laporanPelanggan = \App\Models\LaporanPelanggan::with('pelanggan')
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->latest()
            ->get();

        $laporanStats = [
            'menunggu'  => $laporanPelanggan->where('status', 'menunggu')->count(),
            'disetujui' => $laporanPelanggan->where('status', 'disetujui')->count(),
            'ditolak'   => $laporanPelanggan->where('status', 'ditolak')->count(),
        ];

        $pelanggans = Pelanggan::orderBy('no_pelanggan')->get();

        return view('admin.semua-tiket', compact(
            'tickets', 'stats', 'laporanPelanggan', 'laporanStats', 'pelanggans'
        ));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['pelapor', 'teknisi', 'pelanggan'])->findOrFail($id);

        if (in_array($ticket->status, ['resolved', 'closed'])) {
            return redirect()->route('admin.riwayat.show', $id);
        }
        
        $teknisi_list = \App\Models\User::where('role', 'teknisi')->where('is_active', 1)->get();
        
        return view('admin.ticket-detail', compact('ticket', 'teknisi_list'));
    }

    public function assign(Request $request, $id)
    {
        $ticket  = Ticket::findOrFail($id);
        $teknisi = \Illuminate\Support\Facades\DB::table('teknisi')
                    ->where('user_id', $request->teknisi_id)
                    ->first();

        if (!$teknisi) {
            return response()->json(['success' => false, 'message' => 'Teknisi tidak ditemukan.'], 404);
        }

        $ticket->update([
            'assigned_to' => $teknisi->user_id,
            'status'      => 'in_progress',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Teknisi berhasil di-assign ke {$teknisi->name}",
            ]);
        }

        return redirect()->route('admin.tickets.show', $id)
                        ->with('success', 'Teknisi berhasil di-assign.');
    }

    public function resolve($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);

        $laporanList = \App\Models\LaporanPelanggan::where('ticket_id', $ticket->id)->get();
        foreach ($laporanList as $laporan) {
            $laporan->update(['status' => 'selesai']);
            if ($laporan->email) {
                try {
                    Mail::to($laporan->email)->send(new LaporanStatusChanged($laporan->fresh()));
                } catch (\Exception $e) {}
            }
        }

        return redirect()->route('admin.riwayat')
                         ->with('success', 'Tiket berhasil diselesaikan.');
    }

    public function create()
    {
        return redirect()->route('admin.tickets.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'    => 'nullable|exists:pelanggans,id',
            'nama_tempat'     => 'required|string|max:255',
            'alamat'          => 'required|string',
            'no_telepon'      => 'required|string|max:20',
            'email'           => 'required|email|max:255',
            'jenis_pemesanan' => 'required|string',
            'teknisi_id'      => 'nullable|exists:users,id',
        ]);

        // Jika pelanggan dipilih, ambil data dari pelanggan
        $pelanggan = null;
        if ($request->pelanggan_id) {
            $pelanggan = Pelanggan::find($request->pelanggan_id);
        }

        $ticket = Ticket::create([
            'pelanggan_id'    => $request->pelanggan_id,
            'nama_tempat'     => $request->nama_tempat,
            'alamat'          => $pelanggan ? $pelanggan->alamat : $request->alamat,
            'koordinat'       => $request->koordinat,
            'no_telepon'      => $pelanggan ? $pelanggan->no_telepon : $request->no_telepon,
            'email'           => $pelanggan ? $pelanggan->email : $request->email,
            'jenis_pemesanan' => $request->jenis_pemesanan,
            'title'           => $request->nama_tempat,
            'location'        => $pelanggan ? $pelanggan->alamat : $request->alamat,
            'status'          => 'open',
            'priority'        => 'medium',
            'reported_by'     => $pelanggan ? $pelanggan->nama : ($request->nama_pelapor ?? $request->nama_tempat),
            'reported_at'     => now(),
        ]);

        // Langsung assign teknisi jika dipilih
        if ($request->teknisi_id) {
            $ticket->update([
                'assigned_to' => $request->teknisi_id,
                'status'      => 'in_progress',
            ]);
        }

        return redirect()->route('admin.tickets.index')
                        ->with('success', 'Tiket berhasil dibuat.');
    }

    public function approveLaporan(Request $request, $id)
    {
        $laporan = \App\Models\LaporanPelanggan::findOrFail($id);

        $ticket = Ticket::create([
            'pelanggan_id' => $laporan->pelanggan_id ?? null,
            'title'        => $laporan->jenis_masalah,
            'description'  => $laporan->deskripsi,
            'location'     => $laporan->alamat,
            'alamat'       => $laporan->alamat,
            'no_telepon'   => $laporan->no_telepon,
            'email'        => $laporan->email,
            'reported_by'  => $laporan->nama_pelapor,
            'reported_at'  => now(),
            'status'       => 'open',
            'priority'     => 'medium',
        ]);

        $laporan->update([
            'status'    => 'disetujui',
            'ticket_id' => $ticket->id,
        ]);

        if ($laporan->email) {
            try {
                Mail::to($laporan->email)->send(new LaporanStatusChanged($laporan->fresh()));
            } catch (\Exception $e) {}
        }

        return redirect()->route('admin.tickets.index')
                        ->with('success', 'Laporan disetujui, tiket #' . $ticket->id . ' berhasil dibuat.');
    }

    public function rejectLaporan(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ]);

        $laporan = \App\Models\LaporanPelanggan::findOrFail($id);
        $laporan->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
        ]);

        if ($laporan->email) {
            try {
                Mail::to($laporan->email)->send(new LaporanStatusChanged($laporan->fresh()));
            } catch (\Exception $e) {}
        }

        return redirect()->route('admin.tickets.index')
                        ->with('success', 'Laporan telah ditolak.');
    }
}