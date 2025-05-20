<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmenityEntity extends Model
{
    protected $table = 'amenity_entity';
    protected $primaryKey = 'amenity_entity_id';

    protected $fillable = ['amenity_id', 'entity_type', 'entity_id'];

    // Quan hệ: Liên kết thuộc về một tiện ích
    public function amenity()
    {
        return $this->belongsTo(Amenity::class, 'amenity_id');
    }

    public function entity()
    {
        return $this->morphTo();
    }
}