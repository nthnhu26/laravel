<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    protected $table = 'analytics';
    protected $primaryKey = 'analytic_id';

    protected $fillable = [
        'entity_type',
        'entity_id',
        'user_id',
        'action_type',
        'ip_address',
        'device_type',
        'country_code',
        'city',
        'page_url',
        'session_id'
    ];

    // Quan hệ: Thống kê thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
