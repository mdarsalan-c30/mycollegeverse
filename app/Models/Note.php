<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'file_path',
        'note_type',
        'ai_content',
        'user_id',
        'college_id',
        'subject_id',
        'custom_subject',
        'is_verified',
        'exam_name',
        'is_pyq',
        'pyq_year',
    ];

    public function isAiGenerated()
    {
        return $this->note_type === 'ai';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($note) {
            if (!$note->slug) {
                $base = \Illuminate\Support\Str::slug($note->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $note->slug = $slug;
            }
        });
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\NoteReview::class);
    }

    public function getAvgRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?: 5.0, 1);
    }

    public function getExamHelpRateAttribute()
    {
        $total = $this->reviews()->count();
        if ($total === 0) return 100; // Legacy default
        
        $helped = $this->reviews()->where('helped_in_exam', true)->count();
        return round(($helped / $total) * 100);
    }

    public function college()
    {
        return $this->belongsTo(College::class)->withDefault([
            'name' => 'Global Hub'
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Former Citizen'
        ]);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class)->withDefault(function ($subject, $note) {
            $subject->name = $note->custom_subject ?? 'General Topic';
        });
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Resolve the High-Fidelity URL for the Knowledge Asset.
     */
    public function getPdfUrlAttribute()
    {
        if (filter_var($this->file_path, FILTER_VALIDATE_URL)) {
            return $this->file_path;
        }

        return asset('storage/' . $this->file_path);
    }
}
