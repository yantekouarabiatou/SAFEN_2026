{{--
    ============================================================
    SIDEBAR — admin/partials/sidebar.blade.php
    Fond blanc · couleurs Bénin · menus épurés
    ============================================================
--}}
@php
    $user = auth()->user();
    if (!$user) { return; }

    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $isAdmin      = $user->hasRole(['super-admin', 'admin']);
    $isSuperAdmin = $user->hasRole('super-admin');
    $isArtisan    = $user->hasRole('artisan');
    $isVendor     = $user->hasRole('vendor');
    $isClient     = $user->hasRole('client');

    // Badges
    $pendingArtisans = 0;
    if ($user->can('approuver artisans')) {
        try { $pendingArtisans = \App\Models\Artisan::where('status', 'pending')->count(); } catch (\Exception $e) {}
    }

    $pendingProducts = 0;
    if ($user->can('approuver produits')) {
        try {
            $cols = \Illuminate\Support\Facades\Schema::getColumnListing('products');
            $pendingProducts = in_array('status', $cols)
                ? \App\Models\Product::where('status', 'pending')->count()
                : \App\Models\Product::whereIn('artisan_id', \App\Models\Artisan::where('status','pending')->pluck('id'))->count();
        } catch (\Exception $e) {}
    }

    $pendingOrders = 0;
    if ($user->can('gérer commandes')) {
        try { $pendingOrders = \App\Models\Order::where('status', 'pending')->count(); } catch (\Exception $e) {}
    }

    $unreadMessages = 0;
    if ($isAdmin && $user->can('gérer messages')) {
        $unreadMessages = \App\Models\Contact::where('status', 'unread')->count();
    }

    $dashRoute = match(true) {
        $isAdmin   => route('admin.dashboard'),
        $isArtisan => route('dashboard.artisan'),
        $isVendor  => route('dashboard.vendor'),
        default    => route('home'),
    };
@endphp

<div class="main-sidebar sidebar-style-2">
<style>
/* ═══════════════════════════════════════════════
   SIDEBAR CLAIRE — TOTCHÉMÈGNON
   Fond blanc, accents verts Bénin
═══════════════════════════════════════════════ */

/* Reset fond sombre si injecté par le layout */
.main-sidebar,
#sidebar-wrapper,
aside#sidebar-wrapper {
    background: #ffffff !important;
    border-right: 1px solid #e8ecf0 !important;
    box-shadow: 2px 0 16px rgba(0,0,0,.06) !important;
}

/* Brand */
.sidebar-brand {
    background: #ffffff !important;
    border-bottom: 1px solid #e8ecf0 !important;
    padding: 16px 18px !important;
}
.sidebar-brand a {
    display: flex !important;
    align-items: center !important;
    gap: 11px !important;
    color: #1a1d23 !important;
    text-decoration: none !important;
}

/* Headers de section */
.main-sidebar .sidebar-menu .menu-header {
    font-size: .65rem !important;
    font-weight: 700 !important;
    letter-spacing: 1.2px !important;
    text-transform: uppercase !important;
    color: #b0b8c4 !important;
    padding: 18px 20px 6px !important;
    margin: 0 !important;
}

/* Items normaux */
.main-sidebar .sidebar-menu > li > a {
    color: #4b5563 !important;
    font-size: .855rem !important;
    font-weight: 500 !important;
    padding: 10px 18px !important;
    margin: 2px 10px !important;
    border-radius: 9px !important;
    display: flex !important;
    align-items: center !important;
    gap: 11px !important;
    transition: background .18s, color .18s !important;
    background: transparent !important;
}
.main-sidebar .sidebar-menu > li > a:hover {
    background: rgba(0,135,81,.08) !important;
    color: #008751 !important;
}
.main-sidebar .sidebar-menu > li > a svg,
.main-sidebar .sidebar-menu > li > a i[data-feather] {
    width: 18px !important;
    height: 18px !important;
    flex-shrink: 0 !important;
    color: #9ca3af !important;
    transition: color .18s !important;
}
.main-sidebar .sidebar-menu > li > a:hover svg,
.main-sidebar .sidebar-menu > li > a:hover i[data-feather] {
    color: #008751 !important;
}

