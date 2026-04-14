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

    /**
     * Placement Analytics Hub 🛡️
     * Aggregates approved student reports to provide real-time salary intel.
     */
    public function getPlacementStatsAttribute()
    {
        $approvedReviews = $this->reviews()->where('status', 'approved');
        
        $count = $approvedReviews->whereNotNull('average_package')->count();
        
        if ($count === 0) {
            return [
                'avg' => 'Awaiting Intel',
                'min' => '---',
                'max' => '---',
                'count' => 0,
                'has_data' => false
            ];
        }

        return [
            'avg' => round($approvedReviews->avg('average_package'), 1) . ' LPA',
            'min' => round($approvedReviews->min('lowest_package'), 1) . ' LPA',
            'max' => round($approvedReviews->max('highest_package'), 1) . ' LPA',
            'count' => $count,
            'has_data' => true
        ];
    }
}
