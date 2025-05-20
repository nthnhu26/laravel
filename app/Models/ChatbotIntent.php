<?php

// app/Models/ChatbotIntent.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotIntent extends Model
{
    protected $primaryKey = 'intent_id';
    protected $fillable = ['intent_name', 'description', 'sample_phrases'];
    protected $casts = ['sample_phrases' => 'array'];

    public function messages()
    {
        return $this->hasMany(ChatbotMessage::class, 'intent_id');
    }
}