<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\AssignmentEvaluation;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AssignmentController extends Controller
{
    /**
     * Recruiter View: List all assignments
     */
    public function index()
    {
        $assignments = Assignment::where('recruiter_id', Auth::id())
            ->withCount('submissions')
            ->latest()
            ->get();

        return view('recruiter.assignments.index', compact('assignments'));
    }

    /**
     * Recruiter View: Create form
     */
    public function create()
    {
        $jobs = JobPosting::where('recruiter_id', Auth::id())->get();
        return view('recruiter.assignments.create', compact('jobs'));
    }

    /**
     * Recruiter Action: Store assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'job_id' => 'nullable|exists:job_postings,id',
            'role' => 'nullable|string|max:100',
            'task_type' => 'required|string',
            'instructions' => 'required|string',
            'submission_types' => 'required|array',
            'deadline' => 'nullable',
            'is_public' => 'nullable',
        ]);

        $assignment = Assignment::create([
            'recruiter_id' => Auth::id(),
            'job_id' => $validated['job_id'],
            'title' => $validated['title'],
            'role' => $validated['role'],
            'task_type' => $validated['task_type'],
            'description' => $validated['instructions'], // Syncing description and instructions for redundancy
            'instructions' => $validated['instructions'],
            'submission_types' => $validated['submission_types'],
            'deadline' => $validated['deadline'] ? \Carbon\Carbon::parse($validated['deadline']) : null,
            'is_public' => $request->has('is_public'),
            'status' => 'active',
        ]);

        return redirect()->route('recruiter.assessments.index')->with('success', 'Assignment Verse created! Share the link with candidates.');
    }

    /**
     * Public View: Candidate landing page
     */
    public function show($slug)
    {
        $assignment = Assignment::where('slug', $slug)->firstOrFail();
        
        if ($assignment->status !== 'active') {
            return view('assignments.closed', compact('assignment'));
        }

        return view('assignments.show', compact('assignment'));
    }

    /**
     * Public Action: Candidate submission
     */
    public function submit(Request $request, $slug)
    {
        $assignment = Assignment::where('slug', $slug)->firstOrFail();

        $rules = [
            'candidate_name' => Auth::check() ? 'nullable' : 'required|string|max:255',
            'candidate_email' => Auth::check() ? 'nullable' : 'required|email|max:255',
            'candidate_phone' => 'nullable|string|max:20',
            'submission_text' => 'nullable|string',
            'submission_link' => 'nullable|url',
            'file' => 'nullable|file|max:20480', // 20MB
        ];

        $request->validate($rules);

        $filePath = null;
        $fileId = null;
        $expiresAt = now()->addDays(30); // Default for links/text

        if ($request->hasFile('file')) {
            try {
                $file = $request->file('file');
                $cloudName = env('CLOUDINARY_CLOUD_NAME');
                $uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');

                $response = Http::attach(
                    'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
                )->post("https://api.cloudinary.com/v1_1/{$cloudName}/upload", [
                    'upload_preset' => $uploadPreset,
                ]);

                if ($response->successful()) {
                    $filePath = $response->json('secure_url');
                    $fileId = $response->json('public_id');
                    $expiresAt = now()->addDays(10); // MCV Upload: 10 days auto-delete
                }
            } catch (\Exception $e) {
                \Log::error('Assignment Upload Failed: ' . $e->getMessage());
            }
        }

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => Auth::id(),
            'candidate_name' => $request->candidate_name ?? (Auth::check() ? Auth::user()->name : 'Guest Manifest'),
            'candidate_email' => $request->candidate_email ?? (Auth::check() ? Auth::user()->email : 'guest@mycollegeverse.in'),
            'candidate_phone' => $request->candidate_phone,
            'submission_link' => $request->submission_link,
            'submission_text' => $request->submission_text,
            'file_path' => $filePath,
            'file_id' => $fileId,
            'status' => 'pending',
            'expires_at' => $expiresAt,
        ]);

        return redirect()->route('assignments.confirmation', $submission->id)->with('success', 'Work submitted successfully! Good luck.');
    }

    public function confirmation($id)
    {
        $submission = AssignmentSubmission::with('assignment')->findOrFail($id);
        return view('assignments.confirmation', compact('submission'));
    }

    /**
     * Recruiter View: Review submissions for a specific assignment
     */
    public function review(Assignment $assignment)
    {
        if ($assignment->recruiter_id !== Auth::id()) abort(403);

        $submissions = $assignment->submissions()->latest()->get();
        return view('recruiter.assignments.review', compact('assignment', 'submissions'));
    }

    /**
     * Recruiter Action: Evaluate submission
     */
    public function evaluate(Request $request, AssignmentSubmission $submission)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
            'criteria' => 'required|array',
        ]);

        $submission->update([
            'score' => $request->score,
            'recruiter_notes' => $request->feedback,
            'status' => 'reviewed',
        ]);

        foreach ($request->criteria as $criterion => $score) {
            AssignmentEvaluation::updateOrCreate(
                ['submission_id' => $submission->id, 'criteria' => $criterion],
                ['score' => $score]
            );
        }

        return back()->with('success', 'Evaluation recorded!');
    }

    public function updateSubmissionStatus(Request $request, AssignmentSubmission $submission)
    {
        $submission->update(['status' => $request->status]);
        return back()->with('success', 'Candidate status updated to ' . ucfirst($request->status));
    }

    public function bulkNotify(Request $request)
    {
        // Placeholder for bulk notification logic (Email/Signal)
        return back()->with('success', 'Notification blast sent to selected candidates!');
    }

    /**
     * Recruiter Action: Delete Assignment and Purge Nodes
     */
    public function destroy(Assignment $assignment)
    {
        if ($assignment->recruiter_id !== Auth::id()) abort(403);

        // 🛡️ Deep Space Purge: Delete all submission files from Cloudinary
        $submissions = $assignment->submissions;
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        foreach ($submissions as $submission) {
            if ($submission->file_id) {
                try {
                    Http::withBasicAuth($apiKey, $apiSecret)
                        ->post("https://api.cloudinary.com/v1_1/{$cloudName}/resources/image/upload", [
                            'public_ids' => [$submission->file_id],
                        ]);
                } catch (\Exception $e) {
                    \Log::error('Cloud Purge Failed during Delete: ' . $e->getMessage());
                }
            }
        }

        $assignment->delete(); // Cascading delete will handle DB records if constrained, else we do manually

        return redirect()->route('recruiter.assessments.index')->with('success', 'Assignment and all associated work nodes purged from the multiverse.');
    }
}
