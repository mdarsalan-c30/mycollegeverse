<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'college_id', 'category', 'title', 'content', 'image_path'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'Former Citizen']);
    }

    public function college()
    {
        return $this->belongsTo(College::class)->withDefault(['name' => 'MCV Hub']);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function getScoreAttribute()
    {
        return $this->likes()->sum('value');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) return null;
        return app(\App\Services\ImageKitService::class)->getUrl($this->image_path, ['q' => 80]);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
