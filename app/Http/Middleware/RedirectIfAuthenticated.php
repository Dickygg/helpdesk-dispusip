<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                return match (true) {
                    $user->hasRole('super admin')    => redirect(route('dashboard.super-admin')),
                    $user->hasRole('admin helpdesk') => redirect(route('dashboard.admin')),
                    $user->hasRole('petugas teknis') => redirect(route('dashboard.petugas')),
                    $user->hasRole('pengguna')       => redirect(route('dashboard.pengguna')),
                    default                          => abort(403, 'Role tidak dikenali'),
                };
            }
        }

        return $next($request);
    }
}
