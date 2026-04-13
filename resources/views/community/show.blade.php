<x-app-layout>
    @section('title', $post->title . ' | MyCollegeVerse')
    @section('meta_description', Str::limit($post->content, 150))

    <div class="min-h-screen bg-[#F8FAFC] -mx-4 sm:-mx-8 lg:-mx-12 px-4 sm:px-8 lg:px-12 py-8">
        <div class="grid lg:grid-cols-12 gap-8 max-w-[1600px] mx-auto">
            
            <!-- CENTER: FOCUS THREAD -->
            <main class="lg:col-span-8 space-y-6">
                
                <!-- Navigation Back -->
                <a href="{{ route('community.index') }}" class="inline-flex items-center gap-2 text-xs font-black text-slate-400 hover:text-primary transition-colors uppercase tracking-widest mb-2 group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                    Back to Multiverse Feed
                </a>

                <article class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="flex">
                        <!-- Voting Rail -->
                        <div class="w-[60px] bg-[#F8FAFC]/50 flex flex-col items-center py-6 gap-2 border-r border-slate-50">
                            <button onclick="handleVote({{ $post->id }}, 1)" id="upvote-btn-{{ $post->id }}" 
                                    class="p-1.5 rounded-lg transition-all {{ $post->likes->where('user_id', Auth::id())->where('value', 1)->first() ? 'text-primary bg-primary/10' : 'text-slate-300 hover:text-primary hover:bg-primary/5' }}">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                            </button>
                            <span id="post-score-{{ $post->id }}" class="text-sm font-black text-secondary">{{ $post->score ?? 0 }}</span>
                            <button onclick="handleVote({{ $post->id }}, -1)" id="downvote-btn-{{ $post->id }}" 
                                    class="p-1.5 rounded-lg transition-all {{ $post->likes->where('user_id', Auth::id())->where('value', -1)->first() ? 'text-rose-500 bg-rose-50' : 'text-slate-300 hover:text-rose-500 hover:bg-rose-50' }}">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </div>

                        <!-- Post Content -->
                        <div class="flex-1 p-6 lg:p-8">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $catColors = ['Doubt' => 'bg-rose-100 text-rose-600', 'General' => 'bg-primary/10 text-primary', 'Campus Life' => 'bg-amber-100 text-amber-600', 'Career' => 'bg-emerald-100 text-emerald-600'];
                                        $catColor = $catColors[$post->category] ?? 'bg-slate-100 text-slate-600';
                                    @endphp
                                    <span class="{{ $catColor }} px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider">{{ $post->category }}</span>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('profile.show', $post->user->username) }}" class="text-xs font-black text-secondary hover:text-primary transition-colors">u/{{ $post->user->username  }}</a>
                                        <span class="text-[10px] font-bold text-slate-300">• {{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <h1 class="text-2xl md:text-3xl font-black text-secondary mb-4">{{ $post->title }}</h1>
                            <div class="prose prose-slate max-w-none mb-6 text-slate-600 font-medium leading-relaxed">
                                {!! nl2br(e($post->content)) !!}
                            </div>

                            @if($post->image_url)
                                <div class="mb-8 rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm bg-slate-50">
                                    <img src="{{ $post->image_url }}" class="w-full h-auto max-h-[800px] object-contain mx-auto" alt="{{ $post->title }}">
                                </div>
                            @endif

                            <div class="flex items-center gap-6 pt-6 border-t border-slate-50">
                                <span class="flex items-center gap-2 text-xs font-black text-primary uppercase tracking-widest">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    {{ $post->comments_count }} Scholars in Thread
                                </span>
                                <button onclick="handleShare('{{ url()->current() }}')" class="flex items-center gap-2 text-xs font-black text-slate-400 hover:text-primary transition-colors uppercase tracking-widest group/share">
                                    <svg class="w-5 h-5 group-hover/share:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                    Share SEO Link
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Threaded Comments Section -->
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 space-y-8">
                    <h4 class="text-sm font-black text-secondary uppercase tracking-widest border-b border-slate-50 pb-4">Discussion Thread</h4>
                    
                    @auth
                    <div class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100">
                        <form onsubmit="submitComment(event, {{ $post->id }}, 'App\\Models\\Post')" class="flex gap-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" class="w-10 h-10 rounded-xl flex-shrink-0" />
                            <div class="flex-1 space-y-3">
                                <textarea placeholder="Add to the discussion..." class="w-full bg-white border-none rounded-2xl px-5 py-4 text-sm font-medium focus:ring-primary/20 min-h-[100px] resize-none"></textarea>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-primary text-white px-8 py-2.5 rounded-xl font-black text-xs shadow-lg shadow-primary/20 hover:scale-105 transition-all">Post Reply</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endauth

                    <div class="space-y-6" id="comment-list-{{ $post->id }}">
                        @forelse($post->comments->where('parent_id', null) as $comment)
                            <x-community.comment-item :comment="$comment" />
                        @empty
                            <div class="text-center py-12">
                                <p class="text-slate-400 font-bold italic text-sm">No scholars have commented on this node yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>

            <!-- RIGHT SIDEBAR -->
            <aside class="hidden lg:block lg:col-span-4 space-y-8 sticky top-8 h-fit">
                <!-- Trending Hubs -->
                <div class="glass-light p-8 rounded-[2rem] border border-white shadow-sm overflow-hidden relative">
                    <h4 class="text-lg font-black text-secondary mb-6 relative z-10">Trending Hubs</h4>
                    <div class="space-y-5 relative z-10">
                        @foreach ($trendingHubs as $hub)
                        <div class="flex items-center justify-between group cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center font-black text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                    {{ substr($hub->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-black text-secondary">{{ Str::limit($hub->name, 18) }}</p>
                                    <p class="text-[10px] font-bold text-slate-400">{{ $hub->posts_count }} active discussions</p>
                                </div>
                            </div>
                            <button class="bg-white px-3 py-1 rounded-lg text-[9px] font-black uppercase text-primary border border-primary/20 hover:bg-primary hover:text-white transition-all shadow-sm">Join</button>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('colleges.index') }}" class="block text-center mt-6 text-[10px] font-black text-primary uppercase tracking-widest hover:underline">View All Hubs</a>
                </div>

                <!-- Top Contributors -->
                <div class="glass-light p-8 rounded-[2rem] border border-white shadow-sm">
                    <h4 class="text-lg font-black text-secondary mb-6">Top Contributors</h4>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach ($topContributors as $contributor)
                            <div class="flex flex-col items-center p-4 bg-white/50 rounded-2xl border border-white hover:bg-white transition-all">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($contributor->name) }}&background=random" class="w-10 h-10 rounded-xl mb-2 shadow-sm" />
                                <span class="text-[10px] font-black text-secondary text-center truncate w-full">{{ $contributor->name }}</span>
                                <span class="text-[8px] font-black text-primary uppercase">{{ $contributor->posts_count }} Posts</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- Scripts from Index for Voting/Comments -->
    <script>
        function showReplyForm(commentId) {
            const form = document.getElementById(`reply-form-${commentId}`);
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                form.querySelector('textarea').focus();
            }
        }

        async function handleShare(url) {
            try {
                await navigator.clipboard.writeText(url);
                alert('Deep link copied to clipboard!');
            } catch (err) {
                console.error('Failed to copy: ', err);
            }
        }

        async function handleVote(postId, type) {
            @guest
                window.location.href = "{{ route('login') }}";
                return;
            @endguest

            try {
                const res = await fetch(`{{ url('/community/vote') }}/${postId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ type: type })
                });
                const data = await res.json();
                
                if (data.status === 'success') {
                    document.getElementById(`post-score-${postId}`).innerText = (data.score > 0 ? '+' : '') + data.score;
                    const upBtn = document.getElementById(`upvote-btn-${postId}`);
                    const downBtn = document.getElementById(`downvote-btn-${postId}`);
                    upBtn.classList.remove('text-primary', 'bg-primary/10');
                    upBtn.classList.add('text-slate-300');
                    downBtn.classList.remove('text-rose-500', 'bg-rose-50');
                    downBtn.classList.add('text-slate-300');
                    if (data.current_vote == 1) {
                        upBtn.classList.add('text-primary', 'bg-primary/10');
                        upBtn.classList.remove('text-slate-300');
                    } else if (data.current_vote == -1) {
                        downBtn.classList.add('text-rose-500', 'bg-rose-50');
                        downBtn.classList.remove('text-slate-300');
                    }
                }
            } catch (err) { console.error('Vote failed', err); }
        }

        async function submitComment(e, postId, type, parentId = null) {
            e.preventDefault();
            const form = e.target;
            const input = form.querySelector('textarea, input');
            const button = form.querySelector('button');
            const content = input.value.trim();
            if (!content) return;
            const originalBtnText = button.innerHTML;
            button.disabled = true;
            try {
                const res = await fetch('{{ route("community.comment") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        commentable_id: postId,
                        commentable_type: type,
                        content: content,
                        parent_id: parentId
                    })
                });
                const data = await res.json();
                if (res.ok && data.status === 'success' && data.html) {
                    if (parentId) {
                        const replyForm = document.getElementById(`reply-form-${parentId}`);
                        replyForm.insertAdjacentHTML('beforebegin', data.html);
                        replyForm.classList.add('hidden');
                    } else {
                        const list = document.getElementById(`comment-list-${postId}`);
                        list.insertAdjacentHTML('afterbegin', data.html);
                    }
                    input.value = '';
                }
            } catch (err) { console.error('Comment failed:', err); } finally {
                button.disabled = false;
                button.innerHTML = originalBtnText;
            }
        }
    </script>

    <style>
        .glass-light {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</x-app-layout>
