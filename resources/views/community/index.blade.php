<x-app-layout>
    @section('title', 'Community Hub | MyCollegeVerse')
    @section('meta_description', 'The scholar\'s multiverse. Ask questions, share insights, and build your academic karma with students across the globe.')

    <div class="min-h-screen bg-[#F8FAFC] -mx-4 sm:-mx-8 lg:-mx-12 px-4 sm:px-8 lg:px-12 py-8">
        <div class="grid lg:grid-cols-12 gap-8 max-w-[1600px] mx-auto">
            
            <!-- CENTER: MAIN FEED (Expanded) -->
            <main class="lg:col-span-8 xl:col-span-8 space-y-6">
                <!-- SEO Structured Data -->
                @push('head')
                <script type="application/ld+json">
                {
                  "@context": "https://schema.org",
                  "@type": "DiscussionForumPosting",
                  "headline": "MyCollegeVerse Community Hub",
                  "description": "The academic multiverse for students. Ask doubts, share research, and collaborate.",
                  "author": {
                    "@type": "Organization",
                    "name": "MyCollegeVerse"
                  },
                  "interactionStatistic": {
                    "@type": "InteractionCounter",
                    "interactionType": "https://schema.org/CommentAction",
                    "userInteractionCount": {{ $posts->sum('comments_count') }}
                  }
                }
                </script>
                @endpush
                
                <!-- Search & Filter Bar -->
                <div class="glass-light p-2 rounded-[2rem] border border-white shadow-sm flex items-center gap-2">
                    <div class="flex-1 flex items-center gap-3 bg-white/50 px-6 py-3 rounded-2xl">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" placeholder="Search discussions, doubt, or topics..." class="bg-transparent border-none focus:ring-0 text-sm font-bold text-slate-700 w-full placeholder-slate-300">
                    </div>
                    <div class="flex gap-1 p-1">
                        <button class="px-5 py-2.5 rounded-xl text-xs font-black bg-white text-secondary shadow-sm">Recent</button>
                        <button class="px-5 py-2.5 rounded-xl text-xs font-black text-slate-400 hover:text-primary transition-colors">Trending</button>
                        <button class="px-5 py-2.5 rounded-xl text-xs font-black text-slate-400 hover:text-primary transition-colors">Top</button>
                    </div>
                </div>

                <!-- Input Area -->
                <div id="post-form-area" class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100">
                    @auth
                    <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex gap-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" class="w-10 h-10 rounded-xl"/>
                            <div class="flex-1 space-y-4">
                                <input name="title" required placeholder="Question title (e.g. How to solve Laplace finals?)" 
                                       class="w-full bg-slate-50/50 border-none rounded-xl px-5 py-3 text-sm font-bold focus:ring-primary/20 placeholder-slate-300">
                                <textarea name="content" required placeholder="Describe your doubt in detail..." 
                                          class="w-full bg-slate-50/50 border-none rounded-xl px-5 py-3 text-sm font-medium h-24 focus:ring-primary/20 placeholder-slate-300 resize-none"></textarea>
                                <div id="image-preview-container" class="hidden mb-2">
                                    <div class="relative inline-block">
                                        <img id="image-preview" src="#" class="max-h-32 rounded-xl border border-slate-200 shadow-sm">
                                        <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full p-1 shadow-md hover:bg-rose-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center bg-slate-50/50 rounded-xl px-4 py-2">
                                    <div class="flex items-center gap-4">
                                        <select name="category" class="bg-transparent border-none text-xs font-black uppercase text-slate-400 p-0 focus:ring-0 cursor-pointer hover:text-primary transition-colors">
                                            <option>General</option>
                                            <option>Doubt</option>
                                            <option>Campus Life</option>
                                            <option>Career</option>
                                        </select>
                                        
                                        <!-- Image Upload Button -->
                                        <label class="flex items-center gap-2 cursor-pointer group/upload">
                                            <input type="file" name="image" id="post-image-input" class="hidden" accept="image/*" onchange="previewImage(this)">
                                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-slate-400 group-hover/upload:text-primary group-hover/upload:bg-primary/10 transition-all shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            </div>
                                            <span class="text-[10px] font-black uppercase text-slate-400 group-hover/upload:text-primary transition-colors hidden sm:block">Add Photo</span>
                                        </label>
                                    </div>
                                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-xl font-black text-xs shadow-lg shadow-primary/20 hover:scale-105 transition-all">Launch Post</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="text-center py-4">
                        <p class="text-secondary font-black text-lg">Have something to share?</p>
                        <p class="text-slate-400 text-sm font-bold mb-4">Join our global community of scholars today.</p>
                        <a href="{{ route('login') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-2xl font-black shadow-lg shadow-primary/20">Sign In to Continue</a>
                    </div>
                    @endauth
                </div>

                <!-- THE FEED -->
                <div class="space-y-6" id="community-feed">
                    @forelse ($posts as $post)
                    <article class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden group/post">
                        <div class="flex">
                            <!-- Reddit-Style Voting Rail (Left) -->
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
                                            <a href="{{ route('profile.show', $post->user->username) }}" class="text-xs font-black text-secondary hover:text-primary transition-colors">u/{{ $post->user->username ?: str_replace(' ', '', strtolower($post->user->name)) }}</a>
                                            <span class="text-[10px] font-bold text-slate-300">• {{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    @auth
                                        @if($post->college_id == Auth::user()->college_id)
                                            <span class="bg-indigo-50 text-indigo-500 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-tighter border border-indigo-100/50">Your Campus</span>
                                        @endif
                                    @endauth
                                </div>

                                <a href="{{ route('community.show', ['user' => $post->user->username, 'post' => $post->slug]) }}">
                                    <h3 class="text-xl font-black text-secondary mb-3 group-hover/post:text-primary transition-colors">{{ $post->title }}</h3>
                                </a>
                                <p class="text-slate-600 text-sm font-medium leading-relaxed {{ $post->image_url ? 'mb-4' : 'mb-6' }}">{{ $post->content }}</p>

                                @if($post->image_url)
                                    <div class="mb-6 rounded-[1.5rem] overflow-hidden border border-slate-100 shadow-sm">
                                        <img src="{{ $post->image_url }}" class="w-full h-auto max-h-[500px] object-cover hover:scale-[1.02] transition-transform duration-500 cursor-zoom-in" 
                                             onclick="window.open(this.src, '_blank')" alt="{{ $post->title }}">
                                    </div>
                                @endif

                                <div class="flex items-center gap-6 pt-6 border-t border-slate-50">
                                    <a href="{{ route('community.show', ['user' => $post->user->username, 'post' => $post->slug]) }}" class="flex items-center gap-2 text-xs font-black text-slate-400 hover:text-primary transition-colors uppercase tracking-widest">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        {{ $post->comments_count }} Comments
                                    </a>
                                    <button onclick="handleShare('{{ route('community.show', ['user' => $post->user->username, 'post' => $post->slug]) }}')" class="flex items-center gap-2 text-xs font-black text-slate-400 hover:text-primary transition-colors uppercase tracking-widest group/share">
                                        <svg class="w-5 h-5 group-hover/share:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                        Share Link
                                    </button>
                                </div>

                                <!-- Threaded Comments Section -->
                                <div id="post-comments-{{ $post->id }}" class="hidden mt-8 space-y-4">
                                    <div class="space-y-4" id="comment-list-{{ $post->id }}">
                                        @foreach($post->comments->where('parent_id', null) as $comment)
                                            <x-community.comment-item :comment="$comment" />
                                        @endforeach
                                    </div>
                                    @auth
                                    <div class="mt-6 pt-6 border-t border-slate-50">
                                        <form onsubmit="submitComment(event, {{ $post->id }}, 'App\\Models\\Post')" class="flex gap-4">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" class="w-8 h-8 rounded-lg flex-shrink-0" />
                                            <input type="text" placeholder="Add a comment..." class="flex-1 bg-slate-50 border-none rounded-xl px-5 py-2 text-xs font-bold focus:ring-primary/20">
                                            <button type="submit" class="text-primary font-black text-xs uppercase tracking-widest px-2">Post</button>
                                        </form>
                                    </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </article>
                    @empty
                    <div class="text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100">
                        <p class="text-slate-400 font-bold italic">The multiverse is quiet... Launch the first post!</p>
                    </div>
                    @endforelse
                </div>
            </main>

            <!-- RIGHT SIDEBAR: Discovery & Community Health -->
            <aside class="hidden lg:block lg:col-span-4 space-y-8 sticky top-8 h-fit">
                
                @auth
                    <!-- Ask a Doubt CTA (Relocated) -->
                    <div class="glass-light p-6 rounded-[2.5rem] border border-white shadow-sm overflow-hidden relative group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-primary/10 rounded-full -mr-12 -mt-12 blur-2xl group-hover:scale-150 transition-transform"></div>
                        <h4 class="text-lg font-black text-secondary mb-2 relative z-10">Stuck on a Project?</h4>
                        <p class="text-slate-500 text-xs font-bold mb-6 relative z-10 leading-relaxed">The global scholar community is ready to troubleshoot with you.</p>
                        <button onclick="document.getElementById('post-form-area').scrollIntoView({behavior: 'smooth'})" 
                                class="w-full bg-primary text-white py-4 rounded-2xl font-black text-sm shadow-xl shadow-primary/20 hover:scale-[1.02] transition-all flex items-center justify-center gap-2 relative z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Ask a Doubt
                        </button>
                    </div>

                    <!-- Personal Karma Stats (Relocated) -->
                    <div class="glass-light p-8 rounded-[2.5rem] border border-white shadow-sm">
                        <div class="flex items-center gap-4 mb-6">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" class="w-14 h-14 rounded-2xl shadow-sm"/>
                            <div>
                                <p class="font-black text-secondary text-lg leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] font-black text-primary mt-1.5 uppercase tracking-widest">{{ Auth::user()->college->name ?? 'Global Scholar' }}</p>
                            </div>
                        </div>
                        <div class="bg-[#F8FAFC] rounded-2xl p-6 border border-slate-100 flex justify-between">
                            <div class="text-center flex-1 border-r border-slate-200">
                                <span class="block text-2xl font-black text-secondary">{{ Auth::user()->karma }}</span>
                                <span class="text-[10px] uppercase font-black text-slate-400">Karma</span>
                            </div>
                            <div class="text-center flex-1">
                                <span class="block text-2xl font-black text-secondary">{{ Auth::user()->posts()->count() }}</span>
                                <span class="text-[10px] uppercase font-black text-slate-400">Postings</span>
                            </div>
                        </div>
                    </div>
                @endauth

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

                <!-- Hot Discussions -->
                <div class="glass-light p-8 rounded-[2rem] border border-white shadow-sm">
                    <h4 class="text-lg font-black text-secondary mb-6 italic">Hot Discussions</h4>
                    <div class="space-y-4">
                        @foreach ($posts->sortByDesc('comments_count')->take(3) as $hot)
                        <a href="#" class="block group">
                            <p class="text-xs font-black text-slate-700 group-hover:text-primary transition-colors line-clamp-2 leading-tight mb-1">{{ $hot->title }}</p>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $hot->comments_count }} scholars debating</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Feedback CTA -->
                <div class="bg-secondary p-8 rounded-[2.5rem] shadow-xl relative overflow-hidden group">
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-primary/20 rounded-full blur-3xl transition-all group-hover:scale-110"></div>
                    <h4 class="text-white text-xl font-black mb-2 relative z-10">Build Together</h4>
                    <p class="text-slate-400 text-xs font-bold leading-relaxed mb-6 relative z-10">Suggest new features or report issues to earn unique "Founder Badges".</p>
                    <button class="w-full bg-white/10 hover:bg-white/20 text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all relative z-10">Submit Feedback</button>
                </div>
            </aside>
        </div>
    </div>

    <script>
        function togglePostComments(id) {
            document.getElementById(`post-comments-${id}`).classList.toggle('hidden');
        }

        function showReplyForm(commentId) {
            const form = document.getElementById(`reply-form-${commentId}`);
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                form.querySelector('input').focus();
            }
        }

        async function handleShare(url) {
            try {
                await navigator.clipboard.writeText(url);
                alert('Verse link copied to clipboard!');
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
                    // Update Score
                    document.getElementById(`post-score-${postId}`).innerText = (data.score > 0 ? '+' : '') + data.score;
                    
                    // Update Button Visuals
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
            } catch (err) {
                console.error('Vote failed', err);
            }
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                    document.getElementById('image-preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('post-image-input').value = "";
            document.getElementById('image-preview-container').classList.add('hidden');
        }

        async function submitComment(e, postId, type, parentId = null) {
            e.preventDefault();
            const form = e.target;
            const input = form.querySelector('input');
            const button = form.querySelector('button');
            const content = input.value.trim();
            
            if (!content) return;

            // Prevent double-post: disable button & show loading state
            const originalBtnText = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">...</svg>';
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
                        if (replyForm) {
                            replyForm.insertAdjacentHTML('beforebegin', data.html);
                            replyForm.classList.add('hidden');
                        }
                    } else {
                        const list = document.getElementById(`comment-list-${postId}`);
                        if (list) {
                            // Ensure the container is visible if it was hidden (empty state)
                            document.getElementById(`post-comments-${postId}`).classList.remove('hidden');
                            list.insertAdjacentHTML('beforeend', data.html);
                        }
                    }
                    input.value = ''; // Success: Clear text box
                } else {
                    console.error('Submission error:', data);
                    const msg = data.message || 'The Verse rejected this transmission. Please try again.';
                    alert(msg);
                }
            } catch (err) {
                console.error('Comment failed:', err);
                alert('Connection error. Your comment was saved to the library but couldn\'t be shown instantly.');
            } finally {
                // Restore button
                button.innerHTML = originalBtnText;
                button.disabled = false;
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
