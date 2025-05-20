<!-- resources/views/admin/chatbot/conversation_show.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Hội thoại - Biển Ba Động</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #E0E0E0; }
        .theme-sea-blue { background-color: #1E90FF; color: #FFFFFF; }
        .theme-sand-yellow { background-color: #F4A261; color: #FFFFFF; }
        .theme-sand-yellow:hover { background-color: #E69550; }
        .message-user { background-color: #E6F3FF; }
        .message-bot { background-color: #FFF5E6; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-center mb-6 text-blue-800">Chi tiết Hội thoại #{{ $conversation->conversation_id }}</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Thông tin hội thoại</h2>
            <p><strong>Người dùng:</strong> {{ $conversation->user ? $conversation->user->full_name : 'Khách' }}</p>
            <p><strong>Bắt đầu:</strong> {{ $conversation->started_at->format('d/m/Y H:i') }}</p>
            <p><strong>Kết thúc:</strong> {{ $conversation->ended_at ? $conversation->ended_at->format('d/m/Y H:i') : 'Đang mở' }}</p>

            <h3 class="text-lg font-semibold mt-4 mb-2">Tin nhắn</h3>
            <div class="space-y-4">
                @foreach ($conversation->messages as $message)
                    <div class="p-4 rounded-lg {{ $message->is_from_user ? 'message-user' : 'message-bot' }}">
                        <p><strong>{{ $message->is_from_user ? 'Người dùng' : 'Chatbot' }}:</strong> {{ $message->message }}</p>
                        <p class="text-sm text-gray-500">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                        @if ($message->intent_id)
                            <p class="text-sm">Ý định: {{ $message->intent->intent_name }}</p>
                        @endif
                        @if ($message->entities)
                            <p class="text-sm">Entities: {{ json_encode($message->entities) }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <a href="{{ route('admin.chatbot.conversations') }}" class="mt-4 inline-block px-4 py-2 theme-sand-yellow rounded">Quay lại</a>
    </div>
</body>
</html>