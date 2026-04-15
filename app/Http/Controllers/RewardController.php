<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    /**
     * Display the rewards available for students.
     */
    public function index()
    {
        $rewards = Reward::active()
            ->orderBy('karma_required', 'asc')
            ->get();

        $claimedRewardIds = Auth::user()->claimedRewards()->pluck('rewards.id')->toArray();

        return view('rewards.index', compact('rewards', 'claimedRewardIds'));
    }

    /**
     * Claim a reward using Karma points. ✨
     */
    public function claim(Reward $reward)
    {
        $user = Auth::user();

        // 1. Check if already claimed
        if ($user->claimedRewards()->where('reward_id', $reward->id)->exists()) {
            return back()->with('error', 'You have already manifested this perk! check your claim history.');
        }

        // 2. Check if active/expired
        if (!$reward->is_available) {
            return back()->with('error', 'This perk is no longer available in the current timeline.');
        }

        // 3. Check Karma balance
        if ($user->karma < $reward->karma_required) {
            return back()->with('info', 'Insufficient Karma. You need ' . ($reward->karma_required - $user->karma) . ' more points to unlock this node.');
        }

        // 4. Secure Claim Transaction
        try {
            DB::transaction(function () use ($user, $reward) {
                // Re-verify stock inside transaction
                $currentReward = Reward::where('id', $reward->id)->lockForUpdate()->first();
                
                if ($currentReward->usage_count >= $currentReward->max_usage) {
                    throw new \Exception('Stock exhausted during transaction.');
                }

                // Increase reward usage
                $currentReward->increment('usage_count');

                // Deduct Karma from user by increasing karma_spent
                $user->increment('karma_spent', $reward->karma_required);

                // Attach Reward to User
                $user->claimedRewards()->attach($reward->id, [
                    'claimed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            });

            return back()->with('success', 'Perk Successfully Redeemed! Your claim link is now active in the collection below. 🎁');

        } catch (\Exception $e) {
            \Log::error("Claim Failure: " . $e->getMessage());
            return back()->with('error', 'Nexus disruption during claim. Please try again later.');
        }
    }
}
