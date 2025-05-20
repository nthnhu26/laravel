<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\Tour;
use App\Models\Restaurant;
use App\Models\Transport;
use App\Models\Event;
use App\Models\Post;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Gợi ý cá nhân hóa (nếu đăng nhập)
        $personalizedItems = [];
        if (Auth::check()) {
            $preferences = UserPreference::where('user_id', Auth::id())->pluck('preference_type_id')->toArray();
            if (!empty($preferences)) {
                $personalizedItems = Attraction::whereHas('amenities', function ($q) use ($preferences) {
                    $q->whereIn('name->en', $preferences);
                })->with(['images'])->take(4)->get();
            }
        }

        // Địa điểm nổi bật (dựa trên lượt xem)
        $popularAttractions = Attraction::with(['images'])
            ->select('attractions.*') // Chọn tất cả cột của attractions
            ->leftJoin('analytics', function ($join) {
                $join->on('attractions.attraction_id', '=', 'analytics.entity_id')
                    ->where('analytics.entity_type', 'attraction');
            })
            ->groupBy(
                'attractions.attraction_id',
                'attractions.name',
                'attractions.type',
                'attractions.description',
                'attractions.address',
                'attractions.latitude',
                'attractions.longitude',
                'attractions.opening_hours',
                'attractions.status',
                'attractions.created_at',
                'attractions.updated_at'
            ) // Liệt kê tất cả cột của attractions trong GROUP BY
            ->orderByRaw('COUNT(analytics.analytic_id) DESC')
            ->take(4)
            ->get();

        // Tour nổi bật
        $popularTours = Tour::with(['images'])
            ->where('status', 'active')
            ->inRandomOrder()
            ->take(3)
            ->get();

        // Nhà hàng được yêu thích
        $popularRestaurants = Restaurant::with(['images'])
            ->leftJoin('reviews', function ($join) {
                $join->on('restaurants.restaurant_id', '=', 'reviews.entity_id')
                    ->where('reviews.entity_type', 'restaurant')
                    ->where('reviews.status', 'approved');
            })
            ->select('restaurants.*')
            ->groupBy(
                'restaurants.restaurant_id',
                'restaurants.name',
                'restaurants.type',
                'restaurants.price_category',
                'restaurants.provider_id',
                'restaurants.is_admin_managed',
                'restaurants.description',
                'restaurants.address',
                'restaurants.contact_info',
                'restaurants.latitude',
                'restaurants.longitude',
                'restaurants.opening_hours',
                'restaurants.price_range',
                'restaurants.cancellation_policy',
                'restaurants.status',
                'restaurants.created_at',
                'restaurants.updated_at'
            )
            ->orderByRaw('AVG(reviews.rating) DESC')
            ->take(3)
            ->get();

        // Phương tiện có sẵn
        $availableTransports = Transport::with(['images'])
            ->where('status', 'available')
            ->inRandomOrder()
            ->take(3)
            ->get();

        // Sự kiện sắp tới
        $upcomingEvents = Event::with(['images'])
            ->where('status', 'upcoming')
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        // Bài viết cộng đồng
        $communityPosts = Post::with(['author'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('frontend.home', compact(
            'personalizedItems',
            'popularAttractions',
            'popularTours',
            'popularRestaurants',
            'availableTransports',
            'upcomingEvents',
            'communityPosts'
        ));
    }
}