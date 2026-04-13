<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file_path',
        'user_id',
        'college_id',
        'subject_id',
        'is_verified',
    ];

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
        return $this->belongsTo(Subject::class)->withDefault([
            'name' => 'General Topic'
        ]);
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
