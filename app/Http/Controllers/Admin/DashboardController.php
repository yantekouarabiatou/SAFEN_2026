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
}