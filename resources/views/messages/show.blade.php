@extends('layouts.app')

@section('title', 'Conversation avec ' . $user->name)

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
                        @forelse($conversations ?? [] as $conv)
                        <a href="{{ route('messages.show', $conv->otherUser()->id) }}"
                           class="list-group-item list-group-item-action d-flex align-items-center {{ $conv->id == $conversation->id ? 'active' : '' }}">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ $conv->otherUser()->avatar ?? asset('images/default-avatar.png') }}"
                                     class="rounded-circle" width="40" height="40" alt="Avatar">
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="mb-1">{{ $conv->otherUser()->name }}</h6>
                                    <small class="text-muted">{{ $conv->lastMessage?->created_at?->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-truncate small">
                                    {{ $conv->lastMessage?->message ?? 'Nouvelle conversation' }}
                                </p>
                                @if($conv->unread_count > 0)
                                <span class="badge bg-danger rounded-pill">{{ $conv->unread_count }}</span>
                                @endif
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-chat-dots fs-1 mb-3"></i>
                            <p>Aucune conversation</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <img src="{{ $user->avatar ?? asset('images/default-avatar.png') }}"
                         class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                    <div>
                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <small class="text-muted">{{ $user->role === 'artisan' ? 'Artisan' : 'Client' }}</small>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>

                <!-- Messages -->
                <div class="card-body chat-messages" style="height: 400px; overflow-y: auto;" id="messagesContainer">
                    @forelse($messages as $message)
                    <div class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }} mb-3">
                        <div class="d-flex {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="message-bubble {{ $message->sender_id === auth()->id() ? 'bg-benin-green text-white' : 'bg-light' }} p-3 rounded">
                                <p class="mb-1">{{ $message->message }}</p>
                                <small class="{{ $message->sender_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                    {{ $message->created_at->format('H:i') }}
                                    @if($message->read_at)
                                        <i class="bi bi-check2-all ms-1"></i>
                                    @else
                                        <i class="bi bi-check2 ms-1"></i>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-chat-dots fs-1 mb-3"></i>
                        <p>Commencez la conversation !</p>
                    </div>
                    @endforelse
                </div>

                <!-- Message Input -->
                <div class="card-footer">
                    <form id="messageForm" method="POST" action="{{ route('messages.send', $user) }}">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="message" class="form-control"
                                   placeholder="Tapez votre message..." required maxlength="1000">
                            <button type="submit" class="btn btn-benin-green">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-messages {
    background-color: #f8f9fa;
}

.message-bubble {
    max-width: 70%;
    word-wrap: break-word;
}

.message.sent .message-bubble {
    margin-left: auto;
}

.message.received .message-bubble {
    margin-right: auto;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-scroll to bottom
    scrollToBottom();

    // Submit form with AJAX
    const form = document.getElementById('messageForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const message = formData.get('message').trim();

        if (!message) return;

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add message to chat
                addMessage(data.message, true);
                form.reset();
                scrollToBottom();
            } else {
                alert('Erreur lors de l\'envoi du message');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'envoi du message');
        });
    });

    // Poll for new messages every 5 seconds
    setInterval(checkNewMessages, 5000);
});

function addMessage(message, isSent) {
    const container = document.getElementById('messagesContainer');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isSent ? 'sent' : 'received'} mb-3`;

    messageDiv.innerHTML = `
        <div class="d-flex ${isSent ? 'justify-content-end' : 'justify-content-start'}">
            <div class="message-bubble ${isSent ? 'bg-benin-green text-white' : 'bg-light'} p-3 rounded">
                <p class="mb-1">${message.message}</p>
                <small class="${isSent ? 'text-white-50' : 'text-muted'}">
                    ${new Date(message.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}
                    <i class="bi bi-check2 ms-1"></i>
                </small>
            </div>
        </div>
    `;

    container.appendChild(messageDiv);
}

function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
}

function checkNewMessages() {
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.newMessages && data.newMessages.length > 0) {
            data.newMessages.forEach(message => {
                addMessage(message, false);
            });
            scrollToBottom();
        }
    })
    .catch(error => console.error('Error checking messages:', error));
}
</script>
@endsection