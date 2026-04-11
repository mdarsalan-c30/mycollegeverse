<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LeaderboardController extends Controller
{
    /**
     * ARS (Academic Reputation Score) calculation:
     *
     * | Activity                   | Points |
     * |----------------------------|--------|
     * | Note uploaded              | +50    |
     * | Note download count        | +10 ea |
     * | Community post (posts)     | +20    |
     * | Like received (likes tbl)  | +5 ea  |
     * | Comment made (comments)    | +10    |
     * | Professor review (reviews) | +15    |
     */
    public function index()
    {
        $users = User::query()
            ->leftJoin('colleges', 'users.college_id', '=', 'colleges.id')
            ->select('users.id', 'users.name', 'users.username', 'users.profile_photo_path', 'colleges.name as college')
            // Notes uploaded × 50
            ->selectRaw('(SELECT COUNT(*) FROM notes WHERE notes.user_id = users.id) * 50 AS note_pts')
            // Downloads received × 10
            ->selectRaw('(SELECT COALESCE(SUM(downloads), 0) FROM notes WHERE notes.user_id = users.id) * 10 AS download_pts')
            // Posts × 20
            ->selectRaw('(SELECT COUNT(*) FROM posts WHERE posts.user_id = users.id) * 20 AS post_pts')
            // Likes received on user's posts × 5
            ->selectRaw('(SELECT COUNT(*) FROM likes l JOIN posts p ON l.post_id = p.id WHERE p.user_id = users.id) * 5 AS like_pts')
            // Comments made × 10
            ->selectRaw('(SELECT COUNT(*) FROM comments WHERE comments.user_id = users.id) * 10 AS comment_pts')
            // Professor reviews × 15
            ->selectRaw('(SELECT COUNT(*) FROM reviews WHERE reviews.user_id = users.id) * 15 AS review_pts')
            // TOTAL ARS SCORE
            ->selectRaw('(
                (SELECT COUNT(*) FROM notes WHERE notes.user_id = users.id) * 50 +
                (SELECT COALESCE(SUM(downloads), 0) FROM notes WHERE notes.user_id = users.id) * 10 +
                (SELECT COUNT(*) FROM posts WHERE posts.user_id = users.id) * 20 +
                (SELECT COUNT(*) FROM likes l JOIN posts p ON l.post_id = p.id WHERE p.user_id = users.id) * 5 +
                (SELECT COUNT(*) FROM comments WHERE comments.user_id = users.id) * 10 +
                (SELECT COUNT(*) FROM reviews WHERE reviews.user_id = users.id) * 15
            ) AS total_score')
            ->orderByDesc('total_score')
            ->limit(50)
            ->get();

        // Rank them (ties get same rank)
        $rank = 0;
        $prevScore = -1;
        $users = $users->map(function ($user) use (&$rank, &$prevScore) {
            if ($user->total_score !== $prevScore) {
                $rank++;
                $prevScore = $user->total_score;
            }
            $user->rank = $rank;
            return $user;
        });

        // Find current user's position
        $myRank  = null;
        $myScore = null;
        if (Auth::check()) {
            $me = $users->firstWhere('id', Auth::id());
            if ($me) {
                $myRank  = $me->rank;
                $myScore = $me->total_score;
            }
        }

        $top3 = $users->take(3);
        $rest  = $users->skip(3);

        return view('leaderboard.index', compact('top3', 'rest', 'myRank', 'myScore'));
    }
}
