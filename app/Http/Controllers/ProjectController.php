<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectEndorsement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProjectController extends Controller
{
    /**
     * Display a list of projects in the Ververse.
     */
    public function index()
    {
        $projects = Project::with(['user', 'endorsements'])
            ->orderBy('visibility_score', 'desc')
            ->latest()
            ->paginate(12);

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the upload form.
     */
    public function create()
    {
        $streams = ['Commerce', 'Arts', 'Law', 'Design', 'Journalism', 'Management', 'Science'];
        return view('projects.create', compact('streams'));
    }

    /**
     * Store a new Evidence of Talent (Artifact).
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'stream' => 'required|string',
            'file' => 'required|mimes:pdf,doc,docx,ppt,pptx,zip|max:20480', // 20MB
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB (Mandatory)
        ]);

        try {
            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            $uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');

            // 1. Upload Artifact (Document)
            $file = $request->file('file');
            $fileResponse = Http::attach(
                'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
            )->post("https://api.cloudinary.com/v1_1/{$cloudName}/upload", [
                'upload_preset' => $uploadPreset,
            ]);

            // 2. Upload Cover Image
            $cover = $request->file('cover_image');
            $coverResponse = Http::attach(
                'file', file_get_contents($cover->getRealPath()), $cover->getClientOriginalName()
            )->post("https://api.cloudinary.com/v1_1/{$cloudName}/upload", [
                'upload_preset' => $uploadPreset,
            ]);

            if (!$fileResponse->successful() || !$coverResponse->successful()) {
                throw new \Exception('Failed to synchronize with Cloud Manifest.');
            }

            $project = Project::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'stream' => $request->stream,
                'file_url' => $fileResponse->json('secure_url'),
                'cover_image_url' => $coverResponse->json('secure_url'),
                'visibility_score' => 10, // Base score for manifestation
            ]);

            return redirect()->route('profile.show', Auth::user()->username)->with('success', 'Your Evidence of Talent has been manifested in the Verse! 🗂️');

        } catch (\Exception $e) {
            \Log::error('PoW Manifestation Failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Manifestation Failed: ' . $e->getMessage());
        }
    }

    /**
     * Endorse a project (Social Proof).
     */
    public function endorse(Project $project)
    {
        $user = Auth::user();

        if ($project->user_id === $user->id) {
            return back()->with('error', 'You cannot endorse your own talent.');
        }

        $exists = ProjectEndorsement::where('user_id', $user->id)
            ->where('project_id', $project->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Signal already recorded.');
        }

        ProjectEndorsement::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'strength' => 'standard'
        ]);

        // Boost Visibility Score
        $project->increment('visibility_score', 25);

        return back()->with('success', 'Endorsement recorded! Your signal boosts this talent in the Verse. 🤝');
    }
}
