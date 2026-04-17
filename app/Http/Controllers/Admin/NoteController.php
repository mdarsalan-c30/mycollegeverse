<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Note;
use App\Models\AiUsage;
use App\Models\ApprovalLog;
use App\Traits\GeneratesAiContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        $subjects = Subject::with('course')->orderBy('name')->get();

        // 📊 AI Intelligence Stats (Cached Mode for Stability) 🛡️
        $stats = Cache::remember('admin_ai_stats', 3600 * 6, function() {
            try {
                return [
                    'total_tokens' => \App\Models\AiUsage::sum('total_tokens') ?? 0,
                    'today_tokens' => \App\Models\AiUsage::where('created_at', '>=', now()->startOfDay())->sum('total_tokens') ?? 0,
                    'total_generations' => \App\Models\AiUsage::count(),
                    'today_generations' => \App\Models\AiUsage::where('created_at', '>=', now()->startOfDay())->count(),
                ];
            } catch (\Exception $e) {
                return [
                    'total_tokens' => 0, 'today_tokens' => 0,
                    'total_generations' => 0, 'today_generations' => 0,
                ];
            }
        });

        if ($notes->isEmpty() && $request->has('search')) {
            session()->flash('warning', "Knowledge Archive blank for query: '{$request->search}'");
        }

        return view('admin.notes.index', compact('notes', 'stats', 'subjects'));
    }

    public function bulkGenerateForm()
    {
        $subjects = Subject::all();
        $courses = Course::orderBy('name')->get();
        return view('admin.notes.bulk', compact('subjects', 'courses'));
    }

    public function bulkGenerate(Request $request)
    {
        set_time_limit(0);
        $request->validate([
            'topics' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'detail_level' => 'required|in:quick,detailed',
        ]);

        $topics = array_filter(array_map('trim', explode("\n", $request->topics)));
        $subject = Subject::findOrFail($request->subject_id);
        
        if (count($topics) > 10) {
            return back()->with('error', 'Please limit bulk generation to 10 topics at a time to avoid timeouts.');
        }

        $successCount = 0;
        $errors = [];
        $userId = Auth::id();

        foreach ($topics as $topic) {
            try {
                $result = $this->performAiGeneration($topic, $subject->name, $request->detail_level);

                if (isset($result['error'])) {
                    $errors[] = "Failed for topic '{$topic}': " . $result['error'];
                    continue;
                }

                $model = $result['model'] ?? 'unknown';
                $usage = $result['usage'] ?? [];

                Note::create([
                    'title' => $topic,
                    'note_type' => 'ai',
                    'ai_content' => $result['content'],
                    'user_id' => $userId,
                    'college_id' => Auth::user()->college_id ?? 1,
                    'subject_id' => $subject->id,
                    'is_verified' => true,
                ]);

                // 📊 Log Usage Metadata
                \App\Models\AiUsage::create([
                    'user_id' => $userId,
                    'model' => $model,
                    'type' => 'bulk',
                    'topic' => $topic,
                    'prompt_tokens' => $usage['promptTokenCount'] ?? 0,
                    'candidates_tokens' => $usage['candidatesTokenCount'] ?? 0,
                    'total_tokens' => $usage['totalTokenCount'] ?? 0,
                    'metadata' => [
                        'detail_level' => $request->detail_level,
                        'subject' => $subject->name
                    ]
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Crash for '{$topic}': " . $e->getMessage();
            }
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
     * AJAX-based Atomic Generation for Staging Area
     */
    public function generateSingle(Request $request)
    {
        $request->validate([
            'topic' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'detail_level' => 'required|in:quick,detailed',
        ]);

        try {
            $subject = Subject::findOrFail($request->subject_id);
            $result = $this->performAiGeneration($request->topic, $subject->name, $request->detail_level);

            if (isset($result['error'])) {
                return response()->json(['success' => false, 'error' => $result['error']], 422);
            }

            $userId = Auth::id();
            $model = $result['model'] ?? 'unknown';
            $usage = $result['usage'] ?? [];

            $note = Note::create([
                'title' => $request->topic,
                'note_type' => 'ai',
                'ai_content' => $result['content'],
                'user_id' => $userId,
                'college_id' => Auth::user()->college_id ?? 1,
                'subject_id' => $subject->id,
                'is_verified' => true,
            ]);

            // 📊 Log Usage Metadata
            \App\Models\AiUsage::create([
                'user_id' => $userId,
                'model' => $model,
                'type' => 'staging',
                'topic' => $request->topic,
                'prompt_tokens' => $usage['promptTokenCount'] ?? 0,
                'candidates_tokens' => $usage['candidatesTokenCount'] ?? 0,
                'total_tokens' => $usage['totalTokenCount'] ?? 0,
                'metadata' => [
                    'detail_level' => $request->detail_level,
                    'subject' => $subject->name
                ]
            ]);

            return response()->json([
                'success' => true, 
                'note_id' => $note->id,
                'msg' => "'{$request->topic}' manifested successfully!"
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
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
