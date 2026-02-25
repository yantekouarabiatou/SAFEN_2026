@extends('layouts.admin')

@section('title', 'Modifier mon Profil Artisan')

@section('content')
<section class="section">
    @if(isset($artisan))
    <div class="section-body">
        <!-- Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm" style="border-left: 4px solid #28a745;">
                    <div class="card-body py-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('artisan.profile.show', $artisan->id) }}">Mon Profil Artisan</a></li>
                                <li class="breadcrumb-item active">Modifier</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Colonne gauche: Photo et infos -->
            <div class="col-md-4">
                <!-- Carte Profil -->
                <div class="card shadow-sm" style="border-top: 3px solid #28a745;">
                    <div class="card-header bg-success text-white">
                        <h4><i class="fas fa-user-tie"></i> Photo de Profil</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($artisan->user->photo)
                                <img src="{{ asset('storage/' . $artisan->user->photo) }}"
                                     alt="Photo profil"
                                     class="img-fluid rounded-circle border border-success"
                                     style="width: 180px; height: 180px; object-fit: cover;">
                            @else
                                <div class="avatar-placeholder rounded-circle d-inline-flex align-items-center justify-content-center border border-success"
                                     style="width: 180px; height: 180px; background: #f8f9fa; font-size: 60px;">
                                    <i class="fas fa-user-tie text-success"></i>
                                </div>
                            @endif
                        </div>
                        <h5 class="mb-1 text-success">{{ $artisan->user->prenom }} {{ $artisan->user->nom }}</h5>
                        <p class="text-muted mb-0">{{ $artisan->craft_label ?? 'Artisan' }}</p>
                        <div class="mt-3">
                            <span class="badge badge-{{ $artisan->verified ? 'success' : 'warning' }}">
                                {{ $artisan->verified ? 'Vérifié' : 'En attente' }}
                            </span>
                            <span class="badge badge-{{ $artisan->featured ? 'warning' : 'secondary' }}">
                                {{ $artisan->featured ? 'Mise en avant' : 'Standard' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Informations de connexion -->
                <div class="card shadow-sm mt-4" style="border-top: 3px solid #ffc107;">
                    <div class="card-header bg-warning text-white">
                        <h4><i class="fas fa-lock"></i> Sécurité</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="text-success"><i class="fas fa-envelope mr-2"></i>Email</label>
                            <input type="email" class="form-control border-success" value="{{ $artisan->user->email }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="text-success"><i class="fas fa-user mr-2"></i>Nom d'utilisateur</label>
                            <input type="text" class="form-control border-success" value="{{ $artisan->user->username }}" readonly>
                        </div>
                        <button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#changePasswordModal">
                            <i class="fas fa-key"></i> Changer le mot de passe
                        </button>
                    </div>
                </div>

                <!-- Statistiques rapides -->
                <div class="card shadow-sm mt-4" style="border-top: 3px solid #dc3545;">
                    <div class="card-header bg-danger text-white">
                        <h4><i class="fas fa-chart-line"></i> Aperçu</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-success">Note moyenne</span>
                            <span class="font-weight-bold">{{ number_format($artisan->rating_avg, 1) }}/5</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-warning">Vues</span>
                            <span class="font-weight-bold">{{ $artisan->views ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-danger">Produits</span>
                            <span class="font-weight-bold">{{ $artisan->products_count ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-success">Commandes</span>
                            <span class="font-weight-bold">{{ $artisan->orders_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite: Formulaire de modification -->
            <div class="col-md-8">
                <!-- Formulaire de modification -->
                <div class="card shadow-sm" style="border-top: 3px solid #28a745;">
                    <div class="card-header bg-success text-white">
                        <h4><i class="fas fa-edit"></i> Modifier le Profil Artisan</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('artisan.profile.update', $artisan->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Informations personnelles -->
                            <div class="mb-4">
                                <h5 class="text-success border-bottom pb-2"><i class="fas fa-user mr-2"></i>Informations Personnelles</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-success">Nom <span class="text-danger">*</span></label>
                                            <input type="text" name="nom"
                                                   class="form-control border-success @error('nom') is-invalid @enderror"
                                                   value="{{ old('nom', $artisan->user->nom) }}"
                                                   placeholder="Votre nom" required>
                                            @error('nom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-success">Prénom <span class="text-danger">*</span></label>
                                            <input type="text" name="prenom"
                                                   class="form-control border-success @error('prenom') is-invalid @enderror"
                                                   value="{{ old('prenom', $artisan->user->prenom) }}"
                                                   placeholder="Votre prénom" required>
                                            @error('prenom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-success"><i class="fas fa-phone mr-2"></i>Téléphone</label>
                                            <input type="text" name="telephone"
                                                   class="form-control border-success @error('telephone') is-invalid @enderror"
                                                   value="{{ old('telephone', $artisan->user->telephone) }}"
                                                   placeholder="Ex: +225 01 23 45 67 89">
                                            @error('telephone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-success"><i class="fas fa-language mr-2"></i>Langues parlées</label>
                                            <select name="languages_spoken[]" 
                                                    class="form-control border-success select2-multiple @error('languages_spoken') is-invalid @enderror"
                                                    multiple>
                                                @php
                                                    $languages = ['français', 'anglais', 'arabe', 'dioula', 'bété', 'baoulé', 'sénoufo', 'malinké', 'yacouba', 'autres'];
                                                    $selectedLangs = old('languages_spoken', $artisan->languages_spoken ?? []);
                                                @endphp
                                                @foreach($languages as $lang)
                                                    <option value="{{ $lang }}" 
                                                            {{ in_array($lang, (array)$selectedLangs) ? 'selected' : '' }}>
                                                        {{ ucfirst($lang) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('languages_spoken')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations de l'entreprise -->
                            <div class="mb-4">
                                <h5 class="text-warning border-bottom pb-2"><i class="fas fa-store mr-2"></i>Informations de l'Entreprise</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-warning">Nom de l'entreprise <span class="text-danger">*</span></label>
                                            <input type="text" name="business_name"
                                                   class="form-control border-warning @error('business_name') is-invalid @enderror"
                                                   value="{{ old('business_name', $artisan->business_name) }}"
                                                   placeholder="Nom de votre entreprise/atelier" required>
                                            @error('business_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-warning">Métier <span class="text-danger">*</span></label>
                                            <select name="craft" class="form-control border-warning select2 @error('craft') is-invalid @enderror" required>
                                                <option value="">Sélectionner votre métier...</option>
                                                @foreach([
                                                    'tisserand' => 'Tisserand',
                                                    'sculpteur' => 'Sculpteur',
                                                    'potier' => 'Potier',
                                                    'forgeron' => 'Forgeron',
                                                    'couturier' => 'Couturier traditionnel',
                                                    'mecanicien' => 'Mécanicien',
                                                    'vulcanisateur' => 'Vulcanisateur',
                                                    'coiffeur' => 'Coiffeur',
                                                    'menuisier' => 'Menuisier',
                                                    'bijoutier' => 'Bijoutier',
                                                    'tanneur' => 'Tanneur',
                                                    'corroyeur' => 'Corroyeur',
                                                    'musicien' => 'Musicien traditionnel',
                                                    'autre' => 'Autre artisan'
                                                ] as $value => $label)
                                                    <option value="{{ $value }}" 
                                                            {{ old('craft', $artisan->craft) == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('craft')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-warning">Années d'expérience</label>
                                            <input type="number" name="years_experience"
                                                   class="form-control border-warning @error('years_experience') is-invalid @enderror"
                                                   value="{{ old('years_experience', $artisan->years_experience) }}"
                                                   min="0" max="70"
                                                   placeholder="Ex: 10">
                                            @error('years_experience')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-warning">Informations de tarification</label>
                                            <textarea name="pricing_info" 
                                                      class="form-control border-warning @error('pricing_info') is-invalid @enderror"
                                                      rows="2"
                                                      placeholder="Ex: Prix à partir de 5.000 FCFA, négociable...">{{ old('pricing_info', $artisan->pricing_info) }}</textarea>
                                            @error('pricing_info')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="text-warning">Biographie</label>
                                    <textarea name="bio" 
                                              class="form-control border-warning @error('bio') is-invalid @enderror"
                                              rows="4"
                                              placeholder="Décrivez votre parcours, votre passion, votre expertise...">{{ old('bio', $artisan->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Localisation -->
                            <div class="mb-4">
                                <h5 class="text-danger border-bottom pb-2"><i class="fas fa-map-marker-alt mr-2"></i>Localisation</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-danger">Ville <span class="text-danger">*</span></label>
                                            <input type="text" name="city"
                                                   class="form-control border-danger @error('city') is-invalid @enderror"
                                                   value="{{ old('city', $artisan->city) }}"
                                                   placeholder="Ex: Abidjan" required>
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-danger">Quartier</label>
                                            <input type="text" name="neighborhood"
                                                   class="form-control border-danger @error('neighborhood') is-invalid @enderror"
                                                   value="{{ old('neighborhood', $artisan->neighborhood) }}"
                                                   placeholder="Ex: Cocody, Plateau...">
                                            @error('neighborhood')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-danger"><i class="fab fa-whatsapp mr-2"></i>WhatsApp</label>
                                            <input type="text" name="whatsapp"
                                                   class="form-control border-danger @error('whatsapp') is-invalid @enderror"
                                                   value="{{ old('whatsapp', $artisan->whatsapp) }}"
                                                   placeholder="Numéro WhatsApp">
                                            @error('whatsapp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-danger"><i class="fas fa-phone mr-2"></i>Téléphone secondaire</label>
                                            <input type="text" name="phone"
                                                   class="form-control border-danger @error('phone') is-invalid @enderror"
                                                   value="{{ old('phone', $artisan->phone) }}"
                                                   placeholder="Autre numéro de contact">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-danger">Latitude</label>
                                            <input type="text" name="latitude"
                                                   class="form-control border-danger @error('latitude') is-invalid @enderror"
                                                   value="{{ old('latitude', $artisan->latitude) }}"
                                                   placeholder="Coordonnée GPS">
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-danger">Longitude</label>
                                            <input type="text" name="longitude"
                                                   class="form-control border-danger @error('longitude') is-invalid @enderror"
                                                   value="{{ old('longitude', $artisan->longitude) }}"
                                                   placeholder="Coordonnée GPS">
                                            @error('longitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Photo de profil -->
                            <div class="mb-4">
                                <h5 class="text-success border-bottom pb-2"><i class="fas fa-images mr-2"></i>Médias</h5>
                                <div class="form-group">
                                    <label class="text-success">Photo de profil</label>
                                    <div class="custom-file">
                                        <input type="file" name="photo"
                                               class="custom-file-input @error('photo') is-invalid @enderror"
                                               id="photoUpload"
                                               accept="image/jpeg,image/png,image/jpg">
                                        <label class="custom-file-label border-success" for="photoUpload">
                                            {{ $artisan->user->photo ? basename($artisan->user->photo) : 'Choisir une image...' }}
                                        </label>
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Formats acceptés: JPG, PNG. Taille max: 2MB</small>
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="mb-4">
                                <h5 class="text-warning border-bottom pb-2"><i class="fas fa-cog mr-2"></i>Options</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="verified" 
                                                   class="form-check-input" 
                                                   id="verified"
                                                   {{ old('verified', $artisan->verified) ? 'checked' : '' }}>
                                            <label class="form-check-label text-warning" for="verified">
                                                <i class="fas fa-check-circle mr-1"></i> Profil vérifié
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="featured" 
                                                   class="form-check-input" 
                                                   id="featured"
                                                   {{ old('featured', $artisan->featured) ? 'checked' : '' }}>
                                            <label class="form-check-label text-warning" for="featured">
                                                <i class="fas fa-star mr-1"></i> Mise en avant
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="visible" 
                                                   class="form-check-input" 
                                                   id="visible"
                                                   {{ old('visible', $artisan->visible ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label text-warning" for="visible">
                                                <i class="fas fa-eye mr-1"></i> Visible publiquement
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="text-right mt-4 pt-4 border-top">
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                                <a href="{{ route('artisan.profile.show', $artisan->id) }}" class="btn btn-outline-warning btn-lg ml-2">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-lg ml-2" data-toggle="modal" data-target="#deleteModal">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal changement de mot de passe -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-top: 3px solid #ffc107;">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-key mr-2"></i> Changer le mot de passe</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('artisan.profile.change-password') }}" method="POST" id="changePasswordForm">
                @csrf
                <div class="modal-body">
                    <!-- Formulaire de changement de mot de passe (identique à celui des utilisateurs) -->
                    <!-- Vous pouvez réutiliser le même code HTML du modal de changement de mot de passe -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-1"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-top: 3px solid #dc3545;">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i> Confirmation</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                </p>
                <h5 class="text-center mb-3">Êtes-vous sûr de vouloir supprimer votre profil artisan ?</h5>
                <p class="text-muted text-center">
                    Cette action est irréversible. Tous vos produits, avis et données seront supprimés.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Annuler
                </button>
                <form action="{{ route('artisan.profile.destroy', $artisan->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
    @else
        @include('profile.edit_user')
    @endif
</section>
@endsection

@section('styles')
<style>
    .border-success { border-color: #28a745 !important; }
    .border-warning { border-color: #ffc107 !important; }
    .border-danger { border-color: #dc3545 !important; }
    
    .text-success { color: #28a745 !important; }
    .text-warning { color: #ffc107 !important; }
    .text-danger { color: #dc3545 !important; }
    
    .card-header.bg-success { background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important; }
    .card-header.bg-warning { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important; }
    .card-header.bg-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important; }
    
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        border-color: #ffc107 !important;
    }
    
    .select2-container--default .select2-selection--multiple {
        border-color: #28a745 !important;
    }
    
    .custom-file-label::after {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
    }
    
    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        border: none;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        border: none;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
    }
    
    .badge-success { background-color: #28a745 !important; }
    .badge-warning { background-color: #ffc107 !important; color: #212529; }
    .badge-danger { background-color: #dc3545 !important; }
    
    .avatar-placeholder {
        transition: all 0.3s;
    }
    
    .avatar-placeholder:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialisation Select2
        $('.select2').select2({
            placeholder: "Sélectionner...",
            allowClear: true,
            theme: "classic"
        });
        
        $('.select2-multiple').select2({
            placeholder: "Sélectionner une ou plusieurs langues...",
            allowClear: true,
            theme: "classic"
        });
        
        // Affichage du nom du fichier photo
        $('#photoUpload').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choisir une image...');
        });
        
        // Validation du formulaire
        $('form').on('submit', function(e) {
            if (!$(this).attr('id') || $(this).attr('id') !== 'changePasswordForm') {
                $('button[type="submit"]').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...');
            }
        });
        
        // Animation des cartes
        $('.card').hover(
            function() {
                $(this).addClass('shadow-lg');
            },
            function() {
                $(this).removeClass('shadow-lg');
            }
        );
        
        // Initialisation des tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection