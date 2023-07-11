<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, string $roles): Response
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            abort(401);
        }

        // $roles_array = explode('|', $roles);

        // foreach ($roles_array as $role) {
        //     if (!auth()->user()->role()->where('name', strval($role))->exists()) {
        //         abort(403);
        //     }
        // }

        if (!auth()->user()->role()->where('name', strval($role))->exists()) {
            abort(403);
        }

        return $next($request);
    }
}
