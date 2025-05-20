<?php

// app/Models/SearchResult.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchResult extends Model
{
    protected $table = 'search_results';
    protected $primaryKey = 'search_result_id';
    protected $fillable = ['search_id', 'entity_type', 'entity_id'];
    public $timestamps = false;
    public function search()
    {
        return $this->belongsTo(SearchHistory::class);
    }
}
