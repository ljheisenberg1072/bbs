<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->hasVerifiedEmail() && !$request->is('email/*', 'logout')) {
            return $request->expectsJson()
                ? abort(403, 'You email address is not verified')
                : redirect()->route('verification.notice');
        }
        return $next($request);
    }
}
