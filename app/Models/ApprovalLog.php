<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action',
        'target_type',
        'target_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id')->withDefault(['name' => 'System Authority']);
    }

    /**
     * Record an audit log safely.
     * Use this instead of ApprovalLog::create to prevent crashes if the table is missing on production.
     */
    public static function safeCreate(array $attributes)
    {
        try {
            return self::create($attributes);
        } catch (\Throwable $e) {
            \Log::warning('ApprovalLog safety triggered: ' . $e->getMessage());
            return null;
        }
    }
}
