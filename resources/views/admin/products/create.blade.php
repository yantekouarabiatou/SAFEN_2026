@extends('layouts.admin')

@section('title', 'Ajouter un produit')

@section('content')
<div class="section-header">
    <h1>Ajouter un produit</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produits</a></div>
        <div class="breadcrumb-item active">Ajouter</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <h4>Informations du produit</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Informations de base --}}
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Nom du produit <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label>Nom local</label>
                                    <input type="text" name="name_local" class="form-control" 
                                           value="{{ old('name_local') }}" placeholder="Nom en langue locale">
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
                                            <label>Sous-catégorie</label>
                                            <input type="text" name="subcategory" class="form-control" 
                                                   value="{{ old('subcategory') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Artisan <span class="text-danger">*</span></label>
                                    <select name="artisan_id" class="form-control select2 @error('artisan_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un artisan</option>
                                        @foreach($artisans as $artisan)
                                            <option value="{{ $artisan->id }}" {{ old('artisan_id') == $artisan->id ? 'selected' : '' }}>
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
                                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Description culturelle</label>
                                    <textarea name="description_cultural" class="form-control" rows="3" 
                                              placeholder="Histoire et signification culturelle du produit">{{ old('description_cultural') }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Description technique</label>
                                    <textarea name="description_technical" class="form-control" rows="3"
                                              placeholder="Techniques de fabrication, matériaux utilisés...">{{ old('description_technical') }}</textarea>
                                </div>
                            </div>
                            
                            {{-- Sidebar --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Prix (FCFA) <span class="text-danger">*</span></label>
                                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                           value="{{ old('price') }}" required min="0" step="100">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label>Statut du stock <span class="text-danger">*</span></label>
                                    <select name="stock_status" class="form-control" required>
                                        <option value="in_stock" {{ old('stock_status') == 'in_stock' ? 'selected' : '' }}>En stock</option>
                                        <option value="out_of_stock" {{ old('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Rupture de stock</option>
                                        <option value="preorder" {{ old('stock_status') == 'preorder' ? 'selected' : '' }}>Précommande</option>
                                        <option value="made_to_order" {{ old('stock_status') == 'made_to_order' ? 'selected' : '' }}>Sur commande</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>Origine ethnique</label>
                                    <input type="text" name="ethnic_origin" class="form-control" 
                                           value="{{ old('ethnic_origin') }}" placeholder="Ex: Fon, Yoruba, Bariba...">
                                </div>
                                
                                <div class="form-group">
                                    <label>Matériaux (séparés par virgule)</label>
                                    <input type="text" name="materials" class="form-control" 
                                           value="{{ old('materials') }}" placeholder="bois, cuir, perles...">
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Largeur (cm)</label>
                                            <input type="number" name="width" class="form-control" 
                                                   value="{{ old('width') }}" step="0.1">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Hauteur (cm)</label>
                                            <input type="number" name="height" class="form-control" 
                                                   value="{{ old('height') }}" step="0.1">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Profondeur (cm)</label>
                                            <input type="number" name="depth" class="form-control" 
                                                   value="{{ old('depth') }}" step="0.1">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Poids (kg)</label>
                                            <input type="number" name="weight" class="form-control" 
                                                   value="{{ old('weight') }}" step="0.1">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" name="featured" class="custom-switch-input" {{ old('featured') ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Produit vedette</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Images --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Images du produit</label>
                                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                                    <small class="form-text text-muted">Vous pouvez sélectionner plusieurs images. La première sera l'image principale.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>
@endpush
