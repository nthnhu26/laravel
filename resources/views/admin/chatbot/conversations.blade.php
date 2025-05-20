<!-- resources/views/admin/chatbot/conversations.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Hội thoại - Biển Ba Động</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #E0E0E0; }
        .theme-sea-blue { background-color: #1E90FF; color: #FFFFFF; }
        .theme-sand-yellow { background-color: #F4A261; color: #FFFFFF; }
        .theme-sand-yellow:hover { background-color: #E69550; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-center mb-6 text-blue-800">Quản lý Hội thoại Chatbot</h1>

        <!-- Bộ lọc -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Lọc hội thoại</h2>
            <form method="GET" action="{{ route('admin.chatbot.conversations') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700">Ý định</label>
                        <select name="intent_id" class="w-full p-2 border rounded">
                            <option value="">Tất cả</option>
                            @foreach ($intents as $intent)
                                <option value="{{ $intent->intent_id }}" {{ request('intent_id') == $intent->intent_id ? 'selected' : '' }}>{{ $intent->intent_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700">Người dùng (ID)</label>
                        <input type="number" name="user_id" class="w-full p-2 border rounded" value="{{ request('user_id') }}" placeholder="Nhập user_id">
                    </div>
                    <div>
                        <label class="block text-gray-700">Ngày bắt đầu</label>
                        <input type="date" name="date" class="w-full p-2 border rounded" value="{{ request('date') }}">
                    </div>
                </div>
                <button type="submit" class="mt-4 px-4 py-2 theme-sand-yellow rounded">Lọc</button>
            </form>
        </div>

        <!-- Danh sách hội thoại -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Danh sách hội thoại</h2>
            <table class="w-full table-auto">
                <thead>
                    <tr class="theme-sea-blue">
                        <th class="p-2">ID Phiên</th>
                        <th class="p-2">Người dùng</th>
                        <th class="p-2">Bắt đầu</th>
                        <th class="p-2">Số tin nhắn</th>
                        <th class="p-2">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($conversations as $conversation)
                        <tr class="border-b">
                            <td class="p-2">{{ $conversation->conversation_id }}</td>
                            <td class="p-2">{{ $conversation->user ? $conversation->user->full_name : 'Khách' }}</td>
                            <td class="p-2">{{ $conversation->started_at->format('d/m/Y H:i') }}</td>
                            <td class="p-2">{{ $conversation->messages->count() }}</td>
                            <td class="p-2">
                                <a href="{{ route('admin.chatbot.conversations.show', $conversation) }}" class="text-blue-500">Xem</a>
                                <form action="{{ route('admin.chatbot.conversations.destroy', $conversation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Xóa hội thoại này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $conversations->links() }}
        </div>
    </div>
</body>
</html>