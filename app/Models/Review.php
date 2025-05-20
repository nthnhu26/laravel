<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'review_id';

    protected $fillable = ['user_id', 'entity_type', 'entity_id', 'rating', 'comment', 'status'];

    // Quan hệ: Đánh giá thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewable()
{
    return $this->morphTo('reviewable', 'entity_type', 'entity_id');
}
    
}