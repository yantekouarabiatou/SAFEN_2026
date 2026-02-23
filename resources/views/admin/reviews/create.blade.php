@extends('layouts.app')

@section('title', 'Laisser un avis')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-benin-green text-white">
                    <h4 class="mb-0"><i class="fas fa-star me-2"></i>Partagez votre expérience</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Votre avis aide notre communauté à grandir et permet aux artisans de s'améliorer.
                    </p>

                    <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm">
                        @csrf

                        {{-- Type d'élément à noter --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Que souhaitez-vous évaluer ?</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reviewable_type" id="typeProduct" value="App\Models\Product" checked>
                                    <label class="form-check-label" for="typeProduct">
                                        <i class="fas fa-box me-1"></i>Produit
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reviewable_type" id="typeArtisan" value="App\Models\Artisan">
                                    <label class="form-check-label" for="typeArtisan">
                                        <i class="fas fa-user me-1"></i>Artisan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reviewable_type" id="typeVendor" value="App\Models\Vendor">
                                    <label class="form-check-label" for="typeVendor">
                                        <i class="fas fa-store me-1"></i>Vendeur
                                    </label>
                                </div>
                            </div>
                            @error('reviewable_type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sélection de l'élément --}}
                        <div class="mb-4" id="productSelect" style="display: block;">
                            <label class="form-label fw-bold">Choisissez un produit</label>
                            <select name="reviewable_id" class="form-control" id="productSelectElement">
                                <option value="">Sélectionnez un produit</option>
                                @foreach($products ?? [] as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4" id="artisanSelect" style="display: none;">
                            <label class="form-label fw-bold">Choisissez un artisan</label>
                            <select name="reviewable_id" class="form-control" id="artisanSelectElement">
                                <option value="">Sélectionnez un artisan</option>
                                @foreach($artisans ?? [] as $artisan)
                                    <option value="{{ $artisan->id }}">{{ $artisan->user->name ?? $artisan->business_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4" id="vendorSelect" style="display: none;">
                            <label class="form-label fw-bold">Choisissez un vendeur</label>
                            <select name="reviewable_id" class="form-control" id="vendorSelectElement">
                                <option value="">Sélectionnez un vendeur</option>
                                @foreach($vendors ?? [] as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Note par étoiles --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Votre note</label>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star" data-rating="{{ $i }}">
                                        <i class="far fa-star fa-2x text-warning" style="cursor: pointer;"></i>
                                    </span>
                                @endfor
                                <input type="hidden" name="rating" id="rating" value="" required>
                            </div>
                            @error('rating')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Commentaire --}}
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">Votre commentaire</label>
                            <textarea name="comment" id="comment" rows="5" class="form-control" placeholder="Partagez votre expérience..." required></textarea>
                            @error('comment')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Options supplémentaires --}}
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="anonymous" id="anonymous">
                                <label class="form-check-label" for="anonymous">
                                    Publier anonymement
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Je certifie que cet avis est basé sur mon expérience réelle
                                </label>
                            </div>
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-benin-green">
                                <i class="fas fa-paper-plane"></i> Publier mon avis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .star {
        cursor: pointer;
        margin-right: 5px;
        transition: all 0.2s ease;
    }
    
    .star:hover {
        transform: scale(1.2);
    }
    
    .star.active i,
    .star.active ~ .star i {
        font-weight: 900;
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

    // Gestion des types d'éléments
    $('input[name="reviewable_type"]').change(function() {
        var type = $(this).val();
        
        $('#productSelect, #artisanSelect, #vendorSelect').hide();
        
        if (type.includes('Product')) {
            $('#productSelect').show();
            $('#productSelectElement').attr('name', 'reviewable_id');
            $('#artisanSelectElement, #vendorSelectElement').removeAttr('name');
        } else if (type.includes('Artisan')) {
            $('#artisanSelect').show();
            $('#artisanSelectElement').attr('name', 'reviewable_id');
            $('#productSelectElement, #vendorSelectElement').removeAttr('name');
        } else if (type.includes('Vendor')) {
            $('#vendorSelect').show();
            $('#vendorSelectElement').attr('name', 'reviewable_id');
            $('#productSelectElement, #artisanSelectElement').removeAttr('name');
        }
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
        }
    });
});
</script>
@endpush
@endsection