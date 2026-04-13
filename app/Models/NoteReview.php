<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'note_id',
        'rating',
        'helped_in_exam',
        'feedback'
    ];

    protected $casts = [
        'helped_in_exam' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
