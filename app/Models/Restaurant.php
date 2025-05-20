<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\HasImages;
use App\Traits\HasReviews;

class Restaurant extends Model
{
    use HasTranslations, HasImages, HasReviews;

    protected $table = 'restaurants';
    protected $primaryKey = 'restaurant_id';

    public $translatable = ['name', 'description', 'address', 'opening_hours', 'cancellation_policy'];

    protected $fillable = [
        'name',
        'type',
        'price_category',
        'provider_id',
        'is_admin_managed',
        'description',
        'address',
        'contact_info',
        'latitude',
        'longitude',
        'opening_hours',
        'price_range',
        'cancellation_policy',
        'status'
    ];

    protected $casts = [
        'name' => 'json',
        'is_admin_managed' => 'boolean',
        'contact_info' => 'json',
        'description' => 'json',
        'address' => 'json',
        'opening_hours' => 'json',
        'cancellation_policy' => 'json'
    ];
    // Quan hệ: Nhà hàng thuộc về một nhà cung cấp
    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'provider_id');
    }

    // Quan hệ: Nhà hàng có nhiều món ăn
    public function dishes()
    {
        return $this->hasMany(Dish::class, 'restaurant_id');
    }
    public function getMorphClass()
    {
        return 'restaurant';
    }

    // public function amenities()
    // {
    //     return $this->morphMany(AmenityEntity::class, 'entity', 'entity_type', 'entity_id')->where('entity_type', 'restaurant');
    // }
    // public function images()
    // {
    //     return $this->morphMany(Image::class, 'imageable', 'entity_type', 'entity_id');
    // }
    public function amenities()
    {
        return $this->morphToMany(Amenity::class, 'entity', 'amenity_entity', 'entity_id', 'amenity_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'entity', 'entity_type', 'entity_id');
    }
    // Quan hệ: Nhà hàng có nhiều đặt chỗ
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_id')->where('service_type', 'restaurant');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'entity', 'entity_type', 'entity_id')->where('entity_type', 'restaurant');
    }
}
