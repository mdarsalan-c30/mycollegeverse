<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\College;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display the high-performance editorial feed.
     */
    public function index()
    {
        $blogs = Blog::where('is_published', true)
            ->with('author')
            ->latest('published_at')
            ->paginate(9);

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Display a high-readability article view with institutional mapping.
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('is_published', true)
            ->with(['author', 'comments.user'])
            ->firstOrFail();

        // Register Multiverse View 🛰️
        $blog->increment('views');

        // Logic for Institutional Mapping (Recommended Colleges) 🧬
        $recommendedColleges = collect();
        
        if ($blog->auto_recommend_colleges) {
            // Auto-pilot: Fetch colleges based on keywords or general global priority
            // For now, we fetch top rated colleges as a high-value default
            $recommendedColleges = College::withCount('reviews')
                ->orderBy('avg_rating', 'desc')
                ->take(6)
                ->get();
        } else {
            // Manual: Use explicitly picked colleges
            $recommendedColleges = $blog->colleges();
        }

        return view('blogs.show', compact('blog', 'recommendedColleges'));
    }
}
