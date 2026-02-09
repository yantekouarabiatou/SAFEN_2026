<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::getOrCreateCart();
        $cart->load(['items.product.images', 'items.product.artisan']);

        // Calculer le délai de production maximum
        $maxProductionTime = $cart->items->max(function ($item) {
            return $item->product->production_time_days ?? 0;
        });

        return view('cart.index', compact('cart', 'maxProductionTime'));
    }

    public function add(Request $request)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez vous connecter pour ajouter au panier.'
            ], 401);
        }

        try {
            Log::info('Cart add request', $request->all());

            // Validation simple
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1);

            if (!$productId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID produit manquant.'
                ], 400);
            }

            // Vérifier si le produit existe
            $product = Product::find($productId);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non trouvé.'
                ], 404);
            }

            // Récupérer le panier
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => session()->getId(), 'total' => 0, 'item_count' => 0]
            );

            // Vérifier si l'article existe déjà
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                // Mettre à jour la quantité
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                // Créer un nouvel article
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->price
                ]);
            }

            // Mettre à jour le total du panier
            $cart->updateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier.',
                'cart_count' => $cart->item_count,
                'cart_total' => number_format($cart->total, 0, ',', ' ') . ' FCFA'
            ]);
        } catch (\Exception $e) {
            Log::error('Cart add error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkProduct(Request $request, $productId)
    {
        $cart = Cart::getOrCreateCart();

        $exists = $cart->items()
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'in_cart' => $exists
        ]);
    }

    public function updateTotals()
    {
        try {
            $cart = $this;
            $total = 0;
            $itemCount = 0;

            foreach ($cart->items as $item) {
                $total += $item->price * $item->quantity;
                $itemCount += $item->quantity;
            }

            $cart->total = $total;
            $cart->item_count = $itemCount;
            $cart->save();
        } catch (\Exception $e) {
            Log::error('Error updating cart totals: ' . $e->getMessage());
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

            // --------------------------------------
            // Commenter ou supprimer cette partie
            // $product = $item->product;
            // $availabilityCheck = $product->canBeOrdered($validated['quantity']);
            //
            // if (!$availabilityCheck['can_order']) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => $availabilityCheck['message']
            //     ], 400);
            // }
            // --------------------------------------

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

            $totalDeposit = $cart->items->sum(function ($item) {
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
