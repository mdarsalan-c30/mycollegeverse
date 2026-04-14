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
        try {
            $page = Page::where('slug', $slug)->active()->first();

            if ($page) {
                return view('pages.show', compact('page'));
            }

            // Fallback to static blade files if they exist 🛡️
            if (view()->exists("pages.{$slug}")) {
                return view("pages.{$slug}");
            }

            abort(404, "Identity Node [{$slug}] not found in the multiverse.");
        } catch (\Throwable $e) {
            \Log::error("Multiverse Page Error for [{$slug}]: " . $e->getMessage());
            
            // Critical fallback to home if everything fails
            return redirect('/')->with('error', 'Temporary synchronization error. Please try again.');
        }
    }
}
