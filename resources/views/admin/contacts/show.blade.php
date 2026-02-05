@extends('layouts.admin')

@section('title', 'Message de ' . $contact->name)

@section('content')
<div class="section-header">
    <h1>Message de contact</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">Contacts</a></div>
        <div class="breadcrumb-item active">{{ $contact->name }}</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $contact->subject ?? 'Sans sujet' }}</h4>
                    <div class="card-header-action">
                        @switch($contact->status ?? 'pending')
                            @case('resolved')
                                <span class="badge badge-success">Résolu</span>
                                @break
                            @case('pending')
                                <span class="badge badge-warning">En attente</span>
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="text-muted mb-1">Message:</p>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($contact->message)) !!}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Date:</strong> {{ $contact->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Lu:</strong> {{ $contact->is_read ? 'Oui' : 'Non' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Répondre --}}
            <div class="card">
                <div class="card-header">
                    <h4>Répondre</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Votre réponse</label>
                            <textarea name="reply" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Envoyer la réponse
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            {{-- Expéditeur --}}
            <div class="card">
                <div class="card-header">
                    <h4>Expéditeur</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-user text-primary mr-2"></i>
                            <strong>{{ $contact->name }}</strong>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-envelope text-primary mr-2"></i>
                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        </li>
                        @if($contact->phone)
                        <li class="mb-3">
                            <i class="fas fa-phone text-primary mr-2"></i>
                            <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                        </li>
                        @endif
                    </ul>
                    
                    <a href="mailto:{{ $contact->email }}" class="btn btn-outline-primary btn-block">
                        <i class="fas fa-envelope"></i> Envoyer un email
                    </a>
                </div>
            </div>
            
            {{-- Actions --}}
            <div class="card">
                <div class="card-header">
                    <h4>Actions</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.contacts.update-status', $contact) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="form-group">
                            <label>Statut</label>
                            <select name="status" class="form-control">
                                <option value="pending" {{ ($contact->status ?? 'pending') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="resolved" {{ ($contact->status ?? '') == 'resolved' ? 'selected' : '' }}>Résolu</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            Mettre à jour
                        </button>
                    </form>
                    
                    <hr>
                    
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                    
                    <button type="button" class="btn btn-danger btn-block" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    Swal.fire({
        title: 'Supprimer ce message ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}
</script>
@endpush
