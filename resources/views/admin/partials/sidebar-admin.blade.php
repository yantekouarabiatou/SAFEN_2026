{{--
    ============================================================
    SIDEBAR UNIFIÉE — admin/partials/sidebar.blade.php
    Gérée par les permissions Spatie
    ============================================================
--}}
@php
$user = auth()->user();
if (!$user) { return; }

// Vider le cache Spatie à chaque chargement (dev)
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

$isAdmin = $user->hasRole(['super-admin', 'admin']);
$isSuperAdmin= $user->hasRole('super-admin');
$isArtisan = $user->hasRole('artisan');
$isVendor = $user->hasRole('vendor');
$isClient = $user->hasRole('client');

// ── Badges ──────────────────────────────────────
// Artisans en attente d'approbation
$pendingArtisans = 0;
if ($user->can('approuver artisans')) {
try { $pendingArtisans = \App\Models\Artisan::where('status', 'pending')->count(); }
catch (\Exception $e) {}
}

// Produits : liés aux artisans, on compte les produits des artisans non approuvés
// OU si la table products a une colonne status, on l'utilise directement
$pendingProducts = 0;
if ($user->can('approuver produits')) {
try {
$productColumns = \Illuminate\Support\Facades\Schema::getColumnListing('products');
if (in_array('status', $productColumns)) {
$pendingProducts = \App\Models\Product::where('status', 'pending')->count();
} else {
// Fallback : produits des artisans en attente
$pendingArtisanIds = \App\Models\Artisan::where('status', 'pending')->pluck('id');
$pendingProducts = \App\Models\Product::whereIn('artisan_id', $pendingArtisanIds)->count();
}
} catch (\Exception $e) {}
}

// Commandes en attente
$pendingOrders = 0;
if ($user->can('gérer commandes')) {
try { $pendingOrders = \App\Models\Order::where('status', 'pending')->count(); }
catch (\Exception $e) {}
}

// Devis en attente
$pendingQuotes = 0;
try {
if ($user->can('gérer devis')) {
$pendingQuotes = \App\Models\Quote::where('status', 'pending')->count();
} elseif ($isArtisan && $user->artisan) {
$pendingQuotes = \App\Models\Quote::where('artisan_id', $user->artisan->id)
->where('status', 'pending')->count();
}
} catch (\Exception $e) {}

$unreadMessages = 0;
if ($isAdmin && $user->can('gérer messages')) {
$unreadMessages = \App\Models\Message::whereNull('read_at')->count();
} elseif (method_exists($user, 'unreadMessages')) {
$unreadMessages = $user->unreadMessages()->count();
}

$favCount = ($isClient && method_exists($user, 'favorites'))
? $user->favorites()->count() : 0;

// ── Route dashboard ──────────────────────────────
$dashRoute = match(true) {
$isAdmin => route('admin.dashboard'),
$isArtisan => route('dashboard.artisan'),
$isVendor => route('dashboard.vendor'),
$isClient => route('client.dashboard'),
default => route('home'),
};
@endphp

