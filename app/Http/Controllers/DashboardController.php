<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Employee;
use App\Models\User;
use App\Models\Category;
use App\Models\Assignment;

class DashboardController extends Controller
{
    // Mostrar dashboard principal
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_inventory' => Inventory::count(),
            'total_employees' => Employee::where('active', true)->count(),
            'total_users' => User::where('active', true)->count(),
            'total_categories' => Category::count(),
            'assigned_equipment' => Inventory::where('status', 'assigned')->count(),
            'available_equipment' => Inventory::where('status', 'inventory')->count(),
            'equipment_discard' => Inventory::where('status', 'discard')->count(),
            'equipment_donated' => Inventory::where('status', 'donated')->count(),
        ];

        // Inventario reciente
        $recentInventory = Inventory::with('category')
            ->latest()
            ->take(5)
            ->get();

        // Equipos próximos a depreciar
        $depreciatingSoon = Inventory::whereRaw('DATE_ADD(entry_date, INTERVAL depreciation_years YEAR) <= DATE_ADD(NOW(), INTERVAL 3 MONTH)')
            ->where('status', '!=', 'discard')
            ->where('status', '!=', 'donated')
            ->get();

        // Asignaciones recientes
        $recentAssignments = Assignment::with(['employee', 'inventory'])
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentInventory', 'depreciatingSoon', 'recentAssignments'));
    }
}