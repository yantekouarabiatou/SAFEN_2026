<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::getOrCreateCart();
        $cart->load(['items.product.images', 'items.product.artisan']);

        // Calculer le délai de production maximum
        $maxProductionTime = $cart->items->max(function($item) {
            return $item->product->production_time_days ?? 0;
        });

        return view('cart.index', compact('cart', 'maxProductionTime'));
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'nullable|integer|min:1|max:99',
            ]);

            $product = Product::findOrFail($validated['product_id']);
            $quantity = $validated['quantity'] ?? 1;

            // Vérifier la disponibilité du produit
            $availabilityCheck = $product->canBeOrdered($quantity);
            
            if (!$availabilityCheck['can_order']) {
                return response()->json([
                    'success' => false,
                    'message' => $availabilityCheck['message']
                ], 400);
            }

            $cart = Cart::getOrCreateCart();

            // Vérifier si le produit existe déjà dans le panier
            $existingItem = $cart->items()
                ->where('product_id', $product->id)
                ->first();

            if ($existingItem) {
                // Mettre à jour la quantité
                $newQuantity = $existingItem->quantity + $quantity;
                
                // Vérifier la nouvelle quantité
                $quantityCheck = $product->canBeOrdered($newQuantity);
                if (!$quantityCheck['can_order']) {
                    return response()->json([
                        'success' => false,
                        'message' => $quantityCheck['message']
                    ], 400);
                }
                
                $existingItem->update(['quantity' => $newQuantity]);
                $message = 'Quantité mise à jour dans le panier !';
            } else {
                // Créer un nouvel item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
                
                $message = 'Produit ajouté au panier !';
            }

            // Mettre à jour les totaux du panier
            $cart->updateTotals();

            // Calculer l'acompte total requis
            $cart->load(['items.product']);
            $totalDeposit = $cart->items->sum(function($item) {
                return $item->product->required_deposit_amount * $item->quantity;
            });

            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $cart->item_count,
                'cart_total' => $cart->formatted_total,
                'deposit_required' => number_format($totalDeposit, 0, ',', ' ') . ' FCFA',
                'info' => 'Produit fabriqué sur commande. Acompte de ' . $product->deposit_percentage . '% requis.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides.',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Erreur ajout au panier: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.'
            ], 500);
        }
    }

    public function update(Request $request, CartItem $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        try {
            $cart = Cart::getOrCreateCart();

            if ($item->cart_id !== $cart->id) {
                abort(403, 'Accès non autorisé');
            }

            // Vérifier la disponibilité
            $product = $item->product;
            $availabilityCheck = $product->canBeOrdered($validated['quantity']);
            
            if (!$availabilityCheck['can_order']) {
                return response()->json([
                    'success' => false,
                    'message' => $availabilityCheck['message']
                ], 400);
            }

            $item->update([
                'quantity' => $validated['quantity']
            ]);

            $cart->updateTotals();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Quantité mise à jour.',
                    'cart_count' => $cart->item_count,
                    'cart_total' => $cart->formatted_total,
                    'item_subtotal' => number_format($item->subtotal, 0, ',', ' ') . ' FCFA'
                ]);
            }

            return redirect()->back()->with('success', 'Quantité mise à jour.');

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour panier: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour.'
            ], 500);
        }
    }

    public function remove(CartItem $item)
    {
        try {
            $cart = Cart::getOrCreateCart();

            if ($item->cart_id !== $cart->id) {
                abort(403, 'Accès non autorisé');
            }

            $item->delete();
            $cart->updateTotals();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produit retiré du panier.',
                    'cart_count' => $cart->item_count,
                    'cart_total' => $cart->formatted_total
                ]);
            }

            return redirect()->back()
                ->with('success', 'Produit retiré du panier.');

        } catch (\Exception $e) {
            Log::error('Erreur suppression item panier: ' . $e->getMessage());
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression.');
        }
    }

    public function clear()
    {
        try {
            $cart = Cart::getOrCreateCart();
            $cart->items()->delete();
            $cart->updateTotals();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Panier vidé.',
                    'cart_count' => 0,
                    'cart_total' => '0 FCFA'
                ]);
            }

            return redirect()->route('cart.index')
                ->with('success', 'Panier vidé.');

        } catch (\Exception $e) {
            Log::error('Erreur vidage panier: ' . $e->getMessage());
            
            return redirect()->route('cart.index')
                ->with('error', 'Erreur lors du vidage du panier.');
        }
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
            Log::error('Erreur récupération compteur panier: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'count' => 0,
                'total' => '0 FCFA'
            ], 500);
        }
    }

    /**
     * Calculer l'acompte total requis pour le panier
     */
    public function getDepositAmount()
    {
        try {
            $cart = Cart::getOrCreateCart();
            $cart->load(['items.product']);

            $totalDeposit = $cart->items->sum(function($item) {
                return $item->product->required_deposit_amount * $item->quantity;
            });

            return response()->json([
                'success' => true,
                'deposit_amount' => $totalDeposit,
                'formatted_deposit' => number_format($totalDeposit, 0, ',', ' ') . ' FCFA',
                'cart_total' => $cart->total,
                'formatted_total' => $cart->formatted_total
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur calcul acompte: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul de l\'acompte.'
            ], 500);
        }
    }
}