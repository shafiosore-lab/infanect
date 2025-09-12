<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale');
        if (! $locale) {
            $accept = $request->server('HTTP_ACCEPT_LANGUAGE');
            if ($accept) {
                $lang = substr($accept, 0, 2);
                $locale = in_array($lang, ['en','es']) ? $lang : config('app.locale');
            } else {
                $locale = config('app.locale');
            }
            session(['locale' => $locale]);
        }
        App::setLocale($locale);
        return $next($request);
    }
}
