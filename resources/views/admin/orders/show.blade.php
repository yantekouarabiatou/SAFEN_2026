@extends('layouts.admin')

@section('title', 'Détail de la commande #' . $order->order_number)

@section('content')
    <div class="section-header">
        <h1><i data-feather="package" class="mr-2"></i> Commande #{{ $order->order_number }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Commandes</a></div>
            <div class="breadcrumb-item active">Détail</div>
        </div>
    </div>

    <div class="section-body">
        {{-- Ligne de statuts et actions rapides --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex flex-wrap align-items-center">
                            <span class="mr-3"><strong>Statut commande :</strong> {!! $order->status_badge !!}</span>
                            <span class="mr-3"><strong>Statut paiement :</strong>
                                {!! $order->payment_status_badge !!}</span>
                            <span><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="mt-3 mt-sm-0 d-flex flex-wrap justify-content-end gap-2">

                            {{-- Boutons Valider / Refuser uniquement si pending --}}
                            @if($order->order_status === 'pending')

                                <form action="{{ route('admin.orders.validate', $order->id) }}" method="POST"
                                    class="swal-confirm" data-message="Valider cette commande ?" data-type="success">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i data-feather="check-circle" class="mr-1"></i> Valider
                                    </button>
                                </form>

                                <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST" class="swal-confirm"
                                    data-message="Refuser cette commande ?" data-type="error">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i data-feather="x-circle" class="mr-1"></i> Refuser
                                    </button>
                                </form>

                            @endif

                            {{-- Modifier --}}
                            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning btn-sm">
                                <i data-feather="edit" class="mr-1"></i> Modifier
                            </a>

                            {{-- Imprimer --}}
                            <button class="btn btn-info btn-sm" onclick="window.print()">
                                <i data-feather="printer" class="mr-1"></i> Imprimer
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Informations principales --}}
        <div class="row">
            {{-- Colonne gauche : Client --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4><i data-feather="user" class="mr-2"></i> Informations client</h4>
                    </div>
                    <div class="card-body">
                        @if($order->user_id)
                            <p><strong>Nom :</strong> {{ $order->user->name ?? 'N/A' }}</p>
                            <p><strong>Email :</strong> {{ $order->user->email ?? 'N/A' }}</p>
                            <p><strong>Téléphone :</strong> {{ $order->user->phone ?? 'N/A' }}</p>
                            <p><strong>Adresse :</strong> {{ $order->user->address ?? 'N/A' }}, {{ $order->user->city ?? '' }}
                            </p>
                        @else
                            <p><strong>Nom :</strong> {{ $order->guest_name ?? 'N/A' }}</p>
                            <p><strong>Email :</strong> {{ $order->guest_email ?? 'N/A' }}</p>
                            <p><strong>Téléphone :</strong> {{ $order->guest_phone ?? 'N/A' }}</p>
                            <p><strong>Adresse :</strong> {{ $order->guest_address ?? 'N/A' }}, {{ $order->guest_city ?? '' }}
                                ({{ $order->guest_country ?? '' }})</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Colonne droite : Résumé commande --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4><i data-feather="shopping-bag" class="mr-2"></i> Résumé de la commande</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td>Sous-total :</td>
                                <td class="text-right">{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</td>
                            </tr>
                            <tr>
                                <td>Frais de livraison :</td>
                                <td class="text-right">{{ number_format($order->delivery_fee, 0, ',', ' ') }} FCFA</td>
                            </tr>
                            <tr>
                                <th>Total TTC :</th>
                                <th class="text-right">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</th>
                            </tr>
                            <tr class="text-success">
                                <td>Acompte versé (30%) :</td>
                                <td class="text-right">{{ number_format($order->deposit_amount, 0, ',', ' ') }} FCFA</td>
                            </tr>
                            <tr class="text-danger">
                                <td>Reste à payer :</td>
                                <td class="text-right">{{ number_format($order->remaining_amount, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        </table>
                        <hr>
                        <p><strong>Méthode de paiement :</strong> {{ $order->payment_method ?? 'Non spécifiée' }}</p>
                        <p><strong>Référence paiement :</strong> {{ $order->payment_reference ?? '-' }}</p>
                        @if($order->deposit_paid_at)
                            <p><strong>Acompte payé le :</strong> {{ $order->deposit_paid_at->format('d/m/Y H:i') }}</p>
                        @endif
                        @if($order->fully_paid_at)
                            <p><strong>Paiement total le :</strong> {{ $order->fully_paid_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Articles commandés --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i data-feather="list" class="mr-2"></i> Détail des articles</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Produit / Service</th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->order_items ?? [] as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['name'] ?? 'Produit sans nom' }}</td>
                                            <td>{{ $item['quantity'] ?? 1 }}</td>
                                            <td>{{ number_format($item['price'] ?? 0, 0, ',', ' ') }} FCFA</td>
                                            <td>{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', ' ') }}
                                                FCFA</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Aucun article détaillé</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4><i data-feather="message-circle" class="mr-2"></i> Notes client</h4>
                    </div>
                    <div class="card-body">
                        {{ $order->customer_notes ?? 'Aucune note du client' }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4><i data-feather="clipboard" class="mr-2"></i> Notes administrateur</h4>
                    </div>
                    <div class="card-body">
                        {{ $order->admin_notes ?? 'Aucune note interne' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Réinitialiser les icônes Feather après chargement dynamique
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        document.querySelectorAll('.swal-confirm').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const message = this.dataset.message || 'Êtes-vous sûr ?';
                const type = this.dataset.type || 'warning';

                Swal.fire({
                    title: 'Confirmation',
                    text: message,
                    icon: type,
                    showCancelButton: true,
                    confirmButtonColor: type === 'success' ? '#28a745' : '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, confirmer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

    </script>
@endpush