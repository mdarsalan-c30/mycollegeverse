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
        // Assuming Blogs might be called 'Blog' or similar, we'll check common published status
        $blogs = Blog::where('status', 'published')->latest()->get();

        return response()->view('sitemap', [
            'colleges' => $colleges,
            'blogs' => $blogs,
        ])->header('Content-Type', 'text/xml');
    }
}
