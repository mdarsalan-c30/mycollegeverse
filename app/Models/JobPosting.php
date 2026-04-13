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
        'description',
        'type',
        'location',
        'salary_range',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

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
