<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ResumeController extends Controller
{
    public function index()
    {
        $resumes = Auth::check() 
            ? Auth::user()->resumes()->latest()->get() 
            : collect();

        return view('resumes.index', compact('resumes'));
    }

    public function create()
    {
        $user = Auth::user();
        $existingProjects = $user ? $user->projects()->get() : collect();
        
        // Initial state for the builder
        $initialData = [
            'personal' => [
                'name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'phone' => $user->mobile ?? '',
                'location' => '',
                'website' => '',
                'summary' => $user->mentor_bio ?? '',
            ],
            'education' => [
                [
                    'institution' => $user->college->name ?? '',
                    'degree' => $user->course->name ?? '',
                    'year' => $user->year ?? '',
                    'description' => '',
                ]
            ],
            'experience' => [],
            'skills' => $user->skills ?? [],
            'projects' => [],
            'custom_sections' => []
        ];

        return view('resumes.builder', compact('initialData', 'existingProjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'data' => 'required|array',
        ]);

        $resume = Resume::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'data' => $request->data,
            'template_id' => $request->template_id ?? 'ats-clean',
            'is_public' => $request->is_public ?? true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Resume manifested successfully!',
            'slug' => $resume->slug,
            'redirect' => route('resumes.show', $resume->slug)
        ]);
    }

    public function show($slug)
    {
        $resume = Resume::where('slug', $slug)->firstOrFail();
        
        // Increment view count
        $resume->increment('views_count');

        $template = 'resumes.templates.' . $resume->template_id;
        
        if (!view()->exists($template)) {
            $template = 'resumes.templates.ats-clean';
        }

        return view($template, compact('resume'));
    }

    public function destroy(Resume $resume)
    {
        $this->authorize('delete', $resume);
        $resume->delete();

        return back()->with('success', 'Resume deleted from the Verse.');
    }

    public function aiReview(Request $request)
    {
        $resumeData = $request->input('resume');
        $type = $request->input('type', 'review');

        $prompt = $type === 'roast' 
            ? "You are a brutal, sarcastic tech recruiter. Roast this student resume data in 3-4 sentences. Be funny but helpful. Resume Data: " . json_encode($resumeData)
            : "Analyze this resume and provide 3 actionable improvements. Resume Data: " . json_encode($resumeData);

        try {
            $apiKey = config('services.gemini.key');
            $response = \Illuminate\Support\Facades\Http::post(\"https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}\", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            $feedback = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? \"The Verse is currently quiet. Try again later.\";
            
            return response()->json(['feedback' => $feedback]);
        } catch (\Exception $e) {
            return response()->json(['feedback' => \"AI is currently offline. Focus on your projects for now!\"], 500);
        }
    }
}
