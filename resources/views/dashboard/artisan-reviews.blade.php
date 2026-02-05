@extends('layouts.admin')

@section('title', 'Avis clients')

@section('content')
<div class="section-header">
    <h1>Avis clients</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard.artisan') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Avis</div>
    </div>
</div>

<div class="section-body">
    <!-- Statistiques -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Note moyenne</h4>
                    </div>
                    <div class="card-body">
                        @if(isset($reviews) && count($reviews) > 0)
                            {{ number_format(collect($reviews)->avg('rating'), 1) }} / 5
                        @else
                            0 / 5
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total avis</h4>
                    </div>
                    <div class="card-body">
                        @if(isset($reviews) && method_exists($reviews, 'total'))
                            {{ $reviews->total() }}
                        @else
                            {{ count($reviews ?? []) }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Avis positifs</h4>
                    </div>
                    <div class="card-body">
                        @if(isset($reviews) && count($reviews) > 0)
                            {{ collect($reviews)->filter(function($r) { return $r->rating >= 4; })->count() }}
                        @else
                            0
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-thumbs-down"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Avis négatifs</h4>
                    </div>
                    <div class="card-body">
                        @if(isset($reviews) && count($reviews) > 0)
                            {{ collect($reviews)->filter(function($r) { return $r->rating < 3; })->count() }}
                        @else
                            0
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des avis -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des avis</h4>
                    <div class="card-header-action">
                        <form class="form-inline">
                            <div class="input-group">
                                <select class="form-control" name="rating">
                                    <option value="">Toutes les notes</option>
                                    <option value="5">5 étoiles</option>
                                    <option value="4">4 étoiles</option>
                                    <option value="3">3 étoiles</option>
                                    <option value="2">2 étoiles</option>
                                    <option value="1">1 étoile</option>
                                </select>
                                <div class="input-group-btn">
                                    <button class="btn btn-primary">Filtrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($reviews ?? [] as $review)
                        <div class="media mb-4 pb-4 border-bottom">
                            <img src="{{ $review->user->avatar_url ?? asset('images/default-avatar.png') }}" 
                                 alt="{{ $review->user->name ?? 'Client' }}" 
                                 class="rounded-circle mr-3"
                                 width="60">
                            <div class="media-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mt-0 mb-1">{{ $review->user->name ?? 'Client' }}</h6>
                                        <div class="text-warning mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="text-muted ml-2">{{ $review->rating }}/5</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <small class="text-muted">
                                            {{ $review->created_at ? $review->created_at->format('d/m/Y') : '' }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                                        </small>
                                    </div>
                                </div>
                                
                                @if($review->comment)
                                    <p class="mb-2">{{ $review->comment }}</p>
                                @endif

                                @if($review->reviewable_type === 'App\Models\Product' && $review->reviewable)
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-box"></i> Produit: 
                                            <a href="{{ route('products.show', $review->reviewable) }}" target="_blank">
                                                {{ $review->reviewable->name }}
                                            </a>
                                        </small>
                                    </div>
                                @endif

                                @if($review->response)
                                    <div class="alert alert-light mt-3 mb-0">
                                        <strong>Votre réponse:</strong>
                                        <p class="mb-0 mt-2">{{ $review->response }}</p>
                                    </div>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary mt-2" 
                                            data-toggle="modal" 
                                            data-target="#responseModal{{ $review->id }}">
                                        <i class="fas fa-reply"></i> Répondre
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Modal de réponse -->
                        <div class="modal fade" id="responseModal{{ $review->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Répondre à l'avis</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('reviews.update', $review) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Avis du client</label>
                                                <div class="p-3 bg-light rounded">
                                                    <div class="text-warning mb-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <p class="mb-0">{{ $review->comment }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="response">Votre réponse</label>
                                                <textarea class="form-control" name="response" rows="4" 
                                                          placeholder="Répondez à cet avis..." required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Publier la réponse</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon bg-warning">
                                    <i class="fas fa-star"></i>
                                </div>
                                <h2>Aucun avis</h2>
                                <p class="lead">Vous n'avez pas encore reçu d'avis.</p>
                                <p>Les clients pourront laisser des avis après avoir acheté vos produits.</p>
                            </div>
                        </div>
                    @endforelse

                    @if(isset($reviews) && $reviews instanceof \Illuminate\Pagination\LengthAwarePaginator && $reviews->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
