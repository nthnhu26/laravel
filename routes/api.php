<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/goong/autocomplete', function (Illuminate\Http\Request $request) {
    $query = $request->query('input');
    $url = "https://rsapi.goong.io/Place/AutoComplete?api_key=" . config('services.goong.key') . "&input=" . urlencode($query) . "&limit=5&location=9.8088,106.5662&radius=2000";
    return Http::get($url)->json();
});

Route::get('/goong/place-detail', function (Illuminate\Http\Request $request) {
    $placeId = $request->query('place_id');
    $url = "https://rsapi.goong.io/Place/Detail?api_key=" . config('services.goong.key') . "&place_id=" . $placeId;
    return Http::get($url)->json();
});

Route::get('/goong/map-style', function () {
    $url = "https://tiles.goong.io/assets/goong_map_web.json?api_key=" . config('services.goong.maptiles_key');
    return Http::get($url)->json();
});


use App\Http\Controllers\SearchController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AnalyticsController;


Route::post('/search', [SearchController::class, 'search']); // Tìm kiếm
Route::post('/chatbot', [ChatbotController::class, 'handleMessage']); // Chatbot
Route::post('/analytics/track', [AnalyticsController::class, 'track']); // Theo dõi


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/chat', [ChatbotController::class, 'chat']);
    Route::get('/chat/history', [ChatbotController::class, 'getChatHistory']);
});

