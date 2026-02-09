@extends('layouts.app')

@section('title', 'Mes favoris')

@section('content')
<div class="container py-5">
    <h2>Mes favoris</h2>

    @if($favorites->isEmpty())
        <div class="alert alert-info text-center">
            Vous n'avez pas encore ajouté de produit en favori.
            <a href="{{ route('products.index') }}" class="btn btn-primary ms-2">
                Découvrir les produits
            </a>
        </div>
    @else
        <div class="row">
            @foreach($favorites as $favorite)
                @php
                    $item = $favorite->favoritable; // le produit ou autre modèle
                @endphp

                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($item->images && $item->images->first())
                            <img src="{{ $item->images->first()->image_url }}"
                                 class="card-img-top"
                                 alt="{{ $item->name }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($item->name, 40) }}</h5>
                            <p class="text-success fw-bold">
                                {{ number_format($item->price, 0, ',', ' ') }} FCFA
                            </p>

                            <div class="d-flex justify-content-between">
                                <a href="{{ $item->url ?? route('products.show', $item) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Voir le produit
                                </a>

                                <form action="{{ route('favorites.destroy', $favorite) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $favorites->links() }}
    @endif
</div>
@endsection
