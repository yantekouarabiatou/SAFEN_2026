<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Contact;
use App\Models\Dish;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Page Rapport & Statistiques
     */
    public function analytics()
    {
        // Statistiques principales
        $stats = [
            'total_users' => User::count(),
            'total_artisans' => Artisan::count(),
            'total_products' => Product::count(),
            'total_dishes' => Dish::count(),
            'total_vendors' => Vendor::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'new_contacts' => Contact::where('status', 'new')->count(),
            'total_reviews' => Review::count(),
        ];

        // Graphique des ventes (30 derniers jours)
        $salesChart = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Inscriptions (30 derniers jours)
        $usersChart = User::where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 5 produits par ventes (somme des quantités dans order_items)
        $popularProducts = Product::select(
                'products.id',
                'products.name',
                'products.category',
                'products.price',
                DB::raw('COALESCE(SUM(order_items.quantity),0) as sales'),
                DB::raw('COALESCE(SUM(order_items.quantity * order_items.unit_price),0) as revenue')
            )
            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name', 'products.category', 'products.price')
            ->orderByDesc('sales')
            ->take(5)
            ->get();

        // Top 5 artisans par nombre d'articles vendus (via order_items.artisan_id)
        $topArtisans = Artisan::select(
                'artisans.id',
                'artisans.user_id',
                'artisans.business_name',
                'artisans.phone',
                'artisans.created_at',
                DB::raw('COALESCE(SUM(order_items.quantity),0) as orders_count')
            )
            ->leftJoin('order_items', 'order_items.artisan_id', '=', 'artisans.id')
            ->groupBy('artisans.id', 'artisans.user_id', 'artisans.business_name', 'artisans.phone', 'artisans.created_at')
            ->with('user')
            ->orderByDesc('orders_count')
            ->take(5)
            ->get();

        // Labels des métriques affichées
        $productMetricLabel = 'Ventes (quantité)';
        $artisanMetricLabel = 'Articles vendus';

        return view('admin.dashboard.analytics', compact(
            'stats',
            'salesChart',
            'usersChart',
            'popularProducts',
            'topArtisans'
        ));
    }

    public function index()
    {
        // Statistiques principales
        $stats = [
            'total_users' => User::count(),
            'total_artisans' => Artisan::count(),
            'total_products' => Product::count(),
            'total_dishes' => Dish::count(),
            'total_vendors' => Vendor::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            // 'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            // Option B - Si vous avez une colonne 'amount'
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'new_contacts' => Contact::where('status', 'new')->count(),
            'total_reviews' => Review::count(),
        ];

        // Commandes récentes
        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Nouveaux utilisateurs
        $newUsers = User::latest()
            ->take(5)
            ->get();

        // Produits populaires
        // ✅ NOUVEAU CODE - Utilise order_count
        $popularProducts = Product::orderBy('order_count', 'desc')
            ->take(5)
            ->get();
        // Graphique des ventes (7 derniers jours)
        $salesChart = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Inscriptions (7 derniers jours)
        $usersChart = User::where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'recentOrders',
            'newUsers',
            'popularProducts',
            'salesChart',
            'usersChart'
        ));
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}view: 