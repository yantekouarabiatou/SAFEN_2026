@extends('layouts.admin')

@section('title', 'Détail du devis')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Devis #{{ $quote->id }}</h4>
                <div class="card-header-action">
                    <a href="{{ route('client.quotes.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Statut :</strong>
                        @php
                            $statusLabels = [
                                'pending' => ['label' => 'En attente', 'class' => 'warning'],
                                'responded' => ['label' => 'Répondu', 'class' => 'info'],
                                'accepted' => ['label' => 'Accepté', 'class' => 'success'],
                                'rejected' => ['label' => 'Refusé', 'class' => 'danger'],
                            ];
                            $status = $statusLabels[$quote->status] ?? ['label' => $quote->status, 'class' => 'secondary'];
                        @endphp
                        <span class="badge badge-{{ $status['class'] }}">{{ $status['label'] }}</span>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <strong>Date :</strong> {{ $quote->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Artisan</h6>
                        <p>{{ $quote->artisan->user->name ?? 'Non spécifié' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Budget estimé</h6>
                        <p>{{ $quote->formatted_budget }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <h6>Sujet</h6>
                    <p>{{ $quote->subject }}</p>
                </div>

                <div class="form-group">
                    <h6>Description</h6>
                    <p>{{ $quote->description }}</p>
                </div>

                @if($quote->desired_date)
                <div class="form-group">
                    <h6>Date souhaitée</h6>
                    <p>{{ $quote->desired_date->format('d/m/Y') }}</p>
                </div>
                @endif

                @if($quote->response)
                <hr>
                <h5>Réponse de l'artisan</h5>
                <div class="form-group">
                    <h6>Message</h6>
                    <p>{{ $quote->response }}</p>
                </div>
                @if($quote->amount)
                <div class="form-group">
                    <h6>Montant proposé</h6>
                    <p>{{ number_format($quote->amount, 0, ',', ' ') }} FCFA</p>
                </div>
                @endif
                @if($quote->response_date)
                <div class="form-group">
                    <h6>Date de réponse</h6>
                    <p>{{ $quote->response_date->format('d/m/Y H:i') }}</p>
                </div>
                @endif

                @if($quote->status === 'responded')
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> L'artisan a répondu à votre demande. Vous pouvez accepter ou refuser sa proposition.
                </div>
                <form action="{{ route('client.quotes.update', $quote) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="accepted">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Accepter ce devis ?')">
                        <i class="fas fa-check"></i> Accepter
                    </button>
                </form>
                <form action="{{ route('client.quotes.update', $quote) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Refuser ce devis ?')">
                        <i class="fas fa-times"></i> Refuser
                    </button>
                </form>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection