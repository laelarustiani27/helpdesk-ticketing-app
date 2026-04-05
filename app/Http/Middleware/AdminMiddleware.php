<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Anda tidak memiliki akses sebagai admin.');
        }

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Akun Anda tidak aktif.');
        }

        return $next($request);
    }
}