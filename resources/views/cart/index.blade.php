@extends('layouts.app')

@section('title', 'Mon Panier - AFRI-HERITAGE')

@push('styles')
<style>
    .cart-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .cart-item {
        padding: 1.5rem;
        border-bottom: 1px solid var(--beige);
        transition: background 0.3s ease;
    }

    .cart-item:hover {
        background: var(--beige);
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .product-thumb {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        object-fit: cover;
    }

    .quantity-input {
        width: 70px;
        text-align: center;
        border: 2px solid var(--beige);
        border-radius: 8px;
        padding: 0.375rem;
    }

    .quantity-btn {
        width: 36px;
        height: 36px;
        border: 2px solid var(--beige);
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .quantity-btn:hover {
        background: var(--benin-green);
        border-color: var(--benin-green);
        color: white;
    }

    .remove-btn {
        color: var(--benin-red);
        background: none;
        border: none;
        padding: 0.5rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .remove-btn:hover {
        color: #c00f27;
    }

    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 100px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--beige);
    }

    .summary-row.total {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--benin-green);
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 1.5rem;
    }

    .empty-cart {
        text-align: center;
        padding: 4rem 0;
    }

    .continue-shopping {
        display: flex;
        align-items: center;
        color: var(--benin-green);
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 2rem;
    }

    .continue-shopping:hover {
        color: var(--navy);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Mon Panier</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold mb-0">Mon Panier</h1>
                <a href="{{ route('products.index') }}" class="continue-shopping">
                    <i class="bi bi-arrow-left me-2"></i> Continuer mes achats
                </a>
            </div>

            @if($cart->items->count() > 0)
                <div class="cart-container">
                    @foreach($cart->items as $item)
                        <div class="cart-item">
                            <div class="row align-items-center">
                                <!-- Product Image -->
                                <div class="col-md-2 col-4">
                                    <a href="{{ route('products.show', $item->product) }}">
                                        <img src="{{ $item->product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                             alt="{{ $item->product->name }}"
                                             class="product-thumb">
                                    </a>
                                </div>

                                <!-- Product Info -->
                                <div class="col-md-4 col-8">
                                    <h6 class="fw-bold mb-1">
                                        <a href="{{ route('products.show', $item->product) }}"
                                           class="text-decoration-none text-charcoal">
                                            {{ $item->product->name }}
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-1">
                                        {{ $item->product->ethnic_origin }} • {{ $item->product->category_label }}
                                    </p>
                                    <p class="text-muted small mb-0">
                                        Artisan: {{ $item->product->artisan->user->name }}
                                    </p>
                                </div>

                                <!-- Price -->
                                <div class="col-md-2 col-6 mt-3 mt-md-0">
                                    <div class="fw-bold text-benin-green">
                                        {{ $item->formatted_price }}
                                    </div>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-2 col-6 mt-3 mt-md-0">
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center">
                                            @csrf
                                            @method('PUT')
                                            <button type="button"
                                                    class="quantity-btn me-2"
                                                    onclick="updateQuantity(this, -1)">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number"
                                                   name="quantity"
                                                   value="{{ $item->quantity }}"
                                                   min="1"
                                                   max="20"
                                                   class="quantity-input"
                                                   onchange="this.form.submit()">
                                            <button type="button"
                                                    class="quantity-btn ms-2"
                                                    onclick="updateQuantity(this, 1)">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Total & Remove -->
                                <div class="col-md-2 col-12 mt-3 mt-md-0 text-md-end">
                                    <div class="fw-bold text-benin-green mb-2">
                                        {{ $item->formatted_total }}
                                    </div>
                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="remove-btn">
                                            <i class="bi bi-trash me-1"></i> Retirer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Clear Cart -->
                <div class="text-end mt-3">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-benin-red">
                            <i class="bi bi-trash me-1"></i> Vider le panier
                        </button>
                    </form>
                </div>
            @else
                <div class="empty-cart">
                    <i class="bi bi-cart fs-1 text-muted mb-3"></i>
                    <h4>Votre panier est vide</h4>
                    <p class="text-muted mb-4">Ajoutez des produits artisanaux à votre panier</p>
                    <a href="{{ route('products.index') }}" class="btn btn-benin-green rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i> Découvrir nos produits
                    </a>
                </div>
            @endif
        </div>

        <!-- Order Summary -->
        @if($cart->items->count() > 0)
            <div class="col-lg-4">
                <div class="summary-card">
                    <h5 class="fw-bold mb-4">Récapitulatif de commande</h5>

                    <!-- Summary -->
                    <div class="summary-row">
                        <span>Sous-total ({{ $cart->item_count }} article{{ $cart->item_count > 1 ? 's' : '' }})</span>
                        <span class="fw-bold">{{ $cart->formatted_total }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Livraison</span>
                        <span class="text-success">Gratuite</span>
                    </div>

                    <div class="summary-row">
                        <span>Taxes</span>
                        <span>Incluses</span>
                    </div>

                    <div class="summary-row total">
                        <span>Total</span>
                        <span>{{ $cart->formatted_total }}</span>
                    </div>

                    <!-- Checkout Button -->
                    <button class="btn btn-benin-green w-100 rounded-pill py-3 mb-3"
                            onclick="proceedToCheckout()">
                        <i class="bi bi-lock me-2"></i> Passer la commande
                    </button>

                    <!-- Payment Methods -->
                    <div class="text-center mb-3">
                        <small class="text-muted">Paiements sécurisés</small>
                        <div class="d-flex justify-content-center gap-2 mt-2">
                            <span class="badge bg-light text-dark">Kkiapay</span>
                            <span class="badge bg-light text-dark">MTN MoMo</span>
                            <span class="badge bg-light text-dark">Moov Money</span>
                            <span class="badge bg-light text-dark">Visa</span>
                        </div>
                    </div>

                    <!-- Guarantees -->
                    <div class="border-top pt-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-shield-check text-benin-green me-2"></i>
                            <small>Paiement 100% sécurisé</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-truck text-benin-green me-2"></i>
                            <small>Livraison en 3-5 jours</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-arrow-left-right text-benin-green me-2"></i>
                            <small>Retours sous 14 jours</small>
                        </div>
                    </div>
                </div>

                <!-- Promo Code -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Code promo</h6>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Entrez votre code">
                            <button class="btn btn-benin-green">Appliquer</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Recommended Products -->
    @if($cart->items->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="fw-bold mb-4">Vous aimerez aussi</h4>
                <div class="row g-4">
                    @php
                        $recommendedProducts = \App\Models\Product::where('stock_status', '!=', 'out_of_stock')
                            ->whereNotIn('id', $cart->items->pluck('product_id'))
                            ->inRandomOrder()
                            ->limit(4)
                            ->get();
                    @endphp

                    @foreach($recommendedProducts as $product)
                        <div class="col-md-3 col-sm-6">
                            <div class="card border-0 shadow-sm h-100">
                                <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                     alt="{{ $product->name }}"
                                     class="card-img-top"
                                     style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-2">{{ Str::limit($product->name, 30) }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-benin-green fw-bold">
                                            {{ $product->formatted_price }}
                                        </span>
                                        <button class="btn btn-sm btn-benin-green"
                                                onclick="addToCart({{ $product->id }})">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function updateQuantity(button, change) {
    const form = button.closest('form');
    const input = form.querySelector('input[name="quantity"]');
    let newQuantity = parseInt(input.value) + change;

    // Limites
    if (newQuantity < 1) newQuantity = 1;
    if (newQuantity > 20) newQuantity = 20;

    input.value = newQuantity;
    form.submit();
}

function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert(data.message);
            // Reload page to update cart
            window.location.reload();
        } else {
            alert(data.message);
        }
    });
}

function proceedToCheckout() {
    // Redirection vers la page de paiement
    window.location.href = '/checkout';
}

// Update cart count in navbar
function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = data.count;
                cartBadge.style.display = data.count > 0 ? 'flex' : 'none';
            }
        });
}

// Update cart count on page load
document.addEventListener('DOMContentLoaded', updateCartCount);
</script>
@endpush
