<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'user_id',
        'candidate_name',
        'candidate_email',
        'submission_link',
        'file_path',
        'submission_text',
        'recruiter_notes',
        'score',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluations()
    {
        return $this->hasMany(AssignmentEvaluation::class, 'submission_id');
    }
}
