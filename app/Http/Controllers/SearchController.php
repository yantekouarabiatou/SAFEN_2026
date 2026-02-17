<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artisan;
use App\Models\Dish;
use App\Models\Product;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->q;
        $type = $request->type ?? 'all';

        $results = [];
        $total = 0;

        if (!$query) {
            return view('search.index', compact('results', 'total', 'query', 'type'));
        }

        // Recherche dans tous les modèles
        if ($type === 'all' || $type === 'artisans') {
            $artisans = Artisan::with(['user', 'photos'])
                ->where('visible', true)
                ->where(function ($q) use ($query) {
                    $q->where('business_name', 'like', "%$query%")
                        ->orWhere('craft', 'like', "%$query%")
                        ->orWhere('city', 'like', "%$query%")
                        ->orWhere('bio', 'like', "%$query%");
                })
                ->get();
            $results['artisans'] = $artisans;
            $total += $artisans->count();
        }

        if ($type === 'all' || $type === 'products') {
            $products = Product::with(['images', 'artisan.user'])
                ->where('stock_status', '!=', 'out_of_stock')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%")
                        ->orWhere('name_local', 'like', "%$query%")
                        ->orWhere('description', 'like', "%$query%")
                        ->orWhere('description_cultural', 'like', "%$query%");
                })
                ->get();
            $results['products'] = $products;
            $total += $products->count();
        }

        if ($type === 'all' || $type === 'dishes') {
            $dishes = Dish::with('images')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%")
                        ->orWhere('name_local', 'like', "%$query%")
                        ->orWhere('cultural_description', 'like', "%$query%");
                })
                ->get();
            $results['dishes'] = $dishes;
            $total += $dishes->count();
        }

        return view('search.index', compact('results', 'total', 'query', 'type'));
    }

    public function advanced(Request $request)
    {
        $filters = $request->only([
            'category',
            'min_price',
            'max_price',
            'city',
            'rating',
            'materials',
            'ethnic_origin'
        ]);

        $query = Product::with(['images', 'artisan.user'])
            ->where('stock_status', '!=', 'out_of_stock');

        // Appliquer les filtres
        foreach ($filters as $key => $value) {
            if ($value) {
                switch ($key) {
                    case 'category':
                        $query->where('category', $value);
                        break;
                    case 'min_price':
                        $query->where('price', '>=', $value);
                        break;
                    case 'max_price':
                        $query->where('price', '<=', $value);
                        break;
                    case 'city':
                        $query->whereHas('artisan', function ($q) use ($value) {
                            $q->where('city', $value);
                        });
                        break;
                    case 'rating':
                        $query->whereHas('artisan', function ($q) use ($value) {
                            $q->where('rating_avg', '>=', $value);
                        });
                        break;
                    case 'materials':
                        $query->whereJsonContains('materials', $value);
                        break;
                    case 'ethnic_origin':
                        $query->where('ethnic_origin', $value);
                        break;
                }
            }
        }

        $products = $query->paginate(24);

        return view('search.advanced', compact('products', 'filters'));
    }

    // Dans SearchController.php
    public function ajax(Request $request)
    {
        $query = $request->get('q');
        $results = [];

        // Recherche dans les artisans
        $artisans = Artisan::where('business_name', 'like', "%{$query}%")
            ->orWhere('craft', 'like', "%{$query}%")
            ->orWhere('city', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        // Recherche dans les produits
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        $html = view('partials.search-results', compact('artisans', 'products'))->render();

        return response()->json(['html' => $html]);
    }
}
