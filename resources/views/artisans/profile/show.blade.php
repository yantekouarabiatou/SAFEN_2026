@extends('layouts.admin')

@section('title', 'Profil de ' . $artisan->business_name)

@section('content')
<section class="section">
    <div class="section-body">
        <!-- En-tête avec image de couverture -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm overflow-hidden" style="border: none;">
                    <div class="position-relative">
                        <!-- Image de couverture -->
                        <div class="cover-image" 
                             style="height: 200px; background: linear-gradient(135deg, #28a745 0%, #ffc107 100%);">
                        </div>
                        
                        <!-- Photo de profil -->
                        <div class="position-absolute" style="bottom: -50px; left: 30px;">
                            @if($artisan->user->photo)
                                <img src="{{ asset('storage/' . $artisan->user->photo) }}"
                                     alt="{{ $artisan->user->prenom }} {{ $artisan->user->nom }}"
                                     class="rounded-circle border border-4 border-white shadow-lg"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center border border-4 border-white shadow-lg"
                                     style="width: 120px; height: 120px; font-size: 40px;">
                                    <i class="fas fa-user-tie text-success"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="position-absolute" style="top: 20px; right: 20px;">
                            @auth
                                @if(auth()->id() == $artisan->user_id)
                                    <a href="{{ route('artisan.profile.edit', $artisan->id) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                @endif
                            @endauth
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>
                    
                    <!-- Informations du header -->
                    <div class="card-body pt-5 pb-3" style="margin-top: 60px;">
                        <div class="row">
                            <div class="col-md-8">
                                <h2 class="mb-1 text-success">{{ $artisan->business_name }}</h2>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-user-tie text-warning mr-2"></i>
                                    {{ $artisan->user->prenom }} {{ $artisan->user->nom }} • {{ $artisan->craft_label }}
                                </p>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="badge badge-{{ $artisan->verified ? 'success' : 'warning' }}">
                                        <i class="fas fa-{{ $artisan->verified ? 'check-circle' : 'clock' }} mr-1"></i>
                                        {{ $artisan->verified ? 'Vérifié' : 'En attente' }}
                                    </span>
                                    <span class="badge badge-{{ $artisan->featured ? 'warning' : 'secondary' }}">
                                        <i class="fas fa-{{ $artisan->featured ? 'star' : 'circle' }} mr-1"></i>
                                        {{ $artisan->featured ? 'Mise en avant' : 'Standard' }}
                                    </span>
                                    @if($artisan->years_experience)
                                        <span class="badge badge-info">
                                            <i class="fas fa-award mr-1"></i>
                                            {{ $artisan->years_experience }} ans d'expérience
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right">
                                <div class="rating-display mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= floor($artisan->rating_avg) ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="ml-2 font-weight-bold">{{ number_format($artisan->rating_avg, 1) }}/5</span>
                                    <small class="text-muted">({{ $artisan->reviews_count ?? 0 }} avis)</small>
                                </div>
                                <div class="text-muted">
                                    <i class="fas fa-eye mr-1"></i> {{ $artisan->views ?? 0 }} vues
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Colonne gauche: Informations détaillées -->
            <div class="col-lg-4">
                <!-- Carte de contact -->
                <div class="card shadow-sm mb-4" style="border-top: 3px solid #28a745;">
                    <div class="card-header bg-success text-white">
                        <h5><i class="fas fa-id-card mr-2"></i> Contact</h5>
                    </div>
                    <div class="card-body">
                        <div class="contact-info">
                            @if($artisan->user->email)
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-success text-white mr-3">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Email</small>
                                    <a href="mailto:{{ $artisan->user->email }}" class="text-success">
                                        {{ $artisan->user->email }}
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            @if($artisan->user->telephone)
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-warning text-white mr-3">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Téléphone principal</small>
                                    <a href="tel:{{ $artisan->user->telephone }}" class="text-warning">
                                        {{ $artisan->user->telephone }}
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            @if($artisan->whatsapp)
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-success text-white mr-3" style="background-color: #25D366 !important;">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">WhatsApp</small>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $artisan->whatsapp) }}" 
                                       target="_blank" 
                                       class="text-success" style="color: #25D366 !important;">
                                        {{ $artisan->whatsapp }}
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            @if($artisan->phone)
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-danger text-white mr-3">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Téléphone secondaire</small>
                                    <a href="tel:{{ $artisan->phone }}" class="text-danger">
                                        {{ $artisan->phone }}
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        @auth
                            @if(auth()->id() != $artisan->user_id)
                            <div class="mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#contactModal">
                                    <i class="fas fa-paper-plane mr-2"></i> Contacter l'artisan
                                </button>
                            </div>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Localisation -->
                <div class="card shadow-sm mb-4" style="border-top: 3px solid #ffc107;">
                    <div class="card-header bg-warning text-white">
                        <h5><i class="fas fa-map-marker-alt mr-2"></i> Localisation</h5>
                    </div>
                    <div class="card-body">
                        <div class="location-info">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-warning text-white mr-3">
                                    <i class="fas fa-city"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Ville</small>
                                    <span class="font-weight-bold">{{ $artisan->city ?? 'Non spécifié' }}</span>
                                </div>
                            </div>
                            
                            @if($artisan->neighborhood)
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-success text-white mr-3">
                                    <i class="fas fa-map-pin"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Quartier</small>
                                    <span class="font-weight-bold">{{ $artisan->neighborhood }}</span>
                                </div>
                            </div>
                            @endif
                            
                            @if($artisan->latitude && $artisan->longitude)
                            <div class="mt-3">
                                <div id="map" style="height: 200px; border-radius: 8px; overflow: hidden;"></div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Langues parlées -->
                @if($artisan->languages_spoken && count($artisan->languages_spoken) > 0)
                <div class="card shadow-sm mb-4" style="border-top: 3px solid #dc3545;">
                    <div class="card-header bg-danger text-white">
                        <h5><i class="fas fa-language mr-2"></i> Langues parlées</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($artisan->languages_spoken as $language)
                                <span class="badge badge-pill bg-light text-dark border">
                                    {{ ucfirst($language) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Statistiques -->
                <div class="card shadow-sm" style="border-top: 3px solid #28a745;">
                    <div class="card-header bg-success text-white">
                        <h5><i class="fas fa-chart-bar mr-2"></i> Statistiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <div class="stat-item text-center mb-4">
                                <div class="stat-value text-success">{{ $artisan->products_count ?? 0 }}</div>
                                <div class="stat-label">Produits</div>
                            </div>
                            <div class="stat-item text-center mb-4">
                                <div class="stat-value text-warning">{{ $artisan->orders_count ?? 0 }}</div>
                                <div class="stat-label">Commandes</div>
                            </div>
                            <div class="stat-item text-center mb-4">
                                <div class="stat-value text-danger">{{ $artisan->reviews_count ?? 0 }}</div>
                                <div class="stat-label">Avis</div>
                            </div>
                            <div class="stat-item text-center mb-4">
                                <div class="stat-value text-info">{{ $artisan->years_experience ?? 0 }} ans</div>
                                <div class="stat-label">Expérience</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite: Contenu principal -->
            <div class="col-lg-8">
                <!-- Biographie -->
                <div class="card shadow-sm mb-4" style="border-top: 3px solid #28a745;">
                    <div class="card-header bg-success text-white">
                        <h5><i class="fas fa-book-open mr-2"></i> À propos</h5>
                    </div>
                    <div class="card-body">
                        @if($artisan->bio)
                            <div class="bio-content">
                                {!! nl2br(e($artisan->bio)) !!}
                            </div>
                        @else
                            <p class="text-muted text-center py-3">
                                <i class="fas fa-info-circle fa-2x mb-3"></i><br>
                                Aucune biographie disponible pour le moment.
                            </p>
                        @endif
                        
                        @if($artisan->pricing_info)
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="text-warning mb-2"><i class="fas fa-tag mr-2"></i> Tarification</h6>
                            <p class="mb-0">{{ $artisan->pricing_info }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Produits -->
                <div class="card shadow-sm mb-4" style="border-top: 3px solid #ffc107;">
                    <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-boxes mr-2"></i> Produits</h5>
                        @auth
                            @if(auth()->id() == $artisan->user_id)
                                <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Ajouter un produit
                                </a>
                            @endif
                        @endauth
                    </div>
                    <div class="card-body">
                        @if($artisan->products && $artisan->products->count() > 0)
                            <div class="row">
                                @foreach($artisan->products->take(6) as $product)
                                    <div class="col-md-4 mb-4">
                                        <div class="product-card card h-100 border hover-lift">
                                            @if($product->primaryImage)
                                                <img src="{{ $product->primaryImage->full_url }}" 
                                                     class="card-img-top" 
                                                     alt="{{ $product->name }}"
                                                     style="height: 180px; object-fit: cover;">
                                            @else
                                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                     style="height: 180px;">
                                                    <i class="fas fa-box-open fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="card-body">
                                                <h6 class="card-title text-truncate">{{ $product->name }}</h6>
                                                <p class="card-text small text-muted mb-2">
                                                    {{ $product->category_label }}
                                                </p>
                                                <p class="card-text font-weight-bold text-success mb-0">
                                                    {{ $product->formatted_price }}
                                                </p>
                                            </div>
                                            <div class="card-footer bg-transparent border-top-0">
                                                <a href="{{ route('products.show', $product->slug ?? $product->id) }}" 
                                                   class="btn btn-outline-success btn-sm btn-block">
                                                    <i class="fas fa-eye mr-1"></i> Voir
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($artisan->products->count() > 6)
                                <div class="text-center mt-3">
                                    <a href="{{ route('artisan.products.index', $artisan->id) }}" class="btn btn-outline-warning">
                                        <i class="fas fa-arrow-right mr-2"></i> Voir tous les produits ({{ $artisan->products->count() }})
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                <p>Aucun produit disponible pour le moment.</p>
                                @auth
                                    @if(auth()->id() == $artisan->user_id)
                                        <a href="{{ route('products.create') }}" class="btn btn-success">
                                            <i class="fas fa-plus mr-2"></i> Ajouter votre premier produit
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Avis -->
                <div class="card shadow-sm" style="border-top: 3px solid #dc3545;">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-star mr-2"></i> Avis clients</h5>
                    </div>
                    <div class="card-body">
                        @if($artisan->reviews && $artisan->reviews->count() > 0)
                            <div class="row mb-4">
                                <div class="col-md-4 text-center">
                                    <div class="display-4 text-warning mb-2">{{ number_format($artisan->rating_avg, 1) }}</div>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= floor($artisan->rating_avg) ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted">Basé sur {{ $artisan->reviews->count() }} avis</small>
                                </div>
                                <div class="col-md-8">
                                    @php
                                        $ratings = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                                        foreach($artisan->reviews as $review) {
                                            $ratings[$review->rating]++;
                                        }
                                    @endphp
                                    
                                    @for($i = 5; $i >= 1; $i--)
                                        <div class="d-flex align-items-center mb-2">
                                            <small class="text-muted" style="width: 30px;">{{ $i }} étoiles</small>
                                            <div class="progress flex-grow-1 mx-2" style="height: 8px;">
                                                <div class="progress-bar bg-warning" 
                                                     style="width: {{ $artisan->reviews->count() > 0 ? ($ratings[$i] / $artisan->reviews->count() * 100) : 0 }}%"></div>
                                            </div>
                                            <small class="text-muted" style="width: 40px;">{{ $ratings[$i] }}</small>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            
                            <!-- Liste des avis -->
                            <div class="reviews-list">
                                @foreach($artisan->reviews->take(3) as $review)
                                    <div class="review-item border-bottom pb-3 mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <strong>{{ $review->user->prenom }} {{ $review->user->nom }}</strong>
                                                <div class="small text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                        </div>
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($artisan->reviews->count() > 3)
                                <div class="text-center mt-3">
                                    <a href="{{ route('artisan.reviews.index', $artisan->id) }}" class="btn btn-outline-danger">
                                        <i class="fas fa-arrow-right mr-2"></i> Voir tous les avis
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-star fa-3x mb-3"></i>
                                <p>Aucun avis pour le moment.</p>
                                <small class="d-block">Soyez le premier à laisser un avis !</small>
                            </div>
                        @endif
                        
                        @auth
                            @if(auth()->id() != $artisan->user_id)
                                <div class="mt-4 pt-3 border-top">
                                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#reviewModal1">
                                        <i class="fas fa-star mr-2"></i> Laisser un avis
                                    </button>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de contact -->
@auth
@if(auth()->id() != $artisan->user_id)
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-top: 3px solid #28a745;">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Contacter {{ $artisan->business_name }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('messages.send') }}" method="POST">
                @csrf
                <input type="hidden" name="artisan_id" value="{{ $artisan->id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-success">Sujet</label>
                        <input type="text" name="subject" class="form-control border-success" 
                               placeholder="Ex: Demande de devis pour un produit" required>
                    </div>
                    <div class="form-group">
                        <label class="text-success">Votre message</label>
                        <textarea name="message" class="form-control border-success" rows="6" 
                                  placeholder="Décrivez votre demande en détail..." required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-warning">Budget estimé (optionnel)</label>
                                <input type="number" name="budget" class="form-control border-warning" 
                                       placeholder="Ex: 50000" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-danger">Date souhaitée (optionnel)</label>
                                <input type="date" name="desired_date" class="form-control border-danger" 
                                       min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane mr-1"></i> Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endauth

{{-- Modal pour laisser un avis --}}
<div class="modal fade" id="reviewModal1" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-benin-green text-white">
                <h5 class="modal-title" id="reviewModalLabel">
                    <i class="fas fa-star mr-2"></i>
                    Laisser un avis pour {{ $artisan->user->name ?? $artisan->business_name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm">
                @csrf
                <div class="modal-body">
                    {{-- Informations cachées --}}
                    <input type="hidden" name="reviewable_type" value="App\Models\Artisan">
                    <input type="hidden" name="reviewable_id" value="{{ $artisan->id }}">

                    {{-- Note par étoiles --}}
                    <div class="form-group text-center mb-4">
                        <label class="form-label fw-bold d-block">Votre note</label>
                        <div class="rating-stars d-inline-block">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star" data-rating="{{ $i }}">
                                    <i class="far fa-star fa-2x text-warning" style="cursor: pointer; margin: 0 3px;"></i>
                                </span>
                            @endfor
                            <input type="hidden" name="rating" id="rating" value="" required>
                        </div>
                        <small class="text-muted d-block mt-2">Cliquez sur les étoiles pour donner votre note</small>
                    </div>

                    {{-- Commentaire --}}
                    <div class="form-group mb-4">
                        <label for="comment" class="form-label fw-bold">Votre commentaire</label>
                        <textarea name="comment" id="comment" rows="4" class="form-control" 
                                  placeholder="Partagez votre expérience avec cet artisan..." required></textarea>
                    </div>

                    {{-- Options supplémentaires --}}
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="anonymous" id="anonymous">
                            <label class="custom-control-label" for="anonymous">
                                Publier anonymement
                            </label>
                        </div>
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" name="terms" id="terms" required>
                            <label class="custom-control-label" for="terms">
                                Je certifie que cet avis est basé sur mon expérience réelle
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-benin-green" id="submitReview">
                        <i class="fas fa-paper-plane"></i> Publier mon avis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .star {
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-block;
    }
    
    .star:hover {
        transform: scale(1.2);
    }
    
    .star.active i,
    .star.active ~ .star i {
        font-weight: 900;
    }
    
    .modal-header .close {
        color: white;
        opacity: 0.8;
    }
    
    .modal-header .close:hover {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Gestion des étoiles
    $('.star').click(function() {
        var rating = $(this).data('rating');
        $('#rating').val(rating);
        
        $('.star i').removeClass('fas').addClass('far');
        $('.star').each(function(index) {
            if (index < rating) {
                $(this).find('i').removeClass('far').addClass('fas');
            }
        });
    });

    // Réinitialiser les étoiles quand le modal se ferme
    $('#reviewModal1').on('hidden.bs.modal', function() {
        $('#rating').val('');
        $('.star i').removeClass('fas').addClass('far');
        $('#comment').val('');
        $('#anonymous').prop('checked', false);
        $('#terms').prop('checked', false);
    });

    // Validation du formulaire
    $('#reviewForm').submit(function(e) {
        if (!$('#rating').val()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez donner une note'
            });
            return false;
        }
        
        if (!$('#terms').prop('checked')) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Vous devez accepter les conditions'
            });
            return false;
        }
    });

    // Afficher un message de succès si présent
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès !',
            text: '{{ session('success') }}',
            timer: 3000
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: '{{ session('error') }}'
        });
    @endif
});
</script>
@endpush
@endsection

