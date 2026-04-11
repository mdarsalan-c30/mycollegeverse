<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'description',
        'thumbnail_url',
        'student_count',
        'rating',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function reviews()
    {
        return $this->hasMany(CollegeReview::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function professors()
    {
        return $this->hasMany(Professor::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
