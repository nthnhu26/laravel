<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Room extends Model
{
    use HasTranslations;

    protected $table = 'rooms';
    protected $primaryKey = 'room_id';

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'hotel_id', 'name', 'description', 'price_per_night', 'area', 'capacity', 'bed_type', 'status'
    ];

    // Quan hệ: Phòng thuộc về một khách sạn
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    // Quan hệ: Phòng có nhiều tiện ích
    public function amenities()
    {
        return $this->hasManyThrough(Amenity::class, AmenityEntity::class, 'entity_id', 'amenity_id', 'room_id', 'amenity_id')
            ->where('entity_type', 'room');
    }

    // Quan hệ: Phòng có nhiều hình ảnh
    public function images()
    {
        return $this->hasMany(Image::class, 'entity_id')->where('entity_type', 'room');
    }

    // Quan hệ: Phòng có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class, 'entity_id')->where('entity_type', 'room');
    }
}