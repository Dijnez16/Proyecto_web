<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AssignmentController;

// ==================== RUTAS PÚBLICAS ====================

// Página de login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout (accesible para usuarios autenticados)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Portal público para colaboradores (sin autenticación)
Route::get('/public/equipment', [PublicController::class, 'assignedEquipment'])->name('public.equipment');
Route::post('/public/process-return', [PublicController::class, 'processReturn'])->name('public.process-return');

// ==================== RUTAS PROTEGIDAS ====================

// Grupo de rutas que requieren autenticación
Route::middleware(['auth'])->group(function () {
    
    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de Inventario
    Route::resource('inventory', InventoryController::class);

    // Ruta para generar QR
    Route::post('/inventory/{inventory}/generate-qr', [InventoryController::class, 'generateQR'])->name('inventory.generate-qr');

    // Gestión de Asignaciones
    Route::resource('assignments', AssignmentController::class);
    Route::post('/assignments/{assignment}/return', [AssignmentController::class, 'returnEquipment'])->name('assignments.return');
    
    // Cambiar estado de equipo (ruta adicional)
    Route::post('/inventory/{inventory}/update-status', [InventoryController::class, 'updateStatus'])->name('inventory.update-status');
    
    // Gestión de Colaboradores
    Route::resource('employees', EmployeeController::class);
    
    // Activar/Desactivar colaboradores
    Route::post('/employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');
    Route::post('/employees/{employee}/activate', [EmployeeController::class, 'activate'])->name('employees.activate');
    
    // Gestión de Categorías
    Route::resource('categories', CategoryController::class)->except(['show']);
    
    // ==================== RUTAS SOLO PARA ADMINISTRADORES ====================
    
    // Grupo de rutas que requieren ser administrador
    Route::middleware(['admin'])->group(function () {
        
        // Gestión de Usuarios del Sistema
        Route::resource('users', UserController::class)->except(['show']);
        
        // Activar/Desactivar usuarios
        Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
        Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    });
});

// ==================== RUTA DE FALLBACK ====================

// Redirigir cualquier ruta no definida al dashboard (si está autenticado) o al login
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});