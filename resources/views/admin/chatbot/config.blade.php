<!-- resources/views/admin/chatbot/config.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cấu hình Chatbot - Biển Ba Động</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #E0E0E0; }
        .theme-sea-blue { background-color: #1E90FF; color: #FFFFFF; }
        .theme-sand-yellow { background-color: #F4A261; color: #FFFFFF; }
        .theme-sand-yellow:hover { background-color: #E69550; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-center mb-6 text-blue-800">Cấu hình Chatbot</h1>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Cấu hình nhận diện ý định</h2>
            <form action="{{ route('admin.chatbot.config.update') }}" method="POST">
                @csrf
                @foreach ($intents as $intent)
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold">Từ khóa cho {{ $intent->intent_name }} ({{ $intent->description }})</label>
                        <div x-data="{ keywords: @json($config[$intent->intent_name] ?? []) }">
                            <div class="flex mb-2">
                                <input x-model="newKeyword" type="text" class="w-full p-2 border rounded" placeholder="Ví dụ: {{ $intent->intent_name === 'weather_query' ? 'thời tiết' : ($intent->intent_name === 'restaurant_recommendation' ? 'nhà hàng' : 'khách sạn') }}">
                                <button x-on:click="keywords.push(newKeyword); newKeyword = ''" type="button" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Thêm</button>
                            </div>
                            <ul class="list-disc pl-5">
                                <template x-for="(keyword, index) in keywords" :key="index">
                                    <li>
                                        <span x-text="keyword"></span>
                                        <input type="hidden" name="keywords[{{ $intent->intent_name }}][]" :value="keyword">
                                        <button x-on:click="keywords.splice(index, 1)" type="button" class="text-red-500 ml-2">Xóa</button>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                @endforeach
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Ngưỡng tương đồng (%)</label>
                    <input type="number" name="similarity_threshold" class="w-full p-2 border rounded" value="{{ $config['similarity_threshold'] }}" min="50" max="100" required>
                </div>
                <button type="submit" class="px-4 py-2 theme-sand-yellow rounded">Lưu cấu hình</button>
            </form>
        </div>
    </div>
</body>
</html>