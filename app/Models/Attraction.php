<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\HasImages;
use App\Traits\HasReviews;

class Attraction extends Model
{
    use HasTranslations, HasImages, HasReviews;

    protected $table = 'attractions';
    protected $primaryKey = 'attraction_id';

    public $translatable = ['name', 'description', 'address', 'opening_hours'];

    protected $fillable = [
        'name',
        'type',
        'description',
        'address',
        'latitude',
        'longitude',
        'opening_hours',
        'status'
    ];

    public function amenities()
    {
        return $this->morphToMany(
            Amenity::class,
            'entity',
            'amenity_entity',
            'entity_id',
            'amenity_id'
        );
    }
    public function getMorphClass()
    {
        return 'attraction';
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable', 'entity_type', 'entity_id');
    }

    public function tourDetails()
    {
        return $this->hasMany(TourDetail::class, 'attraction_id');
    }

    // Quan hệ: Địa điểm tham quan có nhiều sự kiện
    public function events()
    {
        return $this->hasMany(Event::class, 'attraction_id');
    }
}
