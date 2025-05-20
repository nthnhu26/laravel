<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotMessage;
use App\Models\ChatbotConversation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChatHistoryService
{
    public function saveMessage($userId, $message, $role = 'user')
    {
        // Lấy hoặc tạo conversation mới
        $conversation = ChatbotConversation::where('user_id', $userId)
            ->whereNull('ended_at')
            ->first();

        if (!$conversation) {
            $conversation = ChatbotConversation::create([
                'session_id' => uniqid(),
                'user_id' => $userId,
                'started_at' => Carbon::now()
            ]);
        }

        // Lưu tin nhắn
        return ChatbotMessage::create([
            'conversation_id' => $conversation->conversation_id,
            'is_from_user' => $role === 'user',
            'message' => $message,
            'created_at' => Carbon::now()
        ]);
    }

    public function getRecentHistory($userId, $limit = 10)
    {
        return ChatbotMessage::join('chatbot_conversations', 'chatbot_messages.conversation_id', '=', 'chatbot_conversations.conversation_id')
            ->where('chatbot_conversations.user_id', $userId)
            ->orderBy('chatbot_messages.created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getContext($userId, $limit = 5)
    {
        $history = $this->getRecentHistory($userId, $limit);
        $context = [];

        foreach ($history as $message) {
            $context[] = [
                'role' => $message->is_from_user ? 'user' : 'assistant',
                'content' => $message->message
            ];
        }

        return $context;
    }

    public function clearHistory($userId)
    {
        // Đánh dấu tất cả conversation của user là đã kết thúc
        ChatbotConversation::where('user_id', $userId)
            ->whereNull('ended_at')
            ->update(['ended_at' => Carbon::now()]);
    }
}
