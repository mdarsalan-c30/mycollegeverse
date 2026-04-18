<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($user = null)
    {
        // Explicitly look up by username if a string is provided
        if (is_string($user)) {
            $user = User::where('username', $user)->firstOrFail();
        }

        // Default to Auth user if no username provided
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }

        if (!$user) {
            abort(404);
        }

        return view('profile.show', compact('user'));
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

    public function updateCover(Request $request)
    {
        $request->validate([
            'cover' => 'required|image|max:10240', // 10MB max for covers
        ]);

        $user = auth()->user();
        $ik = app(\App\Services\ImageKitService::class);

        $upload = $ik->upload($request->file('cover'), 'cover_' . $user->username . '_' . time(), 'covers');

        if ($upload && isset($upload->filePath)) {
            $user->update([
                'cover_photo_path' => $upload->filePath
            ]);

            return response()->json([
                'status' => 'success',
                'url' => $user->cover_photo_url,
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
            'bio' => 'nullable|string|max:1000',
            'skills' => 'nullable|array',
            'social_links' => 'nullable|array',
            'social_links.linkedin' => 'nullable|url',
            'social_links.github' => 'nullable|url',
            'social_links.behance' => 'nullable|url',
        ]);

        $data = $request->only(['career_role', 'bio', 'skills', 'social_links']);
        
        // Clean up skills to ensure they are unique and trimmed
        if ($request->has('skills')) {
            $data['skills'] = collect($request->skills)
                ->map(fn($s) => trim($s))
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        }

        $user->update($data);

        return back()->with('success', 'Professional Persona Synchronized! Your portfolio is now fueling the Verse.');
    }
}
