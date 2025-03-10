<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['zh_CN', 'en_US', 'en'];
        //  从 Accept-Language 中获取优先 Locale
        $locale = $request->getPreferredLanguage($supportedLocales) ?? config('app.fallback_locale');

        App::setLocale($locale);

        return $next($request);
    }
}
