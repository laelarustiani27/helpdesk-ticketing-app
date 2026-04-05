<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('nama_lengkap', 'username', 'email'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}