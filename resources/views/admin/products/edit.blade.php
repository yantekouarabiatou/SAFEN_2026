@extends('layouts.admin')

@section('title', 'Modifier le produit')

@section('content')
<div class="section-header">
    <h1>Modifier le produit</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produits</a></div>
        <div class="breadcrumb-item active">Modifier</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header">
                        <h4>{{ $product->name }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Informations de base --}}
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Nom du produit <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label>Nom local</label>
                                    <input type="text" name="name_local" class="form-control" 
                                           value="{{ old('name_local', $product->name_local) }}" placeholder="Nom en langue locale">
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Catégorie <span class="text-danger">*</span></label>
                                            <select name="category" class="form-control select2 @error('category') is-invalid @enderror" required>
                                                <option value="">Sélectionner une catégorie</option>
                                                @foreach($categories as $key => $label)
                                                    <option value="{{ $key }}" {{ old('category', $product->category) == $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sous-catégorie</label>
                                            <input type="text" name="subcategory" class="form-control" 
                                                   value="{{ old('subcategory', $product->subcategory) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Artisan <span class="text-danger">*</span></label>
                                    <select name="artisan_id" class="form-control select2 @error('artisan_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un artisan</option>
                                        @foreach($artisans as $artisan)
                                            <option value="{{ $artisan->id }}" {{ old('artisan_id', $product->artisan_id) == $artisan->id ? 'selected' : '' }}>
                                                {{ $artisan->user->name }} - {{ $artisan->specialty }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('artisan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Description culturelle</label>
                                    <textarea name="description_cultural" class="form-control" rows="3" 
                                              placeholder="Histoire et signification culturelle du produit">{{ old('description_cultural', $product->description_cultural) }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Description technique</label>
                                    <textarea name="description_technical" class="form-control" rows="3"
                                              placeholder="Techniques de fabrication, matériaux utilisés...">{{ old('description_technical', $product->description_technical) }}</textarea>
                                </div>
                            </div>
                            
                            {{-- Sidebar --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Prix (FCFA) <span class="text-danger">*</span></label>
                                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                           value="{{ old('price', $product->price) }}" required min="0" step="100">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label>Statut du stock <span class="text-danger">*</span></label>
                                    <select name="stock_status" class="form-control" required>
                                        <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>En stock</option>
                                        <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Rupture de stock</option>
                                        <option value="preorder" {{ old('stock_status', $product->stock_status) == 'preorder' ? 'selected' : '' }}>Précommande</option>
                                        <option value="made_to_order" {{ old('stock_status', $product->stock_status) == 'made_to_order' ? 'selected' : '' }}>Sur commande</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>Origine ethnique</label>
                                    <input type="text" name="ethnic_origin" class="form-control" 
                                           value="{{ old('ethnic_origin', $product->ethnic_origin) }}" placeholder="Ex: Fon, Yoruba, Bariba...">
                                </div>
                                
                                <div class="form-group">
                                    <label>Matériaux (séparés par virgule)</label>
                                    <input type="text" name="materials" class="form-control" 
                                           value="{{ old('materials', is_array($product->materials) ? implode(', ', $product->materials) : $product->materials) }}" 
                                           placeholder="bois, cuir, perles...">
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Largeur (cm)</label>
                                            <input type="number" name="width" class="form-control" 
                                                   value="{{ old('width', $product->width) }}" step="0.1">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Hauteur (cm)</label>
                                            <input type="number" name="height" class="form-control" 
                                                   value="{{ old('height', $product->height) }}" step="0.1">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Profondeur (cm)</label>
                                            <input type="number" name="depth" class="form-control" 
                                                   value="{{ old('depth', $product->depth) }}" step="0.1">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Poids (kg)</label>
                                            <input type="number" name="weight" class="form-control" 
                                                   value="{{ old('weight', $product->weight) }}" step="0.1">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" name="featured" class="custom-switch-input" 
                                               {{ old('featured', $product->featured) ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Produit vedette</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Images actuelles --}}
                        @if($product->images->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <label>Images actuelles</label>
                                <div class="gallery gallery-md">
                                    @foreach($product->images as $image)
                                    <div class="gallery-item" data-image="{{ asset($image->image_url) }}" 
                                         style="background-image: url('{{ asset($image->image_url) }}');">
                                        <div class="gallery-item-buttons">
                                            @if($image->is_primary)
                                                <span class="badge badge-primary">Principale</span>
                                            @else
                                                <button type="button" class="btn btn-sm btn-primary btn-set-primary" 
                                                        data-id="{{ $image->id }}" title="Définir comme principale">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-danger btn-delete-image" 
                                                    data-id="{{ $image->id }}" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        {{-- Nouvelles images --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Ajouter de nouvelles images</label>
                                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                                    <small class="form-text text-muted">Vous pouvez sélectionner plusieurs images.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.gallery-item {
    position: relative;
}
.gallery-item-buttons {
    position: absolute;
    bottom: 5px;
    right: 5px;
    display: flex;
    gap: 5px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2();
    
    // Supprimer une image
    $('.btn-delete-image').click(function() {
        var imageId = $(this).data('id');
        var $galleryItem = $(this).closest('.gallery-item');
        
        Swal.fire({
            title: 'Supprimer cette image ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/products/images") }}/' + imageId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $galleryItem.fadeOut(300, function() { $(this).remove(); });
                        Swal.fire('Supprimé!', 'L\'image a été supprimée.', 'success');
                    },
                    error: function() {
                        Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
                    }
                });
            }
        });
    });
    
    // Définir image principale
    $('.btn-set-primary').click(function() {
        var imageId = $(this).data('id');
        
        $.ajax({
            url: '{{ url("admin/products/images") }}/' + imageId + '/primary',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
            }
        });
    });
});
</script>
@endpush
