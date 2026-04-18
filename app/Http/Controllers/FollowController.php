<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Handle the follow/unfollow toggle.
     */
    public function toggle(User $user)
    {
        $follower = Auth::user();

        if ($follower->id === $user->id) {
            return back()->with('error', "Bhai, khud ko follow karke kya milega? Support others! 🤝");
        }

        if ($follower->isFollowing($user)) {
            $follower->following()->detach($user->id);
            $message = "You unfollowed {$user->name}.";
        } else {
            $follower->following()->attach($user->id);
            $message = "You are now following {$user->name}! 🦾";
            
            // Trigger Notification (Verse Signal)
            try {
                $user->notify(new \App\Notifications\StandardVerseNotification(
                    "New Connection ⚡",
                    "{$follower->name} is now following your academic journey.",
                    route('profile.show', $follower->username),
                    '🤝'
                ));
            } catch (\Exception $e) {
                // Silently fail if notification system has issues
                \Log::error("Follow Notification Failed: " . $e->getMessage());
            }
        }

        return back()->with('success', $message);
    }
}
