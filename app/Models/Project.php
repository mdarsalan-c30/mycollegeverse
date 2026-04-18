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
        'description',
        'stream',
        'file_url',
        'cover_image_url',
        'visibility_score',
        'is_featured',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_featured' => 'boolean',
    ];

    /**
     * The student who created this artifact.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verification/Endorsements from the campus community.
     */
    public function endorsements()
    {
        return $this->hasMany(ProjectEndorsement::class);
    }

    /**
     * Helper to get stream iconography.
     */
    public function getIconAttribute()
    {
        $icons = [
            'Commerce' => '📈',
            'Arts' => '🎨',
            'Law' => '⚖️',
            'Design' => '📐',
            'Journalism' => '🎙️',
            'Management' => '💼',
            'Science' => '🔬'
        ];
        return isset($this->stream) ? ($icons[$this->stream] ?? '🛡️') : '🛡️';
    }
}
