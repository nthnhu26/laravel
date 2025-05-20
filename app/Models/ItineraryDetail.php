<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryDetail extends Model
{
    protected $table = 'itinerary_details';
    protected $primaryKey = 'itinerary_detail_id';

    protected $fillable = ['itinerary_id', 'entity_type', 'entity_id', 'visit_date', 'notes', 'estimated_cost'];

    // Quan hệ: Chi tiết lịch trình thuộc về một lịch trình
    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class, 'itinerary_id');
    }

    public function entity()
    {
        return $this->morphTo();
    }
}