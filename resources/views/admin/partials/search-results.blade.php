{{-- resources/views/partials/search-results.blade.php --}}
@if($artisans->count() > 0)
<div class="search-section">
    <div class="search-section-header">
        <i class="fas fa-user-tie mr-2"></i>
        <span class="font-weight-bold">Artisans</span>
    </div>
    @foreach($artisans as $artisan)
        <a href="{{ route('artisans.show', $artisan->id) }}" class="search-item">
            <div class="search-item-icon bg-warning">
                <i class="fas fa-store"></i>
            </div>
            <div class="search-item-content">
                <div class="search-item-title">{{ $artisan->business_name }}</div>
                <div class="search-item-subtitle">{{ $artisan->craft_label }}</div>
            </div>
        </a>
    @endforeach
</div>
@endif

@if($products->count() > 0)
<div class="search-section">
    <div class="search-section-header">
        <i class="fas fa-box mr-2"></i>
        <span class="font-weight-bold">Produits</span>
    </div>
    @foreach($products as $product)
        <a href="{{ route('products.show', $product->slug) }}" class="search-item">
            <div class="search-item-icon bg-success">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="search-item-content">
                <div class="search-item-title">{{ $product->name }}</div>
                <div class="search-item-subtitle">{{ $product->formatted_price }}</div>
            </div>
        </a>
    @endforeach
</div>
@endif

@if($artisans->count() == 0 && $products->count() == 0)
<div class="text-center p-3">
    <i class="fas fa-search fa-lg mb-3 text-muted"></i>
    <p class="mb-0 text-muted">Aucun résultat trouvé pour "{{ request('q') }}"</p>
</div>
@endif