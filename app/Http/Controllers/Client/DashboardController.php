<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GuestOrder;
use App\Models\Quote;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord du client.
     */
    public function index()
    {
        $user = auth()->user();

        // Statistiques
        $stats = [
            'orders_count'       => GuestOrder::where('user_id', $user->id)->count(),
            'quotes_count'       => Quote::where('user_id', $user->id)->count(),
            'pending_orders'     => GuestOrder::where('user_id', $user->id)->where('order_status', 'pending')->count(),
            'favorites_count'    => $user->favorites()->count(),
            'unread_messages'    => $user->unreadMessages()->count(),
        ];

        // 5 dernières commandes
        $recentOrders = GuestOrder::where('user_id', $user->id)
                        ->latest()
                        ->take(5)
                        ->get();

        // 5 derniers devis avec l'artisan associé
        $recentQuotes = Quote::where('user_id', $user->id)
                        ->with('artisan.user') // artisan.user permet d'accéder au nom de l'artisan
                        ->latest()
                        ->take(5)
                        ->get();

        // 5 derniers contacts (messages envoyés)
        $recentContacts = $user->contacts()->latest()->take(5)->get();

        return view('clients.dashboard', compact('user', 'stats', 'recentOrders', 'recentQuotes', 'recentContacts'));
    }
}
