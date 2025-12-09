<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listar todos los usuarios
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('users.create');
    }

    // Almacenar nuevo usuario
    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,operational'
        ]);

        // Crear usuario
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role']
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    // Mostrar formulario de edición
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Actualizar usuario
    public function update(Request $request, User $user)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,operational'
        ]);

        // Actualizar usuario
        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    // Desactivar usuario
    public function deactivate(User $user)
    {
        $user->update(['active' => false]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario desactivado correctamente.');
    }

    // Activar usuario
    public function activate(User $user)
    {
        $user->update(['active' => true]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario activado correctamente.');
    }
}