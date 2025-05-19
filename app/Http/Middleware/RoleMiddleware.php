<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roleIds)
    {
        $allowedRoles = [];
        foreach ($roleIds as $role) {
            $roleParts = explode(',', $role);
            foreach ($roleParts as $rolePart) {
                if (is_numeric($rolePart)) {
                    $allowedRoles[] = (int) $rolePart;
                }
            }
        }

        if (!in_array(Auth::user()->role_id, $allowedRoles)) {
            abort(403); // Unauthorized
        }

        return $next($request);
    }
}
