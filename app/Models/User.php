<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable
{
    use Notifiable, HasTranslations;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    public $translatable = ['full_name'];

    protected $fillable = [
        'email',
        'password',
        'google_id',
        'full_name',
        'phone',
        'avatar',
        'role',
        'status',
        'ban_reason',
        'banned_until',
        'email_verified_at',
        'remember_token'
    ];

    protected $hidden = ['password', 'remember_token'];

    // Quan hệ: Một người dùng có nhiều sở thích
    public function preferences()
    {
        return $this->hasMany(UserPreference::class, 'user_id');
    }

    // Quan hệ: Một người dùng có thể là nhà cung cấp
    public function serviceProvider()
    {
        return $this->hasOne(ServiceProvider::class, 'user_id');
    }

    // Quan hệ: Một người dùng có nhiều đặt chỗ
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    // Quan hệ: Một người dùng có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }
    // Quan hệ: Một người dùng có nhiều lịch trình
    public function itineraries()
    {
        return $this->hasMany(Itinerary::class, 'user_id');
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    // Quan hệ: Một người dùng có nhiều bài viết
    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    // Quan hệ: Một người dùng có nhiều bình luận bài viết
    public function postComments()
    {
        return $this->hasMany(PostComment::class, 'user_id');
    }

    // Quan hệ: Một người dùng có nhiều thông báo
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }
    public function recentNotifications()
    {
        return $this->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    // Quan hệ: Một người dùng có nhiều phiên hội thoại chatbot
    public function chatbotConversations()
    {
        return $this->hasMany(ChatbotConversation::class, 'user_id');
    }
}
