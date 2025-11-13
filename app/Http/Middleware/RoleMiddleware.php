<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Verificar con Spatie
        if (!auth()->user()->hasRole($role)) {
            abort(403, "No se pudo acceder");
        }

        return $next($request);
    }
}