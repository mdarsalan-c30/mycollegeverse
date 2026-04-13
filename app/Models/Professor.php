<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'department', 'college_id', 'profile_pic', 'slug'];

    /**
     * Use slugs instead of IDs for public routing.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function college()
    {
        return $this->belongsTo(College::class)->withDefault([
            'name' => 'Legacy Institution'
        ]);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_pic) {
            // If it's already a full URL (legacy/external), return it
            if (filter_var($this->profile_pic, FILTER_VALIDATE_URL)) {
                return $this->profile_pic;
            }
            // Otherwise resolve via ImageKit
            return app(\App\Services\ImageKitService::class)->getUrl($this->profile_pic, ['w' => 160, 'h' => 160, 'fo' => 'auto', 'q' => 80]);
        }
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=2563EB&color=fff&bold=true";
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }
}
