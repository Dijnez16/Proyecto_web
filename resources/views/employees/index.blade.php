@extends('layout.app')

@section('title', 'Gestión de Colaboradores')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users me-2"></i>Gestión de Colaboradores
    </h1>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus me-2"></i>Nuevo Colaborador
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Nombre, ID, email...">
            </div>
            <div class="col-md-4">
                <label for="active" class="form-label">Estado</label>
                <select class="form-select" id="active" name="active">
                    <option value="">Todos</option>
                    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-filter me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Ubicación</th>
                        <th>Equipos Asignados</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td>
                            @if($employee->photo_path)
                                <img src="{{ $employee->photo_url }}" alt="{{ $employee->full_name }}" 
                                     class="thumbnail" style="width: 50px; height: 50px;">
                            @else
                                <div class="thumbnail bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <code>{{ $employee->employee_id }}</code>
                        </td>
                        <td>{{ $employee->full_name }}</td>
                        <td>{{ $employee->email ?? 'N/A' }}</td>
                        <td>{{ $employee->phone ?? 'N/A' }}</td>
                        <td>{{ $employee->location ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $employee->current_assignments_count }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $employee->active ? 'success' : 'secondary' }}">
                                {{ $employee->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($employee->active)
                            <form action="{{ route('employees.deactivate', $employee) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-warning" 
                                        onclick="return confirm('¿Desactivar este colaborador?')">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('employees.activate', $employee) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No hay colaboradores registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection