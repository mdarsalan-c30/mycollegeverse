<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'stream',
        'description',
        'artifact_url',
        'cover_image_path',
        'type',
        'verification_count',
        'is_official'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function endorsements()
    {
        return $this->hasMany(ProjectEndorsement::class);
    }
}
