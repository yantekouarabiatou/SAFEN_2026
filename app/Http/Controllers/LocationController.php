<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function map(): \Illuminate\View\View
    {
        $artisans = Artisan::with(['user', 'photos'])
            ->where('status', 'approved')
            ->where('visible', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'user_id', 'business_name', 'craft', 'city', 'bio',
                'latitude', 'longitude', 'rating_avg', 'rating_count',
                'verified', 'whatsapp', 'phone']);

        $artisansJson = json_encode($artisans->map(function ($a) {
            $photo = $a->photos->first()?->photo_url ?? null;
            $photoUrl = $photo && file_exists(public_path($photo)) ? asset($photo) : null;

            return [
                'id' => $a->id,
                'name' => $a->business_name ?? $a->user?->name,
                'craft' => $a->craft,
                'city' => $a->city,
                'bio' => \Illuminate\Support\Str::limit($a->bio ?? '', 80),
                'lat' => (float) $a->latitude,
                'lng' => (float) $a->longitude,
                'rating' => round((float) ($a->rating_avg ?? 0), 1),
                'reviews' => (int) ($a->rating_count ?? 0),
                'verified' => (bool) $a->verified,
                'whatsapp' => $a->whatsapp,
                'phone' => $a->phone,
                'photo' => $photoUrl,
                'url' => route('artisans.show', $a->id),
            ];
        }), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);

        $crafts = Artisan::where('status', 'approved')
            ->where('visible', true)
            ->distinct()->pluck('craft')->sort()->values();

        $cities = Artisan::where('status', 'approved')
            ->where('visible', true)
            ->whereNotNull('city')
            ->distinct()->pluck('city')->sort()->values();

        return view('map', compact('artisans', 'artisansJson', 'crafts', 'cities'));
    }

    public function locateArtisans(Request $request): \Illuminate\View\View
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1|max:100',
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 10;

        $artisans = Artisan::nearby($latitude, $longitude, $radius)
            ->with(['user', 'photos'])
            ->where('visible', true)
            ->paginate(20);

        return view('artisans.nearby', compact('artisans', 'latitude', 'longitude', 'radius'));
    }
}
