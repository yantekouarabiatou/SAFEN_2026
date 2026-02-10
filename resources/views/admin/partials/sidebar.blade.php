<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <span class="logo-name">TOTCHEMON</span>
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">SA</a>
        </div>

        <ul class="sidebar-menu">
            {{-- Dashboard --}}
            <li class="menu-header">Tableau de bord</li>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Super Admin Dashboard (uniquement pour super-admin) --}}
            @if(auth()->user()->hasRole('super-admin'))
            <li class="{{ request()->routeIs('admin.super-dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.super-dashboard') }}" class="nav-link">
                    <i class="fas fa-crown text-warning"></i>
                    <span>Super Admin</span>
                </a>
            </li>
            @endif

            {{-- Gestion des artisans --}}
            <li class="menu-header">Gestion des artisans</li>

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

            {{-- Gestion des produits --}}
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

            {{-- Gestion des vendeurs --}}
            <li class="dropdown {{ request()->is('admin/vendors*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-store"></i><span>Vendeurs</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.vendors.index') }}">Tous les vendeurs</a></li>
                    <li><a class="nav-link" href="{{ route('admin.vendors.create') }}">Ajouter un vendeur</a></li>
                </ul>
            </li>

            {{-- Gestion des plats --}}
            <li class="dropdown {{ request()->is('admin/dishes*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-utensils"></i><span>Gastronomie</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.dishes.index') }}">Tous les plats</a></li>
                    <li><a class="nav-link" href="{{ route('admin.dishes.create') }}">Ajouter un plat</a></li>
                </ul>
            </li>

            {{-- Gestion des utilisateurs --}}
            <li class="menu-header">Gestion des utilisateurs</li>

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

            {{-- Gestion des commandes --}}
            <li class="menu-header">Transactions</li>

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

            {{-- Gestion des événements --}}
            <li class="menu-header">Événements culturels</li>

            <li class="dropdown {{ request()->is('admin/events*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-calendar-alt"></i><span>Événements</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.events.index') }}">Tous les événements</a></li>
                    <li><a class="nav-link" href="{{ route('admin.events.create') }}">Créer un événement</a></li>
                </ul>
            </li>

            {{-- Contenu & Avis --}}
            <li class="menu-header">Contenu & Avis</li>

            <li class="{{ request()->is('admin/reviews*') ? 'active' : '' }}">
                <a href="{{ route('admin.reviews.index') }}" class="nav-link">
                    <i class="fas fa-star"></i><span>Avis & Évaluations</span>
                </a>
            </li>

            <li class="{{ request()->is('admin/contacts*') ? 'active' : '' }}">
                <a href="{{ route('admin.contacts.index') }}" class="nav-link">
                    <i class="fas fa-envelope"></i><span>Messages</span>
                    @php $unreadContacts = App\Models\Contact::where('read', false)->count(); @endphp
                    @if($unreadContacts > 0)
                        <span class="badge badge-danger">{{ $unreadContacts }}</span>
                    @endif
                </a>
            </li>

            {{-- Analytics --}}
            <li class="menu-header">Analytics</li>

            <li class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <a href="{{ route('admin.analytics') }}" class="nav-link">
                    <i class="fas fa-chart-bar"></i><span>Analytics & Rapports</span>
                </a>
            </li>

            {{-- Paramètres --}}
            <li class="menu-header">Paramètres</li>

            @if(auth()->user()->hasRole('super-admin'))
            <li class="dropdown {{ request()->is('admin/settings*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-cog"></i><span>Paramètres système</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.settings.general') }}">Général</a></li>
                    <li><a class="nav-link" href="{{ route('admin.settings.payment') }}">Paiements</a></li>
                    <li><a class="nav-link" href="{{ route('admin.settings.notifications') }}">Notifications</a></li>
                    <li><a class="nav-link" href="{{ route('admin.roles.index') }}">Rôles & Permissions</a></li>
                </ul>
            </li>
            @else
            <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.settings') }}" class="nav-link">
                    <i class="fas fa-cog"></i><span>Paramètres</span>
                </a>
            </li>
            @endif

            {{-- Vue site public --}}
            <li>
                <a href="{{ url('/') }}" class="nav-link" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Voir le site</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
