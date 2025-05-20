<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\HasReviews;

class Transport extends Model
{
    use HasTranslations, HasReviews;

    protected $table = 'transports';
    protected $primaryKey = 'transport_id';

    public $translatable = ['name', 'description', 'address'];

    protected $fillable = [
        'name',
        'type',
        'provider_id',
        'is_admin_managed',
        'contact_info',
        'capacity',
        'price_per_day',
        'status'
    ];
    public function getMorphClass()
    {
        return 'transport';
    }

    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'provider_id');
    }

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

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable', 'entity_type', 'entity_id');
    }
}
