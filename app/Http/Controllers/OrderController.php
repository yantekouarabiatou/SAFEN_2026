<?php

namespace App\Http\Controllers;

use App\Models\GuestOrder; // ou Order selon ton modèle
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
   public function show(GuestOrder $order)
{
    return view('orders.show', compact('order'));
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

        return view('orders.tracking', compact('orders'));
    }
}
