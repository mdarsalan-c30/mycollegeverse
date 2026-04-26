<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobBoardController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::with(['recruiter', 'targetCollege'])
            ->where('is_approved', true)
            ->where('status', 'active');

        // College-based targeting
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role === 'student' && $user->college_id) {
                $query->where(function($q) use ($user) {
                    $q->whereNull('target_college_id')
                      ->orWhere('target_college_id', $user->college_id);
                });
            } elseif ($user->role === 'recruiter') {
                $query->whereNull('target_college_id');
            }
        } else {
            $query->whereNull('target_college_id');
        }

        // 🔍 Filter by type/location
        $filter = $request->get('filter');
        if ($filter === 'remote') {
            $query->where(function($q) {
                $q->where('location', 'like', '%remote%')
                  ->orWhere('location', 'like', '%Remote%')
                  ->orWhereNull('location');
            });
        } elseif ($filter === 'internship') {
            $query->where('type', 'Internship');
        } elseif ($filter === 'fulltime') {
            $query->where('type', 'Full-time');
        }

        // 🔍 Keyword search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $jobs = $query->latest()->paginate(12)->withQueryString();

        return view('jobs.index', compact('jobs', 'filter'));
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
            "description" => $job->description,
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
