@extends('layouts.admin')

@section('title', 'Artisans en attente')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Artisans en attente de validation</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom de l'entreprise</th>
                            <th>Métier</th>
                            <th>Ville</th>
                            <th>Téléphone</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingArtisans as $artisan)
                        <tr>
                            <td>{{ $artisan->business_name }}</td>
                            <td>{{ $artisan->craft_label }}</td>
                            <td>{{ $artisan->city }}</td>
                            <td>{{ $artisan->phone }}</td>
                            <td>{{ $artisan->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('artisans.show', $artisan) }}" 
                                   class="btn btn-sm btn-info" target="_blank">
                                    Voir
                                </a>
                                <form action="{{ route('admin.artisans.approve', $artisan) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        Approuver
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal{{ $artisan->id }}">
                                    Rejeter
                                </button>

                                <!-- Modal pour rejet -->
                                <div class="modal fade" id="rejectModal{{ $artisan->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.artisans.reject', $artisan) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Rejeter l'artisan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="reason" class="form-label">Raison du rejet</label>
                                                        <textarea class="form-control" 
                                                                  id="reason" 
                                                                  name="reason" 
                                                                  rows="4" 
                                                                  required
                                                                  placeholder="Expliquez pourquoi vous rejetez ce profil..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-danger">Rejeter</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucun artisan en attente de validation</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $pendingArtisans->links() }}
        </div>
    </div>
</div>
@endsection