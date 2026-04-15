<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'karma_required',
        'claim_link',
        'max_usage',
        'usage_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Scope: Only rewards with stock remaining.
     */
    public function scopeInStock($query)
    {
        return $query->whereColumn('usage_count', '<', 'max_usage');
    }

    /**
     * Scope: Only non-expired rewards.
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope: Only active rewards.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function claimants()
    {
        return $this->belongsToMany(User::class, 'reward_claims')->withPivot('claimed_at')->withTimestamps();
    }

    public function getIsAvailableAttribute()
    {
        return $this->is_active && 
               (!$this->expires_at || $this->expires_at->isFuture()) && 
               ($this->usage_count < $this->max_usage);
    }
}
