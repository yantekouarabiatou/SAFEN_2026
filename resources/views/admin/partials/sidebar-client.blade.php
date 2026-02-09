<div class="main-sidebar sidebar-style-2">
	<aside id="sidebar-wrapper">
		<div class="sidebar-brand">
			<a href="{{ route('home') }}">
				<span class="logo-name">SAFEN</span>
			</a>
		</div>

		<ul
			class="sidebar-menu">

			<!-- Tableau de bord client -->
			<li class="menu-header">Mon Espace</li>

			<li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
				<a href="{{ route('dashboard') }}" class="nav-link">
					<i data-feather="home"></i>
					<span>Accueil</span>
				</a>
			</li>

			<!-- Mes commandes -->
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

			<!-- Favoris & Liste d'envies -->
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

			<!-- Profil & Paramètres -->
			<li class="menu-header">Mon Compte</li>

			{{-- <li class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                <a href="{{ route('profile') }}" class="nav-link">
                    <i data-feather="user"></i>
                    <span>Mon profil</span>
                </a>
            </li> --}}

			<li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
				<a href="{{ route('profile.edit') }}" class="nav-link">
					<i data-feather="settings"></i>
					<span>Paramètres</span>
				</a>
			</li>

			{{-- <li class="{{ request()->routeIs('profile.addresses') ? 'active' : '' }}">
                <a href="{{ route('profile.addresses') }}" class="nav-link">
                    <i data-feather="map"></i>
                    <span>Mes adresses</span>
                </a>
            </li> --}}

			<!-- Navigation -->
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

			<!-- Déconnexion -->
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

