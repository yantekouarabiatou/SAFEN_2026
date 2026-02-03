@extends('layouts.app')

@section('title', 'Artisans à proximité')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Artisans à proximité</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filtres</h5>
                    <form method="GET" action="{{ route('artisans.nearby') }}">
                        <div class="mb-3">
                            <label for="radius" class="form-label">Rayon (km)</label>
                            <input type="range" class="form-range" min="1" max="50" step="1" id="radius" name="radius" value="{{ $radius }}" onchange="updateRadiusValue(this.value)">
                            <div class="text-center">
                                <span id="radiusValue">{{ $radius }}</span> km
                            </div>
                        </div>
                        <input type="hidden" name="latitude" value="{{ $latitude }}">
                        <input type="hidden" name="longitude" value="{{ $longitude }}">
                        <button type="submit" class="btn btn-primary w-100">Appliquer</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @if($artisans->count() > 0)
                <div class="row">
                    @foreach($artisans as $artisan)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="{{ $artisan->photos->first()->photo_url ?? asset('images/default-artisan.jpg') }}" class="card-img-top" alt="{{ $artisan->user->name }}" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $artisan->user->name }}</h5>
                                    <p class="card-text">
                                        <strong>Métier:</strong> {{ $artisan->craft_label }}<br>
                                        <strong>Distance:</strong> {{ round($artisan->distance, 2) }} km<br>
                                        <strong>Ville:</strong> {{ $artisan->city }}
                                    </p>
                                    <a href="{{ route('artisans.show', $artisan) }}" class="btn btn-primary">Voir le profil</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center">
                    {{ $artisans->appends(request()->query())->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    Aucun artisan trouvé dans ce rayon. Essayez d'augmenter le rayon de recherche.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function updateRadiusValue(value) {
    document.getElementById('radiusValue').textContent = value;
}
</script>
@endsection
