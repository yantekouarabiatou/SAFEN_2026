<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">
                <span class="logo-name">TOTCHEMEGNON</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            {{-- Dashboard --}}
            <li class="menu-header">Tableau de bord</li>
            <li class="{{ request()->routeIs('dashboard.artisan') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i data-feather="monitor"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Gestion des produits --}}
            <li class="menu-header">Mes Produits</li>

            <li class="dropdown {{ request()->routeIs('dashboard.artisan.products*') || request()->routeIs('products.*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="shopping-bag"></i>
                    <span>Produits</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('dashboard.artisan.products') ? 'active' : '' }}">
                        <a class="nav-link" href="#">Mes produits</a>
                    </li>
                    <li class="{{ request()->routeIs('products.create') ? 'active' : '' }}">
                        <a class="nav-link" href="#">Ajouter un produit</a>
                    </li>
                </ul>
            </li>

            {{-- Commandes --}}
            <li class="menu-header">Ventes</li>

            <li class="{{ request()->routeIs('dashboard.artisan.orders') ? 'active' : '' }}">
                <a hre="#" class="nav-link">
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

            {{-- Communication --}}
            <li class="menu-header">Communication</li>

            <li class="{{ request()->routeIs('dashboard.messages') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i data-feather="message-square"></i>
                    <span>Messages</span>
                    @php
                        $unreadCount = auth()->user()->unreadMessages ?? 0;
                    @endphp
                    @if($unreadCount > 0)
                        <span class="badge badge-primary">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>

            <li class="{{ request()->routeIs('dashboard.artisan.reviews') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i data-feather="star"></i>
                    <span>Avis clients</span>
                </a>
            </li>

            {{-- Analytics --}}
            <li class="menu-header">Statistiques</li>

            <li class="{{ request()->routeIs('dashboard.artisan.analytics') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i data-feather="bar-chart-2"></i>
                    <span>Analytics</span>
                </a>
            </li>

            {{-- Profil --}}
            <li class="menu-header">Mon Compte</li>

            @if(auth()->user()->artisan)
            <li class="{{ request()->routeIs('artisans.edit') ? 'active' : '' }}">
                <a href="{{ route('artisans.edit', auth()->user()->artisan) }}" class="nav-link">
                    <i data-feather="user"></i>
                    <span>Mon profil artisan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('artisans.show', auth()->user()->artisan) }}" class="nav-link" target="_blank">
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

            {{-- Retour au site --}}
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
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>
</div>
