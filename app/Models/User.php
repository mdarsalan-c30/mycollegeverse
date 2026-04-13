<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use App\Models\JobPosting;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'college_id',
        'username',
        'profile_photo_path',
        'college_email',
        'mobile',
        'year',
        'company_name',
        'company_website',
        'integration_token',
        'status',
        'ban_reason',
        'id_card_url',
    ];

    public function getKarmaAttribute()
    {
        // High-fidelity ARS Point Calculation (Matching Leaderboard logic)
        $noteCount = $this->notes()->count();
        $downloadSum = $this->notes()->sum('downloads');
        $postCount = $this->posts()->count();
        $likesReceived = DB::table('likes')
            ->join('posts', 'likes.post_id', '=', 'posts.id')
            ->where('posts.user_id', $this->id)
            ->count();
        $commentCount = DB::table('comments')->where('user_id', $this->id)->count();
        $reviewCount = DB::table('reviews')->where('user_id', $this->id)->count();

        return (
            ($noteCount * 50) + 
            ($downloadSum * 10) + 
            ($postCount * 20) + 
            ($likesReceived * 5) + 
            ($commentCount * 10) + 
            ($reviewCount * 15)
        );
    }

    public function getArsScoreAttribute()
    {
        // Normalize 0-100 decimal scale for recruiters
        $points = $this->karma;
        if ($points <= 0) return 0.0;
        
        // Target: 1000 points = ~100 ARS Score
        $score = ($points / 1000) * 100;
        return (float) number_format(min(100, $score), 1);
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'recruiter_id');
    }

    public function getRouteKeyName()
    {
        return 'username';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (!$user->username) {
                $baseSlug = \Illuminate\Support\Str::slug($user->name);
                $slug = $baseSlug;
                $counter = 1;
                
                while (static::where('username', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $user->username = $slug;
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return app(\App\Services\ImageKitService::class)->getUrl($this->profile_photo_path, ['w' => 160, 'h' => 160, 'fo' => 'auto', 'q' => 80]);
        }
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=2563EB&color=fff";
    }

    public function getIdCardUrlAttribute($value)
    {
        if (!$this->id_card_url) return null;
        return app(\App\Services\ImageKitService::class)->getUrl($this->id_card_url, ['w' => 800, 'q' => 70]);
    }

    public function receivedMessages()
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    public function getUnreadMessagesCountAttribute()
    {
        return $this->receivedMessages()->where('is_read', false)->count();
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'student_id');
    }

    public function getUnreadPipelineCountAttribute()
    {
        return $this->applications()->where('is_seen_by_student', false)->count();
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
