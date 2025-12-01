@extends('layout.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card p-4">
                    <div class="text-center mb-4">
                        <h2 class="text-primary">
                            <i class="fas fa-server me-2"></i>Sistema CMDB
                        </h2>
                        <p class="text-muted">Ingresa a tu cuenta</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email') }}" required autofocus
                                       placeholder="usuario@ejemplo.com">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       required placeholder="Ingresa tu contraseña">
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </button>
                        </div>

                        <div class="text-center">
                            <small class="text-muted">
                                Credenciales de prueba:<br>
                                Admin: admin@cmdb.com / password<br>
                                Operador: operador@cmdb.com / password
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection