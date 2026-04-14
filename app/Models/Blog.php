<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\College;
use App\Models\Comment;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'seo_score',
        'ai_score',
        'is_published',
        'auto_recommend_colleges',
        'college_ids',
        'views',
        'published_at',
    ];

    protected $casts = [
        'college_ids' => 'array',
        'is_published' => 'boolean',
        'auto_recommend_colleges' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (!$blog->slug) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the colleges associated with this blog post.
     */
    public function colleges()
    {
        if (!$this->college_ids) return collect();
        return College::whereIn('id', $this->college_ids)->get();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getSummaryAttribute()
    {
        if ($this->excerpt) return $this->excerpt;
        return Str::limit(strip_tags($this->content), 160);
    }

    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) return null;
        // Standard high-performance image delivery via ImageKit
        return app(\App\Services\ImageKitService::class)->getUrl($this->featured_image, ['w' => 1200, 'q' => 80]);
    }
}
