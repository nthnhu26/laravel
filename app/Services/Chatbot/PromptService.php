<?php

namespace App\Services\Chatbot;

class PromptService
{
    private $weatherService;
    private $itineraryService;

    public function __construct(
        WeatherService $weatherService,
        ItineraryService $itineraryService
    ) {
        $this->weatherService = $weatherService;
        $this->itineraryService = $itineraryService;
    }

    public function generatePrompt($intent, $entities, $context = [])
    {
        $basePrompt = $this->getBasePrompt($intent);
        $contextPrompt = $this->getContextPrompt($context);
        $entityPrompt = $this->getEntityPrompt($entities);

        return $basePrompt . "\n\n" . $contextPrompt . "\n\n" . $entityPrompt;
    }

    private function getBasePrompt($intent)
    {
        $prompts = [
            'itinerary' => "Bạn là một trợ lý du lịch thông minh. Hãy tạo một lịch trình du lịch chi tiết dựa trên thông tin được cung cấp. Lịch trình nên bao gồm các hoạt động phù hợp với thời tiết và sở thích của người dùng.",
            'weather' => "Bạn là một chuyên gia thời tiết. Hãy phân tích và giải thích thông tin thời tiết được cung cấp một cách dễ hiểu và hữu ích cho người dùng.",
            'travel_advice' => "Bạn là một chuyên gia du lịch giàu kinh nghiệm. Hãy đưa ra những lời khuyên và gợi ý hữu ích dựa trên thông tin được cung cấp.",
            'accommodation' => "Bạn là một chuyên gia tư vấn về chỗ ở. Hãy đề xuất các lựa chọn phù hợp dựa trên ngân sách và sở thích của người dùng.",
            'restaurant' => "Bạn là một chuyên gia ẩm thực. Hãy đề xuất các nhà hàng và món ăn phù hợp với sở thích và ngân sách của người dùng.",
            'activity' => "Bạn là một chuyên gia về hoạt động giải trí. Hãy đề xuất các hoạt động phù hợp với sở thích và điều kiện thời tiết.",
            'general' => "Bạn là một trợ lý du lịch thông minh. Hãy trả lời câu hỏi của người dùng một cách hữu ích và thân thiện."
        ];
// Đảm bảo $intent là string
    if (is_array($intent)) {
        $intent = reset($intent);
    }
    if (!is_string($intent)) {
        $intent = 'general';
    }
        return $prompts[$intent] ?? $prompts['general'];
    }

    private function getContextPrompt($context)
    {
        if (empty($context)) {
            return "";
        }

        $prompt = "Thông tin bổ sung:\n";

        if (isset($context['weather'])) {
            $prompt .= "- Thời tiết hiện tại: " . $context['weather'] . "\n";
        }

        if (isset($context['location'])) {
            $prompt .= "- Địa điểm: " . $context['location'] . "\n";
        }

        if (isset($context['previous_messages'])) {
            $prompt .= "- Các tin nhắn trước đó:\n";
            foreach ($context['previous_messages'] as $message) {
                $prompt .= "  + " . $message . "\n";
            }
        }

        return $prompt;
    }

    private function getEntityPrompt($entities)
    {
        if (empty($entities)) {
            return "";
        }

        $prompt = "Thông tin chi tiết:\n";

        if (isset($entities['duration'])) {
            $prompt .= "- Thời gian: " . $entities['duration'] . " ngày\n";
        }

        if (isset($entities['preferences'])) {
            $prompt .= "- Sở thích: " . implode(", ", $entities['preferences']) . "\n";
        }

        if (isset($entities['budget'])) {
            $prompt .= "- Ngân sách: " . $entities['budget'] . "\n";
        }

        if (isset($entities['start_date'])) {
            $prompt .= "- Ngày bắt đầu: " . $entities['start_date'] . "\n";
        }

        if (isset($entities['location'])) {
            $prompt .= "- Địa điểm: " . $entities['location'] . "\n";
        }

        return $prompt;
    }

    public function generateMissingInfoPrompt($missingInfo)
    {
        $prompts = [
            'duration' => "Bạn muốn đi du lịch trong bao lâu? (Ví dụ: 3 ngày, 1 tuần)",
            'preferences' => "Bạn có sở thích đặc biệt nào không? (Ví dụ: ẩm thực, văn hóa, thiên nhiên)",
            'budget' => "Ngân sách của bạn là bao nhiêu? (Ví dụ: thấp, trung bình, cao)",
            'start_date' => "Bạn muốn bắt đầu chuyến đi vào ngày nào?"
        ];

        $prompt = "Để tôi có thể giúp bạn tốt hơn, vui lòng cung cấp thêm thông tin:\n";
        foreach ($missingInfo as $info) {
            if (isset($prompts[$info])) {
                $prompt .= "- " . $prompts[$info] . "\n";
            }
        }

        return $prompt;
    }

    public function formatItineraryResponse($itinerary)
    {
        $response = "Tôi đã tạo lịch trình du lịch cho bạn:\n\n";

        foreach ($itinerary['days'] as $day) {
            $response .= "Ngày {$day['day_number']}:\n";

            foreach ($day['activities'] as $activity) {
                $response .= "- {$activity['time']}: {$activity['name']}\n";
                if (!empty($activity['description'])) {
                    $response .= "  {$activity['description']}\n";
                }
            }

            if (!empty($day['restaurant'])) {
                $response .= "\nNhà hàng gợi ý: {$day['restaurant']['name']}\n";
            }

            if (!empty($day['hotel'])) {
                $response .= "Khách sạn: {$day['hotel']['name']}\n";
            }

            $response .= "\n";
        }

        return $response;
    }

    public function formatWeatherResponse($forecast)
    {
        $response = "Dự báo thời tiết:\n\n";

        foreach ($forecast as $day) {
            $response .= "{$day['date']}:\n";
            $response .= "- Nhiệt độ: {$day['temperature']}°C\n";
            $response .= "- Thời tiết: {$day['description']}\n";
            $response .= "- Khả năng mưa: {$day['rain_chance']}%\n\n";
        }

        return $response;
    }

    public function getTravelAdvicePrompt($location)
    {
        return "Bạn là một chuyên gia du lịch giàu kinh nghiệm. Hãy đưa ra những lời khuyên và gợi ý hữu ích dựa trên thông tin được cung cấp. Bao gồm:\n" .
            "1. Thời điểm tốt nhất để đến thăm\n" .
            "2. Cách di chuyển\n" .
            "3. Những điều cần lưu ý\n" .
            "4. Các hoạt động nên thử\n" .
            "5. Các địa điểm nên ghé thăm";
    }

    public function formatHotelRecommendations($hotels)
    {
        if (empty($hotels)) {
            return "Xin lỗi, tôi không tìm thấy khách sạn phù hợp với yêu cầu của bạn.";
        }

        $response = "Dưới đây là một số khách sạn phù hợp:\n\n";

        foreach ($hotels as $hotel) {
            $response .= "{$hotel->name['vi']}:\n";
            $response .= "- Địa chỉ: {$hotel->address['vi']}\n";
            $response .= "- Giá: {$hotel->price_range}\n";
            $response .= "- Đánh giá: {$hotel->rating}/5\n";

            if (!empty($hotel->description['vi'])) {
                $response .= "- Mô tả: {$hotel->description['vi']}\n";
            }

            $response .= "\n";
        }

        return $response;
    }

    public function formatRestaurantRecommendations($restaurants)
    {
        if (empty($restaurants)) {
            return "Xin lỗi, tôi không tìm thấy nhà hàng phù hợp với yêu cầu của bạn.";
        }

        $response = "Dưới đây là một số nhà hàng phù hợp:\n\n";

        foreach ($restaurants as $restaurant) {
            $response .= "{$restaurant->name['vi']}:\n";
            $response .= "- Địa chỉ: {$restaurant->address['vi']}\n";
            $response .= "- Loại ẩm thực: {$restaurant->type}\n";
            $response .= "- Giá: {$restaurant->price_category}\n";
            $response .= "- Đánh giá: {$restaurant->rating}/5\n";

            if (!empty($restaurant->description['vi'])) {
                $response .= "- Mô tả: {$restaurant->description['vi']}\n";
            }

            $response .= "\n";
        }

        return $response;
    }

    public function getActivityPrompt($location)
    {
        return "Bạn là một chuyên gia về hoạt động giải trí. Hãy đề xuất các hoạt động phù hợp với sở thích và điều kiện thời tiết. Bao gồm:\n" .
            "1. Các hoạt động ngoài trời\n" .
            "2. Các hoạt động văn hóa\n" .
            "3. Các hoạt động giải trí\n" .
            "4. Các hoạt động ẩm thực\n" .
            "5. Các hoạt động phù hợp với gia đình";
    }

    public function getGeneralQueryPrompt($entities)
    {
        $context = "";
        if (!empty($entities['location'])) {
            $context .= "Địa điểm: {$entities['location']}\n";
        }
        if (!empty($entities['preferences'])) {
            $context .= "Sở thích: " . implode(", ", $entities['preferences']) . "\n";
        }
        if (!empty($entities['budget'])) {
            $context .= "Ngân sách: {$entities['budget']}\n";
        }

        return "Bạn là một trợ lý du lịch thông minh. Hãy trả lời câu hỏi của người dùng dựa trên thông tin trên.\n\n{$context}";
    }
}
