<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'department', 'college_id', 'profile_pic', 'slug'];

    /**
     * Use slugs instead of IDs for public routing.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function college()
    {
        return $this->belongsTo(College::class)->withDefault([
            'name' => 'Legacy Institution'
        ]);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }
}
