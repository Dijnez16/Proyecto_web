@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
    </h1>
</div>
    @if(Auth::user()->isAdmin())
    <div class="alert alert-info">
        <i class="fas fa-user-shield me-2"></i>
        <strong>Modo Administrador:</strong> Tienes acceso completo a todos los módulos.
    </div>
    @else
    <div class="alert alert-warning">
        <i class="fas fa-user-check me-2"></i>
        <strong>Modo Operador:</strong> Puedes gestionar inventario y colaboradores.
    </div>
    @endif

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card bg-primary">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h3>{{ $stats['total_inventory'] }}</h3>
                        <p class="mb-0">Total Inventario</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-server fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card bg-success">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h3>{{ $stats['assigned_equipment'] }}</h3>
                        <p class="mb-0">Equipos Asignados</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-laptop fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card bg-warning">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h3>{{ $stats['total_employees'] }}</h3>
                        <p class="mb-0">Colaboradores</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card bg-info">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h3>{{ $stats['available_equipment'] }}</h3>
                        <p class="mb-0">Disponibles</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contenido Principal -->
<div class="row">
    <!-- Inventario Reciente -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Inventario Reciente
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Categoría</th>
                                <th>Fecha Ingreso</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInventory as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->type == 'hardware' ? 'primary' : 'success' }}">
                                        {{ $item->type }}
                                    </span>
                                </td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->entry_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status == 'inventory' ? 'success' : 'warning' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay equipos en el inventario</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Próximos a Depreciar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Próximos a Depreciar
                </h5>
            </div>
            <div class="card-body">
                @forelse($depreciatingSoon as $item)
                <div class="alert alert-warning py-2 mb-2">
                    <small>
                        <strong>{{ $item->name }}</strong><br>
                        Deprecia: {{ $item->depreciation_end_date->format('d/m/Y') }}
                    </small>
                </div>
                @empty
                <p class="text-muted mb-0">No hay equipos próximos a depreciar.</p>
                @endforelse
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nuevo Equipo
                    </a>
                    <a href="{{ route('employees.create') }}" class="btn btn-success">
                        <i class="fas fa-user-plus me-2"></i>Nuevo Colaborador
                    </a>
                    <a href="{{ route('public.equipment') }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>Portal Colaboradores
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection