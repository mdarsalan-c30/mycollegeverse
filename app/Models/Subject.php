<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'course_id',
        'semester',
        'course', // Legacy fallback to prevent 500 errors
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
