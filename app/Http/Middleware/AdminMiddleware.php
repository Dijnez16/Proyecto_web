<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Por favor inicia sesión.');
        }

        $user = Auth::user();
        if (!$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos de administrador.');
        }

        return $next($request);
    }
}