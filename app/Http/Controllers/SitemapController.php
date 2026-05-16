<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Blog;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate a dynamic XML sitemap for Google Search Console. 🛰️
     */
    public function index()
    {
        $colleges = College::all();
        $blogs = Blog::where('is_published', true)->latest()->get();
        $notes = \App\Models\Note::latest()->get();
        $professors = \App\Models\Professor::all();
        $guides = \App\Models\AcademicGuide::latest()->get();
        $jobs = \App\Models\JobPosting::where('is_active', true)->latest()->get();
        $pages = \App\Models\Page::all();
        $rewards = \App\Models\Reward::where('is_active', true)->get();

        return response()->view('sitemap', [
            'colleges' => $colleges,
            'blogs' => $blogs,
            'notes' => $notes,
            'professors' => $professors,
            'guides' => $guides,
            'jobs' => $jobs,
            'pages' => $pages,
            'rewards' => $rewards,
        ])->header('Content-Type', 'text/xml');
    }
}
