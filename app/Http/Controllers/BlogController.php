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
        try {
            $categories = \App\Models\BlogCategory::with(['blogs' => function($q) {
                $q->where('is_published', true)->latest()->take(6);
            }])->where('is_active', true)->get();

            $featuredBlogs = Blog::where('is_published', true)->where('seo_score', '>=', 70)->latest()->take(3)->get();
            if($featuredBlogs->count() < 1) {
                $featuredBlogs = Blog::where('is_published', true)->latest()->take(3)->get();
            }

            $recentInsights = Blog::where('is_published', true)->with('category')->latest()->paginate(12);
            
            return view('blogs.index', compact('categories', 'featuredBlogs', 'recentInsights'));
        } catch (\Exception $e) {
            \Log::error("Blog Index Error: " . $e->getMessage());
            $categories = collect(); 
            $featuredBlogs = collect();
            return view('blogs.index', compact('categories', 'featuredBlogs'))->with('error', 'Editorial signals are faint. Please refresh or check back later.');
        }
    }

    /**
     * Display a high-readability article view with institutional mapping.
     */
    public function show($slug)
    {
        try {
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
                    ->orderBy('rating', 'desc')
                    ->take(6)
                    ->get();
            } else {
                // Manual: Use explicitly picked colleges
                $recommendedColleges = $blog->colleges();
            }

            return view('blogs.show', compact('blog', 'recommendedColleges'));
        } catch (\Exception $e) {
            \Log::error("Blog Show Error: " . $e->getMessage());
            abort(404, "Article node lost in the multiverse transition.");
        }
    }
}
