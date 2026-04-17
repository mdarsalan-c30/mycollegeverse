<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use App\Models\JobPosting;
use App\Models\NoteReview;

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
        'karma_spent',
        'is_batch_visible',
        'career_role',
        'is_mentor',
        'mentor_bio',
        'mentor_topics',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'mentor_topics' => 'array',
        'is_mentor' => 'boolean',
    ];

    public function getIsMentorEligibleAttribute()
    {
        $yearInt = (int)$this->year;
        
        // Rule: Final Year (4) OR (3rd Year AND Karma >= 500)
        if ($yearInt >= 4) {
            return true;
        }

        if ($yearInt === 3 && $this->karma >= 500) {
            return true;
        }

        return false;
    }

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
        $noteReviewCount = $this->noteReviews()->count();

        return (
            ($noteCount * 50) + 
            ($downloadSum * 10) + 
            ($postCount * 20) + 
            ($likesReceived * 5) + 
            ($commentCount * 10) + 
            ($reviewCount * 15) +
            ($noteReviewCount * 5) -
            $this->karma_spent
        );
    }

    public function noteReviews()
    {
        return $this->hasMany(\App\Models\NoteReview::class);
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
        if (!$value) return null;
        return app(\App\Services\ImageKitService::class)->getUrl($value, ['w' => 800, 'q' => 70]);
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

    public function getBadgesAttribute()
    {
        $badges = [];
        
        // Verse Pioneer (Early Adopters)
        if ($this->id < 100) {
            $badges[] = ['icon' => '👑', 'name' => 'Verse Pioneer', 'color' => 'bg-amber-100 ring-amber-500'];
        }

        // Knowledge Titan (High Note Contribution)
        if ($this->notes()->count() >= 5) {
            $badges[] = ['icon' => '📚', 'name' => 'Knowledge Titan', 'color' => 'bg-blue-100 ring-blue-500'];
        }

        // Community Oracle (High Engagement)
        if ($this->posts()->count() >= 5) {
            $badges[] = ['icon' => '🏛️', 'name' => 'Community Oracle', 'color' => 'bg-purple-100 ring-purple-500'];
        }

        // Rising Star (Karma based)
        if ($this->karma >= 1000) {
            $badges[] = ['icon' => '🌟', 'name' => 'Rising Star', 'color' => 'bg-emerald-100 ring-emerald-500'];
        }

        // Helpful Citizen (Review based)
        $reviewCount = \DB::table('reviews')->where('user_id', $this->id)->count();
        if ($reviewCount >= 3) {
            $badges[] = ['icon' => '🤝', 'name' => 'Verified Helper', 'color' => 'bg-rose-100 ring-rose-500'];
        }

        return $badges;
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    public function savedNotes()
    {
        return $this->belongsToMany(Note::class, 'saved_notes')->withTimestamps();
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function claimedRewards()
    {
        return $this->belongsToMany(Reward::class, 'reward_claims')->withPivot('claimed_at')->withTimestamps();
    }
}
