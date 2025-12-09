<nav class="navbar navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-server me-2"></i>
            Sistema CMDB
        </a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                <i class="fas fa-user me-1"></i>
                {{ Auth::user()->name }} ({{ Auth::user()->role }})
            </span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Salir
                </button>
            </form>
        </div>
    </div>
</nav>