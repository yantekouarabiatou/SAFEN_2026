@extends('layouts.admin')

@section('title', $dish->name)

@section('content')
<div class="section-header">
    <h1>Détails du plat</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.dishes.index') }}">Gastronomie</a></div>
        <div class="breadcrumb-item active">{{ $dish->name }}</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        {{-- Images --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    @if($dish->images->count() > 0)
                        @php
                            $primaryImage = $dish->images->where('is_primary', true)->first() ?? $dish->images->first();
                        @endphp
                        <img src="{{ asset($primaryImage->image_url) }}" alt="{{ $dish->name }}" 
                             class="img-fluid rounded mb-3" id="main-image">
                        
                        @if($dish->images->count() > 1)
                        <div class="row">
                            @foreach($dish->images as $image)
                            <div class="col-3 mb-2">
                                <img src="{{ asset($image->image_url) }}" alt="{{ $dish->name }}" 
                                     class="img-fluid rounded cursor-pointer {{ $image->is_primary ? 'border border-primary' : '' }}"
                                     onclick="document.getElementById('main-image').src = '{{ asset($image->image_url) }}'">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon bg-light">
                                <i class="fas fa-image"></i>
                            </div>
                            <h2>Aucune image</h2>
                        </div>
                    @endif
                </div>
            </div>
            
            {{-- Informations nutritionnelles --}}
            <div class="card">
                <div class="card-header">
                    <h4>Informations</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="mb-2">
                                <i class="fas fa-clock fa-2x text-primary"></i>
                            </div>
                            <h6>{{ $dish->preparation_time ?? '-' }}</h6>
                            <small class="text-muted">minutes</small>
                        </div>
                        <div class="col-4">
                            <div class="mb-2">
                                <i class="fas fa-users fa-2x text-success"></i>
                            </div>
                            <h6>{{ $dish->serving_size ?? '-' }}</h6>
                            <small class="text-muted">portions</small>
                        </div>
                        <div class="col-4">
                            <div class="mb-2">
                                <i class="fas fa-signal fa-2x text-warning"></i>
                            </div>
                            <h6>
                                @switch($dish->difficulty_level)
                                    @case('easy') Facile @break
                                    @case('medium') Moyen @break
                                    @case('hard') Difficile @break
                                    @default -
                                @endswitch
                            </h6>
                            <small class="text-muted">difficulté</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @if($dish->is_vegetarian)
                            <span class="badge badge-success"><i class="fas fa-leaf"></i> Végétarien</span>
                        @endif
                        @if($dish->is_vegan)
                            <span class="badge badge-success"><i class="fas fa-seedling"></i> Végan</span>
                        @endif
                        @if($dish->is_gluten_free)
                            <span class="badge badge-info"><i class="fas fa-bread-slice"></i> Sans gluten</span>
                        @endif
                        @if($dish->spice_level)
                            @switch($dish->spice_level)
                                @case('none')
                                    <span class="badge badge-light"><i class="fas fa-pepper-hot"></i> Non épicé</span>
                                    @break
                                @case('mild')
                                    <span class="badge badge-warning"><i class="fas fa-pepper-hot"></i> Peu épicé</span>
                                    @break
                                @case('medium')
                                    <span class="badge badge-orange"><i class="fas fa-pepper-hot"></i> Épicé</span>
                                    @break
                                @case('hot')
                                    <span class="badge badge-danger"><i class="fas fa-pepper-hot"></i> Très épicé</span>
                                    @break
                            @endswitch
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Détails --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $dish->name }}</h4>
                    <div class="card-header-action">
                        @if($dish->featured)
                            <span class="badge badge-warning"><i class="fas fa-star"></i> Vedette</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Prix</label>
                                <h3 class="text-primary">{{ number_format($dish->price, 0, ',', ' ') }} FCFA</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Catégorie</label>
                                <p><span class="badge badge-info">{{ $dish->category }}</span></p>
                            </div>
                        </div>
                    </div>
                    
                    @if($dish->name_local)
                    <div class="form-group">
                        <label class="text-muted">Nom local</label>
                        <p>{{ $dish->name_local }}</p>
                    </div>
                    @endif
                    
                    @if($dish->region)
                    <div class="form-group">
                        <label class="text-muted">Région d'origine</label>
                        <p><i class="fas fa-map-marker-alt"></i> {{ $dish->region }}</p>
                    </div>
                    @endif
                    
                    @if($dish->description)
                    <div class="form-group">
                        <label class="text-muted">Description</label>
                        <p>{{ $dish->description }}</p>
                    </div>
                    @endif
                    
                    @if($dish->cultural_significance)
                    <div class="form-group">
                        <label class="text-muted">Signification culturelle</label>
                        <p>{{ $dish->cultural_significance }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Ingrédients --}}
            <div class="card">
                <div class="card-header">
                    <h4>Ingrédients</h4>
                </div>
                <div class="card-body">
                    @if($dish->main_ingredients)
                    <div class="form-group">
                        <label class="text-muted">Ingrédients principaux</label>
                        <p>{{ $dish->main_ingredients }}</p>
                    </div>
                    @endif
                    
                    @if($dish->secondary_ingredients)
                    <div class="form-group">
                        <label class="text-muted">Ingrédients secondaires</label>
                        <p>{{ $dish->secondary_ingredients }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Préparation --}}
            @if($dish->preparation_method)
            <div class="card">
                <div class="card-header">
                    <h4>Méthode de préparation</h4>
                </div>
                <div class="card-body">
                    <p>{!! nl2br(e($dish->preparation_method)) !!}</p>
                </div>
            </div>
            @endif
            
            {{-- Vendeurs --}}
            @if($dish->vendors && $dish->vendors->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h4>Vendeurs proposant ce plat</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Vendeur</th>
                                    <th>Localisation</th>
                                    <th>Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dish->vendors as $vendor)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.vendors.show', $vendor) }}">
                                            {{ $vendor->name }}
                                        </a>
                                    </td>
                                    <td>{{ $vendor->location ?? '-' }}</td>
                                    <td>{{ number_format($vendor->pivot->price ?? $dish->price, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    {{-- Actions --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.dishes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                    <a href="{{ route('admin.dishes.edit', $dish) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <button type="button" class="btn btn-danger float-right" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" action="{{ route('admin.dishes.destroy', $dish) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    Swal.fire({
        title: 'Supprimer ce plat ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}
</script>
@endpush
