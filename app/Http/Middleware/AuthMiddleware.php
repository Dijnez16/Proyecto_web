<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    /**
     * Verificar si el usuario está autenticado
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario no está autenticado
        if (!Auth::check()) {
            // Redirigir al login si no está autenticado
            return redirect()->route('login')->with('error', 'Por favor inicia sesión para acceder a esta página.');
        }

        // Verificar si el usuario está activo
        $user = Auth::user();
        if (!$user->active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu cuenta está desactivada. Contacta al administrador.');
        }

        // Usuario autenticado y activo, continuar
        return $next($request);
    }
}