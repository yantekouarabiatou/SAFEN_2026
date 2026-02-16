@extends('layouts.admin')

@section('title', 'Modifier la commande #'.$order->order_number)

@section('content')
<div class="section-header">
    <h1><i data-feather="edit-3" class="mr-2"></i> Modifier commande #{{ $order->order_number }}</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Commandes</a></div>
        <div class="breadcrumb-item active">Modifier</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Informations générales</h4>
                </div>
                <div class="card-body">
                    {{-- Informations en lecture seule --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Client :</strong> 
                                @if($order->user_id)
                                    {{ $order->user->name ?? 'N/A' }} ({{ $order->user->email ?? '' }})
                                @else
                                    {{ $order->guest_name }} - {{ $order->guest_email }}
                                @endif
                            </p>
                            <p><strong>Téléphone :</strong> {{ $order->guest_phone ?? 'N/A' }}</p>
                            <p><strong>Adresse :</strong> {{ $order->guest_address ?? 'N/A' }}, {{ $order->guest_city ?? '' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total :</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</p>
                            <p><strong>Acompte :</strong> {{ number_format($order->deposit_amount, 0, ',', ' ') }} FCFA</p>
                            <p><strong>Reste :</strong> {{ number_format($order->remaining_amount, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>

                    {{-- Formulaire de modification --}}
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Statut de la commande</label>
                            <select name="order_status" class="form-control @error('order_status') is-invalid @enderror">
                                <option value="pending" {{ old('order_status', $order->order_status) == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmed" {{ old('order_status', $order->order_status) == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                <option value="processing" {{ old('order_status', $order->order_status) == 'processing' ? 'selected' : '' }}>En préparation</option>
                                <option value="shipped" {{ old('order_status', $order->order_status) == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                <option value="delivered" {{ old('order_status', $order->order_status) == 'delivered' ? 'selected' : '' }}>Livrée</option>
                                <option value="cancelled" {{ old('order_status', $order->order_status) == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            </select>
                            @error('order_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Statut du paiement</label>
                            <select name="payment_status" class="form-control @error('payment_status') is-invalid @enderror">
                                <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="partial" {{ old('payment_status', $order->payment_status) == 'partial' ? 'selected' : '' }}>Acompte versé</option>
                                <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Payée</option>
                                <option value="failed" {{ old('payment_status', $order->payment_status) == 'failed' ? 'selected' : '' }}>Échouée</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Notes administrateur</label>
                            <textarea name="admin_notes" class="form-control @error('admin_notes') is-invalid @enderror" rows="4">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Ces notes sont internes et ne sont pas visibles par le client.</small>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save" class="mr-1"></i> Mettre à jour
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                <i data-feather="x" class="mr-1"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Recharger les icônes Feather si nécessaire
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endpush