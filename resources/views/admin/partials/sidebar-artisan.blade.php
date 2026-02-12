<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard.artisan') }}">
                <div class="d-flex align-items-center justify-content-center" style="width:45px;height:45px;background:#198754;border-radius:50%;margin-right:10px;">
                    <i class="bi bi-flower1 text-white fs-4"></i>
                </div>
                <span class="logo-name" style="font-weight:bold;font-size:1.2rem;">SAFEN</span>
                <small class="d-block text-muted">Espace Artisan</small>
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">TABLEAU DE BORD</li>
            <li class="{{ request()->routeIs('dashboard.artisan') ? 'active' : '' }}">
                <a href="{{ route('dashboard.artisan') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">MES PRODUITS</li>
            <li class="dropdown {{ request()->is('products*') || request()->is('artisan/products*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-shopping-bag"></i><span>Produits</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('products.index') }}">Mes produits</a></li>
                    <li><a class="nav-link" href="{{ route('products.create') }}">Ajouter un produit</a></li>
                </ul>
            </li>

            <li class="menu-header">VENTES</li>
            <li class="{{ request()->routeIs('dashboard.artisan.orders') ? 'active' : '' }}">
                <a href="{{ route('dashboard.artisan.orders') }}" class="nav-link">
                    <i class="fas fa-shopping-cart"></i><span>Mes commandes</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                <a href="{{ route('quotes.index') }}" class="nav-link">
                    <i class="fas fa-file-invoice-dollar"></i><span>Demandes de devis</span>
                </a>
            </li>

            <li class="menu-header">COMMUNICATION</li>
            <li class="{{ request()->routeIs('dashboard.messages') ? 'active' : '' }}">
                <a href="{{ route('dashboard.messages') }}" class="nav-link">
                    <i class="fas fa-envelope"></i><span>Messages</span>
                    @php($unreadMsg = auth()->user()->unreadMessages?->count() ?? 0)
                    @if($unreadMsg > 0)
                        <span class="badge badge-primary">{{ $unreadMsg }}</span>
                    @endif
                </a>
            </li>
            <li class="{{ request()->routeIs('dashboard.artisan.reviews') ? 'active' : '' }}">
                <a href="{{ route('dashboard.artisan.reviews') }}" class="nav-link">
                    <i class="fas fa-star"></i><span>Avis clients</span>
                </a>
            </li>

            <li class="menu-header">STATISTIQUES</li>
            <li class="{{ request()->routeIs('dashboard.artisan.analytics') ? 'active' : '' }}">
                <a href="{{ route('dashboard.artisan.analytics') }}" class="nav-link">
                    <i class="fas fa-chart-line"></i><span>Analytiques</span>
                </a>
            </li>

            <li class="menu-header">MON COMPTE</li>
            @if(auth()->user()->artisan)
                <li class="{{ request()->routeIs('artisan.profile.edit') ? 'active' : '' }}">
                    <a href="{{ route('artisan.profile.edit', auth()->user()->artisan->id) }}" class="nav-link">
                        <i class="fas fa-user"></i><span>Mon profil</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('artisans.show', auth()->user()->artisan->id) }}" class="nav-link" target="_blank">
                        <i class="fas fa-external-link-alt"></i><span>Voir profil public</span>
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
