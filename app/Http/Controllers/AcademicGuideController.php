<?php

namespace App\Http\Controllers;

use App\Models\AcademicGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
     * Show the manifestation terminal 🛰️
     */
    public function create()
    {
        if (Auth::user()->role === 'recruiter') abort(403);
        return view('guides.create');
    }

    /**
     * Store a new knowledge node 🛡️
     */
    public function store(Request $request)
    {
        if (Auth::user()->role === 'recruiter') abort(403);

        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'category' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        try {
            $filePath = null;
            if ($request->hasFile('pdf_file')) {
                try {
                    $file = $request->file('pdf_file');
                    $cloudName = env('CLOUDINARY_CLOUD_NAME');
                    $uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');

                    if ($cloudName && $uploadPreset) {
                        $uploadFile = $file->getRealPath();
                        $tempPath = null;
                        
                        // Check for Imagick with memory limit safety
                        if (class_exists('\Imagick') && $file->getSize() < 5242880) {
                            try {
                                $imagick = new \Imagick();
                                $imagick->setResolution(100, 100);
                                $imagick->readImage($file->getRealPath());
                                foreach ($imagick as $page) {
                                    $page->setImageFormat('pdf');
                                    $width = $page->getImageWidth();
                                    $height = $page->getImageHeight();
                                    $draw = new \ImagickDraw();
                                    $draw->setFillColor(new \ImagickPixel('#cbd5e1'));
                                    $draw->setFontSize($width / 12);
                                    $draw->setFillOpacity(0.2);
                                    $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
                                    $page->annotateImage($draw, $width / 2, $height / 2, -45, "MYCOLLEGEVERSE.IN");
                                }
                                $tempPath = storage_path('app/temp_' . time() . '.pdf');
                                $imagick->writeImages($tempPath, true);
                                $uploadFile = $tempPath;
                            } catch (\Throwable $it) {
                                $uploadFile = $file->getRealPath();
                            }
                        }

                        $response = Http::attach(
                            'file', file_get_contents($uploadFile), $file->getClientOriginalName()
                        )->post("https://api.cloudinary.com/v1_1/{$cloudName}/upload", [
                            'upload_preset' => $uploadPreset,
                        ]);

                        if ($tempPath && file_exists($tempPath)) unlink($tempPath);
                        if ($response->successful()) $filePath = $response->json('secure_url');
                    }
                } catch (\Throwable $ut) {
                    \Log::error('Upload Error: ' . $ut->getMessage());
                }
            }

            // Memory-Safe Metadata Manifestation 🛰️
            $rawContent = $request->input('content');
            $snippet = substr($rawContent, 0, 1000); 
            $metaDesc = Str::limit(strip_tags($snippet), 160);

            $guide = AcademicGuide::create([
                'user_id' => Auth::id(),
                'title' => Str::limit($request->input('title'), 190),
                'content' => $rawContent,
                'file_path' => $filePath,
                'category' => $request->input('category'),
                'target_university' => Str::limit($request->input('target_university'), 100),
                'target_course' => Str::limit($request->input('target_course'), 100),
                'meta_title' => Str::limit($request->input('meta_title') ?? $request->input('title'), 200),
                'meta_description' => Str::limit($request->input('meta_description') ?? $metaDesc, 160),
                'meta_keywords' => Str::limit($request->input('meta_keywords'), 200),
                'is_published' => true,
            ]);

            return redirect()->route('guides.show', $guide->slug)->with('success', 'Academic Guide manifested successfully! 🚀');
        } catch (\Throwable $e) {
            \Log::error('CRITICAL HUB FAILURE: ' . $e->getMessage());
            return back()->withInput()->with('error', 'System Alert: ' . $e->getMessage());
        }
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

        // High-Velocity SEO Injection 🛰️
        $seoTitle = "{$guide->title} | Academic Guide & Resources PDF | MyCollegeVerse";
        
        // Memory-safe SEO parsing
        $contentSnippet = substr($guide->content, 0, 2000);
        $seoDescription = $guide->meta_description ?? Str::limit(strip_tags($contentSnippet), 160);
        
        $schema = [
            "@context" => "https://schema.org",
            "@graph" => [
                [
                    "@type" => "Article",
                    "headline" => $guide->title,
                    "description" => $seoDescription,
                    "datePublished" => $guide->created_at->toIso8601String(),
                    "dateModified" => $guide->updated_at->toIso8601String(),
                    "author" => [
                        "@type" => "Person",
                        "name" => $guide->user->name ?? 'Archivist'
                    ]
                ]
            ]
        ];

        return view('guides.show', compact('guide', 'related', 'seoTitle', 'seoDescription', 'schema'));
    }

    public function edit($id)
    {
        $guide = AcademicGuide::findOrFail($id);
        if (Auth::id() !== $guide->user_id && Auth::user()->role !== 'admin') abort(403);
        return view('guides.edit', compact('guide'));
    }

    public function update(Request $request, $id)
    {
        $guide = AcademicGuide::findOrFail($id);
        if (Auth::id() !== $guide->user_id && Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'category' => 'required|string',
        ]);

        try {
            $guide->update([
                'title' => Str::limit($request->input('title'), 190),
                'content' => $request->input('content'),
                'category' => $request->input('category'),
                'target_university' => Str::limit($request->input('target_university'), 100),
                'target_course' => Str::limit($request->input('target_course'), 100),
                'meta_title' => Str::limit($request->input('meta_title') ?? $request->input('title'), 200),
                'meta_description' => Str::limit($request->input('meta_description') ?? strip_tags(substr($request->input('content'), 0, 1000)), 160),
                'meta_keywords' => Str::limit($request->input('meta_keywords'), 200),
            ]);

            return redirect()->route('guides.show', $guide->slug)->with('success', 'Knowledge node updated! 🛡️');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Update Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $guide = AcademicGuide::findOrFail($id);
        if (Auth::id() !== $guide->user_id && Auth::user()->role !== 'admin') abort(403);
        
        $guide->delete();
        return redirect()->route('guides.index')->with('success', 'Knowledge node purged from multiverse.');
    }
}
