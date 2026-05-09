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
        $query = AcademicGuide::query()->published();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('q')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                  ->orWhere('content', 'like', '%' . $request->q . '%');
            });
        }

        $guides = $query->latest()->paginate(12);
        
        return view('guides.index', compact('guides'));
    }

    /**
     * Manifest a New Guide (View)
     */
    public function create()
    {
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
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'file_path' => $filePath,
            'category' => $request->input('category'),
            'target_university' => $request->input('target_university'),
            'target_course' => $request->input('target_course'),
            'meta_title' => $request->input('meta_title') ?? $request->input('title'),
            'meta_description' => $request->input('meta_description') ?? Str::limit(strip_tags($request->input('content')), 160),
            'meta_keywords' => $request->input('meta_keywords'),
            'is_published' => true,
        ]);

        return redirect()->route('guides.show', $guide->slug)->with('success', 'Academic Guide manifested successfully! 🚀');
    }

    /**
     * View a Single Guide Node 👁️
     */
    public function show($slug)
    {
        $guide = AcademicGuide::where('slug', $slug)->firstOrFail();
        $guide->increment('views');

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
    public function edit($id)
    {
        $guide = AcademicGuide::findOrFail($id);
        
        if ($guide->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('guides.edit', compact('guide'));
    }

    /**
     * Update Guide Node
     */
    public function update(Request $request, $id)
    {
        $guide = AcademicGuide::findOrFail($id);
        
        if ($guide->user_id !== Auth::id() && Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = [
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'category' => $request->input('category'),
            'target_university' => $request->input('target_university'),
            'target_course' => $request->input('target_course'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
        ];

        if ($request->hasFile('pdf_file')) {
            $data['file_path'] = $request->file('pdf_file')->store('academic-guides', 'public');
        }

        $guide->update($data);

        return redirect()->route('guides.show', $guide->slug)->with('success', 'Guide node updated.');
    }

    /**
     * Purge Guide Node
     */
    public function destroy($id)
    {
        $guide = AcademicGuide::findOrFail($id);
        
        if ($guide->user_id !== Auth::id() && Auth::user()->role !== 'admin') abort(403);
        
        $guide->delete();
        return redirect()->route('guides.index')->with('success', 'Guide node purged from the multiverse.');
    }
}
