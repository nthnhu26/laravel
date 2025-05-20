<?php
// File: app/Models/SearchHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    // Chỉ định tên bảng
    protected $table = 'search_histories';

    // Chỉ định khóa chính
    protected $primaryKey = 'search_id';

    // Các trường có thể điền
    protected $fillable = ['user_id', 'keyword', 'filters', 'is_guest'];

    // Quan hệ với bảng users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Quan hệ với bảng search_results
    public function results()
    {
        return $this->hasMany(SearchResult::class, 'search_id', 'search_id');
    }
}