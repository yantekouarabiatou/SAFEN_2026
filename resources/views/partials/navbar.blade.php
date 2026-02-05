<nav class="navbar navbar-expand-lg navbar-afri sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <div class="d-flex align-items-center justify-content-center me-2"
                 style="width: 45px; height: 45px; background-color: var(--benin-green); border-radius: 50%;">
                <i class="bi bi-flower1 text-white fs-4"></i>
            </div>
            <div>
                <span class="fw-bold text-benin-green fs-5">TOTCHEMEGNON</span>
                <span class="d-block text-muted" style="font-size: 0.7rem; margin-top: -5px;">Bénin</span>
            </div>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i> {{ __('messages.home') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('artisans.*') ? 'active' : '' }}" href="{{ route('artisans.vue') }}">
                        <i class="bi bi-tools me-1"></i> {{ __('artisans & services') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('gastronomie.*') ? 'active' : '' }}" href="{{ route('gastronomie.index') }}">
                        <i class="bi bi-egg-fried me-1"></i> Gastronomie
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-palette me-1"></i> Arts & Artisanat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('culture.*') ? 'active' : '' }}" href="{{ route('culture.index') }}">
                        <i class="bi bi-book me-1"></i> Culture
                    </a>
                </li>
            </ul>

            <!-- Right Side -->
            <div class="d-flex align-items-center gap-3">
                <!-- Language Selector -->
                <div class="dropdown lang-selector">
                    <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-label="{{ __('messages.language') }}">
                        <span class="me-1">
                            @if(app()->getLocale() === 'fr')
                                🇫🇷
                            @elseif(app()->getLocale() === 'en')
                                🇬🇧
                            @else
                                🇧🇯
                            @endif
                        </span> {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() === 'fr' ? 'active' : '' }}"
                               href="{{ route('lang.switch', 'fr') }}">
                                <span class="me-2">🇫🇷</span> {{ __('messages.french') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                               href="{{ route('lang.switch', 'en') }}">
                                <span class="me-2">🇬🇧</span> {{ __('messages.english') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() === 'fon' ? 'active' : '' }}"
                               href="{{ route('lang.switch', 'fon') }}">
                                <span class="me-2">🇧🇯</span> {{ __('fon') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Auth Buttons -->
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-benin-green dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            @if(Auth::user()->hasRole('admin'))
                                <span class="badge bg-benin-red ms-1">Admin</span>
                            @elseif(Auth::user()->hasRole('artisan'))
                                <span class="badge bg-benin-green ms-1">Artisan</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.logout') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-benin-green btn-sm">
                        {{ __('messages.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-benin-green btn-sm">
                        {{ __('messages.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
