<x-app-layout>
    @section('title', $seoTitle ?? $note->title . ' | MyCollegeVerse')
    @section('meta_description', $seoDescription ?? 'Download academic notes for ' . $note->title)

    @push('structured-data')
    <script type="application/ld+json">
        {!! json_encode($schema) !!}
    </script>
    @endpush

    <div class="grid lg:grid-cols-3 gap-10 pb-20">
        <!-- Main Content (Left) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Breadcrumbs -->
            <nav class="flex text-sm font-bold text-slate-400 gap-2">
                <a href="{{ route('notes.index') }}" class="hover:text-primary">Notes</a>
                <span>/</span>
                <span class="text-secondary">{{ $note->subject->name ?? 'General' }}</span>
                <span>/</span>
                <span class="text-slate-800">{{ $note->title }}</span>
            </nav>

            <!-- Note Viewer -->
            <div class="glass rounded-[3rem] overflow-hidden border-white/60 shadow-glass relative">
                <div class="aspect-[3/4] bg-slate-100 relative">
                    <!-- PDF Viewer -->
                    <embed src="{{ filter_var($note->file_path, FILTER_VALIDATE_URL) ? $note->file_path : asset('storage/' . $note->file_path) }}#toolbar=0&navpanes=0&scrollbar=1" type="application/pdf" width="100%" height="100%" class="rounded-[2.5rem]" />
                    
                    <!-- Fallback for browsers that don't support embed -->
                    <div class="absolute inset-0 flex items-center justify-center p-10 text-center bg-slate-50 z-[-1]">
                        <div class="space-y-4">
                            <div class="w-16 h-16 bg-primary/10 rounded-2xl mx-auto flex items-center justify-center text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <h3 class="font-black text-slate-800">Preview not available</h3>
                            <p class="text-sm text-slate-500 font-medium">Your browser doesn't support PDF previews. Please download to view.</p>
                            
                            @auth
                            <a href="{{ route('notes.download', $note->id) }}" class="inline-block bg-primary text-white px-6 py-2 rounded-xl font-bold text-sm">Download Instead</a>
                            @else
                            <a href="{{ route('login') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-xl font-bold text-sm">Sign in to Download</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Meta & Discussion -->
            <div class="glass p-10 rounded-[3rem] border-white/60">
                 <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-8">
                     <div>
                        <h2 class="text-3xl font-black text-secondary mb-2">{{ $note->title }}</h2>
                        <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Uploaded by {{ $note->user->name }} • {{ $note->created_at->diffForHumans() }}</p>
                     </div>
                     
                     @auth
                     <a href="{{ route('notes.download', $note->id) }}" class="bg-primary text-white px-8 py-4 rounded-2xl font-black shadow-lg shadow-primary/20 flex items-center gap-3 hover:scale-105 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" /></svg>
                        Download PDF
                     </a>
                     @else
                     <a href="{{ route('login') }}" class="w-full sm:w-auto bg-slate-100 text-slate-600 px-8 py-4 rounded-2xl font-black flex items-center justify-center gap-3 hover:bg-primary hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        Sign in to Download
                     </a>
                     @endauth
                 </div>

                 <div class="space-y-6">
                    <h3 class="text-xl font-black text-slate-800">Discussion ({{ $note->comments->count() }})</h3>
                    
                    <div id="comment-list-{{ $note->id }}" class="space-y-6 mb-8">
                        @foreach($note->comments as $comment)
                        <div class="flex gap-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=random" class="w-10 h-10 rounded-xl" />
                            <div>
                                <p class="font-bold text-slate-800">{{ $comment->user->name }}</p>
                                <p class="text-sm text-slate-600 font-medium">{{ $comment->content }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @auth
                    <form onsubmit="submitComment(event, {{ $note->id }}, 'App\\Models\\Note')" class="flex gap-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" class="w-10 h-10 rounded-xl shadow-sm"/>
                        <textarea placeholder="Ask a doubt or leave a review..." class="flex-1 bg-slate-50 border border-slate-100 rounded-2xl p-4 text-sm font-medium focus:ring-primary/20"></textarea>
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-xl font-bold flex items-center justify-center">Post</button>
                    </form>
                    @else
                    <div class="bg-slate-50 rounded-[2rem] p-8 text-center border border-dashed border-slate-200">
                        <p class="text-sm font-bold text-slate-500 mb-4">You must be logged in to join the discussion.</p>
                        <a href="{{ route('login') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-all">Sign In to Comment</a>
                    </div>
                    @endauth
                 </div>
            </div>
        </div>

        <!-- Sidebar (Right) -->
        <div class="space-y-8">
             <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm text-center space-y-6">
                <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-600 px-4 py-1.5 rounded-full text-sm font-black ring-4 ring-amber-50">
                    ⭐ 4.9 <span class="text-amber-400/50">/ 5.0</span>
                </div>
                <div class="space-y-1">
                    <p class="text-2xl font-black text-slate-800">Verified Hub</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-loose">Institutionally Validated</p>
                </div>
            </div>

            <div class="glass p-8 rounded-[2.5rem] border-white/60">
                <h4 class="text-lg font-black text-secondary mb-6">Related from {{ $note->subject->name ?? 'General' }}</h4>
                <div class="space-y-6">
                    @forelse($related as $rel)
                    <a href="{{ route('notes.show', $rel->id) }}" class="flex gap-4 group cursor-pointer">
                        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all text-xl">📄</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate group-hover:text-primary transition-colors">{{ $rel->title }}</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $note->subject->name ?? 'General' }} • {{ $rel->user->name }}</p>
                        </div>
                    </a>
                    @empty
                    <p class="text-xs text-slate-400 italic">No related notes yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        async function submitComment(e, id, type) {
            e.preventDefault();
            const input = e.target.querySelector('textarea');
            const content = input.value;
            if (!content) return;

            const res = await fetch('{{ route("community.comment") }}', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    commentable_id: id,
                    commentable_type: type,
                    content: content
                })
            });

            const data = await res.json();
            if (data.status === 'success') {
                document.getElementById(`comment-list-${id}`).insertAdjacentHTML('beforeend', data.html);
                input.value = '';
            }
        }
    </script>
</x-app-layout>
