<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotConversation extends Model
{
    protected $table = 'chatbot_conversations';
    protected $primaryKey = 'conversation_id';

    protected $fillable = ['session_id', 'user_id', 'started_at', 'ended_at'];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
    
    // Quan hệ: Phiên hội thoại thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ: Phiên hội thoại có nhiều tin nhắn
    public function messages()
    {
        return $this->hasMany(ChatbotMessage::class, 'conversation_id');
    }
}