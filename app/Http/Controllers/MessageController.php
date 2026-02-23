<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        // Statistiques
        $stats = [
            'total' => Message::count(),
            'unread' => Message::whereNull('read_at')->count(),
            'read' => Message::whereNotNull('read_at')->count(),
            'conversations' => Conversation::count() // ou Message::distinct('conversation_id')->count('conversation_id')
        ];

        $query = Message::with(['sender', 'receiver', 'conversation']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhereHas('sender', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('receiver', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->status == 'unread') {
                $query->whereNull('read_at');
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user')) {
            $userId = $request->user;
            $query->where(function($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            });
        }

        // Tri
        $query->latest();

        // Pagination
        $messages = $query->paginate(15);

        // Liste des utilisateurs pour le filtre
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        // Types de messages pour le filtre
        $types = Message::distinct('type')->pluck('type');

        return view('admin.messages.index', compact('messages', 'stats', 'users', 'types'));
    }

    public function show(Message $message)
    {
        $message->load(['sender', 'receiver', 'conversation', 'reference', 'replies']);

        // Marquer comme lu si ce n'est pas le cas
        if (!$message->read_at) {
            $message->markAsRead();
        }

        return view('admin.messages.show', compact('message'));
    }

    public function markAsRead(Message $message)
    {
        $message->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Message marqué comme lu'
        ]);
    }

    public function markAsUnread(Message $message)
    {
        $message->update(['read_at' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Message marqué comme non lu'
        ]);
    }

    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'content' => 'required|string|max:5000'
        ]);

        $reply = Message::create([
            'conversation_id' => $message->conversation_id,
            'sender_id' => auth()->id(),
            'receiver_id' => $message->sender_id,
            'message' => $request->content,
            'type' => 'reply',
            'reference_id' => $message->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Réponse envoyée',
            'data' => $reply
        ]);
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message supprimé avec succès'
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,mark-read,mark-unread',
            'ids' => 'required|array',
            'ids.*' => 'exists:messages,id'
        ]);

        $count = count($request->ids);

        switch($request->action) {
            case 'delete':
                Message::whereIn('id', $request->ids)->delete();
                $message = "$count messages supprimés";
                break;
                
            case 'mark-read':
                Message::whereIn('id', $request->ids)->update(['read_at' => now()]);
                $message = "$count messages marqués comme lus";
                break;
                
            case 'mark-unread':
                Message::whereIn('id', $request->ids)->update(['read_at' => null]);
                $message = "$count messages marqués comme non lus";
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

public function conversations()
{
    // Récupérer toutes les conversations
    $allConversations = Conversation::with(['user1', 'user2'])->get();
    
    $conversationsData = [];
    
    foreach ($allConversations as $conversation) {
        // Récupérer les messages de cette conversation
        $messages = Message::where('conversation_id', $conversation->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        if ($messages->count() > 0) {
            $lastMessage = $messages->first();
            
            $conversationsData[] = (object)[
                'id' => $conversation->id,
                'user1' => $conversation->user1,
                'user2' => $conversation->user2,
                'last_message' => $lastMessage,
                'last_message_at' => $lastMessage->created_at,
                'messages_count' => $messages->count(),
                'unread_count' => $messages
                    ->where('receiver_id', auth()->id())
                    ->whereNull('read_at')
                    ->count()
            ];
        }
    }
    
    // Trier par date du dernier message
    usort($conversationsData, function($a, $b) {
        return $b->last_message_at->timestamp <=> $a->last_message_at->timestamp;
    });
    
    // Pagination manuelle
    $page = request()->get('page', 1);
    $perPage = 15;
    $total = count($conversationsData);
    $items = array_slice($conversationsData, ($page - 1) * $perPage, $perPage);
    
    $conversations = new \Illuminate\Pagination\LengthAwarePaginator(
        $items,
        $total,
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );
    
    return view('admin.messages.conversations', compact('conversations'));
}
}