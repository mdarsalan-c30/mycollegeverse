<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\SavedNote;
use App\Models\AiUsage;
use App\Models\Subject;
use App\Models\NoteReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Traits\GeneratesAiContent;

class NoteController extends Controller
{
    use GeneratesAiContent;
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Note::with(['college', 'user', 'subject']);

        // High-Performance Intelligence Filtering 🛰️
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('custom_subject', 'like', "%{$search}%")
                  ->orWhereHas('subject', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('college', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->boolean('exam_trusted')) {
            $query->whereHas('reviews', function($q) {
                $q->where('helped_in_exam', true);
            });
        }

        if ($request->filled('course_id') && $request->course_id !== 'All') {
            $query->whereHas('subject', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        if ($request->filled('semester') && $request->semester !== 'All') {
            $query->whereHas('subject', function($q) use ($request) {
                $q->where('semester', $request->semester);
            });
        }

        if ($request->boolean('is_verified')) {
            $query->where('is_verified', true);
        }

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // For guests, we show a general global feed. For users, we prioritize their college.
        if ($user) {
            $query->orderByRaw('college_id = ? DESC', [$user->college_id]);
        }
        
        $notes = $query->latest()->get();
        $subjects = Subject::all();
        $courses = \App\Models\Course::orderBy('name')->get();
        $availableSemesters = Subject::pluck('semester')->unique()->sort()->values();
        
        return view('notes.index', compact('notes', 'subjects', 'courses', 'availableSemesters'));
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

        // 🛡️ Engagement Check: Is saved?
        $isSaved = Auth::check() 
            ? SavedNote::where('user_id', Auth::id())->where('note_id', $note->id)->exists() 
            : false;

        return view('notes.show', compact('note', 'related', 'seoTitle', 'seoDescription', 'schema', 'isSaved'));
    }

    public function toggleSave(Note $note)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Login required to bookmark knowledge assets.');
        }

        $userId = Auth::id();
        $saved = SavedNote::where('user_id', $userId)->where('note_id', $note->id)->first();

        if ($saved) {
            $saved->delete();
            return back()->with('success', 'Asset removed from your library.');
        }

        SavedNote::create([
            'user_id' => $userId,
            'note_id' => $note->id,
        ]);

        return back()->with('success', 'Asset successfully bookmarked to your library! 📚');
    }

    public function addReview(Request $request, Note $note)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'helped_in_exam' => 'required|boolean',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $note->reviews()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'rating' => $validated['rating'],
                'helped_in_exam' => $validated['helped_in_exam'],
                'feedback' => $validated['feedback'] ?? null,
            ]
        );

        return back()->with('success', 'Your academic validation has been recorded! +5 Karma granted.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required',
            'custom_subject' => 'required_if:subject_id,other|nullable|string|max:100',
            'file' => 'required|mimes:pdf,doc,docx,ppt,pptx,zip,jpg,png|max:10240', // 10MB
        ], [
            'file.max' => 'The file is too large! Maximum allowed is 10MB.',
            'file.mimes' => 'Unsupported file format! Please use PDF, DOC, or common images.',
            'custom_subject.required_if' => 'Please provide the custom subject name.',
        ]);

        if ($request->subject_id !== 'other') {
            $request->validate(['subject_id' => 'exists:subjects,id']);
        }

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
                'file_path' => $secureUrl,
                'user_id' => Auth::id(),
                'college_id' => $college_id,
                'subject_id' => $request->subject_id === 'other' ? null : $request->subject_id,
                'custom_subject' => $request->subject_id === 'other' ? $request->custom_subject : null,
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

        // 🛡️ Handle AI Notes (Print to PDF)
        if ($note->note_type === 'ai') {
            return redirect()->route('notes.print', $note->slug);
        }

        if (filter_var($note->file_path, FILTER_VALIDATE_URL)) {
            return redirect()->away($note->file_path);
        }

        return Storage::disk('public')->download($note->file_path);
    }

    public function print($slug)
    {
        $note = Note::where('slug', $slug)->firstOrFail();
        $note->load(['subject', 'user']);
        return view('notes.print', compact('note'));
    }

    public function generateForm()
    {
        $subjects = Subject::all();
        $courses = \App\Models\Course::orderBy('name')->get();

        return view('notes.generate', compact('subjects', 'courses'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:500',
            'subject_id' => 'required',
            'custom_subject' => 'required_if:subject_id,other|nullable|string|max:100',
            'detail_level' => 'required|in:quick,detailed',
        ]);

        $user = Auth::user();

        // 🛡️ Enforcement: 1 AI Generation per Day
        $lastAiNote = \App\Models\AiUsage::where('user_id', $user->id)
            ->where('type', 'note')
            ->where('created_at', '>', now()->subDay())
            ->first();

        if ($lastAiNote && $user->role !== 'admin') {
            return back()->withInput()->with('error', "Bhai, energy-saving mode on hai! You can generate only 1 AI note per day. Check back tomorrow! 🔋");
        }

        // Resolve subject name
        $subjectName = 'General';
        if ($request->subject_id !== 'other') {
            $subject = Subject::find($request->subject_id);
            $subjectName = $subject ? $subject->name : 'General';
        } else {
            $subjectName = $request->custom_subject;
        }

        $result = $this->performAiGeneration($request->topic, $subjectName, $request->detail_level);

        if (isset($result['error'])) {
            return back()->withInput()->with('error', 'AI generation failed: ' . $result['error']);
        }

        $aiContent = $result['content'];
        $model = $result['model'] ?? 'unknown';
        $usage = $result['usage'] ?? [];

        $note = Note::create([
            'title' => $request->topic,
            'note_type' => 'ai',
            'ai_content' => trim($aiContent),
            'file_path' => null,
            'user_id' => $user->id,
            'college_id' => $user->college_id ?? 1,
            'subject_id' => $request->subject_id === 'other' ? null : $request->subject_id,
            'custom_subject' => $request->subject_id === 'other' ? $request->custom_subject : null,
            'is_verified' => true,
        ]);

        // 📊 Log Usage Metadata
        \App\Models\AiUsage::create([
            'user_id' => $user->id,
            'model' => $model,
            'type' => 'note',
            'topic' => $request->topic,
            'prompt_tokens' => $usage['promptTokenCount'] ?? 0,
            'candidates_tokens' => $usage['candidatesTokenCount'] ?? 0,
            'total_tokens' => $usage['totalTokenCount'] ?? 0,
            'metadata' => [
                'detail_level' => $request->detail_level,
                'subject' => $subjectName
            ]
        ]);

        return redirect()->route('notes.show', $note->slug)->with('success', '🤖 AI Notes generated successfully! Today\'s quota used.');
    }
}
