<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MentorshipRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorshipController extends Controller
{
    /**
     * Toggle the mentor status for the authenticated user.
     */
    public function toggleMode(Request $request)
    {
        $user = Auth::user();

        if (!$user->is_mentor_eligible) {
            return back()->with('error', 'Multiverse Protocol Denial: You must be in your Final Year or have 500+ Karma to become a Guide.');
        }

        $user->update([
            'is_mentor' => !$user->is_mentor,
            'mentor_bio' => $request->get('mentor_bio', $user->mentor_bio),
            'mentor_topics' => $request->get('mentor_topics', $user->mentor_topics),
        ]);

        $status = $user->is_mentor ? 'Activated' : 'Deactivated';
        return back()->with('success', "Guide Mode {$status}! Your wisdom is now discoverable.");
    }

    /**
     * Submit a guidance request to a mentor.
     */
    public function requestStore(Request $request, User $mentor)
    {
        $request->validate([
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ]);

        if (!$mentor->is_mentor) {
            return back()->with('error', 'This user is not currently accepting guidance requests.');
        }

        MentorshipRequest::create([
            'requester_id' => Auth::id(),
            'mentor_id' => $mentor->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Guidance Request Dispatched! The Guide will review your query soon.');
    }

    /**
     * Respond to a mentorship request.
     */
    public function respond(Request $request, MentorshipRequest $mRequest)
    {
        if ($mRequest->mentor_id !== Auth::id()) {
            abort(403);
        }

        $mRequest->update(['status' => $request->status]);

        if ($request->status === 'accepted') {
            // Logic to potentially open a chat or send a notification
            return back()->with('success', 'Request Accepted! Synchronization initiate.');
        }

        return back()->with('info', "Request marked as {$request->status}.");
    }

    /**
     * Complete a session and award Karma.
     */
    public function complete(Request $request, MentorshipRequest $mRequest)
    {
        if ($mRequest->requester_id !== Auth::id()) {
            abort(403);
        }

        $mRequest->update([
            'status' => 'completed',
            'rating' => $request->rating,
            'feedback' => $request->feedback
        ]);

        // Award 100 Karma to the Mentor if rating is high
        if ($request->rating >= 4) {
            // Mentor model doesn't store karma directly, but it's calculated.
            // However, we can add a log or record that contributes.
            // For now, let's assume it's recorded via the mentorship_requests table count.
        }

        return back()->with('success', 'Session Completed! Thank you for strengthening the Verse.');
    }
}
