<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $table = 'itineraries';
    protected $primaryKey = 'itinerary_id';

    protected $fillable = ['user_id', 'title', 'start_date', 'end_date', 'share_token'];

    // Quan hệ: Lịch trình thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ: Lịch trình có nhiều chi tiết
    public function details()
    {
        return $this->hasMany(ItineraryDetail::class, 'itinerary_id');
    }
}