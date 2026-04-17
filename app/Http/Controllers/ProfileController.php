<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($user = null)
    {
        try {
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

            // Fetch PoW Vault & Professional History with Fallback Safety
            $projects = collect();
            $experiences = collect();
            $educations = collect();

            if (\Illuminate\Support\Facades\Schema::hasTable('projects')) {
                $projects = $user->projects()
                    ->with(['endorsements.recruiter'])
                    ->latest()
                    ->get();
            }
            
            if (\Illuminate\Support\Facades\Schema::hasTable('user_experiences')) {
                $experiences = $user->experiences()->latest()->get();
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('user_educations')) {
                $educations = $user->educations()->latest()->get();
            }

            $layout = (auth()->check() && auth()->user()->role === 'recruiter') ? 'layouts.recruiter' : 'layouts.app';

            return view('profile.show', compact('user', 'projects', 'experiences', 'educations', 'layout'));
        } catch (\Exception $e) {
            // Log the error but don't crash the entire platform
            \Illuminate\Support\Facades\Log::error("Portfolio Render Error: " . $e->getMessage());
            
            // Re-throw if in debug mode or return a safe view
            if (config('app.debug')) {
                 return response()->json(['error' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()], 500);
            }

            return view('profile.show', [
                'user' => $user,
                'projects' => collect(),
                'experiences' => collect(),
                'educations' => collect()
            ]);
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
