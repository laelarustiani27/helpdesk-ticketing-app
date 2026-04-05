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
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role == 'teknisi') {
                return redirect()->route('teknisi.dashboard');
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Role tidak dikenali');
            }
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function loginPelanggan(Request $request)
    {
        $request->validate([
            'no_pelanggan' => 'required|string',
            'password'     => 'required|string',
        ], [
            'no_pelanggan.required' => 'Nomor pelanggan wajib diisi.',
            'password.required'     => 'Password wajib diisi.',
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
            ->withErrors(['no_pelanggan' => 'Nomor pelanggan atau password salah.'])
            ->withInput($request->only('no_pelanggan'))
            ->with('role', 'pelanggan');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('pelanggan')->check()) {
            Auth::guard('pelanggan')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}