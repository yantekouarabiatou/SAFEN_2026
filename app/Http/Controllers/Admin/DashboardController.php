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
use App\Models\CulturalEvent;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            if (!$user->hasAnyRole(['super-admin', 'admin'])) {
                abort(403, 'Unauthorized access.');
            }

            return $next($request);
        });
    }
    /**
     * Dashboard principal - Redirige selon le rôle
     */
    public function index()
    {
        $user = Auth::user();

        // Debug: Log pour vérifier les rôles
        Log::info('Dashboard access', [
            'user_id' => $user->id,
            'email' => $user->email,
            'roles' => $user->getRoleNames()->toArray()
        ]);
        \Log::info('Dashboard access', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_roles' => $user->getRoleNames()->toArray(),
            'has_role_super_admin' => $user->hasRole('super-admin'),
            'has_role_admin' => $user->hasRole('admin'),
            'is_admin' => $user->isAdmin(),
        ]);

        // Continuer avec la logique normale...
        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            return $this->adminDashboard();
        }


        if ($user->hasRole('super-admin')) {
            return $this->superAdminDashboard();
        } elseif ($user->hasRole('admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('artisan')) {
            return $this->artisanDashboard();
        } elseif ($user->hasRole('vendor')) {
            return $this->vendorDashboard();
        } else {
            return $this->clientDashboard();
        }
    }

    /**
     * Dashboard Super-Administrateur
     */
    protected function superAdminDashboard()
    {
        \Log::info('Loading super-admin dashboard');

        // Statistiques complètes
        $stats = [
            // Utilisateurs
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_week' => User::where('created_at', '>=', now()->subDays(7))->count(),

            // Artisans
            'total_artisans' => Artisan::count(),
            'artisans_pending' => Artisan::where('status', 'pending')->count(),
            'artisans_approved' => Artisan::where('status', 'approved')->count(),
            'artisans_rejected' => Artisan::where('status', 'rejected')->count(),

            // Produits
            'total_products' => Product::count(),
            'products_pending' => Product::where('status', 'pending')->count(),
            'products_active' => Product::where('status', 'active')->count(),

            // Vendeurs
            'total_vendors' => Vendor::count(),
            'vendors_active' => Vendor::where('status', 'active')->count(),

            // Commandes
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'processing_orders' => Order::where('order_status', 'processing')->count(),
            'completed_orders' => Order::where('order_status', 'completed')->count(),
            'cancelled_orders' => Order::where('order_status', 'cancelled')->count(),

            // Revenus
            'total_revenue' => Order::where('order_status', 'completed')->sum('total_amount'),
            'today_revenue' => Order::where('order_status', 'completed')
                ->whereDate('created_at', today())
                ->sum('total_amount'),
            'monthly_revenue' => Order::where('order_status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),

            // Événements
            'total_events' => CulturalEvent::count(),
            'upcoming_events' => CulturalEvent::where('date', '>=', now())->count(),

            // Messages
            'unread_messages' => Contact::where('read', false)->count(),

            // Avis
            'total_reviews' => Review::count(),

            // Devis
            'pending_quotes' => Quote::where('status', 'pending')->count(),
        ];

        // Dernières alertes
        $recentOrders = Order::with(['user'])
            ->latest()
            ->take(10)
            ->get();

        // Artisans en attente d'approbation
        $pendingArtisans = Artisan::where('status', 'pending')
            ->with(['user', 'photos'])
            ->latest()
            ->take(10)
            ->get();

        // Produits en attente
        $pendingProducts = Product::where('status', 'pending')
            ->with(['images', 'artisan'])
            ->latest()
            ->take(10)
            ->get();

        // Messages non lus
        $unreadContacts = Contact::where('read', false)
            ->latest()
            ->take(10)
            ->get();

        // Commandes en attente
        $pendingOrders = Order::where('order_status', 'pending')
            ->with(['user'])
            ->latest()
            ->take(10)
            ->get();

        // Graphique des inscriptions (30 derniers jours)
        $registrationsChart = User::where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Remplir les jours manquants
        $registrationsChart = $this->fillMissingDates($registrationsChart, 30, 'count');

        // Graphique des ventes (30 derniers jours)
        $salesChart = Order::where('order_status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesChart = $this->fillMissingDates($salesChart, 30);

        return view('admin.dashboard.super-admin', compact(
            'stats',
            'recentOrders',
            'pendingArtisans',
            'pendingProducts',
            'unreadContacts',
            'pendingOrders',
            'registrationsChart',
            'salesChart'
        ));
    }

    /**
     * Dashboard Administrateur
     */
    protected function adminDashboard()
    {
        \Log::info('Loading admin dashboard');

        // Statistiques principales
        $stats = [
            'total_users' => User::count(),
            'total_artisans' => Artisan::where('status', 'approved')->count(),
            'total_products' => Product::where('status', 'active')->count(),
            'total_dishes' => Dish::where('status', 'active')->count(),
            'total_vendors' => Vendor::where('status', 'active')->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'total_revenue' => Order::where('order_status', 'completed')->sum('total_amount'),
            'total_events' => CulturalEvent::count(),
            'artisans_pending' => Artisan::where('status', 'pending')->count(),
            'products_pending' => Product::where('status', 'pending')->count(),
            'unread_messages' => Contact::where('read', false)->count(),
        ];

        // Commandes récentes (10 dernières)
        $recentOrders = Order::with(['user'])
            ->latest()
            ->take(10)
            ->get();

        // Nouveaux utilisateurs (10 derniers)
        $newUsers = User::with('roles')
            ->latest()
            ->take(10)
            ->get();

        // Produits populaires (top 10)
        $popularProducts = Product::with(['images', 'artisan'])
            ->withCount('orderItems')
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();

        // Graphique des ventes (30 derniers jours)
        $salesChart = Order::where('order_status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Remplir les jours manquants avec des zéros
        $salesChart = $this->fillMissingDates($salesChart, 30);

        return view('admin.dashboard.index', compact(
            'stats',
            'recentOrders',
            'newUsers',
            'popularProducts',
            'salesChart'
        ));
    }

    /**
     * Dashboard Artisan
     */
    protected function artisanDashboard()
    {
        $user = Auth::user();
        $artisan = $user->artisan;

        if (!$artisan) {
            return redirect()->route('artisans.create')
                ->with('info', 'Veuillez créer votre profil artisan pour accéder au dashboard.');
        }

        // Statistiques de l'artisan
        $stats = [
            'products' => $artisan->products()->count(),
            'views' => $artisan->views ?? 0,
            'rating' => $artisan->reviews()->avg('rating') ?? 0,
            'orders' => Order::whereHas('items', function ($query) use ($artisan) {
                $query->whereHas('product', function ($q) use ($artisan) {
                    $q->where('artisan_id', $artisan->id);
                });
            })->count(),
            'total_sales' => Order::where('order_status', 'completed')
                ->whereHas('items', function ($query) use ($artisan) {
                    $query->whereHas('product', function ($q) use ($artisan) {
                        $q->where('artisan_id', $artisan->id);
                    });
                })->count(),
            'total_revenue' => Order::where('order_status', 'completed')
                ->whereHas('items', function ($query) use ($artisan) {
                    $query->whereHas('product', function ($q) use ($artisan) {
                        $q->where('artisan_id', $artisan->id);
                    });
                })->sum('total_amount'),
        ];

        // Produits récents de l'artisan
        $recentProducts = $artisan->products()
            ->with('images')
            ->withCount('orderItems')
            ->latest()
            ->take(5)
            ->get();

        // Produits populaires de l'artisan
        $popularProducts = $artisan->products()
            ->with('images')
            ->withCount('orderItems')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.artisan', compact(
            'stats',
            'recentProducts',
            'popularProducts'
        ));
    }

    /**
     * Dashboard Vendeur/Restaurant
     */
    protected function vendorDashboard()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return redirect()->route('vendors.create')
                ->with('info', 'Veuillez créer votre profil vendeur pour accéder au dashboard.');
        }

        // Statistiques du vendeur
        $stats = [
            'dishes' => $vendor->dishes()->count(),
            'orders' => Order::whereHas('items', function ($query) use ($vendor) {
                $query->whereHas('dish', function ($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                });
            })->count(),
            'rating' => $vendor->reviews()->avg('rating') ?? 0,
            'revenue' => Order::where('order_status', 'completed')
                ->whereHas('items', function ($query) use ($vendor) {
                    $query->whereHas('dish', function ($q) use ($vendor) {
                        $q->where('vendor_id', $vendor->id);
                    });
                })->sum('total_amount'),
            'monthly_orders' => Order::whereHas('items', function ($query) use ($vendor) {
                $query->whereHas('dish', function ($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                });
            })->whereMonth('created_at', now()->month)->count(),
            'monthly_revenue' => Order::where('order_status', 'completed')
                ->whereHas('items', function ($query) use ($vendor) {
                    $query->whereHas('dish', function ($q) use ($vendor) {
                        $q->where('vendor_id', $vendor->id);
                    });
                })
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
        ];

        // Plats récents
        $recentDishes = $vendor->dishes()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.vendor', compact(
            'stats',
            'recentDishes'
        ));
    }

    /**
     * Dashboard Client
     */
    protected function clientDashboard()
    {
        $user = Auth::user();

        // Récupérer les favoris
        $favorites = $user->favorites()
            ->with('favoritable')
            ->latest()
            ->get();

        // Produits récemment consultés (simulation via session)
        $recentViewsIds = session()->get('recent_views', []);
        $recentViews = Product::with('images')
            ->whereIn('id', $recentViewsIds)
            ->take(6)
            ->get();

        return view('admin.dashboard.client', compact(
            'favorites',
            'recentViews'
        ));
    }

    /**
     * Remplir les dates manquantes dans le graphique
     */
    protected function fillMissingDates($data, $days = 30, $valueField = 'total')
    {
        $filledData = collect();
        $startDate = now()->subDays($days);

        $dataByDate = $data->keyBy('date');

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');

            if (isset($dataByDate[$date])) {
                $filledData->push($dataByDate[$date]);
            } else {
                $item = ['date' => $date];
                $item[$valueField] = 0;
                if ($valueField === 'total') {
                    $item['count'] = 0;
                }
                $filledData->push((object)$item);
            }
        }

        return $filledData;
    }

    /**
     * Page Analytics (Admin seulement)
     */
    public function analytics()
    {
        $this->authorize('viewAnalytics', User::class);

        // Statistiques détaillées
        $analytics = [
            // Utilisateurs
            'users_total' => User::count(),
            'users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'users_active' => User::where('last_login_at', '>=', now()->subDays(30))->count(),

            // Artisans
            'artisans_approved' => Artisan::where('status', 'approved')->count(),
            'artisans_pending' => Artisan::where('status', 'pending')->count(),

            // Commandes
            'orders_total' => Order::count(),
            'orders_pending' => Order::where('order_status', 'pending')->count(),
            'orders_completed' => Order::where('order_status', 'completed')->count(),

            // Revenus
            'revenue_total' => Order::where('order_status', 'completed')->sum('total_amount'),
            'revenue_this_month' => Order::where('order_status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),

            // Produits
            'products_total' => Product::count(),
            'products_out_of_stock' => Product::where('stock_status', 'out_of_stock')->count(),
        ];

        // Graphiques
        $salesByMonth = Order::where('order_status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();

        $topArtisans = Artisan::withCount(['products' => function ($query) {
            $query->withCount('orderItems');
        }])
            ->orderBy('products_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.analytics', compact(
            'analytics',
            'salesByMonth',
            'topProducts',
            'topArtisans'
        ));
    }
}
