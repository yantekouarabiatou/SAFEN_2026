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
                <form class="form-inline mr-auto">
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Rechercher..." aria-label="Search" data-width="200">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </li>
        </ul>
    </div>
    
    <ul class="navbar-nav navbar-right">
        {{-- Messages --}}
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
                <i data-feather="mail"></i>
                <span class="badge headerBadge1">{{ App\Models\Contact::where('status', 'new')->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                <div class="dropdown-header">
                    Messages
                    <div class="float-right">
                        <a href="{{ route('admin.contacts.index') }}">Voir tout</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-message">
                    @foreach(App\Models\Contact::where('status', 'new')->latest()->take(5)->get() as $contact)
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="dropdown-item">
                            <span class="dropdown-item-avatar text-white">
                                <img alt="image" src="{{ asset('admin-assets/img/users/user-1.png') }}" class="rounded-circle">
                            </span>
                            <span class="dropdown-item-desc">
                                <span class="message-user">{{ $contact->name }}</span>
                                <span class="time messege-text">{{ Str::limit($contact->subject, 30) }}</span>
                                <span class="time">{{ $contact->created_at->diffForHumans() }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
                <div class="dropdown-footer text-center">
                    <a href="{{ route('admin.contacts.index') }}">Voir tous les messages <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>
        
        {{-- Notifications --}}
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
                <i data-feather="bell" class="bell"></i>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                <div class="dropdown-header">
                    Notifications
                    <div class="float-right">
                        <a href="#">Marquer comme lu</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-icons">
                    <a href="#" class="dropdown-item dropdown-item-unread">
                        <span class="dropdown-item-icon bg-primary text-white">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                        <span class="dropdown-item-desc">
                            Nouvelle commande reçue!
                            <span class="time">Il y a 2 min</span>
                        </span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <span class="dropdown-item-icon bg-success text-white">
                            <i class="fas fa-user-plus"></i>
                        </span>
                        <span class="dropdown-item-desc">
                            Nouvel utilisateur inscrit
                            <span class="time">Il y a 1 heure</span>
                        </span>
                    </a>
                </div>
                <div class="dropdown-footer text-center">
                    <a href="#">Voir toutes les notifications <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>
        
        {{-- User Menu --}}
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ Auth::user()->profile_photo_url ?? asset('admin-assets/img/users/user-1.png') }}" class="user-img-radious-style">
                <span class="d-sm-none d-lg-inline-block"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title">{{ Auth::user()->name ?? 'Admin' }}</div>
                <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profil
                </a>
                <a href="{{ route('admin.settings') }}" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Paramètres
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('/') }}" class="dropdown-item has-icon" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Voir le site
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
