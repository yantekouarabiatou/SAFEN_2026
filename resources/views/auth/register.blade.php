@extends('layouts.guest')

@section('title', 'Inscription - AFRI-HERITAGE')

@section('content')
<div class="min-vh-100 d-flex align-items-center py-5" style="background: linear-gradient(135deg, var(--beige) 0%, #ffffff 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                    <div class="row g-0">
                        <!-- Left Side - Branding -->
                        <div class="col-lg-5 d-none d-lg-block position-relative">
                            <div class="h-100 d-flex flex-column justify-content-between text-white p-5"
                                 style="background: linear-gradient(135deg, rgba(3, 122, 49, 0.85) 0%, rgba(94, 16, 16, 0.9) 100%);">

                                <!-- Logo -->
                                <div>
                                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width: 80px; height: 80px; backdrop-filter: blur(10px);">
                                        <i class="bi bi-flower1 fs-1"></i>
                                    </div>
                                    <h2 class="fw-bold">Rejoignez-nous !</h2>
                                    <p class="text-white-50 mb-4">Créez votre compte AFRI-HERITAGE</p>
                                </div>

                                <!-- Benefits -->
                                <div>
                                    <h5 class="fw-bold mb-4">Pourquoi nous rejoindre ?</h5>

                                    <div class="mb-3 d-flex align-items-start">
                                        <div class="rounded-circle bg-benin-yellow d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-check2 text-charcoal fs-5 fw-bold"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Visibilité accrue</h6>
                                            <small class="text-white-50">Touchez une clientèle nationale et internationale</small>
                                        </div>
                                    </div>

                                    <div class="mb-3 d-flex align-items-start">
                                        <div class="rounded-circle bg-benin-yellow d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-check2 text-charcoal fs-5 fw-bold"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Outils professionnels</h6>
                                            <small class="text-white-50">Dashboard, statistiques, gestion simplifiée</small>
                                        </div>
                                    </div>

                                    <div class="mb-3 d-flex align-items-start">
                                        <div class="rounded-circle bg-benin-yellow d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-check2 text-charcoal fs-5 fw-bold"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold">IA à votre service</h6>
                                            <small class="text-white-50">Descriptions automatiques, traductions, audio</small>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-benin-yellow d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-check2 text-charcoal fs-5 fw-bold"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Gratuité totale</h6>
                                            <small class="text-white-50">Inscription et fonctionnalités de base 100% gratuites</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="border-top border-white border-opacity-25 pt-4 mt-4">
                                    <div class="row g-3 text-center">
                                        <div class="col-4">
                                            <h4 class="fw-bold mb-0">500+</h4>
                                            <small class="text-white-50">Artisans</small>
                                        </div>
                                        <div class="col-4">
                                            <h4 class="fw-bold mb-0">12</h4>
                                            <small class="text-white-50">Départements</small>
                                        </div>
                                        <div class="col-4">
                                            <h4 class="fw-bold mb-0">98%</h4>
                                            <small class="text-white-50">Satisfaits</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Decorative -->
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="opacity: 0.05; z-index: 0;">
                                    <svg width="100%" height="100%">
                                        <defs>
                                            <pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse">
                                                <path d="M 50 0 L 0 0 0 50" fill="none" stroke="white" stroke-width="1"/>
                                            </pattern>
                                        </defs>
                                        <rect width="100%" height="100%" fill="url(#grid)"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Register Form -->
                        <div class="col-lg-7">
                            <div class="p-5">
                                <!-- Header -->
                                <div class="text-center mb-4">
                                    <h3 class="fw-bold text-charcoal mb-2">Créer un compte</h3>
                                    <p class="text-muted">Rejoignez la communauté AFRI-HERITAGE</p>
                                </div>

                                <!-- Role Selection (if not pre-selected) -->
                                @if(!request()->has('role'))
                                <div class="mb-4">
                                    <p class="fw-semibold text-charcoal mb-3">
                                        <i class="bi bi-person-badge me-2 text-benin-green"></i>
                                        Je m'inscris en tant que :
                                    </p>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <a href="{{ route('register', ['role' => 'client']) }}"
                                               class="card border-2 {{ request('role') == 'client' ? 'border-benin-green' : 'border-secondary' }} text-decoration-none h-100 hover-card">
                                                <div class="card-body text-center p-4">
                                                    <div class="rounded-circle bg-benin-green bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="bi bi-person  fs-3" style="color:#e0e0e0"></i>
                                                    </div>
                                                    <h5 class="fw-bold text-charcoal mb-2">Client</h5>
                                                    <p class="text-muted small mb-0">
                                                        Découvrez et achetez de l'artisanat authentique
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ route('register', ['role' => 'artisan']) }}"
                                               class="card border-2 {{ request('role') == 'artisan' ? 'border-benin-green' : 'border-secondary' }} text-decoration-none h-100 hover-card">
                                                <div class="card-body text-center p-4">
                                                    <div class="rounded-circle bg-benin-yellow bg-opacity-75 d-inline-flex align-items-center justify-content-center mb-3"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="bi bi-tools fs-3" style="color:#e0e0e0"></i>
                                                    </div>
                                                    <h5 class="fw-bold text-charcoal mb-2">Artisan</h5>
                                                    <p class="text-muted small mb-0">
                                                        Vendez vos créations et développez votre activité
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Register Form -->
                                <form method="POST" action="{{ route('register') }}" x-data="{ role: '{{ request('role', 'client') }}' }">
                                    @csrf

                                    <!-- Hidden Role Field -->
                                    <input type="hidden" name="role" :value="role">

                                    <!-- Role Selector (if role in URL) -->
                                    @if(request()->has('role'))
                                    <div class="alert alert-light border-2 border-benin-green mb-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi {{ request('role') == 'artisan' ? 'bi-tools' : 'bi-person' }} text-benin-green fs-5 me-2"></i>
                                                <strong>Inscription en tant que {{ request('role') == 'artisan' ? 'Artisan' : 'Client' }}</strong>
                                            </div>
                                            <a href="{{ route('register') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                                <i class="bi bi-arrow-left me-1"></i> Changer
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="row g-3">
                                        <!-- Name -->
                                        <div class="col-md-12">
                                            <label for="name" class="form-label fw-semibold text-charcoal">
                                                <i class="bi bi-person-fill me-2 text-benin-green"></i>
                                                Nom complet <span class="text-danger">*</span>
                                            </label>
                                            <input id="name"
                                                   type="text"
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   required
                                                   autofocus
                                                   autocomplete="name"
                                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                   placeholder="Ex: Jean Koffi Soglo"
                                                   style="border-radius: 12px; border: 2px solid #e0e0e0;">
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6">
                                            <label for="email" class="form-label fw-semibold text-charcoal">
                                                <i class="bi bi-envelope-fill me-2 text-benin-green"></i>
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input id="email"
                                                   type="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   required
                                                   autocomplete="username"
                                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                                   placeholder="votre@email.com"
                                                   style="border-radius: 12px; border: 2px solid #e0e0e0;">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- Phone -->
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label fw-semibold text-charcoal">
                                                <i class="bi bi-telephone-fill me-2 text-benin-green"></i>
                                                Téléphone <span class="text-danger">*</span>
                                            </label>
                                            <input id="phone"
                                                   type="tel"
                                                   name="phone"
                                                   value="{{ old('phone') }}"
                                                   required
                                                   class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                                   placeholder="Ex: +229 XX XX XX XX"
                                                   style="border-radius: 12px; border: 2px solid #e0e0e0;">
                                            @error('phone')
                                                <div class="invalid-feedback">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- City (for artisans) -->
                                        <div class="col-md-6" x-show="role === 'artisan'">
                                            <label for="city" class="form-label fw-semibold text-charcoal">
                                                <i class="bi bi-geo-alt-fill me-2 text-benin-green"></i>
                                                Ville
                                            </label>
                                            <select id="city"
                                                    name="city"
                                                    class="form-select form-select-lg @error('city') is-invalid @enderror"
                                                    style="border-radius: 12px; border: 2px solid #e0e0e0;">
                                                <option value="">Sélectionnez votre ville</option>
                                                <option value="Cotonou" {{ old('city') == 'Cotonou' ? 'selected' : '' }}>Cotonou</option>
                                                <option value="Porto-Novo" {{ old('city') == 'Porto-Novo' ? 'selected' : '' }}>Porto-Novo</option>
                                                <option value="Parakou" {{ old('city') == 'Parakou' ? 'selected' : '' }}>Parakou</option>
                                                <option value="Abomey" {{ old('city') == 'Abomey' ? 'selected' : '' }}>Abomey</option>
                                                <option value="Ouidah" {{ old('city') == 'Ouidah' ? 'selected' : '' }}>Ouidah</option>
                                                <option value="Natitingou" {{ old('city') == 'Natitingou' ? 'selected' : '' }}>Natitingou</option>
                                                <option value="Bohicon" {{ old('city') == 'Bohicon' ? 'selected' : '' }}>Bohicon</option>
                                                <option value="Lokossa" {{ old('city') == 'Lokossa' ? 'selected' : '' }}>Lokossa</option>
                                                <option value="Autre">Autre</option>
                                            </select>
                                            @error('city')
                                                <div class="invalid-feedback">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- Language -->
                                        <div class="col-md-6">
                                            <label for="language" class="form-label fw-semibold text-charcoal">
                                                <i class="bi bi-translate me-2 text-benin-green"></i>
                                                Langue préférée
                                            </label>
                                            <select id="language"
                                                    name="language"
                                                    class="form-select form-select-lg @error('language') is-invalid @enderror"
                                                    style="border-radius: 12px; border: 2px solid #e0e0e0;">
                                                <option value="fr" {{ old('language', 'fr') == 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                                                <option value="fon" {{ old('language') == 'fon' ? 'selected' : '' }}>🇧🇯 Fon</option>
                                            </select>
                                        </div>

                                        <!-- Password -->
                                        <div class="col-md-6">
                                            <label for="password" class="form-label fw-semibold text-charcoal">
                                                <i class="bi bi-lock-fill me-2 text-benin-green"></i>
                                                Mot de passe <span class="text-danger">*</span>
                                            </label>
                                            <div class="position-relative">
                                                <input id="password"
                                                       type="password"
                                                       name="password"
                                                       required
                                                       autocomplete="new-password"
                                                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                                                       placeholder="Min. 8 caractères"
                                                       style="border-radius: 12px; border: 2px solid #e0e0e0; padding-right: 45px;">
                                                <button type="button"
                                                        class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted"
                                                        onclick="togglePassword('password', 'toggleIcon1')"
                                                        style="text-decoration: none;">
                                                    <i class="bi bi-eye" id="toggleIcon1"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback d-block">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label fw-semibold text-charcoal">
                                                <i class="bi bi-lock-fill me-2 text-benin-green"></i>
                                                Confirmer <span class="text-danger">*</span>
                                            </label>
                                            <div class="position-relative">
                                                <input id="password_confirmation"
                                                       type="password"
                                                       name="password_confirmation"
                                                       required
                                                       autocomplete="new-password"
                                                       class="form-control form-control-lg"
                                                       placeholder="Répétez le mot de passe"
                                                       style="border-radius: 12px; border: 2px solid #e0e0e0; padding-right: 45px;">
                                                <button type="button"
                                                        class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted"
                                                        onclick="togglePassword('password_confirmation', 'toggleIcon2')"
                                                        style="text-decoration: none;">
                                                    <i class="bi bi-eye" id="toggleIcon2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Terms Checkbox -->
                                    <div class="form-check mt-4">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="terms"
                                               id="terms"
                                               required
                                               style="border-radius: 6px;">
                                        <label class="form-check-label text-muted small" for="terms">
                                            J'accepte les <a href="{{ route('terms') }}" target="_blank" class="text-benin-green">conditions générales</a>
                                            et la <a href="{{ route('privacy') }}" target="_blank" class="text-benin-green">politique de confidentialité</a>
                                        </label>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit"
                                            class="btn btn-benin-green btn-lg w-100 mt-4 fw-bold"
                                            style="border-radius: 12px; padding: 14px;">
                                        <i class="bi bi-person-plus me-2"></i>Créer mon compte
                                    </button>

                                    <!-- Divider -->
                                    <div class="position-relative my-4">
                                        <hr class="text-muted">
                                        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
                                            ou
                                        </span>
                                    </div>

                                    <!-- Login Link -->
                                    <div class="text-center">
                                        <p class="text-muted mb-0">
                                            Vous avez déjà un compte ?
                                            <a href="{{ route('login') }}" class="text-benin-green fw-bold text-decoration-none">
                                                Connectez-vous
                                            </a>
                                        </p>
                                    </div>
                                </form>

                                <!-- Back to Home -->
                                <div class="text-center mt-4 pt-3 border-top">
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill">
                                        <i class="bi bi-arrow-left me-2"></i>Retour à l'accueil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: var(--benin-green) !important;
}
</style>
@endpush

@push('scripts')
<script>
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}
</script>
@endpush
@endsection
