@extends('layout.app')

@section('title', 'Gestión de Categorías')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tags me-2"></i>Gestión de Categorías
    </h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row">
    <!-- Formulario de Creación -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus me-2"></i>Nueva Categoría
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre de la Categoría *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name') }}" required placeholder="Ej: Laptops, Monitores">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3" placeholder="Descripción de la categoría">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Crear Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lista de Categorías -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Categorías Existentes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Total Equipos</th>
                                <th>Asignados</th>
                                <th>Disponibles</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                </td>
                                <td>
                                    @if($category->description)
                                        <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                    @else
                                        <span class="text-muted">Sin descripción</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $category->inventory_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $category->assigned_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $category->available_count }}</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    @if($category->inventory_count == 0)
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('¿Eliminar esta categoría?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            title="No se puede eliminar - tiene equipos asociados" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal de Edición -->
                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Categoría</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('categories.update', $category) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="name{{ $category->id }}" class="form-label">Nombre *</label>
                                                    <input type="text" class="form-control" id="name{{ $category->id }}" 
                                                           name="name" value="{{ $category->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description{{ $category->id }}" class="form-label">Descripción</label>
                                                    <textarea class="form-control" id="description{{ $category->id }}" 
                                                              name="description" rows="3">{{ $category->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay categorías registradas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection