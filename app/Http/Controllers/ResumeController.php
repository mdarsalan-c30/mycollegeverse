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

        // Role-based Templates for Auto-fill
        $roleTemplates = [
            'SDE' => [
                'summary' => 'Passionate Software Development Engineer focused on building scalable web applications and solving complex algorithmic challenges.',
                'skills' => ['Java', 'Spring Boot', 'MySQL', 'System Design', 'Git', 'Data Structures']
            ],
            'Frontend' => [
                'summary' => 'Frontend Developer dedicated to crafting immersive user experiences with modern JavaScript frameworks and responsive design.',
                'skills' => ['React.js', 'Next.js', 'Tailwind CSS', 'TypeScript', 'Redux', 'Figma']
            ],
            'QA' => [
                'summary' => 'Detail-oriented Quality Assurance Engineer experienced in automated testing and ensuring robust software reliability.',
                'skills' => ['Selenium', 'JUnit', 'Postman', 'Manual Testing', 'Bug Tracking', 'CI/CD']
            ],
            'Data' => [
                'summary' => 'Data Analyst focused on extracting actionable insights from complex datasets using statistical modeling and visualization.',
                'skills' => ['Python', 'SQL', 'Pandas', 'Tableau', 'Power BI', 'Statistics']
            ]
        ];

        $initialData = [
            'personal' => [
                'name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'phone' => $user->mobile ?? '',
                'location' => '',
                'role' => '',
                'summary' => ''
            ],
            'education' => [
                [
                    'institution' => $user?->college?->name ?? '',
                    'degree' => $user?->course?->name ?? '',
                    'year' => ($user && $user->year) ? "Graduating " . (2024 + (4 - (int)$user->year)) : '',
                ]
            ],
            'experience' => [],
            'skills' => $user->skills ?? [],
            'projects' => $existingProjects->map(fn($p) => [
                'title' => $p->title,
                'link' => $p->live_url ?? $p->github_url ?? '',
                'description' => strip_tags($p->description)
            ])->toArray(),
        ];

        // Build Default LaTeX String safely in PHP
        $userName = $user->name ?? 'Vanshika Singh';
        $defaultLatex = "\\documentclass[letterpaper,10pt]{article}\n\n" .
            "% HEADER\n" .
            "\\huge \\textbf{{$userName}}\n" .
            "\\small Bachelor Of Technology (B.Tech)\n\n" .
            "Noida, India\n" .
            "\\email{vanshikas117@gmail.com}\n" .
            "\\phone{8076343451}\n\n" .
            "\\section{Professional Summary}\n" .
            "Cloud Engineer with practical experience...\n\n" .
            "\\section{Education}\n" .
            "\\resumeSubheading{Bachelor of Technology (B.Tech)}{AKTU}\n\n" .
            "\\end{document}";

        return view('resumes.builder', compact('initialData', 'existingProjects', 'roleTemplates', 'defaultLatex'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'data' => 'required|array',
            'template_id' => 'required|string'
        ]);

        $resume = Resume::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => (string) \Illuminate\Support\Str::uuid(),
            'template_id' => $request->template_id,
            'data' => $request->data,
            'is_public' => true
        ]);

        return response()->json([
            'status' => 'success',
            'redirect' => route('resumes.show', $resume->slug)
        ]);
    }

    public function show($slug)
    {
        $resume = Resume::where('slug', $slug)->firstOrFail();
        
        $resume->increment('views_count');

        $template = $resume->template_id ?? 'ats-clean';
        $viewPath = "resumes.templates.{$template}";

        if (!view()->exists($viewPath)) {
            $viewPath = 'resumes.templates.ats-clean';
        }

        return view($viewPath, compact('resume'));
    }

    public function destroy(Resume $resume)
    {
        if ($resume->user_id !== Auth::id()) abort(403);
        $resume->delete();
        return back()->with('success', 'Resume archived successfully.');
    }

    public function aiReview(Request $request)
    {
        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            return response()->json(['feedback' => "AI is offline."], 500);
        }

        $type = $request->get('type', 'roast');
        $prompt = ($type === 'roast') 
            ? "Roast this student resume JSON brutally but constructively: " . json_encode($request->resume)
            : "Give 3 ATS improvement tips for this resume JSON: " . json_encode($request->resume);

        try {
            $response = \Illuminate\Support\Facades\Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [['parts' => [['text' => $prompt]]]]
            ]);

            return response()->json(['feedback' => $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? "AI busy."]);
        } catch (\Exception $e) {
            return response()->json(['feedback' => "AI Error."], 500);
        }
    }

}
