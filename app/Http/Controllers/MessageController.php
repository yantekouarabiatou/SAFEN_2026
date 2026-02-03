<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        // Récupérer toutes les conversations de l'utilisateur
        $conversations = Conversation::where(function($query) {
                $query->where('user1_id', Auth::id())
                      ->orWhere('user2_id', Auth::id());
            })
            ->with(['user1', 'user2', 'lastMessage'])
            ->withCount(['messages as unread_count' => function($query) {
                $query->where('read_at', null)
                      ->where('receiver_id', Auth::id());
            }])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        // Marquer les notifications comme lues
        Auth::user()->unreadNotifications()
            ->where('type', 'App\Notifications\NewMessage')
            ->update(['read_at' => now()]);

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        // Vérifier qu'on ne peut pas s'envoyer de message à soi-même
        if ($user->id === Auth::id()) {
            abort(403, 'Vous ne pouvez pas vous envoyer de message à vous-même.');
        }

        // Trouver ou créer une conversation
        $conversation = Conversation::where(function($query) use ($user) {
                $query->where('user1_id', Auth::id())
                      ->where('user2_id', $user->id);
            })
            ->orWhere(function($query) use ($user) {
                $query->where('user1_id', $user->id)
                      ->where('user2_id', Auth::id());
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => Auth::id(),
                'user2_id' => $user->id,
            ]);
        }

        // Récupérer les messages
        $messages = Message::where('conversation_id', $conversation->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        // Marquer les messages comme lus
        Message::where('conversation_id', $conversation->id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', compact('conversation', 'messages', 'user'));
    }

    public function send(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'type' => 'nullable|in:text,quote,order',
            'reference_id' => 'nullable|integer',
        ]);

        // Vérifier qu'on ne peut pas s'envoyer de message à soi-même
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas vous envoyer de message à vous-même.'
            ], 403);
        }

        // Trouver ou créer une conversation
        $conversation = Conversation::where(function($query) use ($user) {
                $query->where('user1_id', Auth::id())
                      ->where('user2_id', $user->id);
            })
            ->orWhere(function($query) use ($user) {
                $query->where('user1_id', $user->id)
                      ->where('user2_id', Auth::id());
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => Auth::id(),
                'user2_id' => $user->id,
            ]);
        }

        // Créer le message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
            'type' => $request->type ?? 'text',
            'reference_id' => $request->reference_id,
        ]);

        // Mettre à jour la conversation
        $conversation->update(['updated_at' => now()]);

        // Notifier le destinataire
        $user->notify(new \App\Notifications\NewMessage($message));

        // Envoyer une notification push (si configuré)
        if ($user->fcm_token) {
            $this->sendPushNotification($user, 'Nouveau message', $request->message);
        }

        return response()->json([
            'success' => true,
            'message' => $message->load('sender'),
        ]);
    }

    public function destroy(Message $message)
    {
        // Vérifier que l'utilisateur est l'expéditeur
        if ($message->sender_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->back()
            ->with('success', 'Message supprimé avec succès.');
    }

    public function markAsRead(Message $message)
    {
        // Vérifier que l'utilisateur est le destinataire
        if ($message->receiver_id !== Auth::id()) {
            abort(403);
        }

        $message->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        // Rechercher des utilisateurs
        $users = User::where('id', '!=', Auth::id())
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            })
            ->where('is_active', true)
            ->limit(10)
            ->get();

        // Rechercher dans les conversations existantes
        $conversations = Conversation::where(function($query) {
                $query->where('user1_id', Auth::id())
                      ->orWhere('user2_id', Auth::id());
            })
            ->whereHas('messages', function($query) use ($search) {
                $query->where('message', 'like', "%$search%");
            })
            ->with(['user1', 'user2'])
            ->limit(10)
            ->get();

        return response()->json([
            'users' => $users,
            'conversations' => $conversations,
        ]);
    }

    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    protected function sendPushNotification($user, $title, $body)
    {
        // Implémentation avec Firebase Cloud Messaging
        // Nécessite le package firebase/php-jwt et une configuration Firebase

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = config('services.fcm.server_key');

        if (!$serverKey || !$user->fcm_token) {
            return;
        }

        $notification = [
            'title' => $title,
            'body' => $body,
            'icon' => asset('images/logo.png'),
            'click_action' => url('/messages'),
        ];

        $data = [
            'type' => 'message',
            'sender_id' => Auth::id(),
            'sender_name' => Auth::user()->name,
        ];

        $fcmNotification = [
            'to' => $user->fcm_token,
            'notification' => $notification,
            'data' => $data,
            'priority' => 'high',
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function sendToArtisan(Request $request, $artisanId)
    {
        $artisan = \App\Models\Artisan::findOrFail($artisanId);
        $user = $artisan->user;

        return $this->send($request, $user);
    }

    public function sendToVendor(Request $request, $vendorId)
    {
        $vendor = \App\Models\Vendor::findOrFail($vendorId);
        $user = $vendor->user;

        return $this->send($request, $user);
    }
}
