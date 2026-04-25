<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnterpriseController extends Controller
{
    /**
     * Display the Enterprise Hub Dashboard.
     */
    public function index()
    {
        // 1. Fetch Recruiters with Job Stats
        $recruiters = User::where('role', 'recruiter')
            ->withCount('jobPostings')
            ->latest()
            ->paginate(10);

        // 2. Pending Jobs for Moderation
        $pendingJobs = JobPosting::where('is_approved', false)
            ->with('recruiter')
            ->latest()
            ->get();

        // 3. Overall Stats
        $stats = [
            'total_recruiters' => User::where('role', 'recruiter')->count(),
            'active_jobs' => JobPosting::where('is_approved', true)->count(),
            'pending_jobs' => JobPosting::where('is_approved', false)->count(),
            'total_applications' => JobApplication::count(),
            'total_hired' => JobApplication::where('status', 'shortlisted')->count(),
        ];

        return view('admin.enterprise.index', compact('recruiters', 'pendingJobs', 'stats'));
    }

    /**
     * Approve a Job Posting.
     */
    public function approveJob(JobPosting $job)
    {
        $job->update(['is_approved' => true]);
        return back()->with('success', "Job node '{$job->title}' has been authorized and is now live in the multiverse.");
    }

    /**
     * Reject/Delete a Job Posting.
     */
    public function rejectJob(JobPosting $job)
    {
        $title = $job->title;
        $job->delete();
        return back()->with('warning', "Job node '{$title}' has been rejected and purged from the archive.");
    }

    /**
     * Toggle Recruiter Status (Ban/Unban).
     */
    public function toggleRecruiterStatus(User $user)
    {
        if ($user->role !== 'recruiter') abort(403);

        $newStatus = $user->status === 'active' ? 'banned' : 'active';
        $user->update(['status' => $newStatus]);

        return back()->with('success', "Recruiter '{$user->name}' status updated to {$newStatus}.");
    }
}
