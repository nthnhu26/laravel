<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\HasImages;
use App\Traits\HasReviews;

class Hotel extends Model
{
    use HasTranslations, HasImages, HasReviews;

    protected $table = 'hotels';

    protected $primaryKey = 'hotel_id';

    public $translatable = ['name', 'description', 'address', 'cancellation_policy'];

    protected $fillable = [
        'name',
        'type',
        'provider_id',
        'is_admin_managed',
        'description',
        'address',
        'contact_info',
        'latitude',
        'longitude',
        'price_range',
        'check_in_time',
        'check_out_time',
        'cancellation_policy',
        'status'
    ];

    protected $casts = [
        'name' => 'json',
        'is_admin_managed' => 'boolean',
        'contact_info' => 'json',
        'description' => 'json',
        'address' => 'json',
        'cancellation_policy' => 'json'

    ];


    public function getMorphClass()
    {
        return 'hotel';
    }

    // Quan hệ: Khách sạn thuộc về một nhà cung cấp
    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'provider_id', 'provider_id');
    }

    // Quan hệ: Khách sạn có nhiều phòng
    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id');
    }

    // Quan hệ: Khách sạn có nhiều tiện ích
    // public function amenities()
    // {
    //     return $this->hasManyThrough(Amenity::class, AmenityEntity::class, 'entity_id', 'amenity_id', 'hotel_id', 'amenity_id')
    //         ->where('entity_type', 'hotel');
    // }

    // // Quan hệ: Khách sạn có nhiều hình ảnh
    // public function images()
    // {
    //     return $this->hasMany(Image::class, 'entity_id')->where('entity_type', 'hotel');
    // }

    public function amenities()
    {
        return $this->morphToMany(Amenity::class, 'entity', 'amenity_entity', 'entity_id', 'amenity_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'entity', 'entity_type', 'entity_id');
    }

    // Quan hệ: Khách sạn có nhiều đặt chỗ
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_id')->where('service_type', 'hotel');
    }
}
