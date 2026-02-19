<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('client.dashboard') }}">
                <div class="d-flex align-items-center justify-content-center" style="width:45px;height:45px;background:#198754;border-radius:50%;margin-right:10px;">
                    <i class="bi bi-flower1 text-white fs-4"></i>
                </div>
                <span class="logo-name" style="font-weight:bold;font-size:1.2rem;">TOTCHEMEGNON</span>
                <small class="d-block text-muted">Espace Client</small>
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">TABLEAU DE BORD</li>
            <li class="{{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                <a href="{{ route('client.dashboard') }}" class="nav-link">
                    <i class="fas fa-home"></i><span>Accueil</span>
                </a>
            </li>

            <li class="menu-header">COMMANDES</li>
            <li class="{{ request()->routeIs('client.orders.*') ? 'active' : '' }}">
                <a href="{{ route('client.orders.index') }}" class="nav-link">
                    <i class="fas fa-shopping-bag"></i><span>Mes commandes</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('client.orders.tracking') ? 'active' : '' }}">
                <a href="{{ route('client.orders.tracking') }}" class="nav-link">
                    <i class="fas fa-map-marker-alt"></i><span>Suivi de livraison</span>
                </a>
            </li>

            <li class="menu-header">DEVIS</li>
            <li class="{{ request()->routeIs('client.quotes.*') ? 'active' : '' }}">
                <a href="{{ route('client.quotes.index') }}" class="nav-link">
                    <i class="fas fa-file-signature"></i><span>Mes devis</span>
                </a>
            </li>
            <li>
                <a href="{{ route('client.quotes.create') }}" class="nav-link">
                    <i class="fas fa-plus-circle"></i><span>Demander un devis</span>
                </a>
            </li>

            <li class="menu-header">FAVORIS</li>
            <li class="{{ request()->routeIs('client.favorites.*') ? 'active' : '' }}">
                <a href="{{ route('client.favorites.index') }}" class="nav-link">
                    <i class="fas fa-heart"></i><span>Mes favoris</span>
                    @php($favCount = auth()->user()->favorites()->count())
                    @if($favCount > 0)
                        <span class="badge badge-danger">{{ $favCount }}</span>
                    @endif
                </a>
            </li>

            <li class="menu-header">MESSAGES</li>
            <li class="{{ request()->routeIs('client.messages.*') ? 'active' : '' }}">
                <a href="{{ route('client.messages.index') }}" class="nav-link">
                    <i class="fas fa-envelope"></i><span>Messagerie</span>
                    @php($unread = auth()->user()->unreadMessages()->count())
                    @if($unread > 0)
                        <span class="badge badge-warning">{{ $unread }}</span>
                    @endif
                </a>
            </li>

            <li class="menu-header">CONTACT</li>
            <li class="{{ request()->routeIs('client.contacts.create') ? 'active' : '' }}">
                <a href="{{ route('client.contacts.create') }}" class="nav-link">
                    <i class="fas fa-headset"></i><span>Contacter un artisan</span>
                </a>
            </li>

            <li class="menu-header">MON COMPTE</li>
            <li class="{{ request()->routeIs('client.profile.edit') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i><span>Paramètres</span>
                </a>
            </li>

            <li class="menu-header">NAVIGATION</li>
            <li>
                <a href="{{ route('products.index') }}" class="nav-link">
                    <i class="fas fa-shopping-cart"></i><span>Continuer mes achats</span>
                </a>
            </li>
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
