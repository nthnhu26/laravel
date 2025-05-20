<!-- resources/views/admin/chatbot/analytics.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phân tích Hiệu suất Chatbot - Biển Ba Động</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #E0E0E0; }
        .theme-sea-blue { background-color: #1E90FF; color: #FFFFFF; }
        .theme-sand-yellow { background-color: #F4A261; color: #FFFFFF; }
        .theme-sand-yellow:hover { background-color: #E69550; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-center mb-6 text-blue-800">Phân tích Hiệu suất Chatbot</h1>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Thống kê chung</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-blue-100 rounded">
                    <p class="text-lg font-semibold">Tổng câu hỏi</p>
                    <p class="text-2xl">{{ $totalMessages }}</p>
                </div>
                <div class="p-4 bg-green-100 rounded">
                    <p class="text-lg font-semibold">Câu hỏi nhận diện</p>
                    <p class="text-2xl">{{ $matchedMessages }}</p>
                </div>
                <div class="p-4 bg-yellow-100 rounded">
                    <p class="text-lg font-semibold">Câu hỏi fallback</p>
                    <p class="text-2xl">{{ $fallbackMessages }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Phân bố ý định</h2>
            <canvas id="intentChart" height="100"></canvas>
            <script>
                const ctx = document.getElementById('intentChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($intentLabels),
                        datasets: [{
                            label: 'Số câu hỏi',
                            data: @json($intentCounts),
                            backgroundColor: '#1E90FF',
                            borderColor: '#1E90FF',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            </script>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Câu hỏi phổ biến</h2>
            <table class="w-full table-auto">
                <thead>
                    <tr class="theme-sea-blue">
                        <th class="p-2">Câu hỏi</th>
                        <th class="p-2">Số lần hỏi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($popularMessages as $msg)
                        <tr class="border-b">
                            <td class="p-2">{{ $msg->message }}</td>
                            <td class="p-2">{{ $msg->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>