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
        {{-- Sélecteur de langue dynamique --}}
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg">
                <i data-feather="globe"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('lang.switch', 'fr') }}" class="dropdown-item {{ app()->getLocale() === 'fr' ? 'active' : '' }}">
                    🇫🇷 Français
                </a>
                <a href="{{ route('lang.switch', 'en') }}" class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                    🇬🇧 English
                </a>
                <a href="{{ route('lang.switch', 'fon') }}" class="dropdown-item {{ app()->getLocale() === 'fon' ? 'active' : '' }}">
                    🇧🇯 Fon
                </a>
            </div>
        </li>
        
        {{-- Messages dynamiques --}}
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
                <i data-feather="mail"></i>
                @php
                    $unreadMessagesCount = auth()->user()->unreadMessages()->count();
                @endphp
                @if($unreadMessagesCount > 0)
                    <span class="badge badge-danger">{{ $unreadMessagesCount }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                <div class="dropdown-header">
                    {{ __('messages.messages') }}
                    <div class="float-right">
                        <a href="{{ route('dashboard.messages') }}">{{ __('messages.see_all') }}</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-message">
                    @if($unreadMessagesCount > 0)
                        @foreach(auth()->user()->unreadMessages()->latest()->take(5)->get() as $message)
                            <a href="{{ route('dashboard.messages') }}" class="dropdown-item dropdown-item-unread">
                                <span class="dropdown-item-avatar avatar-sm">
                                    <img alt="image" src="{{ $message->sender->profile_photo_url ?? asset('admin-assets/img/users/user-1.png') }}" class="rounded-circle">
                                </span>
                                <span class="dropdown-item-description">
                                    <span class="dropdown-item-title">{{ $message->sender->name }}</span>
                                    <span class="text-sm text-muted">{{ Str::limit($message->content, 50) }}</span>
                                </span>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <p class="text-muted mb-0">{{ __('messages.no_new_messages') }}</p>
                        </div>
                    @endif
                </div>
                <div class="dropdown-footer text-center">
                    <a href="{{ route('dashboard.messages') }}">{{ __('messages.see_all_messages') }} <i class="fas fa-chevron-right"></i></a>
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
                <div class="dropdown-title">
                    {{ Auth::user()->name ?? 'Utilisateur' }}
                    @if(Auth::user()->hasRole('artisan'))
                        <span class="badge badge-primary">Artisan</span>
                    @elseif(Auth::user()->hasRole('vendor'))
                        <span class="badge badge-success">Vendeur</span>
                    @elseif(Auth::user()->hasRole('admin'))
                        <span class="badge badge-danger">Admin</span>
                    @endif
                </div>
                <a href="{{ route('profile.edit') }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> {{ __('messages.profile') }}
                </a>
                
                @if(Auth::user()->hasRole('artisan') && Auth::user()->artisan)
                    <a href="{{ route('artisan.profile.edit', Auth::user()->artisan->id) }}" class="dropdown-item has-icon">
                        <i class="fas fa-hammer"></i> {{ __('messages.artisan_profile') }}
                    </a>
                @endif
                
                <a href="{{ route('dashboard.settings') }}" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> {{ __('messages.settings') }}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('/') }}" class="dropdown-item has-icon" target="_blank">
                    <i class="fas fa-external-link-alt"></i> {{ __('messages.view_site') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> {{ __('messages.logout') }}
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>

