<!-- Navbar principale - Version dynamique et pro -->
<nav class="navbar navbar-expand-lg main-navbar sticky-top shadow-sm">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <!-- Bouton sidebar (mobile) -->
            <li>
                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn">
                    <i data-feather="align-justify"></i>
                </a>
            </li>

            <!-- Fullscreen -->
            <li>
                <a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a>
            </li>

            <!-- Recherche -->
            <li class="d-none d-md-block">
                <form class="form-inline">
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Rechercher..." aria-label="Search" data-width="250">
                        <button class="btn" type="submit">
                            <i data-feather="search"></i>
                        </button>
                    </div>
                </form>
            </li>
        </ul>
    </div>

    <ul class="navbar-nav navbar-right">
        <!-- Messages -->
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle position-relative">
                <i data-feather="mail"></i>
                <!-- Badge compteur dynamique (exemple) -->
                @if(auth()->user()->unreadMessages()->count() > 0)
                    <span class="badge badge-pill badge-danger badge-sm position-absolute" style="top:5px;right:5px;">
                        {{ auth()->user()->unreadMessages()->count() }}
                    </span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">
                    Messages
                    <div class="float-right">
                        <a href="#">Voir tout</a>
                    </div>
                </div>

                <div class="dropdown-list-content dropdown-list-message">
                    @if(auth()->user()->recentMessages()->count() > 0)
                        @foreach(auth()->user()->recentMessages()->take(5)->get() as $message)
                            <a href="#" class="dropdown-item dropdown-item-unread">
                                <div class="dropdown-item-avatar">
                                    <img alt="image" src="{{ $message->sender->getAvatarUrlAttribute() }}" class="rounded-circle">
                                </div>
                                <div class="dropdown-item-desc">
                                    <b>{{ $message->sender->fullName }}</b>
                                    <p>{{ Str::limit($message->content, 60) }}</p>
                                    <small>{{ $message->created_at->diffForHumans() }}</small>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Aucun nouveau message</p>
                        </div>
                    @endif
                </div>

                <div class="dropdown-footer text-center">
                    <a href="#">Voir tous les messages <i data-feather="chevron-right"></i></a>
                </div>
            </div>
        </li>

        <!-- Notifications -->
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg position-relative">
                <i data-feather="bell"></i>
                <!-- Badge compteur (exemple) -->
                @if(auth()->user()->unreadNotifications()->count() > 0)
                    <span class="badge badge-pill badge-danger badge-sm position-absolute" style="top:5px;right:5px;">
                        {{ auth()->user()->unreadNotifications()->count() }}
                    </span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">
                    Notifications
                    <div class="float-right">
                        <a href="#">Marquer comme lu</a>
                    </div>
                </div>

                <div class="dropdown-list-content dropdown-list-icons">
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        @foreach(auth()->user()->unreadNotifications()->take(5)->get() as $notification)
                            <a href="#" class="dropdown-item dropdown-item-unread">
                                <div class="dropdown-item-icon bg-primary text-white">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="dropdown-item-desc">
                                    {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                                    <div class="time text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Aucune notification</p>
                        </div>
                    @endif
                </div>

                <div class="dropdown-footer text-center">
                    <a href="#">Voir toutes les notifications <i data-feather="chevron-right"></i></a>
                </div>
            </div>
        </li>

        <!-- Menu utilisateur -->
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                @if(Auth::user()->profile_photo_url)
                    <img alt="Profil" src="{{ Auth::user()->profile_photo_url }}" class="rounded-circle user-img">
                @else
                    <!-- Initiales stylées -->
                    <div class="avatar-initial rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center"
                         style="width:40px; height:40px; font-weight:bold; font-size:1.1rem;">
                        {{ strtoupper(substr(Auth::user()->prenom ?? 'U', 0, 1) . substr(Auth::user()->nom ?? '', 0, 1)) }}
                    </div>
                @endif
                <div class="d-sm-none d-lg-inline-block ml-2">
                    {{ Auth::user()->prenom ?? '' }} {{ Auth::user()->nom ?? 'Utilisateur' }}
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title text-center border-bottom pb-3 mb-3">
                    {{ Auth::user()->fullName ?? 'Bienvenue' }}
                    <small class="d-block text-muted mt-1">{{ Auth::user()->email }}</small>
                    <small class="d-block text-muted mt-1">{{ Auth::user()->role }}</small>
                </div>

                <a href="#" class="dropdown-item has-icon">
                    <i data-feather="user"></i> Mon profil
                </a>
                <a href="#" class="dropdown-item has-icon">
                    <i data-feather="settings"></i> Paramètres
                </a>
                <div class="dropdown-divider"></div>

                <a href="{{ url('/') }}" class="dropdown-item has-icon" target="_blank">
                    <i data-feather="external-link"></i> Voir le site public
                </a>

                <form method="POST" action="{{ route('logout') }}" class="dropdown-item">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger p-0 w-100 text-left has-icon">
                        <i data-feather="log-out"></i> Déconnexion
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>