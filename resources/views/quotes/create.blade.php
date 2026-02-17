@extends('layouts.admin')

@section('title', 'Demander un devis')



@section('content')
<div class="row">
    <div class="col-12 col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Nouvelle demande de devis</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('client.quotes.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="artisan_id">Artisan *</label>
                        <select name="artisan_id" id="artisan_id" class="form-control @error('artisan_id') is-invalid @enderror" required style="width: 100%;">
                            <option value="">Rechercher un artisan...</option>
                            @foreach($artisans as $artisan)
                                <option value="{{ $artisan->id }}" {{ old('artisan_id', $selectedArtisanId) == $artisan->id ? 'selected' : '' }}>
                                    {{ $artisan->user->name }} - {{ $artisan->specialty ?? 'Artisan' }} ({{ $artisan->location ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        @error('artisan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="subject">Sujet *</label>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description détaillée *</label>
                        <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="budget">Budget estimé (FCFA)</label>
                                <input type="number" name="budget" id="budget" class="form-control @error('budget') is-invalid @enderror" value="{{ old('budget') }}" min="0">
                                @error('budget') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="desired_date">Date souhaitée</label>
                                <input type="date" name="desired_date" id="desired_date" class="form-control @error('desired_date') is-invalid @enderror" value="{{ old('desired_date') }}">
                                @error('desired_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Envoyer la demande</button>
                        <a href="{{ route('client.quotes.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#artisan_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Rechercher un artisan...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Aucun artisan trouvé";
                },
                searching: function() {
                    return "Recherche...";
                }
            }
        });
    });
</script>
@endpush