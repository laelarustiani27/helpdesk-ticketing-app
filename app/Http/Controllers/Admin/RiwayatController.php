<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['pelapor', 'teknisi'])
            ->whereIn('status', ['resolved', 'closed'])
            ->latest('resolved_at');

        // Filter pencarian 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter periode
        if ($request->filled('period')) {
            $query->where('resolved_at', '>=', match ($request->period) {
                'today' => now()->startOfDay(),
                'week'  => now()->subDays(7),
                'month' => now()->subDays(30),
                default => null,
            });
        }

        // Filter prioritas 
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $riwayat = $query->get();

        // Statistik 
        $stats = [
            'total'    => $riwayat->count(),
            'critical' => $riwayat->where('priority', 'critical')->count(),
            'high'     => $riwayat->where('priority', 'high')->count(),
            'med_low'  => $riwayat->whereIn('priority', ['medium', 'low'])->count(),
        ];

        return view('admin.riwayat', compact('riwayat', 'stats'));
    }


    public function show($id)
    {
        $ticket = Ticket::with(['pelapor', 'teknisi'])
            ->whereIn('status', ['resolved', 'closed'])
            ->findOrFail($id);

        return view('admin.riwayat-detail', compact('ticket'));
    }

    public function destroy($id)
    {
        $ticket = \App\Models\Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.riwayat')
                        ->with('success', 'Riwayat berhasil dihapus.');
    }
}