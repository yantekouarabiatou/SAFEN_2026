@extends('layouts.admin')

@section('title', 'Ajouter un utilisateur')

@section('content')
<div class="section-header">
    <h1>Ajouter un utilisateur</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></div>
        <div class="breadcrumb-item active">Ajouter</div>
    </div>
</div>

<div class="section-body">
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
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
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" required>
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
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Adresse</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Rôles et permissions</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Rôle(s)</label>
                            @foreach($roles as $role)
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                       class="custom-control-input" id="role-{{ $role->id }}"
                                       {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="role-{{ $role->id }}">
                                    {{ ucfirst($role->name) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4>Photo de profil</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="file" name="profile_photo" class="form-control" accept="image/*">
                            <small class="text-muted">Formats acceptés: JPG, PNG. Max 2MB</small>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">Annuler</a>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Créer l'utilisateur
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
