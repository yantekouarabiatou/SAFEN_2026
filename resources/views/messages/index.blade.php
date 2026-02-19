@extends('layouts.app')

@section('title', 'Mes Messages')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Conversations</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($conversations as $conversation)
                        <a href="{{ route('messages.show', $conversation->otherUser()->id) }}"
                           class="list-group-item list-group-item-action d-flex align-items-center {{ $conversation->id == ($currentConversation ?? null) ? 'active' : '' }}">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ $conversation->otherUser()->avatar ?? asset('images/default-avatar.png') }}"
                                     class="rounded-circle" width="40" height="40" alt="Avatar">
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="mb-1">{{ $conversation->otherUser()->name }}</h6>
                                    <small class="text-muted">{{ $conversation->lastMessage?->created_at?->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-truncate small">
                                    {{ $conversation->lastMessage?->message ?? 'Nouvelle conversation' }}
                                </p>
                                @if($conversation->unread_count > 0)
                                <span class="badge bg-danger rounded-pill">{{ $conversation->unread_count }}</span>
                                @endif
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-chat-dots fs-1 mb-3"></i>
                            <p>Aucune conversation pour le moment</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Messages</h5>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="markAllAsRead()">Marquer tout comme lu</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="clearAllConversations()">Effacer toutes les conversations</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body text-center py-5">
                    <i class="bi bi-chat-dots fs-1 text-muted mb-3"></i>
                    <h5>Sélectionnez une conversation</h5>
                    <p class="text-muted">Cliquez sur une conversation dans la liste pour commencer à discuter.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAllAsRead() {
    if (confirm('Marquer tous les messages comme lus ?')) {
        fetch('{{ route("messages.markAllRead") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(() => location.reload());
    }
}

function clearAllConversations() {
    if (confirm('Êtes-vous sûr de vouloir effacer toutes les conversations ? Cette action est irréversible.')) {
        fetch('{{ route("messages.clearAll") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    }
}
</script>
@endsection