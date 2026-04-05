<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TeknisiAuthController extends Controller
{
    public function showLoginForm()
    {
        // Redirect jika sudah login
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if ($user->isTeknisi()) {
                return redirect()->route('teknisi.dashboard');
            }
        }

        return view('teknisi.auth.login');
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
                    ->where('role', 'teknisi')
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

        // Login user
        Auth::login($user, $request->filled('remember'));

        // Update last login
        $user->updateLastLogin();

        // Regenerate session
        $request->session()->regenerate();

        return redirect()->intended(route('teknisi.dashboard'))
            ->with('success', 'Selamat datang, ' . $user->nama_lengkap . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teknisi.login')
            ->with('success', 'Anda berhasil logout.');
    }
}