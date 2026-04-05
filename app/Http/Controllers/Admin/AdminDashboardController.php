<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Ticket;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        if (Hash::check($user->tanggal_lahir, $user->password)) {
            return redirect()->route('admin.settings.index')
                ->with('warning', 'Demi keamanan, silakan ganti password default Anda (tanggal lahir) terlebih dahulu!');
        }

        $stats = [
            'critical'     => Ticket::where('status', 'critical')->count(),
            'warning'      => Ticket::where('status', 'warning')->count(),
            'resolved'     => Ticket::where('status', 'resolved')->count(),
            'total_issues' => Ticket::count(),
        ];

        $issues = Ticket::with(['teknisi', 'pelapor'])
            ->where('status', '!=', 'resolved')
            ->orderByRaw("FIELD(status, 'critical', 'warning', 'open', 'in_progress')")
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->orderBy('reported_at', 'desc')
            ->get();

        $teknisi_list = User::where('role', 'teknisi')
            ->select('id', 'nama_lengkap')
            ->get();

        return view('admin.dashboard', compact('stats', 'issues', 'teknisi_list'));
    }

    public function showTicket($id)
    {
        $ticket       = Ticket::with(['pelapor', 'teknisi'])->findOrFail($id);
        $teknisi_list = User::where('role', 'teknisi')->get();

        return view('admin.ticket-detail', compact('ticket', 'teknisi_list'));
    }

    public function assignTeknisi(Request $request, $id)
    {
        $request->validate([
            'teknisi_id' => 'required|exists:users,id',
        ]);

        $ticket  = Ticket::findOrFail($id);
        $teknisi = User::findOrFail($request->teknisi_id);

        $ticket->update([
            'assigned_to' => $teknisi->id,
            'status'      => 'in_progress', 
        ]);

        NotificationHelper::notifyIssueAssigned($ticket, $teknisi);

        return response()->json([
            'success' => true,
            'message' => "Tiket berhasil ditugaskan ke {$teknisi->nama_lengkap}",
        ]);
    }

    public function resolveTicket(Request $request, $id)
    {
        $request->validate([
            'catatan_resolusi' => 'nullable|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'status'           => 'resolved',
            'resolved_at'      => now(),
            'catatan_resolusi' => $request->catatan_resolusi
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Tiket berhasil diselesaikan.');
    }

    public function getStats()
    {
        $stats = [
            'critical'     => Ticket::where('status', 'critical')->count(),
            'warning'      => Ticket::where('status', 'warning')->count(),
            'resolved'     => Ticket::where('status', 'resolved')->count(),
            'total_issues' => Ticket::count(),
        ];

        return response()->json($stats);
    }

    public function assignTicket(Request $request, $id)
    {
        $request->validate([
            'teknisi_id' => 'required|exists:users,id',
        ]);

        $ticket = Ticket::findOrFail($id);
        $teknisi = User::findOrFail($request->teknisi_id);
        
        $ticket->update([
            'assigned_to' => $request->teknisi_id,
            'status'      => 'in_progress',
        ]);

        NotificationHelper::notifyIssueAssigned($ticket, $teknisi);

        return response()->json([
            'success' => true,
            'message' => "Tiket berhasil ditugaskan ke {$teknisi->nama_lengkap}",
        ]);
    }
}