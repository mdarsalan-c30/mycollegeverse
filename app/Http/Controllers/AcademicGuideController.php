<?php

namespace App\Http\Controllers;

use App\Models\AcademicGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AcademicGuideController extends Controller
{
    /**
     * Display the Academic Hub 🏛️
     */
    public function index(Request $request)
    {
        $query = AcademicGuide::published();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('q')) {
            $query->where('title', 'like', '%' . $request->q . '%')
                  ->orWhere('content', 'like', '%' . $request->q . '%');
        }

        $guides = $query->latest()->paginate(12);
        
        return view('guides.index', compact('guides'));
    }

    /**
     * Manifest a New Guide (View)
     */
    public function create()
    {
        // Only Students and Admins allowed (Recruiters locked out)
        if (Auth::user()->role === 'recruiter') {
            return redirect()->route('guides.index')->with('error', 'Recruiters cannot manifest academic guides.');
        }

        return view('guides.create');
    }

    /**
     * Store the Guide Node 💾
     */
    public function store(Request $request)
    {
        if (Auth::user()->role === 'recruiter') abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('pdf_file')) {
            $filePath = $request->file('pdf_file')->store('academic-guides', 'public');
        }

        $guide = AcademicGuide::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'file_path' => $filePath,
            'category' => $request->category,
            'target_university' => $request->target_university,
            'target_course' => $request->target_course,
            'meta_title' => $request->meta_title ?? $request->title,
            'meta_description' => $request->meta_description ?? Str::limit(strip_tags($request->content), 160),
            'meta_keywords' => $request->meta_keywords,
            'is_published' => true,
        ]);

        return redirect()->route('guides.show', $guide->slug)->with('success', 'Academic Guide manifested successfully! 🚀');
    }

    /**
     * View a Single Guide Node (SEO Entry Point) 👁️
     */
    public function show($slug)
    {
        $guide = AcademicGuide::where('slug', $slug)->firstOrFail();
        $guide->increment('views');

        // Related guides from same category
        $related = AcademicGuide::where('category', $guide->category)
                    ->where('id', '!=', $guide->id)
                    ->published()
                    ->limit(5)
                    ->get();

        return view('guides.show', compact('guide', 'related'));
    }

    /**
     * Edit Guide Node
     */
    public function edit(AcademicGuide $guide)
    {
        if ($guide->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('guides.edit', compact('guide'));
    }

    /**
     * Update Guide Node
     */
    public function update(Request $request, AcademicGuide $guide)
    {
        if ($guide->user_id !== Auth::id() && Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'target_university' => $request->target_university,
            'target_course' => $request->target_course,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ];

        if ($request->hasFile('pdf_file')) {
            // Optional: delete old file
            $data['file_path'] = $request->file('pdf_file')->store('academic-guides', 'public');
        }

        $guide->update($data);

        return redirect()->route('guides.show', $guide->slug)->with('success', 'Guide node updated.');
    }

    /**
     * Purge Guide Node
     */
    public function destroy(AcademicGuide $guide)
    {
        if ($guide->user_id !== Auth::id() && Auth::user()->role !== 'admin') abort(403);
        
        $guide->delete();
        return redirect()->route('guides.index')->with('success', 'Guide node purged from the multiverse.');
    }
}
