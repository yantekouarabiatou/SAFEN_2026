<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard.vendor') }}">
                <span class="logo-name">TOTCHEMEGNON</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            {{-- Dashboard --}}
            <li class="menu-header">Tableau de bord</li>
            <li class="{{ request()->routeIs('dashboard.vendor') ? 'active' : '' }}">
                <a href="{{ route('dashboard.vendor') }}" class="nav-link">
                    <i data-feather="monitor"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            {{-- Gestion des plats --}}
            <li class="menu-header">Mes Plats</li>
            
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="coffee"></i>
                    <span>Plats</span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="#">Mes plats</a>
                    </li>
                    <li>
                        <a class="nav-link" href="#">Ajouter un plat</a>
                    </li>
                </ul>
            </li>
            
            {{-- Commandes --}}
            <li class="menu-header">Ventes</li>
            
            <li>
                <a href="{{ route('dashboard.orders') }}" class="nav-link">
                    <i data-feather="shopping-cart"></i>
                    <span>Commandes</span>
                </a>
            </li>
            
            {{-- Communication --}}
            <li class="menu-header">Communication</li>
            
            <li class="{{ request()->routeIs('dashboard.messages') ? 'active' : '' }}">
                <a href="{{ route('dashboard.messages') }}" class="nav-link">
                    <i data-feather="message-square"></i>
                    <span>Messages</span>
                </a>
            </li>
            
            {{-- Profil --}}
            <li class="menu-header">Mon Compte</li>
            
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
