<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Note;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecruiterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Simple role check middleware could be added here
    }

    public function index()
    {
        // Only recruiters should see this
        if (Auth::user()->role !== 'recruiter') {
            return redirect()->route('dashboard')->with('error', 'Access restricted to recruiters.');
        }

        // 1. Top Student Talent (Based on ARS Score / Karma)
        $topTalent = User::where('role', 'student')
            ->with(['college', 'posts'])
            ->get()
            ->sortByDesc('karma')
            ->take(6);

        // 2. Talent Sources (Top Colleges)
        $talentSources = DB::table('users')
            ->join('colleges', 'users.college_id', '=', 'colleges.id')
            ->select('colleges.name', 'colleges.id', DB::raw('count(users.id) as student_count'))
            ->where('users.role', 'student')
            ->groupBy('colleges.id', 'colleges.name')
            ->orderBy('student_count', 'desc')
            ->take(5)
            ->get();

        // 3. Trending Skills (Simulated based on categories of top shared notes recently)
        $trendingSkills = [
            ['name' => 'React Native', 'growth' => '+24%'],
            ['name' => 'Python AI', 'growth' => '+18%'],
            ['name' => 'Cloud Native Go', 'growth' => '+12%'],
            ['name' => 'Cyber-Sec Audit', 'growth' => '+8%'],
        ];

        // 4. Recent Applicants for own jobs
        $recentApplicants = \App\Models\JobApplication::whereHas('job', function($q) {
            $q->where('recruiter_id', Auth::id());
        })
        ->with(['student.college', 'job'])
        ->latest()
        ->take(6)
        ->get();

        // 5. All Colleges for broadcasting choice
        $colleges = \App\Models\College::orderBy('name')->get();

        // 6. Pipeline Intelligence Node Data
        $pipelineStats = \App\Models\JobApplication::whereHas('job', function($q) {
            $q->where('recruiter_id', Auth::id());
        })
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();

        // Ensure all statuses exist even if 0
        $pipelineStats = array_merge([
            'pending' => 0,
            'reviewed' => 0,
            'shortlisted' => 0,
            'rejected' => 0,
        ], $pipelineStats);

        $totalApps = array_sum($pipelineStats);
        $shortlistedCount = $pipelineStats['shortlisted'] ?? 0;
        $conversionRate = $totalApps > 0 ? round(($shortlistedCount / $totalApps) * 100, 1) : 0;

        return view('recruiter.dashboard', compact(
            'topTalent', 
            'talentSources', 
            'trendingSkills', 
            'colleges', 
            'recentApplicants',
            'pipelineStats',
            'totalApps',
            'conversionRate'
        ));
    }

    public function storeJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'location' => 'nullable|string',
            'salary_range' => 'nullable|string',
            'target_college_id' => 'nullable|exists:colleges,id',
        ]);

        $job = Auth::user()->jobPostings()->create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'location' => $request->location,
            'salary_range' => $request->salary_range,
            'target_college_id' => $request->target_college_id,
            'is_approved' => false, // Moderated by admin
        ]);

        return back()->with('success', 'Job posting submitted for admin moderation.');
    }

    public function viewApplicants($jobId)
    {
        $job = JobPosting::where('id', $jobId)
            ->where('recruiter_id', Auth::id())
            ->firstOrFail();

        $applications = $job->applications()->with('student.college')->latest()->get();

        return view('recruiter.applicants', compact('job', 'applications'));
    }

    public function updateApplicationStatus(Request $request, $applicationId)
    {
        $application = \App\Models\JobApplication::findOrFail($applicationId);
        
        // Security check
        if ($application->job->recruiter_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:reviewed,shortlisted,rejected'
        ]);

        $application->update([
            'status' => $request->status,
            'is_seen_by_student' => false
        ]);

        // Automated Feedback Broadcast via Nexus Comms
        $student = $application->student;
        $recruiter = Auth::user();
        $jobTitle = $application->job->title;
        $company = $recruiter->company_name ?? 'The Enterprise Node';

        $messages = [
            'shortlisted' => "Congratulations {$student->name}! Your candidacy for {$jobTitle} has been **Shortlisted**. We will be reaching out via this channel for the next synchronization steps. Great work!",
            'reviewed' => "Hello {$student->name}, your candidacy brief for {$jobTitle} at {$company} has been **Reviewed**. Stay tuned for further network signals regarding your application status.",
            'rejected' => "Signal Update: After reviewing your profile for {$jobTitle}, we have decided to move forward with other talent nodes at this time. Thank you for broadcasting your skills with {$company}.",
        ];

        \App\Models\ChatMessage::create([
            'sender_id' => $recruiter->id,
            'receiver_id' => $student->id,
            'message' => $messages[$request->status] ?? "Your application status for {$jobTitle} has been updated to {$request->status}.",
        ]);

        return back()->with('success', 'Candidacy status updated and student notified via Nexus Comms.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action'          => 'required|in:message,interview,shortlist,reject',
            'application_ids' => 'required|array|min:1',
            'application_ids.*' => 'exists:job_applications,id',
        ]);

        $recruiter   = Auth::user();
        $action      = $request->action;
        $applications = \App\Models\JobApplication::whereIn('id', $request->application_ids)
            ->whereHas('job', fn($q) => $q->where('recruiter_id', $recruiter->id))
            ->with('student', 'job')
            ->get();

        foreach ($applications as $app) {
            $student  = $app->student;
            $jobTitle = $app->job->title;
            $company  = $recruiter->company_name ?? 'Our Company';

            if ($action === 'shortlist') {
                $app->update(['status' => 'shortlisted', 'is_seen_by_student' => false]);
                $message = "🎉 Congratulations {$student->name}! You have been **Shortlisted** for {$jobTitle} at {$company}. We will be in touch shortly with next steps.";

            } elseif ($action === 'reject') {
                $app->update(['status' => 'rejected', 'is_seen_by_student' => false]);
                $message = "Thank you for applying for {$jobTitle} at {$company}. After careful review, we have decided to move forward with other candidates at this time. We wish you the best!";

            } elseif ($action === 'interview') {
                $link       = $request->interview_link ?? '';
                $date       = $request->interview_date ? \Carbon\Carbon::parse($request->interview_date)->format('D, d M Y • h:i A') : '';
                $extraNote  = $request->message ?? '';
                $message    = "📅 **Interview Invitation** — {$jobTitle} at {$company}\n\n";
                if ($date)  $message .= "🗓️ **Date & Time:** {$date}\n";
                if ($link)  $message .= "🔗 **Meeting Link:** {$link}\n";
                if ($extraNote) $message .= "\n{$extraNote}";
                $app->update(['status' => 'shortlisted', 'is_seen_by_student' => false]);

            } else { // message
                $raw     = $request->message ?? "Hello, we have reviewed your application for {$jobTitle}.";
                $message = preg_replace('/\{name\}/i', $student->name, $raw);
            }

            \App\Models\ChatMessage::create([
                'sender_id'   => $recruiter->id,
                'receiver_id' => $student->id,
                'message'     => $message,
            ]);
        }

        $count = $applications->count();
        return back()->with('success', "✅ Bulk action sent to {$count} candidates successfully!");
    }

    public function initializeIntegration()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role !== 'recruiter') {
            abort(403);
        }

        $token = 'VN_' . bin2hex(random_bytes(16));
        $user->update(['integration_token' => $token]);

        return back()->with('success', 'Enterprise Integrated Node initialized successfully.');
    }
}
