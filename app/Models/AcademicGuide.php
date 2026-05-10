<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string|null $file_path
 * @property string $category
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $target_university
 * @property string|null $target_course
 * @property int $views
 * @property bool $is_published
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicGuide published()
 * @mixin \Eloquent
 */
class AcademicGuide extends Model
{
    use \App\Traits\Watermarkable;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'file_path',
        'category',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'target_university',
        'target_course',
        'featured_image',
        'is_published',
        'views'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($guide) {
            if (empty($guide->slug)) {
                $guide->slug = Str::slug($guide->title) . '-' . Str::random(5);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for published guides
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
