<?php

namespace App\Http\Controllers;

use App\Models\GuestOrder; // ou Order selon ton modèle
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = GuestOrder::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('clients.orders.index', compact('orders'));
    }


    public function show(GuestOrder $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        return view('clients.orders.show', compact('order'));
    }

    public function tracking()
    {
        $user = Auth::user();

        // Récupérer uniquement les commandes en cours (processing, shipped, etc.)
        $orders = $user->orders()
            ->whereIn('order_status', ['pending', 'processing', 'shipped'])
            ->with(['items' => function ($query) {
                $query->with('product'); // Charger les produits si besoin
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.tracking', compact('orders'));
    }
    /**
     * Annule une commande (si elle est encore annulable).
     */
    public function cancel(GuestOrder $order)
    {
        // Vérification propriété
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Vérifier que la commande peut être annulée (par exemple statut 'pending')
        if ($order->order_status !== 'pending') {
            return redirect()->route('client.orders.index')
                            ->with('error', 'Cette commande ne peut plus être annulée.');
        }

        // Mettre à jour le statut
        $order->update([
            'order_status' => 'cancelled',
            // Optionnel : statut de paiement si nécessaire
            'payment_status' => 'failed',
        ]);

        return redirect()->route('client.orders.index')
                        ->with('success', 'Votre commande a été annulée avec succès.');
    }

}
