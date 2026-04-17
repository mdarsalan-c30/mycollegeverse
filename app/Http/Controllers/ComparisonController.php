<?php

namespace App\Http\Controllers;

use App\Models\College;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    /**
     * Display the comparison selection and trending pairs.
     */
    public function index()
    {
        // Auto-calculate trending pairs (currently just some defaults, 
        // later can be based on views)
        $trendingComparisons = [
            ['slug' => 'lpu-vs-amity', 'label' => 'LPU vs Amity'],
            ['slug' => 'iit-delhi-vs-iit-bombay', 'label' => 'IIT Delhi vs IIT Bombay'],
            ['slug' => 'bits-pilani-vs-mit-manipal', 'label' => 'BITS Pilani vs MIT Manipal'],
        ];

        return view('colleges.compare.index', compact('trendingComparisons'));
    }

    /**
     * Compare 2-3 colleges side by side. ⚖️
     */
    public function compare($slugs)
    {
        $slugArray = explode('-vs-', $slugs);

        if (count($slugArray) < 2 || count($slugArray) > 3) {
            return redirect()->route('compare.index')->with('error', 'Select 2 or 3 colleges to proceed with the battle.');
        }

        $colleges = College::whereIn('slug', $slugArray)
            ->withCount(['notes', 'users', 'professors'])
            ->get()
            ->sortBy(function($college) use ($slugArray) {
                return array_search($college->slug, $slugArray);
            });

        if ($colleges->count() < 2) {
            return redirect()->route('compare.index')->with('error', 'One or more institutional nodes could not be located.');
        }

        return view('colleges.compare.show', compact('colleges'));
    }

    /**
     * Redirect to the clean "vs" URL from the picker.
     */
    public function redirect(Request $request)
    {
        $slugs = array_filter($request->get('colleges', []));
        
        if (count($slugs) < 2) {
            return back()->with('error', 'Pick at least two nodes for comparison.');
        }

        return redirect()->route('compare.show', implode('-vs-', $slugs));
    }
}
