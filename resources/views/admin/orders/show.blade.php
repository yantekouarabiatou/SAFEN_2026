@extends('layouts.admin')

@section('title', 'Commande #' . ($order->order_number ?? $order->id))

@section('content')
<div class="section-header">
    <h1>Commande #{{ $order->order_number ?? $order->id }}</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Commandes</a></div>
        <div class="breadcrumb-item active">#{{ $order->order_number ?? $order->id }}</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        {{-- Détails commande --}}
        <div class="col-md-8">
            {{-- Articles --}}
            <div class="card">
                <div class="card-header">
                    <h4>Articles commandés</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th class="text-right">Sous-total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $image = null;
                                                if ($item->product && $item->product->images) {
                                                    $image = $item->product->images->where('is_primary', true)->first() ?? $item->product->images->first();
                                                }
                                            @endphp
                                            <img src="{{ $image ? asset($image->image_url) : asset('admin-assets/img/example-image.jpg') }}" 
                                                 alt="" class="rounded mr-3" width="50" height="50" style="object-fit: cover;">
                                            <div>
                                                <strong>{{ $item->product->name ?? $item->product_name ?? 'Produit supprimé' }}</strong>
                                                @if($item->product && $item->product->artisan)
                                                    <br><small class="text-muted">Par: {{ $item->product->artisan->user->name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Sous-total</strong></td>
                                    <td class="text-right">{{ number_format($order->subtotal ?? $order->total_amount, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                @if($order->shipping_cost > 0)
                                <tr>
                                    <td colspan="3" class="text-right">Frais de livraison</td>
                                    <td class="text-right">{{ number_format($order->shipping_cost, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                @endif
                                @if($order->discount > 0)
                                <tr>
                                    <td colspan="3" class="text-right text-success">Réduction</td>
                                    <td class="text-right text-success">-{{ number_format($order->discount, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                @endif
                                <tr class="bg-light">
                                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                                    <td class="text-right"><strong class="text-primary h5">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- Historique des statuts --}}
            <div class="card">
                <div class="card-header">
                    <h4>Historique</h4>
                </div>
                <div class="card-body">
                    <div class="activities">
                        <div class="activity">
                            <div class="activity-icon bg-primary text-white shadow-primary">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="activity-detail">
                                <div class="mb-2">
                                    <span class="text-job">Commande créée</span>
                                    <span class="bullet"></span>
                                    <span class="text-muted">{{ $order->created_at->format('d/m/Y à H:i') }}</span>
                                </div>
                                <p>La commande a été passée par {{ $order->user->name }}</p>
                            </div>
                        </div>
                        @if($order->confirmed_at)
                        <div class="activity">
                            <div class="activity-icon bg-info text-white">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="activity-detail">
                                <div class="mb-2">
                                    <span class="text-job">Commande confirmée</span>
                                    <span class="bullet"></span>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($order->confirmed_at)->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($order->shipped_at)
                        <div class="activity">
                            <div class="activity-icon bg-warning text-white">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="activity-detail">
                                <div class="mb-2">
                                    <span class="text-job">Commande expédiée</span>
                                    <span class="bullet"></span>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($order->shipped_at)->format('d/m/Y à H:i') }}</span>
                                </div>
                                @if($order->tracking_number)
                                <p>N° de suivi: {{ $order->tracking_number }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($order->delivered_at)
                        <div class="activity">
                            <div class="activity-icon bg-success text-white">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div class="activity-detail">
                                <div class="mb-2">
                                    <span class="text-job">Commande livrée</span>
                                    <span class="bullet"></span>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($order->delivered_at)->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Notes --}}
            @if($order->notes)
            <div class="card">
                <div class="card-header">
                    <h4>Notes du client</h4>
                </div>
                <div class="card-body">
                    <p>{{ $order->notes }}</p>
                </div>
            </div>
            @endif
        </div>
        
        {{-- Sidebar --}}
        <div class="col-md-4">
            {{-- Statut --}}
            <div class="card">
                <div class="card-header">
                    <h4>Statut de la commande</h4>
                </div>
                <div class="card-body text-center">
                    @switch($order->status)
                        @case('pending')
                            <div class="badge badge-warning p-3 mb-3" style="font-size: 1.2rem;">
                                <i class="fas fa-clock"></i> En attente
                            </div>
                            @break
                        @case('confirmed')
                            <div class="badge badge-info p-3 mb-3" style="font-size: 1.2rem;">
                                <i class="fas fa-check"></i> Confirmée
                            </div>
                            @break
                        @case('processing')
                            <div class="badge badge-primary p-3 mb-3" style="font-size: 1.2rem;">
                                <i class="fas fa-cog"></i> En traitement
                            </div>
                            @break
                        @case('shipped')
                            <div class="badge badge-info p-3 mb-3" style="font-size: 1.2rem;">
                                <i class="fas fa-shipping-fast"></i> Expédiée
                            </div>
                            @break
                        @case('delivered')
                            <div class="badge badge-success p-3 mb-3" style="font-size: 1.2rem;">
                                <i class="fas fa-check-double"></i> Livrée
                            </div>
                            @break
                        @case('cancelled')
                            <div class="badge badge-danger p-3 mb-3" style="font-size: 1.2rem;">
                                <i class="fas fa-times"></i> Annulée
                            </div>
                            @break
                    @endswitch
                    
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="form-group">
                            <label>Changer le statut</label>
                            <select name="status" class="form-control">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>En traitement</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Mettre à jour</button>
                    </form>
                </div>
            </div>
            
            {{-- Paiement --}}
            <div class="card">
                <div class="card-header">
                    <h4>Paiement</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="text-muted mb-0">Statut</p>
                            @switch($order->payment_status ?? 'pending')
                                @case('paid')
                                    <span class="badge badge-success">Payé</span>
                                    @break
                                @case('pending')
                                    <span class="badge badge-warning">En attente</span>
                                    @break
                                @case('failed')
                                    <span class="badge badge-danger">Échoué</span>
                                    @break
                            @endswitch
                        </div>
                        <div class="col-6">
                            <p class="text-muted mb-0">Méthode</p>
                            <strong>{{ ucfirst($order->payment_method ?? 'N/A') }}</strong>
                        </div>
                    </div>
                    @if($order->payment_reference)
                    <div>
                        <p class="text-muted mb-0">Référence</p>
                        <code>{{ $order->payment_reference }}</code>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Client --}}
            <div class="card">
                <div class="card-header">
                    <h4>Client</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $order->user->profile_photo_url ?? asset('admin-assets/img/avatar/avatar-1.png') }}" 
                             alt="{{ $order->user->name }}" 
                             class="rounded-circle mr-3" width="50" height="50">
                        <div>
                            <strong>{{ $order->user->name }}</strong>
                            <br><small class="text-muted">{{ $order->user->email }}</small>
                        </div>
                    </div>
                    @if($order->user->phone)
                    <p><i class="fas fa-phone"></i> {{ $order->user->phone }}</p>
                    @endif
                    <a href="{{ route('admin.users.show', $order->user) }}" class="btn btn-outline-primary btn-sm">
                        Voir le profil
                    </a>
                </div>
            </div>
            
            {{-- Adresse de livraison --}}
            <div class="card">
                <div class="card-header">
                    <h4>Adresse de livraison</h4>
                </div>
                <div class="card-body">
                    @if($order->shipping_address)
                        <p>
                            <strong>{{ $order->shipping_name ?? $order->user->name }}</strong><br>
                            {{ $order->shipping_address }}<br>
                            @if($order->shipping_city){{ $order->shipping_city }}, @endif
                            {{ $order->shipping_country ?? 'Bénin' }}
                        </p>
                        @if($order->shipping_phone)
                        <p><i class="fas fa-phone"></i> {{ $order->shipping_phone }}</p>
                        @endif
                    @else
                        <p class="text-muted">Aucune adresse spécifiée</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {{-- Actions --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                    <button type="button" class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
