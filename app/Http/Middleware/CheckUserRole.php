<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        // Check if the user is authenticated
        if (!$request->user()) {
            abort(403, 'Unauthorized action.');
        }

        // If role is provided, check if the user has the required role
        if ($role && $request->user()->role !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
