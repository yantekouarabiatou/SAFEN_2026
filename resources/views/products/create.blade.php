@extends('layouts.admin')

@section('title', 'Ajouter un produit')

@section('content')
<div class="section-header">
    <h1>Ajouter un produit</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Mes Produits</a></div>
        <div class="breadcrumb-item">Ajouter</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Informations du produit</h4>
                </div>
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    <strong>Erreur!</strong> Veuillez corriger les erreurs suivantes:
                                    <ul class="mb-0 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Nom du produit <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Ex: Statuette en bronze" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="5" 
                                              placeholder="Décrivez votre produit en détail..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category">Catégorie</label>
                                            <select class="form-control @error('category') is-invalid @enderror" 
                                                    id="category" name="category">
                                                <option value="">-- Sélectionner --</option>
                                                <option value="sculpture" {{ old('category') == 'sculpture' ? 'selected' : '' }}>Sculpture</option>
                                                <option value="tissage" {{ old('category') == 'tissage' ? 'selected' : '' }}>Tissage</option>
                                                <option value="poterie" {{ old('category') == 'poterie' ? 'selected' : '' }}>Poterie</option>
                                                <option value="bijoux" {{ old('category') == 'bijoux' ? 'selected' : '' }}>Bijoux</option>
                                                <option value="vetement" {{ old('category') == 'vetement' ? 'selected' : '' }}>Vêtement</option>
                                                <option value="decoration" {{ old('category') == 'decoration' ? 'selected' : '' }}>Décoration</option>
                                                <option value="maroquinerie" {{ old('category') == 'maroquinerie' ? 'selected' : '' }}>Maroquinerie</option>
                                                <option value="instrument" {{ old('category') == 'instrument' ? 'selected' : '' }}>Instrument de musique</option>
                                                <option value="autre" {{ old('category') == 'autre' ? 'selected' : '' }}>Autre</option>
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="material">Matériau principal</label>
                                            <input type="text" class="form-control @error('material') is-invalid @enderror" 
                                                   id="material" name="material" value="{{ old('material') }}" 
                                                   placeholder="Ex: Bronze, Bois, Tissu...">
                                            @error('material')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="price">Prix (FCFA) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price') }}" 
                                                   placeholder="10000" required min="0">
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="stock">Stock disponible</label>
                                            <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                                   id="stock" name="stock" value="{{ old('stock', 1) }}" 
                                                   placeholder="1" min="0">
                                            @error('stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="production_time">Délai de production</label>
                                            <input type="text" class="form-control @error('production_time') is-invalid @enderror" 
                                                   id="production_time" name="production_time" value="{{ old('production_time') }}" 
                                                   placeholder="Ex: 2 semaines">
                                            @error('production_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dimensions">Dimensions</label>
                                            <input type="text" class="form-control @error('dimensions') is-invalid @enderror" 
                                                   id="dimensions" name="dimensions" value="{{ old('dimensions') }}" 
                                                   placeholder="Ex: 30x20x10 cm">
                                            @error('dimensions')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="weight">Poids</label>
                                            <input type="text" class="form-control @error('weight') is-invalid @enderror" 
                                                   id="weight" name="weight" value="{{ old('weight') }}" 
                                                   placeholder="Ex: 500g">
                                            @error('weight')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="color">Couleur(s)</label>
                                            <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                                   id="color" name="color" value="{{ old('color') }}" 
                                                   placeholder="Ex: Rouge, Noir">
                                            @error('color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="images">Images du produit <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('images.*') is-invalid @enderror" 
                                               id="images" name="images[]" accept="image/*" multiple required>
                                        <label class="custom-file-label" for="images">Choisir des images</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Vous pouvez sélectionner plusieurs images (max 5). Formats acceptés: JPG, PNG, WEBP
                                    </small>
                                    @error('images.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    
                                    <div id="image-preview" class="mt-3 row"></div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customizable" 
                                               name="customizable" value="1" {{ old('customizable') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customizable">
                                            Produit personnalisable
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="is_active" 
                                               name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Publier immédiatement
                                        </label>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body bg-light">
                                        <h6 class="card-title"><i class="fas fa-info-circle"></i> Conseils</h6>
                                        <ul class="mb-0 pl-3">
                                            <li>Utilisez des photos de haute qualité</li>
                                            <li>Soyez précis dans la description</li>
                                            <li>Indiquez les dimensions exactes</li>
                                            <li>Mentionnez le délai de fabrication</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="#" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer le produit
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
// Preview images
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    const files = Array.from(e.target.files);
    
    if (files.length > 5) {
        iziToast.warning({
            title: 'Attention',
            message: 'Vous ne pouvez sélectionner que 5 images maximum',
            position: 'topRight'
        });
        e.target.value = '';
        return;
    }
    
    files.forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'col-6 mb-2';
                div.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-fluid rounded" alt="Preview ${index + 1}">
                        <span class="badge badge-primary position-absolute" style="top: 5px; left: 5px;">
                            ${index + 1}
                        </span>
                    </div>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Update label
    const label = document.querySelector('.custom-file-label');
    label.textContent = files.length > 1 ? `${files.length} images sélectionnées` : files[0].name;
});
</script>
@endpush
