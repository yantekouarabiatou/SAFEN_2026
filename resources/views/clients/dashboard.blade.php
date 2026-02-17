@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="row">
    {{-- Statistiques --}}
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Commandes</h4>
                </div>
                <div class="card-body">
                    {{ $stats['orders_count'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="fas fa-file-signature"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Devis</h4>
                </div>
                <div class="card-body">
                    {{ $stats['quotes_count'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-info">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Commandes en attente</h4>
                </div>
                <div class="card-body">
                    {{ $stats['pending_orders'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
                <i class="fas fa-heart"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Favoris</h4>
                </div>
                <div class="card-body">
                    {{ $stats['favorites_count'] }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Dernières commandes --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Dernières commandes</h4>
                <div class="card-header-action">
                    <a href="{{ route('client.orders.index') }}" class="btn btn-primary">Voir toutes</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>N° commande</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('client.orders.show', $order) }}">
                                        #{{ $order->order_number ?? $order->id }}
                                    </a>
                                </td>
                                <td>{{ $order->formatted_total }}</td>
                                <td>{!! $order->status_badge !!}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Aucune commande</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Derniers devis --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Derniers devis</h4>
                <div class="card-header-action">
                    <a href="{{ route('client.quotes.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nouveau devis
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Sujet</th>
                                <th>Artisan</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentQuotes as $quote)
                            <tr>
                                <td>
                                    <a href="{{ route('client.quotes.show', $quote) }}">
                                        {{ $quote->subject }}
                                    </a>
                                </td>
                                <td>{{ $quote->artisan->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $quote->status == 'pending' ? 'warning' : ($quote->status == 'accepted' ? 'success' : 'secondary') }}">
                                        {{ $quote->status_label }}
                                    </span>
                                </td>
                                <td>{{ $quote->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Aucun devis</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Actions rapides --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Actions rapides</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <a href="{{ route('client.quotes.create') }}" class="btn btn-block btn-outline-primary">
                            <i class="fas fa-file-signature mr-2"></i> Demander un devis
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="{{ route('client.contacts.create') }}" class="btn btn-block btn-outline-success">
                            <i class="fas fa-headset mr-2"></i> Contacter un artisan
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="{{ route('products.index') }}" class="btn btn-block btn-outline-warning">
                            <i class="fas fa-shopping-cart mr-2"></i> Continuer mes achats
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="{{ route('client.messages.index') }}" class="btn btn-block btn-outline-info">
                            <i class="fas fa-envelope mr-2"></i> Messagerie
                            @if($stats['unread_messages'] > 0)
                                <span class="badge badge-danger">{{ $stats['unread_messages'] }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Derniers contacts / messages envoyés --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Derniers messages envoyés</h4>
                <div class="card-header-action">
                    <a href="{{ route('client.contacts.index') }}" class="btn btn-primary">Voir tous</a>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-unstyled list-unstyled-border">
                    @forelse($recentContacts as $contact)
                    <li class="media">
                        <div class="media-body">
                            <div class="float-right text-muted text-small">{{ $contact->created_at->diffForHumans() }}</div>
                            <div class="media-title">{{ $contact->subject }}</div>
                            <span class="text-small text-muted">{{ Str::limit($contact->message, 50) }}</span>
                        </div>
                    </li>
                    @empty
                    <li class="text-center text-muted">Aucun message</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
