@extends('layout.app')

@section('title', 'Agregar Equipo')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>Agregar Equipo al Inventario
    </h1>
    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('inventory.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Equipo *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name') }}" required placeholder="Ej: Laptop Dell Latitude">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo *</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Seleccionar Tipo</option>
                            <option value="hardware" {{ old('type') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                            <option value="software" {{ old('type') == 'software' ? 'selected' : '' }}>Software</option>
                        </select>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Categoría *</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Seleccionar Categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="brand" class="form-label">Marca</label>
                        <input type="text" class="form-control" id="brand" name="brand" 
                               value="{{ old('brand') }}" placeholder="Ej: Dell, HP, Microsoft">
                        @error('brand')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="serial_number" class="form-label">Número de Serie</label>
                        <input type="text" class="form-control" id="serial_number" name="serial_number" 
                               value="{{ old('serial_number') }}" placeholder="Ej: ABC123XYZ">
                        @error('serial_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="cost" class="form-label">Costo ($)</label>
                        <input type="number" step="0.01" class="form-control" id="cost" name="cost" 
                               value="{{ old('cost') }}" placeholder="0.00">
                        @error('cost')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="entry_date" class="form-label">Fecha de Ingreso *</label>
                        <input type="date" class="form-control" id="entry_date" name="entry_date" 
                               value="{{ old('entry_date') }}" required>
                        @error('entry_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="depreciation_years" class="form-label">Años de Depreciación *</label>
                        <input type="number" class="form-control" id="depreciation_years" name="depreciation_years" 
                               value="{{ old('depreciation_years', 3) }}" min="1" max="10" required>
                        @error('depreciation_years')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Imagen del Equipo</label>
                <input type="file" class="form-control" id="image" name="image" 
                       accept="image/*">
                <div class="form-text">Formatos: JPG, PNG, GIF. Máximo 2MB.</div>
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar Equipo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection