<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Colaboradores - Sistema CMDB</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #3498db;
            --secondary: #2ecc71;
            --dark: #2c3e50;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .portal-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .equipment-card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .equipment-card:hover {
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="portal-card p-4 mb-4">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <h1 class="text-primary">
                            <i class="fas fa-laptop me-2"></i>Portal de Colaboradores
                        </h1>
                        <p class="text-muted">Consulta tus equipos asignados y gestiona devoluciones</p>
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

                    <!-- Formulario de Búsqueda -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-search me-2"></i>Buscar Mis Equipos
                            </h5>
                            <form method="GET" class="row g-3">
                                <div class="col-md-8">
                                    <label for="employee_id" class="form-label">ID de Colaborador</label>
                                    <input type="text" class="form-control" id="employee_id" name="employee_id" 
                                           value="{{ request('employee_id') }}" 
                                           placeholder="Ingresa tu ID de colaborador (Ej: EMP001)" required>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-2"></i>Buscar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Resultados -->
                    @if(isset($employee))
                        <!-- Información del Colaborador -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>{{ $employee->full_name }}</h4>
                                        <p class="mb-1">
                                            <strong>ID:</strong> <code>{{ $employee->employee_id }}</code>
                                        </p>
                                        @if($employee->email)
                                            <p class="mb-1">
                                                <strong>Email:</strong> {{ $employee->email }}
                                            </p>
                                        @endif
                                        @if($employee->location)
                                            <p class="mb-0">
                                                <strong>Ubicación:</strong> {{ $employee->location }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="badge bg-{{ $employee->active ? 'success' : 'secondary' }} fs-6">
                                            {{ $employee->active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Equipos Asignados -->
                        @if($assignedEquipment->count() > 0)
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-laptop me-2"></i>
                                        Equipos Asignados ({{ $assignedEquipment->count() }})
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($assignedEquipment as $assignment)
                                        <div class="col-md-6 mb-3">
                                            <div class="equipment-card card h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $assignment->inventory->name }}</h6>
                                                    
                                                    <div class="mb-2">
                                                        <span class="badge bg-{{ $assignment->inventory->type == 'hardware' ? 'primary' : 'success' }}">
                                                            {{ $assignment->inventory->type }}
                                                        </span>
                                                        <span class="badge bg-info">
                                                            {{ $assignment->inventory->category->name }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="equipment-details">
                                                        @if($assignment->inventory->brand)
                                                            <p class="mb-1">
                                                                <strong>Marca:</strong> {{ $assignment->inventory->brand }}
                                                            </p>
                                                        @endif
                                                        @if($assignment->inventory->serial_number)
                                                            <p class="mb-1">
                                                                <strong>Serie:</strong> <code>{{ $assignment->inventory->serial_number }}</code>
                                                            </p>
                                                        @endif
                                                        <p class="mb-2">
                                                            <strong>Asignado desde:</strong> 
                                                            {{ $assignment->assignment_date->format('d/m/Y') }}
                                                        </p>
                                                    </div>

                                                    <!-- Botón de Devolución -->
                                                    <button type="button" class="btn btn-outline-warning btn-sm w-100" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#returnModal{{ $assignment->id }}">
                                                        <i class="fas fa-undo me-2"></i>Solicitar Devolución
                                                    </button>

                                                    <!-- Modal de Devolución -->
                                                    <div class="modal fade" id="returnModal{{ $assignment->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Solicitar Devolución</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <form method="POST" action="{{ route('public.process-return') }}">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <p>
                                                                            Estás solicitando la devolución de: 
                                                                            <strong>{{ $assignment->inventory->name }}</strong>
                                                                        </p>
                                                                        
                                                                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                                                                        <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="return_reason{{ $assignment->id }}" class="form-label">
                                                                                Motivo de la Devolución *
                                                                            </label>
                                                                            <select class="form-select" id="return_reason{{ $assignment->id }}" 
                                                                                    name="return_reason" required>
                                                                                <option value="">Seleccionar motivo</option>
                                                                                <option value="Traslado de oficina">Traslado de oficina</option>
                                                                                <option value="Salida de la empresa">Salida de la empresa</option>
                                                                                <option value="Equipo en mal estado">Equipo en mal estado</option>
                                                                                <option value="Actualización de equipo">Actualización de equipo</option>
                                                                                <option value="Otro">Otro</option>
                                                                            </select>
                                                                        </div>
                                                                        
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Información Adicional</label>
                                                                            <textarea class="form-control" name="additional_notes" 
                                                                                      rows="3" placeholder="Detalles adicionales..."></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                        <button type="submit" class="btn btn-warning">
                                                                            <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                No tienes equipos asignados actualmente.
                            </div>
                        @endif
                    @elseif(request()->has('employee_id'))
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No se encontró ningún colaborador con el ID "{{ request('employee_id') }}".
                        </div>
                    @endif

                    <!-- Información de Contacto -->
                    <div class="card mt-4">
                        <div class="card-body text-center">
                            <h6>¿Necesitas ayuda?</h6>
                            <p class="mb-0 text-muted">
                                Contacta al departamento de TI para cualquier consulta sobre tus equipos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>