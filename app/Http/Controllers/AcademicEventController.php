<?php

namespace App\Http\Controllers;

use App\Models\AcademicEvent;
use App\Models\Subject;
use App\Models\User;
use App\Traits\GeneratesAiContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicEventController extends Controller
{
    use GeneratesAiContent;

    /**
     * Store a new academic discovery (Manual or Manifested).
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:exam,mst,project,assignment,quiz,lab,other',
            'due_date' => 'required|date|after:now',
            'subject_id' => 'nullable|exists:subjects,id',
            'priority' => 'required|in:low,medium,high',
            'description' => 'nullable|string|max:1000',
            'is_official_proposal' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        $event = AcademicEvent::create([
            'title' => $request->title,
            'type' => $request->type,
            'due_date' => $request->due_date,
            'subject_id' => $request->subject_id,
            'priority' => $request->priority,
            'description' => $request->description,
            'user_id' => $user->id,
            'college_id' => $user->college_id,
            'course_id' => $user->course_id,
            'semester' => $user->semester,
            'is_official' => false, // Only Admins can set true directly
            'is_verified' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Academic Pulse Manifested! Stay vigilant.',
            'event' => $event
        ]);
    }

    /**
     * Verify an official proposal from a peer.
     */
    public function verify(AcademicEvent $event)
    {
        $user = Auth::user();

        // Prevent double verification or self-verification for rewards
        if ($event->user_id === $user->id) {
            return response()->json(['error' => 'You cannot verify your own manifestation.'], 403);
        }

        $event->increment('verification_count');

        // Logic: Threshold to become "Official"
        if ($event->verification_count >= 5 && !$event->is_official) {
            $event->update(['is_official' => true, 'is_verified' => true]);
            
            // 💰 Reward the Creator & Notify them
            if ($event->user_id) {
                $creator = User::find($event->user_id);
                // Notification for creator
                $creator->notify(new \App\Notifications\AcademicSignal($event));
            }

            // 🏮 BATCH BROADCAST: Notify all classmates matching targeting node
            $batchmates = User::where('college_id', $event->college_id)
                ->where('course_id', $event->course_id)
                ->where('semester', $event->semester)
                ->where('id', '!=', $event->user_id) // Avoid double notifying creator
                ->get();

            if ($batchmates->isNotEmpty()) {
                \Illuminate\Support\Facades\Notification::send($batchmates, new \App\Notifications\AcademicSignal($event));
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Manifestation Verified. Accuracy is the foundation of the Verse.'
        ]);
    }

    /**
     * AI Manifestation: Scan a notice or image for deadlines.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'text_data' => 'nullable|string',
            'image_url' => 'nullable|string',
        ]);

        $input = $request->text_data ?: $request->image_url;
        
        if (!$input) {
            return response()->json(['error' => 'No manifestation data provided.'], 400);
        }

        // Logic: Send to Gemini AI specifically optimized for Assessment Extraction
        $result = $this->extractDeadlinesFromNotice($input);

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }
}
