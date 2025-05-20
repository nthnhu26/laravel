<?php
// File: app/Http/Controllers/HotelController.php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Review;
use App\Models\Amenity;
use App\Models\Favorite;
use App\Models\Analytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PDOException;

class HotelController extends Controller
{
    /**
     * Hiển thị danh sách khách sạn với bộ lọc và tìm kiếm.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            // Lấy tham số tìm kiếm và bộ lọc
            $keyword = $request->query('keyword');
            $type = $request->query('type');
            $priceRange = $request->query('price_range');
            $amenities = $request->query('amenities', []);
            $location = $request->query('location');

            // Truy vấn khách sạn
            $hotels = Hotel::query()
                ->when($keyword, function ($query) use ($keyword) {
                    $query->where('name->vi', 'LIKE', "%$keyword%")
                        ->orWhere('name->en', 'LIKE', "%$keyword%");
                })
                ->when($type, function ($query) use ($type) {
                    $query->where('type', $type);
                })
                ->when($priceRange, function ($query) use ($priceRange) {
                    $query->where('price_range', $priceRange);
                })
                ->when($location, function ($query) use ($location) {
                    $query->where('address->vi', 'LIKE', "%$location%")
                        ->orWhere('address->en', 'LIKE', "%$location%");
                })
                ->when(!empty($amenities), function ($query) use ($amenities) {
                    $query->whereHas('amenities', function ($q) use ($amenities) {
                        $q->whereIn('amenity_id', $amenities);
                    });
                })
                ->where('status', 'active')
                ->with(['images' => function ($query) {
                    $query->where('is_featured', true)->take(1);
                }])
                ->paginate(12);

            // Ghi log tìm kiếm
            Log::info('Hotel search completed', [
                'keyword' => $keyword,
                'filters' => $request->query(),
                'hotel_count' => $hotels->count(),
            ]);

            return view('frontend.hotels.index', compact('hotels'));
        } catch (\Exception $e) {
            Log::error('Error in HotelController::index: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', __('Lỗi hệ thống'));
        }
    }

    /**
     * Hiển thị chi tiết khách sạn.
     *
     * @param Request $request
     * @param int $hotelId
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $hotelId)
    {
        try {
            // Truy vấn khách sạn với các quan hệ
            $hotel = Hotel::with([
                'rooms' => function ($query) {
                    $query->where('status', 'active')
                        ->with(['images' => function ($q) {
                            $q->where('is_featured', true)->take(1);
                        }, 'amenities']);
                },
                'reviews' => function ($query) {
                    $query->where('status', 'approved')->with('user');
                },
                'images',
                'amenities',
                'provider'
            ])
                ->where('status', 'active')
                ->findOrFail($hotelId);

            // Lấy khách sạn liên quan
            $nearbyHotels = Hotel::where('status', 'active')
                ->where('hotel_id', '!=', $hotelId)
                ->where(function ($query) use ($hotel) {
                    $query->where('type', $hotel->type)
                        ->orWhereRaw('ST_Distance_Sphere(
                            POINT(longitude, latitude),
                            POINT(?, ?)
                        ) < 5000', [$hotel->longitude, $hotel->latitude]);
                })
                ->with(['images' => function ($query) {
                    $query->where('is_featured', true)->take(1);
                }])
                ->take(5)
                ->get();

            // Ghi log lượt xem với Analytic đa hình
            Analytic::create([
                'entity_type' => 'hotel',
                'entity_id' => $hotelId,
                'user_id' => Auth::id(),
                'action_type' => 'view',
                'ip_address' => $request->ip(),
                'device_type' => $this->getDeviceType($request->userAgent()),
                'country_code' => null, // Tắt geoip để tránh lỗi cache
                'city' => null, // Tắt geoip
                'page_url' => url()->current(),
                'session_id' => $request->session()->getId(),
            ]);

            return view('frontend.hotels.show', compact('hotel', 'nearbyHotels'));
        } catch (\Exception $e) {
            Log::error('Error in HotelController::show: ' . $e->getMessage(), [
                'hotel_id' => $hotelId,
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', __('Không tìm thấy khách sạn'));
        }
    }

    /**
     * Xử lý yêu thích (Favorites) cho các thực thể đa hình.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFavorite(Request $request)
    {
        try {
            $request->validate([
                'entity_type' => 'required|in:hotel,restaurant,attraction,tour,transport,room,dish',
                'entity_id' => 'required|integer',
            ]);

            $user = Auth::user();
            $entityType = $request->input('entity_type');
            $entityId = $request->input('entity_id');

            // Kiểm tra xem đã yêu thích chưa
            $favorite = Favorite::where('user_id', $user->user_id)
                ->where('favoritable_type', $entityType)
                ->where('favoritable_id', $entityId)
                ->first();

            if ($favorite) {
                $favorite->delete();
                $status = 'removed';
            } else {
                Favorite::create([
                    'user_id' => $user->user_id,
                    'favoritable_type' => $entityType,
                    'favoritable_id' => $entityId,
                ]);
                $status = 'added';
            }

            Log::info('Favorite toggled', [
                'user_id' => $user->user_id,
                'favoritable_type' => $entityType,
                'favoritable_id' => $entityId,
                'status' => $status,
            ]);

            return response()->json(['status' => $status], 200);
        } catch (\Exception $e) {
            Log::error('Error in HotelController::toggleFavorite: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => __('Lỗi hệ thống')], 500);
        }
    }

    /**
     * Xác định loại thiết bị từ User-Agent.
     *
     * @param string $userAgent
     * @return string
     */
    private function getDeviceType($userAgent)
    {
        if (preg_match('/mobile/i', $userAgent)) {
            return 'mobile';
        }
        if (preg_match('/tablet/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }
}