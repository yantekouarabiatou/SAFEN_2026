@extends('layouts.admin')

@section('title', 'Ajouter un vendeur')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone.css">

    <style>
        .card-header-modern {
            background: linear-gradient(135deg, var(--benin-green), #006d40);
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .dish-row {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background: #fff;
            transition: all 0.2s;
        }

        .dish-row:hover {
            box-shadow: 0 4px 12px rgba(0, 135, 81, 0.1);
        }

        .dropzone {
            border: 2px dashed #ced4da;
            border-radius: 8px;
            background: #f8f9fa;
            min-height: 120px;
            text-align: center;
            padding: 20px;
        }

        .dz-preview {
            margin: 8px;
            border-radius: 6px;
            overflow: hidden;
        }

        .dz-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .btn-add-dish {
            background: var(--benin-yellow);
            color: #333;
            border: none;
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
        }

        .btn-add-dish:hover {
            background: #f0c000;
        }
    </style>
@endpush

@section('content')
    <div class="section-header">
        <h1><i data-feather="user-plus"></i> Ajouter un vendeur</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendeurs</a></div>
            <div class="breadcrumb-item active">Ajouter</div>
        </div>
    </div>

    <div class="section-body">
        <form action="{{ route('admin.vendors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Informations vendeur -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header card-header-modern">
                            <h4>Informations du vendeur</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label>Nom du vendeur <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Type de vendeur <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control select2 @error('type') is-invalid @enderror"
                                        required>
                                        <option value=""></option>
                                        <option value="restaurant" {{ old('type') == 'restaurant' ? 'selected' : '' }}>
                                            Restaurant</option>
                                        <option value="maquis" {{ old('type') == 'maquis' ? 'selected' : '' }}>Maquis</option>
                                        <option value="street_vendor" {{ old('type') == 'street_vendor' ? 'selected' : '' }}>
                                            Vendeur de rue</option>
                                        <option value="market_stand" {{ old('type') == 'market_stand' ? 'selected' : '' }}>
                                            Étal de marché</option>
                                        <option value="home_cook" {{ old('type') == 'home_cook' ? 'selected' : '' }}>
                                            Cuisinière à domicile</option>
                                    </select>
                                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Utilisateur associé <span class="text-danger">*</span></label>
                                    <select name="user_id"
                                        class="form-control select2 @error('user_id') is-invalid @enderror" required>
                                        <option value="">-- Sélectionner un utilisateur --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->prenom }} {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="form-text text-muted">
                                        <i data-feather="info"></i> Choisissez l'utilisateur qui gérera ce vendeur
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <label>Ville</label>
                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                        value="{{ old('city') }}">
                                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Téléphone</label>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}">
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>WhatsApp</label>
                                    <input type="text" name="whatsapp"
                                        class="form-control @error('whatsapp') is-invalid @enderror"
                                        value="{{ old('whatsapp') }}">
                                    @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Adresse complète</label>
                                    <input type="text" name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address') }}">
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Logo / Photo</label>
                                    <input type="file" name="logo"
                                        class="form-control-file @error('logo') is-invalid @enderror" accept="image/*">
                                    @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label>Description</label>
                                    <textarea name="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="4">{{ old('description') }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plats proposés -->
                <div class="col-lg-12">
                    <div class="card h-100">
                        <div class="card-header card-header-modern">
                            <h4>Plats proposés</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">
                                Ajoutez les plats que ce vendeur propose. Chaque plat sera créé et associé.
                            </p>

                            <div id="dishes-container" class="mb-4"></div>

                            <button type="button" class="btn btn-add-dish btn-block" id="add-dish-btn">
                                <i data-feather="plus-circle"></i> Ajouter un plat
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-right">
                            <button type="submit" class="btn btn-benin-green btn-lg px-5">
                                <i data-feather="save"></i> Créer le vendeur
                            </button>
                            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary btn-lg px-5 ml-2">
                                <i data-feather="x"></i> Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin-assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone-min.js"></script>

    <script>
        // Index de départ : si old('dishes') existe, on reprend le bon nombre
        let dishIndex = {{ old('dishes') ? count(old('dishes')) : 0 }};

        // Récupérer les anciennes valeurs (JSON sécurisé)
        const oldDishes = @json(old('dishes', []));
        const categories = @json($categories);

        // Fonction qui génère une ligne de plat avec old() pour restaurer les valeurs
        function dishRow(index) {
            const dish = oldDishes[index] || {};

            // Générer les options de catégories
            let categoryOptions = '<option value=""></option>';
            Object.keys(categories).forEach(function (value) {
                const selected = dish.category === value ? 'selected' : '';
                categoryOptions += `<option value="${value}" ${selected}>${categories[value]}</option>`;
            });

            return `
                    <div class="card mb-4 dish-row shadow-sm" data-index="${index}">
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Nom -->
                                <div class="col-md-6">
                                    <label>Nom du plat <span class="text-danger">*</span></label>
                                    <input type="text" name="dishes[${index}][name]" class="form-control" 
                                           value="${dish.name || ''}" required>
                                </div>

                                <!-- Nom local -->
                                <div class="col-md-6">
                                    <label>Nom local (optionnel)</label>
                                    <input type="text" name="dishes[${index}][name_local]" class="form-control" 
                                           value="${dish.name_local || ''}">
                                </div>

                                <!-- Catégorie -->
                                <div class="col-md-4">
                                    <label>Catégorie <span class="text-danger">*</span></label>
                                    <select name="dishes[${index}][category]" class="form-control select2-dish" required>
                                        ${categoryOptions}
                                    </select>
                                </div>

                                <!-- Origine ethnique -->
                                <div class="col-md-4">
                                    <label>Origine ethnique</label>
                                    <input type="text" name="dishes[${index}][ethnic_origin]" class="form-control" 
                                           value="${dish.ethnic_origin || ''}">
                                </div>

                                <!-- Région -->
                                <div class="col-md-4">
                                    <label>Région</label>
                                    <input type="text" name="dishes[${index}][region]" class="form-control" 
                                           value="${dish.region || ''}">
                                </div>

                                <!-- Prix -->
                                <div class="col-md-4">
                                    <label>Prix (FCFA) <span class="text-danger">*</span></label>
                                    <input type="number" name="dishes[${index}][price]" class="form-control" 
                                           value="${dish.price || ''}" min="0" step="100" required>
                                </div>

                                <!-- Disponible -->
                                <div class="col-md-4">
                                    <label>Disponible</label>
                                    <select name="dishes[${index}][available]" class="form-control">
                                        <option value="1" ${(!dish.hasOwnProperty('available') || dish.available != '0') ? 'selected' : ''}>Oui</option>
                                        <option value="0" ${dish.available == '0' ? 'selected' : ''}>Non</option>
                                    </select>
                                </div>

                                <!-- Notes -->
                                <div class="col-md-4">
                                    <label>Notes (optionnel)</label>
                                    <input type="text" name="dishes[${index}][notes]" class="form-control" 
                                           value="${dish.notes || ''}">
                                </div>

                                <!-- Ingrédients -->
                                <div class="col-12">
                                    <label>Ingrédients (séparés par virgule)</label>
                                    <input type="text" name="dishes[${index}][ingredients]" class="form-control" 
                                           placeholder="Ex: igname, sauce tomate, poisson"
                                           value="${dish.ingredients || ''}">
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label>Description</label>
                                    <textarea name="dishes[${index}][description]" class="form-control" rows="2">${dish.description || ''}</textarea>
                                </div>

                                <!-- Images -->
                                <div class="col-12">
                                    <label>Images du plat</label>
                                    <div class="dropzone dish-dropzone" id="dropzone-${index}"></div>
                                    <input type="hidden" name="dishes[${index}][images]" class="dish-images-hidden" 
                                           value="${dish.images || ''}">
                                </div>

                                <!-- Supprimer -->
                                <div class="col-12 text-right mt-3">
                                    <button type="button" class="btn btn-danger btn-sm remove-dish-btn">
                                        <i data-feather="trash-2"></i> Supprimer ce plat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
        }

        // Fonction pour initialiser Dropzone sur un plat
        function initDropzone(index) {
            const dropzoneElement = document.querySelector(`#dropzone-${index}`);
            if (!dropzoneElement) return;

            // Vérifier si Dropzone n'est pas déjà initialisé
            if (dropzoneElement.dropzone) {
                return dropzoneElement.dropzone;
            }

            const dz = new Dropzone(`#dropzone-${index}`, {
                url: "/dummy-upload", // À remplacer par une vraie route si besoin
                paramName: "images[]",
                acceptedFiles: "image/jpeg,image/png,image/gif,image/webp",
                maxFiles: 5,
                addRemoveLinks: true,
                dictDefaultMessage: "Glissez les images ici ou cliquez",
                dictRemoveFile: "Supprimer",
                dictCancelUpload: "Annuler",
                autoProcessQueue: false, // Ne pas uploader automatiquement
                init: function () {
                    this.on("addedfile", function (file) {
                        // Stocker le fichier localement (simulation)
                        let hiddenInput = document.querySelector(`input[name="dishes[${index}][images]"]`);
                        let paths = hiddenInput.value ? hiddenInput.value.split(',') : [];

                        // Utiliser le nom du fichier comme référence
                        paths.push(file.name);
                        hiddenInput.value = paths.join(',');
                    });

                    this.on("removedfile", function (file) {
                        // Retirer le fichier de la liste
                        let hiddenInput = document.querySelector(`input[name="dishes[${index}][images]"]`);
                        let paths = hiddenInput.value ? hiddenInput.value.split(',') : [];
                        paths = paths.filter(p => p !== file.name);
                        hiddenInput.value = paths.join(',');
                    });
                }
            });

            return dz;
        }

        $(document).ready(function () {
            // Initialiser Select2 global
            $('.select2').select2({
                placeholder: "-- Sélectionner --",
                allowClear: true,
                width: '100%'
            });

            // Restaurer les plats existants en cas d'erreur de validation
            if (oldDishes.length > 0) {
                oldDishes.forEach(function (dish, index) {
                    $('#dishes-container').append(dishRow(index));

                    // Initialiser Select2 sur le select catégorie
                    $(`select[name="dishes[${index}][category]"]`).select2({
                        placeholder: "-- Choisir catégorie --",
                        allowClear: true,
                        width: '100%'
                    });

                    // Initialiser Dropzone
                    initDropzone(index);

                    // Rafraîchir les icônes Feather
                    feather.replace();
                });

                dishIndex = oldDishes.length;
            }

            // Ajouter un nouveau plat
            $('#add-dish-btn').click(function () {
                const currentIndex = dishIndex;

                // Ajouter le HTML du plat
                $('#dishes-container').append(dishRow(currentIndex));

                // Initialiser Select2 sur le nouveau select catégorie
                $(`select[name="dishes[${currentIndex}][category]"]`).select2({
                    placeholder: "-- Choisir catégorie --",
                    allowClear: true,
                    width: '100%'
                });

                // Initialiser Dropzone pour ce plat
                initDropzone(currentIndex);

                // Rafraîchir les icônes Feather
                feather.replace();

                // Incrémenter l'index
                dishIndex++;

                // Scroller vers le nouveau plat
                $('html, body').animate({
                    scrollTop: $(`[data-index="${currentIndex}"]`).offset().top - 100
                }, 500);
            });

            // Supprimer un plat
            $('#dishes-container').on('click', '.remove-dish-btn', function () {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce plat ?')) {
                    $(this).closest('.dish-row').remove();

                    // Si aucun plat restant, réinitialiser l'index
                    if ($('#dishes-container').children().length === 0) {
                        dishIndex = 0;
                    }
                }
            });

            // Si aucun plat → ajouter un par défaut
            if ($('#dishes-container').children().length === 0) {
                $('#add-dish-btn').trigger('click');
            }
        });
    </script>
@endpush