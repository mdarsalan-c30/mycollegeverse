<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'college_name',
        'city',
        'state',
        'student_email',
        'message',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
