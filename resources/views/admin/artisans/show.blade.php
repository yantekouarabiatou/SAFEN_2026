@extends('layouts.admin')

@section('title', $artisan->user->name)

@section('content')
<div class="section-header">
    <h1>Profil de l'artisan</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.artisans.index') }}">Artisans</a></div>
        <div class="breadcrumb-item active">{{ $artisan->user->name }}</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        {{-- Profil --}}
        <div class="col-md-4">
            <div class="card profile-widget">
                <div class="profile-widget-header">
                    @php
                        $primaryPhoto = $artisan->photos->where('is_primary', true)->first() ?? $artisan->photos->first();
                    @endphp
                    <img src="{{ $primaryPhoto ? asset($primaryPhoto->photo_url) : asset('admin-assets/img/avatar/avatar-1.png') }}" 
                         alt="{{ $artisan->user->name }}" 
                         class="rounded-circle profile-widget-picture" 
                         style="width: 100px; height: 100px; object-fit: cover;">
                    <div class="profile-widget-items">
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label">Produits</div>
                            <div class="profile-widget-item-value">{{ $artisan->products->count() }}</div>
                        </div>
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label">Avis</div>
                            <div class="profile-widget-item-value">{{ $artisan->reviews->count() }}</div>
                        </div>
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label">Note</div>
                            <div class="profile-widget-item-value">{{ number_format($artisan->average_rating ?? 0, 1) }}</div>
                        </div>
                    </div>
                </div>
                <div class="profile-widget-description">
                    <div class="profile-widget-name">
                        {{ $artisan->user->name }}
                        @if($artisan->is_verified)
                            <i class="fas fa-check-circle text-primary" title="Artisan vérifié"></i>
                        @endif
                        <div class="text-muted d-inline font-weight-normal">
                            <div class="slash"></div> {{ $artisan->specialty }}
                        </div>
                    </div>
                    @if($artisan->bio)
                        <p>{{ $artisan->bio }}</p>
                    @endif
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.artisans.edit', $artisan) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                </div>
            </div>
            
            {{-- Informations de contact --}}
            <div class="card">
                <div class="card-header">
                    <h4>Contact</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border">
                        <li class="media">
                            <div class="media-icon"><i class="fas fa-envelope"></i></div>
                            <div class="media-body">
                                <div class="media-title">Email</div>
                                <div class="text-muted">{{ $artisan->user->email }}</div>
                            </div>
                        </li>
                        @if($artisan->user->phone)
                        <li class="media">
                            <div class="media-icon"><i class="fas fa-phone"></i></div>
                            <div class="media-body">
                                <div class="media-title">Téléphone</div>
                                <div class="text-muted">{{ $artisan->user->phone }}</div>
                            </div>
                        </li>
                        @endif
                        @if($artisan->location)
                        <li class="media">
                            <div class="media-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="media-body">
                                <div class="media-title">Localisation</div>
                                <div class="text-muted">{{ $artisan->location }}</div>
                            </div>
                        </li>
                        @endif
                        @if($artisan->address)
                        <li class="media">
                            <div class="media-icon"><i class="fas fa-home"></i></div>
                            <div class="media-body">
                                <div class="media-title">Adresse</div>
                                <div class="text-muted">{{ $artisan->address }}</div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            
            {{-- Statut --}}
            <div class="card">
                <div class="card-header">
                    <h4>Statut</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="text-muted mb-1">Vérification</p>
                            @if($artisan->is_verified)
                                <span class="badge badge-success"><i class="fas fa-check"></i> Vérifié</span>
                            @else
                                <span class="badge badge-warning">En attente</span>
                            @endif
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-1">Disponibilité</p>
                            @switch($artisan->availability ?? 'available')
                                @case('available')
                                    <span class="badge badge-success">Disponible</span>
                                    @break
                                @case('busy')
                                    <span class="badge badge-warning">Occupé</span>
                                    @break
                                @case('vacation')
                                    <span class="badge badge-info">En vacances</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted mb-1">Commandes personnalisées</p>
                            @if($artisan->accepts_custom_orders)
                                <span class="badge badge-primary"><i class="fas fa-check"></i> Accepte</span>
                            @else
                                <span class="badge badge-secondary">N'accepte pas</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Détails et produits --}}
        <div class="col-md-8">
            {{-- Photos --}}
            @if($artisan->photos->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h4>Galerie photos</h4>
                </div>
                <div class="card-body">
                    <div class="gallery gallery-fw">
                        @foreach($artisan->photos as $photo)
                        <a href="{{ asset($photo->photo_url) }}" class="gallery-item" 
                           data-image="{{ asset($photo->photo_url) }}"
                           style="background-image: url('{{ asset($photo->photo_url) }}');">
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            
            {{-- Informations détaillées --}}
            <div class="card">
                <div class="card-header">
                    <h4>Détails</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Spécialité</label>
                                <p><span class="badge badge-info">{{ $artisan->specialty }}</span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Expérience</label>
                                <p>{{ $artisan->experience_years ?? 0 }} années</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($artisan->ethnic_origin)
                    <div class="form-group">
                        <label class="text-muted">Origine ethnique</label>
                        <p>{{ $artisan->ethnic_origin }}</p>
                    </div>
                    @endif
                    
                    @if($artisan->workshop_story)
                    <div class="form-group">
                        <label class="text-muted">Histoire de l'atelier</label>
                        <p>{{ $artisan->workshop_story }}</p>
                    </div>
                    @endif
                    
                    @if($artisan->techniques)
                    <div class="form-group">
                        <label class="text-muted">Techniques maîtrisées</label>
                        <p>
                            @php
                                $techniques = is_array($artisan->techniques) ? $artisan->techniques : explode(',', $artisan->techniques);
                            @endphp
                            @foreach($techniques as $technique)
                                <span class="badge badge-light">{{ trim($technique) }}</span>
                            @endforeach
                        </p>
                    </div>
                    @endif
                    
                    @if($artisan->materials)
                    <div class="form-group">
                        <label class="text-muted">Matériaux utilisés</label>
                        <p>
                            @php
                                $materials = is_array($artisan->materials) ? $artisan->materials : explode(',', $artisan->materials);
                            @endphp
                            @foreach($materials as $material)
                                <span class="badge badge-light">{{ trim($material) }}</span>
                            @endforeach
                        </p>
                    </div>
                    @endif
                    
                    @if($artisan->certifications)
                    <div class="form-group">
                        <label class="text-muted">Certifications & Prix</label>
                        <p>
                            @php
                                $certifications = is_array($artisan->certifications) ? $artisan->certifications : explode(',', $artisan->certifications);
                            @endphp
                            @foreach($certifications as $cert)
                                <span class="badge badge-success">{{ trim($cert) }}</span>
                            @endforeach
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Produits de l'artisan --}}
            <div class="card">
                <div class="card-header">
                    <h4>Produits ({{ $artisan->products->count() }})</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.products.create') }}?artisan_id={{ $artisan->id }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Ajouter un produit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($artisan->products->count() > 0)
                    <div class="row">
                        @foreach($artisan->products->take(6) as $product)
                        <div class="col-md-4 mb-3">
                            <div class="card card-sm">
                                @php
                                    $image = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                                @endphp
                                <img src="{{ $image ? asset($image->image_url) : asset('admin-assets/img/example-image.jpg') }}" 
                                     alt="{{ $product->name }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1">{{ Str::limit($product->name, 25) }}</h6>
                                    <p class="card-text">
                                        <span class="text-primary font-weight-bold">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                    </p>
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-primary btn-block">
                                        Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($artisan->products->count() > 6)
                    <div class="text-center">
                        <a href="{{ route('admin.products.index') }}?artisan_id={{ $artisan->id }}" class="btn btn-outline-primary">
                            Voir tous les produits ({{ $artisan->products->count() }})
                        </a>
                    </div>
                    @endif
                    @else
                    <div class="empty-state" data-height="200">
                        <div class="empty-state-icon bg-light">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h2>Aucun produit</h2>
                        <p class="lead">Cet artisan n'a pas encore de produits.</p>
                        <a href="{{ route('admin.products.create') }}?artisan_id={{ $artisan->id }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un produit
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Derniers avis --}}
            <div class="card">
                <div class="card-header">
                    <h4>Derniers avis</h4>
                </div>
                <div class="card-body">
                    @if($artisan->reviews->count() > 0)
                    <ul class="list-unstyled list-unstyled-border">
                        @foreach($artisan->reviews->take(5) as $review)
                        <li class="media">
                            <img class="mr-3 rounded-circle" width="50" 
                                 src="{{ $review->user->profile_photo_url ?? asset('admin-assets/img/avatar/avatar-1.png') }}" 
                                 alt="{{ $review->user->name }}">
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
                                <div class="media-title">{{ $review->user->name }}</div>
                                <span class="text-small text-muted">{{ $review->created_at->diffForHumans() }}</span>
                                <p class="mt-2">{{ $review->comment }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun avis pour le moment</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
