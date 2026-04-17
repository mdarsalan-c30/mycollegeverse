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
        'average_package',
        'lowest_package',
        'highest_package',
        'verification_id',
        'reality_tags',
        'status',
    ];

    protected $casts = [
        'reality_tags' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'Former Citizen']);
    }

    public function college()
    {
        return $this->belongsTo(College::class)->withDefault(['name' => 'Legacy Institution']);
    }
}
