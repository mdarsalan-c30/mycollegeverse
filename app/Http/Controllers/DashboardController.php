<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Post;
use App\Models\Professor;
use App\Models\Subject;
use App\Models\User;
use App\Models\AcademicEvent;
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
        
        // 🥁 THE ACADEMIC PULSE: Personalized Deadline Stream 🛡️
        $academicPulse = collect();
        if (\Schema::hasTable('academic_events')) {
            $academicPulse = AcademicEvent::forStudent($user)
                ->where('due_date', '>=', now())
                ->with('subject')
                ->orderBy('due_date', 'asc')
                ->take(5)
                ->get();
        }

        // Personalized Campus Notes
        $myNotes = collect();
        if ($user->college_id && \Schema::hasTable('notes')) {
            $myNotes = Note::where('college_id', $user->college_id)
                ->with(['user', 'subject'])
                ->latest()
                ->take(4)
                ->get();
        }

        // Top Performers across the platform
        $topPerformers = collect();
        if (\Schema::hasTable('users')) {
            $topPerformers = User::withCount('notes')
                ->orderBy('notes_count', 'desc')
                ->take(3)
                ->get();
        }

        // Real Subjects (Resilient Check)
        $subjects = \Schema::hasTable('subjects') ? Subject::with('course')->take(6)->get() : collect();

        // Matched Job Opportunities (Global + Targeted) 
        $matchedJobs = collect();
        if (\Schema::hasTable('job_postings')) {
             $matchedJobs = \App\Models\JobPosting::where('is_approved', true)
                ->where(function($q) use ($user) {
                    $q->whereNull('target_college_id');
                    if (\Schema::hasColumn('users', 'college_id') && $user->college_id) {
                        $q->orWhere('target_college_id', $user->college_id);
                    }
                })
                ->with('recruiter')
                ->latest()
                ->take(3)
                ->get();
        }

        // Weekly Performance Analytics
        $weeklyCredits = 0;
        if (\Schema::hasTable('notes')) {
            $weeklyNotes = $user->notes()->where('created_at', '>=', now()->subDays(7))->count();
            $weeklyCredits = $weeklyNotes * 50;
        }

        return view('dashboard', compact(
            'myNotes', 
            'topPerformers', 
            'subjects', 
            'matchedJobs', 
            'weeklyCredits',
            'academicPulse'
        ));
    }
}
