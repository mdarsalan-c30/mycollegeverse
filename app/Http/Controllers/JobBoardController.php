<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobBoardController extends Controller
{
    public function index()
    {
        $query = JobPosting::with(['recruiter', 'targetCollege'])->where('is_approved', true);

        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role === 'student' && $user->college_id) {
                // Students see Global (null) OR their specific college
                $query->where(function($q) use ($user) {
                    $q->whereNull('target_college_id')
                      ->orWhere('target_college_id', $user->college_id);
                });
            } elseif ($user->role === 'recruiter') {
                 // Recruiters see all public jobs on the main board too
                 $query->whereNull('target_college_id');
            }
        } else {
            // Guests see only Global jobs
            $query->whereNull('target_college_id');
        }

        $jobs = $query->latest()->paginate(12);

        return view('jobs.index', compact('jobs'));
    }

    public function show(JobPosting $job)
    {
        if (!$job->is_approved && (!auth()->check() || auth()->user()->id !== $job->recruiter_id)) {
            abort(404);
        }

        // SEO Expert Injection 🔍
        $seoTitle = "{$job->title} at {$job->recruiter->company_name} | Career Verse";
        $seoDescription = "Apply for {$job->title} internship or job role at {$job->recruiter->company_name}. View verified student opportunities on MyCollegeVerse.";
        
        // JSON-LD JobPosting Schema (Google Jobs Optimized)
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "JobPosting",
            "title" => $job->title,
            "description" => $job->job_description,
            "datePosted" => $job->created_at->toIso8601String(),
            "validThrough" => $job->created_at->addMonths(3)->toIso8601String(),
            "hiringOrganization" => [
                "@type" => "Organization",
                "name" => $job->recruiter->company_name,
                "sameAs" => $job->recruiter->company_website
            ],
            "jobLocation" => [
                "@type" => "Place",
                "address" => [
                    "@type" => "PostalAddress",
                    "addressLocality" => "India",
                    "addressRegion" => "Remote/On-site"
                ]
            ],
            "employmentType" => $job->type === 'Internship' ? 'INTERN' : 'FULL_TIME'
        ];

        return view('jobs.show', compact('job', 'seoTitle', 'seoDescription', 'schema'));
    }
}
