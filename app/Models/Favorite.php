<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorites';
    protected $primaryKey = 'favorite_id';

    protected $fillable = ['user_id', 'entity_type', 'entity_id'];

    // Quan hệ: Yêu thích thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function favorable()
    {
        return $this->morphTo();
    }
}