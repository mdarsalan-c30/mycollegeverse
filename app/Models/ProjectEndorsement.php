<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEndorsement extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'recruiter_id',
        'comment',
        'rating'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }
}
