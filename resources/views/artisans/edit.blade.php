@extends('layouts.admin')

@section('title', 'Modifier mon profil artisan')

@section('content')
<div class="section-header">
    <h1>Modifier mon profil artisan</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard.artisan') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Modifier profil</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Informations du profil</h4>
                </div>
                <form action="{{ route('artisans.update', $artisan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="business_name">Nom de l'entreprise / Nom commercial <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                           id="business_name" name="business_name" 
                                           value="{{ old('business_name', $artisan->business_name) }}" 
                                           placeholder="Ex: Atelier de sculpture Adjovi" required>
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="craft">Métier / Spécialité <span class="text-danger">*</span></label>
                                            <select class="form-control @error('craft') is-invalid @enderror" 
                                                    id="craft" name="craft" required>
                                                <option value="">-- Sélectionner --</option>
                                                <option value="tisserand" {{ old('craft', $artisan->craft) == 'tisserand' ? 'selected' : '' }}>Tisserand</option>
                                                <option value="sculpteur" {{ old('craft', $artisan->craft) == 'sculpteur' ? 'selected' : '' }}>Sculpteur</option>
                                                <option value="potier" {{ old('craft', $artisan->craft) == 'potier' ? 'selected' : '' }}>Potier</option>
                                                <option value="forgeron" {{ old('craft', $artisan->craft) == 'forgeron' ? 'selected' : '' }}>Forgeron</option>
                                                <option value="couturier" {{ old('craft', $artisan->craft) == 'couturier' ? 'selected' : '' }}>Couturier traditionnel</option>
                                                <option value="menuisier" {{ old('craft', $artisan->craft) == 'menuisier' ? 'selected' : '' }}>Menuisier</option>
                                                <option value="bijoutier" {{ old('craft', $artisan->craft) == 'bijoutier' ? 'selected' : '' }}>Bijoutier</option>
                                                <option value="tanneur" {{ old('craft', $artisan->craft) == 'tanneur' ? 'selected' : '' }}>Tanneur</option>
                                                <option value="musicien" {{ old('craft', $artisan->craft) == 'musicien' ? 'selected' : '' }}>Musicien traditionnel</option>
                                                <option value="autre" {{ old('craft', $artisan->craft) == 'autre' ? 'selected' : '' }}>Autre</option>
                                            </select>
                                            @error('craft')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="years_experience">Années d'expérience</label>
                                            <input type="number" class="form-control @error('years_experience') is-invalid @enderror" 
                                                   id="years_experience" name="years_experience" 
                                                   value="{{ old('years_experience', $artisan->years_experience) }}" 
                                                   placeholder="Ex: 15" min="0">
                                            @error('years_experience')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="bio">Biographie / Présentation</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror" 
                                              id="bio" name="bio" rows="5" 
                                              placeholder="Présentez votre parcours, vos techniques, vos spécialités...">{{ old('bio', $artisan->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city">Ville <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                                   id="city" name="city" 
                                                   value="{{ old('city', $artisan->city) }}" 
                                                   placeholder="Ex: Cotonou" required>
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="neighborhood">Quartier</label>
                                            <input type="text" class="form-control @error('neighborhood') is-invalid @enderror" 
                                                   id="neighborhood" name="neighborhood" 
                                                   value="{{ old('neighborhood', $artisan->neighborhood) }}" 
                                                   placeholder="Ex: Jonquet">
                                            @error('neighborhood')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="whatsapp">WhatsApp <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" 
                                                   id="whatsapp" name="whatsapp" 
                                                   value="{{ old('whatsapp', $artisan->whatsapp) }}" 
                                                   placeholder="+229 XX XX XX XX" required>
                                            @error('whatsapp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Téléphone</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" name="phone" 
                                                   value="{{ old('phone', $artisan->phone) }}" 
                                                   placeholder="+229 XX XX XX XX">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pricing_info">Informations tarifaires</label>
                                    <textarea class="form-control @error('pricing_info') is-invalid @enderror" 
                                              id="pricing_info" name="pricing_info" rows="3" 
                                              placeholder="Ex: À partir de 10 000 FCFA">{{ old('pricing_info', $artisan->pricing_info) }}</textarea>
                                    @error('pricing_info')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Langues parlées</label>
                                    <div class="row">
                                        @php
                                            $spokenLanguages = is_array($artisan->languages_spoken) 
                                                ? $artisan->languages_spoken 
                                                : json_decode($artisan->languages_spoken ?? '[]', true);
                                        @endphp
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="lang_fon" 
                                                       name="languages_spoken[]" value="Fon" 
                                                       {{ in_array('Fon', $spokenLanguages) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="lang_fon">Fon</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="lang_francais" 
                                                       name="languages_spoken[]" value="Français" 
                                                       {{ in_array('Français', $spokenLanguages) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="lang_francais">Français</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="lang_yoruba" 
                                                       name="languages_spoken[]" value="Yoruba" 
                                                       {{ in_array('Yoruba', $spokenLanguages) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="lang_yoruba">Yoruba</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="lang_bariba" 
                                                       name="languages_spoken[]" value="Bariba" 
                                                       {{ in_array('Bariba', $spokenLanguages) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="lang_bariba">Bariba</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Photos actuelles</label>
                                    <div class="row">
                                        @foreach($artisan->photos as $photo)
                                            <div class="col-6 mb-2">
                                                <img src="{{ asset('storage/' . $photo->photo_url) }}" 
                                                     alt="Photo" 
                                                     class="img-fluid rounded">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="photos">Ajouter de nouvelles photos</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('photos.*') is-invalid @enderror" 
                                               id="photos" name="photos[]" accept="image/*" multiple>
                                        <label class="custom-file-label" for="photos">Choisir des images</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Maximum 5 photos supplémentaires. Formats: JPG, PNG, WEBP
                                    </small>
                                    @error('photos.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    
                                    <div id="image-preview" class="mt-3 row"></div>
                                </div>

                                <div class="card">
                                    <div class="card-body bg-light">
                                        <h6 class="card-title"><i class="fas fa-info-circle"></i> Conseils</h6>
                                        <ul class="mb-0 pl-3">
                                            <li>Soyez précis dans votre description</li>
                                            <li>Ajoutez des photos de qualité</li>
                                            <li>Mettez à jour vos tarifs régulièrement</li>
                                            <li>Répondez rapidement aux messages</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('artisans.show', $artisan) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour le profil
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
document.getElementById('photos').addEventListener('change', function(e) {
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
