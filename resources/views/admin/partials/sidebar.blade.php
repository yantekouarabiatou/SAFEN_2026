<!-- resources/views/admin/partials/sidebar.blade.php -->
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <span class="logo-name">SAFEN</span>
            </a>
        </div>

        <ul class="sidebar-menu">

            <!-- Dashboard commun (tous les rôles authentifiés) -->
            <li class="menu-header">Tableau de bord</li>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Super Admin & Admin : Gestion globale -->
            @role('super-admin|admin')
                <li class="menu-header">Gestion Globale</li>

                <!-- Artisans -->
                <li class="dropdown {{ request()->is('admin/artisans*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-palette"></i>
                        <span>Artisans</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.artisans.index') }}">Tous les artisans</a></li>
                        <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'pending']) }}">En attente</a></li>
                        <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'approved']) }}">Approuvés</a></li>
                        <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'rejected']) }}">Rejetés</a></li>
                        <li><a class="nav-link" href="{{ route('admin.artisans.create') }}">Ajouter un artisan</a></li>
                    </ul>
                </li>

                <!-- Produits -->
                <li class="dropdown {{ request()->is('admin/products*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Produits</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.products.index') }}">Tous les produits</a></li>
                        <li><a class="nav-link" href="{{ route('admin.products.index', ['status' => 'pending']) }}">En attente</a></li>
                        <li><a class="nav-link" href="{{ route('admin.products.index', ['status' => 'active']) }}">Actifs</a></li>
                        <li><a class="nav-link" href="{{ route('admin.products.create') }}">Ajouter un produit</a></li>
                    </ul>
                </li>

                <!-- Vendeurs -->
                <li class="dropdown {{ request()->is('admin/vendors*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-store"></i>
                        <span>Vendeurs</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.vendors.index') }}">Tous les vendeurs</a></li>
                        <li><a class="nav-link" href="{{ route('admin.vendors.create') }}">Ajouter un vendeur</a></li>
                    </ul>
                </li>

                <!-- Gastronomie / Plats -->
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
                <li class="menu-header">Gestion des utilisateurs</li>
                <li class="dropdown {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-users"></i>
                        <span>Utilisateurs</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.users.index') }}">Tous les utilisateurs</a></li>
                        <li><a class="nav-link" href="{{ route('admin.users.index', ['role' => 'artisan']) }}">Artisans</a></li>
                        <li><a class="nav-link" href="{{ route('admin.users.index', ['role' => 'vendor']) }}">Vendeurs</a></li>
                        <li><a class="nav-link" href="{{ route('admin.users.create') }}">Ajouter un utilisateur</a></li>
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
                        <li><a class="nav-link" href="{{ route('admin.orders.index') }}">Toutes les commandes</a></li>
                        <li><a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">En attente</a></li>
                        <li><a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'processing']) }}">En traitement</a></li>
                        <li><a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'completed']) }}">Complétées</a></li>
                    </ul>
                </li>

                <!-- Devis -->
                <li class="{{ request()->is('admin/quotes*') ? 'active' : '' }}">
                    <a href="{{ route('admin.quotes.index') }}" class="nav-link">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Devis</span>
                    </a>
                </li>

                <!-- Événements culturels -->
                <li class="menu-header">Événements culturels</li>
                <li class="dropdown {{ request()->is('admin/events*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Événements</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('admin.events.index') }}">Tous les événements</a></li>
                        <li><a class="nav-link" href="{{ route('admin.events.create') }}">Créer un événement</a></li>
                    </ul>
                </li>

                <!-- Contenu & Avis -->
                <li class="menu-header">Contenu & Avis</li>
                <li class="{{ request()->is('admin/reviews*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reviews.index') }}" class="nav-link">
                        <i class="fas fa-star"></i>
                        <span>Avis & Évaluations</span>
                    </a>
                </li>

                <!-- Messages -->
                <li class="{{ request()->is('admin/contacts*') ? 'active' : '' }}">
                    <a href="{{ route('admin.contacts.index') }}" class="nav-link">
                        <i class="fas fa-envelope"></i>
                        <span>Messages</span>
                        @php
                            $unreadCount = \App\Models\Contact::where('read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="badge badge-danger">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>

                <!-- Analytics -->
                <li class="menu-header">Analytics</li>
                <li class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                    <a href="{{ route('admin.analytics') }}" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analytics & Rapports</span>
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
                            <li><a class="nav-link" href="{{ route('admin.settings.payment') }}">Paiements</a></li>
                            <li><a class="nav-link" href="{{ route('admin.settings.notifications') }}">Notifications</a></li>
                            <li><a class="nav-link" href="{{ route('admin.roles.index') }}">Rôles & Permissions</a></li>
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
                        <i class="fas fa-external-link-alt"></i>
                        <span>Voir le site</span>
                    </a>
                </li>
            @endrole

            <!-- ARTISAN uniquement -->
            @role('artisan')
                <li class="menu-header">Espace Artisan</li>

                <li class="{{ request()->routeIs('dashboard.artisan') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.artisan') }}" class="nav-link">
                        <i data-feather="monitor"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-header">Mes Produits</li>
                <li class="dropdown {{ request()->is('products*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i data-feather="shopping-bag"></i>
                        <span>Produits</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('products.index') }}">Mes produits</a></li>
                        <li><a class="nav-link" href="{{ route('products.create') }}">Ajouter un produit</a></li>
                    </ul>
                </li>

                <li class="menu-header">Ventes</li>
                <li class="{{ request()->routeIs('dashboard.artisan.orders') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.artisan.orders') }}" class="nav-link">
                        <i data-feather="shopping-cart"></i>
                        <span>Mes commandes</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                    <a href="{{ route('quotes.index') }}" class="nav-link">
                        <i data-feather="file-text"></i>
                        <span>Demandes de devis</span>
                    </a>
                </li>

                <li class="menu-header">Communication</li>
                <li class="{{ request()->routeIs('dashboard.messages') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.messages') }}" class="nav-link">
                        <i data-feather="message-square"></i>
                        <span>Messages</span>
                        @php($unreadCount = auth()->user()->unreadMessages?->count() ?? 0)
                        @if($unreadCount > 0)
                            <span class="badge badge-primary">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="{{ request()->routeIs('dashboard.artisan.reviews') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.artisan.reviews') }}" class="nav-link">
                        <i data-feather="star"></i>
                        <span>Avis clients</span>
                    </a>
                </li>

                <li class="menu-header">Statistiques</li>
                <li class="{{ request()->routeIs('dashboard.artisan.analytics') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.artisan.analytics') }}" class="nav-link">
                        <i data-feather="bar-chart-2"></i>
                        <span>Analytics</span>
                    </a>
                </li>

                <li class="menu-header">Mon Compte</li>
                @if(auth()->user()->artisan)
                    <li class="{{ request()->routeIs('artisan.profile.edit') ? 'active' : '' }}">
                        <a href="{{ route('artisan.profile.edit', auth()->user()->artisan->id) }}" class="nav-link">
                            <i data-feather="user"></i>
                            <span>Mon profil artisan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('artisans.show', auth()->user()->artisan->id) }}" class="nav-link" target="_blank">
                            <i data-feather="external-link"></i>
                            <span>Voir profil public</span>
                        </a>
                    </li>
                @endif

                <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <a href="{{ route('profile.edit') }}" class="nav-link">
                        <i data-feather="settings"></i>
                        <span>Paramètres</span>
                    </a>
                </li>

                <li class="menu-header">Navigation</li>
                <li>
                    <a href="{{ route('home') }}" class="nav-link">
                        <i data-feather="globe"></i>
                        <span>Retour au site</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-feather="log-out"></i>
                        <span>Déconnexion</span>
                    </a>
                </li>
            @endrole

            <!-- VENDEUR uniquement -->
            @role('vendor')
                <li class="menu-header">Espace Vendeur</li>

                <li class="{{ request()->routeIs('dashboard.vendor') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.vendor') }}" class="nav-link">
                        <i data-feather="monitor"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-header">Mes Plats</li>
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="coffee"></i>
                        <span>Plats</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="#">Mes plats</a></li>
                        <li><a class="nav-link" href="#">Ajouter un plat</a></li>
                    </ul>
                </li>

                <li class="menu-header">Ventes</li>
                <li class="{{ request()->routeIs('dashboard.orders') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.orders') }}" class="nav-link">
                        <i data-feather="shopping-cart"></i>
                        <span>Commandes</span>
                    </a>
                </li>

                <li class="menu-header">Communication</li>
                <li class="{{ request()->routeIs('dashboard.messages') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.messages') }}" class="nav-link">
                        <i data-feather="message-square"></i>
                        <span>Messages</span>
                    </a>
                </li>

                <li class="menu-header">Mon Compte</li>
                <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <a href="{{ route('profile.edit') }}" class="nav-link">
                        <i data-feather="settings"></i>
                        <span>Paramètres</span>
                    </a>
                </li>

                <li class="menu-header">Navigation</li>
                <li>
                    <a href="{{ route('home') }}" class="nav-link">
                        <i data-feather="globe"></i>
                        <span>Retour au site</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-feather="log-out"></i>
                        <span>Déconnexion</span>
                    </a>
                </li>
            @endrole

            <!-- CLIENT uniquement -->
            @role('client|user')
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
                        @php
                            $pendingOrders = auth()->user()->orders()->where('order_status', 'pending')->count();
                        @endphp
                        @if($pendingOrders > 0)
                            <span class="badge badge-warning">{{ $pendingOrders }}</span>
                        @endif
                    </a>
                </li>

                <li class="{{ request()->routeIs('orders.tracking') ? 'active' : '' }}">
                    <a href="{{ route('orders.tracking') }}" class="nav-link">
                        <i data-feather="map-pin"></i>
                        <span>Suivi de livraison</span>
                    </a>
                </li>

                <li class="menu-header">Favoris</li>
                <li class="{{ request()->routeIs('favorites') ? 'active' : '' }}">
                    <a href="{{ route('favorites') }}" class="nav-link">
                        <i data-feather="heart"></i>
                        <span>Mes favoris</span>
                        @php
                            $favCount = auth()->user()->favorites()->count();
                        @endphp
                        @if($favCount > 0)
                            <span class="badge badge-danger">{{ $favCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="menu-header">Mon Compte</li>
                <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <a href="{{ route('profile.edit') }}" class="nav-link">
                        <i data-feather="settings"></i>
                        <span>Paramètres</span>
                    </a>
                </li>

                <li class="menu-header">Navigation</li>
                <li>
                    <a href="{{ route('products.index') }}" class="nav-link">
                        <i data-feather="shopping-cart"></i>
                        <span>Continuer mes achats</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}" class="nav-link">
                        <i data-feather="globe"></i>
                        <span>Retour au site</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-feather="log-out"></i>
                        <span>Déconnexion</span>
                    </a>
                </li>
            @endrole

            <!-- Liens de déconnexion commun (déjà présent plusieurs fois → on le garde à la fin) -->
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
