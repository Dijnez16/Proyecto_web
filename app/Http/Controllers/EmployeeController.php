<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // Listar todos los colaboradores
    public function index()
    {
        $employees = Employee::withCount('currentAssignments')
            ->latest()
            ->filter(request(['search', 'active']))
            ->paginate(10);

        return view('employees.index', compact('employees'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('employees.create');
    }

    // Almacenar nuevo colaborador
    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50|unique:employees',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048'
        ]);

        // Procesar foto si se subió
        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('employees', 'public');
        }

        // Crear colaborador
        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Colaborador registrado correctamente.');
    }

    // Mostrar detalles del colaborador
    public function show(Employee $employee)
    {
        $employee->load('currentAssignments.inventory', 'needs');
        return view('employees.show', compact('employee'));
    }

    // Mostrar formulario de edición
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    // Actualizar colaborador
    public function update(Request $request, Employee $employee)
    {
        // Validar datos
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50|unique:employees,employee_id,' . $employee->id,
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048'
        ]);

        // Procesar nueva foto si se subió
        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($employee->photo_path) {
                Storage::disk('public')->delete($employee->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('employees', 'public');
        }

        // Actualizar colaborador
        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Colaborador actualizado correctamente.');
    }

    // Desactivar colaborador
    public function deactivate(Employee $employee)
    {
        $employee->update(['active' => false]);

        return redirect()->route('employees.index')
            ->with('success', 'Colaborador desactivado correctamente.');
    }

    // Activar colaborador
    public function activate(Employee $employee)
    {
        $employee->update(['active' => true]);

        return redirect()->route('employees.index')
            ->with('success', 'Colaborador activado correctamente.');
    }
}