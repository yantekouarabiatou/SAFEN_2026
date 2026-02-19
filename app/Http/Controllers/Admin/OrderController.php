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
        if ($request->ajax()) {
            $query = GuestOrder::query()->with('user'); // Chargez la relation user si nécessaire

            // Filtrage par statut via l'URL (ex: ?status=pending)
            if ($request->has('status') && !empty($request->status)) {
                $query->where('order_status', $request->status);
            }

            return DataTables::of($query)
                ->addColumn('order_number', function ($order) {
                    return '<a href="' . route('admin.orders.show', $order->id) . '">' . $order->order_number . '</a>';
                })
                ->addColumn('customer', function ($order) {
                    if ($order->user_id) {
                        return $order->user->name ?? 'Utilisateur #' . $order->user_id;
                    }
                    return $order->guest_name ?? 'Anonyme';
                })
                ->addColumn('total', function ($order) {
                    return $order->formatted_total;
                })
                ->addColumn('order_status', function ($order) {
                    return $this->getStatusBadge($order->order_status);
                })
                ->addColumn('payment_status', function ($order) {
                    return $this->getPaymentStatusBadge($order->payment_status);
                })
                ->addColumn('created_at', function ($order) {
                    return $order->created_at->format('d/m/Y H:i');
                })
                ->addColumn('action', function ($order) {
                    $buttons = '';
                    if ($order->order_status === 'pending') {
                        $buttons .= '
            <form action="' . route('admin.orders.validate', $order->id) . '" method="POST" style="display:inline;">
                ' . csrf_field() . '
                <button type="submit" class="btn btn-sm btn-success" title="Valider" onclick="return confirm(\'Valider cette commande ?\')">
                    <i class="fas fa-check"></i>
                </button>
            </form>
            <form action="' . route('admin.orders.reject', $order->id) . '" method="POST" style="display:inline;">
                ' . csrf_field() . '
                <button type="submit" class="btn btn-sm btn-danger" title="Refuser" onclick="return confirm(\'Refuser cette commande ?\')">
                    <i class="fas fa-times"></i>
                </button>
            </form>
        ';
                    }
                    $buttons .= '
        <a href="' . route('admin.orders.show', $order->id) . '" class="btn btn-sm btn-info" title="Voir">
            <i class="fas fa-eye"></i>
        </a>
        <a href="' . route('admin.orders.edit', $order->id) . '" class="btn btn-sm btn-warning" title="Modifier">
            <i class="fas fa-edit"></i>
        </a>
    ';
                    return $buttons;
                })
                ->rawColumns(['order_number', 'order_status', 'payment_status', 'action'])
                ->make(true);
        }

        return view('admin.orders.index');
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

    // Ajouter use Illuminate\Http\Request; déjà présent

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
        // Validation
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
        $order->order_status = 'confirmed'; // ou 'processing' selon votre logique
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
