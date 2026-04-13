<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the SEO-critical pages.
     */
    public function index()
    {
        try {
            $pages = Page::latest()->get();
        } catch (\Throwable $e) {
            \Log::warning('Pages table missing — run migrations: ' . $e->getMessage());
            $pages = collect([]);
        }
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new high-fidelity page.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page in the multiverse registry.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required',
            'meta_description' => 'nullable|string|max:160',
        ]);

        Page::create([
            'title' => $request->title,
            'slug' => $request->slug ?? Str::slug($request->title),
            'content' => $request->content,
            'meta_description' => $request->meta_description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Portal Page initialized successfully.');
    }

    /**
     * Show the form for editing an existing multiverse node.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page node.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'content' => $request->content,
            'meta_description' => $request->meta_description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Portal Page recalibrated successfully.');
    }

    /**
     * Remove the specified page node from the multiverse.
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Portal Page terminated.');
    }
}
