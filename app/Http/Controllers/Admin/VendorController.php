<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::with(['user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('shop_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $vendors = $query->latest()->paginate(15);

        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'shop_name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password ?? 'password123'),
                'phone' => $request->phone,
            ]);

            $user->assignRole('vendor');

            Vendor::create([
                'user_id' => $user->id,
                'shop_name' => $request->shop_name,
                'description' => $request->description,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'opening_hours' => $request->opening_hours,
            ]);

            DB::commit();

            return redirect()->route('admin.vendors.index')->with('success', 'Vendeur créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function edit(Vendor $vendor)
    {
        $vendor->load('user');
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $vendor->user_id,
            'shop_name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $vendor->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            $vendor->update([
                'shop_name' => $request->shop_name,
                'description' => $request->description,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'opening_hours' => $request->opening_hours,
            ]);

            DB::commit();

            return redirect()->route('admin.vendors.index')->with('success', 'Vendeur mis à jour.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy(Vendor $vendor)
    {
        $user = $vendor->user;
        $vendor->delete();
        $user->delete();

        return redirect()->route('admin.vendors.index')->with('success', 'Vendeur supprimé.');
    }
}
