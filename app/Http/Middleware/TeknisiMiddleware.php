<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class TeknisiMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('teknisi.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !$user->isTeknisi()) {
            Auth::logout();
            return redirect()->route('teknisi.login')
                ->with('error', 'Anda tidak memiliki akses sebagai teknisi.');
        }

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('teknisi.login')
                ->with('error', 'Akun Anda tidak aktif.');
        }

        return $next($request);
    }
}