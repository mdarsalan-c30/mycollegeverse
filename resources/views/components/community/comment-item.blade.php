@props(['comment', 'depth' => 0])

<div class="flex gap-4 {{ $depth > 0 ? 'ml-10 border-l-2 border-slate-100 pl-4 mt-4' : 'mt-6' }} group/comment">
    <a href="{{ route('profile.show', $comment->user->username) }}" class="flex-shrink-0">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=random" 
             class="w-8 h-8 rounded-lg shadow-sm hover:scale-110 transition-transform" />
    </a>
    <div class="flex-1">
        <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100/50 group-hover/comment:border-primary/20 transition-colors">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('profile.show', $comment->user->username) }}" class="text-xs font-black text-secondary hover:text-primary transition-colors">
                    {{ $comment->user->name }}
                </a>
                <span class="text-[10px] font-bold text-slate-400">• {{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-sm text-slate-700 font-medium leading-relaxed">{{ $comment->content }}</p>
        </div>
        
        <div class="flex gap-4 mt-2 ml-2">
            @auth
            <button onclick="showReplyForm({{ $comment->id }})" class="text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-wider transition-colors">Reply</button>
            @endauth
            <button class="text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-wider transition-colors">Share</button>
        </div>

        <!-- Nested Replis -->
        @if($comment->replies && $comment->replies->count() > 0)
            <div class="space-y-4">
                @foreach($comment->replies as $reply)
                    <x-community.comment-item :comment="$reply" :depth="$depth + 1" />
                @endforeach
            </div>
        @endif

        <!-- Hidden Reply Form -->
        <div id="reply-form-{{ $comment->id }}" class="hidden mt-4 ml-2">
            <form onsubmit="submitComment(event, {{ $comment->commentable_id }}, '{{ $comment->commentable_type }}', {{ $comment->id }})" class="flex gap-3">
                <input type="text" placeholder="Write a reply..." class="flex-1 bg-white border border-slate-200 rounded-xl px-4 py-2 text-xs font-medium focus:ring-primary/20">
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-xl text-[10px] font-black shadow-sm">Reply</button>
            </form>
        </div>
    </div>
</div>
