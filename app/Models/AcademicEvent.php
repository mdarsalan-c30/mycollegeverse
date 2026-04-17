<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'due_date',
        'description',
        'priority',
        'subject_id',
        'college_id',
        'course_id',
        'semester',
        'user_id',
        'is_official',
        'is_verified',
        'verification_count'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_official' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter events relevant to a specific student.
     */
    public function scopeForStudent($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            // Official Batch Events
            $q->where('college_id', $user->college_id)
              ->where('course_id', $user->course_id)
              ->where('semester', $user->semester)
              ->where('is_official', true);
        })->orWhere(function ($q) use ($user) {
            // Personal Events
            $q->where('user_id', $user->id);
        });
    }

    public function getDueDateLabelAttribute()
    {
        $now = now();
        $diff = $now->diffInHours($this->due_date, false);

        if ($diff < 0) return 'Expired';
        if ($diff < 24) return 'Due in ' . round($diff) . 'h';
        return 'Due in ' . $now->diffInDays($this->due_date) . 'd';
    }
}
