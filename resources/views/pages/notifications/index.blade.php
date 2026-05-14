@extends('layouts.app')

@section('title', 'Mes notifications — TOTCHÉMÈGNON')

@push('styles')
<style>
.notif-page { background: #f0f4f8; min-height: 100vh; padding: 32px 0 60px; }
.notif-header {
    background: linear-gradient(135deg, #005c38 0%, #008751 100%);
    padding: 28px 0 20px;
    color: #fff;
    margin-bottom: 28px;
}
.notif-header h1 { font-family:'Montserrat',sans-serif; font-size:1.5rem; font-weight:800; margin:0; }
.notif-header p  { font-size:.85rem; opacity:.8; margin:4px 0 0; }

.notif-toolbar {
    background: #fff; border-radius: 12px;
    padding: 12px 16px;
    display: flex; align-items: center; justify-content: space-between;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
    margin-bottom: 16px; gap: 8px; flex-wrap: wrap;
}
.notif-toolbar .badge-count {
    background: rgba(0,135,81,.1); color: #008751;
    border-radius: 20px; padding: 4px 12px;
    font-size: .75rem; font-weight: 700;
}
.btn-notif-action {
    padding: 7px 14px; border-radius: 8px; border: none;
    font-size: .78rem; font-weight: 600; cursor: pointer; transition: all .15s;
    display: inline-flex; align-items: center; gap: 5px;
}
.btn-mark-all { background: #f0fdf4; color: #008751; }
.btn-mark-all:hover { background: #dcfce7; }
.btn-clear-all { background: #fff1f2; color: #E8112D; }
.btn-clear-all:hover { background: #ffe4e6; }

/* Carte de notification */
.notif-card {
    background: #fff; border-radius: 14px;
    padding: 16px 18px;
    margin-bottom: 10px;
    display: flex; align-items: flex-start; gap: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    transition: all .18s; position: relative;
    border-left: 4px solid transparent;
}
.notif-card.unread { border-left-color: #008751; background: #f0fdf4; }
.notif-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.09); transform: translateY(-1px); }

.notif-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.notif-title { font-weight: 700; font-size: .88rem; color: #1a1d23; margin: 0 0 3px; }
.notif-message { font-size: .82rem; color: #6b7280; line-height: 1.5; margin: 0; }
.notif-time { font-size: .7rem; color: #9ca3af; margin-top: 6px; display: flex; align-items: center; gap: 4px; }

.notif-actions {
    display: flex; align-items: center; gap: 6px;
    margin-left: auto; flex-shrink: 0;
}
.btn-notif-sm {
    width: 30px; height: 30px; border-radius: 8px; border: none;
    background: #f3f4f6; color: #9ca3af; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; transition: all .15s;
}
.btn-notif-sm:hover { background: #e5e7eb; color: #4b5563; }
.btn-notif-delete:hover { background: #fff1f2; color: #E8112D; }

/* Empty state */
.notif-empty {
    text-align: center; padding: 60px 20px;
    background: #fff; border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}
.notif-empty-icon { font-size: 3.5rem; opacity: .2; display: block; margin-bottom: 16px; }

/* Pagination */
.pagination-wrap { display: flex; justify-content: center; margin-top: 24px; }
</style>
@endpush

@section('content')
{{-- Hero --}}
<div class="notif-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div style="width:44px;height:44px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div>
                <h1>Mes notifications</h1>
                <p>{{ $notifications->total() }} notification(s) · {{ auth()->user()->unreadNotifications->count() }} non lue(s)</p>
            </div>
        </div>
    </div>
</div>

<div class="notif-page" style="padding-top:0;">
    <div class="container">

        {{-- Toolbar --}}
        <div class="notif-toolbar">
            <div>
                <span class="badge-count">
                    {{ auth()->user()->unreadNotifications->count() }} non lue(s)
                </span>
            </div>
            <div class="d-flex gap-2">
                @if(auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}" id="markAllForm">
                    @csrf
                    <button type="submit" class="btn-notif-action btn-mark-all">
                        <i class="bi bi-check2-all"></i> Tout marquer comme lu
                    </button>
                </form>
                @endif
                @if($notifications->total() > 0)
                <form method="POST" action="{{ route('notifications.clear-all') }}" id="clearAllForm"
                      onsubmit="return confirm('Supprimer toutes les notifications ?')">
                    @csrf
                    <button type="submit" class="btn-notif-action btn-clear-all">
                        <i class="bi bi-trash3"></i> Tout supprimer
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Liste --}}
        @forelse($notifications as $notif)
        @php
            $data    = $notif->data;
            $isRead  = !is_null($notif->read_at);
            $icon    = $data['icon'] ?? 'bi-bell';
            $color   = $data['color'] ?? '#6b7280';
            $title   = $data['title'] ?? 'Notification';
            $message = $data['message'] ?? '';
            $url     = $data['url'] ?? '#';
        @endphp
        <div class="notif-card {{ $isRead ? '' : 'unread' }}" id="notif-{{ $notif->id }}">
            {{-- Icône --}}
            <div class="notif-icon" style="background:{{ $color }}1a;">
                <i class="bi {{ $icon }}" style="color:{{ $color }};"></i>
            </div>

            {{-- Contenu --}}
            <div style="flex:1;min-width:0;">
                <div class="notif-title">
                    @if(!$isRead)
                        <span style="width:8px;height:8px;background:#008751;border-radius:50%;display:inline-block;margin-right:6px;"></span>
                    @endif
                    {{ $title }}
                </div>
                <p class="notif-message">{{ $message }}</p>
                <div class="notif-time">
                    <i class="bi bi-clock" style="font-size:.65rem;"></i>
                    {{ $notif->created_at->diffForHumans() }}
                    @if($isRead)
                        <span style="color:#d1d5db;">·</span>
                        <i class="bi bi-check2-all" style="color:#008751;font-size:.65rem;"></i>
                        <span>Lu</span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="notif-actions">
                @if($url && $url !== '#')
                <a href="{{ $url }}" class="btn-notif-sm" title="Voir" onclick="markRead('{{ $notif->id }}')">
                    <i class="bi bi-arrow-right"></i>
                </a>
                @endif
                @if(!$isRead)
                <button class="btn-notif-sm" title="Marquer comme lu" onclick="markRead('{{ $notif->id }}', true)">
                    <i class="bi bi-check2"></i>
                </button>
                @endif
                <button class="btn-notif-sm btn-notif-delete" title="Supprimer" onclick="deleteNotif('{{ $notif->id }}')">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="notif-empty">
            <i class="bi bi-bell-slash notif-empty-icon"></i>
            <div class="fw-bold mb-2" style="font-size:1.1rem;color:#6b7280;">Aucune notification</div>
            <div class="text-muted" style="font-size:.85rem;">Vous serez notifié ici des nouvelles activités importantes</div>
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($notifications->hasPages())
        <div class="pagination-wrap">
            {{ $notifications->links() }}
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function markRead(id, refresh = false) {
    fetch('/notifications/' + id + '/read', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
    }).then(function() {
        const card = document.getElementById('notif-' + id);
        if (card) {
            card.classList.remove('unread');
            const dot = card.querySelector('span[style*="background:#008751"]');
            if (dot) dot.remove();
        }
        updateBellCount();
        if (refresh) location.reload();
    });
}

function deleteNotif(id) {
    fetch('/notifications/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
    }).then(function() {
        const card = document.getElementById('notif-' + id);
        if (card) { card.style.opacity = '0'; card.style.transform = 'translateX(20px)'; setTimeout(() => card.remove(), 250); }
        updateBellCount();
    });
}

function updateBellCount() {
    fetch('/notifications/unread-count')
        .then(r => r.json())
        .then(function(d) {
            const badge = document.getElementById('notif-bell-badge');
            if (badge) {
                badge.textContent = d.count;
                badge.style.display = d.count > 0 ? '' : 'none';
            }
        });
}
</script>
@endpush
