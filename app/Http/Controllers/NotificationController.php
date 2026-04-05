<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        $teknisi_list = \App\Models\User::where('role', 'teknisi')->get();
        $layout = auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.teknisi';

        return view('notification', compact('notifications', 'teknisi_list', 'layout'));
    }

    public function list()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($n) {
                return [
                    'id'          => $n->id,
                    'type'        => $n->data['type'] ?? 'sistem',
                    'title'       => $n->data['title'] ?? 'Notifikasi',
                    'description' => $n->data['message'] ?? '',
                    'location'    => $n->data['location'] ?? null,
                    'is_read'     => !is_null($n->read_at),
                    'ticket_id'   => $n->data['ticket_id'] ?? null,
                    'time'        => $n->created_at->format('H:i • d M'),
                ];
            });

        return response()->json($notifications);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()
            ->unreadNotifications()
            ->count();

        return response()->json([
            'count' => $count
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'success' => true
        ]);
    }

    public function markAllAsRead()
    {
        Auth::user()
            ->unreadNotifications
            ->markAsRead();

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function destroyAll()
    {
        Auth::user()
            ->notifications()
            ->delete();

        return response()->json(['success' => true]);
    }
}