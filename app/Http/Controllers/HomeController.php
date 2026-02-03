<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Product;
use App\Models\Dish;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['artisan.user', 'primaryImage'])
            ->where('featured', true)
            ->where('stock_status', 'in_stock')
            ->limit(8)
            ->get();

        $featuredArtisans = Artisan::with(['user', 'photos'])
            ->where('featured', true)
            ->where('verified', true)
            ->orderBy('rating_avg', 'desc')
            ->limit(4)
            ->get();

        return view('home', compact('featuredProducts', 'featuredArtisans'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        // Recherche dans les artisans
        $artisans = Artisan::with('user')
            ->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhere('craft', 'like', "%{$query}%")
            ->orWhere('city', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        // Recherche dans les produits
        $products = Product::with(['artisan.user', 'primaryImage'])
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        // Recherche dans les plats
        $dishes = Dish::where('name', 'like', "%{$query}%")
            ->orWhere('name_local', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return view('pages.search', compact('query', 'artisans', 'products', 'dishes'));
    }

    public function dashboard()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return view('admin.dashboard');
        } elseif ($user->hasRole('artisan')) {
            return view('artisan.dashboard');
        }

        return view('client.dashboard');
    }

    public function culture()
    {
        return view('pages.culture');
    }
}
