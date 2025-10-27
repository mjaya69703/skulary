<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureRoleIsActive
{
    public function handle($request, Closure $next, $role)
    {
        $activeRole = session('active_role');

        if (!Auth::check() || $activeRole !== $role) {
            abort(403, 'Unauthorized - Role tidak sesuai');
        }

        return $next($request);
    }
}
