@extends('layouts.admin')

@section('title', 'Mes Produits')

@section('content')
<div class="section-header">
    <h1>Mes Produits</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard.artisan') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Mes Produits</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste de mes produits</h4>
                    <div class="card-header-action">
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un produit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped" id="products-table">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Vues</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products ?? [] as $index => $product)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}" 
                                             alt="{{ $product->name }}" 
                                             width="60" 
                                             class="rounded">
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    </td>
                                    <td>{{ $product->category ?? 'Non définie' }}</td>
                                    <td class="font-weight-bold text-primary">
                                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td>
                                        @if(($product->stock ?? 0) > 10)
                                            <span class="badge badge-success">{{ $product->stock }} en stock</span>
                                        @elseif(($product->stock ?? 0) > 0)
                                            <span class="badge badge-warning">{{ $product->stock }} restants</span>
                                        @else
                                            <span class="badge badge-danger">Rupture</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fas fa-eye"></i> {{ $product->views ?? 0 }}
                                    </td>
                                    <td>
                                        @if($product->is_active ?? true)
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Voir"
                                               target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Supprimer"
                                                    onclick="confirmDelete({{ $product->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $product->id }}" 
                                              action="{{ route('products.destroy', $product) }}" 
                                              method="POST" 
                                              style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-primary">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                            <h2>Aucun produit</h2>
                                            <p class="lead">Vous n'avez pas encore ajouté de produits à votre boutique.</p>
                                            <a href="{{ route('products.create') }}" class="btn btn-primary mt-4">
                                                <i class="fas fa-plus"></i> Ajouter mon premier produit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(productId) {
    swal({
        title: 'Êtes-vous sûr?',
        text: 'Cette action est irréversible!',
        icon: 'warning',
        buttons: {
            cancel: {
                text: "Annuler",
                value: null,
                visible: true,
                className: "",
                closeModal: true,
            },
            confirm: {
                text: "Oui, supprimer!",
                value: true,
                visible: true,
                className: "btn-danger",
                closeModal: true
            }
        },
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            document.getElementById('delete-form-' + productId).submit();
        }
    });
}

$(document).ready(function() {
    @if(count($products ?? []) > 0)
    $('#products-table').DataTable({
        "paging": false,
        "info": false,
        "language": {
            "search": "Rechercher:",
            "zeroRecords": "Aucun produit trouvé",
        }
    });
    @endif
});
</script>
@endpush
