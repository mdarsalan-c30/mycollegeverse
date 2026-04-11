<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'student_id',
        'resume_path',
        'resume_shared_link',
        'about_me',
        'why_hire',
        'status'
    ];

    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
