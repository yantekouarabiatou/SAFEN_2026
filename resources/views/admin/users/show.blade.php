@extends('layouts.admin')

@section('title', $user->name)

@section('content')
<div class="section-header">
    <h1>Profil utilisateur</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></div>
        <div class="breadcrumb-item active">{{ $user->name }}</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        {{-- Profil --}}
        <div class="col-md-4">
            <div class="card profile-widget">
                <div class="profile-widget-header">
                    <img src="{{ $user->profile_photo_url ?? asset('admin-assets/img/avatar/avatar-1.png') }}" 
                         alt="{{ $user->name }}" 
                         class="rounded-circle profile-widget-picture" 
                         style="width: 100px; height: 100px; object-fit: cover;">
                    <div class="profile-widget-items">
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label">Commandes</div>
                            <div class="profile-widget-item-value">{{ $user->orders->count() }}</div>
                        </div>
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label">Favoris</div>
                            <div class="profile-widget-item-value">{{ $user->favorites->count() ?? 0 }}</div>
                        </div>
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label">Avis</div>
                            <div class="profile-widget-item-value">{{ $user->reviews->count() ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="profile-widget-description">
                    <div class="profile-widget-name">
                        {{ $user->name }}
                        <div class="text-muted d-inline font-weight-normal">
                            <div class="slash"></div>
                            @foreach($user->roles as $role)
                                {{ ucfirst($role->name) }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                            @if($user->roles->isEmpty())
                                Utilisateur
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                </div>
            </div>
            
            {{-- Informations de contact --}}
            <div class="card">
                <div class="card-header">
                    <h4>Informations</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border">
                        <li class="media">
                            <div class="media-icon"><i class="fas fa-envelope"></i></div>
                            <div class="media-body">
                                <div class="media-title">Email</div>
                                <div class="text-muted">{{ $user->email }}</div>
                            </div>
                        </li>
                        @if($user->phone)
                        <li class="media">
                            <div class="media-icon"><i class="fas fa-phone"></i></div>
                            <div class="media-body">
                                <div class="media-title">Téléphone</div>
                                <div class="text-muted">{{ $user->phone }}</div>
                            </div>
                        </li>
                        @endif
                        @if($user->address)
                        <li class="media">
                            <div class="media-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="media-body">
                                <div class="media-title">Adresse</div>
                                <div class="text-muted">{{ $user->address }}</div>
                            </div>
                        </li>
                        @endif
                        <li class="media">
                            <div class="media-icon"><i class="fas fa-calendar"></i></div>
                            <div class="media-body">
                                <div class="media-title">Inscrit le</div>
                                <div class="text-muted">{{ $user->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        </li>
                        @if($user->last_login_at)
                        <li class="media">
                            <div class="media-icon"><i class="fas fa-sign-in-alt"></i></div>
                            <div class="media-body">
                                <div class="media-title">Dernière connexion</div>
                                <div class="text-muted">{{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y à H:i') }}</div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            
            {{-- Rôles --}}
            <div class="card">
                <div class="card-header">
                    <h4>Rôles & Permissions</h4>
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        @foreach($user->roles as $role)
                            @switch($role->name)
                                @case('admin')
                                    <span class="badge badge-danger">Admin</span>
                                    @break
                                @case('artisan')
                                    <span class="badge badge-warning">Artisan</span>
                                    @break
                                @case('vendor')
                                    <span class="badge badge-info">Vendeur</span>
                                    @break
                                @default
                                    <span class="badge badge-light">{{ ucfirst($role->name) }}</span>
                            @endswitch
                        @endforeach
                    @else
                        <span class="badge badge-secondary">Utilisateur standard</span>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Activités --}}
        <div class="col-md-8">
            {{-- Profil artisan --}}
            @if($user->artisan)
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-hands"></i> Profil Artisan</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.artisans.show', $user->artisan) }}" class="btn btn-sm btn-primary">
                            Voir le profil complet
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Spécialité:</strong> {{ $user->artisan->specialty }}</p>
                            <p><strong>Expérience:</strong> {{ $user->artisan->experience_years ?? 0 }} ans</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Produits:</strong> {{ $user->artisan->products->count() }}</p>
                            <p><strong>Statut:</strong> 
                                @if($user->artisan->is_verified)
                                    <span class="badge badge-success">Vérifié</span>
                                @else
                                    <span class="badge badge-warning">En attente</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            {{-- Dernières commandes --}}
            <div class="card">
                <div class="card-header">
                    <h4>Dernières commandes</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.orders.index') }}?user_id={{ $user->id }}" class="btn btn-sm btn-primary">
                            Voir toutes
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Articles</th>
                                    <th>Total</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->orders->take(5) as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}">
                                            #{{ $order->order_number ?? $order->id }}
                                        </a>
                                    </td>
                                    <td>{{ $order->items->count() }} article(s)</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge badge-warning">En attente</span>
                                                @break
                                            @case('delivered')
                                                <span class="badge badge-success">Livrée</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge badge-danger">Annulée</span>
                                                @break
                                            @default
                                                <span class="badge badge-info">{{ $order->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        <span class="text-muted">Aucune commande</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- Derniers avis --}}
            @if($user->reviews && $user->reviews->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h4>Derniers avis</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border">
                        @foreach($user->reviews->take(5) as $review)
                        <li class="media">
                            <div class="media-body">
                                <div class="float-right">
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <div class="media-title">
                                    @if($review->product)
                                        {{ $review->product->name }}
                                    @elseif($review->artisan)
                                        {{ $review->artisan->user->name }}
                                    @endif
                                </div>
                                <span class="text-small text-muted">{{ $review->created_at->diffForHumans() }}</span>
                                @if($review->comment)
                                <p class="mt-2 mb-0">{{ Str::limit($review->comment, 150) }}</p>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            
            {{-- Statistiques d'achat --}}
            <div class="card">
                <div class="card-header">
                    <h4>Statistiques</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-primary">{{ $user->orders->count() }}</h4>
                            <p class="text-muted">Commandes</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success">{{ number_format($user->orders->sum('total_amount'), 0, ',', ' ') }}</h4>
                            <p class="text-muted">FCFA dépensés</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info">{{ $user->orders->where('status', 'delivered')->count() }}</h4>
                            <p class="text-muted">Livrées</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning">{{ $user->reviews->count() ?? 0 }}</h4>
                            <p class="text-muted">Avis donnés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
