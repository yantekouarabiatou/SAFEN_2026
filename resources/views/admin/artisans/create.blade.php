@extends('layouts.admin')

@section('title', 'Ajouter un artisan')

@section('content')
<div class="section-header">
    <h1>Ajouter un artisan</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.artisans.index') }}">Artisans</a></div>
        <div class="breadcrumb-item active">Ajouter</div>
    </div>
</div>

<div class="section-body">
    <form action="{{ route('admin.artisans.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            {{-- Informations utilisateur --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Compte utilisateur</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Utilisateur existant</label>
                            <select name="user_id" class="form-control select2" id="user-select">
                                <option value="">Créer un nouveau compte</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Sélectionnez un utilisateur existant ou créez-en un nouveau</small>
                        </div>

                        <div id="new-user-fields" class="{{ old('user_id') ? 'd-none' : '' }}">
                            <hr>
                            <div class="form-group">
                                <label>Nom complet <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>

                            <div class="form-group">
                                <label>Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
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
                </div>

                {{-- Photos --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Photos</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Photos de l'artisan</label>
                            <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">La première image sera utilisée comme photo principale</small>
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
                                    <label>Métier / Spécialité <span class="text-danger">*</span></label>
                                    <select name="craft" class="form-control @error('craft') is-invalid @enderror" required>
                                        <option value="">Sélectionner un métier</option>
                                        <option value="tisserand" {{ old('craft') == 'tisserand' ? 'selected' : '' }}>Tisserand</option>
                                        <option value="sculpteur" {{ old('craft') == 'sculpteur' ? 'selected' : '' }}>Sculpteur</option>
                                        <option value="potier" {{ old('craft') == 'potier' ? 'selected' : '' }}>Potier</option>
                                        <option value="forgeron" {{ old('craft') == 'forgeron' ? 'selected' : '' }}>Forgeron</option>
                                        <option value="couturier" {{ old('craft') == 'couturier' ? 'selected' : '' }}>Couturier traditionnel</option>
                                        <option value="bijoutier" {{ old('craft') == 'bijoutier' ? 'selected' : '' }}>Bijoutier</option>
                                        <option value="tanneur" {{ old('craft') == 'tanneur' ? 'selected' : '' }}>Tanneur</option>
                                        <option value="musicien" {{ old('craft') == 'musicien' ? 'selected' : '' }}>Musicien traditionnel</option>
                                        <option value="autre" {{ old('craft') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('craft')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Années d'expérience</label>
                                    <input type="number" name="years_experience" class="form-control"
                                           value="{{ old('years_experience') }}" min="0" max="80">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ville <span class="text-danger">*</span></label>
                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                           value="{{ old('city') }}" placeholder="Ex: Cotonou, Porto-Novo..." required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Quartier</label>
                                    <input type="text" name="neighborhood" class="form-control"
                                           value="{{ old('neighborhood') }}" placeholder="Ex: Dantokpa, Agla...">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Biographie</label>
                            <textarea name="bio" class="form-control" rows="4"
                                      placeholder="Parcours de l'artisan, son histoire...">{{ old('bio') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Histoire de l'atelier</label>
                            <textarea name="workshop_story" class="form-control" rows="3"
                                      placeholder="L'histoire de l'atelier, sa transmission...">{{ old('workshop_story') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Techniques maîtrisées</label>
                                    <input type="text" name="techniques" class="form-control"
                                           value="{{ old('techniques') }}" placeholder="Séparées par des virgules">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Matériaux utilisés</label>
                                    <input type="text" name="materials" class="form-control"
                                           value="{{ old('materials') }}" placeholder="Séparés par des virgules">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Certifications / Prix</label>
                                    <input type="text" name="certifications" class="form-control"
                                           value="{{ old('certifications') }}" placeholder="Séparés par des virgules">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Disponibilité</label>
                                    <select name="availability" class="form-control">
                                        <option value="available" {{ old('availability') == 'available' ? 'selected' : '' }}>Disponible</option>
                                        <option value="busy" {{ old('availability') == 'busy' ? 'selected' : '' }}>Occupé</option>
                                        <option value="vacation" {{ old('availability') == 'vacation' ? 'selected' : '' }}>En vacances</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" name="accepts_custom_orders" class="custom-switch-input"
                                               {{ old('accepts_custom_orders', true) ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Accepte les commandes personnalisées</span>
                                    </label>
                                </div>
                            </div>
                            {{-- Vous pouvez ajouter ici d'autres switchs si nécessaire --}}
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.artisans.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2();

    $('#user-select').change(function() {
        if ($(this).val()) {
            $('#new-user-fields').addClass('d-none');
        } else {
            $('#new-user-fields').removeClass('d-none');
        }
    });
});
</script>
@endpush
