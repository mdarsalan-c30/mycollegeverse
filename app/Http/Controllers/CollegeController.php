<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\College;
use App\Models\Note;
use App\Models\Professor;
use App\Models\CollegeReview;
use App\Models\CollegeRequest;
use Illuminate\Support\Facades\Auth;

class CollegeController extends Controller
{
    public function index()
    {
        $colleges = College::withCount(['notes', 'professors', 'users'])->get();
        $myPendingRequest = Auth::check()
            ? CollegeRequest::where('user_id', Auth::id())->where('status', 'pending')->first()
            : null;
        return view('colleges.index', compact('colleges', 'myPendingRequest'));
    }

    public function show(College $college)
    {
        $college->load(['notes.user', 'professors', 'notes.subject'])
                ->load(['reviews' => function($query) {
                    $query->where('status', 'approved')->with('user');
                }])
                ->load(['posts' => function($query) {
                    $query->with(['user', 'comments.user', 'likes'])->latest();
                }])
                ->loadCount('users');

        // SEO Expert Injection 🔍
        $seoTitle = "{$college->name} Verse - Experience, Notes & Community | MyCollegeVerse";
        $seoDescription = "Access verified notes, professor reviews, and the campus feed for {$college->name}. Connect with students and build your academic prestige.";
        
        // JSON-LD EducationalOrganization Schema
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "EducationalOrganization",
            "name" => $college->name,
            "description" => $college->about ?? $seoDescription,
            "url" => route('colleges.show', $college->slug),
            "address" => [
                "@type" => "PostalAddress",
                "addressLocality" => $college->city,
                "addressRegion" => $college->state,
                "addressCountry" => "India"
            ]
        ];

        return view('colleges.show', compact('college', 'seoTitle', 'seoDescription', 'schema'));
    }

    public function rate(Request $request, College $college)
    {
        $request->validate([
            'campus_rating' => 'required|integer|min:1|max:5',
            'faculty_rating' => 'required|integer|min:1|max:5',
            'academic_rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'verification_id' => 'required|string|max:50',
        ]);

        CollegeReview::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'college_id' => $college->id
            ],
            [
                'campus_rating' => $request->campus_rating,
                'faculty_rating' => $request->faculty_rating,
                'academic_rating' => $request->academic_rating,
                'comment' => $request->comment,
                'verification_id' => $request->verification_id,
                'status' => 'pending' // Always reset to pending for verification
            ]
        );

        return back()->with('success', 'Your review has been submitted for verification. It will be published once approved by a moderator!');
    }

    public function requestCollege(Request $request)
    {
        $request->validate([
            'college_name' => 'required|string|max:255',
            'city'         => 'required|string|max:100',
            'state'        => 'required|string|max:100',
            'student_email'=> 'nullable|email|max:255',
            'message'      => 'nullable|string|max:500',
        ]);

        // Prevent duplicate pending requests for same college by same user
        $exists = CollegeRequest::where('user_id', Auth::id())
            ->where('college_name', $request->college_name)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('info', 'You already have a pending request for this college!');
        }

        CollegeRequest::create([
            'user_id'       => Auth::id(),
            'college_name'  => $request->college_name,
            'city'          => $request->city,
            'state'         => $request->state,
            'student_email' => $request->student_email,
            'message'       => $request->message,
        ]);

        return back()->with('success', 'Your college request has been submitted! We\'ll review and add it soon. 🎓');
    }
}
