<?php

// app/Models/ChatbotMessage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotMessage extends Model
{
    protected $primaryKey = 'message_id';
    protected $fillable = ['conversation_id', 'is_from_user', 'message', 'intent_id', 'entities'];
    protected $casts = ['entities' => 'array'];

    public function conversation()
    {
        return $this->belongsTo(ChatbotConversation::class, 'conversation_id');
    }

    public function intent()
    {
        return $this->belongsTo(ChatbotIntent::class, 'intent_id');
    }
}