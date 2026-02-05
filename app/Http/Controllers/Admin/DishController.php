<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\DishImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DishController extends Controller
{
    public function index(Request $request)
    {
        $query = Dish::with(['images']);

        // Filtres
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        $dishes = $query->latest()->paginate(15);
        
        $categories = ['main' => 'Plat principal', 'drink' => 'Boisson', 'snack' => 'Snack', 'dessert' => 'Dessert', 'sauce' => 'Sauce'];
        $regions = Dish::distinct()->whereNotNull('region')->pluck('region');

        return view('admin.dishes.index', compact('dishes', 'categories', 'regions'));
    }

    public function create()
    {
        $categories = [
            'main' => 'Plat principal',
            'drink' => 'Boisson',
            'snack' => 'Snack',
            'dessert' => 'Dessert',
            'sauce' => 'Sauce',
        ];

        return view('admin.dishes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:main,drink,snack,dessert,sauce',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $dish = Dish::create([
                'name' => $request->name,
                'name_local' => $request->name_local,
                'category' => $request->category,
                'description' => $request->description,
                'history' => $request->history,
                'main_ingredients' => $request->main_ingredients ? json_encode(explode(',', $request->main_ingredients)) : null,
                'preparation_time' => $request->preparation_time,
                'cooking_time' => $request->cooking_time,
                'difficulty' => $request->difficulty,
                'servings' => $request->servings,
                'calories' => $request->calories,
                'region' => $request->region,
                'ethnic_group' => $request->ethnic_group,
                'is_vegetarian' => $request->boolean('is_vegetarian'),
                'is_vegan' => $request->boolean('is_vegan'),
                'is_gluten_free' => $request->boolean('is_gluten_free'),
                'spiciness' => $request->spiciness ?? 0,
                'slug' => Str::slug($request->name) . '-' . uniqid(),
                'featured' => $request->boolean('featured'),
            ]);

            // Upload des images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('dishes', 'public');
                    DishImage::create([
                        'dish_id' => $dish->id,
                        'image_url' => $path,
                        'order' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.dishes.index')
                ->with('success', 'Plat créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    public function show(Dish $dish)
    {
        $dish->load(['images', 'vendors']);
        return view('admin.dishes.show', compact('dish'));
    }

    public function edit(Dish $dish)
    {
        $dish->load(['images']);
        $categories = [
            'main' => 'Plat principal',
            'drink' => 'Boisson',
            'snack' => 'Snack',
            'dessert' => 'Dessert',
            'sauce' => 'Sauce',
        ];

        return view('admin.dishes.edit', compact('dish', 'categories'));
    }

    public function update(Request $request, Dish $dish)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:main,drink,snack,dessert,sauce',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $dish->update([
                'name' => $request->name,
                'name_local' => $request->name_local,
                'category' => $request->category,
                'description' => $request->description,
                'history' => $request->history,
                'main_ingredients' => $request->main_ingredients ? json_encode(explode(',', $request->main_ingredients)) : null,
                'preparation_time' => $request->preparation_time,
                'cooking_time' => $request->cooking_time,
                'difficulty' => $request->difficulty,
                'servings' => $request->servings,
                'calories' => $request->calories,
                'region' => $request->region,
                'ethnic_group' => $request->ethnic_group,
                'is_vegetarian' => $request->boolean('is_vegetarian'),
                'is_vegan' => $request->boolean('is_vegan'),
                'is_gluten_free' => $request->boolean('is_gluten_free'),
                'spiciness' => $request->spiciness ?? 0,
                'featured' => $request->boolean('featured'),
            ]);

            // Upload des nouvelles images
            if ($request->hasFile('images')) {
                $lastOrder = $dish->images()->max('order') ?? -1;
                foreach ($request->file('images') as $image) {
                    $lastOrder++;
                    $path = $image->store('dishes', 'public');
                    DishImage::create([
                        'dish_id' => $dish->id,
                        'image_url' => $path,
                        'order' => $lastOrder,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.dishes.index')
                ->with('success', 'Plat mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    public function destroy(Dish $dish)
    {
        try {
            // Supprimer les images
            foreach ($dish->images as $image) {
                Storage::disk('public')->delete($image->image_url);
            }
            
            $dish->delete();

            return redirect()
                ->route('admin.dishes.index')
                ->with('success', 'Plat supprimé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function deleteImage($imageId)
    {
        $image = DishImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->image_url);
        $image->delete();

        return response()->json(['success' => true]);
    }
}
