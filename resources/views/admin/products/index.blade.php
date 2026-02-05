@extends('layouts.admin')

@section('title', 'Gestion des produits')

@section('content')
<div class="section-header">
    <h1>Produits</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Produits</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des produits</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un produit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Filtres --}}
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="category" class="form-control">
                                    <option value="">Toutes catégories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                            {{ ucfirst($cat) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="stock_status" class="form-control">
                                    <option value="">Tous statuts</option>
                                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>En stock</option>
                                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Rupture</option>
                                    <option value="preorder" {{ request('stock_status') == 'preorder' ? 'selected' : '' }}>Précommande</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">Filtrer</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-block">Réinitialiser</a>
                            </div>
                        </div>
                    </form>

                    {{-- Tableau --}}
                    <div class="table-responsive">
                        <table class="table table-striped" id="products-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Artisan</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Vedette</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        @if($product->images->first())
                                            <img src="{{ asset($product->images->first()->image_url) }}" alt="{{ $product->name }}" 
                                                 width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                                        @else
                                            <img src="{{ asset('admin-assets/img/image-64.png') }}" alt="No image" width="50">
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($product->name, 30) }}</strong>
                                        @if($product->name_local)
                                            <br><small class="text-muted">{{ $product->name_local }}</small>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-primary">{{ ucfirst($product->category) }}</span></td>
                                    <td>{{ $product->artisan->user->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($product->price, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        @php
                                            $stockColors = [
                                                'in_stock' => 'success',
                                                'out_of_stock' => 'danger',
                                                'preorder' => 'warning',
                                                'made_to_order' => 'info',
                                            ];
                                            $stockLabels = [
                                                'in_stock' => 'En stock',
                                                'out_of_stock' => 'Rupture',
                                                'preorder' => 'Précommande',
                                                'made_to_order' => 'Sur commande',
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $stockColors[$product->stock_status] ?? 'secondary' }}">
                                            {{ $stockLabels[$product->stock_status] ?? $product->stock_status }}
                                        </span>
                                    </td>
                                    <td>
                                        <label class="custom-switch mt-2">
                                            <input type="checkbox" class="custom-switch-input toggle-featured" 
                                                   data-id="{{ $product->id }}" {{ $product->featured ? 'checked' : '' }}>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Aucun produit trouvé</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle Featured
    $('.toggle-featured').change(function() {
        var productId = $(this).data('id');
        $.post("{{ url('admin/products') }}/" + productId + "/toggle-featured", {
            _token: '{{ csrf_token() }}'
        }).done(function(response) {
            iziToast.success({
                title: 'Succès',
                message: 'Statut mis à jour',
                position: 'topRight'
            });
        });
    });

    // Confirm delete
    $('.delete-form').submit(function(e) {
        e.preventDefault();
        var form = this;
        swal({
            title: 'Êtes-vous sûr?',
            text: 'Cette action est irréversible!',
            icon: 'warning',
            buttons: ['Annuler', 'Supprimer'],
            dangerMode: true,
        }).then(function(willDelete) {
            if (willDelete) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
