<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale');

        if (!$locale) {
            $user   = auth()->user();
            $locale = $user?->language ?? 'id';
        }

        if (!in_array($locale, ['id', 'en', 'de'])) {
            $locale = 'id';
        }

        app()->setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }
}