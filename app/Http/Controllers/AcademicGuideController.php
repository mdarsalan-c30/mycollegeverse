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
            try {
                $file = $request->file('pdf_file');
                $cloudName = env('CLOUDINARY_CLOUD_NAME');
                $uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');

                if (!$cloudName || !$uploadPreset) {
                    throw new \Exception('Cloudinary configuration missing.');
                }

                $authorName = Auth::user()->name ?? 'MCV Archivist';
                $safeName = preg_replace('/[^A-Za-z0-9 ]/', '', $authorName);

                // --- High-Quality Premium Imagick Watermarking ---
                try {
                    // Check file size (If > 10MB, skip branding to prevent server timeout)
                    if ($file->getSize() < 10485760) {
                        $imagick = new \Imagick();
                        $imagick->setResolution(150, 150);
                        $imagick->readImage($file->getRealPath());

                        foreach ($imagick as $page) {
                            $page->setImageFormat('pdf');
                            $page->setImageCompressionQuality(90);

                            $width = $page->getImageWidth();
                            $height = $page->getImageHeight();

                            // 1. Center Diagonal Watermark
                            $draw = new \ImagickDraw();
                            $draw->setFillColor(new \ImagickPixel('#cbd5e1'));
                            $draw->setFontSize($width / 10);
                            $draw->setFillOpacity(0.3);
                            $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
                            $draw->setFontWeight(700);
                            $page->annotateImage($draw, $width / 2, $height / 2, -45, "MYCOLLEGEVERSE.IN");

                            // 2. Professional Footer
                            $footerDraw = new \ImagickDraw();
                            $footerDraw->setFillColor(new \ImagickPixel('#475569'));
                            $footerDraw->setFontSize(14);
                            $footerDraw->setFontWeight(800);
                            $footerDraw->setFillOpacity(1.0);
                            
                            $margin = 60;
                            $footerDraw->setTextAlignment(\Imagick::ALIGN_LEFT);
                            $page->annotateImage($footerDraw, $margin, $height - 40, 0, "Downloaded from MyCollegeVerse.in");

                            $footerDraw->setTextAlignment(\Imagick::ALIGN_RIGHT);
                            $page->annotateImage($footerDraw, $width - $margin, $height - 40, 0, "Author: {$safeName}");
                        }

                        $tempPath = storage_path('app/temp_' . time() . '.pdf');
                        $imagick->writeImages($tempPath, true);
                        $uploadFile = $tempPath;
                    } else {
                        $uploadFile = $file->getRealPath();
                    }
                } catch (\Exception $e) {
                    \Log::warning("Imagick Watermarking failed: " . $e->getMessage());
                    $uploadFile = $file->getRealPath();
                }
                // ----------------------------------------------

                $response = Http::attach(
                    'file', file_get_contents($uploadFile), $file->getClientOriginalName()
                )->post("https://api.cloudinary.com/v1_1/{$cloudName}/upload", [
                    'upload_preset' => $uploadPreset,
                ]);

                if (isset($tempPath) && file_exists($tempPath)) {
                    unlink($tempPath);
                }

                if ($response->successful()) {
                    $filePath = $response->json('secure_url');
                } else {
                    \Log::error('Academic Guide Cloudinary Upload Failed: ' . $response->body());
                }
            } catch (\Exception $e) {
                \Log::error('Academic Guide Upload Exception: ' . $e->getMessage());
            }
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

        // High-Velocity SEO Injection 🛰️
        $seoTitle = "{$guide->title} | Academic Guide & Resources PDF | MyCollegeVerse";
        $seoDescription = $guide->meta_description ?? Str::limit(strip_tags($guide->content), 160);
        
        $schema = [
            "@context" => "https://schema.org",
            "@graph" => [
                [
                    "@type" => "Article",
                    "headline" => $guide->title,
                    "description" => $seoDescription,
                    "datePublished" => $guide->created_at->toIso8601String(),
                    "author" => ["@type" => "Person", "name" => $guide->user->name ?? 'MyCollegeVerse Team'],
                ],
                [
                    "@type" => "FAQPage",
                    "mainEntity" => [
                        [
                            "@type" => "Question",
                            "name" => "How to download {$guide->title} PDF?",
                            "acceptedAnswer" => [
                                "@type" => "Answer",
                                "text" => "You can download the PDF by clicking the 'Download Node' button on the MyCollegeVerse platform."
                            ]
                        ],
                        [
                            "@type" => "Question",
                            "name" => "Is this academic guide verified?",
                            "acceptedAnswer" => [
                                "@type" => "Answer",
                                "text" => "Yes, all guides in the Academic Hub are reviewed for accuracy and relevance."
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return view('guides.show', compact('guide', 'related', 'seoTitle', 'seoDescription', 'schema'));
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
            try {
                $file = $request->file('pdf_file');
                $cloudName = env('CLOUDINARY_CLOUD_NAME');
                $uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');

                $response = Http::attach(
                    'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
                )->post("https://api.cloudinary.com/v1_1/{$cloudName}/upload", [
                    'upload_preset' => $uploadPreset,
                ]);

                if ($response->successful()) {
                    $data['file_path'] = $response->json('secure_url');
                }
            } catch (\Exception $e) {
                \Log::error('Academic Guide Update Upload Error: ' . $e->getMessage());
            }
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
