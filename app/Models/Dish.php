<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Dish extends Model
{
    use HasTranslations;

    protected $table = 'dishes';
    protected $primaryKey = 'dish_id';

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'name', 'restaurant_id', 'description', 'price', 'price_range', 'dish_type', 'status'
    ];

    // Quan hệ: Món ăn thuộc về một nhà hàng
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    // Quan hệ: Món ăn có nhiều hình ảnh
    public function images()
    {
        return $this->hasMany(Image::class, 'entity_id')->where('entity_type', 'dish');
    }

    // Quan hệ: Món ăn có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class, 'entity_id')->where('entity_type', 'dish');
    }
}