<?php

namespace App\Traits;

use App\Models\Review;

trait HasReviews
{
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable', 'entity_type', 'entity_id');
    }

    // Tính trung bình đánh giá
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }
}
