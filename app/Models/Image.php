<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Image extends Model
{
    use HasTranslations;

    protected $table = 'images';
    protected $primaryKey = 'image_id';

    public $translatable = ['caption'];

    protected $fillable = ['entity_type', 'entity_id', 'url', 'caption', 'is_featured'];

    // public function imageable()
    // {
    //     return $this->morphTo('imageable', 'entity_type', 'entity_id');
    // }
    public function entity()
    {
        return $this->morphTo();
    }
}