<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    /**
     * Display the Community Moderation Portal.
     */
    public function index(Request $request)
    {
        $query = Post::query();

        // High-Fidelity Filtering 🔍
        if ($request->has('type')) {
            if ($request->type == 'pinned') $query->where('is_pinned', true);
            if ($request->type == 'reported') $query->has('reports');
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $posts = $query->with(['user', 'college'])
            ->withCount(['comments', 'likes'])
            ->latest()
            ->paginate(15);

        return view('admin.community.index', compact('posts'));
    }

    /**
     * Toggle the 'Pinned' status for high-fidelity visibility.
     */
    public function togglePin(Post $post)
    {
        $post->update(['is_pinned' => !$post->is_pinned]);

        ApprovalLog::create([
            'admin_id' => Auth::id(),
            'action' => $post->is_pinned ? 'post_pinned' : 'post_unpinned',
            'target_type' => 'Post',
            'target_id' => $post->id,
            'metadata' => ['content_preview' => substr($post->content, 0, 50)],
        ]);

        return back()->with('success', $post->is_pinned ? 'Post has been pinned to the top of the feed.' : 'Post unpinned.');
    }

    /**
     * Purge toxic or non-compliant content.
     */
    public function destroy(Post $post)
    {
        $content = substr($post->content, 0, 50);
        
        ApprovalLog::create([
            'admin_id' => Auth::id(),
            'action' => 'post_purged',
            'target_type' => 'Post',
            'target_id' => $post->id,
            'metadata' => ['content_preview' => $content],
        ]);

        $post->delete();

        return back()->with('success', 'Social node purged from the multiverse feed.');
    }
}
