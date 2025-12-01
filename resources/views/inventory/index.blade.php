@extends('layout.app')

@section('title', 'Gestión de Inventario')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-laptop me-2"></i>Gestión de Inventario
    </h1>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nuevo Equipo
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
            <div class="col-md-3">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Nombre, marca, serie...">
            </div>
            <div class="col-md-2">
                <label for="type" class="form-label">Tipo</label>
                <select class="form-select" id="type" name="type">
                    <option value="">Todos</option>
                    <option value="hardware" {{ request('type') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                    <option value="software" {{ request('type') == 'software' ? 'selected' : '' }}>Software</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="category" class="form-label">Categoría</label>
                <select class="form-select" id="category" name="category">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Estado</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Todos</option>
                    <option value="inventory" {{ request('status') == 'inventory' ? 'selected' : '' }}>Inventario</option>
                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Asignado</option>
                    <option value="discard" {{ request('status') == 'discard' ? 'selected' : '' }}>Descarte</option>
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
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Marca/Serie</th>
                        <th>Costo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventory as $item)
                    <tr>
                        <td>
                            @if($item->image_path)
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" 
                                     class="thumbnail" style="width: 60px; height: 60px;">
                            @else
                                <div class="thumbnail bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>
                            <span class="badge bg-{{ $item->type == 'hardware' ? 'primary' : 'success' }}">
                                {{ $item->type }}
                            </span>
                        </td>
                        <td>{{ $item->category->name }}</td>
                        <td>
                            <small>
                                <strong>{{ $item->brand }}</strong><br>
                                @if($item->serial_number)
                                    <code>{{ $item->serial_number }}</code>
                                @endif
                            </small>
                        </td>
                        <td>
                            @if($item->cost)
                                ${{ number_format($item->cost, 2) }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ 
                                $item->status == 'inventory' ? 'success' : 
                                ($item->status == 'assigned' ? 'primary' : 'warning')
                            }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('inventory.show', $item) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('inventory.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay equipos en el inventario</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $inventory->links() }}
        </div>
    </div>
</div>
@endsection