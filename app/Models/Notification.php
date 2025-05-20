<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';

    protected $fillable = ['user_id', 'type', 'message', 'is_read', 'related_entity_type', 'related_entity_id'];

    // Quan hệ: Thông báo thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}