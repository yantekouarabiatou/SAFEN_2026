@extends('layouts.admin')

@section('title', 'Conversations')

@section('content')
<div class="section-header">
    <h1>Conversations</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.messages.index') }}">Messages</a></div>
        <div class="breadcrumb-item active">Conversations</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des conversations</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="conversations-table">
                            <thead>
                                <tr>
                                    <th>Participants</th>
                                    <th>Dernier message</th>
                                    <th>Date</th>
                                    <th>Messages</th>
                                    <th>Non lus</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($conversations as $conversation)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                            $participants = [$conversation->user1, $conversation->user2];
                                            @endphp
                                            @foreach($participants as $participant)
                                            @if($participant)
                                            <div class="d-flex align-items-center mr-3">
                                                @if($participant->avatar)
                                                <img src="{{ $participant->avatar }}"
                                                    alt="{{ $participant->name }}"
                                                    class="rounded-circle mr-1" width="30" height="30">
                                                @else
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-1"
                                                    style="width: 30px; height: 30px; font-size: 12px;">
                                                    {{ strtoupper(substr($participant->name, 0, 1)) }}
                                                </div>
                                                @endif
                                                <small>{{ $participant->name }}</small>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        @if($conversation->last_message)
                                        {{ Str::limit($conversation->last_message->message, 50) }}
                                        @else
                                        <em>Aucun message</em>
                                        @endif
                                    </td>
                                    <td>
                                        @if($conversation->last_message_at)
                                        {{ \Carbon\Carbon::parse($conversation->last_message_at)->format('d/m/Y H:i') }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $conversation->messages_count }}</td>
                                    <td class="text-center">
                                        @if($conversation->unread_count > 0)
                                        <span class="badge badge-danger">{{ $conversation->unread_count }}</span>
                                        @else
                                        <span class="badge badge-success">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.messages.index', ['user' => $conversation->user1->id ?? $conversation->user2->id]) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-light">
                                                <i class="fas fa-comments fa-3x text-muted"></i>
                                            </div>
                                            <h4>Aucune conversation</h4>
                                            <p class="text-muted">Il n'y a pas encore de conversations.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($conversations->hasPages())
                <div class="card-footer">
                    {{ $conversations->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection