<?php

namespace App\Services\Chatbot;

use App\Models\Attraction;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\UserPreference;
use App\Models\Itinerary;
use App\Models\ItineraryDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ItineraryService
{
    private $weatherService;
    private $maxAttractionsPerDay = 3;
    private $maxRestaurantsPerDay = 2;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function generateItinerary($userId, $duration, $preferences = [], $budget = null, $startDate = null)
    {
        // Lấy sở thích người dùng từ database
        $userPreferences = UserPreference::where('user_id', $userId)
            ->join('preference_types', 'user_preferences.preference_type_id', '=', 'preference_types.preference_type_id')
            ->pluck('preference_types.name->vi')
            ->toArray();

        // Lấy thông tin thời tiết cho các ngày
        $weatherForecasts = $this->weatherService->getForecastForDays($startDate, $duration);

        // Lấy các địa điểm phù hợp
        $attractions = $this->getSuitableAttractions($userPreferences, $preferences);

        // Lấy khách sạn phù hợp
        $hotels = $this->getSuitableHotels($userPreferences, $budget);

        // Lấy nhà hàng phù hợp
        $restaurants = $this->getSuitableRestaurants($userPreferences, $budget);

        // Tạo lịch trình
        $itinerary = $this->createItinerary($userId, $duration, $attractions, $hotels, $restaurants, $weatherForecasts, $startDate);

        return $itinerary;
    }

    private function getSuitableAttractions($userPreferences, $preferences)
    {
        $query = Attraction::where('status', 'active');

        // Lọc theo loại địa điểm nếu có
        if (isset($preferences['type'])) {
            $query->where('type', $preferences['type']);
        }

        // Lọc theo sở thích người dùng
        if (!empty($userPreferences)) {
            $query->where(function ($q) use ($userPreferences) {
                foreach ($userPreferences as $preference) {
                    $q->orWhereRaw("description->>'vi' LIKE ?", ["%$preference%"]);
                }
            });
        }

        // Sắp xếp theo đánh giá và khoảng cách
        $query->orderBy('rating', 'desc');

        return $query->get();
    }

    private function getSuitableHotels($userPreferences, $budget)
    {
        $query = Hotel::where('status', 'active');

        // Lọc theo giá nếu có
        if ($budget) {
            $query->where('price_range', $budget);
        }

        // Lọc theo sở thích người dùng
        if (!empty($userPreferences)) {
            $query->where(function ($q) use ($userPreferences) {
                foreach ($userPreferences as $preference) {
                    $q->orWhereRaw("description->>'vi' LIKE ?", ["%$preference%"]);
                }
            });
        }

        return $query->get();
    }

    private function getSuitableRestaurants($userPreferences, $budget)
    {
        $query = Restaurant::where('status', 'active');

        // Lọc theo loại ẩm thực nếu có
        if (isset($preferences['cuisine'])) {
            $query->where('type', $preferences['cuisine']);
        }

        // Lọc theo giá nếu có
        if ($budget) {
            $query->where('price_category', $budget);
        }

        // Lọc theo sở thích người dùng
        if (!empty($userPreferences)) {
            $query->where(function ($q) use ($userPreferences) {
                foreach ($userPreferences as $preference) {
                    $q->orWhereRaw("description->>'vi' LIKE ?", ["%$preference%"]);
                }
            });
        }

        return $query->get();
    }

    private function createItinerary($userId, $duration, $attractions, $hotels, $restaurants, $weatherForecasts, $startDate)
    {
        // Tạo lịch trình mới
        $itinerary = Itinerary::create([
            'user_id' => $userId,
            'title' => 'Lịch trình du lịch Biển Ba Động',
            'start_date' => $startDate ?? now(),
            'end_date' => ($startDate ?? now())->addDays($duration - 1)
        ]);

        // Phân bổ các địa điểm vào các ngày
        $attractionsPerDay = ceil($attractions->count() / $duration);
        $currentDay = 0;

        foreach ($attractions->chunk($attractionsPerDay) as $dayAttractions) {
            $currentDay++;
            $currentDate = ($startDate ?? now())->addDays($currentDay - 1);
            $weather = $weatherForecasts[$currentDate->format('Y-m-d')] ?? null;

            // Thêm địa điểm vào lịch trình
            foreach ($dayAttractions as $attraction) {
                // Kiểm tra thời tiết có phù hợp không
                if ($weather && !$this->isWeatherSuitable($weather, $attraction->type)) {
                    continue;
                }

                ItineraryDetail::create([
                    'itinerary_id' => $itinerary->id,
                    'entity_type' => 'attraction',
                    'entity_id' => $attraction->id,
                    'visit_date' => $currentDate,
                    'notes' => $this->generateAttractionNotes($attraction, $weather)
                ]);

                // Thêm nhà hàng gần đó
                $nearbyRestaurants = $this->findNearbyRestaurants($attraction, $restaurants);
                if ($nearbyRestaurants->isNotEmpty()) {
                    foreach ($nearbyRestaurants->take($this->maxRestaurantsPerDay) as $restaurant) {
                        ItineraryDetail::create([
                            'itinerary_id' => $itinerary->id,
                            'entity_type' => 'restaurant',
                            'entity_id' => $restaurant->id,
                            'visit_date' => $currentDate,
                            'notes' => 'Địa điểm ăn uống gần ' . $attraction->name->vi
                        ]);
                    }
                }
            }
        }

        // Thêm khách sạn vào ngày đầu tiên
        if ($hotels->isNotEmpty()) {
            $bestHotel = $this->findBestHotel($hotels, $attractions->first());
            if ($bestHotel) {
                ItineraryDetail::create([
                    'itinerary_id' => $itinerary->id,
                    'entity_type' => 'hotel',
                    'entity_id' => $bestHotel->id,
                    'visit_date' => $startDate ?? now(),
                    'notes' => 'Nơi lưu trú'
                ]);
            }
        }

        return $itinerary;
    }

    private function findNearbyRestaurants($attraction, $restaurants)
    {
        return $restaurants->filter(function ($restaurant) use ($attraction) {
            $distance = $this->calculateDistance(
                $attraction->latitude,
                $attraction->longitude,
                $restaurant->latitude,
                $restaurant->longitude
            );
            return $distance <= 5; // Trong bán kính 5km
        })->sortBy(function ($restaurant) use ($attraction) {
            return $this->calculateDistance(
                $attraction->latitude,
                $attraction->longitude,
                $restaurant->latitude,
                $restaurant->longitude
            );
        });
    }

    private function findBestHotel($hotels, $firstAttraction)
    {
        return $hotels->sortBy(function ($hotel) use ($firstAttraction) {
            return $this->calculateDistance(
                $firstAttraction->latitude,
                $firstAttraction->longitude,
                $hotel->latitude,
                $hotel->longitude
            );
        })->first();
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

    private function isWeatherSuitable($weather, $attractionType)
    {
        // Kiểm tra thời tiết có phù hợp với loại địa điểm không
        $weatherCondition = strtolower($weather['condition'] ?? '');

        switch ($attractionType) {
            case 'beach':
                return !str_contains($weatherCondition, 'mưa') &&
                    !str_contains($weatherCondition, 'bão');
            case 'outdoor':
                return !str_contains($weatherCondition, 'mưa');
            default:
                return true;
        }
    }

    private function generateAttractionNotes($attraction, $weather = null)
    {
        $notes = [];

        if ($attraction->opening_hours) {
            $notes[] = "Giờ mở cửa: " . $attraction->opening_hours->vi;
        }

        if ($attraction->description) {
            $notes[] = "Mô tả: " . $attraction->description->vi;
        }

        if ($weather) {
            $notes[] = "Thời tiết: " . $weather['condition'] .
                ", nhiệt độ " . $weather['temperature'] . "°C";
        }

        return implode("\n", $notes);
    }
}
