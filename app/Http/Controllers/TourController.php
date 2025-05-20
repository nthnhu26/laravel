<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $query = Tour::query();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('duration_days') && $request->duration_days) {
            $query->where('duration_days', $request->duration_days);
        }

        if ($request->has('amenities') && is_array($request->amenities)) {
            $query->whereHas('amenities', function ($q) use ($request) {
                $q->whereIn('amenity_id', $request->amenities);
            });
        }

        $tours = $query->with(['images'])->paginate(10);
        $amenities = Amenity::all();

        return view('frontend.tours.index', compact('tours', 'amenities'));
    }

    public function show($id)
    {
        $tour = Tour::with([
            'provider',
            'createdBy',
            'tourDetails.attraction',
            'amenities',
            'images',
            'reviews.user'
        ])->findOrFail($id);

        // Debug dữ liệu reviews
        Log::info('Tour ID: ' . $id);
        Log::info('Reviews count: ' . $tour->reviews->count());
        Log::info('Reviews data: ', $tour->reviews->toArray());

        // Lấy tour liên quan
        $relatedTours = Tour::where('tour_id', '!=', $id)
            ->where('provider_id', $tour->provider_id)
            ->with(['images'])
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('frontend.tours.show', compact('tour', 'relatedTours'));
    }
}