{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'SAFEN - Tableau de bord')</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('storage/company/favicon.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('storage/company/favicon.png') }}">

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('admin-assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/select2/dist/css/select2.min.css') }}">
    @stack('styles')
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            {{-- Navbar --}}
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li>
                            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn">
                                <i data-feather="align-justify"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link nav-link-lg fullscreen-btn">
                                <i data-feather="maximize"></i>
                            </a>
                        </li>
                        <li>
                            <form class="form-inline mr-auto" action="{{ route('search') }}" method="GET">
                                <div class="search-element">
                                    <input class="form-control" type="search" name="q" placeholder="Rechercher artisans, produits, plats…" aria-label="Search">
                                    <button class="btn" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>

                <ul class="navbar-nav navbar-right">
                    {{-- Notifications --}}
                    <li class="dropdown dropdown-list-toggle">
                        @auth
                            @php
                                $user = auth()->user();
                                $unreadCount = $user->unreadNotifications()->count();
                                $notifications = $user->notifications()->latest()->take(7)->get();
                            @endphp
                            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
                                <i class="far fa-bell" style="color: #000000;"></i>
                                <span id="unread-count" class="badge badge-danger badge-header"
                                    style="{{ $unreadCount > 0 ? '' : 'display: none;' }}">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown" style="width: 360px;">
                                <div class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span>Notifications</span>
                                    @if($unreadCount > 0)
                                        <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link text-primary p-0 border-0 small">
                                                Tout marquer comme lu
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="dropdown-list-content dropdown-list-icons" style="max-height: 320px; overflow-y: auto;">
                                    @forelse($notifications as $notification)
                                        <a href="{{ $notification->data['url'] ?? '#' }}"
                                            class="dropdown-item dropdown-item-unread-hover {{ $notification->read_at ? '' : 'dropdown-item-unread' }}"
                                            data-notification-id="{{ $notification->id }}"
                                            onclick="handleNotificationClick(event, this)">
                                            <div class="dropdown-item-icon {{ $notification->data['color'] ?? 'bg-primary' }} text-white">
                                                <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }}"></i>
                                            </div>
                                            <div class="dropdown-item-desc">
                                                <div class="notification-message">
                                                    {!! $notification->data['message'] ?? 'Notification' !!}
                                                </div>
                                                <div class="time text-muted small mt-1">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="dropdown-item text-center text-muted py-5">
                                            <i class="far fa-bell-slash fa-3x mb-3"></i>
                                            <p class="mb-0">Aucune notification</p>
                                        </div>
                                    @endforelse
                                </div>

                                <div class="dropdown-footer text-center border-top pt-3">
                                    <a href="{{ route('notifications.index') }}" class="font-weight-bold" style="color: #2c5282;">
                                        Voir toutes les notifications <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </li>

                    {{-- Bouton paramètres mobile --}}
                    <li class="nav-item d-lg-none">
                        <a href="javascript:void(0)" class="nav-link nav-link-lg settingPanelToggleMobile">
                            <i class="fa fa-cog fa-spin"></i>
                        </a>
                    </li>

                    {{-- Profil utilisateur --}}
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            @auth
                                @php
                                    $user = auth()->user();
                                    $nomComplet = $user->name;
                                    $initiales = strtoupper(substr($user->name, 0, 2));

                                    // Déterminer le rôle affiché
                                    $displayRole = 'Utilisateur';
                                    if ($user->hasRole('super-admin')) $displayRole = 'Super Administrateur';
                                    elseif ($user->hasRole('admin')) $displayRole = 'Administrateur';
                                    elseif ($user->hasRole('artisan')) $displayRole = 'Artisan';
                                    elseif ($user->hasRole('vendor')) $displayRole = 'Vendeur';
                                    elseif ($user->hasRole('client')) $displayRole = 'Client';

                                    // Couleur avatar
                                    $colors = [
                                        ['bg' => '#4a70b7', 'border' => '#3a5a9d'],
                                        ['bg' => '#10b981', 'border' => '#0da271'],
                                        ['bg' => '#f59e0b', 'border' => '#d97706'],
                                        ['bg' => '#ef4444', 'border' => '#dc2626'],
                                    ];
                                    $colorIndex = crc32($user->email) % count($colors);
                                    $selectedColor = $colors[$colorIndex];
                                @endphp

                                <div class="d-flex align-items-center">
                                    <div class="avatar-wrapper position-relative">
                                        @if($user->avatar)
                                            <img alt="image" src="{{ $user->avatar_url }}"
                                                class="user-img-radious-style"
                                                style="width: 38px; height: 38px; object-fit: cover; border-radius: 50%;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center user-img-radious-style"
                                                style="background: {{ $selectedColor['bg'] }}; color: white; width: 38px; height: 38px;
                                                border-radius: 50%; font-weight: 600; font-size: 14px;
                                                border: 2px solid {{ $selectedColor['border'] }};">
                                                {{ $initiales }}
                                            </div>
                                        @endif
                                        <div style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px;
                                            background: #10b981; border: 2px solid white; border-radius: 50%;"></div>
                                    </div>
                                    <div class="user-info ml-2 d-none d-lg-block">
                                        <div class="user-name" style="font-size: 14px; font-weight: 600; color: #2d3748; line-height: 1.2;">
                                            {{ $nomComplet }}
                                        </div>
                                        <div class="user-role" style="font-size: 12px; color: #718096; line-height: 1.2;">
                                            {{ $displayRole }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center">
                                    <div class="avatar-wrapper">
                                        <img alt="image" src="{{ asset('admin-assets/img/avatar-default.png') }}"
                                            class="user-img-radious-style"
                                            style="width: 38px; height: 38px; object-fit: cover; border-radius: 50%;">
                                    </div>
                                    <div class="user-info ml-2 d-none d-lg-block">
                                        <div class="user-name" style="font-size: 14px; font-weight: 600; color: #2d3748;">
                                            Invité
                                        </div>
                                    </div>
                                </div>
                            @endauth
                        </a>

                        {{-- Dropdown menu --}}
                        @auth
                        <div class="dropdown-menu dropdown-menu-right pullDown"
                            style="border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-radius: 12px; min-width: 260px; overflow: hidden;">
                            <div class="dropdown-header" style="background: linear-gradient(135deg, #4a70b7, #2c5282); color: white; padding: 20px;">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        @if($user->avatar)
                                            <img alt="image" src="{{ $user->avatar_url }}"
                                                style="width: 54px; height: 54px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255,255,255,0.3);">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center"
                                                style="background: {{ $selectedColor['bg'] }}; color: white; width: 54px; height: 54px;
                                                border-radius: 50%; font-weight: 600; font-size: 18px; border: 3px solid rgba(255,255,255,0.3);">
                                                {{ $initiales }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-size: 16px; font-weight: 600; margin-bottom: 2px;">
                                            {{ $user->name }}
                                        </div>
                                        <div style="font-size: 12px; opacity: 0.8; margin-bottom: 6px;">
                                            {{ $user->email }}
                                        </div>
                                        <div style="font-size: 11px; font-weight: 600; background: rgba(255,255,255,0.2);
                                            padding: 3px 10px; border-radius: 20px; display: inline-block;">
                                            {{ $displayRole }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-body" style="padding: 10px 0;">
                                <a href="#" class="dropdown-item has-icon d-flex align-items-center py-2">
                                    <div class="icon-wrapper mr-3 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px; background: #ebf5ff; border-radius: 8px;">
                                        <i class="far fa-user text-primary" style="font-size: 14px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #2d3748;">Mon profil</div>
                                        <small class="text-muted d-block">Voir mes informations</small>
                                    </div>
                                </a>

                                <a href="{{ route('notifications.index') }}" class="dropdown-item has-icon d-flex align-items-center py-2">
                                    <div class="icon-wrapper mr-3 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px; background: #fff5f5; border-radius: 8px;">
                                        <i class="far fa-bell text-danger" style="font-size: 14px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #2d3748;">Notifications</div>
                                        <small class="text-muted d-block">
                                            {{ $unreadCount > 0 ? $unreadCount . ' nouvelles' : 'À jour' }}
                                        </small>
                                    </div>
                                </a>

                                @can('voir analytics')
                                <a href="{{ route('admin.analytics') }}" class="dropdown-item has-icon d-flex align-items-center py-2">
                                    <div class="icon-wrapper mr-3 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px; background: #fffbeb; border-radius: 8px;">
                                        <i class="fas fa-chart-bar text-warning" style="font-size: 14px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #2d3748;">Analytiques</div>
                                        <small class="text-muted d-block">Rapports et statistiques</small>
                                    </div>
                                </a>
                                @endcan

                                @if($user->hasRole('artisan') && $user->artisan)
                                <a href="{{ route('artisan.profile.edit', $user->artisan->id) }}" class="dropdown-item has-icon d-flex align-items-center py-2">
                                    <div class="icon-wrapper mr-3 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px; background: #e6f7e6; border-radius: 8px;">
                                        <i class="fas fa-palette text-success" style="font-size: 14px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #2d3748;">Profil artisan</div>
                                        <small class="text-muted d-block">Gérer ma boutique</small>
                                    </div>
                                </a>
                                @endif

                                @if($user->hasRole('vendor') && $user->vendor)
                                <a href="{{ route('vendor.profile.edit', $user->vendor->id) }}" class="dropdown-item has-icon d-flex align-items-center py-2">
                                    <div class="icon-wrapper mr-3 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px; background: #fff0d9; border-radius: 8px;">
                                        <i class="fas fa-store text-orange" style="font-size: 14px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #2d3748;">Profil vendeur</div>
                                        <small class="text-muted d-block">Gérer mon restaurant</small>
                                    </div>
                                </a>
                                @endif

                                <div class="dropdown-divider my-2"></div>

                                <form method="POST" action="{{ route('logout') }}" id="logout-form-nav">
                                    @csrf
                                    <a href="#" class="dropdown-item has-icon d-flex align-items-center py-2 text-danger"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                                        <div class="icon-wrapper mr-3 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background: #fef2f2; border-radius: 8px;">
                                            <i class="fas fa-sign-out-alt" style="font-size: 14px;"></i>
                                        </div>
                                        <div style="font-weight: 600;">Déconnexion</div>
                                    </a>
                                </form>
                            </div>
                        </div>
                        @endauth
                    </li>
                </ul>
            </nav>

            {{-- ========== SIDEBAR ========== --}}
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="{{ route('admin.dashboard') }}">
                            <div class="d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background-color: #198754; border-radius: 50%; margin-right: 10px;">
                                <i class="bi bi-flower1 text-white fs-4"></i>
                            </div>
                            <span class="logo-name" style="font-weight: bold; font-size: 1.2rem;">SAFEN</span>
                            <small class="d-block text-muted">Bénin</small>
                        </a>
                    </div>

                    <ul class="sidebar-menu">
                        {{-- DASHBOARD (tous) --}}
                        <li class="menu-header">TABLEAU DE BORD</li>
                        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        {{-- ========== ADMIN / GESTION GLOBALE ========== --}}
                        @canany(['gérer artisans', 'voir produits', 'voir utilisateurs', 'voir commandes', 'voir événements', 'voir analytics'])
                        <li class="menu-header">GESTION GLOBALE</li>

                        {{-- Artisans --}}
                        @canany(['voir artisans', 'gérer artisans'])
                        <li class="dropdown {{ request()->is('admin/artisans*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown">
                                <i class="fas fa-palette"></i>
                                <span>Artisans</span>
                            </a>
                            <ul class="dropdown-menu">
                                @can('voir artisans')
                                <li><a class="nav-link" href="{{ route('admin.artisans.index') }}">Tous les artisans</a></li>
                                <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'pending']) }}">En attente</a></li>
                                <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'approved']) }}">Approuvés</a></li>
                                <li><a class="nav-link" href="{{ route('admin.artisans.index', ['status' => 'rejected']) }}">Rejetés</a></li>
                                @endcan
                                @can('créer artisans')
                                <li><a class="nav-link" href="{{ route('admin.artisans.create') }}">Ajouter un artisan</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        {{-- Produits --}}
                        @canany(['voir produits', 'gérer produits'])
                        <li class="dropdown {{ request()->is('admin/products*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown">
                                <i class="fas fa-shopping-bag"></i>
                                <span>Produits</span>
                            </a>
                            <ul class="dropdown-menu">
                                @can('voir produits')
                                <li><a class="nav-link" href="{{ route('admin.products.index') }}">Tous les produits</a></li>
                                <li><a class="nav-link" href="{{ route('admin.products.index', ['status' => 'pending']) }}">En attente</a></li>
                                <li><a class="nav-link" href="{{ route('admin.products.index', ['status' => 'active']) }}">Actifs</a></li>
                                @endcan
                                @can('créer produits')
                                <li><a class="nav-link" href="{{ route('admin.products.create') }}">Ajouter un produit</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        {{-- Vendeurs --}}
                        @canany(['voir vendeurs', 'gérer vendeurs'])
                        <li class="dropdown {{ request()->is('admin/vendors*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown">
                                <i class="fas fa-store"></i>
                                <span>Vendeurs</span>
                            </a>
                            <ul class="dropdown-menu">
                                @can('voir vendeurs')
                                <li><a class="nav-link" href="{{ route('admin.vendors.index') }}">Tous les vendeurs</a></li>
                                @endcan
                                @can('créer vendeurs')
                                <li><a class="nav-link" href="{{ route('admin.vendors.create') }}">Ajouter un vendeur</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        {{-- Gastronomie / Plats --}}
                        @canany(['voir plats', 'gérer plats'])
                        <li class="dropdown {{ request()->is('admin/dishes*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown">
                                <i class="fas fa-utensils"></i>
                                <span>Gastronomie</span>
                            </a>
                            <ul class="dropdown-menu">
                                @can('voir plats')
                                <li><a class="nav-link" href="{{ route('admin.dishes.index') }}">Tous les plats</a></li>
                                @endcan
                                @can('créer plats')
                                <li><a class="nav-link" href="{{ route('admin.dishes.create') }}">Ajouter un plat</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        {{-- Utilisateurs --}}
                        @canany(['voir utilisateurs', 'gérer utilisateurs'])
                        <li class="menu-header">GESTION DES UTILISATEURS</li>
                        <li class="dropdown {{ request()->is('admin/users*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown">
                                <i class="fas fa-users"></i>
                                <span>Utilisateurs</span>
                            </a>
                            <ul class="dropdown-menu">
                                @can('voir utilisateurs')
                                <li><a class="nav-link" href="{{ route('admin.users.index') }}">Tous les utilisateurs</a></li>
                                <li><a class="nav-link" href="{{ route('admin.users.index', ['role' => 'artisan']) }}">Artisans</a></li>
                                <li><a class="nav-link" href="{{ route('admin.users.index', ['role' => 'vendor']) }}">Vendeurs</a></li>
                                @endcan
                                @can('créer utilisateurs')
                                <li><a class="nav-link" href="{{ route('admin.users.create') }}">Ajouter un utilisateur</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        {{-- Commandes --}}
                        @canany(['voir commandes', 'gérer commandes'])
                        <li class="menu-header">TRANSACTIONS</li>
                        <li class="dropdown {{ request()->is('admin/orders*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Commandes</span>
                            </a>
                            <ul class="dropdown-menu">
                                @can('voir commandes')
                                <li><a class="nav-link" href="{{ route('admin.orders.index') }}">Toutes les commandes</a></li>
                                <li><a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">En attente</a></li>
                                <li><a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'processing']) }}">En traitement</a></li>
                                <li><a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'completed']) }}">Complétées</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        {{-- Devis --}}
                        @canany(['voir devis', 'gérer devis'])
                        <li class="{{ request()->is('admin/quotes*') ? 'active' : '' }}">
                            <a href="{{ route('admin.quotes.index') }}" class="nav-link">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Devis</span>
                            </a>
                        </li>
                        @endcanany

                        {{-- Événements culturels --}}
                        @canany(['voir événements', 'gérer événements'])
                        <li class="menu-header">CULTURE</li>
                        <li class="dropdown {{ request()->is('admin/events*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Événements</span>
                            </a>
                            <ul class="dropdown-menu">
                                @can('voir événements')
                                <li><a class="nav-link" href="{{ route('admin.events.index') }}">Tous les événements</a></li>
                                @endcan
                                @can('créer événements')
                                <li><a class="nav-link" href="{{ route('admin.events.create') }}">Créer un événement</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        {{-- Avis --}}
                        @canany(['voir avis', 'gérer avis'])
                        <li class="menu-header">CONTENU</li>
                        <li class="{{ request()->is('admin/reviews*') ? 'active' : '' }}">
                            <a href="{{ route('admin.reviews.index') }}" class="nav-link">
                                <i class="fas fa-star"></i>
                                <span>Avis & évaluations</span>
                            </a>
                        </li>
                        @endcanany

                        {{-- Messages --}}
                        @canany(['voir messages', 'gérer messages'])
                        <li class="{{ request()->is('admin/contacts*') ? 'active' : '' }}">
                            <a href="{{ route('admin.contacts.index') }}" class="nav-link">
                                <i class="fas fa-envelope"></i>
                                <span>Messages</span>
                                @php
                                    $unreadMessages = \App\Models\Contact::where('read', false)->count();
                                @endphp
                                @if($unreadMessages > 0)
                                    <span class="badge badge-danger">{{ $unreadMessages }}</span>
                                @endif
                            </a>
                        </li>
                        @endcanany

                        {{-- Analytics --}}
                        @can('voir analytics')
                        <li class="menu-header">ANALYTICS</li>
                        <li class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                            <a href="{{ route('admin.analytics') }}" class="nav-link">
                                <i class="fas fa-chart-bar"></i>
                                <span>Rapports & statistiques</span>
                            </a>
                        </li>
                        @endcan

                        {{-- Paramètres --}}
                        @canany(['gérer paramètres généraux', 'gérer rôles et permissions'])
                        <li class="menu-header">PARAMÈTRES</li>
                        <li class="dropdown {{ request()->is('admin/settings*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown">
                                <i class="fas fa-cog"></i>
                                <span>Configuration</span>
                            </a>
                            <ul class="dropdown-menu">
                                @can('gérer paramètres généraux')
                                <li><a class="nav-link" href="{{ route('admin.settings.general') }}">Général</a></li>
                                <li><a class="nav-link" href="{{ route('admin.settings.payment') }}">Paiements</a></li>
                                <li><a class="nav-link" href="{{ route('admin.settings.notifications') }}">Notifications</a></li>
                                @endcan
                                @can('gérer rôles et permissions')
                                <li><a class="nav-link" href="{{ route('admin.roles.index') }}">Rôles & permissions</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany
                        @endcanany {{-- Fin bloc admin --}}

                        {{-- ========== ESPACE ARTISAN ========== --}}
                        @role('artisan')
                        <li class="menu-header">ESPACE ARTISAN</li>
                        <li class="{{ request()->routeIs('dashboard.artisan') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.artisan') }}" class="nav-link">
                                <i data-feather="monitor"></i>
                                <span>Tableau de bord</span>
                            </a>
                        </li>

                        <li class="menu-header">MES PRODUITS</li>
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

                        <li class="menu-header">VENTES</li>
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

                        <li class="menu-header">COMMUNICATION</li>
                        <li class="{{ request()->routeIs('dashboard.messages') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.messages') }}" class="nav-link">
                                <i data-feather="message-square"></i>
                                <span>Messages</span>
                                @php($unreadMsg = auth()->user()->unreadMessages?->count() ?? 0)
                                @if($unreadMsg > 0)
                                    <span class="badge badge-primary">{{ $unreadMsg }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('dashboard.artisan.reviews') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.artisan.reviews') }}" class="nav-link">
                                <i data-feather="star"></i>
                                <span>Avis clients</span>
                            </a>
                        </li>

                        <li class="menu-header">STATISTIQUES</li>
                        <li class="{{ request()->routeIs('dashboard.artisan.analytics') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.artisan.analytics') }}" class="nav-link">
                                <i data-feather="bar-chart-2"></i>
                                <span>Analytiques</span>
                            </a>
                        </li>

                        <li class="menu-header">MON COMPTE</li>
                        @if(auth()->user()->artisan)
                        <li class="{{ request()->routeIs('artisan.profile.edit') ? 'active' : '' }}">
                            <a href="{{ route('artisan.profile.edit', auth()->user()->artisan->id) }}" class="nav-link">
                                <i data-feather="user"></i>
                                <span>Mon profil</span>
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
                        @endrole

                        {{-- ========== ESPACE VENDEUR ========== --}}
                        @role('vendor')
                        <li class="menu-header">ESPACE VENDEUR</li>
                        <li class="{{ request()->routeIs('dashboard.vendor') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.vendor') }}" class="nav-link">
                                <i data-feather="monitor"></i>
                                <span>Tableau de bord</span>
                            </a>
                        </li>

                        <li class="menu-header">MES PLATS</li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown">
                                <i data-feather="coffee"></i>
                                <span>Plats</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="{{ route('vendor.dishes.index') }}">Mes plats</a></li>
                                <li><a class="nav-link" href="{{ route('vendor.dishes.create') }}">Ajouter un plat</a></li>
                            </ul>
                        </li>

                        <li class="menu-header">VENTES</li>
                        <li class="{{ request()->routeIs('dashboard.orders') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.orders') }}" class="nav-link">
                                <i data-feather="shopping-cart"></i>
                                <span>Commandes</span>
                            </a>
                        </li>

                        <li class="menu-header">COMMUNICATION</li>
                        <li class="{{ request()->routeIs('dashboard.messages') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.messages') }}" class="nav-link">
                                <i data-feather="message-square"></i>
                                <span>Messages</span>
                            </a>
                        </li>

                        <li class="menu-header">MON COMPTE</li>
                        @if(auth()->user()->vendor)
                        <li class="{{ request()->routeIs('vendor.profile.edit') ? 'active' : '' }}">
                            <a href="{{ route('vendor.profile.edit', auth()->user()->vendor->id) }}" class="nav-link">
                                <i data-feather="user"></i>
                                <span>Mon profil</span>
                            </a>
                        </li>
                        @endif
                        <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <a href="{{ route('profile.edit') }}" class="nav-link">
                                <i data-feather="settings"></i>
                                <span>Paramètres</span>
                            </a>
                        </li>
                        @endrole

                        {{-- ========== ESPACE CLIENT ========== --}}
                        @role('client')
                        <li class="menu-header">MON ESPACE</li>
                        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class="nav-link">
                                <i data-feather="home"></i>
                                <span>Accueil</span>
                            </a>
                        </li>

                        <li class="menu-header">COMMANDES</li>
                        <li class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
                            <a href="{{ route('orders.index') }}" class="nav-link">
                                <i data-feather="shopping-bag"></i>
                                <span>Mes commandes</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('orders.tracking') ? 'active' : '' }}">
                            <a href="{{ route('orders.tracking') }}" class="nav-link">
                                <i data-feather="map-pin"></i>
                                <span>Suivi de livraison</span>
                            </a>
                        </li>

                        <li class="menu-header">FAVORIS</li>
                        <li class="{{ request()->routeIs('favorites') ? 'active' : '' }}">
                            <a href="{{ route('favorites') }}" class="nav-link">
                                <i data-feather="heart"></i>
                                <span>Mes favoris</span>
                                @php($favCount = auth()->user()->favorites()->count())
                                @if($favCount > 0)
                                    <span class="badge badge-danger">{{ $favCount }}</span>
                                @endif
                            </a>
                        </li>

                        <li class="menu-header">MON COMPTE</li>
                        <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <a href="{{ route('profile.edit') }}" class="nav-link">
                                <i data-feather="settings"></i>
                                <span>Paramètres</span>
                            </a>
                        </li>
                        @endrole

                        {{-- LIENS COMMUNS --}}
                        <li class="menu-header">NAVIGATION</li>
                        <li>
                            <a href="{{ route('home') }}" class="nav-link" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Voir le site public</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" class="nav-link"
                               onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Déconnexion</span>
                            </a>
                            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </aside>
            </div>

            {{-- ========== MAIN CONTENT ========== --}}
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        @yield('content')
                    </div>
                </section>
            </div>

            {{-- ========== PANEL DE CONFIGURATION (optionnel) ========== --}}
            <div class="settingSidebar">
                <a href="javascript:void(0)" class="settingPanelToggle">
                    <i class="fa fa-spin fa-cog"></i>
                </a>
                <div class="settingSidebar-body ps-container ps-theme-default">
                    <div class="fade show active">
                        <div class="setting-panel-header">Panneau de configuration</div>
                        <div class="p-15 border-bottom">
                            <h6 class="font-medium m-b-10">Thème</h6>
                            <div class="selectgroup layout-color w-50">
                                <label class="selectgroup-item">
                                    <input type="radio" name="theme" value="light" class="selectgroup-input-radio select-layout" {{ session('theme','light') == 'light' ? 'checked' : '' }}>
                                    <span class="selectgroup-button">Clair</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="theme" value="dark" class="selectgroup-input-radio select-layout" {{ session('theme','light') == 'dark' ? 'checked' : '' }}>
                                    <span class="selectgroup-button">Sombre</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4 mb-4 p-3 align-center">
                            <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                                <i class="fas fa-undo"></i> Restaurer les paramètres
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== FOOTER ========== --}}
            <footer class="main-footer">
                <div class="footer-left">
                    &copy; {{ date('Y') }} <a href="https://safen.bj" target="_blank">SAFEN Bénin</a> – Tous droits réservés.
                </div>
                <div class="footer-right">
                    v1.0.0
                </div>
            </footer>
        </div>
    </div>

    {{-- ========== SCRIPTS ========== --}}
    <script src="{{ asset('admin-assets/js/app.min.js') }}"></script>
    @if(request()->routeIs('admin.dashboard') || request()->routeIs('dashboard*'))
        <script src="{{ asset('admin-assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('admin-assets/js/page/index.js') }}"></script>
    @endif
    <script src="{{ asset('admin-assets/js/scripts.js') }}"></script>
    <script src="{{ asset('admin-assets/js/custom.js') }}"></script>
    <script src="{{ asset('admin-assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    @include('sweetalert::alert')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialisation Select2
        $(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Sélectionner une option',
                allowClear: true,
                width: '100%'
            });
        });

        // Gestionnaire de clic sur les notifications
        function handleNotificationClick(event, element) {
            event.preventDefault();
            const notificationId = element.dataset.notificationId;
            const url = element.getAttribute('href');
            if (!url || url === '#') return;

            if (!element.classList.contains('dropdown-item-unread')) {
                window.location.href = url;
                return;
            }

            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(() => {
                element.classList.remove('dropdown-item-unread');
                const badge = document.getElementById('unread-count');
                if (badge) {
                    let count = parseInt(badge.textContent.replace(/[^0-9]/g, '')) || 0;
                    count = Math.max(0, count - 1);
                    badge.textContent = count > 99 ? '99+' : count;
                    if (count === 0) badge.style.display = 'none';
                }
                window.location.href = url;
            })
            .catch(() => window.location.href = url);
        }

        // Gestion du thème (clair/sombre)
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.body.classList.add('dark-mode');
                document.querySelector('.main-sidebar')?.classList.add('sidebar-dark');
                document.querySelector('.navbar-bg')?.classList.add('bg-dark', 'navbar-dark');
            }
            document.querySelectorAll('.select-layout').forEach(radio => {
                radio.addEventListener('change', function() {
                    const newTheme = this.value;
                    if (newTheme === 'dark') {
                        document.body.classList.add('dark-mode');
                        document.querySelector('.main-sidebar')?.classList.add('sidebar-dark');
                        document.querySelector('.navbar-bg')?.classList.add('bg-dark', 'navbar-dark');
                    } else {
                        document.body.classList.remove('dark-mode');
                        document.querySelector('.main-sidebar')?.classList.remove('sidebar-dark');
                        document.querySelector('.navbar-bg')?.classList.remove('bg-dark', 'navbar-dark');
                    }
                    localStorage.setItem('theme', newTheme);
                });
            });
        })();

        // Gestion du panneau de paramètres
        document.addEventListener('DOMContentLoaded', function() {
            const settingBtn = document.querySelector('.settingPanelToggle');
            const settingPanel = document.querySelector('.settingSidebar');
            if (settingBtn && settingPanel) {
                settingBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    settingPanel.classList.toggle('show');
                });
                document.addEventListener('click', function(event) {
                    if (settingPanel.classList.contains('show') &&
                        !settingPanel.contains(event.target) &&
                        !settingBtn.contains(event.target)) {
                        settingPanel.classList.remove('show');
                    }
                });
            }
        });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
