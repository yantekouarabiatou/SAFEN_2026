<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::getOrCreateCart();
        $cart->load(['items.product.images']);

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $cart = Cart::getOrCreateCart();

            // Calculer la quantité actuelle dans le panier
            $currentCartQuantity = 0;
            $optionsKey = $request->options ? json_encode($request->options) : null;

            $existingItem = $cart->items()
                ->where('product_id', $product->id)
                ->when($optionsKey, function($query) use ($optionsKey) {
                    return $query->where('options', $optionsKey);
                })
                ->when(!$optionsKey, function($query) {
                    return $query->whereNull('options');
                })
                ->first();

            if ($existingItem) {
                $currentCartQuantity = $existingItem->quantity;
            }

            // Valider la disponibilité
            $validation = $this->validateProductAvailability($product, $request->quantity, $currentCartQuantity);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ], 400);
            }

            if ($existingItem) {
                // Mettre à jour la quantité
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $request->quantity
                ]);
            } else {
                // Ajouter un nouvel item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'options' => $request->options
                ]);
            }

            // Mettre à jour les totaux
            $cart->updateTotals();

            // Recharger le panier avec les relations
            $cart->load(['items.product']);

            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier avec succès !',
                'cart_count' => $cart->item_count,
                'cart_total' => $cart->formatted_total,
                'item' => [
                    'product_name' => $product->name,
                    'quantity' => $request->quantity,
                    'options' => $request->options
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur ajout au panier: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'ajout au panier. Veuillez réessayer.'
            ], 500);
        }
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:20'
        ]);

        // Vérifier que l'item appartient au panier de l'utilisateur
        $cart = Cart::getOrCreateCart();

        if ($item->cart_id !== $cart->id) {
            abort(403);
        }

        // Vérifier le stock
        $product = $item->product;

        if ($product->stock_status === 'low_stock' && $request->quantity > 2) {
            return redirect()->back()
                ->with('error', 'Stock limité. Maximum 2 unités.');
        }

        $item->update([
            'quantity' => $request->quantity
        ]);

        // Mettre à jour les totaux
        $cart->updateTotals();

        return redirect()->back()
            ->with('success', 'Quantité mise à jour.');
    }

    public function remove(CartItem $item)
    {
        // Vérifier que l'item appartient au panier de l'utilisateur
        $cart = Cart::getOrCreateCart();

        if ($item->cart_id !== $cart->id) {
            abort(403);
        }

        $item->delete();

        // Mettre à jour les totaux
        $cart->updateTotals();

        return redirect()->back()
            ->with('success', 'Produit retiré du panier.');
    }

    public function clear()
    {
        $cart = Cart::getOrCreateCart();
        $cart->items()->delete();
        $cart->updateTotals();

        return redirect()->route('cart.index')
            ->with('success', 'Panier vidé.');
    }

    public function getCartCount()
    {
        try {
            $cart = Cart::getOrCreateCart();

            return response()->json([
                'success' => true,
                'count' => $cart->item_count,
                'total' => $cart->formatted_total
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0,
                'total' => '0 FCFA'
            ], 500);
        }
    }

    /**
     * Fusionner le panier de session avec le panier utilisateur (appelé lors de la connexion)
     */
    public function mergeSessionCart()
    {
        if (!auth()->check()) {
            return response()->json(['success' => false], 401);
        }

        try {
            $sessionId = session()->getId();
            Cart::mergeSessionCartToUser(auth()->id(), $sessionId);

            $cart = Cart::getOrCreateCart();

            return response()->json([
                'success' => true,
                'cart_count' => $cart->item_count,
                'cart_total' => $cart->formatted_total
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la fusion des paniers.'
            ], 500);
        }
    }

    /**
     * Valider la disponibilité d'un produit avant ajout
     */
    private function validateProductAvailability(Product $product, int $quantity, ?int $currentCartQuantity = 0): array
    {
        if (!$product->is_available) {
            return ['valid' => false, 'message' => 'Ce produit n\'est pas disponible actuellement.'];
        }

        // Pour les produits sur commande, ne pas vérifier le stock
        // Tous les produits sont sur commande selon les spécifications
        return ['valid' => true];
    }
}
