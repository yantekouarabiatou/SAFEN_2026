@extends('layouts.admin')

@section('title', 'Tableau de bord Super-Admin')

@section('content')
    <div class="section-header">
        <h1>
            <i class="fas fa-crown text-warning"></i> Tableau de bord Super-Administrateur
            <small class="text-muted">Vue d'ensemble complète du système</small>
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <i class="fas fa-chart-line"></i> Super-Admin
            </div>
        </div>
    </div>

    {{-- Message de bienvenue --}}
    <div class="row">
        <div class="col-12">
            <div class="hero bg-gradient-primary text-white rounded-lg shadow">
                <div class="hero-inner">
                    <h2>Bienvenue, Super-Admin {{ auth()->user()->name }} !</h2>
                    <p class="lead">
                        Vous avez un accès complet à toutes les fonctionnalités du système.
                        Surveillez l'activité, gérez les utilisateurs et maintenez la plateforme.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.analytics') }}" class="btn btn-outline-white btn-lg">
                            <i class="fas fa-chart-bar"></i> Voir les analytics détaillés
                        </a>
                        <a href="#" class="btn btn-white btn-lg">
                            <i class="fas fa-users-cog"></i> Gérer les utilisateurs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alertes urgentes --}}
    @if(($artisans_pending ?? 0) > 0 || ($products_pending ?? 0) > 0 || ($unread_messages ?? 0) > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-danger">
                    <div class="card-header">
                        <h4><i class="fas fa-exclamation-triangle"></i> Alertes nécessitant votre attention</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(($artisans_pending ?? 0) > 0)
                                <div class="col-md-4">
                                    <div class="alert alert-warning alert-has-icon">
                                        <div class="alert-icon"><i class="fas fa-palette"></i></div>
                                        <div class="alert-body">
                                            <div class="alert-title">Artisans en attente</div>
                                            <div class="alert-text">
                                                <strong>{{ $artisans_pending }}</strong> artisans en attente d'approbation.
                                                <a href="#" class="btn btn-sm btn-warning ml-2">
                                                    Vérifier maintenant
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(($products_pending ?? 0) > 0)
                                <div class="col-md-4">
                                    <div class="alert alert-warning alert-has-icon">
                                        <div class="alert-icon"><i class="fas fa-box-open"></i></div>
                                        <div class="alert-body">
                                            <div class="alert-title">Produits en attente</div>
                                            <div class="alert-text">
                                                <strong>{{ $products_pending }}</strong> produits en attente d'approbation.
                                                <a href="{{ route('admin.products.index', ['status' => 'pending']) }}"
                                                    class="btn btn-sm btn-warning ml-2">
                                                    Vérifier maintenant
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(($unread_messages ?? 0) > 0)
                                <div class="col-md-4">
                                    <div class="alert alert-info alert-has-icon">
                                        <div class="alert-icon"><i class="fas fa-envelope"></i></div>
                                        <div class="alert-body">
                                            <div class="alert-title">Messages non lus</div>
                                            <div class="alert-text">
                                                <strong>{{ $unread_messages }}</strong> messages non lus.
                                                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-info ml-2">
                                                    Voir les messages
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Statistiques principales --}}
    <div class="row mt-4">
        {{-- Utilisateurs --}}
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1 card-primary">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Utilisateurs totaux</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($total_users ?? 0) }}
                    </div>
                    <div class="card-footer">
                        <small>
                            +{{ $new_users_today ?? 0 }} aujourd'hui •
                            +{{ $new_users_week ?? 0 }} cette semaine
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Artisans --}}
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1 card-warning">
                <div class="card-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Artisans</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($total_artisans ?? 0) }}
                    </div>
                    <div class="card-footer">
                        <small>
                            <span class="text-success">{{ $artisans_approved ?? 0 }} approuvés</span> •
                            <span class="text-danger">{{ $artisans_pending ?? 0 }} en attente</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Commandes --}}
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1 card-success">
                <div class="card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Commandes</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($total_orders ?? 0) }}
                    </div>
                    <div class="card-footer">
                        <small>
                            <span class="text-success">{{ $completed_orders ?? 0 }} complétées</span> •
                            <span class="text-warning">{{ $pending_orders ?? 0 }} en attente</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenus --}}
        <div class="col-lg-3 col-md-6">
            <div class="card card-statistic-1 card-info">
                <div class="card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Revenus totaux</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($total_revenue ?? 0, 0, ',', ' ') }} FCFA
                    </div>
                    <div class="card-footer">
                        <small>
                            {{ number_format($today_revenue ?? 0, 0, ',', ' ') }} FCFA aujourd'hui
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-bolt"></i> Actions rapides</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <a href="{{ route('admin.artisans.index', ['status' => 'pending']) }}"
                                class="btn btn-warning btn-icon icon-left btn-lg btn-block mb-3">
                                <i class="fas fa-user-check"></i>
                                <div class="mt-2">Approuver artisans</div>
                                @if(($artisans_pending ?? 0) > 0)
                                    <span class="badge badge-light ml-2">{{ $artisans_pending }}</span>
                                @endif
                            </a>
                        </div>

                        <div class="col-md-3 text-center">
                            <a href=""{{-- <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" --}}
                                class="btn btn-warning btn-icon icon-left btn-lg btn-block mb-3">
                                <i class="fas fa-box-check"></i>
                                <div class="mt-2">Approuver produits</div>
                                @if(($products_pending ?? 0) > 0)
                                    <span class="badge badge-light ml-2">{{ $products_pending }}</span>
                                @endif
                            </a>
                        </div>

                        <div class="col-md-3 text-center">
                            <a href="#"
                                class="btn btn-info btn-icon icon-left btn-lg btn-block mb-3">
                                <i class="fas fa-envelope"></i>
                                <div class="mt-2">Voir les messages</div>
                                @if(($unread_messages ?? 0) > 0)
                                    <span class="badge badge-light ml-2">{{ $unread_messages }}</span>
                                @endif
                            </a>
                        </div>

                        <div class="col-md-3 text-center">
                            <a href="#"
                                class="btn btn-danger btn-icon icon-left btn-lg btn-block mb-3">
                                <i class="fas fa-shopping-cart"></i>
                                <div class="mt-2">Commandes en attente</div>
                                @if(($pending_orders ?? 0) > 0)
                                    <span class="badge badge-light ml-2">{{ $pending_orders }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphiques --}}
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-chart-line"></i> Inscriptions des 30 derniers jours</h4>
                </div>
                <div class="card-body">
                    <canvas id="registrationsChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-chart-bar"></i> Ventes des 30 derniers jours</h4>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Artisans en attente --}}
    @if($pendingArtisans->isNotEmpty())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-clock text-warning"></i> Artisans en attente d'approbation</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.artisans.index', ['status' => 'pending']) }}"
                                class="btn btn-warning btn-sm">Tous voir</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Spécialité</th>
                                        <th>Date d'inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingArtisans as $artisan)
                                        <tr>
                                            <td>{{ $artisan->business_name ?? $artisan->user->name }}</td>
                                            <td>{{ $artisan->user->email ?? 'N/A' }}</td>
                                            <td>{{ $artisan->craft ?? 'Non spécifié' }}</td>
                                            <td>{{ $artisan->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.artisans.show', $artisan) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.artisans.approve', $artisan) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Approuver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Messages non lus --}}
    @if($unreadContacts->isNotEmpty())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-envelope text-info"></i> Messages non lus</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.contacts.index') }}" class="btn btn-info btn-sm">Tous voir</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Sujet</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unreadContacts as $contact)
                                        <tr>
                                            <td>{{ $contact->name }}</td>
                                            <td>{{ $contact->email }}</td>
                                            <td>{{ Str::limit($contact->subject, 30) }}</td>
                                            <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Commandes récentes --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-shopping-cart"></i> Commandes récentes</h4>
                    <div class="card-header-action">
                        <a href="#" class="btn btn-primary btn-sm">Voir toutes</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            @if($order->order_status == 'pending')
                                                <span class="badge badge-warning">En attente</span>
                                            @elseif($order->order_status == 'processing')
                                                <span class="badge badge-info">En traitement</span>
                                            @elseif($order->order_status == 'completed')
                                                <span class="badge badge-success">Complétée</span>
                                            @elseif($order->order_status == 'cancelled')
                                                <span class="badge badge-danger">Annulée</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Graphique des inscriptions
            var registrationsData = @json($registrationsChart ?? []);

            var ctx1 = document.getElementById('registrationsChart');
            if (ctx1 && registrationsData.length > 0) {
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: registrationsData.map(item => {
                            const date = new Date(item.date);
                            return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
                        }),
                        datasets: [{
                            label: 'Nouveaux utilisateurs',
                            data: registrationsData.map(item => item.count ?? 0),
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true, position: 'top' }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } }
                        }
                    }
                });
            }

            // Graphique des ventes
            var salesData = @json($salesChart ?? []);

            var ctx2 = document.getElementById('salesChart');
            if (ctx2 && salesData.length > 0) {
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: salesData.map(item => {
                            const date = new Date(item.date);
                            return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
                        }),
                        datasets: [{
                            label: 'Ventes (FCFA)',
                            data: salesData.map(item => item.total ?? 0),
                            backgroundColor: '#28a745',
                            borderColor: '#28a745',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true, position: 'top' }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('fr-FR') + ' FCFA';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
