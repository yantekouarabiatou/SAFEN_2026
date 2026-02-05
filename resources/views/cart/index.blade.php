@extends('layouts.app')

@section('title', 'Mon Panier')

@push('styles')
<style>
    :root {
        --benin-green: #009639;
        --benin-yellow: #FCD116;
        --benin-red: #E8112D;
    }

    .cart-container {
        max-width: 1200px;
        margin: 3rem auto;
        padding: 0 1rem;
    }

    .cart-header {
        background: linear-gradient(135deg, var(--benin-green), var(--benin-red));
        color: white;
        padding: 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
    }

    .cart-item {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .item-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 15px;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quantity-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: 2px solid var(--benin-green);
        background: white;
        color: var(--benin-green);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .quantity-btn:hover {
        background: var(--benin-green);
        color: white;
    }

    .quantity-input {
        width: 60px;
        text-align: center;
        border: 2px solid #e3e6f0;
        border-radius: 10px;
        padding: 0.5rem;
        font-weight: 600;
    }

    .btn-remove {
        color: var(--benin-red);
        background: rgba(232, 17, 45, 0.1);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    .btn-remove:hover {
        background: var(--benin-red);
        color: white;
    }

    .summary-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 2rem;
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

    .deposit-alert {
        background: linear-gradient(135deg, var(--benin-yellow), #ffd700);
        color: #333;
        padding: 1.5rem;
        border-radius: 15px;
        margin: 1.5rem 0;
        font-weight: 600;
    }

    .production-badge {
        background: rgba(0, 150, 57, 0.1);
        color: var(--benin-green);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-checkout {
        background: linear-gradient(135deg, var(--benin-green), #007a2e);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s ease;
    }

    .btn-checkout:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 150, 57, 0.3);
        color: white;
    }

    .empty-cart {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-cart i {
        font-size: 5rem;
        color: #e3e6f0;
        margin-bottom: 2rem;
    }
</style>
@endpush

@section('content')
<div class="cart-container">
    @if($cart->items->count() > 0)
        <div class="cart-header">
            <h2><i class="bi bi-cart-check me-2"></i>Mon Panier</h2>
            <p class="mb-0 opacity-75">{{ $cart->item_count }} article(s) • Tous les produits sont fabriqués sur commande</p>
        </div>

        <div class="row">
            <!-- Liste des produits -->
            <div class="col-lg-8">
                @foreach($cart->items as $item)
                <div class="cart-item" id="cart-item-{{ $item->id }}">
                    <div class="row align-items-center">
                        <!-- Image -->
                        <div class="col-md-2">
                            @if($item->product->images && $item->product->images->first())
                                <img src="{{ $item->product->images->first()->image_url }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="item-image">
                            @else
                                <div class="item-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image" style="font-size: 2rem; color: #ccc;"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Informations produit -->
                        <div class="col-md-5">
                            <h5 class="mb-2">
                                <a href="{{ route('products.show', $item->product) }}" class="text-dark text-decoration-none">
                                    {{ $item->product->name }}
                                </a>
                            </h5>
                            
                            @if($item->product->name_local)
                                <p class="text-muted mb-2">
                                    <i class="bi bi-translate"></i> {{ $item->product->name_local }}
                                </p>
                            @endif

                            <div class="production-badge mb-2">
                                <i class="bi bi-clock-history"></i>
                                Délai : {{ $item->product->production_time_text }}
                            </div>

                            @if($item->product->artisan)
                                <small class="text-muted">
                                    <i class="bi bi-person-circle"></i>
                                    Par {{ $item->product->artisan->business_name ?? $item->product->artisan->user->name }}
                                </small>
                            @endif
                        </div>

                        <!-- Prix unitaire -->
                        <div class="col-md-2 text-center">
                            <div class="fw-bold text-success">{{ $item->product->formatted_price }}</div>
                            <small class="text-muted">Prix unitaire</small>
                            
                            @if($item->product->requires_deposit)
                                <div class="mt-2">
                                    <small class="badge bg-warning text-dark">
                                        Acompte: {{ $item->product->formatted_deposit }}
                                    </small>
                                </div>
                            @endif
                        </div>

                        <!-- Quantité -->
                        <div class="col-md-2">
                            <div class="quantity-controls justify-content-center">
                                <button class="quantity-btn" onclick="updateQuantity({{ $item->id }}, -1)">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" 
                                       class="quantity-input" 
                                       value="{{ $item->quantity }}" 
                                       min="{{ $item->product->min_order_quantity }}"
                                       max="{{ $item->product->max_order_quantity ?? 99 }}"
                                       id="quantity-{{ $item->id }}"
                                       onchange="updateQuantityDirect({{ $item->id }})">
                                <button class="quantity-btn" onclick="updateQuantity({{ $item->id }}, 1)">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            
                            @if($item->product->min_order_quantity > 1)
                                <small class="text-muted d-block text-center mt-1">
                                    Min: {{ $item->product->min_order_quantity }}
                                </small>
                            @endif
                        </div>

                        <!-- Supprimer -->
                        <div class="col-md-1 text-end">
                            <button class="btn-remove" onclick="removeItem({{ $item->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Sous-total de l'item -->
                    <div class="row mt-3 pt-3 border-top">
                        <div class="col text-end">
                            <strong>Sous-total: </strong>
                            <span class="text-success fw-bold" id="item-subtotal-{{ $item->id }}">
                                {{ $item->formatted_subtotal }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Bouton vider le panier -->
                <div class="text-center mt-3">
                    <button class="btn btn-outline-danger rounded-pill" onclick="clearCart()">
                        <i class="bi bi-trash me-2"></i>Vider le panier
                    </button>
                </div>
            </div>

            <!-- Récapitulatif -->
            <div class="col-lg-4">
                <div class="summary-card">
                    <h5 class="mb-4"><i class="bi bi-receipt me-2"></i>Récapitulatif</h5>

                    <!-- Alerte production -->
                    <div class="alert alert-info" style="border-radius: 15px;">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>
                            <strong>Produits sur commande</strong><br>
                            Délai de fabrication maximum : <strong>{{ $maxProductionTime }} jours</strong>
                        </small>
                    </div>

                    <!-- Totaux -->
                    <div class="summary-row">
                        <span>Sous-total ({{ $cart->item_count }} articles)</span>
                        <strong id="cart-subtotal">{{ $cart->formatted_total }}</strong>
                    </div>

                    <!-- Acompte requis -->
                    @php
                        $totalDeposit = $cart->items->sum(function($item) {
                            return $item->product->required_deposit_amount * $item->quantity;
                        });
                        $remainingAmount = $cart->total - $totalDeposit;
                    @endphp

                    <div class="deposit-alert">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="d-block">Acompte à payer maintenant</small>
                                <h4 class="mb-0" id="deposit-amount">{{ number_format($totalDeposit, 0, ',', ' ') }} FCFA</h4>
                            </div>
                            <i class="bi bi-shield-check" style="font-size: 2.5rem;"></i>
                        </div>
                        <hr style="border-color: rgba(0,0,0,0.1);">
                        <small class="d-block">
                            <i class="bi bi-info-circle me-1"></i>
                            Solde à régler : {{ number_format($remainingAmount, 0, ',', ' ') }} FCFA
                        </small>
                    </div>

                    <div class="summary-row total">
                        <span>Total de la commande</span>
                        <span id="cart-total">{{ $cart->formatted_total }}</span>
                    </div>

                    <!-- Bouton commander -->
                    <a href="{{ route('checkout.index') }}" class="btn-checkout mt-3">
                        <i class="bi bi-lock-fill me-2"></i>Commander maintenant
                    </a>

                    <!-- Garanties -->
                    <div class="mt-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-shield-check text-success"></i>
                            <small>Paiement sécurisé</small>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-hand-thumbs-up text-success"></i>
                            <small>Fabrication artisanale garantie</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-truck text-success"></i>
                            <small>Livraison dans tout le Bénin</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Panier vide -->
        <div class="empty-cart">
            <i class="bi bi-cart-x"></i>
            <h3>Votre panier est vide</h3>
            <p class="text-muted mb-4">Découvrez nos magnifiques créations artisanales béninoises</p>
            <a href="{{ route('products.index') }}" class="btn btn-benin-green rounded-pill px-5">
                <i class="bi bi-shop me-2"></i>Découvrir nos produits
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function updateQuantity(itemId, change) {
        const input = document.getElementById(`quantity-${itemId}`);
        let newQuantity = parseInt(input.value) + change;
        
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 99;
        
        if (newQuantity < min) newQuantity = min;
        if (newQuantity > max) newQuantity = max;
        
        input.value = newQuantity;
        updateQuantityDirect(itemId);
    }

    function updateQuantityDirect(itemId) {
        const input = document.getElementById(`quantity-${itemId}`);
        const quantity = parseInt(input.value);
        
        fetch(`/cart/update/${itemId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'affichage
                document.getElementById(`item-subtotal-${itemId}`).textContent = data.item_subtotal;
                document.getElementById('cart-total').textContent = data.cart_total;
                document.getElementById('cart-subtotal').textContent = data.cart_total;
                updateCartCount(data.cart_count);
                
                // Recalculer l'acompte
                recalculateDeposit();
                
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue', 'error');
        });
    }

    function removeItem(itemId) {
        if (!confirm('Voulez-vous vraiment retirer cet article du panier ?')) {
            return;
        }

        fetch(`/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`cart-item-${itemId}`).remove();
                
                updateCartCount(data.cart_count);
                
                if (data.cart_count === 0) {
                    location.reload();
                } else {
                    document.getElementById('cart-total').textContent = data.cart_total;
                    document.getElementById('cart-subtotal').textContent = data.cart_total;
                    recalculateDeposit();
                }
                
                showToast(data.message, 'success');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue', 'error');
        });
    }

    function clearCart() {
        if (!confirm('Voulez-vous vraiment vider votre panier ?')) {
            return;
        }

        fetch('/cart/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue', 'error');
        });
    }

    function recalculateDeposit() {
        fetch('/cart/deposit', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('deposit-amount').textContent = data.formatted_deposit;
            }
        })
        .catch(error => console.error('Erreur calcul acompte:', error));
    }

    function updateCartCount(count) {
        const badges = document.querySelectorAll('.cart-count, .cart-badge');
        badges.forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        });
    }

    function showToast(message, type = 'info') {
        let bgColor = 'bg-info';
        let icon = 'info-circle';
        
        if (type === 'success') {
            bgColor = 'bg-success';
            icon = 'check-circle-fill';
        } else if (type === 'error') {
            bgColor = 'bg-danger';
            icon = 'x-circle-fill';
        }

        const toastHTML = `
            <div class="toast align-items-center text-white ${bgColor} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-${icon} me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }

        container.insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = container.lastElementChild;
        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }
</script>
@endpush