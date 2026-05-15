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
        $guides = \App\Models\AcademicGuide::where('is_published', true)->latest()->get();
        $jobs = \App\Models\JobPosting::where('is_approved', true)->latest()->get();
        $pages = \App\Models\Page::where('is_active', true)->get();

        return response()->view('sitemap', [
            'colleges' => $colleges,
            'blogs' => $blogs,
            'notes' => $notes,
            'professors' => $professors,
            'guides' => $guides,
            'jobs' => $jobs,
            'pages' => $pages,
        ])->header('Content-Type', 'text/xml');
    }
}
