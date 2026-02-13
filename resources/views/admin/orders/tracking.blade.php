@extends('layouts.admin')

@section('title', 'Suivi de mes commandes')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Suivi de mes commandes</h2>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-list mr-2"></i>Voir toutes mes commandes
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-truck fa-3x mb-3 text-muted"></i>
            <h5>Aucune commande en cours de traitement</h5>
            <p class="text-muted mb-4">Vous n'avez pas de commande en attente de livraison pour le moment.</p>
            <a href="{{ route('products.index') }}" class="btn btn-success">
                <i class="fas fa-shopping-cart mr-2"></i>Continuer mes achats
            </a>
        </div>
    @else
        <div class="row">
            @foreach($orders as $order)
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light">
                            <div>
                                <h5 class="mb-0">Commande #{{ $order->order_number }}</h5>
                                <small class="text-muted">
                                    Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                                </small>
                            </div>
                            <span class="badge badge-{{ $order->order_status == 'shipped' ? 'success' : 'warning' }} px-3 py-2">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Produits -->
                                <div class="col-md-8">
                                    <h6>Produits commandés</h6>
                                    <ul class="list-group list-group-flush">
                                        @foreach($order->items as $item)
                                            <li class="list-group-item px-0 py-2 border-bottom">
                                                <div class="d-flex align-items-center">
                                                    @if($item->product && $item->product->primaryImage)
                                                        <img src="{{ $item->product->primaryImage->full_url }}"
                                                             alt="{{ $item->product_name }}"
                                                             class="mr-3 rounded" width="60" height="60" style="object-fit: cover;">
                                                    @else
                                                        <div class="mr-3 bg-light rounded d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                                            <i class="fas fa-box text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div class="flex-grow-1">
                                                        <strong>{{ $item->product_name }}</strong>
                                                        <small class="d-block text-muted">Quantité : {{ $item->quantity }}</small>
                                                    </div>
                                                    <div class="text-right">
                                                        <strong class="text-success">{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</strong>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Infos livraison & statut -->
                                <div class="col-md-4">
                                    <h6>Statut de la commande</h6>
                                    <div class="progress mb-3" style="height: 25px;">
                                        @php
                                            $progress = match($order->order_status) {
                                                'pending'    => 25,
                                                'processing' => 50,
                                                'shipped'    => 75,
                                                default      => 100,
                                            };
                                        @endphp
                                        <div class="progress-bar bg-success" role="progressbar"
                                             style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ $progress }}%
                                        </div>
                                    </div>

                                    <ul class="list-unstyled timeline">
                                        <li class="{{ $order->order_status == 'pending' ? 'active' : '' }}">
                                            <i class="fas fa-clock"></i> Commande reçue
                                        </li>
                                        <li class="{{ $order->order_status == 'processing' ? 'active' : '' }}">
                                            <i class="fas fa-hammer"></i> En préparation
                                        </li>
                                        <li class="{{ $order->order_status == 'shipped' ? 'active' : '' }}">
                                            <i class="fas fa-truck"></i> Expédiée
                                        </li>
                                        <li class="{{ $order->order_status == 'delivered' ? 'active' : '' }}">
                                            <i class="fas fa-check-circle"></i> Livrée
                                        </li>
                                    </ul>

                                    <div class="mt-4">
                                        <small class="d-block text-muted">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ $order->guest_address ?? 'Adresse non spécifiée' }}
                                        </small>
                                        <small class="d-block text-muted">
                                            <i class="fas fa-city mr-1"></i>
                                            {{ $order->guest_city }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Total :</strong>
                                    <span class="text-success fw-bold">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye mr-1"></i>Détails complets
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
