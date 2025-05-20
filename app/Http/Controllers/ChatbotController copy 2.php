<?php
// app/Http/Controllers/ChatbotController.php
namespace App\Http\Controllers;

use App\Services\Chatbot\IntentDetector;
use App\Services\Chatbot\WeatherService;
use App\Services\Chatbot\RecommendationService;
use App\Services\Chatbot\AttractionService;
use App\Services\Chatbot\MapService;
use App\Services\Chatbot\FallbackService;
use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    protected $intentDetector;
    protected $weatherService;
    protected $recommendationService;
    protected $attractionService;
    protected $mapService;
    protected $fallbackService;

    public function __construct(
        IntentDetector $intentDetector,
        WeatherService $weatherService,
        RecommendationService $recommendationService,
        AttractionService $attractionService,
        MapService $mapService,
        FallbackService $fallbackService
    ) {
        $this->intentDetector = $intentDetector;
        $this->weatherService = $weatherService;
        $this->recommendationService = $recommendationService;
        $this->attractionService = $attractionService;
        $this->mapService = $mapService;
        $this->fallbackService = $fallbackService;
    }

    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        $message = $request->input('message');
        $userId = auth()->id();
        $sessionId = $request->session()->getId();

        $conversation = ChatbotConversation::firstOrCreate(
            ['session_id' => $sessionId],
            ['user_id' => $userId, 'started_at' => now()]
        );

        if ($conversation->messages()->count() > 100) {
            return response()->json(['reply' => 'Phiên trò chuyện đã đạt giới hạn. Vui lòng bắt đầu lại!']);
        }

        ChatbotMessage::create([
            'conversation_id' => $conversation->conversation_id,
            'is_from_user' => true,
            'message' => $message,
        ]);

        $intent = $this->intentDetector->detect($message);
        $reply = $this->handleIntent($intent, $message, $userId);

        ChatbotMessage::create([
            'conversation_id' => $conversation->conversation_id,
            'is_from_user' => false,
            'message' => $reply,
            'intent_id' => $intent ? $intent->intent_id : null,
            'entities' => $this->extractEntities($message),
        ]);

        return response()->json(['reply' => $reply]);
    }

    private function handleIntent($intent, $message, $userId)
    {
        if (!$intent) {
            return $this->fallbackService->handle($message, $userId);
        }

        switch ($intent->intent_name) {
            case 'weather_query':
                return $this->weatherService->handle($message);
            case 'restaurant_recommendation':
                return $this->recommendationService->handleRestaurant($userId);
            case 'hotel_recommendation':
                return $this->recommendationService->handleHotel($userId);
            case 'attraction_info':
                return $this->attractionService->handle($message);
            case 'map_request':
                return $this->mapService->handle($message);
            default:
                return $this->fallbackService->handle($message, $userId);
        }
    }

    private function extractEntities($message)
    {
        $entities = [];
        $date = $this->parseDateFromMessage($message);
        if ($date) {
            $entities['date'] = $date->toDateString();
        }
        return $entities;
    }

    private function parseDateFromMessage($message)
    {
        $message = mb_strtolower($message);
        if (strpos($message, 'hôm nay') !== false) {
            return Carbon::today();
        }
        if (strpos($message, 'ngày mai') !== false) {
            return Carbon::tomorrow();
        }
        preg_match('/(\d{1,2})[\/\-](\d{1,2})(?:[\/\-](\d{4}))?/', $message, $matches);
        if ($matches) {
            $day = $matches[1];
            $month = $matches[2];
            $year = $matches[3] ?? Carbon::today()->year;
            try {
                return Carbon::create($year, $month, $day);
            } catch (\Exception $e) {
                return null;
            }
        }
        return Carbon::today();
    }
}