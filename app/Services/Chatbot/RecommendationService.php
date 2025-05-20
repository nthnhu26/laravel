<?php

namespace App\Services\Chatbot;

use App\Models\Restaurant;
use App\Models\Hotel;
use App\Models\UserPreference;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    private $maxDistance = 5; // km
    private $maxResults = 5;

    public function getRestaurantRecommendations($userId, $location = null, $preferences = [], $budget = null)
    {
        $query = Restaurant::where('status', 'active');

        // Lọc theo vị trí nếu có
        if ($location) {
            $query->where(function ($q) use ($location) {
                $q->whereRaw("address->>'vi' LIKE ?", ["%$location%"]);
            });
        }

        // Lọc theo loại ẩm thực
        if (!empty($preferences['cuisine'])) {
            $query->where('type', $preferences['cuisine']);
        }

        // Lọc theo giá
        if ($budget) {
            $query->where('price_category', $budget);
        }

        // Lọc theo sở thích người dùng
        $userPreferences = $this->getUserPreferences($userId);
        if (!empty($userPreferences)) {
            $query->where(function ($q) use ($userPreferences) {
                foreach ($userPreferences as $preference) {
                    $q->orWhereRaw("description->>'vi' LIKE ?", ["%$preference%"]);
                }
            });
        }

        // Sắp xếp theo đánh giá và khoảng cách
        $query->orderBy('rating', 'desc');

        $restaurants = $query->get();

        // Lọc theo khoảng cách nếu có tọa độ
        if ($location && isset($location['latitude']) && isset($location['longitude'])) {
            $restaurants = $restaurants->filter(function ($restaurant) use ($location) {
                $distance = $this->calculateDistance(
                    $location['latitude'],
                    $location['longitude'],
                    $restaurant->latitude,
                    $restaurant->longitude
                );
                return $distance <= $this->maxDistance;
            })->sortBy(function ($restaurant) use ($location) {
                return $this->calculateDistance(
                    $location['latitude'],
                    $location['longitude'],
                    $restaurant->latitude,
                    $restaurant->longitude
                );
            });
        }

        return $restaurants->take($this->maxResults);
    }

    public function getHotelRecommendations($userId, $location = null, $preferences = [], $budget = null)
    {
        $query = Hotel::where('status', 'active');

        // Lọc theo vị trí nếu có
        if ($location) {
            $query->where(function ($q) use ($location) {
                $q->whereRaw("address->>'vi' LIKE ?", ["%$location%"]);
            });
        }

        // Lọc theo loại khách sạn
        if (!empty($preferences['type'])) {
            $query->where('type', $preferences['type']);
        }

        // Lọc theo giá
        if ($budget) {
            $query->where('price_range', $budget);
        }

        // Lọc theo tiện ích
        if (!empty($preferences['amenities'])) {
            $query->whereHas('amenities', function ($q) use ($preferences) {
                $q->whereIn('amenity_id', $preferences['amenities']);
            });
        }

        // Lọc theo sở thích người dùng
        $userPreferences = $this->getUserPreferences($userId);
        if (!empty($userPreferences)) {
            $query->where(function ($q) use ($userPreferences) {
                foreach ($userPreferences as $preference) {
                    $q->orWhereRaw("description->>'vi' LIKE ?", ["%$preference%"]);
                }
            });
        }

        // Sắp xếp theo đánh giá và khoảng cách
        $query->orderBy('rating', 'desc');

        $hotels = $query->get();

        // Lọc theo khoảng cách nếu có tọa độ
        if ($location && isset($location['latitude']) && isset($location['longitude'])) {
            $hotels = $hotels->filter(function ($hotel) use ($location) {
                $distance = $this->calculateDistance(
                    $location['latitude'],
                    $location['longitude'],
                    $hotel->latitude,
                    $hotel->longitude
                );
                return $distance <= $this->maxDistance;
            })->sortBy(function ($hotel) use ($location) {
                return $this->calculateDistance(
                    $location['latitude'],
                    $location['longitude'],
                    $hotel->latitude,
                    $hotel->longitude
                );
            });
        }

        return $hotels->take($this->maxResults);
    }

    private function getUserPreferences($userId)
    {
        return UserPreference::where('user_id', $userId)
            ->join('preference_types', 'user_preferences.preference_type_id', '=', 'preference_types.preference_type_id')
            ->pluck('preference_types.name->vi')
            ->toArray();
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
