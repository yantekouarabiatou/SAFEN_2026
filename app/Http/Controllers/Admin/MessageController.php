<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Afficher la liste des messages
     */
 public function index(Request $request)
{
    // Récupérer les données
    $messages = Message::with(['sender', 'receiver'])->paginate(15);
    $stats = [
        'total' => Message::count(),
        'unread' => Message::whereNull('read_at')->count(),
        'read' => Message::whereNotNull('read_at')->count(),
        'conversations' => Conversation::count()
    ];
    $users = User::orderBy('name')->get(['id', 'name', 'email']);
    $types = Message::distinct('type')->pluck('type');
    
    // Retourner la vue avec les données
    return view('admin.messages.index', compact('messages', 'stats', 'users', 'types'));
}
    /**
     * Afficher les détails d'un message
     */
    public function show(Message $message)
    {
        $message->load(['sender', 'receiver', 'conversation', 'reference', 'replies']);

        // Marquer comme lu si ce n'est pas le cas
        if (!$message->read_at) {
            $message->markAsRead();
        }
        
        return view('admin.messages.show', compact('message'));
    }

    /**
     * Marquer un message comme lu
     */
    public function markAsRead(Message $message)
    {
        $message->markAsRead();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Message marqué comme lu'
            ]);
        }

        return back()->with('success', 'Message marqué comme lu');
    }

    /**
     * Marquer un message comme non lu
     */
    public function markAsUnread(Message $message)
    {
        $message->update(['read_at' => null]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Message marqué comme non lu'
            ]);
        }

        return back()->with('success', 'Message marqué comme non lu');
    }

    /**
     * Répondre à un message
     */
    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'content' => 'required|string|max:5000'
        ]);

        // Créer la réponse
        $reply = Message::create([
            'conversation_id' => $message->conversation_id,
            'sender_id' => auth()->id(),
            'receiver_id' => $message->sender_id,
            'message' => $request->content,
            'type' => 'reply',
            'reference_id' => $message->id
        ]);

        // Mettre à jour la conversation
        if ($message->conversation) {
            $message->conversation->update(['last_message_at' => now()]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Réponse envoyée',
                'data' => $reply->load('sender')
            ]);
        }

        return back()->with('success', 'Réponse envoyée');
    }

    /**
     * Supprimer un message
     */
    public function destroy(Message $message)
    {
        $message->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Message supprimé avec succès'
            ]);
        }

        return redirect()->route('admin.messages.index')->with('success', 'Message supprimé');
    }

    /**
     * Actions groupées sur les messages
     */
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

    /**
     * Afficher les conversations
     */
    public function conversations()
    {
        $conversations = Conversation::with(['user1', 'user2'])
            ->withCount(['messages'])
            ->withMax('messages', 'created_at as last_message_at')
            ->latest('last_message_at')
            ->paginate(15);

        // Ajouter le nombre de messages non lus pour chaque conversation
        foreach ($conversations as $conversation) {
            $conversation->unread_count = Message::where('conversation_id', $conversation->id)
                ->where('receiver_id', auth()->id())
                ->whereNull('read_at')
                ->count();
            
            $conversation->last_message = Message::where('conversation_id', $conversation->id)
                ->latest()
                ->first();
        }

        return view('admin.messages.conversations', compact('conversations'));
    }
}