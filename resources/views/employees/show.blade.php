@extends('layout.app')

@section('title', 'Detalles del Colaborador')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user me-2"></i>Detalles del Colaborador
    </h1>
    <div>
        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <!-- Información Principal -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">ID Colaborador:</th>
                                <td><code>{{ $employee->employee_id }}</code></td>
                            </tr>
                            <tr>
                                <th>Nombre Completo:</th>
                                <td>{{ $employee->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $employee->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Teléfono:</th>
                                <td>{{ $employee->phone ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Ubicación:</th>
                                <td>{{ $employee->location ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    <span class="badge bg-{{ $employee->active ? 'success' : 'secondary' }}">
                                        {{ $employee->active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Fecha Registro:</th>
                                <td>{{ $employee->created_at->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($employee->address)
                <div class="mt-3">
                    <h6>Dirección</h6>
                    <p class="mb-0">{{ $employee->address }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Equipos Asignados -->
        @if($employee->currentAssignments->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Equipos Actualmente Asignados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Tipo</th>
                                <th>Fecha Asignación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->currentAssignments as $assignment)
                            <tr>
                                <td>{{ $assignment->inventory->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $assignment->inventory->type == 'hardware' ? 'primary' : 'success' }}">
                                        {{ $assignment->inventory->type }}
                                    </span>
                                </td>
                                <td>{{ $assignment->assignment_date->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('inventory.show', $assignment->inventory) }}" 
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Foto y Información Adicional -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Foto</h5>
            </div>
            <div class="card-body text-center">
                @if($employee->photo_path)
                    <img src="{{ $employee->photo_url }}" alt="{{ $employee->full_name }}" 
                         class="img-fluid rounded" style="max-height: 200px;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="fas fa-user fa-3x text-muted"></i>
                    </div>
                    <p class="text-muted mt-2 mb-0">Sin foto</p>
                @endif
            </div>
        </div>
        
        <!-- Estadísticas -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Estadísticas</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h3>{{ $employee->currentAssignments->count() }}</h3>
                    <p class="text-muted mb-0">Equipos Asignados</p>
                </div>
                
                @if($employee->needs->count() > 0)
                <div class="text-center mt-3">
                    <h3>{{ $employee->needs->count() }}</h3>
                    <p class="text-muted mb-0">Solicitudes Pendientes</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection