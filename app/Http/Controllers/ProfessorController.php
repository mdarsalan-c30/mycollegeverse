<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Professor;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

use App\Models\ProfessorRequest;

class ProfessorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Professor::with('reviews');

        if ($user) {
            $query->orderByRaw('college_id = ? DESC', [$user->college_id]);
        }
        
        $professors = $query->get();
            
        $myPendingRequest = Auth::check()
            ? ProfessorRequest::where('user_id', Auth::id())->where('status', 'pending')->first()
            : null;
            
        return view('professors.index', compact('professors', 'myPendingRequest'));
    }

    public function show($id)
    {
        $professor = Professor::with(['reviews.user', 'college'])->findOrFail($id);
        return view('professors.show', compact('professor'));
    }

    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'professor_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Professor reviewed successfully!');
    }

    public function requestProfessor(Request $request)
    {
        $request->validate([
            'professor_name' => 'required|string|max:255',
            'department'     => 'required|string|max:255',
            'college_name'   => 'required|string|max:255',
            'message'        => 'nullable|string|max:500',
        ]);

        // Prevent duplicate pending requests for same professor by same user
        $exists = ProfessorRequest::where('user_id', Auth::id())
            ->where('professor_name', $request->professor_name)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('info', 'You already have a pending request for this professor!');
        }

        ProfessorRequest::create([
            'user_id'        => Auth::id(),
            'professor_name' => $request->professor_name,
            'department'     => $request->department,
            'college_name'   => $request->college_name,
            'message'        => $request->message,
        ]);

        return back()->with('success', 'Your professor request has been submitted! We\'ll review and add them soon. 🎓');
    }
}
