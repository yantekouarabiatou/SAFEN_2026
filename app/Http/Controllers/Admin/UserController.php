<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DataTables;

class UserController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles')
                ->select('users.*')
                ->latest();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('full_name', function ($user) {
                    return $user->name ?? ($user->prenom . ' ' . $user->nom);
                })
                ->addColumn('avatar', function ($user) {
                    return $user->avatar; // chemin relatif
                })
                ->addColumn('avatar_url', function ($user) {
                    return $user->avatar_url; // accesseur
                })
                ->addColumn('name', function ($user) {
                    return $user->name;
                })
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->implode(', ');
                })
                ->addColumn('status', function ($user) {
                    return $user->is_active
                        ? '<span class="badge badge-success">Actif</span>'
                        : '<span class="badge badge-danger">Inactif</span>';
                })
                ->addColumn('created_at', function ($user) {
                    return $user->created_at->format('d/m/Y H:i');
                })
                ->addColumn('action', function ($user) {
                    $edit = '<a href="' . route('admin.users.edit', $user->id) . '" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                     </a>';
                    $delete = '';
                    if (auth()->id() !== $user->id) {
                        $delete = '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $user->id . '">
                              <i class="fas fa-trash"></i>
                           </button>';
                    }
                    return $edit . ' ' . $delete;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $stats = [
            'total' => User::count(),
            'active_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'artisans' => User::whereHas('roles', fn($q) => $q->where('name', 'artisan'))->count(),
            'admins' => User::whereHas('roles', fn($q) => $q->whereIn('name', ['admin', 'super-admin']))->count(),
        ];

        return view('admin.users.index', compact('stats'));
    }


    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Assigner les rôles sélectionnés
        $user->syncRoles($request->roles);

        // Gérer l'upload de la photo de profil
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }
    public function show(User $user)
    {
        // Charger les relations nécessaires pour la vue
        $user->load([
            'roles',
            'artisan',
            'orders' => function ($q) {
                $q->latest()->limit(5); // Dernières 5 commandes
            },
            'reviews' => function ($q) {
                $q->latest()->limit(5); // Derniers 5 avis
            },
        ])->loadCount(['orders', 'favorites', 'reviews']); // Pour les compteurs rapides

        return view('admin.users.show', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Empêcher l'utilisateur de retirer son propre rôle admin
        $roles = $request->roles;
        if ($user->id === auth()->id() && !in_array('admin', $roles) && $user->hasRole('admin')) {
            // Forcer la conservation du rôle admin
            $roles[] = 'admin';
        }

        $user->syncRoles($roles);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}
