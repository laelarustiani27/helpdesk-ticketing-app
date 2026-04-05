<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\UpdatesPreferences;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeknisiSettingsController extends Controller
{
    use UpdatesPreferences;

    public function index()
    {
        $user = Auth::user();
        return view('teknisi.settings', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'notif_enabled' => $request->boolean('notif_enabled'),
            'notif_ticket'  => $request->boolean('notif_ticket'),
            'notif_assign'  => $request->boolean('notif_assign'),
        ]);
        return response()->json(['success' => true]);
    }
}