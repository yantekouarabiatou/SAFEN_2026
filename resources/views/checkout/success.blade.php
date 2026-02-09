@extends('layouts.app')

@section('title', 'Commande confirmée')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>

                    <h2 class="mt-4 mb-3 text-success">Commande confirmée !</h2>

                    <p class="lead mb-4">
                        Merci pour votre commande #{{ $order->order_number }}
                    </p>

                    @if($order->payment_method === 'cash_on_delivery')
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Vous paierez à la livraison. Un artisan vous contactera bientôt pour confirmer les détails.
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="bi bi-shield-check me-2"></i>
                            Votre acompte a été enregistré avec succès.
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        Numéro de commande : <strong>{{ $order->order_number }}</strong><br>
                        Total : <strong>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</strong>
                    </p>

                    <div class="d-grid gap-3 col-8 mx-auto">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-success btn-lg">
                            Voir les détails de la commande
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            Continuer mes achats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
