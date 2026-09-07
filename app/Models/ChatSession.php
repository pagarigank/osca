<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    protected $fillable = ['guest_identifier', 'guest_name', 'guest_email', 'status'];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
