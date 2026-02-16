@extends('layouts.admin')

@section('title', 'Modifier le vendeur - ' . $vendor->name)

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
        <h1><i data-feather="edit"></i> Modifier {{ $vendor->name }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendeurs</a></div>
            <div class="breadcrumb-item active">Modifier</div>
        </div>
    </div>

    <div class="section-body">
        <form action="{{ route('admin.vendors.update', $vendor) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                                        value="{{ old('name', $vendor->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Type de vendeur <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control select2 @error('type') is-invalid @enderror"
                                        required>
                                        <option value=""></option>
                                        <option value="restaurant" {{ old('type', $vendor->type) == 'restaurant' ? 'selected' : '' }}>
                                            Restaurant</option>
                                        <option value="maquis" {{ old('type', $vendor->type) == 'maquis' ? 'selected' : '' }}>Maquis</option>
                                        <option value="street_vendor" {{ old('type', $vendor->type) == 'street_vendor' ? 'selected' : '' }}>
                                            Vendeur de rue</option>
                                        <option value="market_stand" {{ old('type', $vendor->type) == 'market_stand' ? 'selected' : '' }}>
                                            Étal de marché</option>
                                        <option value="home_cook" {{ old('type', $vendor->type) == 'home_cook' ? 'selected' : '' }}>
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
                                            <option value="{{ $user->id }}" {{ old('user_id', $vendor->user_id) == $user->id ? 'selected' : '' }}>
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
                                        value="{{ old('city', $vendor->city) }}">
                                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Téléphone</label>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $vendor->phone) }}">
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>WhatsApp</label>
                                    <input type="text" name="whatsapp"
                                        class="form-control @error('whatsapp') is-invalid @enderror"
                                        value="{{ old('whatsapp', $vendor->whatsapp) }}">
                                    @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Adresse complète</label>
                                    <input type="text" name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address', $vendor->address) }}">
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Logo / Photo</label>
                                    <input type="file" name="logo"
                                        class="form-control-file @error('logo') is-invalid @enderror" accept="image/*">
                                    @if($vendor->logo)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($vendor->logo) }}" alt="Logo actuel" style="max-height: 80px;">
                                            <small class="text-muted">Logo actuel (laisser vide pour conserver)</small>
                                        </div>
                                    @endif
                                    @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label>Description</label>
                                    <textarea name="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="4">{{ old('description', $vendor->description) }}</textarea>
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
                                Modifiez les plats existants ou ajoutez-en de nouveaux.
                            </p>

                            <div id="dishes-container" class="mb-4">
                                @foreach($vendor->dishes as $index => $dish)
                                    @include('admin.vendors._dish_row', [
                                        'index' => $index,
                                        'dish' => $dish,
                                        'categories' => $categories,
                                        'old' => old("dishes.$index"),
                                    ])
                                @endforeach
                            </div>

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
                                <i data-feather="save"></i> Mettre à jour
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
        // Index de départ : nombre de plats existants
        let dishIndex = {{ $vendor->dishes->count() }};

        // Fonction qui génère une ligne de plat (vide) pour un nouveau plat
        function dishRow(index) {
            const categories = @json($categories);
            let categoryOptions = '<option value=""></option>';
            Object.keys(categories).forEach(function (value) {
                categoryOptions += `<option value="${value}">${categories[value]}</option>`;
            });

            return `
                <div class="card mb-4 dish-row shadow-sm" data-index="${index}">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Nom du plat <span class="text-danger">*</span></label>
                                <input type="text" name="dishes[${index}][name]" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Nom local (optionnel)</label>
                                <input type="text" name="dishes[${index}][name_local]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Catégorie <span class="text-danger">*</span></label>
                                <select name="dishes[${index}][category]" class="form-control select2-dish" required>
                                    ${categoryOptions}
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Origine ethnique</label>
                                <input type="text" name="dishes[${index}][ethnic_origin]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Région</label>
                                <input type="text" name="dishes[${index}][region]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Prix (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" name="dishes[${index}][price]" class="form-control" min="0" step="100" required>
                            </div>
                            <div class="col-md-4">
                                <label>Disponible</label>
                                <select name="dishes[${index}][available]" class="form-control">
                                    <option value="1">Oui</option>
                                    <option value="0">Non</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Notes (optionnel)</label>
                                <input type="text" name="dishes[${index}][notes]" class="form-control">
                            </div>
                            <div class="col-12">
                                <label>Ingrédients (séparés par virgule)</label>
                                <input type="text" name="dishes[${index}][ingredients]" class="form-control" placeholder="Ex: igname, sauce tomate, poisson">
                            </div>
                            <div class="col-12">
                                <label>Description</label>
                                <textarea name="dishes[${index}][description]" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-12">
                                <label>Images du plat</label>
                                <div class="dropzone dish-dropzone" id="dropzone-${index}"></div>
                                <input type="hidden" name="dishes[${index}][images]" class="dish-images-hidden">
                            </div>
                            <div class="col-12 text-right mt-3">
                                <button type="button" class="btn btn-danger btn-sm remove-dish-btn" data-new="true">
                                    <i data-feather="trash-2"></i> Supprimer ce plat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Fonction pour initialiser Dropzone sur un plat (nouveau)
        function initDropzone(index) {
            const dropzoneElement = document.querySelector(`#dropzone-${index}`);
            if (!dropzoneElement || dropzoneElement.dropzone) return;

            const dz = new Dropzone(`#dropzone-${index}`, {
                url: "/dummy-upload", // À remplacer par une vraie route si besoin
                paramName: "images[]",
                acceptedFiles: "image/jpeg,image/png,image/gif,image/webp",
                maxFiles: 5,
                addRemoveLinks: true,
                dictDefaultMessage: "Glissez les images ici ou cliquez",
                dictRemoveFile: "Supprimer",
                autoProcessQueue: false,
                init: function () {
                    this.on("addedfile", function (file) {
                        let hiddenInput = document.querySelector(`input[name="dishes[${index}][images]"]`);
                        let paths = hiddenInput.value ? hiddenInput.value.split(',') : [];
                        paths.push(file.name);
                        hiddenInput.value = paths.join(',');
                    });
                    this.on("removedfile", function (file) {
                        let hiddenInput = document.querySelector(`input[name="dishes[${index}][images]"]`);
                        let paths = hiddenInput.value ? hiddenInput.value.split(',') : [];
                        paths = paths.filter(p => p !== file.name);
                        hiddenInput.value = paths.join(',');
                    });
                }
            });
        }

        $(document).ready(function () {
            // Initialiser Select2 global
            $('.select2').select2({
                placeholder: "-- Sélectionner --",
                allowClear: true,
                width: '100%'
            });

            // Initialiser Select2 pour les selects de catégorie dans les plats existants
            $('.select2-dish').select2({
                placeholder: "-- Choisir catégorie --",
                allowClear: true,
                width: '100%'
            });

            // Ajouter un nouveau plat
            $('#add-dish-btn').click(function () {
                const currentIndex = dishIndex;
                $('#dishes-container').append(dishRow(currentIndex));

                // Initialiser Select2 sur le nouveau select catégorie
                $(`select[name="dishes[${currentIndex}][category]"]`).select2({
                    placeholder: "-- Choisir catégorie --",
                    allowClear: true,
                    width: '100%'
                });

                initDropzone(currentIndex);
                feather.replace();
                dishIndex++;

                $('html, body').animate({
                    scrollTop: $(`[data-index="${currentIndex}"]`).offset().top - 100
                }, 500);
            });

            // Supprimer un plat (existant ou nouveau)
            $('#dishes-container').on('click', '.remove-dish-btn', function () {
                const $btn = $(this);
                const $row = $btn.closest('.dish-row');
                const isNew = $btn.data('new') === true;
                const dishId = $row.find('input[name$="[id]"]').val();

                if (isNew) {
                    // Nouveau plat : suppression simple
                    $row.remove();
                } else {
                    // Plat existant : appel AJAX
                    Swal.fire({
                        title: 'Supprimer ce plat ?',
                        text: "Cette action est irréversible.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: 'var(--benin-red)',
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route("admin.dishes.destroy", ":id") }}'.replace(':id', dishId),
                                type: 'DELETE',
                                data: { _token: '{{ csrf_token() }}' },
                                success: function () {
                                    $row.remove();
                                    Swal.fire('Supprimé', 'Le plat a été supprimé.', 'success');
                                },
                                error: function () {
                                    Swal.fire('Erreur', 'Impossible de supprimer ce plat.', 'error');
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush