<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Subject;
use App\Models\NoteReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Note::with(['college', 'user', 'subject']);

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // For guests, we show a general global feed. For users, we prioritize their college.
        if ($user) {
            $query->orderByRaw('college_id = ? DESC', [$user->college_id]);
        }
        
        $notes = $query->latest()->get();
        $subjects = Subject::all();
        
        return view('notes.index', compact('notes', 'subjects'));
    }

    public function show($identifier)
    {
        // 1. Resolve Note (Try Slug first, fallback to ID)
        $note = Note::where('slug', $identifier)->first();
        
        if (!$note && is_numeric($identifier)) {
            $note = Note::find($identifier);
            // 2. SEO Health Check: 301 Redirect to Slug if finding via ID
            if ($note && $note->slug) {
                return redirect()->route('notes.show', $note->slug, 301);
            }
        }

        if (!$note) abort(404);

        $note->load(['college', 'user', 'subject', 'reviews.user']);
        
        $related = Note::where('subject_id', $note->subject_id)
            ->where('id', '!=', $note->id)
            ->take(3)
            ->get();

        // SEO Expert Injection 🔍
        $seoTitle = "Download {$note->title} - {$note->subject->name} Notes | {$note->college->name}";
        $seoDescription = "Access high-fidelity academic notes for {$note->subject->name} at {$note->college->name}. Shared by {$note->user->name}. Verified exam-ready knowledge.";
        
        // JSON-LD Structured Data
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Article",
            "headline" => $note->title,
            "description" => $seoDescription,
            "author" => [
                "@type" => "Person",
                "name" => $note->user->name
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => "MyCollegeVerse",
                "logo" => asset('images/logo.png')
            ],
            "datePublished" => $note->created_at->toIso8601String(),
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => $note->avg_rating,
                "reviewCount" => max(1, $note->reviews()->count())
            ]
        ];

        return view('notes.show', compact('note', 'related', 'seoTitle', 'seoDescription', 'schema'));
    }

    public function addReview(Request $request, Note $note)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'helped_in_exam' => 'required|boolean',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $review = $note->reviews()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'rating' => $validated['rating'],
                'helped_in_exam' => $validated['helped_in_exam'],
                'feedback' => $validated['feedback'],
            ]
        );

        return back()->with('success', 'Your academic validation has been recorded! +5 Karma granted.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'required|mimes:pdf,doc,docx,ppt,pptx,zip,jpg,png|max:10240', // 10MB
        ], [
            'file.max' => 'The file is too large! Maximum allowed is 10MB.',
            'file.mimes' => 'Unsupported file format! Please use PDF, DOC, or common images.',
        ]);

        try {
            $file = $request->file('file');
            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            $uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');

            if (!$cloudName || !$uploadPreset) {
                throw new \Exception('Cloudinary configuration missing.');
            }

            // Upload via Cloudinary API
            $response = Http::attach(
                'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
            )->post("https://api.cloudinary.com/v1_1/{$cloudName}/upload", [
                'upload_preset' => $uploadPreset,
            ]);

            if (!$response->successful()) {
                \Log::error('Cloudinary API Error: ' . $response->body());
                throw new \Exception('Failed to upload to cloud storage.');
            }

            $secureUrl = $response->json('secure_url');

            // Ensure we have a college_id
            $college_id = Auth::user()->college_id ?? 1;

            Note::create([
                'title' => $request->title,
                'file_path' => $secureUrl, // Save the cloud URL
                'user_id' => Auth::id(),
                'college_id' => $college_id,
                'subject_id' => $request->subject_id,
            ]);

            return redirect()->route('notes.index')->with('success', 'Note shared successfully with the verse!');
        } catch (\Exception $e) {
            \Log::error('Note Upload Failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to share note. ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $note = Note::findOrFail($id);

        if (filter_var($note->file_path, FILTER_VALIDATE_URL)) {
            return redirect()->away($note->file_path);
        }

        return Storage::disk('public')->download($note->file_path);
    }
}
