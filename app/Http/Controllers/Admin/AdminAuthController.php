<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        $user = User::where('username', $request->username)
                    ->where('role', 'admin')
                    ->where('is_active', true)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Username tidak ditemukan atau akun tidak aktif.',
            ])->withInput($request->only('username'));
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah.',
            ])->withInput($request->only('username'));
        }

        Auth::login($user, $request->filled('remember'));
        $user->updateLastLogin();
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Selamat datang, ' . $user->nama_lengkap . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Anda berhasil logout.');
    }
}