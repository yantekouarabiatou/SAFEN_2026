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

                                    {{-- Bouton Anansi --}}
                                    <div class="anansi-generate-bar">
                                        <span class="anansi-hint">🕷️ Laisse Anansi rédiger la description à ta place</span>
                                        <button type="button" class="anansi-trigger-btn" id="anansiTrigger">
                                            Générer avec Anansi
                                        </button>
                                    </div>

                                    {{-- Panneau Anansi inline --}}
                                    <div id="anansiPanel" class="anansi-inline-panel" style="display:none;">
                                        <div class="anansi-inline-header">
                                            <span>🕷️ <strong>Anansi</strong> — Décris ton produit</span>
                                            <button type="button" onclick="closeAnansiPanel()" class="anansi-close-inline">✕</button>
                                        </div>
                                        <div class="anansi-inline-body">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <input type="text" id="ai_name" class="anansi-field" placeholder="Nom du produit">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" id="ai_category" class="anansi-field" placeholder="Catégorie (masque, tissu, bijou...)">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" id="ai_materials" class="anansi-field" placeholder="Matériaux (bois, bronze, perles...)">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" id="ai_origin" class="anansi-field" placeholder="Origine ethnique (Fon, Yoruba, Bariba...)">
                                                </div>
                                                <div class="col-12">
                                                    <select id="ai_language" class="anansi-field">
                                                        <option value="fr">🇫🇷 Description en français</option>
                                                        <option value="en">🇬🇧 Description en anglais</option>
                                                        <option value="fon">🇧🇯 Français + mots Fon</option>
                                                        <option value="yoruba">🌍 Français + mots Yoruba</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="button" id="anansiGenerateBtn" onclick="generateWithAnansi()" class="anansi-generate-submit">
                                                <span id="aiBtnText">🕷️ Tisser la description</span>
                                            </button>
                                            <div id="anansiResult" class="anansi-result" style="display:none;">
                                                <div class="anansi-result-text" id="anansiResultText"></div>
                                                <div class="d-flex gap-2 mt-2">
                                                    <button type="button" onclick="useAnansiText()" class="anansi-use-btn">
                                                        ✅ Utiliser cette description
                                                    </button>
                                                    <button type="button" onclick="generateWithAnansi()" class="anansi-retry-btn">
                                                        🔄 Regénérer
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="anansiLoader" style="display:none;text-align:center;padding:16px;">
                                                <div style="font-size:28px;animation:spin 2s linear infinite;display:inline-block;">🕷️</div>
                                                <p style="font-size:.8rem;color:#6b7280;margin-top:6px;">Anansi tisse ta description...</p>
                                            </div>
                                        </div>
                                    </div>

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

@push('styles')
<style>
/* Barre Anansi au-dessus du textarea */
.anansi-generate-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
    border: 1.5px solid #bbf7d0;
    border-radius: 10px 10px 0 0;
    padding: 7px 12px;
    margin-bottom: 0;
}
.anansi-hint {
    font-size: .8rem;
    color: #166534;
    font-weight: 500;
}
.anansi-trigger-btn {
    background: linear-gradient(135deg, #005c38, #008751);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 5px 14px;
    font-size: .8rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
}
.anansi-trigger-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,135,81,.35);
}

/* Panneau inline Anansi */
.anansi-inline-panel {
    border: 1.5px solid #bbf7d0;
    border-top: none;
    border-radius: 0 0 10px 10px;
    background: white;
    overflow: hidden;
    margin-bottom: 8px;
}
.anansi-inline-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: linear-gradient(135deg, #005c38, #008751);
    color: white;
    font-size: .85rem;
}
.anansi-close-inline {
    background: none;
    border: none;
    color: rgba(255,255,255,.7);
    cursor: pointer;
    font-size: 14px;
    padding: 0 4px;
    line-height: 1;
}
.anansi-close-inline:hover { color: white; }
.anansi-inline-body {
    padding: 14px;
}
.anansi-field {
    width: 100%;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    padding: 7px 10px;
    font-size: .82rem;
    color: #1f2937;
    outline: none;
    transition: border-color .2s;
    background: #f9fafb;
}
.anansi-field:focus {
    border-color: #008751;
    box-shadow: 0 0 0 3px rgba(0,135,81,.1);
    background: white;
}
.anansi-generate-submit {
    width: 100%;
    background: linear-gradient(135deg, #005c38, #008751);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 10px;
    font-size: .88rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 10px;
    transition: all .2s;
}
.anansi-generate-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 16px rgba(0,135,81,.4);
}
.anansi-result {
    margin-top: 12px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 12px;
}
.anansi-result-text {
    font-size: .84rem;
    line-height: 1.65;
    color: #1f2937;
    white-space: pre-wrap;
}
.anansi-use-btn {
    background: #008751;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: .8rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s;
}
.anansi-use-btn:hover { background: #005c38; }
.anansi-retry-btn {
    background: white;
    color: #008751;
    border: 1.5px solid #008751;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
}
.anansi-retry-btn:hover { background: #f0fdf4; }

@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('anansiTrigger').addEventListener('click', function() {
    const panel = document.getElementById('anansiPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';

    // Pré-remplir depuis les champs du formulaire
    if (panel.style.display === 'block') {
        const nameVal = document.getElementById('name')?.value;
        const matVal  = document.getElementById('material')?.value;
        const catEl   = document.getElementById('category');
        const catVal  = catEl?.options[catEl?.selectedIndex]?.text;

        if (nameVal) document.getElementById('ai_name').value = nameVal;
        if (matVal)  document.getElementById('ai_materials').value = matVal;
        if (catVal && catVal !== '-- Sélectionner --') document.getElementById('ai_category').value = catVal;
    }
});

function closeAnansiPanel() {
    document.getElementById('anansiPanel').style.display = 'none';
}

async function generateWithAnansi() {
    const btn     = document.getElementById('anansiGenerateBtn');
    const loader  = document.getElementById('anansiLoader');
    const result  = document.getElementById('anansiResult');
    const resultText = document.getElementById('anansiResultText');

    btn.style.display    = 'none';
    loader.style.display = 'block';
    result.style.display = 'none';

    const payload = {
        type:          'product',
        name:          document.getElementById('ai_name').value,
        category:      document.getElementById('ai_category').value,
        materials:     document.getElementById('ai_materials').value,
        ethnic_origin: document.getElementById('ai_origin').value,
        language:      document.getElementById('ai_language').value,
    };

    try {
        const response = await fetch('{{ route("anansi.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();
        resultText.textContent = data.text || 'Désolé, impossible de générer.';
        result.style.display = 'block';
    } catch(e) {
        resultText.textContent = 'Erreur de connexion. Réessaie dans un instant.';
        result.style.display = 'block';
    } finally {
        btn.style.display    = 'block';
        loader.style.display = 'none';
    }
}

function useAnansiText() {
    const text = document.getElementById('anansiResultText').textContent;
    document.getElementById('description').value = text;
    closeAnansiPanel();
    document.getElementById('description').scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>
@endpush

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