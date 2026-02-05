@extends('layouts.app')

@section('title', 'Finaliser la commande')

@push('styles')
<style>
    :root {
        --benin-green: #009639;
        --benin-yellow: #FCD116;
        --benin-red: #E8112D;
    }

    .checkout-container {
        max-width: 1200px;
        margin: 3rem auto;
        padding: 0 1rem;
    }

    .checkout-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .checkout-header {
        background: linear-gradient(135deg, var(--benin-green), var(--benin-red));
        color: white;
        padding: 2rem;
        border-radius: 20px 20px 0 0;
        margin: -2rem -2rem 2rem;
    }

    .form-control, .form-select {
        border: 2px solid #e3e6f0;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--benin-green);
        box-shadow: 0 0 0 0.2rem rgba(0, 150, 57, 0.1);
    }

    .payment-method {
        border: 2px solid #e3e6f0;
        border-radius: 15px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-method:hover {
        border-color: var(--benin-green);
        transform: translateY(-2px);
    }

    .payment-method.active {
        border-color: var(--benin-green);
        background: rgba(0, 150, 57, 0.05);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e3e6f0;
    }

    .summary-row.total {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--benin-green);
        border-bottom: none;
        margin-top: 1rem;
    }

    .deposit-highlight {
        background: linear-gradient(135deg, var(--benin-yellow), #ffd700);
        color: #333;
        padding: 1.5rem;
        border-radius: 15px;
        margin: 1.5rem 0;
        font-weight: 600;
    }

    .btn-place-order {
        background: linear-gradient(135deg, var(--benin-green), #007a2e);
        color: white;
        border: none;
        padding: 1rem 3rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s ease;
    }

    .btn-place-order:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 150, 57, 0.3);
        color: white;
    }

    .product-mini {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 15px;
        margin-bottom: 1rem;
    }

    .product-mini img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
    }
</style>
@endpush

@section('content')
<div class="checkout-container">
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        
        <div class="row">
            <!-- Formulaire client -->
            <div class="col-lg-7">
                <div class="checkout-card">
                    <div class="checkout-header">
                        <h3><i class="bi bi-person-circle me-2"></i>Vos informations</h3>
                        <p class="mb-0 opacity-75">Remplissez vos coordonnées pour recevoir votre commande</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom complet *</label>
                            <input type="text" name="guest_name" class="form-control" 
                                   value="{{ old('guest_name', auth()->user()->name ?? '') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="guest_email" class="form-control" 
                                   value="{{ old('guest_email', auth()->user()->email ?? '') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Téléphone *</label>
                            <input type="tel" name="guest_phone" class="form-control" 
                                   value="{{ old('guest_phone') }}" placeholder="+229 XX XX XX XX" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ville *</label>
                            <select name="guest_city" class="form-select" required>
                                <option value="">Sélectionner...</option>
                                <option value="Cotonou">Cotonou</option>
                                <option value="Porto-Novo">Porto-Novo</option>
                                <option value="Parakou">Parakou</option>
                                <option value="Abomey-Calavi">Abomey-Calavi</option>
                                <option value="Djougou">Djougou</option>
                                <option value="Bohicon">Bohicon</option>
                                <option value="Kandi">Kandi</option>
                                <option value="Lokossa">Lokossa</option>
                                <option value="Ouidah">Ouidah</option>
                                <option value="Natitingou">Natitingou</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Adresse de livraison *</label>
                            <textarea name="guest_address" class="form-control" rows="3" 
                                      style="border-radius: 20px;" required>{{ old('guest_address') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Notes (optionnel)</label>
                            <textarea name="customer_notes" class="form-control" rows="3" 
                                      style="border-radius: 20px;" 
                                      placeholder="Instructions spéciales, préférences...">{{ old('customer_notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Méthodes de paiement -->
                    <div class="mt-4">
                        <h5 class="mb-3"><i class="bi bi-credit-card me-2"></i>Mode de paiement</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="payment-method">
                                    <input type="radio" name="payment_method" value="mtn_money" required>
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-phone-fill" style="font-size: 2rem; color: #FFCB05;"></i>
                                        <div>
                                            <strong>MTN Mobile Money</strong>
                                            <small class="d-block text-muted">Paiement mobile sécurisé</small>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label class="payment-method">
                                    <input type="radio" name="payment_method" value="moov_money">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-phone-fill" style="font-size: 2rem; color: #009FE3;"></i>
                                        <div>
                                            <strong>Moov Money</strong>
                                            <small class="d-block text-muted">Paiement mobile sécurisé</small>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label class="payment-method">
                                    <input type="radio" name="payment_method" value="bank_transfer">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-bank" style="font-size: 2rem; color: var(--benin-green);"></i>
                                        <div>
                                            <strong>Virement bancaire</strong>
                                            <small class="d-block text-muted">Par votre banque</small>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label class="payment-method">
                                    <input type="radio" name="payment_method" value="cash_on_delivery">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-cash-coin" style="font-size: 2rem; color: var(--benin-red);"></i>
                                        <div>
                                            <strong>Paiement à la livraison</strong>
                                            <small class="d-block text-muted">Cash uniquement</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Récapitulatif -->
            <div class="col-lg-5">
                <div class="checkout-card">
                    <h5 class="mb-3"><i class="bi bi-cart-check me-2"></i>Récapitulatif</h5>

                    <!-- Produits -->
                    @foreach($cart->items as $item)
                    <div class="product-mini">
                        @if($item->product->images && $item->product->images->first())
                            <img src="{{ $item->product->images->first()->image_url }}" alt="{{ $item->product->name }}">
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <small class="text-muted">Quantité: {{ $item->quantity }}</small>
                            <div class="fw-bold text-success">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} FCFA</div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Totaux -->
                    <div class="mt-3">
                        <div class="summary-row">
                            <span>Sous-total</span>
                            <strong>{{ number_format($subtotal, 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="summary-row">
                            <span>Frais de livraison</span>
                            <strong>{{ number_format($deliveryFee, 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    <!-- Acompte -->
                    <div class="deposit-highlight">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="d-block">Acompte à payer (30%)</small>
                                <h4 class="mb-0">{{ number_format($depositAmount, 0, ',', ' ') }} FCFA</h4>
                            </div>
                            <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
                        </div>
                        <small class="d-block mt-2 opacity-75">
                            Reste à payer: {{ number_format($remainingAmount, 0, ',', ' ') }} FCFA
                        </small>
                    </div>

                    <div class="alert alert-info" style="border-radius: 15px;">
                        <i class="bi bi-shield-check me-2"></i>
                        <small>Tous les produits sont fabriqués sur commande par nos artisans béninois</small>
                    </div>

                    <button type="submit" class="btn-place-order">
                        <i class="bi bi-check-circle me-2"></i>Valider la commande
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Activer visuellement la méthode de paiement sélectionnée
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-method').forEach(method => {
                method.classList.remove('active');
            });
            this.closest('.payment-method').classList.add('active');
        });
    });
</script>
@endpush