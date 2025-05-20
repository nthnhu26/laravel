<?php

namespace App\Http\Controllers;

use App\Services\Chatbot\GeminiService;
use App\Services\Chatbot\ChatHistoryService;
use App\Services\Chatbot\PromptService;
use App\Services\Chatbot\IntentService;
use App\Services\Chatbot\ItineraryService;
use App\Services\Chatbot\WeatherService;
use App\Services\Chatbot\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    private $geminiService;
    private $chatHistoryService;
    private $promptService;
    private $intentService;
    private $itineraryService;
    private $weatherService;
    private $recommendationService;

    public function __construct(
        GeminiService $geminiService,
        ChatHistoryService $chatHistoryService,
        PromptService $promptService,
        IntentService $intentService,
        ItineraryService $itineraryService,
        WeatherService $weatherService,
        RecommendationService $recommendationService
    ) {
        $this->geminiService = $geminiService;
        $this->chatHistoryService = $chatHistoryService;
        $this->promptService = $promptService;
        $this->intentService = $intentService;
        $this->itineraryService = $itineraryService;
        $this->weatherService = $weatherService;
        $this->recommendationService = $recommendationService;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = $request->input('message');
        $userId = Auth::id(); // Get authenticated user ID

        // Save user message
        $this->chatHistoryService->saveMessage($userId, $message, 'user');

        // Detect intent
        $intent = $this->intentService->detectIntent($message);
        $entities = $this->intentService->extractEntities($message);

        // Check required information
        $missingInfo = $this->intentService->checkRequiredInfo($intent, $entities);
        if (!empty($missingInfo)) {
            $response = $this->promptService->generateMissingInfoPrompt($missingInfo);
            $this->chatHistoryService->saveMessage($userId, $response, 'assistant');
            return response()->json(['response' => $response]);
        }

        // Generate prompt
        $prompt = $this->promptService->generatePrompt($intent, $entities, $userId);

        // Call Gemini API
        $response = $this->geminiService->generateResponse($prompt);

        // Save response
        $this->chatHistoryService->saveMessage($userId, $response, 'assistant');

        return response()->json(['response' => $response]);
    }

    public function getChatHistory(Request $request)
    {
        $userId = Auth::id();
        $history = $this->chatHistoryService->getRecentHistory($userId);
        return response()->json(['history' => $history]);
    }
}
