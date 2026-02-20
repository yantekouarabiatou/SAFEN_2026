<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Affiche la liste des commandes.
     */
    public function index(Request $request)
    {
        // Si c'est une requête AJAX pour DataTables
        if ($request->ajax()) {
            return $this->getOrdersData($request);
        }
        
        // Sinon, afficher la vue normale avec pagination
        $query = GuestOrder::query()->with('user');
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('order_status', $request->status);
        }
        
        $orders = $query->orderByDesc('created_at')->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * Récupère les données pour DataTables
     */
    private function getOrdersData(Request $request)
    {
        $query = GuestOrder::with('user');
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('order_status', $request->status);
        }
        
        return DataTables::of($query)
            ->addColumn('user_name', function ($order) {
                return $order->user->name ?? 'Client invité';
            })
            ->addColumn('order_number', function ($order) {
                return '<a href="' . route('admin.orders.show', $order->id) . '">#' . $order->order_number . '</a>';
            })
            ->addColumn('order_status', function ($order) {
                return $this->getStatusBadge($order->order_status);
            })
            ->addColumn('payment_status', function ($order) {
                return $this->getPaymentStatusBadge($order->payment_status);
            })
            ->addColumn('action', function ($order) {
                return '
                    <a href="' . route('admin.orders.show', $order->id) . '" class="btn btn-sm btn-info" title="Voir">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="' . route('admin.orders.edit', $order->id) . '" class="btn btn-sm btn-warning" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                ';
            })
            ->rawColumns(['order_number', 'order_status', 'payment_status', 'action'])
            ->make(true);
    }

    /**
     * Retourne le badge HTML pour le statut de commande.
     */
    private function getStatusBadge($status)
    {
        $badgeClass = match ($status) {
            'pending'    => 'badge-warning',   // jaune
            'confirmed'  => 'badge-success',   // vert
            'processing' => 'badge-success',   // vert
            'shipped'    => 'badge-success',   // vert
            'delivered'  => 'badge-success',   // vert
            'cancelled'  => 'badge-danger',    // rouge
            default      => 'badge-secondary'
        };

        $labels = [
            'pending'    => 'En attente',
            'confirmed'  => 'Confirmée',
            'processing' => 'En préparation',
            'shipped'    => 'Expédiée',
            'delivered'  => 'Livrée',
            'cancelled'  => 'Annulée',
        ];

        $label = $labels[$status] ?? $status;

        return '<span class="badge ' . $badgeClass . '">' . $label . '</span>';
    }

    /**
     * Badge pour le statut de paiement.
     */
    private function getPaymentStatusBadge($status)
    {
        $badgeClass = match ($status) {
            'pending' => 'badge-warning',
            'partial' => 'badge-info',
            'paid'    => 'badge-success',
            'failed'  => 'badge-danger',
            default   => 'badge-secondary'
        };

        $labels = [
            'pending' => 'En attente',
            'partial' => 'Acompte versé',
            'paid'    => 'Payée',
            'failed'  => 'Échouée',
        ];

        return '<span class="badge ' . $badgeClass . '">' . ($labels[$status] ?? $status) . '</span>';
    }

    public function show($id)
    {
        $order = GuestOrder::with('user')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = GuestOrder::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = GuestOrder::findOrFail($id);
        
        $validated = $request->validate([
            'order_status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,partial,paid,failed',
            'admin_notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Commande mise à jour.');
    }

    public function validateOrder($id)
    {
        $order = GuestOrder::findOrFail($id);
        $order->order_status = 'confirmed';
        $order->save();

        return redirect()->back()->with('success', 'Commande validée.');
    }

    public function rejectOrder($id)
    {
        $order = GuestOrder::findOrFail($id);
        $order->order_status = 'cancelled';
        $order->save();

        return redirect()->back()->with('success', 'Commande refusée/annulée.');
    }
}