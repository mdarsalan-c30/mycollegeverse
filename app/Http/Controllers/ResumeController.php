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
                'name' => $user?->name ?? '',
                'email' => $user?->email ?? '',
                'phone' => $user?->mobile ?? '',
                'location' => 'Your City, India',
                'role' => 'B.Tech - Computer Science and Engineering',
                'summary' => 'Briefly describe your expertise and impact here. Focus on performance, scalability, and reliability.'
            ],
            'education' => [
                [
                    'institution' => $user?->college?->name ?? 'Your University',
                    'degree' => $user?->course?->name ?? 'Bachelor of Technology (B.Tech)',
                    'year' => '2020 -- 2024',
                ]
            ],
            'experience' => [
                [
                    'company' => 'Role — Company Name',
                    'date' => 'June 2025 -- Present',
                    'points' => [
                        'Built scalable and efficient cloud-based solutions.',
                        'Collaborated with stakeholders to improve system performance.',
                        'Optimized model performance through hyperparameter tuning.'
                    ]
                ]
            ],
            'skills' => $user?->skills ?? ['Java', 'Python', 'Docker'],
            'projects' => $existingProjects->count() > 0 ? $existingProjects->map(fn($p) => [
                'title' => $p->title,
                'link' => $p->live_url ?? $p->github_url ?? '',
                'description' => strip_tags($p->description)
            ])->toArray() : [
                [
                    'title' => 'Sample Project Title',
                    'link' => 'github.com/your-repo',
                    'description' => 'Developed an automated system for detection and classification using Deep Learning.'
                ]
            ],
        ];

        // Build Default LaTeX String safely using the Premium Template
        $userName = $user?->name ?? 'Your Name';
        $userEmail = $user?->email ?? 'your.email@example.com';
        $userPhone = $user?->mobile ?? '1234567890';
        
        $defaultLatex = "\\documentclass[letterpaper,10pt]{article}\n\n" .
            "\\usepackage{latexsym}\n\\usepackage[empty]{fullpage}\n\\usepackage{titlesec}\n\\usepackage{enumitem}\n\\usepackage[hidelinks]{hyperref}\n\\usepackage{fancyhdr}\n\\usepackage[english]{babel}\n\\usepackage{tabularx}\n\\usepackage{array}\n\\input{glyphtounicode}\n\\usepackage{fontawesome5}\n\\hypersetup{colorlinks=true, urlcolor=black}\n\n" .
            "\\pagestyle{fancy}\n\\fancyhf{}\n\\renewcommand{\\headrulewidth}{0pt}\n\\renewcommand{\\footrulewidth}{0pt}\n\n" .
            "\\addtolength{\\oddsidemargin}{-0.6in}\n\\addtolength{\\textwidth}{1.2in}\n\\addtolength{\\topmargin}{-0.7in}\n\\addtolength{\\textheight}{1.4in}\n\n" .
            "\\raggedright\n\\raggedbottom\n\\setlength{\\parindent}{0pt}\n\n" .
            "\\titleformat{\\section}{\n  \\vspace{-4pt}\\raggedright\\large\\bfseries\\uppercase\n}{}{0em}{}[\\titlerule \\vspace{-4pt}]\n\n" .
            "\\pdfgentounicode=1\n\n" .
            "\\newcommand{\\resumeSubheading}[2]{\n  \\begin{tabular*}{0.97\\textwidth}{l@{\\extracolsep{\\fill}}r}\n    \\textbf{#1} & #2 \\\\\n  \\end{tabular*}\n}\n\n" .
            "%================ EDIT DETAILS =================\n" .
            "\\newcommand{\\name}{{$userName}}\n" .
            "\\newcommand{\\phone}{{$userPhone}}\n" .
            "\\newcommand{\\email}{{$userEmail}}\n\n" .
            "\\begin{document}\n\n" .
            "%================ HEADER =================\n" .
            "\\begin{tabularx}{\\linewidth}{X r}\n" .
            "\\begin{tabular}{@{}l@{}}\n" .
            "{\\huge \\textbf{\\name}} \\\\\n" .
            "{\\small Bachelor Of Technology (B.Tech) - Computer Science and Engineering}\n" .
            "\\end{tabular}\n" .
            "&\n" .
            "\\begin{tabular}{@{}r@{}}\n" .
            "Your City, India \\\\\n" .
            "\\href{mailto:\\email}{\\email} \\\\\n" .
            "+91-\\phone \\\\\n" .
            "\\href{https://linkedin.com/in/yourprofile}{LinkedIn}\n" .
            "\\end{tabular}\n" .
            "\\end{tabularx}\n" .
            "\\vspace{-12pt}\n\n" .
            "%================ SUMMARY =================\n" .
            "\\section{Professional Summary}\n\n" .
            "Briefly describe your expertise and impact here. Focus on performance, scalability, and reliability.\n\n" .
            "%================ EDUCATION =================\n" .
            "\\section{Education}\n" .
            "\\resumeSubheading\n" .
            "{Bachelor of Technology (B.Tech) — Your University}{2020 -- 2024} \\\\\n\n" .
            "%================ EXPERIENCE =================\n" .
            "\\section{Professional Experience}\n\n" .
            "\\resumeSubheading{Role — Company Name}{June 2025 -- Present}\n" .
            "\\begin{itemize}[itemsep=-1pt, topsep=0pt]\n" .
            "  \\item Built scalable and efficient cloud-based solutions.\n" .
            "  \\item Collaborated with stakeholders to improve system performance.\n" .
            "\\end{itemize}\n\n" .
            "%================ PROJECT =================\n" .
            "\\section{Project}\n\n" .
            "\\resumeSubheading{Sample Project Title}{Mar 2024 -- May 2024}\n" .
            "\\begin{itemize}[itemsep=-1pt, topsep=0pt]\n" .
            "  \\item Developed an automated system for detection and classification using Deep Learning.\n" .
            "\\end{itemize}\n\n" .
            "%================ SKILLS =================\n" .
            "\\section{Skills}\n\n" .
            "\\begin{itemize}[itemsep=-1pt, topsep=0pt]\n" .
            "  \\item \\textbf{Programming:} Java, JavaScript, Python\n" .
            "  \\item \\textbf{Core Concepts:} System Design, Cloud Architecture\n" .
            "\\end{itemize}\n\n" .
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

    public function edit($slug)
    {
        $resume = Resume::where('slug', $slug)->firstOrFail();
        $user = Auth::user();
        $existingProjects = $user ? $user->projects()->get() : collect();

        $roleTemplates = [
            'SDE' => ['summary' => '...', 'skills' => []],
            'Frontend' => ['summary' => '...', 'skills' => []],
        ];

        $initialData = (isset($resume->data['raw_latex'])) ? [] : $resume->data;
        $defaultLatex = $resume->data['raw_latex'] ?? '';

        return view('resumes.builder', [
            'resume_model' => $resume,
            'initialData' => $initialData,
            'existingProjects' => $existingProjects,
            'roleTemplates' => $roleTemplates,
            'defaultLatex' => $defaultLatex
        ]);
    }

    public function show($slug)
    {
        $resume = Resume::where('slug', $slug)->firstOrFail();
        $resume->increment('views_count');
        $template = $resume->template_id ?? 'latex-classic';
        return view("resumes.templates.{$template}", compact('resume'));
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
        if (!$apiKey) return response()->json(['feedback' => "AI is offline."], 500);
        $type = $request->get('type', 'roast');
        $prompt = ($type === 'roast') 
            ? "Roast this student resume JSON brutally but constructively: " . json_encode($request->resume)
            : "Give 3 ATS improvement tips for this resume JSON: " . json_encode($request->resume);
        try {
            $response = \Illuminate\Support\Facades\Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [['parts' => [['text' => $prompt]]]]
            ]);
            return response()->json(['feedback' => $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? "AI busy."]);
        } catch (\Exception $e) { return response()->json(['feedback' => "AI Error."], 500); }
    }
}
