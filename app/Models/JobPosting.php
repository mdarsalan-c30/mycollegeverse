<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruiter_id',
        'target_college_id',
        'title',
        'slug',
        'description',
        'type',
        'location',
        'salary_range',
        'is_approved',
        'status',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (!$job->slug) {
                $base = \Illuminate\Support\Str::slug($job->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $job->slug = $slug;
            }
        });
    }

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id')->withDefault(['name' => 'Former Recruiter']);
    }

    public function targetCollege()
    {
        return $this->belongsTo(College::class, 'target_college_id')->withDefault(['name' => 'Legacy Hub']);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }
}
