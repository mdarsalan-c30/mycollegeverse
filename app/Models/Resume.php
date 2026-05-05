<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'template_id',
        'data',
        'is_public',
        'views_count'
    ];

    protected $casts = [
        'data' => 'array',
        'is_public' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($resume) {
            if (!$resume->slug) {
                $resume->slug = Str::uuid()->toString();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a shareable URL for the resume.
     */
    public function getShareUrlAttribute()
    {
        return route('resumes.show', $this->slug);
    }
}
