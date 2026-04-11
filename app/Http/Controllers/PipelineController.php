<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PipelineController extends Controller
{
    public function index()
    {
        $applications = JobApplication::where('student_id', Auth::id())
            ->with(['job.recruiter'])
            ->latest()
            ->get();

        // Broadcast Seen Signal: Mark all as viewed by student
        JobApplication::where('student_id', Auth::id())
            ->where('is_seen_by_student', false)
            ->update(['is_seen_by_student' => true]);

        return view('student.pipeline', compact('applications'));
    }
}
