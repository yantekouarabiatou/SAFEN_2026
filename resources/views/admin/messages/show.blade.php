@extends('layouts.admin')

@section('title', 'Détail du message')

@section('content')
<div class="section-header">
    <h1>Détail du message</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.messages.index') }}">Messages</a></div>
        <div class="breadcrumb-item active">Détail</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Informations du message</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Expéditeur</h6>
                                    <div class="d-flex align-items-center">
                                        @if($message->sender && $message->sender->avatar)
                                            <img src="{{ $message->sender->avatar }}" 
                                                 alt="{{ $message->sender->name }}" 
                                                 class="rounded-circle mr-3" width="50" height="50">
                                        @else
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-3" 
                                                 style="width: 50px; height: 50px; font-size: 18px;">
                                                {{ $message->sender ? strtoupper(substr($message->sender->name, 0, 1)) : '?' }}
                                            </div>
                                        @endif
                                        <div>
                                            <h5>{{ $message->sender->name ?? 'Utilisateur supprimé' }}</h5>
                                            <p class="mb-0">{{ $message->sender->email ?? '' }}</p>
                                            <small class="text-muted">Rôle: {{ $message->sender->role ?? 'Utilisateur' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Destinataire</h6>
                                    <div class="d-flex align-items-center">
                                        @if($message->receiver && $message->receiver->avatar)
                                            <img src="{{ $message->receiver->avatar }}" 
                                                 alt="{{ $message->receiver->name }}" 
                                                 class="rounded-circle mr-3" width="50" height="50">
                                        @else
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-3" 
                                                 style="width: 50px; height: 50px; font-size: 18px;">
                                                {{ $message->receiver ? strtoupper(substr($message->receiver->name, 0, 1)) : '?' }}
                                            </div>
                                        @endif
                                        <div>
                                            <h5>{{ $message->receiver->name ?? 'Utilisateur supprimé' }}</h5>
                                            <p class="mb-0">{{ $message->receiver->email ?? '' }}</p>
                                            <small class="text-muted">Rôle: {{ $message->receiver->role ?? 'Utilisateur' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Message</h5>
                                    <div class="card-header-action">
                                        @if(!$message->read_at)
                                            <button class="btn btn-success btn-sm" id="markAsRead">
                                                <i class="fas fa-check"></i> Marquer comme lu
                                            </button>
                                        @else
                                            <button class="btn btn-warning btn-sm" id="markAsUnread">
                                                <i class="fas fa-envelope"></i> Marquer comme non lu
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <small class="text-muted">Envoyé le {{ $message->created_at->format('d/m/Y à H:i') }}</small>
                                        @if($message->read_at)
                                            <br>
                                            <small class="text-muted">Lu le {{ $message->read_at->format('d/m/Y à H:i') }}</small>
                                        @endif
                                        @if($message->type)
                                            <br>
                                            <span class="badge badge-info">Type: {{ $message->type }}</span>
                                        @endif
                                    </div>
                                    <div class="p-4 bg-light rounded">
                                        {{ $message->message }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($message->reference_id)
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card bg-info-light">
                                <div class="card-header">
                                    <h6><i class="fas fa-reply"></i> Ce message est une réponse</h6>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('admin.messages.show', $message->reference_id) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i> Voir le message original
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($message->replies && $message->replies->count() > 0)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Réponses ({{ $message->replies->count() }})</h5>
                                </div>
                                <div class="card-body">
                                    @foreach($message->replies as $reply)
                                    <div class="mb-3 p-3 {{ $reply->sender_id == auth()->id() ? 'bg-light text-right' : 'bg-info-light' }} rounded">
                                        <strong>{{ $reply->sender->name ?? 'Inconnu' }}</strong>
                                        <small class="text-muted ml-2">{{ $reply->created_at->format('d/m H:i') }}</small>
                                        <p class="mb-0 mt-2">{{ $reply->message }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Répondre</h5>
                                </div>
                                <div class="card-body">
                                    <form id="replyForm">
                                        @csrf
                                        <div class="form-group">
                                            <label>Votre réponse</label>
                                            <textarea name="content" class="form-control" rows="5" placeholder="Écrivez votre réponse..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Envoyer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Marquer comme lu
    $('#markAsRead').click(function() {
        $.ajax({
            url: '{{ route("admin.messages.mark-read", $message) }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                Swal.fire('Succès!', response.message, 'success')
                    .then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
            }
        });
    });

    // Marquer comme non lu
    $('#markAsUnread').click(function() {
        $.ajax({
            url: '{{ route("admin.messages.mark-unread", $message) }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                Swal.fire('Succès!', response.message, 'success')
                    .then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
            }
        });
    });

    // Répondre
    $('#replyForm').submit(function(e) {
        e.preventDefault();
        
        var content = $('textarea[name="content"]').val();
        
        if (!content) {
            Swal.fire('Erreur', 'Le message ne peut pas être vide', 'error');
            return;
        }
        
        $.ajax({
            url: '{{ route("admin.messages.reply", $message) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                content: content
            },
            success: function(response) {
                Swal.fire('Envoyé!', response.message, 'success')
                    .then(() => {
                        $('textarea[name="content"]').val('');
                        location.reload();
                    });
            },
            error: function(xhr) {
                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
            }
        });
    });
});
</script>
@endpush