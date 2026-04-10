<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showLoginPelanggan()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
            'role'     => 'required|in:admin,teknisi',
        ]);

        $credentials = [
            'username'  => $request->username,
            'password'  => $request->password,
            'role'      => $request->role,
            'is_active' => 1,
        ];

        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Username, password, atau role salah');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        return match($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'teknisi' => redirect()->route('teknisi.dashboard'),
            default   => redirect('/login'),
        };
    }

    public function loginPelanggan(Request $request)
    {
        $request->validate([
            'no_pelanggan' => 'required|string',
            'password'     => 'required|string',
        ], [
            'no_pelanggan.required' => 'Nomor pelanggan wajib diisi',
            'password.required'     => 'Password wajib diisi',
        ]);

        $credentials = [
            'no_pelanggan' => $request->no_pelanggan,
            'password'     => $request->password,
        ];

        if (Auth::guard('pelanggan')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('pelanggan.dashboard');
        }

        return back()
            ->withErrors(['no_pelanggan' => 'Nomor pelanggan atau password salah'])
            ->withInput($request->only('no_pelanggan'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('pelanggan')->check()) {
            Auth::guard('pelanggan')->logout();
        } else {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}