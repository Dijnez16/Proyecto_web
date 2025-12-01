<div class="sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
                    <i class="fas fa-laptop me-2"></i>
                    Inventario
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                    <i class="fas fa-users me-2"></i>
                    Colaboradores
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="fas fa-tags me-2"></i>
                    Categorías
                </a>
            </li>
            @if(Auth::user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="fas fa-user-cog me-2"></i>
                    Usuarios
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>