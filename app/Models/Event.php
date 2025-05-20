<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\HasReviews;

class Event extends Model
{
    use HasTranslations, HasReviews;

    protected $table = 'events';
    protected $primaryKey = 'event_id';

    public $translatable = ['title', 'description', 'location'];

    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'location',
        'attraction_id', 'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getMorphClass()
    {
        return 'event';
    }

    public function attraction()
    {
        return $this->belongsTo(Attraction::class, 'attraction_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable', 'entity_type', 'entity_id');
    }
}