/* Item actif */
.main-sidebar .sidebar-menu > li.active > a {
    background: linear-gradient(135deg, #008751, #00a862) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(0,135,81,.28) !important;
    font-weight: 600 !important;
}
.main-sidebar .sidebar-menu > li.active > a svg,
.main-sidebar .sidebar-menu > li.active > a i[data-feather] {
    color: rgba(255,255,255,.85) !important;
}

/* ── Dropdown sous-menu — override Bootstrap 5 ── */
/* Bootstrap 5 force position:absolute → on remet static pour l'affichage inline */
.main-sidebar .sidebar-menu li ul.dropdown-menu {
    position: static !important;
    float: none !important;
    width: 100% !important;
    box-shadow: none !important;
    border: none !important;
    border-radius: 0 !important;
    padding: 4px 0 8px !important;
    margin: 0 !important;
    min-width: 0 !important;
    background: transparent !important;
    /* display géré par jQuery slideToggle */
}
/* Items sous-menu */
.main-sidebar .sidebar-menu li ul.dropdown-menu li a {
    height: auto !important;
    padding: 8px 16px 8px 50px !important;
    font-size: .82rem !important;
    font-weight: 500 !important;
    color: #6b7280 !important;
    border-radius: 7px !important;
    margin: 1px 10px !important;
    display: flex !important;
    align-items: center !important;
    gap: 7px !important;
    transition: background .15s, color .15s !important;
    position: relative !important;
    background: transparent !important;
    width: calc(100% - 20px) !important;
}
/* Puce décorative */
.main-sidebar .sidebar-menu li ul.dropdown-menu li a::before {
    content: '' !important;
    width: 5px !important;
    height: 5px !important;
    border-radius: 50% !important;
    background: #d1d5db !important;
    flex-shrink: 0 !important;
    transition: background .15s !important;
    display: inline-block !important;
}
.main-sidebar .sidebar-menu li ul.dropdown-menu li a:hover {
    background: rgba(0,135,81,.08) !important;
    color: #008751 !important;
}
.main-sidebar .sidebar-menu li ul.dropdown-menu li a:hover::before {
    background: #008751 !important;
}
/* Badge dans sous-menu */
.main-sidebar .sidebar-menu li ul.dropdown-menu li a .badge {
    margin-left: auto !important;
    float: none !important;
    padding: 2px 6px !important;
}

/* Badges */
.main-sidebar .badge {
    font-size: .66rem !important;
    padding: 3px 7px !important;
    border-radius: 10px !important;
    margin-left: auto !important;
    font-weight: 700 !important;
}
.main-sidebar .badge-warning { background: #FCD116 !important; color: #78350f !important; }
.main-sidebar .badge-danger  { background: #E8112D !important; color: #fff !important; }

/* Séparateur bas de sidebar */
.sidebar-divider {
    border: none !important;
    border-top: 1px solid #e8ecf0 !important;
    margin: 10px 16px !important;
}

/* Lien danger déconnexion */
.nav-link-logout {
    color: #E8112D !important;
}
.nav-link-logout i[data-feather] { color: #E8112D !important; }
.nav-link-logout:hover { background: rgba(232,17,45,.08) !important; color: #c0000f !important; }
</style>

<aside id="sidebar-wrapper">

    {{-- ── BRAND / LOGO ──────────────────────────── --}}
    <div class="sidebar-brand">
        <a href="{{ $dashRoute }}">
            <svg width="40" height="40" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink:0;">
                <defs>
                    <linearGradient id="sbG" x1="0" y1="0" x2="44" y2="44" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#00b86b"/>
                        <stop offset="100%" stop-color="#005c38"/>
                    </linearGradient>
                </defs>
                {{-- Cercle fond vert --}}
                <circle cx="22" cy="22" r="21" fill="url(#sbG)"/>
                {{-- Anneau or --}}
                <circle cx="22" cy="22" r="21" fill="none" stroke="#FCD116" stroke-width="1.6"/>
                {{-- Lettre T blanche --}}
                <rect x="9" y="13" width="26" height="5" rx="2.5" fill="#fff"/>
                <rect x="17.5" y="13" width="9" height="19" rx="2.5" fill="#fff"/>
                {{-- Points décoratifs --}}
                <circle cx="5.5" cy="22" r="2" fill="#FCD116"/>
                <circle cx="38.5" cy="22" r="2" fill="#FCD116"/>
                <circle cx="22" cy="5" r="1.7" fill="#E8112D"/>
                {{-- Losange or haut --}}
                <path d="M22 9 L24 12 L22 14.5 L20 12Z" fill="#FCD116" opacity=".75"/>
            </svg>

            <div style="overflow:hidden; line-height:1.1;">
                <div style="font-family:'Montserrat',sans-serif;font-weight:800;font-size:.9rem;
                            letter-spacing:-.3px;color:#1a1d23;white-space:nowrap;">
                    TOTCHÉMÈGNON
                </div>
                <div style="font-size:.64rem;letter-spacing:.5px;text-transform:uppercase;
                            color:#9ca3af;margin-top:2px;">
                    @if($isSuperAdmin) Super Admin
                    @elseif($isAdmin)  Administration
                    @elseif($isArtisan) Espace Artisan
                    @elseif($isVendor)  Espace Vendeur
                    @else               Mon espace
                    @endif
                </div>
            </div>
        </a>
    </div>

    <ul class="sidebar-menu" style="padding-bottom:20px;">

        {{-- ═══════════════════════════════════
             PRINCIPAL
        ═══════════════════════════════════ --}}
        <li class="menu-header">Principal</li>

        <li class="{{ request()->routeIs('admin.dashboard','dashboard.artisan','dashboard.vendor') ? 'active' : '' }}">
            <a href="{{ $dashRoute }}" class="nav-link">
                <i data-feather="home"></i>
                <span>Tableau de bord</span>
            </a>
        </li>

        @if($user->can('voir analytics') || $user->can('voir analytics artisan'))
        <li class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
            <a href="{{ route('admin.analytics') }}" class="nav-link">
                <i data-feather="bar-chart-2"></i>
                <span>Analytics</span>
            </a>
        </li>
        @endif

        {{-- ═══════════════════════════════════
             CATALOGUE (admin)
        ═══════════════════════════════════ --}}
        @if($isAdmin)
        <li class="menu-header">Catalogue</li>

        {{-- Artisans --}}
        @if($user->can('gérer artisans') || $user->can('approuver artisans'))
        <li class="dropdown {{ request()->is('admin/artisans*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i data-feather="pen-tool"></i>
                <span>Artisans</span>
                @if($pendingArtisans > 0)
                    <span class="badge badge-warning">{{ $pendingArtisans }}</span>
                @endif
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.artisans.index') }}">Tous les artisans</a></li>
                @if($pendingArtisans > 0)
                <li><a href="{{ route('admin.artisans.index', ['status' => 'pending']) }}">
                    En attente <span class="badge badge-warning">{{ $pendingArtisans }}</span>
                </a></li>
                @endif
                <li><a href="{{ route('admin.artisans.index', ['status' => 'approved']) }}">Approuvés</a></li>
                @if($user->can('créer artisans'))
                <li><a href="{{ route('admin.artisans.create') }}">+ Ajouter un artisan</a></li>
                @endif
            </ul>
        </li>
        @endif

        {{-- Produits --}}
        @if($user->can('gérer produits') || $user->can('approuver produits'))
        <li class="dropdown {{ request()->is('admin/products*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i data-feather="shopping-bag"></i>
                <span>Produits</span>
                @if($pendingProducts > 0)
                    <span class="badge badge-warning">{{ $pendingProducts }}</span>
                @endif
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.products.index') }}">Tous les produits</a></li>
                @if($pendingProducts > 0)
                <li><a href="{{ route('admin.products.index', ['status' => 'pending']) }}">
                    En attente <span class="badge badge-warning">{{ $pendingProducts }}</span>
                </a></li>
                @endif
                <li><a href="{{ route('admin.products.index', ['status' => 'active']) }}">Actifs</a></li>
                @if($user->can('créer produits'))
                <li><a href="{{ route('admin.products.create') }}">+ Ajouter un produit</a></li>
                @endif
            </ul>
        </li>
        @endif

        {{-- Gastronomie --}}
        @if($user->can('gérer plats') || $user->can('approuver plats'))
        <li class="dropdown {{ request()->is('admin/dishes*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i data-feather="coffee"></i>
                <span>Gastronomie</span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.dishes.index') }}">Tous les plats</a></li>
                @if($user->can('créer plats'))
                <li><a href="{{ route('admin.dishes.create') }}">+ Ajouter un plat</a></li>
                @endif
            </ul>
        </li>
        @endif

        {{-- Événements culturels --}}
        @if($user->can('voir événements'))
        <li class="dropdown {{ request()->is('admin/events*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i data-feather="calendar"></i>
                <span>Événements</span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.events.index') }}">Tous les événements</a></li>
                @if($user->can('créer événements'))
                <li><a href="{{ route('admin.events.create') }}">+ Créer un événement</a></li>
                @endif
            </ul>
        </li>
        @endif
        @endif {{-- fin $isAdmin --}}

        {{-- ═══════════════════════════════════
             MES PRODUITS (artisan)
        ═══════════════════════════════════ --}}
        @if($isArtisan && $user->can('voir produits'))
        <li class="menu-header">Mes produits</li>
        <li class="{{ request()->is('admin/products*') ? 'active' : '' }}">
            <a href="{{ route('admin.products.index') }}" class="nav-link">
                <i data-feather="shopping-bag"></i>
                <span>Mes produits</span>
            </a>
        </li>
        @if($user->can('créer produits'))
        <li class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
            <a href="{{ route('admin.products.create') }}" class="nav-link">
                <i data-feather="plus-circle"></i>
                <span>Ajouter un produit</span>
            </a>
        </li>
        @endif
        @endif

        {{-- ═══════════════════════════════════
             MES PLATS (vendor)
        ═══════════════════════════════════ --}}
        @if($isVendor && $user->can('voir plats'))
        <li class="menu-header">Mes plats</li>
        <li class="{{ request()->routeIs('vendor.dishes*') ? 'active' : '' }}">
            <a href="{{ route('vendor.dishes.index') }}" class="nav-link">
                <i data-feather="coffee"></i>
                <span>Mes plats</span>
            </a>
        </li>
        @if($user->can('créer plats'))
        <li class="{{ request()->routeIs('vendor.dishes.create') ? 'active' : '' }}">
            <a href="{{ route('vendor.dishes.create') }}" class="nav-link">
                <i data-feather="plus-circle"></i>
                <span>Ajouter un plat</span>
            </a>
        </li>
        @endif
        @endif

        {{-- ═══════════════════════════════════
             UTILISATEURS (admin)
        ═══════════════════════════════════ --}}
        @if($isAdmin && $user->can('gérer utilisateurs'))
        <li class="menu-header">Utilisateurs</li>
        <li class="dropdown {{ request()->is('admin/users*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i data-feather="users"></i>
                <span>Utilisateurs</span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.users.index') }}">Tous</a></li>
                <li><a href="{{ route('admin.users.index', ['role' => 'artisan']) }}">Artisans</a></li>
                <li><a href="{{ route('admin.users.index', ['role' => 'vendor']) }}">Vendeurs</a></li>
                <li><a href="{{ route('admin.users.index', ['role' => 'client']) }}">Clients</a></li>
                @if($user->can('créer utilisateurs'))
                <li><a href="{{ route('admin.users.create') }}">+ Ajouter</a></li>
                @endif
            </ul>
        </li>

        @if($user->can('gérer vendeurs'))
        <li class="{{ request()->is('admin/vendors*') ? 'active' : '' }}">
            <a href="{{ route('admin.vendors.index') }}" class="nav-link">
                <i data-feather="briefcase"></i>
                <span>Vendeurs</span>
            </a>
        </li>
        @endif
        @endif

        {{-- ═══════════════════════════════════
             COMMERCE
        ═══════════════════════════════════ --}}
        @if($user->can('voir commandes'))
        <li class="menu-header">Commerce</li>

        @if($isAdmin)
        <li class="dropdown {{ request()->is('admin/orders*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i data-feather="shopping-cart"></i>
                <span>Commandes</span>
                @if($pendingOrders > 0)
                    <span class="badge badge-danger">{{ $pendingOrders }}</span>
                @endif
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.orders.index') }}">Toutes les commandes</a></li>
                @if($pendingOrders > 0)
                <li><a href="{{ route('admin.orders.index', ['status' => 'pending']) }}">
                    En attente <span class="badge badge-danger">{{ $pendingOrders }}</span>
                </a></li>
                @endif
                <li><a href="{{ route('admin.orders.index', ['status' => 'processing']) }}">En traitement</a></li>
                <li><a href="{{ route('admin.orders.index', ['status' => 'completed']) }}">Complétées</a></li>
            </ul>
        </li>
        @elseif($isArtisan)
        <li class="{{ request()->routeIs('dashboard.artisan.orders') ? 'active' : '' }}">
            <a href="{{ route('dashboard.artisan.orders') }}" class="nav-link">
                <i data-feather="shopping-cart"></i>
                <span>Mes commandes</span>
            </a>
        </li>
        @elseif($isVendor)
        <li class="{{ request()->routeIs('dashboard.orders') ? 'active' : '' }}">
            <a href="{{ route('dashboard.orders') }}" class="nav-link">
                <i data-feather="shopping-cart"></i>
                <span>Mes commandes</span>
            </a>
        </li>
        @endif
        @endif

        {{-- ═══════════════════════════════════
             COMMUNICATION (admin)
        ═══════════════════════════════════ --}}
        @if($isAdmin && ($user->can('voir messages') || $user->can('voir avis')))
        <li class="menu-header">Communication</li>

        @if($user->can('voir messages') || $user->can('répondre messages'))
        <li class="{{ request()->routeIs('admin.messages.*','admin.contacts.*') ? 'active' : '' }}">
            <a href="{{ route('admin.messages.index') }}" class="nav-link">
                <i data-feather="mail"></i>
                <span>Messagerie</span>
                @if($unreadMessages > 0)
                    <span class="badge badge-danger">{{ $unreadMessages }}</span>
                @endif
            </a>
        </li>
        @endif

        @if($user->can('voir avis'))
        <li class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <a href="{{ route('admin.reviews.index') }}" class="nav-link">
                <i data-feather="star"></i>
                <span>Avis & évaluations</span>
            </a>
        </li>
        @endif
        @endif

        {{-- ═══════════════════════════════════
             PARAMÈTRES (admin)
        ═══════════════════════════════════ --}}
        @if($isAdmin && $user->can('gérer paramètres généraux'))
        <li class="menu-header">Paramètres</li>
        <li class="{{ request()->is('admin/settings*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.index', ['section' => 'general']) }}" class="nav-link">
                <i data-feather="settings"></i>
                <span>Configuration</span>
            </a>
        </li>
        @endif

        {{-- ═══════════════════════════════════
             MON COMPTE (séparateur bas)
        ═══════════════════════════════════ --}}
        <hr class="sidebar-divider">

        @if($isArtisan && isset($user->artisan) && $user->artisan)
        <li class="{{ request()->routeIs('artisans.edit') ? 'active' : '' }}">
            <a href="{{ route('artisans.edit', $user->artisan) }}" class="nav-link">
                <i data-feather="user"></i>
                <span>Mon profil artisan</span>
            </a>
        </li>
        @endif

        <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <a href="{{ route('profile.edit') }}" class="nav-link">
                <i data-feather="user"></i>
                <span>Mon compte</span>
            </a>
        </li>

        <li>
            <a href="{{ url('/') }}" class="nav-link" target="_blank">
                <i data-feather="globe"></i>
                <span>Voir le site public</span>
            </a>
        </li>

        <li>
            <a href="{{ route('logout') }}" class="nav-link nav-link-logout"
               onclick="event.preventDefault(); document.getElementById('sb-logout').submit();">
                <i data-feather="log-out"></i>
                <span>Déconnexion</span>
            </a>
            <form id="sb-logout" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        </li>

    </ul>
</aside>
</div>
