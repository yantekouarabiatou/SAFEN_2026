@extends('layouts.admin')

@section('title', 'Modifier l\'utilisateur')

@section('content')
<div class="section-header">
    <h1>Modifier l'utilisateur</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></div>
        <div class="breadcrumb-item active">{{ $user->name }}</div>
    </div>
</div>

<div class="section-body">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations de l'utilisateur</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Téléphone</label>
                                    <input type="text" name="phone" class="form-control" 
                                           value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Adresse</label>
                                    <input type="text" name="address" class="form-control" 
                                           value="{{ old('address', $user->address) }}">
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <h6 class="text-muted">Changer le mot de passe (optionnel)</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nouveau mot de passe</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                    <small class="text-muted">Laissez vide pour garder le mot de passe actuel</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Profil</h4>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $user->profile_photo_url ?? asset('admin-assets/img/avatar/avatar-1.png') }}" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                        <h5>{{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                        <p class="text-muted">
                            Inscrit le {{ $user->created_at->format('d/m/Y') }}
                        </p>
                        
                        <div class="form-group mt-3">
                            <label>Changer la photo</label>
                            <input type="file" name="profile_photo" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4>Rôles</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            @foreach($roles as $role)
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                       class="custom-control-input" id="role-{{ $role->id }}"
                                       {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                       @if($user->id === auth()->id() && $role->name === 'admin') disabled @endif>
                                <label class="custom-control-label" for="role-{{ $role->id }}">
                                    {{ ucfirst($role->name) }}
                                </label>
                            </div>
                            @endforeach
                            @if($user->id === auth()->id())
                            <small class="text-muted">Vous ne pouvez pas retirer votre propre rôle admin</small>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">Annuler</a>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
