<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'quantity' => 'required|integer|min:1',
            'options' => 'nullable|array'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Vérifier le stock
        if ($product->stock_status === 'out_of_stock') {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit est en rupture de stock.'
            ], 400);
        }

        if ($product->stock_status === 'low_stock' && $request->quantity > 2) {
            return response()->json([
                'success' => false,
                'message' => 'Stock limité. Maximum 2 unités.'
            ], 400);
        }

        $cart = Cart::getOrCreateCart();

        // Vérifier si le produit est déjà dans le panier
        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            // Mettre à jour la quantité
            $newQuantity = $existingItem->quantity + $request->quantity;

            // Vérifier à nouveau le stock
            if ($product->stock_status === 'low_stock' && $newQuantity > 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock limité. Maximum 2 unités au total.'
                ], 400);
            }

            $existingItem->update([
                'quantity' => $newQuantity
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

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté au panier.',
            'cart_count' => $cart->item_count,
            'cart_total' => $cart->formatted_total
        ]);
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
        $cart = Cart::getOrCreateCart();

        return response()->json([
            'count' => $cart->item_count,
            'total' => $cart->formatted_total
        ]);
    }
}
