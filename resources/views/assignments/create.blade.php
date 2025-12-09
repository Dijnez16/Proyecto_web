@extends('layout.app')

@section('title', 'Nueva Asignación')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-handshake me-2"></i>Nueva Asignación de Equipo
    </h1>
    <a href="{{ route('assignments.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('assignments.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Colaborador *</label>
                                <select class="form-select" id="employee_id" name="employee_id" required>
                                    <option value="">Seleccionar Colaborador</option>
                                    @foreach($activeEmployees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->full_name }} ({{ $employee->employee_id }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assignment_date" class="form-label">Fecha de Asignación *</label>
                                <input type="date" class="form-control" id="assignment_date" name="assignment_date" 
                                       value="{{ date('Y-m-d') }}" required>
                                @error('assignment_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="inventory_id" class="form-label">Equipo a Asignar *</label>
                        <select class="form-select" id="inventory_id" name="inventory_id" required>
                            <option value="">Seleccionar Equipo</option>
                            @foreach($availableInventory as $item)
                            <option value="{{ $item->id }}" data-category="{{ $item->category->name }}" data-type="{{ $item->type }}">
                                {{ $item->name }} - {{ $item->brand }} ({{ $item->category->name }})
                            </option>
                            @endforeach
                        </select>
                        @error('inventory_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        
                        @if($availableInventory->isEmpty())
                        <div class="alert alert-warning mt-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No hay equipos disponibles para asignar. Todos los equipos están asignados, en descarte o donados.
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Observaciones (Opcional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Observaciones sobre esta asignación..."></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary" {{ $availableInventory->isEmpty() ? 'disabled' : '' }}>
                            <i class="fas fa-save me-2"></i>Asignar Equipo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Información
                </h5>
            </div>
            <div class="card-body">
                <p><strong>Equipos Disponibles:</strong> {{ $availableInventory->count() }}</p>
                <p><strong>Colaboradores Activos:</strong> {{ $activeEmployees->count() }}</p>
                <hr>
                <small class="text-muted">
                    <i class="fas fa-lightbulb me-1"></i>
                    Solo se pueden asignar equipos con estado "inventory" (disponibles).
                </small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-seleccionar fecha actual
    document.getElementById('assignment_date').valueAsDate = new Date();
</script>
@endpush
@endsection