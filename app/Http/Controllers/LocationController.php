<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function locateArtisans(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1|max:100'
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 10; // en kilomètres

        $artisans = Artisan::nearby($latitude, $longitude, $radius)
            ->with(['user', 'photos'])
            ->where('visible', true)
            ->paginate(20);

        return view('artisans.nearby', compact('artisans', 'latitude', 'longitude', 'radius'));
    }
}
