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
        
        // Defensive Registry Sync: Only show professors with an institutional anchor 🏢
        // This prevents 500 errors when rendering orphaned nodes.
        $query = Professor::whereHas('college')->with(['reviews', 'college']);

        if ($user && $user->college_id) {
            $query->orderByRaw('college_id = ? DESC', [$user->college_id]);
        }
        
        $professors = $query->latest()->get();
            
        $myPendingRequest = Auth::check()
            ? ProfessorRequest::where('user_id', Auth::id())->where('status', 'pending')->first()
            : null;
            
        return view('professors.index', compact('professors', 'myPendingRequest'));
    }

    public function show(Professor $professor)
    {
        try {
            $professor->load(['reviews.user', 'college']);
            return view('professors.show', compact('professor'));
        } catch (\Exception $e) {
            \Log::error("Faculty Detail Crash | Professor ID: {$professor->id} | Error: " . $e->getMessage());
            return redirect()->route('professors.index')->with('error', "Faculty Node Data Corrupted: " . $e->getMessage());
        }
    }

    public function rate(Request $request, Professor $professor, \App\Services\ImageKitService $imageKit)
    {
        $user = Auth::user();

        $rules = [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ];

        // Optional ID card for professor reviews (for anonymity/safety)
        if ($request->hasFile('id_card_image')) {
            $rules['id_card_image'] = 'image|max:2048';
        }

        $request->validate($rules);

        // Upload ID Card if provided and user doesn't have one
        if ($request->hasFile('id_card_image') && !$user->id_card_url) {
            $upload = $imageKit->upload(
                $request->file('id_card_image'),
                "id_card_{$user->id}_" . time() . ".jpg",
                '/verifications'
            );
            
            if ($upload) {
                $user->update(['id_card_url' => $upload->filePath]);
            }
        }

        Review::create([
            'user_id' => $user->id,
            'professor_id' => $professor->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Observations recorded in the faculty ledger.');
    }

    public function requestProfessor(Request $request, \App\Services\ImageKitService $imageKit)
    {
        $request->validate([
            'professor_name' => 'required|string|max:255',
            'department'     => 'required|string|max:255',
            'college_name'   => 'required|string|max:255',
            'message'        => 'nullable|string|max:500',
            'profile_photo_url' => 'nullable|url',
            'profile_photo_file' => 'nullable|image|max:1024', // 1MB max for faculty
        ]);

        // Prevent duplicate pending requests
        $exists = ProfessorRequest::where('user_id', Auth::id())
            ->where('professor_name', $request->professor_name)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('info', 'Synchronization in progress for this faculty node.');
        }

        $photoUrl = $request->profile_photo_url;

        // Handle File Upload (Prioritize over URL for higher fidelity)
        if ($request->hasFile('profile_photo_file')) {
            $upload = $imageKit->upload(
                $request->file('profile_photo_file'),
                "request_" . \Illuminate\Support\Str::slug($request->professor_name) . "_" . time() . ".jpg",
                '/requests'
            );
            
            if ($upload) {
                // Optimization: Store the path. Service will handle transformation during retrieval.
                $photoUrl = $upload->filePath;
            }
        }

        ProfessorRequest::create([
            'user_id'        => Auth::id(),
            'professor_name' => $request->professor_name,
            'department'     => $request->department,
            'college_name'   => $request->college_name,
            'message'        => $request->message,
            'profile_photo_url' => $photoUrl,
        ]);

        return back()->with('success', "Faculty integration request dispatched to the multiverse council.");
    }
}
