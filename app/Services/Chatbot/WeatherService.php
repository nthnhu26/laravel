<?php

namespace App\Services\Chatbot;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WeatherService
{
    private $apiKey;
    private $apiUrl;
    private $latitude;
    private $longitude;

    public function __construct()
    {
        $this->apiKey = env('OPENWEATHER_API_KEY');
        $this->apiUrl = 'https://api.openweathermap.org/data/2.5';
        $this->latitude = env('BA_DONG_LATITUDE', '10.1234');
        $this->longitude = env('BA_DONG_LONGITUDE', '106.5678');
    }

    public function getForecastForDays($location = null, $days = 7)
    {
        try {
            $coordinates = $this->getCoordinates($location);
            $response = Http::get("{$this->apiUrl}/forecast", [
                'lat' => $coordinates['latitude'],
                'lon' => $coordinates['longitude'],
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'vi'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->processForecastData($data, $days);
            }

            Log::error('OpenWeather API error', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OpenWeather API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    public function getCurrentWeather($location = null)
    {
        try {
            $coordinates = $this->getCoordinates($location);
            $response = Http::get("{$this->apiUrl}/weather", [
                'lat' => $coordinates['latitude'],
                'lon' => $coordinates['longitude'],
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'vi'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'temperature' => round($data['main']['temp']),
                    'description' => $data['weather'][0]['description'],
                    'humidity' => $data['main']['humidity'],
                    'wind_speed' => $data['wind']['speed'],
                    'icon' => $data['weather'][0]['icon']
                ];
            }

            Log::error('OpenWeather API error', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OpenWeather API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    private function getCoordinates($location)
    {
        if ($location === 'Ba Dong Beach' || $location === null) {
            return [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude
            ];
        }

        // TODO: Implement geocoding for other locations
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }

    private function processForecastData($data, $days)
    {
        $forecast = [];
        $currentDate = null;
        $dailyData = [];

        foreach ($data['list'] as $item) {
            $date = Carbon::createFromTimestamp($item['dt'])->format('Y-m-d');

            if ($currentDate !== $date) {
                if ($currentDate !== null) {
                    $forecast[] = $this->processDayForecast($dailyData);
                }
                $currentDate = $date;
                $dailyData = [];
            }

            $dailyData[] = $item;
        }

        if (!empty($dailyData)) {
            $forecast[] = $this->processDayForecast($dailyData);
        }

        return array_slice($forecast, 0, $days);
    }

    private function processDayForecast($dailyData)
    {
        $temperatures = [];
        $descriptions = [];
        $rainChance = 0;

        foreach ($dailyData as $data) {
            $temperatures[] = $data['main']['temp'];
            $descriptions[] = $data['weather'][0]['description'];
            if (isset($data['pop'])) {
                $rainChance = max($rainChance, $data['pop'] * 100);
            }
        }

        $avgTemp = array_sum($temperatures) / count($temperatures);
        $mainDescription = $this->getMostFrequentDescription($descriptions);

        return [
            'date' => Carbon::createFromTimestamp($dailyData[0]['dt'])->format('d/m/Y'),
            'temperature' => round($avgTemp),
            'description' => $mainDescription,
            'rain_chance' => round($rainChance)
        ];
    }

    private function getMostFrequentDescription($descriptions)
    {
        $counts = array_count_values($descriptions);
        arsort($counts);
        return array_key_first($counts);
    }
}
