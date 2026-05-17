<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (! $request->user()) {
            return redirect('login');
        }

        $userRole     = strtolower($request->user()->role?->roles_name ?? '');
        $allowedRoles = array_map('strtolower', $roles);

        if (! in_array($userRole, $allowedRoles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
