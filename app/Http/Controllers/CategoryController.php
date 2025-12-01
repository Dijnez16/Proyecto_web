<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Listar todas las categorías
    public function index()
    {
        $categories = Category::withCount('inventory')->latest()->get();
        return view('categories.index', compact('categories'));
    }

    // Almacenar nueva categoría
    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories',
            'description' => 'nullable|string'
        ]);

        // Crear categoría
        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    // Actualizar categoría
    public function update(Request $request, Category $category)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        // Actualizar categoría
        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    // Eliminar categoría
    public function destroy(Category $category)
    {
        // Verificar si hay equipos en esta categoría
        if ($category->inventory()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene equipos asociados.');
        }

        // Eliminar categoría
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}