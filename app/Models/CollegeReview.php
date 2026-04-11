<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'college_id',
        'campus_rating',
        'faculty_rating',
        'academic_rating',
        'comment',
        'verification_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}
