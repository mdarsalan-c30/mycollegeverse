<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Note;
use App\Traits\GeneratesAiContent;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    use GeneratesAiContent;

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

        $notes = $query->with(['user', 'college'])->latest()->paginate(15);
        
        if ($notes->isEmpty() && $request->has('search')) {
            session()->flash('warning', "Knowledge Archive blank for query: '{$request->search}'");
        }

        return view('admin.notes.index', compact('notes'));
    }

    public function bulkGenerateForm()
    {
        $subjects = Subject::all();
        $courses = Course::orderBy('name')->get();
        return view('admin.notes.bulk', compact('subjects', 'courses'));
    }

    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'topics' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'detail_level' => 'required|in:quick,detailed,exam',
        ]);

        $topics = array_filter(array_map('trim', explode("\n", $request->topics)));
        $subject = Subject::findOrFail($request->subject_id);
        
        if (count($topics) > 10) {
            return back()->with('error', 'Please limit bulk generation to 10 topics at a time to avoid timeouts.');
        }

        $successCount = 0;
        $errors = [];

        foreach ($topics as $topic) {
            $result = $this->performAiGeneration($topic, $subject->name, $request->detail_level);

            if (isset($result['error'])) {
                $errors[] = "Failed for topic '{$topic}': " . $result['error'];
                continue;
            }

            Note::create([
                'title' => $topic,
                'note_type' => 'ai',
                'ai_content' => $result['content'],
                'user_id' => Auth::id(),
                'college_id' => Auth::user()->college_id ?? 1,
                'subject_id' => $subject->id,
                'is_verified' => true,
            ]);
            $successCount++;
        }

        if ($successCount > 0) {
            $msg = "Successfully generated {$successCount} AI notes for {$subject->name}.";
            if (!empty($errors)) {
                $msg .= " However, some failed: " . implode(', ', $errors);
            }
            return redirect()->route('admin.notes')->with('success', $msg);
        }

        return back()->with('error', 'Generation failed for all topics. Errors: ' . implode(', ', $errors));
    }

    /**
     * Verify a specific note asset.
     */
    public function verify(Note $note)
    {
        $note->update(['is_verified' => true]);

        // Audit Logging 🛡️
        ApprovalLog::safeCreate([
            'admin_id' => Auth::id(),
            'action' => 'note_verified',
            'target_type' => 'Note',
            'target_id' => $note->id,
            'metadata' => [
                'title' => $note->title,
                'author' => optional($note->user)->name ?? 'Unknown',
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
        $authorName = optional($note->user)->name ?? 'Unknown';

        // Log before deletion to preserve asset history
        ApprovalLog::safeCreate([
            'admin_id' => Auth::id(),
            'action' => 'note_purged',
            'target_type' => 'Note',
            'target_id' => $note->id,
            'metadata' => [
                'title' => $title,
                'author' => $authorName,
            ],
        ]);

        $note->delete();

        return back()->with('success', "Knowledge Asset '{$title}' has been rejected and removed from the multiverse.");
    }
}
