@extends('layouts.admin')

@section('title', 'Modifier le plat')

@section('content')
<div class="section-header">
    <h1>Modifier le plat</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.dishes.index') }}">Gastronomie</a></div>
        <div class="breadcrumb-item active">{{ $dish->name }}</div>
    </div>
</div>

<div class="section-body">
    <form action="{{ route('admin.dishes.update', $dish) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations du plat</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom du plat <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $dish->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom local</label>
                                    <input type="text" name="name_local" class="form-control" 
                                           value="{{ old('name_local', $dish->name_local) }}" placeholder="Nom en langue locale">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Catégorie <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control select2 @error('category') is-invalid @enderror" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories as $key => $label)
                                            <option value="{{ $key }}" {{ old('category', $dish->category) == $key ? 'selected' : '' }}>
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
                                    <label>Région d'origine</label>
                                    <input type="text" name="region" class="form-control" 
                                           value="{{ old('region', $dish->region) }}" placeholder="Ex: Sud-Bénin, Atlantique...">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $dish->description) }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Signification culturelle</label>
                            <textarea name="cultural_significance" class="form-control" rows="3"
                                      placeholder="Contexte culturel, histoire, occasions spéciales...">{{ old('cultural_significance', $dish->cultural_significance) }}</textarea>
                        </div>
                        
                        <hr>
                        <h5 class="mb-3">Ingrédients & Préparation</h5>
                        
                        <div class="form-group">
                            <label>Ingrédients principaux</label>
                            <textarea name="main_ingredients" class="form-control" rows="3"
                                      placeholder="Listez les ingrédients principaux...">{{ old('main_ingredients', $dish->main_ingredients) }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Ingrédients secondaires</label>
                            <textarea name="secondary_ingredients" class="form-control" rows="2"
                                      placeholder="Épices, assaisonnements...">{{ old('secondary_ingredients', $dish->secondary_ingredients) }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Méthode de préparation</label>
                            <textarea name="preparation_method" class="form-control" rows="4"
                                      placeholder="Étapes de préparation...">{{ old('preparation_method', $dish->preparation_method) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Détails</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Prix (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                   value="{{ old('price', $dish->price) }}" required min="0" step="100">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Temps de préparation</label>
                                    <div class="input-group">
                                        <input type="number" name="preparation_time" class="form-control" 
                                               value="{{ old('preparation_time', $dish->preparation_time) }}" min="0">
                                        <div class="input-group-append">
                                            <span class="input-group-text">min</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Portions</label>
                                    <input type="number" name="serving_size" class="form-control" 
                                           value="{{ old('serving_size', $dish->serving_size) }}" min="1" placeholder="Personnes">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Niveau de difficulté</label>
                            <select name="difficulty_level" class="form-control">
                                <option value="">Sélectionner</option>
                                <option value="easy" {{ old('difficulty_level', $dish->difficulty_level) == 'easy' ? 'selected' : '' }}>Facile</option>
                                <option value="medium" {{ old('difficulty_level', $dish->difficulty_level) == 'medium' ? 'selected' : '' }}>Moyen</option>
                                <option value="hard" {{ old('difficulty_level', $dish->difficulty_level) == 'hard' ? 'selected' : '' }}>Difficile</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Niveau de piment</label>
                            <select name="spice_level" class="form-control">
                                <option value="">Non spécifié</option>
                                <option value="none" {{ old('spice_level', $dish->spice_level) == 'none' ? 'selected' : '' }}>Non épicé</option>
                                <option value="mild" {{ old('spice_level', $dish->spice_level) == 'mild' ? 'selected' : '' }}>Légèrement épicé</option>
                                <option value="medium" {{ old('spice_level', $dish->spice_level) == 'medium' ? 'selected' : '' }}>Épicé</option>
                                <option value="hot" {{ old('spice_level', $dish->spice_level) == 'hot' ? 'selected' : '' }}>Très épicé</option>
                            </select>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label class="d-block">Options alimentaires</label>
                            <label class="custom-switch mt-2">
                                <input type="checkbox" name="is_vegetarian" class="custom-switch-input" {{ old('is_vegetarian', $dish->is_vegetarian) ? 'checked' : '' }}>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Végétarien</span>
                            </label>
                            <label class="custom-switch mt-2">
                                <input type="checkbox" name="is_vegan" class="custom-switch-input" {{ old('is_vegan', $dish->is_vegan) ? 'checked' : '' }}>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Végan</span>
                            </label>
                            <label class="custom-switch mt-2">
                                <input type="checkbox" name="is_gluten_free" class="custom-switch-input" {{ old('is_gluten_free', $dish->is_gluten_free) ? 'checked' : '' }}>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Sans gluten</span>
                            </label>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label class="custom-switch">
                                <input type="checkbox" name="featured" class="custom-switch-input" {{ old('featured', $dish->featured) ? 'checked' : '' }}>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Plat vedette</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                {{-- Images actuelles --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Images</h4>
                    </div>
                    <div class="card-body">
                        @if($dish->images->count() > 0)
                        <div class="gallery gallery-md mb-3">
                            @foreach($dish->images as $image)
                            <div class="gallery-item" style="background-image: url('{{ asset($image->image_url) }}');">
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
                        @endif
                        
                        <div class="form-group">
                            <label>Ajouter des images</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.dishes.index') }}" class="btn btn-secondary btn-block">Annuler</a>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
                    url: '{{ url("admin/dishes/images") }}/' + imageId,
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
            url: '{{ url("admin/dishes/images") }}/' + imageId + '/primary',
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
