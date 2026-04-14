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
        'type',
        'streams',
        'location',
        'state',
        'city',
        'description',
        'thumbnail_url',
        'student_count',
        'rating',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
        'streams' => 'array',
    ];

    /**
     * Aesthetic Resilience Hub 🛰️
     * Returns a premium academic visual if the database link is missing.
     */
    public function getThumbnailUrlAttribute($value)
    {
        if (!empty($value) && !str_contains($value, 'placeholder.com')) {
            return $value;
        }

        // Curated "Multiverse Aesthetic" Fallback nodes
        $fallbacks = [
            'https://images.unsplash.com/photo-1541339907198-e08756ebafe3?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1523050853064-8504f2f40058?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1621640100002-4c99b2446713?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1541829070764-84a7d30dee6b?q=80&w=800&auto=format&fit=crop',
        ];

        // Seeding the random selection with the college id for consistency 🌌
        return $fallbacks[$this->id % count($fallbacks)];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Institutional Identity Hub 🛡️
     * Returns the dynamic average rating or a recruitment status.
     */
    public function getAverageRatingAttribute()
    {
        $approvedReviews = $this->reviews()->where('status', 'approved');
        
        if ($approvedReviews->count() === 0) {
            return "Yet to Review";
        }

        $campus = $approvedReviews->avg('campus_rating');
        $faculty = $approvedReviews->avg('faculty_rating');
        $academic = $approvedReviews->avg('academic_rating');

        return round(($campus + $faculty + $academic) / 3, 1);
    }

    /**
     * Rating Synchronizer 🛰️
     * Updates the cached 'rating' column for high-performance listing.
     */
    public function syncRating()
    {
        $approvedReviews = $this->reviews()->where('status', 'approved');
        
        if ($approvedReviews->count() === 0) {
            $this->update(['rating' => 0.0]);
            return;
        }

        $campus = $approvedReviews->avg('campus_rating');
        $faculty = $approvedReviews->avg('faculty_rating');
        $academic = $approvedReviews->avg('academic_rating');

        $avg = round(($campus + $faculty + $academic) / 3, 1);
        
        $this->update(['rating' => $avg]);
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

    /**
     * Council Intelligence Hub 🛡️
     * Aggregates diverse rating signals into normalized percentages.
     */
    public function getAcademicMetricsAttribute()
    {
        $approvedReviews = $this->reviews()->where('status', 'approved');
        
        if ($approvedReviews->count() === 0) {
            return [
                ['label' => 'Campus Culture', 'percent' => 0, 'text' => 'Awaiting Hub Intel'],
                ['label' => 'Faculty Quality', 'percent' => 0, 'text' => 'N/A'],
                ['label' => 'Academic Rigor', 'percent' => 0, 'text' => '---']
            ];
        }

        $campus = $approvedReviews->avg('campus_rating');
        $faculty = $approvedReviews->avg('faculty_rating');
        $academic = $approvedReviews->avg('academic_rating');

        return [
            [
                'label' => 'Campus Culture', 
                'percent' => ($campus / 5) * 100, 
                'text' => number_format($campus, 1) . ' / 5.0'
            ],
            [
                'label' => 'Faculty Quality', 
                'percent' => ($faculty / 5) * 100, 
                'text' => number_format($faculty, 1) . ' / 5.0'
            ],
            [
                'label' => 'Academic Rigor', 
                'percent' => ($academic / 5) * 100, 
                'text' => number_format($academic, 1) . ' / 5.0'
            ]
        ];
    }
}
