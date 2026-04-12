<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display the Knowledge Moderation Queue.
     */
    public function index(Request $request)
    {
        $query = Note::query();

        // High-Fidelity Filtering 🔍
        if ($request->has('status') && $request->status != 'all') {
            $query->where('is_verified', $request->status == 'verified' ? 1 : 0);
        } else {
            // Default to Pending for efficiency
            $query->where('is_verified', 0);
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $notes = $query->with(['user', 'college', 'subject'])
            ->latest()
            ->paginate(15);

        return view('admin.notes.index', compact('notes'));
    }

    /**
     * Verify a specific note asset.
     */
    public function verify(Note $note)
    {
        $note->update(['is_verified' => true]);

        // Audit Logging 🛡️
        ApprovalLog::create([
            'admin_id' => Auth::id(),
            'action' => 'note_verified',
            'target_type' => 'Note',
            'target_id' => $note->id,
            'metadata' => [
                'title' => $note->title,
                'user' => $note->user->name
            ],
        ]);

        return back()->with('success', "Knowledge Asset '{$note->title}' has been verified and is now live.");
    }

    /**
     * Reject and remove a note asset.
     */
    public function destroy(Note $note)
    {
        $title = $note->title;
        $userName = $note->user->name;

        // Log before deletion to preserve asset history
        ApprovalLog::create([
            'admin_id' => Auth::id(),
            'action' => 'note_rejected',
            'target_type' => 'Note',
            'target_id' => $note->id,
            'metadata' => [
                'title' => $title,
                'user' => $userName,
                'action' => 'deleted'
            ],
        ]);

        $note->delete();

        return back()->with('success', "Knowledge Asset '{$title}' has been rejected and removed from the multiverse.");
    }
}
