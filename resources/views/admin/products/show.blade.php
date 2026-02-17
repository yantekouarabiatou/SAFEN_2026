@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<div class="section-header">
    <h1>Détails du produit</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produits</a></div>
        <div class="breadcrumb-item active">{{ $product->name }}</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        {{-- Images du produit --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    @if($product->images->count() > 0)
                        @php
                            $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                        @endphp
                        <div class="chocolat-parent">
                            <a href="{{ asset($primaryImage->image_url) }}" class="chocolat-image">
                                <img src="{{ asset($primaryImage->image_url) }}" alt="{{ $product->name }}"
                                     class="img-fluid rounded mb-3" id="main-image">
                            </a>
                        </div>

                        @if($product->images->count() > 1)
                        <div class="row">
                            @foreach($product->images as $image)
                            <div class="col-3 mb-2">
                                <img src="{{ asset($image->image_url) }}" alt="{{ $product->name }}"
                                     class="img-fluid rounded thumbnail-image cursor-pointer {{ $image->is_primary ? 'border border-primary' : '' }}"
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
                            <p class="lead">Ce produit n'a pas encore d'images.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Informations du produit --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $product->name }}</h4>
                    <div class="card-header-action">
                        @if($product->featured)
                            <span class="badge badge-warning"><i class="fas fa-star"></i> Vedette</span>
                        @endif
                        @switch($product->stock_status)
                            @case('in_stock')
                                <span class="badge badge-success">En stock</span>
                                @break
                            @case('out_of_stock')
                                <span class="badge badge-danger">Rupture</span>
                                @break
                            @case('preorder')
                                <span class="badge badge-info">Précommande</span>
                                @break
                            @case('made_to_order')
                                <span class="badge badge-primary">Sur commande</span>
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Prix</label>
                                <h3 class="text-primary">{{ number_format($product->price, 0, ',', ' ') }} FCFA</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Catégorie</label>
                                <p>
                                    <span class="badge badge-light">{{ $product->category }}</span>
                                    @if($product->subcategory)
                                        <span class="badge badge-light">{{ $product->subcategory }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($product->name_local)
                    <div class="form-group">
                        <label class="text-muted">Nom local</label>
                        <p>{{ $product->name_local }}</p>
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="text-muted">Artisan</label>
                        <p>
                            <a href="{{ route('admin.artisans.show', $product->artisan) }}">
                                <img src="{{ $product->artisan->user->profile_photo_url ?? asset('admin-assets/img/avatar/avatar-1.png') }}"
                                     alt="" class="rounded-circle mr-2" width="30">
                                {{ $product->artisan->user->name }}
                            </a>
                            <small class="text-muted">- {{ $product->artisan->specialty }}</small>
                        </p>
                    </div>

                    @if($product->description)
                    <div class="form-group">
                        <label class="text-muted">Description</label>
                        <p>{{ $product->description }}</p>
                    </div>
                    @endif

                    @if($product->description_cultural)
                    <div class="form-group">
                        <label class="text-muted">Signification culturelle</label>
                        <p>{{ $product->description_cultural }}</p>
                    </div>
                    @endif

                    @if($product->description_technical)
                    <div class="form-group">
                        <label class="text-muted">Techniques de fabrication</label>
                        <p>{{ $product->description_technical }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Caractéristiques --}}
            <div class="card">
                <div class="card-header">
                    <h4>Caractéristiques</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($product->ethnic_origin)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Origine ethnique</label>
                                <p>{{ $product->ethnic_origin }}</p>
                            </div>
                        </div>
                        @endif

                        @if($product->materials)
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="text-muted">Matériaux</label>
                                <p>
                                    @php
                                        $materials = is_array($product->materials) ? $product->materials : explode(',', $product->materials);
                                    @endphp
                                    @foreach($materials as $material)
                                        <span class="badge badge-light">{{ trim($material) }}</span>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="row">
                        @if($product->width || $product->height || $product->depth)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Dimensions</label>
                                <p>
                                    @if($product->width) L: {{ $product->width }} cm @endif
                                    @if($product->height) × H: {{ $product->height }} cm @endif
                                    @if($product->depth) × P: {{ $product->depth }} cm @endif
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($product->weight)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Poids</label>
                                <p>{{ $product->weight }} kg</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Statistiques --}}
            <div class="card">
                <div class="card-header">
                    <h4>Statistiques</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="mb-2">
                                <i class="fas fa-eye fa-2x text-muted"></i>
                            </div>
                            <h5>{{ $product->views_count ?? 0 }}</h5>
                            <small class="text-muted">Vues</small>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2">
                                <i class="fas fa-heart fa-2x text-danger"></i>
                            </div>
                            <h5>{{ $product->favorites_count ?? 0 }}</h5>
                            <small class="text-muted">Favoris</small>
                        </div>
                        <div class="col-md-3">
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="number" name="quantity" value="1" min="1">
                                <button type="submit">Ajouter au panier</button>
                            </form>
                            <h5>{{ $product->orders_count ?? 0 }}</h5>
                            <small class="text-muted">Commandes</small>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2">
                                <i class="fas fa-star fa-2x text-warning"></i>
                            </div>
                            <h5>{{ number_format($product->average_rating ?? 0, 1) }}</h5>
                            <small class="text-muted">Note moyenne</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
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

<form id="delete-form" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
.thumbnail-image {
    cursor: pointer;
    transition: all 0.3s;
}
.thumbnail-image:hover {
    opacity: 0.8;
    transform: scale(1.05);
}
</style>
@endpush

@push('scripts')
<script>
$('.add-to-cart').click(function(e) {
    e.preventDefault();
    var product_id = $(this).data('product-id');
    var quantity = $(this).data('quantity') || 1;

    $.ajax({
        url: '{{ route('cart.add') }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            product_id: product_id,
            quantity: quantity
        },
        success: function(response) {
            // Mettre à jour l'interface
        },
        error: function(xhr) {
            // Gérer les erreurs
        }
    });
});
function confirmDelete() {
    Swal.fire({
        title: 'Supprimer ce produit ?',
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
