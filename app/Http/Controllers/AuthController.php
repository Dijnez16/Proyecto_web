<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        // Validar datos
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Intentar autenticación
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Verificar si el usuario está activo
            if (!$user->active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Tu cuenta está desactivada.']);
            }

            // Regenerar sesión y redirigir
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Credenciales incorrectas
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}