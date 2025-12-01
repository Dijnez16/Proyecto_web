<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Assignment;

class PublicController extends Controller
{
    // Mostrar equipos asignados a colaborador (vista pública)
    public function assignedEquipment(Request $request)
    {
        // Buscar colaborador por ID de empleado
        $employee = null;
        $assignedEquipment = [];

        if ($request->has('employee_id')) {
            $employee = Employee::where('employee_id', $request->employee_id)
                ->where('active', true)
                ->first();

            if ($employee) {
                $assignedEquipment = Assignment::with('inventory')
                    ->where('employee_id', $employee->id)
                    ->where('status', 'active')
                    ->get();
            }
        }

        return view('public.assigned-equipment', compact('employee', 'assignedEquipment'));
    }

    // Procesar solicitud de devolución
    public function processReturn(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'return_reason' => 'required|string|max:500',
            'employee_id' => 'required|exists:employees,employee_id'
        ]);

        // Buscar la asignación
        $assignment = Assignment::find($request->assignment_id);

        // Verificar que pertenece al empleado
        if ($assignment->employee->employee_id !== $request->employee_id) {
            return back()->with('error', 'No tienes permisos para devolver este equipo.');
        }

        // Marcar como devuelto (en realidad se marca para revisión)
        $assignment->inventory->update(['status' => 'technical_review']);
        $assignment->update([
            'return_reason' => $request->return_reason,
            'status' => 'returned'
        ]);

        return back()->with('success', 'Solicitud de devolución enviada correctamente. El equipo será revisado por el área técnica.');
    }
}