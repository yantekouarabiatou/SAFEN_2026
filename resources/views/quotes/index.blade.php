@extends('layouts.admin')

@section('title', 'Demandes de devis')

@section('content')
    <div class="section-header">
        <h1>Demandes de devis</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('client.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Devis</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Liste des demandes de devis</h4>
                        <div class="card-header-action">
                            <form class="form-inline">
                                <div class="input-group">
                                    <select class="form-control" name="status">
                                        <option value="">Tous les statuts</option>
                                        <option value="pending">En attente</option>
                                        <option value="responded">Répondu</option>
                                        <option value="accepted">Accepté</option>
                                        <option value="rejected">Refusé</option>
                                    </select>
                                    <div class="input-group-btn">
                                        <button class="btn btn-primary">Filtrer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped" id="quotes-table">
                                <thead>
                                    <tr>
                                        <th>N° Devis</th>
                                        <th>Client</th>
                                        <th>Produit/Service</th>
                                        <th>Description</th>
                                        <th>Budget</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($quotes ?? [] as $quote)
                                        <tr>
                                            <td>
                                                <strong>#{{ $quote->id }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $quote->user->avatar_url ?? asset('images/default-avatar.png') }}"
                                                        alt="avatar" class="rounded-circle mr-2" width="35">
                                                    <div>
                                                        <strong>{{ $quote->user->name ?? 'Client' }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $quote->user->email ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $quote->product->name ?? $quote->service ?? 'Non spécifié' }}</td>
                                            <td>
                                                <span title="{{ $quote->description }}">
                                                    {{ Str::limit($quote->description, 50) }}
                                                </span>
                                            </td>
                                            <td class="font-weight-bold">
                                                @if($quote->budget)
                                                    {{ number_format($quote->budget, 0, ',', ' ') }} FCFA
                                                @else
                                                    <span class="text-muted">Non défini</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $quote->created_at ? $quote->created_at->format('d/m/Y') : '-' }}
                                                <br>
                                                <small
                                                    class="text-muted">{{ $quote->created_at ? $quote->created_at->diffForHumans() : '' }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $status = $quote->status ?? 'pending';
                                                    $statusLabels = [
                                                        'pending' => ['label' => 'En attente', 'class' => 'warning'],
                                                        'responded' => ['label' => 'Répondu', 'class' => 'info'],
                                                        'accepted' => ['label' => 'Accepté', 'class' => 'success'],
                                                        'rejected' => ['label' => 'Refusé', 'class' => 'danger'],
                                                    ];
                                                    $statusInfo = $statusLabels[$status] ?? ['label' => ucfirst($status), 'class' => 'secondary'];
                                                @endphp
                                                <span class="badge badge-{{ $statusInfo['class'] }}">
                                                    {{ $statusInfo['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('quotes.show', $quote) }}" class="btn btn-sm btn-info"
                                                        title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(($quote->status ?? 'pending') === 'pending')
                                                        <button type="button" class="btn btn-sm btn-success" title="Répondre"
                                                            data-toggle="modal" data-target="#responseModal{{ $quote->id }}">
                                                            <i class="fas fa-reply"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal de réponse -->
                                        <div class="modal fade" id="responseModal{{ $quote->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> {{-- Ajout
                                                de modal-dialog-centered --}}
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Répondre au devis #{{ $quote->id }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('quotes.update', $quote) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Demande du client</label>
                                                                <p class="form-control-plaintext">{{ $quote->description }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="proposed_price">Prix proposé (FCFA)</label>
                                                                <input type="number" class="form-control" name="proposed_price"
                                                                    placeholder="Ex: 50000" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="response">Votre réponse</label>
                                                                <textarea class="form-control" name="response" rows="4"
                                                                    placeholder="Détaillez votre proposition..."
                                                                    required></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="delivery_time">Délai de livraison estimé</label>
                                                                <input type="text" class="form-control" name="delivery_time"
                                                                    placeholder="Ex: 2 semaines">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-primary">Envoyer la
                                                                proposition</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="empty-state">
                                                    <div class="empty-state-icon bg-info">
                                                        <i class="fas fa-file-invoice"></i>
                                                    </div>
                                                    <h2>Aucune demande de devis</h2>
                                                    <p class="lead">Vous n'avez pas encore reçu de demandes de devis.</p>
                                                    <p>Les clients peuvent vous contacter via votre profil public.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(isset($quotes) && $quotes instanceof \Illuminate\Pagination\LengthAwarePaginator && $quotes->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $quotes->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            @if(count($quotes ?? []) > 0)
                $('#quotes-table').DataTable({
                    "paging": false,
                    "info": false,
                    "order": [[5, "desc"]],
                    "language": {
                        "search": "Rechercher:",
                        "zeroRecords": "Aucun devis trouvé",
                    }
                });
            @endif
    });
    </script>
@endpush