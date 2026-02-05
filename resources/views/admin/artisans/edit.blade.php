@extends('layouts.admin')

@section('title', 'Modifier l\'artisan')

@section('content')
<div class="section-header">
    <h1>Modifier l'artisan</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.artisans.index') }}">Artisans</a></div>
        <div class="breadcrumb-item active">{{ $artisan->user->name }}</div>
    </div>
</div>

<div class="section-body">
    <form action="{{ route('admin.artisans.update', $artisan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            {{-- Informations utilisateur --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Compte utilisateur</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            @php
                                $photo = $artisan->photos->where('is_primary', true)->first() ?? $artisan->photos->first();
                            @endphp
                            <img src="{{ $photo ? asset($photo->photo_url) : asset('admin-assets/img/avatar/avatar-1.png') }}" 
                                 alt="{{ $artisan->user->name }}" 
                                 class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                            <h5>{{ $artisan->user->name }}</h5>
                            <p class="text-muted">{{ $artisan->user->email }}</p>
                        </div>
                        
                        <div class="form-group">
                            <label>Nom complet <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $artisan->user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $artisan->user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Téléphone</label>
                            <input type="text" name="phone" class="form-control" 
                                   value="{{ old('phone', $artisan->user->phone) }}">
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label>Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            <small class="text-muted">Laissez vide pour garder le mot de passe actuel</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>
                
                {{-- Photos actuelles --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Photos</h4>
                    </div>
                    <div class="card-body">
                        @if($artisan->photos->count() > 0)
                        <div class="gallery gallery-md mb-3">
                            @foreach($artisan->photos as $photo)
                            <div class="gallery-item" style="background-image: url('{{ asset($photo->photo_url) }}');">
                                <div class="gallery-item-buttons">
                                    @if($photo->is_primary)
                                        <span class="badge badge-primary">Principale</span>
                                    @else
                                        <button type="button" class="btn btn-sm btn-primary btn-set-primary" 
                                                data-id="{{ $photo->id }}" title="Définir comme principale">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-photo" 
                                            data-id="{{ $photo->id }}" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        
                        <div class="form-group">
                            <label>Ajouter des photos</label>
                            <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Informations artisan --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations de l'artisan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Spécialité <span class="text-danger">*</span></label>
                                    <select name="specialty" class="form-control @error('specialty') is-invalid @enderror" required>
                                        <option value="">Sélectionner une spécialité</option>
                                        <option value="Sculpture" {{ old('specialty', $artisan->specialty) == 'Sculpture' ? 'selected' : '' }}>Sculpture</option>
                                        <option value="Tissage" {{ old('specialty', $artisan->specialty) == 'Tissage' ? 'selected' : '' }}>Tissage</option>
                                        <option value="Poterie" {{ old('specialty', $artisan->specialty) == 'Poterie' ? 'selected' : '' }}>Poterie</option>
                                        <option value="Vannerie" {{ old('specialty', $artisan->specialty) == 'Vannerie' ? 'selected' : '' }}>Vannerie</option>
                                        <option value="Maroquinerie" {{ old('specialty', $artisan->specialty) == 'Maroquinerie' ? 'selected' : '' }}>Maroquinerie</option>
                                        <option value="Forge" {{ old('specialty', $artisan->specialty) == 'Forge' ? 'selected' : '' }}>Forge</option>
                                        <option value="Bijouterie" {{ old('specialty', $artisan->specialty) == 'Bijouterie' ? 'selected' : '' }}>Bijouterie</option>
                                        <option value="Peinture" {{ old('specialty', $artisan->specialty) == 'Peinture' ? 'selected' : '' }}>Peinture</option>
                                        <option value="Broderie" {{ old('specialty', $artisan->specialty) == 'Broderie' ? 'selected' : '' }}>Broderie</option>
                                        <option value="Autre" {{ old('specialty', $artisan->specialty) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('specialty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Années d'expérience</label>
                                    <input type="number" name="experience_years" class="form-control" 
                                           value="{{ old('experience_years', $artisan->experience_years) }}" min="0" max="80">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Localisation</label>
                                    <input type="text" name="location" class="form-control" 
                                           value="{{ old('location', $artisan->location) }}" placeholder="Ex: Cotonou, Porto-Novo...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Origine ethnique</label>
                                    <input type="text" name="ethnic_origin" class="form-control" 
                                           value="{{ old('ethnic_origin', $artisan->ethnic_origin) }}" placeholder="Ex: Fon, Yoruba, Bariba...">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Adresse complète</label>
                            <input type="text" name="address" class="form-control" 
                                   value="{{ old('address', $artisan->address) }}">
                        </div>
                        
                        <div class="form-group">
                            <label>Biographie</label>
                            <textarea name="bio" class="form-control" rows="4" 
                                      placeholder="Parcours de l'artisan, son histoire...">{{ old('bio', $artisan->bio) }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Histoire de l'atelier</label>
                            <textarea name="workshop_story" class="form-control" rows="3"
                                      placeholder="L'histoire de l'atelier, sa transmission...">{{ old('workshop_story', $artisan->workshop_story) }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Techniques maîtrisées</label>
                                    @php
                                        $techniques = is_array($artisan->techniques) ? implode(', ', $artisan->techniques) : $artisan->techniques;
                                    @endphp
                                    <input type="text" name="techniques" class="form-control" 
                                           value="{{ old('techniques', $techniques) }}" placeholder="Séparées par des virgules">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Matériaux utilisés</label>
                                    @php
                                        $materials = is_array($artisan->materials) ? implode(', ', $artisan->materials) : $artisan->materials;
                                    @endphp
                                    <input type="text" name="materials" class="form-control" 
                                           value="{{ old('materials', $materials) }}" placeholder="Séparés par des virgules">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Certifications / Prix</label>
                                    @php
                                        $certifications = is_array($artisan->certifications) ? implode(', ', $artisan->certifications) : $artisan->certifications;
                                    @endphp
                                    <input type="text" name="certifications" class="form-control" 
                                           value="{{ old('certifications', $certifications) }}" placeholder="Séparés par des virgules">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Disponibilité</label>
                                    <select name="availability" class="form-control">
                                        <option value="available" {{ old('availability', $artisan->availability) == 'available' ? 'selected' : '' }}>Disponible</option>
                                        <option value="busy" {{ old('availability', $artisan->availability) == 'busy' ? 'selected' : '' }}>Occupé</option>
                                        <option value="vacation" {{ old('availability', $artisan->availability) == 'vacation' ? 'selected' : '' }}>En vacances</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" name="is_verified" class="custom-switch-input" 
                                               {{ old('is_verified', $artisan->is_verified) ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Artisan vérifié</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" name="accepts_custom_orders" class="custom-switch-input" 
                                               {{ old('accepts_custom_orders', $artisan->accepts_custom_orders) ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Accepte les commandes personnalisées</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.artisans.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
                
                {{-- Statistiques --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Statistiques</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h5>{{ $artisan->products->count() }}</h5>
                                <small class="text-muted">Produits</small>
                            </div>
                            <div class="col-md-3">
                                <h5>{{ $artisan->reviews->count() }}</h5>
                                <small class="text-muted">Avis</small>
                            </div>
                            <div class="col-md-3">
                                <h5>{{ number_format($artisan->average_rating ?? 0, 1) }}</h5>
                                <small class="text-muted">Note moyenne</small>
                            </div>
                            <div class="col-md-3">
                                <h5>{{ $artisan->orders_count ?? 0 }}</h5>
                                <small class="text-muted">Commandes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.gallery-item {
    position: relative;
}
.gallery-item-buttons {
    position: absolute;
    bottom: 5px;
    right: 5px;
    display: flex;
    gap: 5px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Supprimer une photo
    $('.btn-delete-photo').click(function() {
        var photoId = $(this).data('id');
        var $galleryItem = $(this).closest('.gallery-item');
        
        Swal.fire({
            title: 'Supprimer cette photo ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/artisans/photos") }}/' + photoId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $galleryItem.fadeOut(300, function() { $(this).remove(); });
                        Swal.fire('Supprimé!', 'La photo a été supprimée.', 'success');
                    },
                    error: function() {
                        Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
                    }
                });
            }
        });
    });
    
    // Définir photo principale
    $('.btn-set-primary').click(function() {
        var photoId = $(this).data('id');
        
        $.ajax({
            url: '{{ url("admin/artisans/photos") }}/' + photoId + '/primary',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
            }
        });
    });
});
</script>
@endpush
