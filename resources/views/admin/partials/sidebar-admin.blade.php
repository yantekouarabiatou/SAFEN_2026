<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <div class="d-flex align-items-center justify-content-center" style="width:45px;height:45px;background:#198754;border-radius:50%;margin-right:10px;">
                    <i class="bi bi-flower1 text-white fs-4"></i>
                </div>
                <span class="logo-name" style="font-weight:bold;font-size:1.2rem;">SAFEN</span>
                <small class="d-block text-muted">Bénin</small>
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">TABLEAU DE BORD</li>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">GESTION GLOBALE</li>

            {{-- Artisans --}}
            <li class="dropdown {{ request()->is('admin/artisans*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-palette"></i><span>Artisans</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.artisans.index') }}">Tous les artisans</a></li>
                    <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'pending']) }}">En attente</a></li>
                    <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'approved']) }}">Approuvés</a></li>
                    <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'rejected']) }}">Rejetés</a></li>
                    <li><a class="nav-link" href="{{ route('admin.artisans.create') }}">Ajouter un artisan</a></li>
                </ul>
            </li>

            {{-- Produits --}}
            <li class="dropdown {{ request()->is('admin/products*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-shopping-bag"></i><span>Produits</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.products.index') }}">Tous les produits</a></li>
                    <li><a class="nav-link" href="{{ route('admin.products.index', ['status' => 'pending']) }}">En attente</a></li>
                    <li><a class="nav-link" href="{{ route('admin.products.index', ['status' => 'active']) }}">Actifs</a></li>
                    <li><a class="nav-link" href="{{ route('admin.products.create') }}">Ajouter un produit</a></li>
                </ul>
            </li>

            {{-- Vendeurs --}}
            <li class="dropdown {{ request()->is('admin/vendors*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-store"></i><span>Vendeurs</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.vendors.index') }}">Tous les vendeurs</a></li>
                    <li><a class="nav-link" href="{{ route('admin.vendors.create') }}">Ajouter un vendeur</a></li>
                </ul>
            </li>

            {{-- Gastronomie / Plats --}}
            <li class="dropdown {{ request()->is('admin/dishes*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-utensils"></i><span>Gastronomie</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.dishes.index') }}">Tous les plats</a></li>
                    <li><a class="nav-link" href="{{ route('admin.dishes.create') }}">Ajouter un plat</a></li>
                </ul>
            </li>

            <li class="menu-header">GESTION DES UTILISATEURS</li>
            <li class="dropdown {{ request()->is('admin/users*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-users"></i><span>Utilisateurs</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.users.index') }}">Tous les utilisateurs</a></li>
                    <li><a class="nav-link" href="{{ route('admin.users.index', ['role' => 'artisan']) }}">Artisans</a></li>
                    <li><a class="nav-link" href="{{ route('admin.users.index', ['role' => 'vendor']) }}">Vendeurs</a></li>
                    <li><a class="nav-link" href="{{ route('admin.users.create') }}">Ajouter un utilisateur</a></li>
                </ul>
            </li>

            <li class="menu-header">TRANSACTIONS</li>
            <li class="dropdown {{ request()->is('admin/orders*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-shopping-cart"></i><span>Commandes</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.orders.index') }}">Toutes les commandes</a></li>
                    <li><a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">En attente</a></li>
                    <li><a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'processing']) }}">En traitement</a></li>
                    <li><a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'completed']) }}">Complétées</a></li>
                </ul>
            </li>

            <li class="{{ request()->is('admin/quotes*') ? 'active' : '' }}">
                <a href="{{ route('admin.quotes.index') }}" class="nav-link">
                    <i class="fas fa-file-invoice-dollar"></i><span>Devis</span>
                </a>
            </li>

            <li class="menu-header">CULTURE</li>
            <li class="dropdown {{ request()->is('admin/events*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-calendar-alt"></i><span>Événements</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.events.index') }}">Tous les événements</a></li>
                    <li><a class="nav-link" href="{{ route('admin.events.create') }}">Créer un événement</a></li>
                </ul>
            </li>

            <li class="menu-header">CONTENU & AVIS</li>
            <li class="{{ request()->is('admin/reviews*') ? 'active' : '' }}">
                <a href="{{ route('admin.reviews.index') }}" class="nav-link">
                    <i class="fas fa-star"></i><span>Avis & évaluations</span>
                </a>
            </li>
            <li class="{{ request()->is('admin/contacts*') ? 'active' : '' }}">
                <a href="{{ route('admin.contacts.index') }}" class="nav-link">
                    <i class="fas fa-envelope"></i><span>Messages</span>
                    @php
                        $unreadMessages = \App\Models\Contact::where('read', false)->count();
                    @endphp
                    @if($unreadMessages > 0)
                        <span class="badge badge-danger">{{ $unreadMessages }}</span>
                    @endif
                </a>
            </li>

            <li class="menu-header">ANALYTICS</li>
            <li class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <a href="{{ route('admin.analytics') }}" class="nav-link">
                    <i class="fas fa-chart-bar"></i><span>Rapports & statistiques</span>
                </a>
            </li>

            @if(auth()->user()->hasRole('super-admin'))
                <li class="menu-header">PARAMÈTRES</li>
                <li class="dropdown {{ request()->is('admin/settings*') || request()->is('admin/roles*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-cog"></i><span>Configuration</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.settings.general') }}">Général</a></li>
                        <li><a class="nav-link" href="{{ route('admin.settings.payment') }}">Paiements</a></li>
                        <li><a class="nav-link" href="{{ route('admin.settings.notifications') }}">Notifications</a></li>
                        <li><a class="nav-link" href="{{ route('admin.roles.index') }}">Rôles & permissions</a></li>
                    </ul>
                </li>
            @endif

            {{-- Liens communs --}}
            <li class="menu-header">NAVIGATION</li>
            <li>
                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    <i class="fas fa-external-link-alt"></i><span>Voir le site public</span>
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
