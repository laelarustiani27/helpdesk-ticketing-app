<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Hitung ticket berdasarkan status yang ada
        $criticalTickets = Ticket::where('status', 'critical')->count();
        $warningTickets = Ticket::where('status', 'warning')->count();
        $resolvedTickets = Ticket::where('status', 'resolved')->count();
        $totalTickets = Ticket::count();
        
        // Atau ambil semua tickets
        $tickets = Ticket::orderBy('created_at', 'desc')->get();
        
        return view('admin.dashboard', compact(
            'criticalTickets',
            'warningTickets', 
            'resolvedTickets',
            'totalTickets',
            'tickets'
        ));
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('auction.show', compact('ticket'));
    }
}