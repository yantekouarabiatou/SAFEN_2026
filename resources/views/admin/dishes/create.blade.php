@extends('layouts.admin')

@section('title', 'Ajouter un plat')

@section('content')
<div class="section-header">
    <h1>Ajouter un plat</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.dishes.index') }}">Gastronomie</a></div>
        <div class="breadcrumb-item active">Ajouter</div>
    </div>
</div>

<div class="section-body">
    <form action="{{ route('admin.dishes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
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
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom local</label>
                                    <input type="text" name="name_local" class="form-control" 
                                           value="{{ old('name_local') }}" placeholder="Nom en langue locale">
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
                                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
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
                                           value="{{ old('region') }}" placeholder="Ex: Sud-Bénin, Atlantique...">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Signification culturelle</label>
                            <textarea name="cultural_significance" class="form-control" rows="3"
                                      placeholder="Contexte culturel, histoire, occasions spéciales...">{{ old('cultural_significance') }}</textarea>
                        </div>
                        
                        <hr>
                        <h5 class="mb-3">Ingrédients & Préparation</h5>
                        
                        <div class="form-group">
                            <label>Ingrédients principaux</label>
                            <textarea name="main_ingredients" class="form-control" rows="3"
                                      placeholder="Listez les ingrédients principaux...">{{ old('main_ingredients') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Ingrédients secondaires</label>
                            <textarea name="secondary_ingredients" class="form-control" rows="2"
                                      placeholder="Épices, assaisonnements...">{{ old('secondary_ingredients') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Méthode de préparation</label>
                            <textarea name="preparation_method" class="form-control" rows="4"
                                      placeholder="Étapes de préparation...">{{ old('preparation_method') }}</textarea>
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
                                   value="{{ old('price') }}" required min="0" step="100">
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
                                               value="{{ old('preparation_time') }}" min="0">
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
                                           value="{{ old('serving_size') }}" min="1" placeholder="Personnes">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Niveau de difficulté</label>
                            <select name="difficulty_level" class="form-control">
                                <option value="">Sélectionner</option>
                                <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>Facile</option>
                                <option value="medium" {{ old('difficulty_level') == 'medium' ? 'selected' : '' }}>Moyen</option>
                                <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>Difficile</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Niveau de piment</label>
                            <select name="spice_level" class="form-control">
                                <option value="">Non spécifié</option>
                                <option value="none" {{ old('spice_level') == 'none' ? 'selected' : '' }}>Non épicé</option>
                                <option value="mild" {{ old('spice_level') == 'mild' ? 'selected' : '' }}>Légèrement épicé</option>
                                <option value="medium" {{ old('spice_level') == 'medium' ? 'selected' : '' }}>Épicé</option>
                                <option value="hot" {{ old('spice_level') == 'hot' ? 'selected' : '' }}>Très épicé</option>
                            </select>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label class="d-block">Options alimentaires</label>
                            <label class="custom-switch mt-2">
                                <input type="checkbox" name="is_vegetarian" class="custom-switch-input" {{ old('is_vegetarian') ? 'checked' : '' }}>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Végétarien</span>
                            </label>
                            <label class="custom-switch mt-2">
                                <input type="checkbox" name="is_vegan" class="custom-switch-input" {{ old('is_vegan') ? 'checked' : '' }}>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Végan</span>
                            </label>
                            <label class="custom-switch mt-2">
                                <input type="checkbox" name="is_gluten_free" class="custom-switch-input" {{ old('is_gluten_free') ? 'checked' : '' }}>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Sans gluten</span>
                            </label>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label class="custom-switch">
                                <input type="checkbox" name="featured" class="custom-switch-input" {{ old('featured') ? 'checked' : '' }}>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Plat vedette</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4>Images</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Photos du plat</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">La première image sera l'image principale</small>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.dishes.index') }}" class="btn btn-secondary btn-block">Annuler</a>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>
@endpush
