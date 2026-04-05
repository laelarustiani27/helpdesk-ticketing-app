<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
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
            'is_active' => true,
        ];

        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Username, password, atau role tidak sesuai');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $user->updateLastLogin();

        return match($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'teknisi' => redirect()->route('teknisi.dashboard'),
            default   => redirect('/'),
        };
    }

    public function logout(Request $request)
        {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }

}