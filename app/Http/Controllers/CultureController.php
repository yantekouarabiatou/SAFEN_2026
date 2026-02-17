<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Artisan;
use App\Models\CulturalEvent;
use App\Models\Dish;

class CultureController extends Controller
{
    public function index()
    {
        // Statistiques
        $stats = [
            'artisans' => Artisan::count(),
            'products' => Product::count(),
            'dishes' => Dish::count(),
            'regions' => Product::distinct('ethnic_origin')->count()
        ];

        // Événements à venir
        $upcomingEvents = CulturalEvent::upcoming()->limit(3)->get();

        // Produits vedettes
        $featuredProducts = Product::with(['images', 'artisan.user'])
            ->where('featured', true)
            ->where('stock_status', '!=', 'out_of_stock')
            ->limit(8)
            ->get();

        // Artisans vedettes
        $featuredArtisans = Artisan::with(['user', 'photos'])
            ->where('featured', true)
            ->where('visible', true)
            ->limit(6)
            ->get();

        // Plats populaires
        $popularDishes = Dish::with('images')
            ->orderBy('views', 'desc')
            ->limit(6)
            ->get();

        // Faits culturels
        $culturalFacts = [
            [
                'title' => 'Le masque Guèlèdè',
                'description' => 'Patrimoine immatériel de l\'UNESCO, ces masques célèbrent les femmes et la maternité.',
                'icon' => '🎭',
                'category' => 'Art'
            ],
            [
                'title' => 'La Route de l\'Esclave',
                'description' => 'Ouidah, port historique avec la Porte du Non-Retour, symbole de mémoire.',
                'icon' => '🛤️',
                'category' => 'Histoire'
            ],
            [
                'title' => 'Les Tata Somba',
                'description' => 'Maisons-forteresses en terre du peuple Bétammaribé, classées UNESCO.',
                'icon' => '🏰',
                'category' => 'Architecture'
            ],
            [
                'title' => 'Berceau du Vaudou',
                'description' => 'Le Bénin est considéré comme le berceau mondial de la religion vaudou.',
                'icon' => '🕯️',
                'category' => 'Spiritualité'
            ]
        ];

        return view('culture.index', compact(
            'stats',
            'culturalFacts',
            'featuredProducts',
            'featuredArtisans',
            'popularDishes',
            'upcomingEvents'
        ));
    }

    public function traditions()
    {
        return view('culture.traditions');
    }

    public function history()
    {
        return view('culture.history');
    }

    public function festivals()
    {
        return view('culture.festivals');
    }
}
