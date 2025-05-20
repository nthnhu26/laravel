<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Amenity;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::query();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('amenities') && is_array($request->amenities)) {
            $query->whereHas('amenities', function ($q) use ($request) {
                $q->whereIn('amenity_id', $request->amenities);
            });
        }

        $restaurants = $query->with(['images'])->paginate(10);
        $amenities = Amenity::all();

        return view('frontend.restaurants.index', compact('restaurants', 'amenities'));
    }

    public function show($id)
    {
        $restaurant = Restaurant::with([
            'amenities',
            'images',
            'reviews.user'
        ])->findOrFail($id);

        // Debug dữ liệu reviews
        Log::info('Restaurant ID: ' . $id);
        Log::info('Reviews count: ' . $restaurant->reviews->count());
        Log::info('Reviews data: ', $restaurant->reviews->toArray());

        // Lấy bài post liên quan


        // Lấy nhà hàng liên quan
        $relatedRestaurants = Restaurant::where('restaurant_id', '!=', $id)
            ->where(function ($query) use ($restaurant) {
                $query->whereRaw('ST_Distance_Sphere(
                    POINT(longitude, latitude),
                    POINT(?, ?)
                ) < 10000', [$restaurant->longitude, $restaurant->latitude]);
            })
            ->with(['images'])
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('frontend.restaurants.show', compact('restaurant', 'relatedRestaurants'));
    }
}