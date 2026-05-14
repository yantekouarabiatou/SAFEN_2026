<nav class="navbar navbar-expand-lg navbar-afri sticky-top">
    <div class="container">

        {{-- ── Logo ──────────────────────────────────────────── --}}
        <a class="navbar-brand d-flex align-items-center gap-2 me-2 me-xl-4" href="{{ route('home') }}">
            {{-- SVG Logo TOTCHÉMÈGNON --}}
            <svg width="36" height="36" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="nlg" x1="0" y1="0" x2="44" y2="44" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#00b86b"/>
                        <stop offset="100%" stop-color="#005c38"/>
                    </linearGradient>
                </defs>
                <circle cx="22" cy="22" r="21" fill="url(#nlg)"/>
                <circle cx="22" cy="22" r="21" fill="none" stroke="#FCD116" stroke-width="1.6"/>
                <rect x="9" y="13" width="26" height="5" rx="2.5" fill="#fff"/>
                <rect x="17.5" y="13" width="9" height="19" rx="2.5" fill="#fff"/>
                <circle cx="5.5" cy="22" r="2" fill="#FCD116"/>
                <circle cx="38.5" cy="22" r="2" fill="#FCD116"/>
                <circle cx="22" cy="5" r="1.7" fill="#E8112D"/>
                <path d="M22 9 L24 12 L22 14.5 L20 12Z" fill="#FCD116" opacity=".75"/>
            </svg>
            <div class="d-none d-xl-block" style="line-height:1.1;">
                <span class="fw-bold text-benin-green d-block" style="font-family:'Montserrat',sans-serif;font-size:1rem;letter-spacing:-.2px;">
                    TOTCHÉMÈGNON
                </span>
                <span class="text-muted d-block" style="font-size:.62rem;letter-spacing:.5px;text-transform:uppercase;margin-top:1px;">
                    Bénin
                </span>
            </div>
        </a>

        {{-- ── Toggle mobile ──────────────────────────────────── --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-4"></i>
        </button>

        {{-- ── Liens de navigation ─────────────────────────────── --}}
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i>{{ __('messages.home') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('artisans.*') ? 'active' : '' }}" href="{{ route('artisans.vue') }}">
                        <i class="bi bi-tools me-1"></i>{{ __('messages.artisans') ?? 'Artisans' }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('gastronomie.*') ? 'active' : '' }}" href="{{ route('gastronomie.index') }}">
                        <i class="bi bi-egg-fried me-1"></i>{{ __('messages.gastronomy') ?? 'Gastronomie' }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-palette me-1"></i>{{ __('messages.marketplace') ?? 'Marketplace' }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('culture.*') ? 'active' : '' }}" href="{{ route('culture.index') }}">
                        <i class="bi bi-book me-1"></i>{{ __('messages.culture') ?? 'Culture' }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}" href="{{ route('map') }}">
                        <i class="bi bi-geo-alt-fill me-1" style="color:#E8112D;"></i>Carte
                    </a>
                </li>
            </ul>

            {{-- ── Droite : panier · langue · compte ──────────── --}}
            <div class="d-flex align-items-center gap-1 flex-shrink-0">

                {{-- Panier (auth) --}}
                @auth
                @php
                    $navCartCount   = \App\Models\Cart::where('user_id', Auth::id())->value('item_count') ?? 0;
                    $navUnreadCount = Auth::user()->unreadNotifications->count();
                @endphp
                <a href="{{ route('cart.index') }}"
                   class="btn btn-outline-benin-green btn-sm position-relative"
                   aria-label="Panier" id="navbar-cart-btn">
                    <i class="bi bi-cart3 fs-6"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-benin-red cart-badge"
                          style="font-size:.6rem;{{ $navCartCount > 0 ? '' : 'display:none;' }}">
                        {{ $navCartCount }}
                    </span>
                </a>

                {{-- Cloche notifications --}}
                <div class="dropdown" id="notif-dropdown">
                    <button class="btn btn-outline-benin-green btn-sm position-relative"
                            type="button" data-bs-toggle="dropdown" aria-label="Notifications"
                            id="notif-bell-btn" data-bs-auto-close="outside">
                        <i class="bi bi-bell fs-6"></i>
                        <span id="notif-bell-badge"
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-benin-red"
                              style="font-size:.6rem;{{ $navUnreadCount > 0 ? '' : 'display:none;' }}">
                            {{ $navUnreadCount > 9 ? '9+' : $navUnreadCount }}
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0"
                         style="min-width:340px;border-radius:14px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,.14);border:1px solid #f3f4f6;">
                        {{-- Header --}}
                        <div style="padding:14px 16px 10px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;">
                            <div style="font-weight:700;font-size:.88rem;color:#1a1d23;">
                                <i class="bi bi-bell me-1" style="color:#008751;"></i>Notifications
                                @if($navUnreadCount > 0)
                                    <span style="background:#008751;color:#fff;border-radius:10px;padding:2px 7px;font-size:.65rem;font-weight:700;margin-left:4px;">
                                        {{ $navUnreadCount }}
                                    </span>
                                @endif
                            </div>
                            <div style="display:flex;gap:6px;">
                                @if($navUnreadCount > 0)
                                <button onclick="notifMarkAllRead()" style="background:none;border:none;font-size:.72rem;color:#008751;font-weight:600;cursor:pointer;padding:2px 6px;">
                                    Tout lire
                                </button>
                                @endif
                                <a href="{{ route('notifications.index') }}" style="font-size:.72rem;color:#6b7280;font-weight:500;text-decoration:none;padding:2px 4px;">
                                    Voir tout
                                </a>
                            </div>
                        </div>
                        {{-- Liste --}}
                        <div id="notif-dropdown-list" style="max-height:380px;overflow-y:auto;">
                            @php $recentNotifs = Auth::user()->notifications()->latest()->take(8)->get(); @endphp
                            @forelse($recentNotifs as $n)
                            @php
                                $nd   = $n->data;
                                $nRead = !is_null($n->read_at);
                            @endphp
                            <div class="notif-drop-item {{ $nRead ? '' : 'notif-drop-unread' }}"
                                 id="ndi-{{ $n->id }}"
                                 style="display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-bottom:1px solid #f9fafb;cursor:pointer;transition:background .15s;{{ $nRead ? '' : 'background:#f0fdf4;' }}"
                                 onclick="notifClick('{{ $n->id }}', '{{ addslashes($nd['url'] ?? '#') }}')">
                                <div style="width:36px;height:36px;border-radius:10px;background:{{ $nd['color'] ?? '#6b7280' }}1a;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi {{ $nd['icon'] ?? 'bi-bell' }}" style="font-size:.88rem;color:{{ $nd['color'] ?? '#6b7280' }};"></i>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-weight:{{ $nRead ? '500' : '700' }};font-size:.8rem;color:#1a1d23;margin-bottom:2px;">
                                        {{ $nd['title'] ?? 'Notification' }}
                                    </div>
                                    <div style="font-size:.74rem;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:230px;">
                                        {{ $nd['message'] ?? '' }}
                                    </div>
                                    <div style="font-size:.67rem;color:#9ca3af;margin-top:3px;">
                                        {{ $n->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                @if(!$nRead)
                                <div style="width:8px;height:8px;background:#008751;border-radius:50%;flex-shrink:0;margin-top:4px;"></div>
                                @endif
                            </div>
                            @empty
                            <div style="text-align:center;padding:32px 16px;color:#9ca3af;">
                                <i class="bi bi-bell-slash" style="font-size:2rem;display:block;margin-bottom:8px;opacity:.3;"></i>
                                <div style="font-size:.82rem;">Aucune notification</div>
                            </div>
                            @endforelse
                        </div>
                        {{-- Footer --}}
                        <div style="padding:10px 16px;border-top:1px solid #f3f4f6;text-align:center;">
                            <a href="{{ route('notifications.index') }}"
                               style="font-size:.78rem;font-weight:600;color:#008751;text-decoration:none;">
                                Toutes les notifications →
                            </a>
                        </div>
                    </div>
                </div>
                @endauth

                {{-- Sélecteur de langue --}}
                <div class="dropdown lang-selector">
                    <button class="btn btn-sm dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-label="{{ __('messages.language') }}">
                        <span class="me-1">
                            @if(app()->getLocale() === 'fr') 🇫🇷
                            @elseif(app()->getLocale() === 'en') 🇬🇧
                            @else 🇧🇯
                            @endif
                        </span>
                        {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() === 'fr' ? 'active' : '' }}"
                               href="{{ route('lang.switch', 'fr') }}">
                                <span class="me-2">🇫🇷</span>{{ __('messages.french') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                               href="{{ route('lang.switch', 'en') }}">
                                <span class="me-2">🇬🇧</span>{{ __('messages.english') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() === 'fon' ? 'active' : '' }}"
                               href="{{ route('lang.switch', 'fon') }}">
                                <span class="me-2">🇧🇯</span>{{ __('messages.fon') }}
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Auth --}}
                @auth
                    @php $authUser = Auth::user(); $isAdminOrArtisan = $authUser->hasRole(['admin','super-admin','artisan','vendor']); @endphp

                    @if($isAdminOrArtisan)
                    {{-- ── Dropdown enrichi pour admin/artisan ── --}}
                    <div class="dropdown">
                        <button class="btn btn-outline-benin-green btn-sm dropdown-toggle d-flex align-items-center gap-2"
                                type="button" data-bs-toggle="dropdown">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white"
                                  style="width:26px;height:26px;font-size:.7rem;font-weight:700;
                                         background:var(--benin-green);flex-shrink:0;">
                                {{ strtoupper(substr($authUser->name, 0, 1)) }}
                            </span>
                            <span class="d-none d-lg-inline">{{ $authUser->name }}</span>
                            @if($authUser->hasRole(['admin','super-admin']))
                                <span class="badge ms-1" style="background:var(--benin-red);font-size:.6rem;padding:2px 6px;">Admin</span>
                            @elseif($authUser->hasRole('artisan'))
                                <span class="badge ms-1" style="background:var(--benin-green);font-size:.6rem;padding:2px 6px;">Artisan</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width:220px;">

                            {{-- En-tête utilisateur --}}
                            <li>
                                <div class="px-3 py-2 border-bottom">
                                    <div class="fw-semibold" style="font-size:.85rem;color:#1a1d23;">{{ $authUser->name }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">{{ $authUser->email }}</div>
                                </div>
                            </li>

                            {{-- Accès Dashboard (bien visible) --}}
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 mt-1"
                                   href="{{ $authUser->hasRole(['admin','super-admin']) ? route('admin.dashboard') : route('dashboard.artisan') }}"
                                   style="color:var(--benin-green);font-weight:600;">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                          style="width:28px;height:28px;background:rgba(0,135,81,.1);flex-shrink:0;">
                                        <i class="bi bi-speedometer2" style="font-size:.85rem;"></i>
                                    </span>
                                    Tableau de bord
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                   href="{{ route('profile.edit') }}">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                          style="width:28px;height:28px;background:#f3f4f6;flex-shrink:0;">
                                        <i class="bi bi-person" style="font-size:.85rem;color:#6b7280;"></i>
                                    </span>
                                    Mon profil
                                </a>
                            </li>

                            <li><hr class="dropdown-divider my-1"></li>

                            {{-- Déconnexion bien visible --}}
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit"
                                            class="dropdown-item d-flex align-items-center gap-2 py-2"
                                            style="color:#E8112D;font-weight:600;">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                              style="width:28px;height:28px;background:rgba(232,17,45,.1);flex-shrink:0;">
                                            <i class="bi bi-box-arrow-right" style="font-size:.85rem;"></i>
                                        </span>
                                        Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    @else
                    {{-- ── Dropdown standard pour les clients ── --}}
                    <div class="dropdown">
                        <button class="btn btn-outline-benin-green dropdown-toggle btn-sm"
                                type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ $authUser->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width:200px;">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2"></i>{{ __('messages.profile') }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('messages.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endif

                @else
                    {{-- Non connecté --}}
                    <a href="{{ route('login') }}" class="btn btn-outline-benin-green btn-sm">
                        {{ __('messages.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-benin-green btn-sm">
                        {{ __('messages.register') }}
                    </a>
                @endauth

            </div>{{-- fin droite --}}
        </div>
    </div>
</nav>
