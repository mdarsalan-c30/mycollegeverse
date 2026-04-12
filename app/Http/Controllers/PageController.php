<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified high-fidelity SEO page.
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->active()->firstOrFail();

        return view('pages.show', compact('page'));
    }
}
