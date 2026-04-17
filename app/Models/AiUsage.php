<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'model',
        'type',
        'topic',
        'prompt_tokens',
        'candidates_tokens',
        'total_tokens',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];
}