<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        {{-- ── BRAND ─────────────────────────────────── --}}
        <div class="sidebar-brand">
            <a href="{{ $dashRoute }}">
                <div class="d-flex align-items-center justify-content-center"
                    style="width:45px;height:45px;background:#008751;border-radius:50%;margin-right:10px;">
                    <i class="bi bi-flower1 text-white fs-4"></i>
                </div>
                <span class="logo-name" style="font-weight:bold;font-size:1.1rem;">TOTCHEMEGNON</span>
                <small class="d-block text-muted" style="font-size:11px;">
                    @if($isSuperAdmin) Super Admin
                    @elseif($isAdmin) Administration
                    @elseif($isArtisan) Espace Artisan
                    @elseif($isVendor) Espace Vendeur
                    @else Espace Client
                    @endif
                </small>
            </a>
        </div>

        <ul class="sidebar-menu">

            {{-- ══════════════════════════════════════════
                 TABLEAU DE BORD
            ══════════════════════════════════════════ --}}
            <li class="menu-header">Tableau de bord</li>
            <li class="{{ request()->routeIs('admin.dashboard','dashboard.artisan','dashboard.vendor','client.dashboard') ? 'active' : '' }}">
                <a href="{{ $dashRoute }}" class="nav-link">
                    <i data-feather="monitor"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- ══════════════════════════════════════════
                 GESTION GLOBALE (admin)
            ══════════════════════════════════════════ --}}
            @if($user->can('gérer artisans') || $user->can('approuver artisans'))
            <li class="menu-header">Gestion globale</li>

            {{-- Artisans --}}
            <li class="dropdown {{ request()->is('admin/artisans*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i data-feather="pen-tool"></i>
                    <span>Artisans</span>
                    @if($pendingArtisans > 0)
                    <span class="badge badge-warning ml-auto">{{ $pendingArtisans }}</span>
                    @endif
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.artisans.index') }}">Tous les artisans</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.artisans.index', ['status' => 'pending']) }}">
                            En attente @if($pendingArtisans > 0)<span class="badge badge-warning">{{ $pendingArtisans }}</span>@endif
                        </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.artisans.index', ['status' => 'approved']) }}">Approuvés</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.artisans.index', ['status' => 'rejected']) }}">Rejetés</a></li>
                    @if($user->can('créer artisans'))
                    <li><a class="dropdown-item" href="{{ route('admin.artisans.create') }}">+ Ajouter</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if($user->can('gérer produits') || $user->can('approuver produits'))
            {{-- Produits (admin) --}}
            <li class="dropdown {{ request()->is('admin/products*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i data-feather="shopping-bag"></i>
                    <span>Produits</span>
                    @if($pendingProducts > 0)
                    <span class="badge badge-warning ml-auto">{{ $pendingProducts }}</span>
                    @endif
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">Tous les produits</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.products.index', ['status' => 'pending']) }}">
                            En attente @if($pendingProducts > 0)<span class="badge badge-warning">{{ $pendingProducts }}</span>@endif
                        </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.products.index', ['status' => 'active']) }}">Actifs</a></li>
                    @if($user->can('créer produits'))
                    <li><a class="dropdown-item" href="{{ route('admin.products.create') }}">+ Ajouter</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if($user->can('gérer vendeurs'))
            <li class="dropdown {{ request()->is('admin/vendors*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i data-feather="briefcase"></i><span>Vendeurs</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.vendors.index') }}">Tous les vendeurs</a></li>
                    @if($user->can('créer vendeurs'))
                    <li><a class="dropdown-item" href="{{ route('admin.vendors.create') }}">+ Ajouter</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if($user->can('gérer plats') || $user->can('approuver plats'))
            <li class="dropdown {{ request()->is('admin/dishes*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i data-feather="coffee"></i><span>Gastronomie</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.dishes.index') }}">Tous les plats</a></li>
                    @if($user->can('créer plats'))
                    <li><a class="dropdown-item" href="{{ route('admin.dishes.create') }}">+ Ajouter</a></li>
                    @endif
                </ul>
            </li>
            @endif

            {{-- ══════════════════════════════════════════
                 GESTION UTILISATEURS (admin)
            ══════════════════════════════════════════ --}}
            @if($user->can('gérer utilisateurs'))
            <li class="menu-header">Utilisateurs</li>
            <li class="dropdown {{ request()->is('admin/users*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i data-feather="users"></i><span>Utilisateurs</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Tous</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.users.index', ['role' => 'artisan']) }}">Artisans</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.users.index', ['role' => 'vendor']) }}">Vendeurs</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.users.index', ['role' => 'client']) }}">Clients</a></li>
                    @if($user->can('créer utilisateurs'))
                    <li><a class="dropdown-item" href="{{ route('admin.users.create') }}">+ Ajouter</a></li>
                    @endif
                </ul>
            </li>
            @endif

            {{-- ══════════════════════════════════════════
                 MES PRODUITS (artisan)
            ══════════════════════════════════════════ --}}
            @if($isArtisan && $user->can('voir produits'))
            <li class="menu-header">Mes Produits</li>
            <li class="dropdown {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i data-feather="shopping-bag"></i><span>Produits</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">Mes produits</a></li>
                    @if($user->can('créer produits'))
                    <li><a class="dropdown-item" href="{{ route('admin.products.create') }}">+ Ajouter</a></li>
                    @endif
                </ul>
            </li>
            @endif

            {{-- ══════════════════════════════════════════
                 MES PLATS (vendor)
            ══════════════════════════════════════════ --}}
            @if($isVendor && $user->can('voir plats'))
            <li class="menu-header">Mes Plats</li>
            <li class="dropdown {{ request()->routeIs('vendor.dishes*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i data-feather="coffee"></i><span>Plats</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('vendor.dishes.index') }}">Mes plats</a></li>
                    @if($user->can('créer plats'))
                    <li><a class="dropdown-item" href="{{ route('vendor.dishes.create') }}">+ Ajouter</a></li>
                    @endif
                </ul>
            </li>
            @endif

            {{-- ══════════════════════════════════════════
                 TRANSACTIONS
            ══════════════════════════════════════════ --}}
            @if($user->can('voir commandes') || $user->can('voir devis'))
            <li class="menu-header">Transactions</li>

            @if($user->can('voir commandes'))
            @if($isAdmin)
            <li class="dropdown {{ request()->is('admin/orders*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i data-feather="shopping-cart"></i>
                    <span>Commandes</span>
                    @if($pendingOrders > 0)
                    <span class="badge badge-danger ml-auto">{{ $pendingOrders }}</span>
                    @endif
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">Toutes</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">
                            En attente @if($pendingOrders > 0)<span class="badge badge-danger">{{ $pendingOrders }}</span>@endif
                        </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'processing']) }}">En traitement</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'shipped']) }}">Expédiées</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'completed']) }}">Complétées</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}">Annulées</a></li>
                </ul>
            </li>
            @elseif($isArtisan)
            <li class="{{ request()->routeIs('dashboard.artisan.orders') ? 'active' : '' }}">
                <a href="{{ route('dashboard.artisan.orders') }}" class="nav-link">
                    <i data-feather="shopping-cart"></i><span>Mes commandes</span>
                </a>
            </li>
            @elseif($isVendor)
            <li class="{{ request()->routeIs('dashboard.orders') ? 'active' : '' }}">
                <a href="{{ route('dashboard.orders') }}" class="nav-link">
                    <i data-feather="shopping-cart"></i><span>Mes commandes</span>
                </a>
            </li>
            @elseif($isClient)
            <li class="{{ request()->routeIs('client.orders.*') ? 'active' : '' }}">
                <a href="{{ route('client.orders.index') }}" class="nav-link">
                    <i data-feather="shopping-bag"></i><span>Mes commandes</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('client.orders.tracking') ? 'active' : '' }}">
                <a href="{{ route('client.orders.tracking') }}" class="nav-link">
                    <i data-feather="map-pin"></i><span>Suivi de livraison</span>
                </a>
            </li>
            @endif
            @endif

            @if($user->can('voir devis') || $user->can('gérer devis'))
            <li class="{{ request()->routeIs('admin.quotes.*','client.quotes.*','quotes.*') ? 'active' : '' }}">
                <a href="{{ $isAdmin ? route('admin.quotes.index') : ($isClient ? route('client.quotes.index') : route('quotes.index')) }}"
                    class="nav-link">
                    <i data-feather="file-text"></i>
                    <span>Devis</span>
                    @if($pendingQuotes > 0)
                    <span class="badge badge-info ml-auto">{{ $pendingQuotes }}</span>
                    @endif
                </a>
            </li>
            @if($isClient)
            <li>
                <a href="{{ route('client.quotes.create') }}" class="nav-link">
                    <i data-feather="plus-circle"></i><span>Demander un devis</span>
                </a>
            </li>
            @endif
            @endif
            @endif

            {{-- ══════════════════════════════════════════
                 FAVORIS (client)
            ══════════════════════════════════════════ --}}
            @if($isClient && $user->can('gérer favoris'))
            <li class="menu-header">Favoris</li>
            <li class="{{ request()->routeIs('client.favorites.*') ? 'active' : '' }}">
                <a href="{{ route('client.favorites.index') }}" class="nav-link">
                    <i data-feather="heart"></i>
                    <span>Mes favoris</span>
                    @if($favCount > 0)<span class="badge badge-danger ml-auto">{{ $favCount }}</span>@endif
                </a>
            </li>
            @endif

            {{-- ══════════════════════════════════════════
                 CULTURE
            ══════════════════════════════════════════ --}}
            @if($user->can('voir événements'))
            <li class="menu-header">Culture</li>
            <li class="dropdown {{ request()->is('admin/events*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i data-feather="calendar"></i><span>Événements</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.events.index') }}">Tous les événements</a></li>
                    @if($user->can('créer événements'))
                    <li><a class="dropdown-item" href="{{ route('admin.events.create') }}">+ Créer</a></li>
                    @endif
                </ul>
            </li>
            @endif

            {{-- ══════════════════════════════════════════
                 CONTENU & COMMUNICATION
            ══════════════════════════════════════════ --}}
            @if($user->can('voir avis') || $user->can('voir messages') || $user->can('répondre messages'))
            <li class="menu-header">Contenu & Communication</li>

            @if($user->can('voir avis'))
            <li class="{{ request()->routeIs('admin.reviews.*','dashboard.artisan.reviews') ? 'active' : '' }}">
                <a href="{{ $isAdmin ? route('admin.reviews.index') : '#' }}" class="nav-link">
                    <i data-feather="star"></i><span>Avis & évaluations</span>
                </a>
            </li>
            @endif

            @if($user->can('voir messages') || $user->can('répondre messages'))
            <li class="{{ request()->routeIs('admin.contacts.*','client.messages.*','dashboard.messages') ? 'active' : '' }}">
                <a href="{{ $isAdmin ? route('admin.messages.index') : ($isClient ? route('client.messages.index') : route('dashboard.messages')) }}"
                    class="nav-link">
                    <i data-feather="mail"></i>
                    <span>Messagerie</span>
                    @if($unreadMessages > 0)
                    <span class="badge badge-warning ml-auto">{{ $unreadMessages }}</span>
                    @endif
                </a>
            </li>
            @endif

            @if($isClient)
            <li class="{{ request()->routeIs('client.contacts.create') ? 'active' : '' }}">
                <a href="{{ route('client.contacts.create') }}" class="nav-link">
                    <i data-feather="headphones"></i><span>Contacter un artisan</span>
                </a>
            </li>
            @endif
            @endif

            {{-- ══════════════════════════════════════════
                 ANALYTICS
            ══════════════════════════════════════════ --}}
            @if($user->can('voir analytics') || $user->can('voir analytics artisan') || $user->can('voir analytics vendeur'))
            <li class="menu-header">Analytics</li>
            <li class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <a href="{{ route('admin.analytics') }}" class="nav-link">
                    <i data-feather="bar-chart-2"></i><span>Rapports & statistiques</span>
                </a>
            </li>
            @endif

            {{-- ══════════════════════════════════════════
                 PARAMÈTRES SYSTÈME (super-admin)
            ══════════════════════════════════════════ --}}
            @if($user->can('gérer paramètres généraux'))
            <li class="menu-header">Paramètres système</li>
            <li class="dropdown {{ request()->is('admin/settings*','admin/roles*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i data-feather="settings"></i><span>Configuration</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Général</a></li>
                    @if($user->can('gérer paramètres paiement'))
                    <li><a class="dropdown-item" href="#">Paiements</a></li>
                    @endif
                    @if($user->can('gérer paramètres notifications'))
                    <li><a class="dropdown-item" href="#">Notifications</a></li>
                    @endif
                    @if($user->can('gérer rôles et permissions'))
                    <li><a class="dropdown-item" href="#">Rôles & permissions</a></li>
                    @endif
                </ul>
            </li>
            @endif

            {{-- ══════════════════════════════════════════
                 MON COMPTE
            ══════════════════════════════════════════ --}}
            <li class="menu-header">Mon Compte</li>

            @if($isArtisan && $user->artisan)
            <li class="{{ request()->routeIs('artisans.edit') ? 'active' : '' }}">
                <a href="{{ route('artisans.edit', $user->artisan) }}" class="nav-link">
                    <i data-feather="user"></i><span>Mon profil artisan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('artisans.show', $user->artisan) }}" class="nav-link" target="_blank">
                    <i data-feather="external-link"></i><span>Voir profil public</span>
                </a>
            </li>
            @endif

            <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <a href="{{ route('profile.edit') }}" class="nav-link">
                    <i data-feather="settings"></i><span>Paramètres du compte</span>
                </a>
            </li>

            {{-- ══════════════════════════════════════════
                 NAVIGATION
            ══════════════════════════════════════════ --}}
            <li class="menu-header">Navigation</li>

            @if($isClient)
            <li>
                <a href="{{ route('products.index') }}" class="nav-link">
                    <i data-feather="shopping-cart"></i><span>Continuer mes achats</span>
                </a>
            </li>
            @endif

            <li>
                <a href="{{ route('home') }}" class="nav-link" {{ $isAdmin ? 'target="_blank"' : '' }}>
                    <i data-feather="globe"></i>
                    <span>{{ $isAdmin ? 'Voir le site public' : 'Retour au site' }}</span>
                </a>
            </li>

            <li>
                <a href="{{ route('logout') }}" class="nav-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i data-feather="log-out"></i><span>Déconnexion</span>
                </a>
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </li>

        </ul>
    </aside>
</div>