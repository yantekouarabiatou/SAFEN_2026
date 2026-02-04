<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required_without:dish_id|exists:products,id',
            'dish_id' => 'required_without:product_id|exists:dishes,id',
        ]);

        $type = $request->product_id ? 'App\Models\Product' : 'App\Models\Dish';
        $id = $request->product_id ?? $request->dish_id;

        $favorite = Favorite::where('user_id', auth()->id())
            ->where('favoritable_type', $type)
            ->where('favoritable_id', $id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Retiré des favoris';
            $isFavorite = false;
        } else {
            Favorite::create([
                'user_id' => auth()->id(),
                'favoritable_type' => $type,
                'favoritable_id' => $id,
            ]);
            $message = 'Ajouté aux favoris';
            $isFavorite = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorite' => $isFavorite,
        ]);
    }

    public function index()
    {
        $favorites = auth()->user()->favorites()
            ->with('favoritable')
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }
}