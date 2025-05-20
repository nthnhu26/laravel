<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'booking_id';
    protected $table = 'bookings';

    protected $fillable = [
        'user_id', 'service_type', 'service_id', 'room_id', 'booking_date',
        'start_date', 'end_date', 'number_of_people', 'special_requests', 'booking_code', 'status'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'booking_id');
    }
}