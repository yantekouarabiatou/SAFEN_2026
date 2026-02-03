<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::getOrCreateCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        // Vérifier le stock des produits
        foreach ($cart->items as $item) {
            if ($item->product->stock_status === 'out_of_stock') {
                return redirect()->route('cart.index')
                    ->with('error', "Le produit {$item->product->name} n'est plus disponible.");
            }
        }

        $user = Auth::user();
        $addresses = $user->addresses ?? [];

        return view('checkout.index', compact('cart', 'user', 'addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_neighborhood' => 'nullable|string',
            'shipping_phone' => 'required|string',
            'shipping_notes' => 'nullable|string',
            'payment_method' => 'required|in:kkiapay,mtn_momo,moov_money,visa,mastercard',
            'billing_same_as_shipping' => 'boolean'
        ]);

        $cart = Cart::getOrCreateCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        // Vérifier le stock à nouveau
        foreach ($cart->items as $item) {
            $product = $item->product;

            if ($product->stock_status === 'out_of_stock') {
                return redirect()->route('cart.index')
                    ->with('error', "Le produit {$product->name} n'est plus disponible.");
            }

            if ($product->stock_status === 'low_stock' && $item->quantity > 2) {
                return redirect()->route('cart.index')
                    ->with('error', "Stock limité pour {$product->name}.");
            }
        }

        DB::beginTransaction();

        try {
            // Créer la commande
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'CMD-' . time() . '-' . rand(1000, 9999),
                'status' => 'pending',
                'total' => $cart->total,
                'item_count' => $cart->item_count,

                // Informations de livraison
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_neighborhood' => $request->shipping_neighborhood,
                'shipping_phone' => $request->shipping_phone,
                'shipping_notes' => $request->shipping_notes,

                // Informations de facturation
                'billing_address' => $request->billing_same_as_shipping ? $request->shipping_address : $request->billing_address,
                'billing_city' => $request->billing_same_as_shipping ? $request->shipping_city : $request->billing_city,

                // Méthode de paiement
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',

                // Frais
                'shipping_fee' => 0, // Livraison gratuite pour le MVP
                'tax_amount' => 0,   // Taxes incluses

                // Informations artisanales
                'artisan_notes' => 'Commande générée depuis AFRI-HERITAGE',
            ]);

            // Créer les items de commande
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'artisan_id' => $cartItem->product->artisan_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->price,
                    'total_price' => $cartItem->total,
                    'product_data' => json_encode([
                        'name' => $cartItem->product->name,
                        'image' => $cartItem->product->images->first()->image_url ?? null,
                        'artisan_name' => $cartItem->product->artisan->user->name,
                    ]),
                ]);

                // Mettre à jour le stock du produit
                $product = $cartItem->product;
                if ($product->stock_status === 'low_stock') {
                    $product->stock_status = 'out_of_stock';
                }
                $product->order_count += $cartItem->quantity;
                $product->save();
            }

            // Vider le panier
            $cart->items()->delete();
            $cart->update(['status' => 'completed']);

            DB::commit();

            // Initialiser le paiement via l'API choisie
            if (in_array($request->payment_method, ['kkiapay', 'mtn_momo', 'moov_money'])) {
                return $this->initiateMobilePayment($order);
            } else {
                // Pour les cartes, rediriger vers la page de paiement
                return redirect()->route('checkout.success', $order)
                    ->with('success', 'Commande créée avec succès !');
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la commande: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    public function cancel()
    {
        return view('checkout.cancel')
            ->with('error', 'Le paiement a été annulé.');
    }

    protected function initiateMobilePayment(Order $order)
    {
        // Configuration pour KkiaPay (exemple)
        $config = [
            'public_key' => config('services.kkiapay.public_key'),
            'amount' => $order->total * 100, // En centimes
            'currency' => 'XOF',
            'order_id' => $order->order_number,
            'metadata' => json_encode(['order_id' => $order->id]),
            'callback_url' => route('checkout.webhook'),
            'theme' => '#009639',
            'name' => 'AFRI-HERITAGE Bénin',
        ];

        // Pour le MVP, on simule un paiement réussi
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'paid_at' => now(),
        ]);

        return redirect()->route('checkout.success', $order)
            ->with('success', 'Paiement effectué avec succès !');
    }

    public function webhook(Request $request)
    {
        // Gérer les webhooks de paiement
        $data = $request->all();

        if ($data['status'] === 'SUCCESS') {
            $order = Order::where('order_number', $data['order_id'])->first();

            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'paid_at' => now(),
                    'payment_reference' => $data['transaction_id'],
                ]);

                // Notifier l'utilisateur et les artisans
                // $this->sendOrderNotifications($order);
            }
        }

        return response()->json(['status' => 'received']);
    }
}
