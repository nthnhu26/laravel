<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotIntent;
use App\Models\ChatbotMessage;
use App\Models\ChatbotConversation;

class IntentService
{
    private $intents = [
        'itinerary' => [
            'patterns' => [
                'tạo lịch trình',
                'lên kế hoạch',
                'lịch trình du lịch',
                'kế hoạch du lịch',
                'đi du lịch',
                'thăm quan',
                'khám phá'
            ],
            'required_info' => [
                'duration' => 'Bạn muốn đi du lịch trong bao lâu? (Ví dụ: 3 ngày, 1 tuần)',
                'preferences' => 'Bạn có sở thích đặc biệt nào không? (Ví dụ: ẩm thực, văn hóa, thiên nhiên)',
                'budget' => 'Ngân sách của bạn là bao nhiêu? (Ví dụ: thấp, trung bình, cao)',
                'start_date' => 'Bạn muốn bắt đầu chuyến đi vào ngày nào?'
            ]
        ],
        'weather' => [
            'patterns' => [
                'thời tiết',
                'nhiệt độ',
                'mưa',
                'nắng',
                'dự báo thời tiết',
                'khí hậu'
            ]
        ],
        'travel_advice' => [
            'patterns' => [
                'tư vấn',
                'lời khuyên',
                'kinh nghiệm',
                'mẹo',
                'hướng dẫn',
                'cần biết',
                'lưu ý'
            ]
        ],
        'accommodation' => [
            'patterns' => [
                'khách sạn',
                'nhà nghỉ',
                'resort',
                'homestay',
                'chỗ ở',
                'lưu trú',
                'đặt phòng'
            ]
        ],
        'restaurant' => [
            'patterns' => [
                'nhà hàng',
                'quán ăn',
                'ẩm thực',
                'đồ ăn',
                'món ăn',
                'đặc sản',
                'địa điểm ăn uống'
            ]
        ],
        'activity' => [
            'patterns' => [
                'hoạt động',
                'giải trí',
                'vui chơi',
                'thể thao',
                'tour',
                'trải nghiệm',
                'khám phá'
            ]
        ]
    ];

    public function detectIntent($message)
    {
        $message = mb_strtolower($message, 'UTF-8');
        $maxConfidence = 0;
        $detectedIntent = 'general';

        foreach ($this->intents as $intent => $config) {
            $confidence = $this->calculateConfidence($message, $config['patterns']);
            if ($confidence > $maxConfidence) {
                $maxConfidence = $confidence;
                $detectedIntent = $intent;
            }
        }

        return [
            'intent' => $detectedIntent,
            'confidence' => $maxConfidence
        ];
    }

    public function extractEntities($message)
    {
        $entities = [];
        $message = mb_strtolower($message, 'UTF-8');

        // Trích xuất thời gian
        $duration = $this->extractDuration($message);
        if ($duration) {
            $entities['duration'] = $duration;
        }

        // Trích xuất sở thích
        $preferences = $this->extractPreferences($message);
        if (!empty($preferences)) {
            $entities['preferences'] = $preferences;
        }

        // Trích xuất ngân sách
        $budget = $this->extractBudget($message);
        if ($budget) {
            $entities['budget'] = $budget;
        }

        // Trích xuất ngày bắt đầu
        $startDate = $this->extractStartDate($message);
        if ($startDate) {
            $entities['start_date'] = $startDate;
        }

        // Trích xuất địa điểm
        $location = $this->extractLocation($message);
        if ($location) {
            $entities['location'] = $location;
        }

        return $entities;
    }

    public function checkRequiredInfo($entities)
    {
        $intent = $this->detectIntent($entities['message'] ?? '')['intent'];
        if ($intent !== 'itinerary') {
            return [];
        }

        $missingInfo = [];
        foreach ($this->intents['itinerary']['required_info'] as $field => $prompt) {
            if (!isset($entities[$field])) {
                $missingInfo[] = $field;
            }
        }

        return $missingInfo;
    }

    private function calculateConfidence($message, $patterns)
    {
        $maxConfidence = 0;
        foreach ($patterns as $pattern) {
            $confidence = $this->calculatePatternConfidence($message, $pattern);
            $maxConfidence = max($maxConfidence, $confidence);
        }
        return $maxConfidence;
    }

    private function calculatePatternConfidence($message, $pattern)
    {
        $pattern = mb_strtolower($pattern, 'UTF-8');

        // Kiểm tra trùng khớp chính xác
        if ($message === $pattern) {
            return 1.0;
        }

        // Kiểm tra chứa pattern
        if (strpos($message, $pattern) !== false) {
            return 0.8;
        }

        // Kiểm tra độ tương đồng
        $similarity = similar_text($message, $pattern, $percent);
        return $percent / 100;
    }

    private function extractDuration($message)
    {
        $patterns = [
            '/(\d+)\s*(?:ngày|day)/u' => function ($matches) {
                return (int) $matches[1];
            },
            '/(\d+)\s*(?:tuần|week)/u' => function ($matches) {
                return (int) $matches[1] * 7;
            }
        ];

        foreach ($patterns as $pattern => $callback) {
            if (preg_match($pattern, $message, $matches)) {
                return $callback($matches);
            }
        }

        return null;
    }

    private function extractPreferences($message)
    {
        $preferences = [];
        $preferencePatterns = [
            'ẩm thực' => 'cuisine',
            'văn hóa' => 'culture',
            'thiên nhiên' => 'nature',
            'biển' => 'beach',
            'núi' => 'mountain',
            'lịch sử' => 'history',
            'mua sắm' => 'shopping',
            'giải trí' => 'entertainment'
        ];

        foreach ($preferencePatterns as $pattern => $preference) {
            if (strpos($message, $pattern) !== false) {
                $preferences[] = $preference;
            }
        }

        return $preferences;
    }

    private function extractBudget($message)
    {
        $budgetPatterns = [
            'thấp' => 'low',
            'rẻ' => 'low',
            'tiết kiệm' => 'low',
            'trung bình' => 'medium',
            'vừa phải' => 'medium',
            'cao' => 'high',
            'đắt' => 'high',
            'sang trọng' => 'high'
        ];

        foreach ($budgetPatterns as $pattern => $budget) {
            if (strpos($message, $pattern) !== false) {
                return $budget;
            }
        }

        return null;
    }

    private function extractStartDate($message)
    {
        $patterns = [
            '/(\d{1,2})\/(\d{1,2})\/(\d{4})/u' => function ($matches) {
                return "{$matches[3]}-{$matches[2]}-{$matches[1]}";
            },
            '/(\d{1,2})\/(\d{1,2})/u' => function ($matches) {
                $year = date('Y');
                return "{$year}-{$matches[2]}-{$matches[1]}";
            },
            '/(?:ngày|hôm)\s+(\d{1,2})/u' => function ($matches) {
                $day = (int) $matches[1];
                $month = (int) date('m');
                $year = (int) date('Y');
                return "{$year}-{$month}-{$day}";
            }
        ];

        foreach ($patterns as $pattern => $callback) {
            if (preg_match($pattern, $message, $matches)) {
                return $callback($matches);
            }
        }

        return null;
    }

    private function extractLocation($message)
    {
        $locations = [
            'biển ba động',
            'ba động',
            'bãi biển ba động'
        ];

        foreach ($locations as $location) {
            if (strpos($message, $location) !== false) {
                return $location;
            }
        }

        return null;
    }
}
