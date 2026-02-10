<!-- resources/views/admin/partials/sidebar.blade.php -->
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <span class="logo-name">SAFEN</span>
            </a>
        </div>

        <ul class="sidebar-menu">

            <!-- Dashboard commun (visible pour TOUS les utilisateurs connectés) -->
            <li class="menu-header">Tableau de bord</li>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- SUPER-ADMIN et ADMIN -->
            @if(auth()->user()->hasAnyRole(['super-admin', 'admin']))
                <li class="menu-header">Gestion Globale</li>

                <!-- Artisans -->
                <li class="dropdown {{ request()->is('admin/artisans*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-palette"></i>
                        <span>Artisans</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.artisans.index') }}">Tous</a></li>
                        <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'pending']) }}">En attente</a></li>
                        <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'approved']) }}">Approuvés</a></li>
                        <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'rejected']) }}">Rejetés</a></li>
                        <li><a class="nav-link" href="{{ route('admin.artisans.create') }}">Ajouter</a></li>
                    </ul>
                </li>

                <!-- Produits -->
                <li class="dropdown {{ request()->is('admin/products*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Produits</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.products.index') }}">Tous</a></li>
                        <li><a class="nav-link" href="{{ route('admin.products.index', ['status' => 'pending']) }}">En attente</a></li>
                        <li><a class="nav-link" href="{{ route('admin.products.index', ['status' => 'active']) }}">Actifs</a></li>
                        <li><a class="nav-link" href="{{ route('admin.products.create') }}">Ajouter</a></li>
                    </ul>
                </li>

                <!-- Vendeurs -->
                <li class="dropdown {{ request()->is('admin/vendors*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-store"></i>
                        <span>Vendeurs</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.vendors.index') }}">Tous</a></li>
                        <li><a class="nav-link" href="{{ route('admin.vendors.create') }}">Ajouter</a></li>
                    </ul>
                </li>

                <!-- Gastronomie -->
                <li class="dropdown {{ request()->is('admin/dishes*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-utensils"></i>
                        <span>Gastronomie</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.dishes.index') }}">Tous les plats</a></li>
                        <li><a class="nav-link" href="{{ route('admin.dishes.create') }}">Ajouter un plat</a></li>
                    </ul>
                </li>

                <!-- Utilisateurs -->
                <li class="menu-header">Utilisateurs</li>
                <li class="dropdown {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-users"></i>
                        <span>Utilisateurs</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.users.index') }}">Tous</a></li>
                        <li><a class="nav-link" href="{{ route('admin.users.index', ['role' => 'artisan']) }}">Artisans</a></li>
                        <li><a class="nav-link" href="{{ route('admin.users.index', ['role' => 'vendor']) }}">Vendeurs</a></li>
                        <li><a class="nav-link" href="{{ route('admin.users.create') }}">Ajouter</a></li>
                    </ul>
                </li>

                <!-- Commandes -->
                <li class="menu-header">Transactions</li>
                <li class="dropdown {{ request()->is('admin/orders*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Commandes</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.orders.index') }}">Toutes</a></li>
                        <li><a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">En attente</a></li>
                        <li><a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'completed']) }}">Complétées</a></li>
                    </ul>
                </li>

                <!-- Analytics -->
                <li class="menu-header">Analytics</li>
                <li class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                    <a href="{{ route('admin.analytics') }}" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analytics</span>
                    </a>
                </li>

                <!-- Paramètres -->
                <li class="menu-header">Paramètres</li>
                @if(auth()->user()->hasRole('super-admin'))
                    <li class="dropdown {{ request()->is('admin/settings*') ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres système</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="{{ route('admin.settings.general') }}">Général</a></li>
                            <!-- Ajoute les autres si besoin -->
                        </ul>
                    </li>
                @else
                    <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <a href="{{ route('admin.settings') }}" class="nav-link">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                @endif

                <!-- Voir le site -->
                <li>
                    <a href="{{ url('/') }}" class="nav-link" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Voir le site
                    </a>
                </li>
            @endif

            <!-- ============================================== -->
            <!-- ARTISAN -->
            <!-- ============================================== -->
            @if(auth()->user()->hasRole('artisan'))
                <li class="menu-header">Espace Artisan</li>

                <li class="{{ request()->routeIs('dashboard.artisan') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.artisan') }}" class="nav-link">
                        <i data-feather="monitor"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-header">Produits</li>
                <li><a href="{{ route('products.index') }}" class="nav-link"><i data-feather="shopping-bag"></i> Mes produits</a></li>
                <li><a href="{{ route('products.create') }}" class="nav-link"><i data-feather="plus-circle"></i> Ajouter</a></li>

                <li class="menu-header">Ventes</li>
                <li><a href="{{ route('dashboard.artisan.orders') }}" class="nav-link"><i data-feather="shopping-cart"></i> Mes commandes</a></li>

                <li class="menu-header">Compte</li>
                <li><a href="{{ route('profile.edit') }}" class="nav-link"><i data-feather="settings"></i> Paramètres</a></li>

                <li class="menu-header">Navigation</li>
                <li><a href="{{ route('home') }}" class="nav-link"><i data-feather="globe"></i> Retour au site</a></li>
            @endif

            <!-- ============================================== -->
            <!-- VENDEUR -->
            <!-- ============================================== -->
            @if(auth()->user()->hasRole('vendor'))
                <li class="menu-header">Espace Vendeur</li>

                <li class="{{ request()->routeIs('dashboard.vendor') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.vendor') }}" class="nav-link">
                        <i data-feather="monitor"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-header">Plats</li>
                <li><a href="#" class="nav-link"><i data-feather="coffee"></i> Mes plats</a></li>
                <li><a href="#" class="nav-link"><i data-feather="plus-circle"></i> Ajouter</a></li>

                <li class="menu-header">Ventes</li>
                <li><a href="{{ route('dashboard.orders') }}" class="nav-link"><i data-feather="shopping-cart"></i> Commandes</a></li>

                <li class="menu-header">Compte</li>
                <li><a href="{{ route('profile.edit') }}" class="nav-link"><i data-feather="settings"></i> Paramètres</a></li>

                <li class="menu-header">Navigation</li>
                <li><a href="{{ route('home') }}" class="nav-link"><i data-feather="globe"></i> Retour au site</a></li>
            @endif

            <!-- ============================================== -->
            <!-- CLIENT -->
            <!-- ============================================== -->
            @if(auth()->user()->hasRole('client') || !auth()->user()->hasAnyRole(['super-admin','admin','artisan','vendor']))
                <li class="menu-header">Mon Espace</li>

                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i data-feather="home"></i>
                        <span>Accueil</span>
                    </a>
                </li>

                <li class="menu-header">Commandes</li>
                <li class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
                    <a href="{{ route('orders.index') }}" class="nav-link">
                        <i data-feather="shopping-bag"></i>
                        <span>Mes commandes</span>
                        @php($pending = auth()->user()->orders()->where('order_status', 'pending')->count())
                        @if($pending > 0)
                            <span class="badge badge-warning">{{ $pending }}</span>
                        @endif
                    </a>
                </li>

                <li class="menu-header">Favoris</li>
                <li class="{{ request()->routeIs('favorites') ? 'active' : '' }}">
                    <a href="{{ route('favorites') }}" class="nav-link">
                        <i data-feather="heart"></i>
                        <span>Mes favoris</span>
                        @php($fav = auth()->user()->favorites()->count())
                        @if($fav > 0)
                            <span class="badge badge-danger">{{ $fav }}</span>
                        @endif
                    </a>
                </li>

                <li class="menu-header">Compte</li>
                <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <a href="{{ route('profile.edit') }}" class="nav-link">
                        <i data-feather="settings"></i>
                        <span>Paramètres</span>
                    </a>
                </li>

                <li class="menu-header">Navigation</li>
                <li><a href="{{ route('home') }}" class="nav-link"><i data-feather="globe"></i> Retour au site</a></li>
            @endif

            <!-- Déconnexion (toujours visible) -->
            <li class="menu-header">Navigation</li>
            <li>
                <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-feather="log-out"></i>
                    <span>Déconnexion</span>
                </a>
            </li>
        </ul>
    </aside>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
