<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Post;
use App\Models\Professor;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect nodes to their respective command centers
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'recruiter') {
            return redirect()->route('recruiter.dashboard');
        }
        
        // Personalized Campus Notes
        $myNotes = collect();
        if ($user->college_id) {
            $myNotes = Note::where('college_id', $user->college_id)
                ->with(['user', 'subject'])
                ->latest()
                ->take(4)
                ->get();
        }

        // Top Performers across the platform
        $topPerformers = User::withCount('notes')
            ->orderBy('notes_count', 'desc')
            ->take(3)
            ->get();

        // Real Subjects (Resilient Check)
        $subjects = \Schema::hasTable('subjects') ? Subject::take(6)->get() : collect();

        // Matched Job Opportunities (Global + Targeted) 
        $matchedJobs = \App\Models\JobPosting::where('is_approved', true)
            ->where(function($q) use ($user) {
                $q->whereNull('target_college_id');
                if ($user->college_id) {
                    $q->orWhere('target_college_id', $user->college_id);
                }
            })
            ->with('recruiter')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', compact('myNotes', 'topPerformers', 'subjects', 'matchedJobs'));
    }
}
