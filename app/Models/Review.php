<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'professor_id', 'rating', 'comment', 'status', 'tags', 'unit_focus', 'internal_difficulty'];

    protected $casts = [
        'tags' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Former Citizen',
            'username' => 'citizen',
            'profile_photo_url' => 'https://ui-avatars.com/api/?name=Former+Citizen&background=E2E8F0&color=475569'
        ]);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class)->withDefault([
            'name' => 'Legacy Advisor'
        ]);
    }
}
