<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessorRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'professor_name',
        'department',
        'college_name',
        'message',
        'profile_photo_url',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
