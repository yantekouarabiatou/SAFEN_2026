<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">
                <div class="d-flex align-items-center justify-content-center" style="width:45px;height:45px;background:#198754;border-radius:50%;margin-right:10px;">
                    <i class="bi bi-flower1 text-white fs-4"></i>
                </div>
                <span class="logo-name" style="font-weight:bold;font-size:1.2rem;">SAFEN</span>
                <small class="d-block text-muted">Espace Client</small>
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">TABLEAU DE BORD</li>
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fas fa-home"></i><span>Accueil</span>
                </a>
            </li>

            <li class="menu-header">COMMANDES</li>
            <li class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
                <a href="{{ route('orders.index') }}" class="nav-link">
                    <i class="fas fa-shopping-bag"></i><span>Mes commandes</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('orders.tracking') ? 'active' : '' }}">
                <a href="{{ route('orders.tracking') }}" class="nav-link">
                    <i class="fas fa-map-marker-alt"></i><span>Suivi de livraison</span>
                </a>
            </li>

            <li class="menu-header">FAVORIS</li>
            <li class="{{ request()->routeIs('favorites') ? 'active' : '' }}">
                <a href="{{ route('favorites') }}" class="nav-link">
                    <i class="fas fa-heart"></i><span>Mes favoris</span>
                    @php($favCount = auth()->user()->favorites()->count())
                    @if($favCount > 0)
                        <span class="badge badge-danger">{{ $favCount }}</span>
                    @endif
                </a>
            </li>

            <li class="menu-header">MON COMPTE</li>
            <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <a href="{{ route('profile.edit') }}" class="nav-link">
                    <i class="fas fa-cog"></i><span>Paramètres</span>
                </a>
            </li>

            <li class="menu-header">NAVIGATION</li>
            <li>
                <a href="{{ route('products.index') }}" class="nav-link">
                    <i class="fas fa-shopping-cart"></i><span>Continuer mes achats</span>
                </a>
            </li>
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
