<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard.vendor') }}">
                <div class="d-flex align-items-center justify-content-center" style="width:45px;height:45px;background:#198754;border-radius:50%;margin-right:10px;">
                    <i class="bi bi-flower1 text-white fs-4"></i>
                </div>
                <span class="logo-name" style="font-weight:bold;font-size:1.2rem;">SAFEN</span>
                <small class="d-block text-muted">Espace Vendeur</small>
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">TABLEAU DE BORD</li>
            <li class="{{ request()->routeIs('dashboard.vendor') ? 'active' : '' }}">
                <a href="{{ route('dashboard.vendor') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">MES PLATS</li>
            <li class="dropdown {{ request()->is('vendor/dishes*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-utensils"></i><span>Plats</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('vendor.dishes.index') }}">Mes plats</a></li>
                    <li><a class="nav-link" href="{{ route('vendor.dishes.create') }}">Ajouter un plat</a></li>
                </ul>
            </li>

            <li class="menu-header">VENTES</li>
            <li class="{{ request()->routeIs('dashboard.orders') ? 'active' : '' }}">
                <a href="{{ route('dashboard.orders') }}" class="nav-link">
                    <i class="fas fa-shopping-cart"></i><span>Commandes</span>
                </a>
            </li>

            <li class="menu-header">COMMUNICATION</li>
            <li class="{{ request()->routeIs('dashboard.messages') ? 'active' : '' }}">
                <a href="{{ route('dashboard.messages') }}" class="nav-link">
                    <i class="fas fa-envelope"></i><span>Messages</span>
                </a>
            </li>

            <li class="menu-header">MON COMPTE</li>
            @if(auth()->user()->vendor)
                <li class="{{ request()->routeIs('vendor.profile.edit') ? 'active' : '' }}">
                    <a href="{{ route('vendor.profile.edit', auth()->user()->vendor->id) }}" class="nav-link">
                        <i class="fas fa-user"></i><span>Mon profil</span>
                    </a>
                </li>
            @endif
            <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <a href="{{ route('profile.edit') }}" class="nav-link">
                    <i class="fas fa-cog"></i><span>Paramètres</span>
                </a>
            </li>

            <li class="menu-header">NAVIGATION</li>
            <li>
                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    <i class="fas fa-external-link-alt"></i><span>Retour au site</span>
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}" class="nav-link"
                   onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i class="fas fa-sign-out-alt"></i><span>Déconnexion</span>
                </a>
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>
</div>
