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
use App\Models\GuestOrder;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Logs de debug
        Log::info('Accès au dashboard', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'name'    => $user->name,
            'roles'   => $user->getRoleNames()->toArray(),
        ]);

        Log::info('Dashboard debug', [
            'user_id' => $user->id,
            'email' => $user->email,
            'roles_array' => $user->getRoleNames()->toArray(),
            'has_super_admin' => $user->hasRole('super-admin'),
            'has_admin' => $user->hasRole('admin'),
            'has_any_admin' => $user->hasAnyRole(['admin', 'super-admin']),
        ]);

        // Statistiques communes (disponibles pour tous)
        $commonStats = $this->getCommonStats();

        // Données spécifiques au rôle (incluant listes et graphiques)
        $roleSpecificData = $this->getRoleSpecificData($user);

        // Fusion complète pour la vue
        $stats = array_merge($commonStats, $roleSpecificData);

        // Variables supplémentaires pour la vue (listes, graphiques)
        $viewData = [
            'stats'           => $stats,
            'user'            => $user,
            // Valeurs par défaut si non définies pour le rôle
            'pendingArtisans' => $roleSpecificData['pendingArtisans'] ?? collect([]),
            'pendingProducts' => $roleSpecificData['pendingProducts'] ?? collect([]),
            'unreadContacts'  => $roleSpecificData['unreadContacts'] ?? collect([]),
            'recentOrders'    => $roleSpecificData['recentOrders'] ?? collect([]),
            'registrationsChart' => $roleSpecificData['registrationsChart'] ?? collect([]),
            'salesChart'      => $roleSpecificData['salesChart'] ?? collect([]),
        ];

        return view('admin.dashboard.index', $viewData);
    }

    /**
     * Statistiques communes à tous les rôles
     */
    protected function getCommonStats(): array
    {
        return [
            'total_orders'       => GuestOrder::count() + Order::count(), // inclut les deux types si besoin
            'pending_orders'     => GuestOrder::where('order_status', 'pending')->count() + Order::where('order_status', 'pending')->count(),
            'completed_orders'   => GuestOrder::where('order_status', 'completed')->count() + Order::where('order_status', 'completed')->count(),
            'total_revenue'      => GuestOrder::where('order_status', 'completed')->sum('total_amount') + Order::where('order_status', 'completed')->sum('total_amount'),
            'unread_messages'    => Contact::where('read', false)->count(),
            'total_reviews'      => Review::count(),
        ];
    }

    /**
     * Données spécifiques selon le rôle
     */
    protected function getRoleSpecificData(User $user): array
    {
        $data = [];

        if ($user->hasRole('super-admin')) {
            $data = $this->getSuperAdminStats();
        } elseif ($user->hasRole('admin')) {
            $data = $this->getAdminStats();
        } elseif ($user->hasRole('artisan')) {
            $data = $this->getArtisanStats($user);
        } elseif ($user->hasRole('vendor')) {
            $data = $this->getVendorStats($user);
        } else {
            $data = $this->getClientStats($user);
        }

        return $data;
    }

    protected function getSuperAdminStats(): array
    {
        return [
            'total_users'        => User::count(),
            'new_users_today'    => User::whereDate('created_at', today())->count(),
            'new_users_week'     => User::where('created_at', '>=', now()->subDays(7))->count(),
            'total_artisans'     => Artisan::count(),
            'artisans_pending'   => Artisan::where('status', 'pending')->count(),
            'artisans_approved'  => Artisan::where('status', 'approved')->count(),
            'total_products'     => Product::count(),
            'products_pending'   => Product::where('status', 'pending')->count(),
            'total_vendors'      => Vendor::count(),
            'total_events'       => CulturalEvent::count(),
            'pending_quotes'     => Quote::where('status', 'pending')->count(),

            // Listes pour affichage
            'pendingArtisans'    => Artisan::where('status', 'pending')->with(['user'])->latest()->take(8)->get(),
            'pendingProducts'    => Product::where('status', 'pending')->with(['artisan'])->latest()->take(8)->get(),
            'unreadContacts'     => Contact::where('read', false)->latest()->take(8)->get(),
            'recentOrders'       => Order::with('user')->latest()->take(10)->get(),

            // Graphiques
            'registrationsChart' => $this->getRegistrationsChart(),
            'salesChart'         => $this->getSalesChart(),
        ];
    }

    protected function getAdminStats(): array
    {
        return [
            'total_users'       => User::count(),
            'total_artisans'    => Artisan::where('status', 'approved')->count(),
            'total_products'    => Product::where('status', 'active')->count(),
            'total_vendors'     => Vendor::where('status', 'active')->count(),
            'total_dishes'      => Dish::where('status', 'active')->count(),
            'total_events'      => CulturalEvent::count(),
            'artisans_pending'  => Artisan::where('status', 'pending')->count(),
            'products_pending'  => Product::where('status', 'pending')->count(),

            'recentOrders'      => Order::with('user')->latest()->take(10)->get(),
            'newUsers'          => User::latest()->take(8)->get(),
            'popularProducts'   => Product::withCount('orderItems')->orderBy('views', 'desc')->take(8)->get(),
            'salesChart'        => $this->getSalesChart(),
        ];
    }

    // ────────────────────────────────────────────────
    // Artisan
    // ────────────────────────────────────────────────
    protected function getArtisanStats(User $user): array
    {
        $artisan = $user->artisan;

        if (!$artisan) {
            return [
                'no_profile' => true,
                'message'    => 'Veuillez créer votre profil artisan pour accéder à votre espace.',
            ];
        }

        return [
            'products_count'    => $artisan->products()->count(),
            'views'             => $artisan->views ?? 0,
            'rating'            => $artisan->reviews()->avg('rating') ?? 0,
            'orders_count'      => Order::whereHas('items.product', fn($q) => $q->where('artisan_id', $artisan->id))->count(),
            'total_sales'       => Order::where('order_status', 'completed')
                ->whereHas('items.product', fn($q) => $q->where('artisan_id', $artisan->id))
                ->count(),
            'total_revenue'     => Order::where('order_status', 'completed')
                ->whereHas('items.product', fn($q) => $q->where('artisan_id', $artisan->id))
                ->sum('total_amount'),

            'recentProducts'    => $artisan->products()->with('images')->latest()->take(6)->get(),
            'popularProducts'   => $artisan->products()->with('images')->withCount('orderItems')->orderBy('views', 'desc')->take(6)->get(),
        ];
    }

    // ────────────────────────────────────────────────
    // Vendeur (Restaurant)
    // ────────────────────────────────────────────────
    protected function getVendorStats(User $user): array
    {
        $vendor = $user->vendor;

        if (!$vendor) {
            return [
                'no_profile' => true,
                'message'    => 'Veuillez créer votre profil vendeur pour accéder à votre espace.',
            ];
        }

        return [
            'dishes_count'      => $vendor->dishes()->count(),
            'orders_count'      => Order::whereHas('items.dish', fn($q) => $q->where('vendor_id', $vendor->id))->count(),
            'rating'            => $vendor->reviews()->avg('rating') ?? 0,
            'total_revenue'     => Order::where('order_status', 'completed')
                ->whereHas('items.dish', fn($q) => $q->where('vendor_id', $vendor->id))
                ->sum('total_amount'),
            'monthly_revenue'   => Order::where('order_status', 'completed')
                ->whereHas('items.dish', fn($q) => $q->where('vendor_id', $vendor->id))
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),

            'recentDishes'      => $vendor->dishes()->latest()->take(6)->get(),
        ];
    }

    // ────────────────────────────────────────────────
    // Client (utilisateur normal)
    // ────────────────────────────────────────────────
    protected function getClientStats(User $user): array
    {
        $recentViewsIds = session()->get('recent_views', []);

        return [
            'favorites_count'   => $user->favorites()->count(),
            'orders_count'      => $user->orders()->count(),
            'reviews_count'     => $user->reviews()->count(),
            'recentViews'       => Product::whereIn('id', $recentViewsIds)->with('images')->take(6)->get(),
            'favorites'         => $user->favorites()->with('favoritable')->latest()->take(6)->get(),
        ];
    }

    // ────────────────────────────────────────────────
    // Helpers pour les graphiques
    // ────────────────────────────────────────────────
    protected function getRegistrationsChart(int $days = 30)
    {
        $data = User::where('created_at', '>=', now()->subDays($days))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->fillMissingDates($data, $days, 'count');
    }

    protected function getSalesChart(int $days = 30)
    {
        $data = Order::where('order_status', 'completed')
            ->where('created_at', '>=', now()->subDays($days))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->fillMissingDates($data, $days);
    }

    /**
     * Remplit les dates manquantes dans les graphiques
     */
    protected function fillMissingDates($collection, int $days = 30, string $valueField = 'total'): \Illuminate\Support\Collection
    {
        $start = now()->subDays($days)->format('Y-m-d');
        $dates = collect(range(0, $days - 1))->map(fn($i) => now()->subDays($days - 1 - $i)->format('Y-m-d'));

        $dataByDate = $collection->keyBy('date');

        return $dates->map(function ($date) use ($dataByDate, $valueField) {
            $item = $dataByDate->get($date, (object) ['date' => $date]);
            $item->$valueField = $item->$valueField ?? 0;
            if ($valueField === 'total') {
                $item->count = $item->count ?? 0;
            }
            return $item;
        });
    }

    protected function getArtisanStats(User $user): array
    {
        $data = $this->getArtisanStatsOriginal($user); // ton code original

        // Ajouter des valeurs par défaut pour éviter undefined dans la vue
        return array_merge([
            'pendingArtisans' => collect([]),
            'pendingProducts' => collect([]),
            'unreadContacts'  => collect([]),
            'recentOrders'    => collect([]),
            'registrationsChart' => collect([]),
            'salesChart'      => collect([]),
        ], $data);
    }

    // Même chose pour vendor et client si besoin
}
