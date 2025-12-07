@extends('layout.app')

@section('title', 'Asignaciones de Equipos')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-handshake me-2"></i>Asignaciones de Equipos
    </h1>
    <a href="{{ route('assignments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nueva Asignación
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Colaborador</th>
                        <th>Equipo</th>
                        <th>Fecha Asignación</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                    <tr>
                        <td>
                            <strong>{{ $assignment->employee->full_name }}</strong><br>
                            <small class="text-muted">{{ $assignment->employee->employee_id }}</small>
                        </td>
                        <td>
                            <strong>{{ $assignment->inventory->name }}</strong><br>
                            <small class="text-muted">{{ $assignment->inventory->category->name }}</small>
                        </td>
                        <td>{{ $assignment->assignment_date->format('d/m/Y') }}</td>
                        <td>
                            @if($assignment->return_date)
                                {{ $assignment->return_date->format('d/m/Y') }}
                            @else
                                <span class="text-muted">Pendiente</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $assignment->status == 'active' ? 'success' : 'warning' }}">
                                {{ $assignment->status == 'active' ? 'Activa' : 'Devuelto' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($assignment->status == 'active')
                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                    data-bs-toggle="modal" data-bs-target="#returnModal{{ $assignment->id }}">
                                <i class="fas fa-undo"></i>
                            </button>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal para devolución -->
                    @if($assignment->status == 'active')
                    <div class="modal fade" id="returnModal{{ $assignment->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Registrar Devolución</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('assignments.return', $assignment) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Estás devolviendo: <strong>{{ $assignment->inventory->name }}</strong></p>
                                        <p>Asignado a: <strong>{{ $assignment->employee->full_name }}</strong></p>
                                        
                                        <div class="mb-3">
                                            <label for="condition{{ $assignment->id }}" class="form-label">Condición del Equipo *</label>
                                            <select class="form-select" id="condition{{ $assignment->id }}" name="condition" required>
                                                <option value="">Seleccionar condición</option>
                                                <option value="good">Buen estado (volver a inventario)</option>
                                                <option value="needs_repair">Necesita reparación</option>
                                                <option value="damaged">Dañado (ir a descarte)</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="return_reason{{ $assignment->id }}" class="form-label">Motivo de la Devolución *</label>
                                            <textarea class="form-control" id="return_reason{{ $assignment->id }}" 
                                                      name="return_reason" rows="3" required
                                                      placeholder="Ej: Traslado de oficina, salida del colaborador, equipo dañado..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-check me-2"></i>Registrar Devolución
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay asignaciones registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $assignments->links() }}
        </div>
    </div>
</div>
@endsection