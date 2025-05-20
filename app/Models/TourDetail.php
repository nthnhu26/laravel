<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TourDetail extends Model
{
    use HasTranslations;

    protected $table = 'tour_details';
    protected $primaryKey = 'tour_detail_id';

    public $translatable = ['description'];

    protected $fillable = [
        'tour_id', 'day_number', 'attraction_id', 'description'
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function attraction()
    {
        return $this->belongsTo(Attraction::class, 'attraction_id');
    }
}