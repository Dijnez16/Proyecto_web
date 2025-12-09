@extends('layout.app')

@section('title', 'Editar Colaborador')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>Editar Colaborador
    </h1>
    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">ID de Colaborador *</label>
                        <input type="text" class="form-control" id="employee_id" name="employee_id" 
                               value="{{ old('employee_id', $employee->employee_id) }}" required>
                        @error('employee_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto</label>
                        @if($employee->photo_path)
                            <div class="mb-2">
                                <img src="{{ $employee->photo_url }}" alt="{{ $employee->full_name }}" 
                                     class="thumbnail" style="width: 80px; height: 80px;">
                                <div class="form-text">Foto actual</div>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        <div class="form-text">Dejar vacío para mantener la foto actual.</div>
                        @error('photo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" 
                               value="{{ old('first_name', $employee->first_name) }}" required>
                        @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Apellido *</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" 
                               value="{{ old('last_name', $employee->last_name) }}" required>
                        @error('last_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email', $employee->email) }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="phone" name="phone" 
                               value="{{ old('phone', $employee->phone) }}">
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Dirección</label>
                <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $employee->address) }}</textarea>
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Ubicación/Oficina</label>
                <input type="text" class="form-control" id="location" name="location" 
                       value="{{ old('location', $employee->location) }}">
                @error('location')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Actualizar Colaborador
                </button>
            </div>
        </form>
    </div>
</div>
@endsection