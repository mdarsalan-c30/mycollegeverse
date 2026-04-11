<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'message', 'is_read', 'type', 'image_path'];

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return app(\App\Services\ImageKitService::class)->getUrl($this->image_path, ['w' => 800, 'q' => 80]);
        }
        return null;
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
