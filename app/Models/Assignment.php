<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruiter_id',
        'job_id',
        'title',
        'slug',
        'description',
        'instructions',
        'role',
        'task_type',
        'submission_types',
        'deadline',
        'is_public',
        'status',
        'settings',
    ];

    protected $casts = [
        'submission_types' => 'array',
        'settings' => 'array',
        'deadline' => 'datetime',
        'is_public' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($assignment) {
            if (!$assignment->slug) {
                $base = \Illuminate\Support\Str::slug($assignment->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $assignment->slug = $slug;
            }
        });
    }

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
