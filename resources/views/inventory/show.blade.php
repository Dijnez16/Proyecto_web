@extends('layout.app')

@section('title', 'Detalles del Equipo')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-laptop me-2"></i>Detalles del Equipo
    </h1>
    <div>
        <a href="{{ route('inventory.edit', $inventory) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
        @if($inventory->status !== 'discard')
            <button class="btn btn-sm btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#discardModal{{ $inventory->id }}">
                <i class="fas fa-trash"></i> Descartar
            </button>
        @endif

        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <!-- Información Principal -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Nombre:</th>
                                <td>{{ $inventory->name }}</td>
                            </tr>
                            <tr>
                                <th>Tipo:</th>
                                <td>
                                    <span class="badge bg-{{ $inventory->type == 'hardware' ? 'primary' : 'success' }}">
                                        {{ $inventory->type }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Categoría:</th>
                                <td>{{ $inventory->category->name }}</td>
                            </tr>
                            <tr>
                                <th>Marca:</th>
                                <td>{{ $inventory->brand ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Número de Serie:</th>
                                <td>
                                    @if($inventory->serial_number)
                                        <code>{{ $inventory->serial_number }}</code>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Costo:</th>
                                <td>
                                    @if($inventory->cost)
                                        ${{ number_format($inventory->cost, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Fecha Ingreso:</th>
                                <td>{{ $inventory->entry_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    <span class="badge bg-{{ 
                                        $inventory->status == 'inventory' ? 'success' : 
                                        ($inventory->status == 'assigned' ? 'primary' : 'warning')
                                    }}">
                                        {{ $inventory->status }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Información de Depreciación -->
                <div class="mt-4">
                    <h6>Información de Depreciación</h6>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th>Años de Depreciación</th>
                            <th>Fecha de Depreciación</th>
                            <th>Estado</th>
                        </tr>
                        <tr>
                            <td>{{ $inventory->depreciation_years }} años</td>
                            <td>{{ $inventory->depreciation_end_date->format('d/m/Y') }}</td>
                            <td>
                                @if($inventory->isAboutToDepreciate())
                                    <span class="badge bg-warning">Próximo a depreciar</span>
                                @else
                                    <span class="badge bg-success">En período normal</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Imagen y Acciones -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Imagen</h5>
            </div>
            <div class="card-body text-center">
                @if($inventory->image_path)
                    <img src="{{ $inventory->image_url }}" alt="{{ $inventory->name }}" 
                         class="img-fluid rounded" style="max-height: 200px;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                    <p class="text-muted mt-2 mb-0">Sin imagen</p>
                @endif
            </div>
        </div>
        <<!-- Sección QR - FUNCIONA CON RUTA LOCAL -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-qrcode me-2"></i>Código QR del Equipo
                </h5>
            </div>
            <div class="card-body text-center">
                @if($inventory->qr_code)
                    <img src="{{ asset('storage/' . $inventory->qr_code) }}" 
                        alt="QR Code {{ $inventory->name }}"
                        class="img-fluid rounded border" 
                        style="max-width: 200px;">
                    <p class="mt-2">
                        <small class="text-muted">
                            Escanear para ver información del equipo
                        </small>
                    </p>
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $inventory->qr_code) }}" 
                        download="QR-{{ $inventory->name }}.png"
                        class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-download me-1"></i>Descargar
                        </a>
                        <form action="{{ route('inventory.generate-qr', $inventory) }}" 
                            method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-sync me-1"></i>Regenerar
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                        style="height: 200px;">
                        <div>
                            <i class="fas fa-qrcode fa-3x text-muted"></i>
                            <p class="text-muted mt-2 mb-0">QR no generado</p>
                            <form action="{{ route('inventory.generate-qr', $inventory) }}" 
                                method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-qrcode me-1"></i>Generar QR
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Historial de Asignaciones -->
        @if($inventory->assignments->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Historial de Asignaciones</h5>
            </div>
            <div class="card-body">
                @foreach($inventory->assignments as $assignment)
                <div class="border-bottom pb-2 mb-2">
                    <small>
                        <strong>{{ $assignment->employee->full_name }}</strong><br>
                        Asignado: {{ $assignment->assignment_date->format('d/m/Y') }}<br>
                        @if($assignment->return_date)
                            Devuelto: {{ $assignment->return_date->format('d/m/Y') }}<br>
                            <em>{{ $assignment->return_reason }}</em>
                        @else
                            <span class="badge bg-success">Actualmente asignado</span>
                        @endif
                    </small>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
<!-- MODAL DESCARTAR EQUIPO -->
<div class="modal fade" id="discardModal{{ $inventory->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-trash me-2"></i>Descartar equipo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('inventory.discard', $inventory) }}">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    <p>
                        ¿Estás seguro que deseas descartar el equipo
                        <strong>{{ $inventory->name }}</strong>?
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Motivo del descarte *</label>
                        <select name="discard_reason" class="form-select" required>
                            <option value="">Seleccionar motivo</option>
                            <option value="Fin de vida útil">Fin de vida útil</option>
                            <option value="Daño irreparable">Daño irreparable</option>
                            <option value="Obsoleto">Obsoleto</option>
                            <option value="Donación">Donación</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="discard_notes" class="form-control" rows="3"
                                  placeholder="Comentarios adicionales..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Confirmar descarte
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection