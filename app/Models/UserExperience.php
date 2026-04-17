<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'company',
        'type',
        'duration',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
