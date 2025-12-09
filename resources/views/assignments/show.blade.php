@extends('layout.app')

@section('title', 'Detalles de Asignación')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-handshake me-2"></i>Detalles de Asignación
    </h1>
    <div>
        <a href="{{ route('assignments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Información de la Asignación</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Colaborador:</th>
                                <td>
                                    <strong>{{ $assignment->employee->full_name }}</strong><br>
                                    <small class="text-muted">{{ $assignment->employee->employee_id }}</small>
                                </td>
                            </tr>
                            <tr>
                                <th>Equipo:</th>
                                <td>
                                    <strong>{{ $assignment->inventory->name }}</strong><br>
                                    <small class="text-muted">
                                        {{ $assignment->inventory->category->name }} | 
                                        {{ $assignment->inventory->type }}
                                    </small>
                                </td>
                            </tr>
                            <tr>
                                <th>Fecha Asignación:</th>
                                <td>{{ $assignment->assignment_date->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Estado:</th>
                                <td>
                                    <span class="badge bg-{{ $assignment->status == 'active' ? 'success' : 'warning' }}">
                                        {{ $assignment->status == 'active' ? 'Activa' : 'Devuelto' }}
                                    </span>
                                </td>
                            </tr>
                            @if($assignment->return_date)
                            <tr>
                                <th>Fecha Devolución:</th>
                                <td>{{ $assignment->return_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Motivo Devolución:</th>
                                <td>{{ $assignment->return_reason }}</td>
                            </tr>
                            @if($assignment->returnedByUser)
                            <tr>
                                <th>Devuelto a:</th>
                                <td>{{ $assignment->returnedByUser->name }}</td>
                            </tr>
                            @endif
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Información del Equipo -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Detalles del Equipo</h5>
            </div>
            <div class="card-body">
                @if($assignment->inventory->image_path)
                <div class="text-center mb-3">
                    <img src="{{ $assignment->inventory->image_url }}" alt="{{ $assignment->inventory->name }}" 
                         class="img-fluid rounded" style="max-height: 150px;">
                </div>
                @endif
                <p><strong>Marca:</strong> {{ $assignment->inventory->brand ?? 'N/A' }}</p>
                <p><strong>Número de Serie:</strong> {{ $assignment->inventory->serial_number ?? 'N/A' }}</p>
                <p><strong>Costo:</strong> 
                    @if($assignment->inventory->cost)
                        ${{ number_format($assignment->inventory->cost, 2) }}
                    @else
                        N/A
                    @endif
                </p>
            </div>
        </div>
        
        <!-- Información del Colaborador -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información del Colaborador</h5>
            </div>
            <div class="card-body">
                @if($assignment->employee->photo_path)
                <div class="text-center mb-3">
                    <img src="{{ $assignment->employee->photo_url }}" alt="{{ $assignment->employee->full_name }}" 
                         class="img-fluid rounded" style="max-height: 100px;">
                </div>
                @endif
                <p><strong>Email:</strong> {{ $assignment->employee->email ?? 'N/A' }}</p>
                <p><strong>Teléfono:</strong> {{ $assignment->employee->phone ?? 'N/A' }}</p>
                <p><strong>Ubicación:</strong> {{ $assignment->employee->location ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection