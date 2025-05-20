<?php
//—even/seeders/ChatbotIntentSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatbotIntent;

class ChatbotIntentSeeder extends Seeder
{
    public function run()
    {
        $defaultIntents = [
            [
                'intent_name' => 'weather_query',
                'description' => 'Hỏi về thời tiết',
                'sample_phrases' => [
                    'Thời tiết ở Biển Ba Động hôm nay?',
                    'Mai trời có mưa không?',
                    'Thời tiết ngày tới',
                ],
            ],
            [
                'intent_name' => 'restaurant_recommendation',
                'description' => 'Gợi ý nhà hàng',
                'sample_phrases' => [
                    'Tìm nhà hàng hải sản giá rẻ',
                    'Nhà hàng nào ngon ở Ba Động?',
                ],
            ],
            [
                'intent_name' => 'hotel_recommendation',
                'description' => 'Gợi ý khách sạn',
                'sample_phrases' => [
                    'Khách sạn giá rẻ gần biển',
                    'Tìm homestay ở Ba Động',
                ],
            ],
            [
                'intent_name' => 'attraction_info',
                'description' => 'Hỏi thông tin địa điểm',
                'sample_phrases' => [
                    'Biển Ba Động có gì chơi?',
                    'Giờ mở cửa của chợ Ba Động?',
                ],
            ],
            [
                'intent_name' => 'map_request',
                'description' => 'Yêu cầu bản đồ',
                'sample_phrases' => [
                    'Bản đồ đến Biển Ba Động',
                    'Vị trí nhà hàng Hải Sản Tươi Ngon',
                ],
            ],
        ];

        foreach ($defaultIntents as $intent) {
            ChatbotIntent::updateOrCreate(
                ['intent_name' => $intent['intent_name']],
                [
                    'description' => $intent['description'],
                    'sample_phrases' => $intent['sample_phrases'],
                ]
            );
        }
    }
}
//php artisan db:seed --class=ChatbotIntentSeeder