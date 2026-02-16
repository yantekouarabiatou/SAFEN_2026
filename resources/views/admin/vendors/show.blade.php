@extends('layouts.admin')

@section('title', 'Détails du Vendeur - ' . $vendor->name)

@push('styles')
    <style>
        :root {
            --benin-green: #008751;
            --benin-yellow: #FCD116;
            --benin-red: #E8112D;
        }

        .vendor-header {
            background: linear-gradient(135deg, var(--benin-green) 0%, #004d2e 100%);
            color: white;
            padding: 2rem 0;
            border-radius: 20px 20px 20px 20px;
            box-shadow: 0 10px 30px rgba(0, 135, 81, 0.3);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .vendor-header::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(30deg);
        }

        .vendor-logo-large {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border: 5px solid rgba(255,255,255,0.3);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
        }

        .vendor-logo-large:hover {
            transform: scale(1.05);
        }

        .vendor-logo-placeholder {
            width: 130px;
            height: 130px;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: white;
            margin: 0 auto 1rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .badge-custom {
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .badge-verified {
            background: var(--benin-green);
            color: white;
        }

        .badge-pending {
            background: var(--benin-yellow);
            color: #333;
        }

        .info-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            background: white;
            height: 100%;
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,135,81,0.15);
        }

        .info-card .card-header {
            background: transparent;
            border-bottom: 2px solid rgba(0,135,81,0.1);
            padding: 1.2rem 1.5rem;
        }

        .info-card .card-header h4 {
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-list li {
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-list li strong {
            width: 110px;
            color: #495057;
            font-weight: 600;
        }

        .specialty-badge {
            background: #f8f9fa;
            color: #2c3e50;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            margin: 0 5px 8px 0;
            display: inline-block;
            border-left: 3px solid var(--benin-green);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .plat-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.03);
        }

        .plat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,135,81,0.2);
            border-color: var(--benin-green);
        }

        .plat-image-wrapper {
            height: 180px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .plat-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .plat-card:hover .plat-image {
            transform: scale(1.1);
        }

        .plat-badge-price {
            background: var(--benin-green);
            color: white;
            padding: 6px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
        }

        .plat-badge-available {
            background: #28a745;
            color: white;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.85rem;
        }

        .plat-badge-unavailable {
            background: var(--benin-red);
            color: white;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.85rem;
        }

        .btn-action {
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .btn-edit {
            background: var(--benin-yellow);
            color: #333;
        }

        .btn-delete {
            background: var(--benin-red);
            color: white;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--benin-green);
            line-height: 1.2;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .vendor-logo-large {
                width: 100px;
                height: 100px;
            }
            .info-list li {
                flex-direction: column;
                align-items: flex-start;
            }
            .info-list li strong {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="section-header">
        <h1 class="d-flex align-items-center">
            <i data-feather="user" class="mr-2" style="width: 32px; height: 32px;"></i> 
            {{ $vendor->name }}
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendeurs</a></div>
            <div class="breadcrumb-item active">{{ Str::limit($vendor->name, 30) }}</div>
        </div>
    </div>

    <div class="section-body">
        <!-- En-tête vendeur -->
        <div class="vendor-header">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center text-md-left">
                        @if($vendor->logo)
                            <img src="{{ Storage::url($vendor->logo) }}" alt="Logo" class="vendor-logo-large mb-3 mb-md-0">
                        @else
                            <div class="vendor-logo-placeholder mx-auto mx-md-0">
                                {{ strtoupper(substr($vendor->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 text-center text-md-left">
                        <h2 class="mb-2">{{ $vendor->name }}</h2>
                        @if($vendor->user)
                            <p class="mb-2 opacity-75">
                                <i data-feather="user-check" class="mr-1" style="width: 18px;"></i>
                                Géré par : <strong>{{ $vendor->user->prenom }} {{ $vendor->user->nom }}</strong>
                                <br><small>{{ $vendor->user->email }}</small>
                            </p>
                        @endif
                        <div class="mt-3">
                            <span class="badge badge-custom bg-white text-dark px-4 py-2 mr-2">
                                <i data-feather="tag" class="mr-1" style="width: 14px;"></i>
                                {{ $vendor->type_label }}
                            </span>
                            @if($vendor->verified)
                                <span class="badge badge-custom badge-verified px-4 py-2">
                                    <i data-feather="check-circle" class="mr-1" style="width: 14px;"></i>
                                    Vérifié
                                </span>
                            @else
                                <span class="badge badge-custom badge-pending px-4 py-2">
                                    <i data-feather="clock" class="mr-1" style="width: 14px;"></i>
                                    En attente
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 text-center text-md-right mt-4 mt-md-0">
                        <div class="stat-card bg-white-10 bg-opacity-10">
                            <div class="stat-number text-white">{{ $vendor->dishes->count() }}</div>
                            <div class="stat-label text-white-50">Plats proposés</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Colonne gauche : infos principales -->
            <div class="col-lg-4">
                <!-- Carte Informations -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i data-feather="info" class="mr-2"></i>Informations</h4>
                    </div>
                    <div class="card-body p-0">
                        <ul class="info-list">
                            <li>
                                <strong>Ville :</strong> 
                                <span>{{ $vendor->city ?? 'Non renseignée' }}</span>
                            </li>
                            <li>
                                <strong>Téléphone :</strong> 
                                @if($vendor->phone)
                                    <a href="tel:{{ $vendor->phone }}" class="text-decoration-none">{{ $vendor->phone }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </li>
                            <li>
                                <strong>WhatsApp :</strong> 
                                @if($vendor->whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $vendor->whatsapp) }}" target="_blank" class="text-decoration-none">
                                        {{ $vendor->whatsapp }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </li>
                            <li>
                                <strong>Adresse :</strong> 
                                <span>{{ $vendor->address ?? '-' }}</span>
                            </li>
                            <li>
                                <strong>Coordonnées :</strong>
                                @if($vendor->latitude && $vendor->longitude)
                                    <span>{{ $vendor->latitude }}, {{ $vendor->longitude }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Carte Spécialités -->
                <div class="info-card mt-4">
                    <div class="card-header">
                        <h4><i data-feather="star" class="mr-2"></i>Spécialités</h4>
                    </div>
                    <div class="card-body">
                        @if($vendor->specialties)
                            @php $specs = is_array($vendor->specialties) ? $vendor->specialties : json_decode($vendor->specialties, true) ?? []; @endphp
                            <div class="d-flex flex-wrap">
                                @forelse($specs as $spec)
                                    <span class="specialty-badge">{{ $spec }}</span>
                                @empty
                                    <p class="text-muted mb-0">Aucune spécialité renseignée</p>
                                @endforelse
                            </div>
                        @else
                            <p class="text-muted mb-0">Aucune spécialité renseignée</p>
                        @endif
                    </div>
                </div>

                <!-- Horaires (si présents) -->
                @if($vendor->opening_hours)
                <div class="info-card mt-4">
                    <div class="card-header">
                        <h4><i data-feather="clock" class="mr-2"></i>Horaires</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $vendor->opening_hours }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Colonne droite : description + plats -->
            <div class="col-lg-8">
                <!-- Description -->
                <div class="info-card">
                    <div class="card-header">
                        <h4><i data-feather="file-text" class="mr-2"></i>Description</h4>
                    </div>
                    <div class="card-body">
                        @if($vendor->description)
                            <p class="mb-0">{{ nl2br(e($vendor->description)) }}</p>
                        @else
                            <p class="text-muted mb-0">Aucune description disponible</p>
                        @endif
                    </div>
                </div>

                <!-- Plats proposés -->
                <div class="info-card mt-4">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                        <h4><i data-feather="coffee" class="mr-2"></i>Plats proposés ({{ $vendor->dishes->count() }})</h4>
                        <a href="{{ route('admin.vendors.edit', $vendor) }}" class="btn btn-sm btn-warning">
                            <i data-feather="edit-2" class="mr-1"></i> Gérer les plats
                        </a>
                    </div>
                    <div class="card-body">
                        @if($vendor->dishes->isEmpty())
                            <div class="text-center py-5">
                                <i data-feather="coffee" class="text-muted" style="width: 60px; height: 60px;"></i>
                                <p class="text-muted mt-3 fs-5">Aucun plat enregistré pour ce vendeur</p>
                                <a href="{{ route('admin.vendors.edit', $vendor) }}" class="btn btn-outline-primary mt-2">
                                    <i data-feather="plus"></i> Ajouter un plat
                                </a>
                            </div>
                        @else
                            <div class="row g-4">
                                @foreach($vendor->dishes as $dish)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="plat-card">
                                            <div class="plat-image-wrapper">
                                                @if($dish->images->isNotEmpty())
                                                    <img src="{{ Storage::url($dish->images->first()->image_url) }}" 
                                                         alt="{{ $dish->name }}" 
                                                         class="plat-image">
                                                @else
                                                    <div class="plat-image d-flex align-items-center justify-content-center bg-light text-muted">
                                                        <i data-feather="image" style="width: 40px;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="p-3">
                                                <h6 class="mb-1 fw-bold">{{ $dish->name }}</h6>
                                                @if($dish->name_local)
                                                    <small class="text-muted d-block mb-2">
                                                        <i data-feather="globe" class="mr-1" style="width: 12px;"></i>{{ $dish->name_local }}
                                                    </small>
                                                @endif

                                                <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
                                                    <span class="plat-badge-price">
                                                        {{ number_format($dish->pivot->price, 0, ',', ' ') }} FCFA
                                                    </span>
                                                    @if($dish->pivot->available)
                                                        <span class="plat-badge-available">
                                                            <i data-feather="check-circle" class="mr-1" style="width: 12px;"></i>Disponible
                                                        </span>
                                                    @else
                                                        <span class="plat-badge-unavailable">
                                                            <i data-feather="x-circle" class="mr-1" style="width: 12px;"></i>Indisponible
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex flex-wrap gap-3 justify-content-end">
                        <a href="{{ route('admin.vendors.edit', $vendor) }}" class="btn btn-action btn-edit px-5">
                            <i data-feather="edit" class="mr-2"></i> Modifier
                        </a>
                        <button type="button" class="btn btn-action btn-delete px-5 delete-vendor-btn"
                                data-id="{{ $vendor->id }}"
                                data-name="{{ $vendor->name }}">
                            <i data-feather="trash-2" class="mr-2"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Recharger les icônes Feather après chaque mise à jour du DOM
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Suppression avec SweetAlert2
            $('.delete-vendor-btn').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                Swal.fire({
                    title: 'Supprimer ce vendeur ?',
                    html: `Le vendeur <strong>${name}</strong> et ses associations seront définitivement supprimés.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--benin-red)',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("admin.vendors.destroy", ":id") }}'.replace(':id', id),
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function() {
                                Swal.fire({
                                    title: 'Supprimé !',
                                    text: 'Le vendeur a été supprimé avec succès.',
                                    icon: 'success',
                                    confirmButtonColor: 'var(--benin-green)'
                                }).then(() => {
                                    window.location.href = '{{ route("admin.vendors.index") }}';
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Erreur',
                                    text: 'Impossible de supprimer ce vendeur. Veuillez réessayer.',
                                    icon: 'error',
                                    confirmButtonColor: 'var(--benin-red)'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush