@extends('layouts.guest')

@section('title', 'Inscription - TOTCHEMEGNON')

@push('styles')
<style>
    :root {
        --benin-green: #009639;
        --benin-yellow: #FCD116;
        --benin-red: #E8112D;
        --terracotta: #D4774E;
        --beige: #F5E6D3;
        --charcoal: #2C3E50;
        --light-gray: #f8f9fa;
        --border-radius: 15px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .register-container {
        min-height: 100vh;
        background: linear-gradient(135deg, rgba(245, 230, 211, 0.15) 0%, rgba(255, 255, 255, 1) 100%);
        position: relative;
        overflow: hidden;
    }

    .register-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23009639' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        z-index: 0;
    }

    .register-card {
        background: white;
        border-radius: 25px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(245, 230, 211, 0.3);
        backdrop-filter: blur(10px);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .register-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--benin-green), var(--benin-yellow), var(--benin-red));
        z-index: 2;
    }

    .brand-side {
        background: linear-gradient(135deg, rgba(0, 150, 57, 0.95) 0%, rgba(232, 17, 45, 0.95) 100%);
        position: relative;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .brand-side::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
    }

    .logo-badge {
        width: 90px;
        height: 90px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        transition: var(--transition);
    }

    .logo-badge:hover {
        transform: rotate(-5deg) scale(1.05);
        background: rgba(255, 255, 255, 0.3);
    }

    .logo-badge i {
        font-size: 2.5rem;
        color: white;
        text-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .benefit-item {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: var(--transition);
    }

    .benefit-item:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateX(5px);
    }

    .benefit-icon {
        width: 45px;
        height: 45px;
        background: var(--benin-yellow);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 1rem;
        box-shadow: 0 6px 15px rgba(252, 209, 22, 0.3);
    }

    .benefit-icon i {
        color: var(--charcoal);
        font-size: 1.2rem;
    }

    .stat-item {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 0.8rem;
        text-align: center;
        transition: var(--transition);
    }

    .stat-item:hover {
        background: rgba(255, 255, 255, 0.12);
        transform: translateY(-3px);
    }

    .form-side {
        padding: 3rem;
    }

    .role-card {
        border: 2px solid var(--beige);
        border-radius: var(--border-radius);
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        height: 100%;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .role-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: transparent;
        transition: var(--transition);
    }

    .role-card:hover {
        border-color: var(--benin-green);
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 150, 57, 0.1);
    }

    .role-card.active {
        border-color: var(--benin-green);
        background: linear-gradient(to bottom, rgba(0, 150, 57, 0.02), rgba(252, 209, 22, 0.02));
    }

    .role-card.active::before {
        background: linear-gradient(90deg, var(--benin-green), var(--benin-yellow));
    }

    .role-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        transition: var(--transition);
    }

    .role-card.client .role-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .role-card.artisan .role-icon {
        background: linear-gradient(135deg, var(--benin-yellow) 0%, var(--terracotta) 100%);
        color: white;
    }

    .form-control-custom {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        font-size: 1rem;
        transition: var(--transition);
        background: white;
    }

    .form-control-custom:focus {
        border-color: var(--benin-green);
        box-shadow: 0 0 0 0.25rem rgba(0, 150, 57, 0.15);
        background: white;
    }

    .form-label-custom {
        font-weight: 600;
        color: var(--charcoal);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label-custom .icon {
        color: var(--benin-green);
        font-size: 1.1rem;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        z-index: 10;
        padding: 0.5rem;
        transition: var(--transition);
    }

    .password-toggle:hover {
        color: var(--benin-green);
    }

    .password-container {
        position: relative;
    }

    .terms-check {
        padding: 1rem;
        background: rgba(0, 150, 57, 0.03);
        border-radius: 12px;
        border: 1px solid rgba(0, 150, 57, 0.1);
    }

    .terms-check label {
        cursor: pointer;
        user-select: none;
    }

    .terms-check input[type="checkbox"] {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 2px solid #dee2e6;
        cursor: pointer;
        margin-right: 0.75rem;
    }

    .terms-check input[type="checkbox"]:checked {
        background-color: var(--benin-green);
        border-color: var(--benin-green);
    }

    .btn-register {
        background: linear-gradient(135deg, var(--benin-green) 0%, #007a2e 100%);
        border: none;
        border-radius: 12px;
        padding: 1.25rem;
        font-weight: 600;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .btn-register:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(0, 150, 57, 0.3);
    }

    .btn-register::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.3), transparent);
        transform: rotate(30deg);
        transition: var(--transition);
        opacity: 0;
    }

    .btn-register:hover::after {
        animation: shine 1.5s ease;
    }

    @keyframes shine {
        0% { opacity: 0; transform: translateX(-100%) rotate(30deg); }
        50% { opacity: 1; }
        100% { opacity: 0; transform: translateX(100%) rotate(30deg); }
    }

    .divider-custom {
        position: relative;
        text-align: center;
        margin: 2rem 0;
    }

    .divider-custom::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, #dee2e6, transparent);
    }

    .divider-custom span {
        background: white;
        padding: 0 1.5rem;
        color: #6c757d;
        font-size: 0.9rem;
        position: relative;
        z-index: 1;
    }

    .login-link {
        color: var(--benin-green);
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        position: relative;
    }

    .login-link:hover {
        color: #007a2e;
    }

    .login-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--benin-green);
        transition: var(--transition);
    }

    .login-link:hover::after {
        width: 100%;
    }

    @media (max-width: 991.98px) {
        .brand-side {
            padding: 2rem;
            border-radius: 25px 25px 0 0;
        }

        .form-side {
            padding: 2rem;
        }

        .register-card {
            border-radius: 25px;
        }
    }

    @media (max-width: 575.98px) {
        .role-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .benefit-item {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="register-container d-flex align-items-center py-4 py-lg-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="register-card">
                    <div class="row g-0">
                        <!-- Brand Side -->
                        <div class="col-lg-5 d-none d-lg-block">
                            <div class="brand-side h-100 text-white">
                                <!-- Logo & Branding -->
                                <div>
                                    <div class="logo-badge">
                                        <i class="bi bi-flower1"></i>
                                    </div>
                                    <h2 class="fw-bold mb-3">Rejoignez TOTCHEMEGNON</h2>
                                    <p class="text-white-75 mb-4">La plateforme qui valorise l'artisanat béninois</p>
                                </div>

                                <!-- Benefits -->
                                <div class="mt-4">
                                    <h5 class="fw-bold mb-4">Pourquoi nous rejoindre ?</h5>

                                    <div class="benefit-item d-flex align-items-center">
                                        <div class="benefit-icon">
                                            <i class="bi bi-eye-fill"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Visibilité internationale</h6>
                                            <small class="text-white-75">Exposez vos créations à un public mondial</small>
                                        </div>
                                    </div>

                                    <div class="benefit-item d-flex align-items-center">
                                        <div class="benefit-icon">
                                            <i class="bi bi-graph-up"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Croissance assurée</h6>
                                            <small class="text-white-75">Développez votre activité avec nos outils</small>
                                        </div>
                                    </div>

                                    <div class="benefit-item d-flex align-items-center">
                                        <div class="benefit-icon">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Sécurisé et fiable</h6>
                                            <small class="text-white-75">Transactions sécurisées et paiements garantis</small>
                                        </div>
                                    </div>

                                    <div class="benefit-item d-flex align-items-center">
                                        <div class="benefit-icon">
                                            <i class="bi bi-headset"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Support dédié</h6>
                                            <small class="text-white-75">Notre équipe vous accompagne à chaque étape</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="mt-5 pt-4 border-top border-white-10">
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="stat-item">
                                                <h4 class="fw-bold mb-1">500+</h4>
                                                <small class="text-white-75">Artisans</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="stat-item">
                                                <h4 class="fw-bold mb-1">12</h4>
                                                <small class="text-white-75">Départements</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="stat-item">
                                                <h4 class="fw-bold mb-1">98%</h4>
                                                <small class="text-white-75">Satisfaits</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Side -->
                        <div class="col-lg-7">
                            <div class="form-side">
                                <!-- Header -->
                                <div class="text-center mb-5">
                                    <h3 class="fw-bold text-charcoal mb-2">Créer votre compte</h3>
                                    <p class="text-muted">Rejoignez notre communauté et découvrez l'artisanat béninois</p>
                                </div>

                                <!-- Role Selection -->
                                @if(!request()->has('role'))
                                <div class="mb-5">
                                    <p class="form-label-custom mb-3">
                                        <span class="icon"><i class="bi bi-person-badge"></i></span>
                                        Je m'inscris en tant que :
                                    </p>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="role-card client" onclick="window.location.href='{{ route('register', ['role' => 'client']) }}'">
                                                <div class="role-icon">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                                <h5 class="fw-bold text-charcoal mb-2">Client</h5>
                                                <p class="text-muted small mb-0">
                                                    Découvrez et achetez de l'artisanat authentique
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="role-card artisan" onclick="window.location.href='{{ route('register', ['role' => 'artisan']) }}'">
                                                <div class="role-icon">
                                                    <i class="bi bi-tools"></i>
                                                </div>
                                                <h5 class="fw-bold text-charcoal mb-2">Artisan</h5>
                                                <p class="text-muted small mb-0">
                                                    Vendez vos créations et développez votre activité
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Registration Form -->
                                <form method="POST" action="{{ route('register') }}" x-data="{ role: '{{ request('role', 'client') }}' }">
                                    @csrf

                                    <input type="hidden" name="role" :value="role">

                                    @if(request()->has('role'))
                                    <div class="alert alert-light border-start border-5 border-benin-green mb-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="role-icon {{ request('role') == 'artisan' ? 'bg-benin-yellow' : 'bg-primary' }} text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="bi {{ request('role') == 'artisan' ? 'bi-tools' : 'bi-person' }}"></i>
                                                </div>
                                                <div>
                                                    <strong>Inscription en tant que {{ request('role') == 'artisan' ? 'Artisan' : 'Client' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ request('role') == 'artisan' ? 'Créez votre boutique en ligne' : 'Accédez à des produits uniques' }}</small>
                                                </div>
                                            </div>
                                            <a href="{{ route('register') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                <i class="bi bi-arrow-left me-1"></i> Changer
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="row g-4">
                                        <!-- Name -->
                                        <div class="col-12">
                                            <label class="form-label-custom">
                                                <span class="icon"><i class="bi bi-person-fill"></i></span>
                                                Nom complet <span class="text-danger">*</span>
                                            </label>
                                            <input id="name"
                                                   type="text"
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   required
                                                   autofocus
                                                   autocomplete="name"
                                                   class="form-control form-control-custom @error('name') is-invalid @enderror"
                                                   placeholder="Ex: Jean Koffi Soglo">
                                            @error('name')
                                            <div class="invalid-feedback d-flex align-items-center">
                                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6">
                                            <label class="form-label-custom">
                                                <span class="icon"><i class="bi bi-envelope-fill"></i></span>
                                                Adresse email <span class="text-danger">*</span>
                                            </label>
                                            <input id="email"
                                                   type="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   required
                                                   autocomplete="username"
                                                   class="form-control form-control-custom @error('email') is-invalid @enderror"
                                                   placeholder="votre@email.com">
                                            @error('email')
                                            <div class="invalid-feedback d-flex align-items-center">
                                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Phone -->
                                        <div class="col-md-6">
                                            <label class="form-label-custom">
                                                <span class="icon"><i class="bi bi-telephone-fill"></i></span>
                                                Téléphone <span class="text-danger">*</span>
                                            </label>
                                            <input id="phone"
                                                   type="tel"
                                                   name="phone"
                                                   value="{{ old('phone') }}"
                                                   required
                                                   class="form-control form-control-custom @error('phone') is-invalid @enderror"
                                                   placeholder="+229 XX XX XX XX">
                                            @error('phone')
                                            <div class="invalid-feedback d-flex align-items-center">
                                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- City (for artisans) -->
                                        <div class="col-md-6" x-show="role === 'artisan'">
                                            <label class="form-label-custom">
                                                <span class="icon"><i class="bi bi-geo-alt-fill"></i></span>
                                                Ville
                                            </label>
                                            <select id="city"
                                                    name="city"
                                                    class="form-select form-control-custom @error('city') is-invalid @enderror">
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
                                            <div class="invalid-feedback d-flex align-items-center">
                                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Language -->
                                        <div class="col-md-6">
                                            <label class="form-label-custom">
                                                <span class="icon"><i class="bi bi-translate"></i></span>
                                                Langue préférée
                                            </label>
                                            <select id="language"
                                                    name="language"
                                                    class="form-select form-control-custom @error('language') is-invalid @enderror">
                                                <option value="fr" {{ old('language', 'fr') == 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                                                <option value="fon" {{ old('language') == 'fon' ? 'selected' : '' }}>🇧🇯 Fon</option>
                                            </select>
                                        </div>

                                        <!-- Password -->
                                        <div class="col-md-6">
                                            <label class="form-label-custom">
                                                <span class="icon"><i class="bi bi-lock-fill"></i></span>
                                                Mot de passe <span class="text-danger">*</span>
                                            </label>
                                            <div class="password-container">
                                                <input id="password"
                                                       type="password"
                                                       name="password"
                                                       required
                                                       autocomplete="new-password"
                                                       class="form-control form-control-custom @error('password') is-invalid @enderror"
                                                       placeholder="Min. 8 caractères">
                                                <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                                                    <i class="bi bi-eye" id="toggleIcon1"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                            <div class="invalid-feedback d-flex align-items-center">
                                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="col-md-6">
                                            <label class="form-label-custom">
                                                <span class="icon"><i class="bi bi-lock-fill"></i></span>
                                                Confirmer le mot de passe <span class="text-danger">*</span>
                                            </label>
                                            <div class="password-container">
                                                <input id="password_confirmation"
                                                       type="password"
                                                       name="password_confirmation"
                                                       required
                                                       autocomplete="new-password"
                                                       class="form-control form-control-custom"
                                                       placeholder="Répétez le mot de passe">
                                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                                                    <i class="bi bi-eye" id="toggleIcon2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Terms -->
                                    <div class="terms-check mt-4">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="terms"
                                                   id="terms"
                                                   required
                                                   {{ old('terms') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="terms">
                                                J'accepte les <a href="{{ route('terms') }}" target="_blank" class="login-link">conditions générales</a>
                                                et la <a href="{{ route('privacy') }}" target="_blank" class="login-link">politique de confidentialité</a>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <button type="submit" class="btn btn-register text-white w-100 mt-5">
                                        <i class="bi bi-person-plus me-2"></i>Créer mon compte
                                    </button>

                                    <!-- Divider -->
                                    <div class="divider-custom mt-5">
                                        <span>ou</span>
                                    </div>

                                    <!-- Login Link -->
                                    <div class="text-center mt-4">
                                        <p class="text-muted mb-0">
                                            Vous avez déjà un compte ?
                                            <a href="{{ route('login') }}" class="login-link ms-1">Connectez-vous</a>
                                        </p>
                                    </div>
                                </form>

                                <!-- Back to Home -->
                                <div class="text-center mt-5 pt-4 border-top">
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4">
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
@endsection

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

// Add active class to selected role cards
document.addEventListener('DOMContentLoaded', function() {
    const role = '{{ request('role', '') }}';
    if (role) {
        const card = document.querySelector(`.role-card.${role}`);
        if (card) {
            card.classList.add('active');
        }
    }
});
</script>
@endpush
