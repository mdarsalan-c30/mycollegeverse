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
}
