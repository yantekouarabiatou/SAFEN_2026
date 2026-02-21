{{--
    ============================================================
    NAVBAR PRINCIPALE — admin/partials/navbar.blade.php
    Feather Icons uniquement (noms valides)
    ============================================================
--}}
@php
    $user = auth()->user();
    $initials = strtoupper(
        substr($user->name ?? 'U', 0, 1) .
        (str_contains($user->name ?? '', ' ')
            ? substr(strrchr($user->name, ' '), 1, 1)
            : '')
    );
    $bgColors = ['#008751','#E8112D','#FCD116','#0066cc','#6f42c1','#fd7e14'];
    $bgColor  = $bgColors[crc32($user->email ?? '') % count($bgColors)];

    $unreadMsgCount   = 0;
    $unreadNotifCount = 0;
    try {
        $unreadMsgCount   = method_exists($user, 'unreadMessages')
            ? $user->unreadMessages()->count() : 0;
        $unreadNotifCount = method_exists($user, 'unreadNotifications')
            ? $user->unreadNotifications()->count() : 0;
    } catch (\Exception $e) {}
@endphp

<nav class="navbar navbar-expand-lg main-navbar sticky-top">
    {{-- ── GAUCHE ─────────────────────────────── --}}
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn" title="Menu">
                    <i data-feather="align-justify"></i>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link nav-link-lg fullscreen-btn" title="Plein écran">
                    <i data-feather="maximize"></i>
                </a>
            </li>
        </ul>

        {{-- Barre de recherche --}}
        <div class="d-none d-md-block">
            <form class="form-inline" action="#" method="GET">
                <div class="search-element">
                    <input class="form-control" type="search" name="q"
                           placeholder="Rechercher..." aria-label="Rechercher">
                    <button class="btn" type="submit">
                        <i data-feather="search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── DROITE ─────────────────────────────── --}}
    <ul class="navbar-nav navbar-right">

        {{-- Messages --}}
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown"
               class="nav-link nav-link-lg message-toggle position-relative"
               title="Messages">
                <i data-feather="mail"></i>
                @if($unreadMsgCount > 0)
                    <span class="badge badge-pill badge-danger badge-sm position-absolute"
                          style="top:4px;right:4px;font-size:10px;min-width:18px;">
                        {{ $unreadMsgCount > 99 ? '99+' : $unreadMsgCount }}
                    </span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header d-flex justify-content-between align-items-center">
                    <span><i data-feather="mail" style="width:14px;height:14px;margin-right:6px;"></i>Messages</span>
                    @if($isAdmin ?? false)
                    <a href="{{ route('admin.contacts.index') }}" class="text-primary small">Voir tout</a>
                    @endif
                </div>

                <div class="dropdown-list-content dropdown-list-message">
                    @php
                        try {
                            $recentMsgs = method_exists($user, 'recentMessages')
                                ? $user->recentMessages()->take(5)->get()
                                : collect();
                        } catch (\Exception $e) { $recentMsgs = collect(); }
                    @endphp

                    @forelse($recentMsgs as $message)
                        <a href="#" class="dropdown-item {{ is_null($message->read_at) ? 'dropdown-item-unread' : '' }}">
                            <div class="dropdown-item-avatar">
                                @if($message->sender->avatar ?? false)
                                    <img alt="{{ $message->sender->name }}"
                                         src="{{ asset('storage/'.$message->sender->avatar) }}"
                                         class="rounded-circle" style="width:36px;height:36px;object-fit:cover;">
                                @else
                                    <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                         style="width:36px;height:36px;background:#008751;font-size:13px;">
                                        {{ strtoupper(substr($message->sender->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="dropdown-item-desc">
                                <b>{{ $message->sender->name ?? 'Inconnu' }}</b>
                                <p class="mb-0 text-muted small">{{ Str::limit($message->content ?? '', 55) }}</p>
                                <span class="time text-muted" style="font-size:11px;">
                                    {{ $message->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-4">
                            <i data-feather="inbox" style="width:32px;height:32px;color:#ccc;"></i>
                            <p class="text-muted mt-2 mb-0 small">Aucun message</p>
                        </div>
                    @endforelse
                </div>

                <div class="dropdown-footer text-center">
                    <a href="#">Voir tous les messages <i data-feather="chevron-right" style="width:14px;height:14px;"></i></a>
                </div>
            </div>
        </li>

        {{-- Notifications --}}
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown"
               class="nav-link nav-link-lg notification-toggle position-relative"
               title="Notifications">
                <i data-feather="bell"></i>
                @if($unreadNotifCount > 0)
                    <span class="badge badge-pill badge-danger badge-sm position-absolute"
                          style="top:4px;right:4px;font-size:10px;min-width:18px;">
                        {{ $unreadNotifCount > 99 ? '99+' : $unreadNotifCount }}
                    </span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header d-flex justify-content-between align-items-center">
                    <span><i data-feather="bell" style="width:14px;height:14px;margin-right:6px;"></i>Notifications</span>
                    @if($unreadNotifCount > 0)
                    <form method="POST" action="#" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 text-primary small">
                            Tout marquer lu
                        </button>
                    </form>
                    @endif
                </div>

                <div class="dropdown-list-content dropdown-list-icons">
                    @php
                        try {
                            $notifs = method_exists($user, 'unreadNotifications')
                                ? $user->unreadNotifications()->take(5)->get()
                                : collect();
                        } catch (\Exception $e) { $notifs = collect(); }
                    @endphp

                    @forelse($notifs as $notif)
                        @php
                            $icon  = $notif->data['icon']  ?? 'bell';
                            $color = $notif->data['color'] ?? '#008751';
                            $msg   = $notif->data['message'] ?? 'Nouvelle notification';
                            $link  = $notif->data['url']   ?? '#';
                        @endphp
                        <a href="{{ $link }}" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon text-white d-flex align-items-center justify-content-center rounded-circle"
                                 style="width:36px;height:36px;background:{{ $color }};flex-shrink:0;">
                                <i data-feather="{{ $icon }}" style="width:16px;height:16px;"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                <p class="mb-0">{{ $msg }}</p>
                                <span class="time text-muted" style="font-size:11px;">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-4">
                            <i data-feather="bell-off" style="width:32px;height:32px;color:#ccc;"></i>
                            <p class="text-muted mt-2 mb-0 small">Aucune notification</p>
                        </div>
                    @endforelse
                </div>

                <div class="dropdown-footer text-center">
                    <a href="#">Toutes les notifications <i data-feather="chevron-right" style="width:14px;height:14px;"></i></a>
                </div>
            </div>
        </li>

        {{-- Profil utilisateur --}}
        <li class="dropdown">
            <a href="#" data-toggle="dropdown"
               class="nav-link dropdown-toggle nav-link-lg nav-link-user d-flex align-items-center"
               style="gap:10px;padding:6px 10px;">

                {{-- Avatar --}}
                @if($user->avatar ?? false)
                    <img alt="{{ $user->name }}"
                         src="{{ asset('storage/'.$user->avatar) }}"
                         class="rounded-circle"
                         style="width:38px;height:38px;object-fit:cover;border:2px solid rgba(255,255,255,0.3);">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                         style="width:38px;height:38px;background:{{ $bgColor }};font-size:14px;flex-shrink:0;border:2px solid rgba(255,255,255,0.3);">
                        {{ $initials ?: 'U' }}
                    </div>
                @endif

                {{-- Nom + rôle (desktop uniquement) --}}
                <div class="d-none d-lg-flex flex-column" style="line-height:1.2;">
                    <span style="font-weight:600;font-size:13px;">{{ $user->name ?? 'Utilisateur' }}</span>
                    <small class="text-muted" style="font-size:11px;">
                        {{ ucfirst($user->roles->first()?->name ?? 'Membre') }}
                    </small>
                </div>

                <i data-feather="chevron-down" class="d-none d-lg-block" style="width:14px;height:14px;opacity:0.6;"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right" style="min-width:240px;">
                {{-- En-tête profil --}}
                <div class="px-4 py-3 border-bottom d-flex align-items-center" style="gap:12px;">
                    @if($user->avatar ?? false)
                        <img src="{{ asset('storage/'.$user->avatar) }}" class="rounded-circle"
                             style="width:44px;height:44px;object-fit:cover;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width:44px;height:44px;background:{{ $bgColor }};font-size:15px;flex-shrink:0;">
                            {{ $initials ?: 'U' }}
                        </div>
                    @endif
                    <div>
                        <div style="font-weight:600;font-size:14px;">{{ $user->name ?? 'Utilisateur' }}</div>
                        <div class="text-muted" style="font-size:12px;">{{ $user->email }}</div>
                        <span class="badge mt-1"
                              style="background:{{ $bgColor }};color:white;font-size:10px;padding:2px 8px;border-radius:10px;">
                            {{ ucfirst($user->roles->first()?->name ?? 'Membre') }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <a href="{{ route('profile.edit') }}" class="dropdown-item has-icon">
                    <i data-feather="user"></i> Mon profil
                </a>
                <a href="#" class="dropdown-item has-icon">
                    <i data-feather="settings"></i> Paramètres
                </a>

                <div class="dropdown-divider"></div>

                <a href="{{ route('home') }}" class="dropdown-item has-icon" target="_blank">
                    <i data-feather="external-link"></i> Voir le site public
                </a>

                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit"
                            class="dropdown-item has-icon text-danger"
                            style="background:none;border:none;width:100%;text-align:left;cursor:pointer;">
                        <i data-feather="log-out"></i> Déconnexion
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>