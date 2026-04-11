<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Services\UploadcareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    protected $uploadcare;

    public function __construct(UploadcareService $uploadcare)
    {
        $this->uploadcare = $uploadcare;
    }

    public function store(Request $request, $jobId)
    {
        $job = JobPosting::findOrFail($jobId);

        // Prevent multiple applications
        $existing = JobApplication::where('job_id', $job->id)
            ->where('student_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already applied for this role.');
        }

        $request->validate([
            'resume' => 'required|string', // Uploadcare URL/UUID
            'about_me' => 'required|string|min:20',
            'why_hire' => 'required|string|min:20',
        ]);

        try {
            // Use the service to harden the URL before saving
            $resumeUrl = $this->uploadcare->getCdnUrl($request->resume);

            // Save Application
            $application = JobApplication::create([
                'job_id' => $job->id,
                'student_id' => Auth::id(),
                'resume_path' => $resumeUrl, 
                'resume_shared_link' => $resumeUrl, 
                'about_me' => $request->about_me,
                'why_hire' => $request->why_hire,
                'status' => 'pending'
            ]);

            return redirect()->route('pipeline.index')->with('success', 'Application initialized. Your candidacy is now moving through the Verse Pipeline.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Application Submission Error: ' . $e->getMessage());
            return back()->with('error', 'Critical System Fault: ' . $e->getMessage());
        }
    }
}
