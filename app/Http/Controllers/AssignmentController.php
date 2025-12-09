<?php

declare(strict_types=1);  // ← AGREGAR ESTO

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Inventory;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    // Mostrar formulario de asignación
    public function create()
    {
        // Equipos disponibles para asignar
        $availableInventory = Inventory::where('status', 'inventory')
            ->with('category')
            ->get();
            
        // Colaboradores activos
        $activeEmployees = Employee::where('active', true)
            ->orderBy('first_name')
            ->get();
            
        return view('assignments.create', compact('availableInventory', 'activeEmployees'));
    }

    // Almacenar nueva asignación
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'inventory_id' => 'required|exists:inventory,id',
            'assignment_date' => 'required|date',
            'notes' => 'nullable|string|max:500'
        ]);

        // Verificar que el equipo esté disponible
        $inventory = Inventory::find($validated['inventory_id']);
        if ($inventory->status !== 'inventory') {
            return back()->withErrors(['inventory_id' => 'Este equipo no está disponible para asignación.']);
        }

        // Crear la asignación
        $assignment = Assignment::create([
            'employee_id' => $validated['employee_id'],
            'inventory_id' => $validated['inventory_id'],
            'assignment_date' => $validated['assignment_date'],
            'status' => 'active'
        ]);

        // Actualizar estado del equipo
        $inventory->update(['status' => 'assigned']);

        return redirect()->route('assignments.index')
            ->with('success', 'Equipo asignado correctamente al colaborador.');
    }

    // Listar todas las asignaciones
    public function index()
    {
        $assignments = Assignment::with(['employee', 'inventory.category'])
            ->latest()
            ->paginate(10);
            
        return view('assignments.index', compact('assignments'));
    }

    // Mostrar detalles de asignación
    public function show(Assignment $assignment)
    {
        $assignment->load(['employee', 'inventory.category', 'returnedByUser']);
        return view('assignments.show', compact('assignment'));
    }

    // Procesar devolución de equipo
    public function returnEquipment(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'return_reason' => 'required|string|max:500',
            'condition' => 'required|in:good,needs_repair,damaged'
        ]);

        // Marcar como devuelto
        $assignment->update([
            'return_date' => now(),
            'return_reason' => $validated['return_reason'],
            'returned_to_user' => Auth::id(),
            'status' => 'returned'
        ]);

        // Actualizar estado del equipo según condición
        $newStatus = $validated['condition'] === 'good' ? 'inventory' : 'technical_review';
        $assignment->inventory->update(['status' => $newStatus]);

        return redirect()->route('assignments.show', $assignment)
            ->with('success', 'Devolución registrada correctamente.');
    }
}