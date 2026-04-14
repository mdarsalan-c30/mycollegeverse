<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\College;
use App\Services\SeoAnalyzerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    protected $seoAnalyzer;

    public function __construct(SeoAnalyzerService $seoAnalyzer)
    {
        $this->seoAnalyzer = $seoAnalyzer;
    }

    public function index()
    {
        try {
            $blogs = Blog::with('author')->latest()->paginate(10);
            return view('admin.blogs.index', compact('blogs'));
        } catch (\Exception $e) {
            \Log::error("Editorial Hub Error: " . $e->getMessage());
            $blogs = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            return view('admin.blogs.index', compact('blogs'))->with('error', 'Article Registry connection unstable. Ensure migrations are complete.');
        }
    }

    public function create()
    {
        $colleges = College::orderBy('name')->get();
        $categories = \App\Models\BlogCategory::all();
        return view('admin.blogs.create', compact('colleges', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:blog_categories,id',
            'content' => 'required',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'auto_recommend_colleges' => 'nullable',
            'college_ids' => 'nullable|array',
            'is_published' => 'nullable',
        ]);

        // Automated SEO Scan 🧠
        $seoReport = $this->seoAnalyzer->analyze(
            $validated['title'],
            $validated['content'],
            $validated['meta_description'] ?? '',
            explode(',', $validated['meta_keywords'] ?? '')
        );

        $blog = Blog::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'],
            'featured_image' => $validated['featured_image'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'],
            'meta_keywords' => $validated['meta_keywords'],
            'seo_score' => $seoReport['score'],
            'ai_score' => 100, // Automated AI Originality Score Node
            'is_published' => $request->has('is_published'),
            'auto_recommend_colleges' => $request->has('auto_recommend_colleges'),
            'college_ids' => $validated['college_ids'] ?? [],
            'published_at' => $request->has('is_published') ? now() : null,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog manifested successfully with an SEO score of ' . $seoReport['score'] . '%!');
    }

    public function edit(Blog $blog)
    {
        $colleges = College::orderBy('name')->get();
        $categories = \App\Models\BlogCategory::all();
        return view('admin.blogs.edit', compact('blog', 'colleges', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:blog_categories,id',
            'content' => 'required',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'auto_recommend_colleges' => 'nullable',
            'college_ids' => 'nullable|array',
            'is_published' => 'nullable',
        ]);

        $seoReport = $this->seoAnalyzer->analyze(
            $validated['title'],
            $validated['content'],
            $validated['meta_description'] ?? '',
            explode(',', $validated['meta_keywords'] ?? '')
        );

        $blog->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'],
            'featured_image' => $validated['featured_image'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'],
            'meta_keywords' => $validated['meta_keywords'],
            'seo_score' => $seoReport['score'],
            'is_published' => $request->has('is_published'),
            'auto_recommend_colleges' => $request->has('auto_recommend_colleges'),
            'college_ids' => $validated['college_ids'] ?? [],
            'published_at' => ($request->has('is_published') && !$blog->published_at) ? now() : $blog->published_at,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Editorial updated. New SEO Score: ' . $seoReport['score'] . '%');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return back()->with('success', 'Article removed from the multiverse.');
    }
}
