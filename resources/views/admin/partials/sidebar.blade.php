{{-- Sidebar conditionnelle selon le rôle --}}
@auth
    @php
        $user = auth()->user();
    @endphp

    @if($user->hasRole('super-admin') || $user->hasRole('admin'))
        @include('admin.partials.sidebar-admin')
    @elseif($user->hasRole('artisan'))
        @include('admin.partials.sidebar-artisan')
    @elseif($user->hasRole('vendor'))
        @include('admin.partials.sidebar-vendor')
    @elseif($user->hasRole('client'))
        @include('admin.partials.sidebar-client')
    @else
        {{-- Fallback : sidebar minimaliste --}}
        @include('admin.partials.sidebar-client')
    @endif
@endauth
