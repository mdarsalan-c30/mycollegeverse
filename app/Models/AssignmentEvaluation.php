<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'criteria',
        'score',
        'feedback',
    ];

    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }
}
