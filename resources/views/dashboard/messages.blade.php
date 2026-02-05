@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="section-header">
    <h1>Messages</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard.artisan') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Messages</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card chat-box" id="mychatbox">
                <div class="card-header">
                    <h4><i class="fas fa-comments"></i> Messagerie</h4>
                </div>
                <div class="card-body chat-content" style="height: 600px; overflow-y: auto;">
                    @forelse($messages ?? [] as $conversation)
                        @php
                            $otherUser = $conversation->user1_id == auth()->id() 
                                ? $conversation->user2 
                                : $conversation->user1;
                            $lastMessage = $conversation->messages()->latest()->first();
                        @endphp
                        <div class="chat-item mb-3 p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <img src="{{ $otherUser->avatar_url ?? asset('images/default-avatar.png') }}" 
                                     alt="{{ $otherUser->name ?? 'Utilisateur' }}" 
                                     class="rounded-circle mr-3"
                                     width="50">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">{{ $otherUser->name ?? 'Utilisateur' }}</h6>
                                        <small class="text-muted">
                                            {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                                        </small>
                                    </div>
                                    <p class="text-muted mb-0">
                                        {{ $lastMessage ? Str::limit($lastMessage->message, 60) : 'Aucun message' }}
                                    </p>
                                </div>
                                <div>
                                    <a href="{{ route('messages.show', $otherUser) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-envelope-open"></i> Ouvrir
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon bg-primary">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <h2>Aucun message</h2>
                                <p class="lead">Vous n'avez pas encore de conversations.</p>
                                <p>Les clients peuvent vous contacter via votre profil public.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