@section('styles')
<style>
    .cover-image {
        background: linear-gradient(135deg, #28a745 0%, #ffc107 100%) !important;
    }
    
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #e9ecef;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .rating-star {
        cursor: pointer;
        color: #e9ecef;
        margin: 0 2px;
        transition: color 0.2s;
    }
    
    .rating-star.active,
    .rating-star:hover {
        color: #ffc107;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .bio-content {
        line-height: 1.8;
        font-size: 1.05rem;
    }
    
    .badge.badge-warning {
        color: #212529;
    }
    
    .progress-bar.bg-warning {
        background-color: #ffc107 !important;
    }
    
    .btn-outline-success {
        color: #28a745;
        border-color: #28a745;
    }
    
    .btn-outline-success:hover {
        background-color: #28a745;
        color: white;
    }
    
    .btn-outline-warning {
        color: #ffc107;
        border-color: #ffc107;
    }
    
    .btn-outline-warning:hover {
        background-color: #ffc107;
        color: #212529;
    }
    
    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Gestion des étoiles de notation
        $('.rating-star').on('click', function() {
            const rating = $(this).data('rating');
            $('#ratingValue').val(rating);
            
            $('.rating-star').each(function() {
                const starRating = $(this).data('rating');
                if (starRating <= rating) {
                    $(this).addClass('active').removeClass('far').addClass('fas');
                } else {
                    $(this).removeClass('active').removeClass('fas').addClass('far');
                }
            });
        });
        
        // Survol des étoiles
        $('.rating-star').hover(
            function() {
                const rating = $(this).data('rating');
                $('.rating-star').each(function() {
                    const starRating = $(this).data('rating');
                    if (starRating <= rating) {
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                });
            },
            function() {
                const currentRating = $('#ratingValue').val();
                $('.rating-star').each(function() {
                    const starRating = $(this).data('rating');
                    if (starRating <= currentRating) {
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                });
            }
        );
        
        // Initialisation de la carte
        @if($artisan->latitude && $artisan->longitude)
            function initMap() {
                const location = { lat: {{ $artisan->latitude }}, lng: {{ $artisan->longitude }} };
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 15,
                    center: location,
                    styles: [
                        {
                            featureType: "poi",
                            elementType: "labels",
                            stylers: [{ visibility: "off" }]
                        }
                    ]
                });
                
                new google.maps.Marker({
                    position: location,
                    map: map,
                    title: "{{ $artisan->business_name }}",
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        fillColor: "#28a745",
                        fillOpacity: 1,
                        strokeColor: "#ffffff",
                        strokeWeight: 2
                    }
                });
            }
            
            // Charger l'API Google Maps si elle n'est pas déjà chargée
            if (typeof google === 'undefined') {
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap`;
                script.async = true;
                script.defer = true;
                document.head.appendChild(script);
                
                window.initMap = initMap;
            } else {
                initMap();
            }
        @endif
        
        // Animation des cartes
        $('.card').hover(
            function() {
                $(this).addClass('shadow-lg');
            },
            function() {
                $(this).removeClass('shadow-lg');
            }
        );
        
        // Initialisation des tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection