@extends('layouts.app')

@section('title', 'Commande #' . $order->order_number)

@section('content')
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">Commande #{{ $order->order_number }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informations client</h5>
                        <p><strong>Nom :</strong> {{ $order->guest_name }}</p>
                        <p><strong>Email :</strong> {{ $order->guest_email }}</p>
                        <p><strong>Téléphone :</strong> {{ $order->guest_phone }}</p>
                        <p><strong>Adresse :</strong> {{ $order->guest_address }}, {{ $order->guest_city }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Détails paiement</h5>
                        <p><strong>Méthode :</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        <p><strong>Total :</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</p>
                        <p><strong>Acompte payé :</strong> {{ number_format($order->deposit_amount, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>

                <hr>

                <h5>Produits commandés</h5>
                @foreach($order->order_items as $item)
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <strong>{{ $item['product_name'] }}</strong>
                            @if(!empty($item['product_name_local']))
                                <small class="d-block text-muted">{{ $item['product_name_local'] }}</small>
                            @endif
                            <small class="d-block text-muted">Quantité : {{ $item['quantity'] }}</small>
                        </div>
                        <div class="text-end">
                            {{ number_format($item['subtotal'], 0, ',', ' ') }} FCFA
                        </div>
                    </div>
                @endforeach

                <hr>

                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                Continuer mes achats
            </a>
        </div>
    </div>
@endsection
