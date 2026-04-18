<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($user = null)
    {
        try {
            if (is_string($user)) {
                $user = User::where('username', $user)->firstOrFail();
            }
            if (!$user && auth()->check()) $user = auth()->user();
            if (!$user) abort(404);

            $projects = collect();
            $experiences = collect();
            $educations = collect();
            $layout = (auth()->check() && auth()->user()->role === 'recruiter') ? 'layouts.recruiter' : 'layouts.app';

            return view('profile.show', compact('user', 'projects', 'experiences', 'educations', 'layout'));
        } catch (\Throwable $e) {
            return "CATCH ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine();
        }
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:5120', // 5MB max
        ]);

        $user = auth()->user();
        $ik = app(\App\Services\ImageKitService::class);

        $upload = $ik->upload($request->file('photo'), 'avatar_' . $user->username . '_' . time(), 'avatars');

        if ($upload && isset($upload->filePath)) {
            $user->update([
                'profile_photo_path' => $upload->filePath
            ]);

            return response()->json([
                'status' => 'success',
                'url' => $user->profile_photo_url,
                'path' => $upload->filePath
            ]);
        }

        return response()->json(['error' => 'Upload failed'], 500);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'career_role' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
        ]);

        $user->update($request->only(['career_role', 'bio']));

        return back()->with('success', 'Profile Protocol Synchronized! Your data is now fueling the Verse.');
    }
}
