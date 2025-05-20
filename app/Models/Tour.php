<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\HasReviews;

class Tour extends Model
{
    use HasTranslations, HasReviews;

    protected $table = 'tours';
    protected $primaryKey = 'tour_id';

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'type',
        'provider_id',
        'is_admin_managed',
        'description',
        'contact_info',
        'duration_days',
        'price',
        'max_people',
        'status'
    ];

    protected $casts = [
        'is_admin_managed' => 'boolean',
        'duration_days' => 'integer',
        'price' => 'float',
        'max_people' => 'integer',
    ];

    public function getMorphClass()
    {
        return 'tour';
    }

    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'provider_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tourDetails()
    {
        return $this->hasMany(TourDetail::class, 'tour_id');
    }

    // public function amenities()
    // {
    //     return $this->morphToMany(
    //         Amenity::class,
    //         'entity',
    //         'amenity_entity',
    //         'entity_id',
    //         'amenity_id'
    //     );
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
}
