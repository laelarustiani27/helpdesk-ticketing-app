<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait UpdatesPreferences
{
    public function updatePreferences(Request $request)
    {
        $language = $request->language ?? 'id';
        $theme    = $request->theme    ?? 'light';

        Auth::user()->update([
            'language' => $language,
            'theme'    => $theme,
        ]);

        session()->put('locale', $language);
        app()->setLocale($language);

        return back()->with('success', __('app.preferences_saved'));
    }
}