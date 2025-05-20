<?php
// app/Http/Controllers/Admin/ChatbotTrainingController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotIntent;
use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Services\Chatbot\IntentDetector;
use App\Services\Chatbot\WeatherService;
use App\Services\Chatbot\RecommendationService;
use App\Services\Chatbot\AttractionService;
use App\Services\Chatbot\MapService;
use App\Services\Chatbot\FallbackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChatbotTrainingController extends Controller
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

    public function index()
    {
        $intents = ChatbotIntent::all();
        $suggestedPhrases = ChatbotMessage::where('is_from_user', true)
            ->whereNull('intent_id')
            ->groupBy('message')
            ->selectRaw('message, COUNT(*) as count')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('admin.chatbot.train', compact('intents', 'suggestedPhrases'));
    }

    public function edit(ChatbotIntent $intent)
    {
        $intents = ChatbotIntent::all();
        $suggestedPhrases = ChatbotMessage::where('is_from_user', true)
            ->whereNull('intent_id')
            ->groupBy('message')
            ->selectRaw('message, COUNT(*) as count')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('admin.chatbot.train', compact('intent', 'intents', 'suggestedPhrases'));
    }

    public function update(Request $request, ChatbotIntent $intent)
    {
        $request->validate([
            'sample_phrases' => 'array',
            'sample_phrases.*' => 'string|max:1000',
        ]);

        $intent->update([
            'sample_phrases' => $request->sample_phrases ?? [],
        ]);

        return redirect()->route('admin.chatbot.train')->with('success', 'Mẫu câu hỏi đã được cập nhật!');
    }

    public function test(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        $message = $request->input('message');
        $userId = auth()->id();

        $intent = $this->intentDetector->detect($message);
        $reply = $this->handleIntent($intent, $message, $userId);

        $testResult = [
            'intent' => $intent ? $intent->intent_name : 'Không xác định',
            'reply' => $reply,
            'similarity' => $intent ? $this->calculateSimilarity($message, $intent->sample_phrases) : 0,
        ];

        $intents = ChatbotIntent::all();
        $suggestedPhrases = ChatbotMessage::where('is_from_user', true)
            ->whereNull('intent_id')
            ->groupBy('message')
            ->selectRaw('message, COUNT(*) as count')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('admin.chatbot.train', compact('intents', 'suggestedPhrases', 'testResult', 'message'));
    }

    public function suggest(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'intent_name' => 'required|string|exists:chatbot_intents,intent_name',
        ]);

        $intent = ChatbotIntent::where('intent_name', $request->intent_name)->first();
        $phrases = $intent->sample_phrases ?? [];
        if (!in_array($request->message, $phrases)) {
            $phrases[] = $request->message;
            $intent->update(['sample_phrases' => $phrases]);
        }

        return redirect()->route('admin.chatbot.train')->with('success', "Câu hỏi đã được thêm vào ý định {$request->intent_name}!");
    }

    public function conversations(Request $request)
    {
        $conversations = ChatbotConversation::with('messages')
            ->when($request->intent_id, function ($query) use ($request) {
                $query->whereHas('messages', function ($q) use ($request) {
                    $q->where('intent_id', $request->intent_id);
                });
            })
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('started_at', $request->date);
            })
            ->latest()
            ->paginate(10);

        $intents = ChatbotIntent::all();
        return view('admin.chatbot.conversations', compact('conversations', 'intents'));
    }

    public function showConversation(ChatbotConversation $conversation)
    {
        $conversation->load('messages', 'user');
        return view('admin.chatbot.conversation_show', compact('conversation'));
    }

    public function destroyConversation(ChatbotConversation $conversation)
    {
        $conversation->delete();
        return redirect()->route('admin.chatbot.conversations')->with('success', 'Hội thoại đã được xóa!');
    }

    public function analytics()
    {
        $totalMessages = ChatbotMessage::where('is_from_user', true)->count();
        $matchedMessages = ChatbotMessage::where('is_from_user', true)->whereNotNull('intent_id')->count();
        $fallbackMessages = ChatbotMessage::where('is_from_user', true)->whereNull('intent_id')->count();

        $intentStats = ChatbotIntent::withCount(['messages' => function ($query) {
            $query->where('is_from_user', true);
        }])->get()->map(function ($intent) {
            return [
                'name' => $intent->intent_name,
                'count' => $intent->messages_count,
            ];
        });

        $intentLabels = $intentStats->pluck('name')->toArray();
        $intentCounts = $intentStats->pluck('count')->toArray();

        $popularMessages = ChatbotMessage::where('is_from_user', true)
            ->groupBy('message')
            ->selectRaw('message, COUNT(*) as count')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('admin.chatbot.analytics', compact('totalMessages', 'matchedMessages', 'fallbackMessages', 'intentLabels', 'intentCounts', 'popularMessages'));
    }

    // app/Http/Controllers/Admin/ChatbotTrainingController.php
    public function config()
    {
        $intents = ChatbotIntent::all();
        $config = Cache::get('chatbot_config', [
            'weather_query' => ['thời tiết', 'mưa', 'nắng', 'nhiệt độ', 'nóng', 'lạnh', 'dự báo'],
            'restaurant_recommendation' => ['nhà hàng', 'quán ăn', 'hải sản', 'ngon'],
            'hotel_recommendation' => ['khách sạn', 'homestay', 'nghỉ', 'phòng'],
            'attraction_info' => ['địa điểm', 'chơi', 'giờ mở cửa', 'tham quan'],
            'map_request' => ['bản đồ', 'vị trí', 'đường đi'],
            'similarity_threshold' => 80,
        ]);

        return view('admin.chatbot.config', compact('config', 'intents'));
    }

    public function updateConfig(Request $request)
    {
        $request->validate([
            'keywords' => 'array',
            'keywords.*' => 'array',
            'keywords.*.*' => 'string|max:255',
            'similarity_threshold' => 'required|integer|min:50|max:100',
        ]);

        $config = [
            'similarity_threshold' => $request->similarity_threshold,
        ];

        // Lưu từ khóa cho từng ý định
        foreach ($request->keywords as $intentName => $keywords) {
            $config[$intentName] = array_filter($keywords, fn($keyword) => !empty(trim($keyword)));
        }

        Cache::put('chatbot_config', $config, now()->addDays(30));

        return redirect()->route('admin.chatbot.config')->with('success', 'Cấu hình đã được cập nhật!');
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

    private function calculateSimilarity($message, $phrases)
    {
        $maxSimilarity = 0;
        foreach ($phrases as $phrase) {
            similar_text(mb_strtolower($message), mb_strtolower($phrase), $percent);
            $maxSimilarity = max($maxSimilarity, $percent);
        }
        return $maxSimilarity;
    }
}
