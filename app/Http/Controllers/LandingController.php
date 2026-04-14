<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Models\College;
use App\Models\Post;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'notes' => Note::count(),
            'colleges' => College::count(),
        ];

        $recentNotes = Note::with(['user', 'college', 'subject'])
            ->latest()
            ->take(3)
            ->get();

        $topColleges = College::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(8)
            ->get();

        $trendingDiscussions = Post::with(['user', 'likes'])
            ->withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->latest()
            ->take(3)
            ->get();

        $latestBlogs = \App\Models\Blog::where('is_published', true)
            ->with('category')
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact('stats', 'recentNotes', 'topColleges', 'trendingDiscussions', 'latestBlogs'));
    }
}
