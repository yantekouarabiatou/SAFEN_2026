<div class="main-sidebar sidebar-style-2">
	<aside id="sidebar-wrapper">
		<div class="sidebar-brand">
			<a href="{{ route('admin.dashboard') }}">
				<span class="logo-name">TOTCHEMEGNON</span>
			</a>
		</div>
		<ul class="sidebar-menu">
			{{-- Dashboard --}}
			<li class="menu-header">Tableau de bord</li>
			<li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
				<a href="{{ route('admin.dashboard') }}" class="nav-link">
					<i data-feather="monitor"></i>
					<span>Dashboard</span>
				</a>
			</li>

			{{-- Gestion des contenus --}}
			<li class="menu-header">Gestion du contenu</li>

			{{-- Artisans --}}
			<li class="dropdown {{ request()->routeIs('admin.artisans.*') ? 'active' : '' }}">
				<a href="#" class="menu-toggle nav-link has-dropdown">
					<i data-feather="users"></i>
					<span>Artisans</span>
				</a>
				<ul class="dropdown-menu">
					<li class="{{ request()->routeIs('admin.artisans.index') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('admin.artisans.index') }}">Liste des artisans</a>
					</li>
					<li class="{{ request()->routeIs('admin.artisans.create') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('admin.artisans.create') }}">Ajouter un artisan</a>
					</li>
				</ul>
			</li>

			{{-- Produits --}}
			<li class="dropdown {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
				<a href="#" class="menu-toggle nav-link has-dropdown">
					<i data-feather="shopping-bag"></i>
					<span>Produits</span>
				</a>
				<ul class="dropdown-menu">
					<li class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('admin.products.index') }}">Liste des produits</a>
					</li>
					<li class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('admin.products.create') }}">Ajouter un produit</a>
					</li>
				</ul>
			</li>

			{{-- Plats / Gastronomie --}}
			<li class="dropdown {{ request()->routeIs('admin.dishes.*') ? 'active' : '' }}">
				<a href="#" class="menu-toggle nav-link has-dropdown">
					<i data-feather="coffee"></i>
					<span>Gastronomie</span>
				</a>
				<ul class="dropdown-menu">
					<li class="{{ request()->routeIs('admin.dishes.index') ? 'active' : '' }}">
						<a class="nav-link" href="#">Liste des plats</a>
					</li>
					<li class="{{ request()->routeIs('admin.dishes.create') ? 'active' : '' }}">
						<a class="nav-link" href="#">Ajouter un plat</a>
					</li>
				</ul>
			</li>

			{{-- Vendeurs --}}
			<li class="dropdown {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
				<a href="#" class="menu-toggle nav-link has-dropdown">
					<i data-feather="home"></i>
					<span>Vendeurs</span>
				</a>
				<ul class="dropdown-menu">
					<li class="{{ request()->routeIs('admin.vendors.index') ? 'active' : '' }}">
						<a class="nav-link" href="#">Liste des vendeurs</a>
					</li>
					<li class="{{ request()->routeIs('admin.vendors.create') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('admin.vendors.create') }}">Ajouter un vendeur</a>
					</li>
				</ul>
			</li>

			{{-- Commandes & Transactions --}}
			<li class="menu-header">Commandes & Transactions</li>

			<li class="dropdown {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
				<a href="#" class="menu-toggle nav-link has-dropdown">
					<i data-feather="shopping-cart"></i>
					<span>Commandes</span>
				</a>
				<ul class="dropdown-menu">
					<li class="{{ request()->routeIs('admin.orders.index') && !request()->has('status') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('admin.orders.index') }}">
							Toutes les commandes
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.orders.index') && request('status') == 'pending' ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">
							En attente
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.orders.index') && request('status') == 'processing' ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'processing']) }}">
							En traitement
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.orders.index') && request('status') == 'completed' ? 'active' : '' }}">
						<a class="nav-link bg-dark" href="{{ route('admin.orders.index', ['status' => 'completed']) }}">
							Complétées
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.orders.index') && request('status') == 'cancelled' ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}">
							Annulées
						</a>
					</li>
				</ul>
			</li>

			<li class="{{ request()->routeIs('admin.quotes.*') ? 'active' : '' }}">
				<a href="{{ route('admin.quotes.index') }}" class="nav-link">
					<i data-feather="file-text"></i>
					<span>Devis</span>
				</a>
			</li>

			{{-- Utilisateurs --}}
			<li class="menu-header">Utilisateurs</li>

			<li class="dropdown {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
				<a href="#" class="menu-toggle nav-link has-dropdown">
					<i data-feather="user"></i>
					<span>Utilisateurs</span>
				</a>
				<ul class="dropdown-menu">
					<li class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('admin.users.index') }}">Tous les utilisateurs</a>
					</li>
					<li class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('admin.users.create') }}">Ajouter un utilisateur</a>
					</li>
				</ul>
			</li>

			<li class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
				<a href="{{ route('admin.reviews.index') }}" class="nav-link">
					<i data-feather="star"></i>
					<span>Avis</span>
				</a>
			</li>

			{{-- Communication --}}
			<li class="menu-header">Communication</li>

			<li class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
				<a href="{{ route('admin.contacts.index') }}" class="nav-link">
					<i data-feather="mail"></i>
					<span>Messages</span>
					@php $unreadContacts = App\Models\Contact::where('status', 'new')->count(); @endphp
					@if($unreadContacts > 0)
                        <span class="badge badge-primary">{{ $unreadContacts }}</span>
                    @endif
				</a>
			</li>

			{{-- Paramètres --}}
			<li class="menu-header">Paramètres</li>

			<li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
				<a href="" class="nav-link">
					<i data-feather="settings"></i>
					<span>Paramètres</span>
				</a>
			</li>

			<li>
				<a href="{{ url('/') }}" class="nav-link" target="_blank">
					<i data-feather="external-link"></i>
					<span>Voir le site</span>
				</a>
			</li>
		</ul>
	</aside>
</div>

