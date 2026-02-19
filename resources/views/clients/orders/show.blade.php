@extends('layouts.admin')

@section('title', 'Détail de la commande')

@section('content')
<div class="row">
    <div class="col-12 col-md-12 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Commande #{{ $order->order_number ?? $order->id }}</h4>
                <div class="card-header-action">
                    <a href="{{ route('client.orders.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Statut :</strong> {!! $order->status_badge !!}
                    </div>
                    <div class="col-md-6 text-md-right">
                        <strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Informations de livraison</h6>
                        <p>
                            {{ $order->guest_name }}<br>
                            {{ $order->guest_address }}<br>
                            {{ $order->guest_city }}, {{ $order->guest_country }}<br>
                            Tél: {{ $order->guest_phone }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Paiement</h6>
                        <p>
                            Méthode : {{ ucfirst($order->payment_method) }}<br>
                            Statut : {!! $order->payment_status_badge !!}<br>
                            @if($order->deposit_amount > 0)
                                Acompte : {{ $order->formatted_deposit }}<br>
                                Reste : {{ $order->formatted_remaining }}
                            @endif
                        </p>
                    </div>
                </div>

                <h6>Articles commandés</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->order_items as $item)
                            <tr>
                                <td>{{ $item['name'] ?? 'Produit' }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ number_format($item['price'] ?? 0, 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', ' ') }} FCFA</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Sous-total</th>
                                <th>{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</th>
                            </tr>
                            @if($order->delivery_fee > 0)
                            <tr>
                                <th colspan="3" class="text-right">Frais de livraison</th>
                                <th>{{ number_format($order->delivery_fee, 0, ',', ' ') }} FCFA</th>
                            </tr>
                            @endif
                            <tr>
                                <th colspan="3" class="text-right">Total</th>
                                <th class="text-success">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection