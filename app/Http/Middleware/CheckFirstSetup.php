<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFirstSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Routes yang BOLEH diakses saat first_setup = 1 (AKTIF/belum setup)
            $allowedRoutesWhenActive = [
                'auth.first-setup',           // Halaman form biodata
                'auth.complete-setup',        // Submit form biodata
                'auth.verify-email-page',     // Halaman verifikasi email
                'auth.send-verification-email', // Resend email verification
                'auth.verify-email',          // Confirm email verification
                'auth.logout',                // Logout
            ];
            
            // Jika fst_setup = 1 (AKTIF = belum setup biodata)
            if ($user->fst_setup == 1) {
                // Jika mencoba akses route yang TIDAK di-allow, redirect ke first-setup
                if (!$request->routeIs($allowedRoutesWhenActive)) {
                    return redirect()->route('auth.first-setup')->with('warning', 'Please complete your profile setup first.');
                }
            }
        }

        return $next($request);
    }
}
