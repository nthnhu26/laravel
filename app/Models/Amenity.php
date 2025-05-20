<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Amenity extends Model
{
    use HasTranslations;

    protected $table = 'amenities';
    protected $primaryKey = 'amenity_id';

    public $translatable = ['name', 'description'];

    protected $fillable = ['name', 'description', 'icon'];

    public function entities()
    {
        return $this->morphedByMany(
            Attraction::class,
            'entity',
            'amenity_entity',
            'amenity_id',
            'entity_id'
        );
    }
    
    public function amenityEntities()
    {
        return $this->hasMany(AmenityEntity::class, 'amenity_id');
    }

    
}
