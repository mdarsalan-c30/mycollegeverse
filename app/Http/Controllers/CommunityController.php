<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $query = Post::with(['user', 'comments.user', 'likes'])
            ->withCount('comments')
            ->withSum('likes as score', 'value');

        if ($user) {
            // Priority to user's college
            $query->orderByRaw('college_id = ? DESC', [$user->college_id ?? 0]);
        }
        
        $posts = $query->latest()->get();

        // Data for Sidebars
        $trendingHubs = \App\Models\College::withCount('posts')->orderBy('posts_count', 'desc')->take(5)->get();
        $topContributors = \App\Models\User::withCount('posts')->orderBy('posts_count', 'desc')->take(6)->get();
            
        return view('community.index', compact('posts', 'trendingHubs', 'topContributors'));
    }

    /**
     * Display a focused community thread.
     */
    public function show(\App\Models\User $user, Post $post)
    {
        // Safety: Ensure the post belongs to the user in the URL
        if ($post->user_id !== $user->id) {
            abort(404);
        }

        $post->load(['user', 'comments.user', 'likes'])
            ->loadCount('comments')
            ->loadSum('likes as score', 'value');

        // SEO Expert Injection 🔍
        $seoTitle = "{$post->title} - Community Discussion | MyCollegeVerse";
        $seoDescription = \Illuminate\Support\Str::limit($post->content, 160);
        
        // JSON-LD DiscussionForumPosting Schema
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "DiscussionForumPosting",
            "headline" => $post->title,
            "articleBody" => $post->content,
            "author" => [
                "@type" => "Person",
                "name" => $post->user->name
            ],
            "datePublished" => $post->created_at->toIso8601String(),
            "interactionStatistic" => [
                "@type" => "InteractionCounter",
                "interactionType" => "https://schema.org/CommentAction",
                "userInteractionCount" => $post->comments_count
            ]
        ];

        return view('community.show', compact('post', 'trendingHubs', 'topContributors', 'seoTitle', 'seoDescription', 'schema'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'college_id' => 'nullable|integer|exists:colleges,id',
            'image' => 'nullable|image|max:5120', // 5MB limit
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $collegeId = $validated['college_id'] ?? ($user->college_id ?? null);

        if (!$collegeId) {
            return back()->with('error', 'You must be part of a College Verse to post here!');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $ik = app(\App\Services\ImageKitService::class);
            $upload = $ik->upload(
                $request->file('image'), 
                'post_' . time() . '_' . Auth::id(), 
                'community_posts'
            );
            if ($upload && isset($upload->filePath)) {
                $imagePath = $upload->filePath;
            }
        }

        try {
            Post::create([
                'user_id' => Auth::id(),
                'college_id' => $collegeId,
                'title' => $validated['title'],
                'content' => $validated['content'],
                'category' => $validated['category'],
                'image_path' => $imagePath,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\Log::error("Community Manifestation Failure: " . $e->getMessage());
            
            if (str_contains($e->getMessage(), 'Unknown column')) {
                return back()->with('error', 'Multiverse Sync Required! Please visit /multiverse-migrate to finalize the Community Identity schema.');
            }
            
            return back()->with('error', 'Post Node collapsed during manifestation. Please try again.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("General Community Error: " . $e->getMessage());
            return back()->with('error', 'Verse Sync Failure: ' . $e->getMessage());
        }

        return back()->with('success', 'Launched to the Verse!');
    }

    public function comment(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'commentable_id' => $validated['commentable_id'],
            'commentable_type' => $validated['commentable_type'],
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        // Load user immediately for the component
        $comment->load('user');

        if ($request->expectsJson() || $request->ajax()) {
            try {
                // Critical Fix: Blade components expect an $attributes bag even when rendered as a partial.
                $html = view('components.community.comment-item', [
                    'comment' => $comment, 
                    'depth' => 0,
                    'attributes' => new \Illuminate\View\ComponentAttributeBag([])
                ])->render();

                return response()->json([
                    'status' => 'success',
                    'comment' => $comment,
                    'html' => $html
                ]);
            } catch (\Exception $e) {
                // Return 500 with the error message so we can see it in the console if it fails.
                return response()->json([
                    'status' => 'error',
                    'message' => 'Render failed: ' . $e->getMessage(),
                ], 500);
            }
        }

        return back()->with('success', 'Comment added.');
    }

    public function vote(Request $request, $postId)
    {
        $type = $request->input('type', 1); // 1 = up, -1 = down
        
        $like = Like::where('user_id', Auth::id())
                    ->where('post_id', $postId)
                    ->first();
        
        if ($like) {
            if ($like->value == $type) {
                // Clicking same button again removes the vote
                $like->delete();
                $currentVote = 0;
            } else {
                // Switching from up to down or vice-versa
                $like->update(['value' => $type]);
                $currentVote = $type;
            }
        } else {
            // New vote
            Like::create([
                'user_id' => Auth::id(),
                'post_id' => $postId,
                'value' => $type
            ]);
            $currentVote = $type;
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'current_vote' => $currentVote,
                'score' => Like::where('post_id', $postId)->sum('value')
            ]);
        }

        return back();
    }
}
