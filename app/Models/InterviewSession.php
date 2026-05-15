<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'role', 'transcript', 'score', 'feedback', 'status'];

    protected $casts = [
        'transcript' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
