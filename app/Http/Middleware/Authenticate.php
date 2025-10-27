<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // kalau user belum login
        if (!Auth::check()) {
            // kalau request bukan JSON, redirect ke halaman login
            if (!$request->expectsJson()) {
                return redirect()
                    ->route('auth.signin-index')
                    ->withErrors(['error' => 'Kamu harus login dulu untuk mengakses halaman ini.']);
            }

            // kalau request JSON (misalnya API)
            abort(401, 'Unauthenticated.');
        }

        // kalau sudah login, lanjut ke halaman berikutnya
        return $next($request);
    }

    /**
     * Default redirect jika dibutuhkan oleh Laravel.
     */
    protected function redirectTo($request): ?string
    {
        return $request->expectsJson() ? null : route('auth.signin-index');
    }
}
