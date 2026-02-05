@extends('layouts.admin')

@section('title', 'Devis #' . ($quote->quote_number ?? $quote->id))

@section('content')
<div class="section-header">
    <h1>Demande de devis #{{ $quote->quote_number ?? $quote->id }}</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.quotes.index') }}">Devis</a></div>
        <div class="breadcrumb-item active">#{{ $quote->quote_number ?? $quote->id }}</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-md-8">
            {{-- Détails de la demande --}}
            <div class="card">
                <div class="card-header">
                    <h4>Détails de la demande</h4>
                    <div class="card-header-action">
                        @switch($quote->status ?? 'pending')
                            @case('pending')
                                <span class="badge badge-warning p-2">En attente</span>
                                @break
                            @case('sent')
                                <span class="badge badge-info p-2">Envoyé</span>
                                @break
                            @case('accepted')
                                <span class="badge badge-success p-2">Accepté</span>
                                @break
                            @case('rejected')
                                <span class="badge badge-danger p-2">Refusé</span>
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="card-body">
                    @if($quote->product)
                    <div class="row mb-4">
                        <div class="col-md-3">
                            @php
                                $image = $quote->product->images->where('is_primary', true)->first() ?? $quote->product->images->first();
                            @endphp
                            <img src="{{ $image ? asset($image->image_url) : asset('admin-assets/img/example-image.jpg') }}" 
                                 alt="{{ $quote->product->name }}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-9">
                            <h5>{{ $quote->product->name }}</h5>
                            <p class="text-muted">{{ Str::limit($quote->product->description, 100) }}</p>
                            <p><strong>Prix catalogue:</strong> {{ number_format($quote->product->price, 0, ',', ' ') }} FCFA</p>
                            <a href="{{ route('admin.products.show', $quote->product) }}" class="btn btn-sm btn-outline-primary">
                                Voir le produit
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    @if($quote->artisan)
                    <div class="mb-4">
                        <h6 class="text-muted">Artisan concerné</h6>
                        <p>
                            <a href="{{ route('admin.artisans.show', $quote->artisan) }}">
                                {{ $quote->artisan->user->name }}
                            </a>
                            - {{ $quote->artisan->specialty }}
                        </p>
                    </div>
                    @endif
                    
                    <div class="form-group">
                        <label class="text-muted">Description de la demande</label>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($quote->description ?? 'Aucune description fournie')) !!}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Quantité souhaitée</label>
                                <p><strong>{{ $quote->quantity ?? 1 }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Budget estimé</label>
                                <p>
                                    @if($quote->budget)
                                        <strong>{{ number_format($quote->budget, 0, ',', ' ') }} FCFA</strong>
                                    @else
                                        <span class="text-muted">Non spécifié</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Date souhaitée</label>
                                <p>
                                    @if($quote->desired_date)
                                        {{ \Carbon\Carbon::parse($quote->desired_date)->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Non spécifiée</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Date de la demande</label>
                                <p>{{ $quote->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($quote->specifications)
                    <div class="form-group">
                        <label class="text-muted">Spécifications particulières</label>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($quote->specifications)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Répondre --}}
            <div class="card">
                <div class="card-header">
                    <h4>Répondre au devis</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quotes.respond', $quote) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Montant du devis (FCFA)</label>
                                    <input type="number" name="amount" class="form-control" 
                                           value="{{ old('amount', $quote->quoted_amount) }}" min="0" step="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Délai de livraison</label>
                                    <input type="text" name="delivery_time" class="form-control" 
                                           value="{{ old('delivery_time', $quote->delivery_time) }}"
                                           placeholder="Ex: 2 semaines">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Message de réponse</label>
                            <textarea name="response" class="form-control" rows="4">{{ old('response', $quote->response) }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Validité du devis</label>
                            <input type="date" name="valid_until" class="form-control" 
                                   value="{{ old('valid_until', $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('Y-m-d') : '') }}">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Envoyer le devis
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            {{-- Client --}}
            <div class="card">
                <div class="card-header">
                    <h4>Client</h4>
                </div>
                <div class="card-body">
                    @if($quote->user)
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $quote->user->profile_photo_url ?? asset('admin-assets/img/avatar/avatar-1.png') }}" 
                             alt="{{ $quote->user->name }}" 
                             class="rounded-circle mr-3" width="50" height="50">
                        <div>
                            <strong>{{ $quote->user->name }}</strong>
                            <br><small class="text-muted">Client enregistré</small>
                        </div>
                    </div>
                    @else
                    <p><strong>{{ $quote->name }}</strong></p>
                    @endif
                    
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-envelope text-primary mr-2"></i>
                            <a href="mailto:{{ $quote->user->email ?? $quote->email }}">
                                {{ $quote->user->email ?? $quote->email }}
                            </a>
                        </li>
                        @if($quote->phone || ($quote->user && $quote->user->phone))
                        <li class="mb-2">
                            <i class="fas fa-phone text-primary mr-2"></i>
                            <a href="tel:{{ $quote->phone ?? $quote->user->phone }}">
                                {{ $quote->phone ?? $quote->user->phone }}
                            </a>
                        </li>
                        @endif
                    </ul>
                    
                    @if($quote->user)
                    <a href="{{ route('admin.users.show', $quote->user) }}" class="btn btn-outline-primary btn-block">
                        Voir le profil
                    </a>
                    @endif
                </div>
            </div>
            
            {{-- Statut --}}
            <div class="card">
                <div class="card-header">
                    <h4>Changer le statut</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quotes.update-status', $quote) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="pending" {{ ($quote->status ?? 'pending') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="sent" {{ ($quote->status ?? '') == 'sent' ? 'selected' : '' }}>Envoyé</option>
                                <option value="accepted" {{ ($quote->status ?? '') == 'accepted' ? 'selected' : '' }}>Accepté</option>
                                <option value="rejected" {{ ($quote->status ?? '') == 'rejected' ? 'selected' : '' }}>Refusé</option>
                                <option value="expired" {{ ($quote->status ?? '') == 'expired' ? 'selected' : '' }}>Expiré</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Mettre à jour</button>
                    </form>
                </div>
            </div>
            
            {{-- Actions --}}
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.quotes.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                    <a href="mailto:{{ $quote->user->email ?? $quote->email }}" class="btn btn-info btn-block">
                        <i class="fas fa-envelope"></i> Contacter par email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
