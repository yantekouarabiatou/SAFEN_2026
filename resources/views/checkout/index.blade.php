@extends('layouts.app')

@section('title', 'Finaliser la commande')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    :root {
        --benin-green: #009639;
        --benin-yellow: #FCD116;
        --benin-red: #E8112D;
    }
    .checkout-container { max-width: 1200px; margin: 3rem auto; padding: 0 1rem; }
    .checkout-card { background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 2rem; margin-bottom: 2rem; }
    .checkout-header { background: linear-gradient(135deg, var(--benin-green), var(--benin-red)); color: white; padding: 2rem; border-radius: 20px 20px 0 0; margin: -2rem -2rem 2rem; }
    .form-control { border: 2px solid #e3e6f0; border-radius: 50px; padding: 0.75rem 1.5rem; }
    .form-control:focus { border-color: var(--benin-green); box-shadow: 0 0 0 0.2rem rgba(0,150,57,0.1); }
    textarea.form-control { border-radius: 20px !important; }

    /* FIX 1 : Select2 ne prend pas border-radius de .form-select, on cible directement */
    .select2-container--bootstrap-5 .select2-selection { border: 2px solid #e3e6f0 !important; border-radius: 50px !important; padding: 0.6rem 1.5rem !important; height: auto !important; min-height: 50px; display: flex; align-items: center; }
    .select2-container--bootstrap-5 .select2-selection__rendered { padding: 0 !important; line-height: 1.5 !important; }
    .select2-container--bootstrap-5 .select2-selection__arrow { top: 50% !important; transform: translateY(-50%); right: 14px !important; }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection { border-color: var(--benin-green) !important; box-shadow: 0 0 0 0.2rem rgba(0,150,57,0.1) !important; }
    .select2-container--bootstrap-5 .select2-dropdown { border-radius: 15px; border: 2px solid var(--benin-green); box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; }
    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field { border-radius: 8px; border: 1px solid #dee2e6; padding: 8px 12px; }
    /* FIX 2 : classe correcte pour les groupes Select2 */
    .select2-container--bootstrap-5 .select2-results__option[role="group"] > strong,
    .select2-results__group { font-weight: 700 !important; color: var(--benin-green) !important; background: #f0fdf4 !important; padding: 6px 12px !important; font-size: 0.78rem !important; text-transform: uppercase; letter-spacing: 0.5px; }
    .select2-container--bootstrap-5 .select2-results__option--selectable:hover,
    .select2-container--bootstrap-5 .select2-results__option--highlighted { background: var(--benin-green) !important; color: white !important; }

    /* Méthodes de paiement */
    .payment-method { border: 2px solid #e3e6f0; border-radius: 15px; padding: 1rem; cursor: pointer; transition: all 0.3s ease; display: block; width: 100%; margin: 0; }
    .payment-method:hover { border-color: var(--benin-green); transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,150,57,0.1); }
    .payment-method.active { border-color: var(--benin-green); background: rgba(0,150,57,0.05); }
    /* FIX 3 : cacher le radio SANS casser le comportement */
    .payment-method input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }

    .sandbox-badge { background: #fff3cd; border: 1px solid #ffc107; color: #856404; border-radius: 8px; padding: 8px 14px; font-size: 0.8rem; font-weight: 600; }
    .test-cards { background: #f8f9fa; border: 1px dashed #dee2e6; border-radius: 12px; padding: 1rem; margin-top: 1rem; font-size: 0.82rem; }
    .summary-row { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e3e6f0; }
    .summary-row.total { font-size: 1.25rem; font-weight: 700; color: var(--benin-green); border-bottom: none; margin-top: 1rem; }
    .deposit-highlight { background: linear-gradient(135deg, var(--benin-yellow), #ffd700); color: #333; padding: 1.5rem; border-radius: 15px; margin: 1.5rem 0; font-weight: 600; }
    .btn-place-order { background: linear-gradient(135deg, var(--benin-green), #007a2e); color: white; border: none; padding: 1rem 3rem; border-radius: 50px; font-weight: 700; font-size: 1.1rem; width: 100%; transition: all 0.3s ease; }
    .btn-place-order:hover:not(:disabled) { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,150,57,0.3); color: white; }
    .btn-place-order:disabled { opacity: 0.7; cursor: not-allowed; }
    .product-mini { display: flex; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 15px; margin-bottom: 1rem; }
    .product-mini img { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; flex-shrink: 0; }
    .delivery-fee-info { background: #e8f5e9; border-left: 4px solid var(--benin-green); border-radius: 8px; padding: 10px 14px; font-size: 0.85rem; margin-top: 8px; display: none; }
    /* FIX 4 : erreur de validation rouge sur Select2 */
    .is-invalid + .select2-container--bootstrap-5 .select2-selection { border-color: #dc3545 !important; }
</style>
@endpush

@section('content')
<div class="checkout-container">
    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row">

            {{-- Erreurs --}}
            @if ($errors->any())
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Erreurs dans le formulaire :</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif

            {{-- ===== FORMULAIRE CLIENT ===== --}}
            <div class="col-lg-7">
                <div class="checkout-card">
                    <div class="checkout-header">
                        <h3><i class="bi bi-person-circle me-2"></i>Vos informations</h3>
                        <p class="mb-0 opacity-75">Remplissez vos coordonnées pour recevoir votre commande</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet *</label>
                            <input type="text" name="guest_name" class="form-control @error('guest_name') is-invalid @enderror"
                                   value="{{ old('guest_name', auth()->user()->name ?? '') }}" required>
                            @error('guest_name')<div class="invalid-feedback px-3">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email *</label>
                            <input type="email" name="guest_email" class="form-control @error('guest_email') is-invalid @enderror"
                                   value="{{ old('guest_email', auth()->user()->email ?? '') }}" required>
                            @error('guest_email')<div class="invalid-feedback px-3">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Téléphone *</label>
                            <input type="tel" name="guest_phone" class="form-control @error('guest_phone') is-invalid @enderror"
                                   value="{{ old('guest_phone') }}" placeholder="+229 XX XX XX XX" required>
                            @error('guest_phone')<div class="invalid-feedback px-3">{{ $message }}</div>@enderror
                        </div>

                        {{-- ===== SELECT2 COMMUNES ===== --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Commune de livraison *</label>
                            {{-- FIX 5 : ne pas mettre border-radius sur le select natif, Select2 le remplace --}}
                            <select name="guest_city" id="communeSelect"
                                    class="@error('guest_city') is-invalid @enderror" required style="width:100%">
                                <option value="">Rechercher une commune...</option>
                                @foreach($communesGrouped as $departement => $communes)
                                    <optgroup label="{{ $departement }}">
                                        @foreach($communes as $commune)
                                            <option value="{{ $commune }}"
                                                {{ old('guest_city') === $commune ? 'selected' : '' }}>
                                                {{ $commune }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('guest_city')<div class="text-danger small mt-1 px-2">{{ $message }}</div>@enderror
                            <div class="delivery-fee-info" id="deliveryFeeInfo">
                                <i class="bi bi-truck me-1"></i>
                                <span id="deliveryFeeText"></span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Adresse précise *</label>
                            <textarea name="guest_address" class="form-control @error('guest_address') is-invalid @enderror"
                                      rows="3" required>{{ old('guest_address') }}</textarea>
                            @error('guest_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Notes (optionnel)</label>
                            <textarea name="customer_notes" class="form-control" rows="2"
                                      placeholder="Instructions spéciales, préférences...">{{ old('customer_notes') }}</textarea>
                        </div>
                    </div>

                    {{-- ===== MÉTHODES DE PAIEMENT ===== --}}
                    <div class="mt-4">
                        <h5 class="mb-3"><i class="bi bi-credit-card me-2"></i>Mode de paiement</h5>

                        @if(config('services.fedapay.environment') === 'sandbox')
                        <div class="sandbox-badge mb-3">
                            🧪 Mode TEST actif — Aucun vrai paiement ne sera effectué
                        </div>
                        @endif

                        {{-- FIX 6 : afficher message erreur paiement --}}
                        @error('payment_method')
                        <div class="alert alert-danger py-2">{{ $message }}</div>
                        @enderror

                        <div class="row g-3">

                            {{-- FedaPay --}}
                            <div class="col-12">
                                <label class="payment-method" for="pm-fedapay-input">
                                    <input type="radio" name="payment_method" value="fedapay" id="pm-fedapay-input"
                                           {{ old('payment_method') === 'fedapay' ? 'checked' : '' }}>
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-wallet2" style="font-size:2rem; color:var(--benin-green);"></i>
                                        <div class="flex-grow-1">
                                            <strong>FedaPay</strong>
                                            <small class="d-block text-muted">MTN Money, Moov Money, carte bancaire</small>
                                        </div>
                                        @if(config('services.fedapay.environment') === 'sandbox')
                                        <span class="badge bg-warning text-dark">TEST</span>
                                        @endif
                                    </div>
                                </label>

                                @if(config('services.fedapay.environment') === 'sandbox')
                                <div class="test-cards" id="fedapayTestCards" style="display:none;">
                                    <strong>🧪 Numéros de test FedaPay :</strong>
                                    <table class="table table-sm mt-2 mb-0">
                                        <thead><tr><th>Réseau</th><th>Numéro</th><th>Résultat</th></tr></thead>
                                        <tbody>
                                            <tr><td>MTN</td><td>+22961000001</td><td><span class="badge bg-success">Succès</span></td></tr>
                                            <tr><td>MTN</td><td>+22961000002</td><td><span class="badge bg-danger">Échec</span></td></tr>
                                            <tr><td>Moov</td><td>+22994000001</td><td><span class="badge bg-success">Succès</span></td></tr>
                                            <tr><td>Moov</td><td>+22994000002</td><td><span class="badge bg-danger">Échec</span></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>

                            {{-- MTN Money --}}
                            <div class="col-md-6">
                                <label class="payment-method" for="pm-mtn-input">
                                    <input type="radio" name="payment_method" value="mtn_money" id="pm-mtn-input"
                                           {{ old('payment_method') === 'mtn_money' ? 'checked' : '' }}>
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-phone-fill" style="font-size:2rem; color:#FFCB05;"></i>
                                        <div>
                                            <strong>MTN Mobile Money</strong>
                                            <small class="d-block text-muted">Paiement à la livraison</small>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            {{-- Moov Money --}}
                            <div class="col-md-6">
                                <label class="payment-method" for="pm-moov-input">
                                    <input type="radio" name="payment_method" value="moov_money" id="pm-moov-input"
                                           {{ old('payment_method') === 'moov_money' ? 'checked' : '' }}>
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-phone-fill" style="font-size:2rem; color:#009FE3;"></i>
                                        <div>
                                            <strong>Moov Money</strong>
                                            <small class="d-block text-muted">Paiement à la livraison</small>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            {{-- Virement --}}
                            <div class="col-md-6">
                                <label class="payment-method" for="pm-bank-input">
                                    <input type="radio" name="payment_method" value="bank_transfer" id="pm-bank-input"
                                           {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }}>
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-bank" style="font-size:2rem; color:var(--benin-green);"></i>
                                        <div>
                                            <strong>Virement bancaire</strong>
                                            <small class="d-block text-muted">Par votre banque</small>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            {{-- Cash --}}
                            <div class="col-md-6">
                                <label class="payment-method" for="pm-cash-input">
                                    <input type="radio" name="payment_method" value="cash_on_delivery" id="pm-cash-input"
                                           {{ old('payment_method') === 'cash_on_delivery' ? 'checked' : '' }}>
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-cash-coin" style="font-size:2rem; color:var(--benin-red);"></i>
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

            {{-- ===== RÉCAPITULATIF ===== --}}
            <div class="col-lg-5">
                <div class="checkout-card">
                    <h5 class="mb-3"><i class="bi bi-cart-check me-2"></i>Récapitulatif</h5>

                    @forelse($cart->items as $item)
                    <div class="product-mini">
                        @if($item->product->images && $item->product->images->first())
                            <img src="{{ $item->product->images->first()->image_url }}"
                                 alt="{{ $item->product->name }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light"
                                 style="width:80px;height:80px;border-radius:10px;flex-shrink:0;">
                                <i class="bi bi-image text-muted fs-4"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <small class="text-muted">Quantité : {{ $item->quantity }}</small>
                            <div class="fw-bold text-success">
                                {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    </div>
                    @empty
                        <p class="text-muted text-center">Panier vide</p>
                    @endforelse

                    {{-- Totaux --}}
                    <div class="mt-3">
                        <div class="summary-row">
                            <span>Sous-total</span>
                            <strong>{{ number_format($subtotal, 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="summary-row">
                            <span>Frais de livraison</span>
                            <strong id="deliveryFeeDisplay">{{ number_format($deliveryFee, 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span id="totalDisplay">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    {{-- Acompte --}}
                    <div class="deposit-highlight">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="d-block">Acompte à payer (30%)</small>
                                <h4 class="mb-0" id="depositDisplay">{{ number_format($depositAmount, 0, ',', ' ') }} FCFA</h4>
                            </div>
                            <i class="bi bi-info-circle" style="font-size:2rem;"></i>
                        </div>
                        <small class="d-block mt-2 opacity-75">
                            Reste à payer : <span id="remainingDisplay">{{ number_format($remainingAmount, 0, ',', ' ') }}</span> FCFA
                        </small>
                    </div>

                    <div class="alert alert-info" style="border-radius:15px;">
                        <i class="bi bi-shield-check me-2"></i>
                        <small>Tous les produits sont fabriqués sur commande par nos artisans béninois</small>
                    </div>

                    <button type="submit" class="btn-place-order" id="submitBtn">
                        <i class="bi bi-check-circle me-2"></i>Valider la commande
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    // ── Select2 communes ──────────────────────────────────────────────────────
    $('#communeSelect').select2({
        theme: 'bootstrap-5',
        placeholder: '🔍 Rechercher une commune...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function () { return "Commune introuvable"; },
            searching: function () { return "Recherche en cours..."; }
        }
    });

    // Restaurer la valeur après erreur de validation
    @if(old('guest_city'))
        $('#communeSelect').val('{{ old('guest_city') }}').trigger('change');
    @endif

    // Recalcul frais de livraison au changement de commune
    $('#communeSelect').on('change', function () {
        const commune = $(this).val();
        if (!commune) { $('#deliveryFeeInfo').slideUp(); return; }

        $.ajax({
            url: '{{ route("checkout.calculate-delivery") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', city: commune, subtotal: {{ $subtotal }} },
            success: function (data) {
                if (data.success) {
                    $('#deliveryFeeDisplay').text(data.delivery.formatted_fee);
                    $('#totalDisplay').text(data.formatted_total);
                    $('#depositDisplay').text(data.formatted_deposit);
                    $('#remainingDisplay').text(data.formatted_remaining);
                    $('#deliveryFeeText').text(
                        'Vers ' + commune + ' : ' + data.delivery.formatted_fee +
                        ' · Livraison : ' + data.delivery.estimated_delivery
                    );
                    $('#deliveryFeeInfo').slideDown();
                }
            },
            error: function () {
                console.error('Erreur calcul livraison');
            }
        });
    });

    // ── Activer visuellement la méthode de paiement ───────────────────────────
    // Restaurer état au rechargement après erreur
    const checkedPayment = $('input[name="payment_method"]:checked');
    if (checkedPayment.length) {
        checkedPayment.closest('.payment-method').addClass('active');
        if (checkedPayment.val() === 'fedapay') $('#fedapayTestCards').show();
    }

    $('input[name="payment_method"]').on('change', function () {
        $('.payment-method').removeClass('active');
        $(this).closest('.payment-method').addClass('active');
        if ($(this).val() === 'fedapay') {
            $('#fedapayTestCards').slideDown(200);
        } else {
            $('#fedapayTestCards').slideUp(200);
        }
    });

    // ── Validation avant soumission ───────────────────────────────────────────
    $('#checkoutForm').on('submit', function (e) {
        const commune = $('#communeSelect').val();
        const payment = $('input[name="payment_method"]:checked').val();
        let valid = true;

        if (!commune) {
            e.preventDefault();
            $('#communeSelect').next('.select2-container').find('.select2-selection')
                .css('border-color', '#dc3545');
            $('<div class="text-danger small mt-1 px-2" id="commune-error">Veuillez sélectionner une commune.</div>')
                .insertAfter($('#communeSelect').next('.select2-container'));
            valid = false;
        } else {
            $('#commune-error').remove();
            $('#communeSelect').next('.select2-container').find('.select2-selection')
                .css('border-color', '');
        }

        if (!payment) {
            e.preventDefault();
            if (!$('#payment-error').length) {
                $('<div class="alert alert-danger py-2 mt-2" id="payment-error">Veuillez sélectionner un mode de paiement.</div>')
                    .insertAfter($('.row.g-3').last());
            }
            valid = false;
        } else {
            $('#payment-error').remove();
        }

        if (valid) {
            $('#submitBtn').prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Traitement en cours...'
            );
        }
    });

});
</script>
@endpush
