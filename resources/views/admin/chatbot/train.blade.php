<!-- resources/views/admin/chatbot/train.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Mẫu Câu Hỏi Chatbot - Biển Ba Động</title>
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
        <h1 class="text-2xl font-bold text-center mb-6 text-blue-800">Quản lý Mẫu Câu Hỏi Chatbot</h1>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Kiểm tra nhận diện -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Kiểm tra nhận diện ý định</h2>
            <form action="{{ route('admin.chatbot.test') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700">Nhập câu hỏi</label>
                    <input type="text" name="message" class="w-full p-2 border rounded" placeholder="Ví dụ: Thời tiết hôm nay ở Ba Động?" value="{{ $message ?? '' }}">
                </div>
                <button type="submit" class="px-4 py-2 theme-sand-yellow rounded">Kiểm tra</button>
            </form>
            @if (isset($testResult))
                <div class="mt-4 p-4 bg-gray-100 rounded">
                    <p><strong>Câu hỏi:</strong> {{ $message }}</p>
                    <p><strong>Ý định nhận diện:</strong> {{ $testResult['intent'] }}</p>
                    <p><strong>Độ tương đồng:</strong> {{ number_format($testResult['similarity'], 2) }}%</p>
                    <p><strong>Phản hồi:</strong> {{ $testResult['reply'] }}</p>
                </div>
            @endif
        </div>

        <!-- Gợi ý mẫu câu -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Gợi ý mẫu câu từ hội thoại</h2>
            <table class="w-full table-auto">
                <thead>
                    <tr class="theme-sea-blue">
                        <th class="p-2">Câu hỏi</th>
                        <th class="p-2">Số lần hỏi</th>
                        <th class="p-2">Thêm vào ý định</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suggestedPhrases as $msg)
                        <tr class="border-b">
                            <td class="p-2">{{ $msg->message }}</td>
                            <td class="p-2">{{ $msg->count }}</td>
                            <td class="p-2">
                                <form action="{{ route('admin.chatbot.intents.suggest') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="message" value="{{ $msg->message }}">
                                    <select name="intent_name" class="p-1 border rounded">
                                        @foreach ($intents as $intent)
                                            <option value="{{ $intent->intent_name }}">{{ $intent->intent_name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="ml-2 text-blue-500">Thêm</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Danh sách ý định -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Danh sách ý định</h2>
            <table class="w-full table-auto">
                <thead>
                    <tr class="theme-sea-blue">
                        <th class="p-2">Ý định</th>
                        <th class="p-2">Mô tả</th>
                        <th class="p-2">Mẫu câu hỏi</th>
                        <th class="p-2">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($intents as $intent)
                        <tr class="border-b">
                            <td class="p-2">{{ $intent->intent_name }}</td>
                            <td class="p-2">{{ $intent->description }}</td>
                            <td class="p-2">{{ implode(', ', $intent->sample_phrases ?? []) }}</td>
                            <td class="p-2">
                                <a href="{{ route('admin.chatbot.intents.edit', $intent) }}" class="text-blue-500">Sửa mẫu câu</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Form chỉnh sửa mẫu câu -->
        @if (isset($intent))
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Chỉnh sửa mẫu câu hỏi cho {{ $intent->intent_name }}</h2>
                <form action="{{ route('admin.chatbot.intents.update', $intent) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div x-data="{ phrases: @json($intent->sample_phrases ?? []) }" class="mb-4">
                        <label class="block text-gray-700">Mẫu câu hỏi</label>
                        <div class="flex mb-2">
                            <input x-model="newPhrase" type="text" class="w-full p-2 border rounded" placeholder="Ví dụ: Thời tiết hôm nay?">
                            <button x-on:click="phrases.push(newPhrase); newPhrase = ''" type="button" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Thêm</button>
                        </div>
                        <ul class="list-disc pl-5">
                            <template x-for="(phrase, index) in phrases" :key="index">
                                <li>
                                    <span x-text="phrase"></span>
                                    <input type="hidden" name="sample_phrases[]" :value="phrase">
                                    <button x-on:click="phrases.splice(index, 1)" type="button" class="text-red-500 ml-2">Xóa</button>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <button type="submit" class="px-4 py-2 theme-sand-yellow rounded">Lưu mẫu câu</button>
                </form>
            </div>
        @endif
    </div>
</body>
</html>