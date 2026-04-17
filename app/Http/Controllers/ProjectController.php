<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectEndorsement;
use App\Models\User;
use App\Notifications\AcademicSignal; // Reuse for project alerts
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Store a new Proof-of-Work artifact.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'stream' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'artifact_url' => 'required|url',
            'cover_image' => 'nullable|image|max:2048', // 2MB Cover
            'type' => 'required|in:case_study,research,design,code,essay,other'
        ]);

        $user = Auth::user();
        $coverPath = null;

        if ($request->hasFile('cover_image')) {
            $ik = app(\App\Services\ImageKitService::class);
            $upload = $ik->upload($request->file('cover_image'), 'project_' . time(), 'projects');
            if ($upload && isset($upload->filePath)) {
                $coverPath = $upload->filePath;
            }
        }

        $project = Project::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'stream' => $request->stream,
            'description' => $request->description,
            'artifact_url' => $request->artifact_url,
            'cover_image_path' => $coverPath,
            'type' => $request->type,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Project Manifested in the Vault!',
            'project' => $project
        ]);
    }

    /**
     * Vouch and Verify a peer's project.
     */
    public function verify(Project $project)
    {
        $user = Auth::user();
        
        if ($project->user_id === $user->id) {
            return response()->json(['error' => 'Self-verification is not permitted.'], 403);
        }

        $project->increment('verification_count');

        // Threshold for "Official Proof-of-Work" (User requested 3-5, I chose 3)
        if ($project->verification_count >= 3 && !$project->is_official) {
            $project->update(['is_official' => true]);
            
            // 💰 Grant 5x Reward (250 Points) - User approved 5x
            // Note: ARS Points are calculated dynamically based on 'Official' projects in the User model later.
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Project Vouched. Credibility enhanced!'
        ]);
    }

    /**
     * Professional Endorsement (Recruiter only)
     */
    public function endorse(Request $request, Project $project)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        if (Auth::user()->role !== 'recruiter') {
            return response()->json(['error' => 'Only industrial recruiters can endorse projects.'], 403);
        }

        $endorsement = ProjectEndorsement::create([
            'project_id' => $project->id,
            'recruiter_id' => Auth::id(),
            'comment' => $request->comment,
            'rating' => $request->rating
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Professional Endorsement Published!'
        ]);
    }

    /**
     * Multi-Artifact Feed (For Recruiter Discovery)
     */
    public function showcase()
    {
        $projects = Project::where('is_official', true)
            ->with(['user', 'endorsements'])
            ->latest()
            ->paginate(12);

        return view('showcase.index', compact('projects'));
    }
}
