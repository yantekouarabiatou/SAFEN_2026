<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with(['roles', 'poste'])
                ->select('users.*') // Important pour éviter conflits avec with()
                ->latest();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('full_name', function ($user) {
                    return $user->name ?? ($user->prenom . ' ' . $user->nom);
                })
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->implode(', ');
                })
                ->addColumn('status', function ($user) {
                    return $user->is_active
                        ? '<span class="badge bg-success">Actif</span>'
                        : '<span class="badge bg-danger">Inactif</span>';
                })
                ->addColumn('created_at', function ($user) {
                    return $user->created_at->format('d/m/Y H:i');
                })
                ->addColumn('action', function ($user) {
                    $edit = '<a href="' . route('admin.users.edit', $user->id) . '" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                         </a>';

                    $delete = '';
                    if (auth()->id() !== $user->id) {
                        $delete = '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $user->id . '">
                                  <i class="bi bi-trash"></i>
                               </button>';
                    }

                    return $edit . ' ' . $delete;
                })
                ->rawColumns(['status', 'action', 'roles'])
                ->make(true);
        }

        // Statistiques pour les cartes du haut
        $stats = [
            'total' => User::count(),
            'active_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'artisans' => User::whereHas('roles', fn($q) => $q->where('name', 'artisan'))->count(),
            'admins' => User::whereHas('roles', fn($q) => $q->whereIn('name', ['admin', 'super-admin']))->count(),
        ];

        $roles = Role::orderBy('name')->get();

        return view('pages.users.index', compact('stats', 'roles'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,artisan,vendor,user',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,artisan,vendor,user',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}
