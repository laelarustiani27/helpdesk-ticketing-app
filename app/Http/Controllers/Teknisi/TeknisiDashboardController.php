<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Helpers\NotificationHelper;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeknisiDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tickets = Ticket::where('assigned_to', $user->id)
            ->latest()
            ->get();

        $criticalCount = $tickets->where('priority', 'critical')->count();
        $warningCount  = $tickets->whereIn('priority', ['high', 'medium'])->count();
        $resolvedCount = $tickets->whereIn('status', ['resolved', 'closed'])->count();
        $totalIssues   = $tickets->count();

        return view('teknisi.dashboard', compact(
            'tickets',
            'criticalCount',
            'warningCount',
            'resolvedCount',
            'totalIssues'
        ));
    }

    public function showLaporanForm()
    {
        return view('teknisi.laporan');
    }

    public function showLaporan($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('teknisi.detail-tugas', compact('ticket'));
    }

    public function detailTugas($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('teknisi.detail-tugas', compact('ticket'));
    }

    public function kerjakan($id)
    {
        Ticket::findOrFail($id)->update(['status' => 'in_progress']);
        return redirect()->route('teknisi.tugas.index')
            ->with('success', 'Tiket sedang dikerjakan.');
    }

    public function selesai($id)
    {
        Ticket::findOrFail($id)->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);
        return redirect()->route('teknisi.tugas.index')
            ->with('success', 'Tiket berhasil diselesaikan.');
    }

    public function submitLaporan(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'lokasi'    => 'required|string|max:255',
            'prioritas' => 'required|in:critical,high,medium,low',
            'deskripsi' => 'required|string',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('tickets', 'public');
        }

        $ticket = Ticket::create([
            'title'       => $request->judul,
            'description' => $request->deskripsi,
            'location'    => $request->lokasi,
            'priority'    => $request->prioritas,
            'status'      => 'open',
            'foto'        => $fotoPath,
            'reported_by' => Auth::id(),
            'assigned_to' => null,
            'reported_at' => now(),
            'is_active'   => true,
        ]);

        NotificationHelper::notifyTeknisiReport($ticket, Auth::user());

        return redirect()->route('teknisi.laporan')
            ->with('success', 'Laporan berhasil dikirim. Admin akan segera menindaklanjuti.');
    }

    public function riwayat()
    {
        $user = Auth::user();
        $tickets = Ticket::where(function($q) use ($user) {
                        $q->where('assigned_to', $user->id)
                          ->orWhere('reported_by', $user->id);
                    })
                    ->whereIn('status', ['resolved', 'closed'])
                    ->latest()
                    ->get();

        return view('teknisi.riwayat', compact('tickets'));
    }

    public function tugas()
    {
        $user = Auth::user();

        $tickets = Ticket::where(function($q) use ($user) {
                        $q->where('assigned_to', $user->id)
                        ->orWhere('reported_by', $user->id);
                    })
                    ->whereNotIn('status', ['resolved', 'closed'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('teknisi.my-jobs', compact('tickets'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();

        $ticket = Ticket::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('reported_by', $user->id);
            })
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
        ]);

        $ticket->update([
            'status'      => $request->status,
            'resolved_at' => $request->status === 'resolved' ? now() : null,
        ]);

        return response()->json(['success' => true, 'status' => $ticket->status]);
    }

    public function getKomentar($id)
    {
        $user = Auth::user();

        $ticket = Ticket::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('reported_by', $user->id);
            })
            ->firstOrFail();

        if (method_exists($ticket, 'comments')) {
            $comments = $ticket->comments()
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($c) {
                    $user = \App\Models\User::where('id', $c->user_id)->first();
                    return [
                        'id'         => $c->id,
                        'body'       => $c->body,
                        'user_name'  => $user->nama_lengkap ?? $user->name ?? 'Teknisi',
                        'created_at' => $c->created_at,
                    ];
                });
        } else {
            $comments = collect();
        }

        return response()->json(['success' => true, 'data' => $comments]);
    }

    public function storeKomentar(Request $request, $id)
    {
        $user = Auth::user();

        $ticket = Ticket::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('reported_by', $user->id);
            })
            ->firstOrFail();

        $request->validate(['body' => 'required|string|max:2000']);

        if (!method_exists($ticket, 'comments')) {
            return response()->json(['success' => false, 'message' => 'Fitur komentar belum tersedia.'], 422);
        }

        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'body'    => $request->body,
        ]);

        return response()->json(['success' => true, 'comment' => $comment]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:users,username,' . Auth::id(),
            'email'        => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update($request->only('nama_lengkap', 'username', 'email'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|min:8|confirmed',
        ]);

        Auth::user()->update(['password' => bcrypt($request->password)]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function deleteRiwayat($id)
    {
        $user = Auth::user();

        $ticket = Ticket::where('id', $id)
            ->whereIn('status', ['resolved', 'closed'])
            ->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                ->orWhere('reported_by', $user->id);
            })
            ->firstOrFail();

        $ticket->delete();

        return redirect()->route('teknisi.riwayat')
            ->with('success', 'Riwayat berhasil dihapus.');
    }
}