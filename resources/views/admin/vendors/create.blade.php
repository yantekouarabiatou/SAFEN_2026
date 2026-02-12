@extends('layouts.admin')

@section('title', isset($vendor) ? 'Modifier mon profil vendeur' : 'Créer mon profil vendeur')

@push('styles')
    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --benin-green: #009639;
            --benin-yellow: #FCD116;
            --benin-red: #E8112D;
            --dark-green: #006d2c;
            --soft-gray: #f5f7fa;
            --border-radius-card: 20px;
        }

        body {
            background-color: var(--soft-gray);
        }

        .card-modern {
            border: none;
            border-radius: var(--border-radius-card);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            overflow: hidden;
        }

        .card-modern:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }

        .card-header-modern {
            background: linear-gradient(135deg, var(--benin-green), var(--dark-green));
            color: white;
            padding: 1.2rem 1.8rem;
            border-bottom: none;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .card-header-modern i {
            margin-right: 10px;
            font-size: 1.4rem;
        }

        .btn-benin-primary {
            background: var(--benin-green);
            border: none;
            color: white;
            border-radius: 50px;
            padding: 0.7rem 2rem;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 4px 10px rgba(0, 150, 57, 0.2);
        }

        .btn-benin-primary:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 150, 57, 0.3);
            color: white;
        }

        .btn-benin-yellow {
            background: var(--benin-yellow);
            border: none;
            color: #2d2d2d;
            border-radius: 50px;
            padding: 0.7rem 2rem;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 4px 10px rgba(252, 209, 22, 0.2);
        }

        .btn-benin-yellow:hover {
            background: #e5bc0e;
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(252, 209, 22, 0.3);
        }

        .btn-benin-outline {
            background: transparent;
            border: 2px solid var(--benin-green);
            color: var(--benin-green);
            border-radius: 50px;
            padding: 0.6rem 1.8rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-benin-outline:hover {
            background: var(--benin-green);
            color: white;
        }

        .dish-card {
            background: white;
            border-radius: 16px;
            padding: 1.2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transition: all 0.2s;
            border-left: 5px solid var(--benin-green);
            margin-bottom: 1rem;
        }

        .dish-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-left-color: var(--benin-yellow);
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .required::after {
            content: " *";
            color: var(--benin-red);
            font-weight: bold;
        }

        .logo-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .logo-upload-area {
            border: 2px dashed #ced4da;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.2s;
            cursor: pointer;
        }

        .logo-upload-area:hover {
            border-color: var(--benin-green);
            background: #eaf6ec;
        }

        .logo-upload-area i {
            font-size: 3rem;
            color: var(--benin-green);
        }

        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 12px !important;
            border: 1px solid #e2e8f0;
            padding: 0.4rem;
        }

        .badge-benin {
            background: var(--benin-yellow);
            color: #2d2d2d;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .section-title-custom {
            position: relative;
            margin: 2rem 0 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .section-title-custom:before {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 60px;
            height: 4px;
            background: var(--benin-green);
            border-radius: 2px;
        }

        .animated {
            animation: fadeInUp 0.4s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="section-header">
        <h1 class="d-flex align-items-center">
            <i class="fas fa-store" style="color: var(--benin-green); font-size: 2rem; margin-right: 15px;"></i>
            <span>{{ isset($vendor) ? 'Modifier mon profil vendeur' : 'Devenir vendeur' }}</span>
            <small class="ml-3 text-muted"
                style="font-size: 1rem;">{{ isset($vendor) ? 'Mettez à jour vos informations' : 'Créez votre boutique en ligne' }}</small>
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i> Accueil</a></div>
            <div class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendeurs</a></div>
            <div class="breadcrumb-item active">{{ isset($vendor) ? 'Édition' : 'Création' }}</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                {{-- Carte principale --}}
                <div class="card card-modern animated">
                    <div class="card-header card-header-modern d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-store-alt"></i> Informations de l'établissement</h4>
                        @if(isset($vendor))
                            <span class="badge-benin"><i class="fas fa-check-circle"></i> Profil actif</span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <form
                            action="{{ isset($vendor) ? route('admin.vendors.update', $vendor) : route('admin.vendors.store') }}"
                            method="POST" enctype="multipart/form-data" id="vendorForm">
                            @csrf
                            @if(isset($vendor)) @method('PUT') @endif

                            <div class="row g-4">
                                {{-- Colonne gauche : Logo + upload --}}
                                <div class="col-lg-4 text-center">
                                    <div class="sticky-top" style="top: 20px; z-index: 10;">
                                        <div class="mb-4">
                                            @php
                                                $logoUrl = isset($vendor) && $vendor->logo ? Storage::url($vendor->logo) : null;
                                            @endphp
                                            <img id="logoPreview"
                                                src="{{ $logoUrl ?? asset('images/default-vendor-logo.png') }}"
                                                class="logo-preview" alt="Logo">
                                        </div>
                                        <div class="logo-upload-area"
                                            onclick="document.getElementById('logoInput').click();">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <h5 class="mt-3">Glissez-déposez ou cliquez</h5>
                                            <p class="text-muted small mb-0">PNG, JPG, GIF – Max 2 Mo</p>
                                            <input type="file" id="logoInput" name="logo" accept="image/*" class="d-none"
                                                onchange="previewLogo(event)">
                                        </div>
                                        @error('logo')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Colonne droite : champs --}}
                                <div class="col-lg-8">
                                    <div class="row g-4">
                                        {{-- Nom commercial --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label required">Nom commercial / Enseigne</label>
                                                <input type="text" name="name"
                                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                    value="{{ old('name', $vendor->name ?? '') }}"
                                                    placeholder="ex: Maquis du Centre" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Type d'activité --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label required">Type d'activité</label>
                                                <select name="type"
                                                    class="form-select form-select-lg select2 @error('type') is-invalid @enderror"
                                                    required>
                                                    <option value="">-- Sélectionnez --</option>
                                                    <option value="maquis" {{ old('type', $vendor->type ?? '') == 'maquis' ? 'selected' : '' }}>Maquis</option>
                                                    <option value="restaurant" {{ old('type', $vendor->type ?? '') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                                                    <option value="traiteur" {{ old('type', $vendor->type ?? '') == 'traiteur' ? 'selected' : '' }}>Traiteur</option>
                                                    <option value="artisan-vendeur" {{ old('type', $vendor->type ?? '') == 'artisan-vendeur' ? 'selected' : '' }}>Artisan vendeur</option>
                                                    <option value="autre" {{ old('type', $vendor->type ?? '') == 'autre' ? 'selected' : '' }}>Autre</option>
                                                </select>
                                                @error('type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Ville --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label required">Ville</label>
                                                <input type="text" name="city"
                                                    class="form-control form-control-lg @error('city') is-invalid @enderror"
                                                    value="{{ old('city', $vendor->city ?? '') }}" placeholder="ex: Cotonou"
                                                    required>
                                                @error('city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Téléphone / WhatsApp --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label required">Téléphone / WhatsApp</label>
                                                <input type="text" name="phone"
                                                    class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                                    value="{{ old('phone', $vendor->phone ?? Auth::user()->phone ?? '') }}"
                                                    placeholder="ex: +229 61 23 45 67" required>
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Adresse (facultatif) --}}
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label">Adresse complète</label>
                                                <input type="text" name="address"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    value="{{ old('address', $vendor->address ?? '') }}"
                                                    placeholder="Rue, quartier, point de repère…">
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Coordonnées GPS (facultatif) --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Latitude</label>
                                                <input type="text" name="latitude"
                                                    class="form-control @error('latitude') is-invalid @enderror"
                                                    value="{{ old('latitude', $vendor->latitude ?? '') }}"
                                                    placeholder="ex: 6.3701">
                                                @error('latitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Longitude</label>
                                                <input type="text" name="longitude"
                                                    class="form-control @error('longitude') is-invalid @enderror"
                                                    value="{{ old('longitude', $vendor->longitude ?? '') }}"
                                                    placeholder="ex: 2.3912">
                                                @error('longitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Horaires d'ouverture (facultatif) --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Horaires d'ouverture</label>
                                                <input type="text" name="opening_hours"
                                                    class="form-control @error('opening_hours') is-invalid @enderror"
                                                    value="{{ old('opening_hours', $vendor->opening_hours ?? '') }}"
                                                    placeholder="ex: Lun-Ven 08:00-22:00">
                                                @error('opening_hours')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- WhatsApp (distinct, facultatif) --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">WhatsApp (si différent)</label>
                                                <input type="text" name="whatsapp"
                                                    class="form-control @error('whatsapp') is-invalid @enderror"
                                                    value="{{ old('whatsapp', $vendor->whatsapp ?? '') }}"
                                                    placeholder="ex: +229 61 23 45 67">
                                                @error('whatsapp')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Description --}}
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label">Description de votre établissement</label>
                                                <textarea name="description"
                                                    class="form-control @error('description') is-invalid @enderror" rows="4"
                                                    placeholder="Présentez votre établissement, son ambiance, ses spécialités…">{{ old('description', $vendor->description ?? '') }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION : GESTION DES PLATS --}}
                            <hr class="my-5" style="border-top: 2px dashed #e2e8f0;">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="section-title-custom mb-0">
                                    <i class="fas fa-utensils" style="color: var(--benin-green);"></i> Mes plats
                                </h3>
                                <button type="button" class="btn btn-benin-yellow btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#createDishModal">
                                    <i class="fas fa-plus-circle me-2"></i> Ajouter un plat
                                </button>
                            </div>

                            {{-- Liste des plats existants --}}
                            <div id="dishesContainer" class="row g-4">
                                @forelse($vendor->dishes ?? [] as $dish)
                                    <div class="col-md-6 col-lg-4 dish-item" data-dish-id="{{ $dish->id }}">
                                        <div class="dish-card h-100">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="d-flex">
                                                    @if($dish->images->first())
                                                        <img src="{{ Storage::url($dish->images->first()->image_url) }}"
                                                            class="rounded-3 me-3"
                                                            style="width: 70px; height: 70px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center me-3"
                                                            style="width: 70px; height: 70px;">
                                                            <i class="fas fa-image fa-2x text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h5 class="fw-bold mb-1">{{ $dish->name }}</h5>
                                                        @if($dish->name_local)
                                                            <span class="badge bg-light text-dark">{{ $dish->name_local }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-dish"
                                                    data-dish-id="{{ $dish->id }}" data-dish-name="{{ $dish->name }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                            <div class="mt-3">
                                                <label class="small fw-bold text-muted">Prix (FCFA)</label>
                                                <div class="input-group">
                                                    <input type="number" name="prices[{{ $dish->id }}]"
                                                        class="form-control border-success"
                                                        value="{{ old("prices.{$dish->id}", $dish->pivot->price ?? '') }}"
                                                        placeholder="Prix" min="0" step="100">
                                                    <span class="input-group-text bg-white border-success">
                                                        <i class="fas fa-tag text-success"></i>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="dishes[]" value="{{ $dish->id }}">
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5" id="noDishesMessage">
                                        <div class="empty-state">
                                            <i class="fas fa-utensils fa-4x text-muted mb-4"></i>
                                            <h4 class="text-muted">Aucun plat enregistré</h4>
                                            <p class="text-muted">Commencez par ajouter votre premier plat !</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Bouton de soumission --}}
                            <div class="text-center mt-5 pt-4">
                                <button type="submit" class="btn btn-benin-primary btn-lg px-5 py-3">
                                    <i class="fas fa-save me-2"></i>
                                    {{ isset($vendor) ? 'Mettre à jour mon profil' : 'Créer mon profil vendeur' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== MODAL : CRÉER UN NOUVEAU PLAT ========== --}}
    <div class="modal fade" id="createDishModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header card-header-modern">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-utensils me-2"></i> Ajouter un nouveau plat
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fermer"></button>
                </div>
                <form id="createDishForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required">Nom du plat</label>
                                    <input type="text" name="name" id="dishName" class="form-control form-control-lg"
                                        placeholder="ex: Pâte rouge" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nom local (facultatif)</label>
                                    <input type="text" name="name_local" id="dishNameLocal" class="form-control"
                                        placeholder="ex: Amiwo">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required">Catégorie</label>
                                    <select name="category" id="dishCategory" class="form-select select2-modal" required>
                                        @foreach(\App\Models\Dish::$categoryLabels as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required">Prix (FCFA)</label>
                                    <input type="number" name="price" id="dishPrice" class="form-control form-control-lg"
                                        min="0" step="100" placeholder="5000" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Origine ethnique</label>
                                    <input type="text" name="ethnic_origin" class="form-control" placeholder="ex: Fon">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Région</label>
                                    <input type="text" name="region" class="form-control" placeholder="ex: Atlantique">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label required">Ingrédients</label>
                                    <input type="text" name="ingredients" id="dishIngredients" class="form-control"
                                        placeholder="farine de maïs, tomate, oignon, piment" required>
                                    <small class="text-muted">Séparez les ingrédients par des virgules</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" id="dishDescription" class="form-control" rows="3"
                                        placeholder="Description courte..."></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Image du plat</label>
                                    <input type="file" name="image" id="dishImage" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-benin-primary">
                            <i class="fas fa-plus-circle me-2"></i> Ajouter ce plat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ========== MODAL : CONFIRMATION SUPPRESSION PLAT ========== --}}
    <div class="modal fade" id="deleteDishModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Confirmer la suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="fas fa-utensils fa-4x text-danger mb-4"></i>
                    <h5>Voulez-vous retirer <strong id="deleteDishName"></strong> de votre catalogue ?</h5>
                    <p class="text-muted mt-3">Cette action est irréversible.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteDish">
                        <i class="fas fa-trash-alt me-2"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // --- Initialisation Select2 ---
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Sélectionnez --',
                allowClear: true
            });

            // Pour le modal, on réinitialise Select2 après ouverture
            $('#createDishModal').on('shown.bs.modal', function () {
                $('.select2-modal').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $('#createDishModal'),
                    placeholder: '-- Choisissez --'
                });
            });

            // --- Prévisualisation du logo ---
            window.previewLogo = function (event) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('logoPreview').src = e.target.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            };

            // --- Ajout d'un plat via AJAX ---
            $('#createDishForm').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                // Ajouter le prix (obligatoire)
                if (!$('#dishPrice').val()) {
                    Swal.fire('Erreur', 'Le prix est obligatoire.', 'error');
                    return;
                }

                // Désactiver le bouton pendant l'envoi
                let submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Ajout...');

                $.ajax({
                    url: '{{ route("admin.vendor.dishes.quick-store") }}', // Route à créer
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            // Ajouter le plat à la liste
                            appendDishToContainer(response.dish, response.price);
                            // Fermer le modal et reset
                            $('#createDishModal').modal('hide');
                            $('#createDishForm')[0].reset();
                            $('.select2-modal').val(null).trigger('change');
                            // Notification
                            Swal.fire({
                                icon: 'success',
                                title: 'Plat ajouté !',
                                text: response.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            // Masquer le message "aucun plat"
                            $('#noDishesMessage').fadeOut();
                        }
                    },
                    error: function (xhr) {
                        let errorMsg = 'Une erreur est survenue.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire('Erreur', errorMsg, 'error');
                    },
                    complete: function () {
                        submitBtn.prop('disabled', false).html('<i class="fas fa-plus-circle me-2"></i> Ajouter ce plat');
                    }
                });
            });

            // Fonction pour ajouter un plat dans le container
            function appendDishToContainer(dish, price) {
                let imageHtml = dish.image
                    ? `<img src="${dish.image_url}" class="rounded-3 me-3" style="width:70px;height:70px;object-fit:cover;">`
                    : `<div class="bg-light rounded-3 d-flex align-items-center justify-content-center me-3" style="width:70px;height:70px;"><i class="fas fa-image fa-2x text-muted"></i></div>`;

                let dishHtml = `
                        <div class="col-md-6 col-lg-4 dish-item" data-dish-id="${dish.id}">
                            <div class="dish-card h-100">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex">
                                        ${imageHtml}
                                        <div>
                                            <h5 class="fw-bold mb-1">${dish.name}</h5>
                                            ${dish.name_local ? `<span class="badge bg-light text-dark">${dish.name_local}</span>` : ''}
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-dish" data-dish-id="${dish.id}" data-dish-name="${dish.name}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                <div class="mt-3">
                                    <label class="small fw-bold text-muted">Prix (FCFA)</label>
                                    <div class="input-group">
                                        <input type="number" name="prices[${dish.id}]" class="form-control border-success" value="${price}" placeholder="Prix" min="0" step="100">
                                        <span class="input-group-text bg-white border-success"><i class="fas fa-tag text-success"></i></span>
                                    </div>
                                    <input type="hidden" name="dishes[]" value="${dish.id}">
                                </div>
                            </div>
                        </div>
                    `;
                $('#dishesContainer').append(dishHtml);
            }

            // --- Suppression d'un plat (avec confirmation) ---
            let dishToDelete = null;

            $(document).on('click', '.remove-dish', function () {
                dishToDelete = {
                    id: $(this).data('dish-id'),
                    name: $(this).data('dish-name')
                };
                $('#deleteDishName').text(dishToDelete.name);
                $('#deleteDishModal').modal('show');
            });

            $('#confirmDeleteDish').on('click', function () {
                if (!dishToDelete) return;

                $.ajax({
                    url: '{{ route("admin.vendor.dishes.detach", "") }}/' + dishToDelete.id,
                    //    ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
                    //    Utilise le nom de route du groupe vendor
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            $(`.dish-item[data-dish-id="${dishToDelete.id}"]`).fadeOut(300, function () {
                                $(this).remove();
                                if ($('#dishesContainer .dish-item').length === 0) {
                                    $('#noDishesMessage').fadeIn();
                                }
                            });
                            Swal.fire({
                                icon: 'success',
                                title: 'Plat retiré',
                                text: response.message,
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON?.message || 'Erreur lors de la suppression.';
                        Swal.fire('Erreur', msg, 'error');
                    },
                    complete: function () {
                        $('#deleteDishModal').modal('hide');
                    }
                });
            });
               // --- Validation du formulaire principal avant soumission ---
            $('#vendorForm').on('submit', function (e) {
                let name = $('input[name="name"]').val();
                let type = $('select[name="type"]').val();
                let city = $('input[name="city"]').val();
                let phone = $('input[name="phone"]').val();

                if (!name || !type || !city || !phone) {
                    e.preventDefault();
                    Swal.fire('Champs obligatoires', 'Veuillez remplir tous les champs marqués d\'un astérisque.', 'warning');
                }
            });
        });
    </script>
@endpush
