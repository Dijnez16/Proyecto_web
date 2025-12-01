<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Employee;
use App\Models\User;
use App\Models\Category;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Estadísticas según el rol
        if ($user->isAdmin()) {
            // ADMIN: Ve todas las estadísticas
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
            
            // Admin ve todo el inventario reciente
            $recentInventory = Inventory::with('category')
                ->latest()
                ->take(5)
                ->get();
                
        } else {
            // OPERADOR: Ve solo estadísticas operativas
            $stats = [
                'total_inventory' => Inventory::count(),
                'assigned_equipment' => Inventory::where('status', 'assigned')->count(),
                'available_equipment' => Inventory::where('status', 'inventory')->count(),
                'total_employees' => Employee::where('active', true)->count(),
            ];
            
            // Operador ve solo equipos disponibles/recientes
            $recentInventory = Inventory::with('category')
                ->whereIn('status', ['inventory', 'assigned'])
                ->latest()
                ->take(5)
                ->get();
        }
        
        // Equipos próximos a depreciar (para ambos roles)
        $depreciatingSoon = Inventory::whereRaw('DATE_ADD(entry_date, INTERVAL depreciation_years YEAR) <= DATE_ADD(NOW(), INTERVAL 3 MONTH)')
            ->where('status', '!=', 'discard')
            ->where('status', '!=', 'donated')
            ->get();

        // Asignaciones recientes (para ambos roles)
        $recentAssignments = Assignment::with(['employee', 'inventory'])
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentInventory', 'depreciatingSoon', 'recentAssignments', 'user'));
    }
}