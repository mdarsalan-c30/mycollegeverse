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
        $blogs = Blog::where('status', 'published')->latest()->get();
        $notes = \App\Models\Note::latest()->get();
        $professors = \App\Models\Professor::all();

        return response()->view('sitemap', [
            'colleges' => $colleges,
            'blogs' => $blogs,
            'notes' => $notes,
            'professors' => $professors,
        ])->header('Content-Type', 'text/xml');
    }
}
