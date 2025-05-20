<?php
// app/Http/Controllers/ChatbotController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Models\ChatbotIntent;
use App\Models\UserPreference;
use App\Models\SearchHistory;
use App\Models\Restaurant;
use App\Models\Hotel;
use App\Models\Attraction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        $message = $request->input('message');
        $userId = auth()->id();
        $sessionId = $request->session()->getId();

        // Lưu hội thoại
        $conversation = ChatbotConversation::firstOrCreate(
            ['session_id' => $sessionId],
            ['user_id' => $userId, 'started_at' => now()]
        );

        // Kiểm tra giới hạn tin nhắn
        if ($conversation->messages()->count() > 100) {
            return response()->json(['reply' => 'Phiên trò chuyện đã đạt giới hạn. Vui lòng bắt đầu lại!']);
        }

        // Lưu tin nhắn người dùng
        ChatbotMessage::create([
            'conversation_id' => $conversation->conversation_id,
            'is_from_user' => true,
            'message' => $message,
        ]);

        // Nhận diện ý định
        $intent = $this->detectIntent($message);

        // Lấy sở thích và lịch sử tìm kiếm
        $preferences = UserPreference::where('user_id', $userId)
            ->join('preference_types', 'user_preferences.preference_type_id', '=', 'preference_types.preference_type_id')
            ->pluck('preference_types.name->vi')
            ->toArray();
        $recentSearches = SearchHistory::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->pluck('keyword')
            ->toArray();

        // Xử lý ý định
        $reply = $this->handleIntent($intent, $message, $preferences, $recentSearches);

        // Lưu phản hồi chatbot
        ChatbotMessage::create([
            'conversation_id' => $conversation->conversation_id,
            'is_from_user' => false,
            'message' => $reply,
            'intent_id' => $intent ? $intent->intent_id : null,
        ]);

        return response()->json(['reply' => $reply]);
    }

    private function detectIntent($message)
    {
        // Xác định intent dựa trên từ khóa trong message
        $message = mb_strtolower($message);

        // Kiểm tra các từ khóa thời tiết
        $weatherKeywords = ['thời tiết', 'mưa', 'nắng', 'nhiệt độ', 'nóng', 'lạnh', 'dự báo'];
        foreach ($weatherKeywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                // Tìm intent thời tiết trong database
                $weatherIntent = ChatbotIntent::where('intent_name', 'weather_query')->first();
                if ($weatherIntent) {
                    return $weatherIntent;
                }

                // Nếu không tìm thấy trong database, tạo một intent tạm thời
                $tempIntent = new ChatbotIntent();
                $tempIntent->intent_id = 0;
                $tempIntent->intent_name = 'weather_query';
                return $tempIntent;
            }
        }

        // Kiểm tra các intent khác tương tự...
        $restaurantKeywords = ['nhà hàng', 'quán ăn', 'đồ ăn', 'ăn uống', 'ẩm thực'];
        foreach ($restaurantKeywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                $intent = ChatbotIntent::where('intent_name', 'restaurant_recommendation')->first();
                return $intent ?: (function () {
                    $temp = new ChatbotIntent();
                    $temp->intent_name = 'restaurant_recommendation';
                    return $temp;
                })();
            }
        }

        // Tương tự cho hotel và attraction...

        // Kiểm tra từ database theo cách cũ
        $intents = ChatbotIntent::all();
        foreach ($intents as $intent) {
            $phrases = $intent->sample_phrases ?? [];
            foreach ($phrases as $phrase) {
                if (stripos($message, $phrase) !== false) {
                    return $intent;
                }
            }
        }

        return null;
    }

    private function handleIntent($intent, $message, $preferences, $recentSearches)
    {
        // Kiểm tra intent trước khi gọi API
        if ($intent) {
            switch ($intent->intent_name) {
                case 'weather_query':
                    return $this->handleWeatherQuery($message);
                case 'restaurant_recommendation':
                    return $this->handleRestaurantRecommendation($preferences);
                case 'hotel_recommendation':
                    return $this->handleHotelRecommendation($preferences);
                case 'attraction_info':
                    return $this->handleAttractionInfo($message);
                case 'map_request':
                    return $this->handleMapRequest($message);
            }
        }

        // Nếu không có intent hoặc intent không khớp với các case ở trên, 
        // sử dụng Gemini API
        $prompt = "Bạn là trợ lý du lịch cho Biển Ba Động. Người dùng có sở thích: " . implode(', ', $preferences) .
            ". Tìm kiếm gần đây: " . implode(', ', $recentSearches) .
            ". Câu hỏi: '$message'. Trả lời bằng tiếng Việt, ngắn gọn và hữu ích.";

        // Gọi Gemini API
        $response = Http::post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'),
            [
                'contents' => [
                    ['parts' => [['text' => $prompt]]],
                ],
            ]
        );

        return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, tôi không hiểu câu hỏi. Bạn có thể hỏi lại không?';
    }

    // private function handleWeatherQuery($message)
    // {
    //     $targetDate = $this->parseDateFromMessage($message);
    //     if (!$targetDate) {
    //         return "Vui lòng chỉ định ngày cụ thể (ví dụ: hôm nay, ngày mai, hoặc 20/5).";
    //     }

    //     $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
    //         'lat' => 9.2833,
    //         'lon' => 106.3167,
    //         'appid' => env('OPENWEATHER_API_KEY', '4985bdef25e1a09040126d292b131f89'),
    //         'units' => 'metric',
    //         'lang' => 'vi',
    //     ]);

    //     if ($response->successful()) {
    //         $forecasts = $response->json()['list'];
    //         $targetDateStr = $targetDate->format('Y-m-d');
    //         $dayForecasts = array_filter($forecasts, function ($forecast) use ($targetDateStr) {
    //             return strpos($forecast['dt_txt'], $targetDateStr) === 0;
    //         });

    //         if (empty($dayForecasts)) {
    //             return "Không có dữ liệu dự báo cho ngày {$targetDate->format('d/m/Y')}.";
    //         }

    //         $temps = array_column($dayForecasts, 'main');
    //         $tempMin = min(array_column($temps, 'temp_min'));
    //         $tempMax = max(array_column($temps, 'temp_max'));
    //         $condition = $dayForecasts[array_key_first($dayForecasts)]['weather'][0]['description'];

    //         return "Chào bạn, thời tiết ngày {$targetDate->format('d/m/Y')} ở Biển Ba Động dự kiến $condition, nhiệt độ khoảng {$tempMin}-{$tempMax}°C. Chúc bạn có một ngày vui vẻ!";
    //     }

    //     return "Không thể lấy dữ liệu thời tiết. Bạn muốn thử lại không?";
    // }

    // private function parseDateFromMessage($message)
    // {
    //     $message = mb_strtolower($message);

    //     // Từ khóa ngày
    //     if (strpos($message, 'hôm nay') !== false) {
    //         return Carbon::today();
    //     }
    //     if (strpos($message, 'ngày mai') !== false) {
    //         return Carbon::tomorrow();
    //     }
    //     if (strpos($message, 'hôm qua') !== false) {
    //         return Carbon::yesterday();
    //     }

    //     // Ngày cụ thể (ví dụ: 20/5, 20-5, 20/05/2025)
    //     preg_match('/(\d{1,2})[\/\-](\d{1,2})(?:[\/\-](\d{4}))?/', $message, $matches);
    //     if ($matches) {
    //         $day = $matches[1];
    //         $month = $matches[2];
    //         $year = $matches[3] ?? Carbon::today()->year;
    //         try {
    //             return Carbon::create($year, $month, $day);
    //         } catch (\Exception $e) {
    //             return null;
    //         }
    //     }

    //     return null;
    // }
    /**
     * Xử lý các truy vấn thời tiết từ người dùng
     * Hỗ trợ cả dự báo một ngày cụ thể và dự báo nhiều ngày liên tiếp
     */
    private function handleWeatherQuery($message)
    {
        // Kiểm tra xem người dùng có yêu cầu dự báo nhiều ngày không
        $multiDayRequest = $this->isMultiDayRequest($message);

        if ($multiDayRequest) {
            // Xử lý yêu cầu dự báo nhiều ngày
            return $this->getMultiDayForecast($message);
        } else {
            // Xử lý yêu cầu dự báo một ngày cụ thể
            return $this->getSingleDayForecast($message);
        }
    }

    /**
     * Kiểm tra xem tin nhắn có phải là yêu cầu dự báo nhiều ngày không
     */
    private function isMultiDayRequest($message)
    {
        $message = mb_strtolower($message);
        $multiDayKeywords = [
            'nhiều ngày',
            'dài ngày',
            'dự báo 5 ngày',
            'tuần này',
            'tuần tới',
            'vài ngày tới',
            'mấy ngày tới',
            'mấy ngày nữa',
            'các ngày tới',
            'thời tiết tuần',
            'thời tiết tháng',
            'thời tiết mấy ngày',
            'thời tiết 3 ngày',
            'thời tiết 5 ngày',
            'thời tiết 7 ngày'
        ];

        foreach ($multiDayKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Trả về dự báo thời tiết cho một ngày cụ thể
     */
    private function getSingleDayForecast($message)
    {
        $targetDate = $this->parseDateFromMessage($message);
        if (!$targetDate) {
            return "Vui lòng chỉ định ngày cụ thể (ví dụ: hôm nay, ngày mai, hoặc 20/5).";
        }

        $now = Carbon::now();
        $daysFromNow = $targetDate->diffInDays($now);

        if ($daysFromNow > 5) {
            return "Tôi chỉ có thể dự báo thời tiết tối đa 5 ngày tới. Vui lòng chọn một ngày gần hơn.";
        }

        $response = $this->fetchWeatherData();

        if (!$response->successful()) {
            return "Không thể lấy dữ liệu thời tiết. Bạn muốn thử lại không?";
        }

        $forecasts = $response->json()['list'];
        $targetDateStr = $targetDate->format('Y-m-d');
        $dayForecasts = array_filter($forecasts, function ($forecast) use ($targetDateStr) {
            return strpos($forecast['dt_txt'], $targetDateStr) === 0;
        });

        if (empty($dayForecasts)) {
            return "Không có dữ liệu dự báo cho ngày {$targetDate->format('d/m/Y')}.";
        }

        // Phân tích dự báo theo khung giờ trong ngày
        $morningForecast = $this->findForecastByHour($dayForecasts, 9);
        $afternoonForecast = $this->findForecastByHour($dayForecasts, 15);
        $eveningForecast = $this->findForecastByHour($dayForecasts, 21);

        // Tính toán nhiệt độ trung bình, cao nhất, thấp nhất
        $temps = array_column($dayForecasts, 'main');
        $tempMin = min(array_column($temps, 'temp_min'));
        $tempMax = max(array_column($temps, 'temp_max'));

        // Lấy thông tin thời tiết chủ đạo
        $mainCondition = $this->getMainCondition($dayForecasts);
        $humidity = $this->getAverageHumidity($dayForecasts);

        $response = "Chào bạn, thời tiết ngày {$targetDate->format('d/m/Y')} ở Biển Ba Động:\n\n";
        $response .= "- Thời tiết chung: $mainCondition\n";
        $response .= "- Nhiệt độ: {$tempMin}-{$tempMax}°C\n";
        $response .= "- Độ ẩm trung bình: {$humidity}%\n\n";

        // Thêm chi tiết theo khung giờ nếu có
        if ($morningForecast) {
            $temp = round($morningForecast['main']['temp']);
            $condition = $morningForecast['weather'][0]['description'];
            $response .= "- Buổi sáng: $condition, {$temp}°C\n";
        }

        if ($afternoonForecast) {
            $temp = round($afternoonForecast['main']['temp']);
            $condition = $afternoonForecast['weather'][0]['description'];
            $response .= "- Buổi chiều: $condition, {$temp}°C\n";
        }

        if ($eveningForecast) {
            $temp = round($eveningForecast['main']['temp']);
            $condition = $eveningForecast['weather'][0]['description'];
            $response .= "- Buổi tối: $condition, {$temp}°C\n";
        }

        $response .= "\nChúc bạn có một ngày vui vẻ!";
        return $response;
    }

    /**
     * Trả về dự báo thời tiết cho nhiều ngày liên tiếp
     */
    private function getMultiDayForecast($message)
    {
        $dayCount = $this->parseForecastDaysCount($message);
        if ($dayCount > 5) {
            $dayCount = 5; // Giới hạn tối đa 5 ngày (do API hạn chế)
        }

        $response = $this->fetchWeatherData();

        if (!$response->successful()) {
            return "Không thể lấy dữ liệu thời tiết. Bạn muốn thử lại không?";
        }

        $forecasts = $response->json()['list'];

        // Nhóm dự báo theo ngày
        $forecastsByDay = [];
        foreach ($forecasts as $forecast) {
            $date = substr($forecast['dt_txt'], 0, 10);
            if (!isset($forecastsByDay[$date])) {
                $forecastsByDay[$date] = [];
            }
            $forecastsByDay[$date][] = $forecast;
        }

        // Giới hạn số lượng ngày theo yêu cầu
        $forecastsByDay = array_slice($forecastsByDay, 0, $dayCount, true);

        $result = "Chào bạn, dự báo thời tiết $dayCount ngày tới ở Biển Ba Động:\n\n";

        foreach ($forecastsByDay as $date => $dayForecasts) {
            $dateObj = Carbon::createFromFormat('Y-m-d', $date);
            $dateFormatted = $dateObj->format('d/m/Y');
            $dayName = $this->getVietnameseDayName($dateObj);

            // Tính toán nhiệt độ và tình trạng thời tiết
            $temps = array_column($dayForecasts, 'main');
            $tempMin = min(array_column($temps, 'temp_min'));
            $tempMax = max(array_column($temps, 'temp_max'));
            $mainCondition = $this->getMainCondition($dayForecasts);

            $result .= "📅 $dayName ($dateFormatted):\n";
            $result .= "- Thời tiết: $mainCondition\n";
            $result .= "- Nhiệt độ: {$tempMin}-{$tempMax}°C\n";

            // Thêm dự báo mưa nếu có
            $rainChance = $this->calculateRainChance($dayForecasts);
            if ($rainChance > 0) {
                $result .= "- Khả năng mưa: {$rainChance}%\n";
            }

            $result .= "\n";
        }

        $result .= "Lưu ý: Đây là dự báo và có thể thay đổi. Vui lòng kiểm tra lại trước khi ra ngoài.\n";
        $result .= "Chúc bạn có những ngày vui vẻ!";

        return $result;
    }

    /**
     * Phân tích số ngày dự báo từ tin nhắn
     */
    private function parseForecastDaysCount($message)
    {
        $message = mb_strtolower($message);

        // Tìm số ngày được đề cập trong tin nhắn
        preg_match('/(\d+)\s*ngày/', $message, $matches);
        if ($matches && isset($matches[1])) {
            return (int)$matches[1];
        }

        // Mặc định trả về 3 ngày nếu không xác định được
        if (strpos($message, 'tuần') !== false) {
            return 5; // Gần cả tuần
        }

        return 3; // Mặc định 3 ngày
    }

    /**
     * Tải dữ liệu thời tiết từ API
     */
    private function fetchWeatherData()
    {
        return Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'lat' => 9.2833,
            'lon' => 106.3167,
            'appid' => env('OPENWEATHER_API_KEY', '4985bdef25e1a09040126d292b131f89'),
            'units' => 'metric',
            'lang' => 'vi',
        ]);
    }

    /**
     * Tìm dự báo gần với giờ chỉ định nhất
     */
    private function findForecastByHour($forecasts, $hour)
    {
        $forecasts = array_values($forecasts);
        $closest = null;
        $closestDiff = 24;

        foreach ($forecasts as $forecast) {
            $forecastHour = (int)substr($forecast['dt_txt'], 11, 2);
            $diff = abs($forecastHour - $hour);

            if ($diff < $closestDiff) {
                $closest = $forecast;
                $closestDiff = $diff;
            }
        }

        return $closest;
    }

    /**
     * Xác định điều kiện thời tiết chính trong ngày
     */
    private function getMainCondition($forecasts)
    {
        $conditions = [];
        foreach ($forecasts as $forecast) {
            $condition = $forecast['weather'][0]['description'];
            if (!isset($conditions[$condition])) {
                $conditions[$condition] = 0;
            }
            $conditions[$condition]++;
        }

        arsort($conditions);
        return array_key_first($conditions);
    }

    /**
     * Tính độ ẩm trung bình
     */
    private function getAverageHumidity($forecasts)
    {
        $humidities = array_column(array_column($forecasts, 'main'), 'humidity');
        return round(array_sum($humidities) / count($humidities));
    }

    /**
     * Tính khả năng mưa
     */
    private function calculateRainChance($forecasts)
    {
        $rainCount = 0;
        foreach ($forecasts as $forecast) {
            if (
                isset($forecast['rain']) ||
                strpos($forecast['weather'][0]['description'], 'mưa') !== false ||
                strpos($forecast['weather'][0]['main'], 'Rain') !== false
            ) {
                $rainCount++;
            }
        }

        return round(($rainCount / count($forecasts)) * 100);
    }

    /**
     * Trả về tên thứ trong tuần bằng tiếng Việt
     */
    private function getVietnameseDayName($date)
    {
        $dayOfWeek = $date->dayOfWeek;
        $today = Carbon::today();

        if ($date->isSameDay($today)) {
            return "Hôm nay";
        } elseif ($date->isSameDay($today->copy()->addDay())) {
            return "Ngày mai";
        } elseif ($date->isSameDay($today->copy()->addDays(2))) {
            return "Ngày kia";
        }

        $days = [
            0 => 'Chủ nhật',
            1 => 'Thứ hai',
            2 => 'Thứ ba',
            3 => 'Thứ tư',
            4 => 'Thứ năm',
            5 => 'Thứ sáu',
            6 => 'Thứ bảy',
        ];

        return $days[$dayOfWeek];
    }

    /**
     * Phân tích ngày từ tin nhắn
     */
    private function parseDateFromMessage($message)
    {
        $message = mb_strtolower($message);

        // Từ khóa ngày
        if (strpos($message, 'hôm nay') !== false) {
            return Carbon::today();
        }
        if (strpos($message, 'ngày mai') !== false) {
            return Carbon::tomorrow();
        }
        if (strpos($message, 'ngày kia') !== false || strpos($message, 'ngày mốt') !== false) {
            return Carbon::today()->addDays(2);
        }
        if (strpos($message, 'hôm qua') !== false) {
            return Carbon::yesterday();
        }

        // Ngày theo thứ trong tuần
        $dayNames = [
            'chủ nhật' => 0,
            'thứ hai' => 1,
            'thứ ba' => 2,
            'thứ tư' => 3,
            'thứ năm' => 4,
            'thứ sáu' => 5,
            'thứ bảy' => 6
        ];

        foreach ($dayNames as $dayName => $dayOfWeek) {
            if (strpos($message, $dayName) !== false) {
                $today = Carbon::today();
                $today->addDays(1);
                while ($today->dayOfWeek != $dayOfWeek) {
                    $today->addDay();
                }
                return $today;
            }
        }

        // Ngày cụ thể (ví dụ: 20/5, 20-5, 20/05/2025)
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

        return Carbon::today(); // Mặc định trả về ngày hôm nay
    }
    private function handleRestaurantRecommendation($preferences)
    {
        $query = Restaurant::where('status', 'active');
        if (in_array('Ngân sách thấp', $preferences)) {
            $query->where('price_category', 'budget');
        }
        if (in_array('Hải sản', $preferences)) {
            $query->where('type', 'seafood');
        }
        $restaurant = $query->first();
        return $restaurant
            ? "Tôi gợi ý nhà hàng {$restaurant->name->vi} (Địa chỉ: {$restaurant->address->vi}, giá: {$restaurant->price_range}). Bạn muốn xem bản đồ không?"
            : "Không tìm thấy nhà hàng phù hợp. Bạn muốn thử tiêu chí khác không?";
    }

    private function handleHotelRecommendation($preferences)
    {
        $query = Hotel::where('status', 'active');
        if (in_array('Ngân sách thấp', $preferences)) {
            $query->where('type', 'budget');
        }
        $hotel = $query->first();
        return $hotel
            ? "Tôi gợi ý khách sạn {$hotel->name->vi} (Địa chỉ: {$hotel->address->vi}, giá: {$hotel->price_range}). Bạn muốn đặt phòng không?"
            : "Không tìm thấy khách sạn phù hợp. Bạn muốn thử tiêu chí khác không?";
    }

    private function handleAttractionInfo($message)
    {
        $keyword = str_replace(['Biển Ba Động', 'ở', 'có gì'], '', $message);
        $attraction = Attraction::whereRaw("name->>'vi' LIKE ?", ["%$keyword%"])
            ->where('status', 'active')
            ->first();
        return $attraction
            ? "Địa điểm {$attraction->name->vi}: {$attraction->description->vi}. Giờ mở cửa: {$attraction->opening_hours->vi}. Bạn cần bản đồ không?"
            : "Không tìm thấy địa điểm phù hợp. Bạn có thể cung cấp thêm thông tin không?";
    }

    private function handleMapRequest($message)
    {
        $keyword = str_replace(['bản đồ', 'vị trí', 'đến'], '', $message);
        $attraction = Attraction::whereRaw("name->>'vi' LIKE ?", ["%$keyword%"])
            ->where('status', 'active')
            ->first();
        if ($attraction) {
            $mapUrl = "https://tiles.goong.io/?lat={$attraction->latitude}&lng={$attraction->longitude}&zoom=15&api_key=" . env('GOONG_MAPTILES_API_KEY');
            return "Đây là vị trí của {$attraction->name->vi}: <a href='$mapUrl' target='_blank'>Xem bản đồ</a>";
        }
        return "Không tìm thấy địa điểm. Bạn có thể cung cấp tên cụ thể hơn không?";
    }
}
