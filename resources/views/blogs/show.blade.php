<x-app-layout>
    @section('title', $blog->meta_title ?? $blog->title . ' | MyCollegeVerse Blog')
    @section('meta_description', $blog->meta_description ?? $blog->summary)

    @push('head')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .article-content { line-height: 1.8; color: #1e293b; }
        .article-content h2 { font-weight: 800; font-size: 1.875rem; margin-top: 2.5rem; margin-bottom: 1.25rem; color: #0f172a; }
        .article-content p { margin-bottom: 1.5rem; font-size: 1.125rem; }
        .article-card { border-radius: 0; transition: all 0.3s ease; }
        .prose-node { max-width: 720px; margin-left: auto; margin-right: auto; }
    </style>
    @endpush

    <article class="bg-white min-h-screen">
        <!-- Progress Bar Node 🛰️ -->
        <div class="fixed top-0 left-0 w-full h-1 z-[60] pointer-events-none">
            <div id="read-progress" class="h-full bg-primary transition-all duration-150" style="width: 0%"></div>
        </div>

        <div class="py-20">
            <!-- Header Node -->
            <header class="prose-node px-6 mb-16">
                <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-6">
                    <span class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ optional($blog->published_at)->format('M d, Y') ?? 'Timeline Unknown' }}
                    </span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ ceil(str_word_count(strip_tags($blog->content ?? '')) / 200) }} MIN READ
                    </span>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-secondary leading-[1.1] tracking-tight mb-8">
                    {{ $blog->title }}
                </h1>

                <div class="flex items-center justify-between py-10 border-t border-b border-slate-50 italic">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-primary font-black border border-slate-100 shadow-sm">
                            {{ substr(optional($blog->author)->name ?? 'M', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Author Identity</p>
                            <p class="font-bold text-secondary">{{ optional($blog->author)->name ?? 'Multiverse Member' }}</p>
                        </div>
                    </div>

                    <!-- Share Nexus 📡 -->
                    <div class="flex items-center gap-3" x-data="{
                        shareData: {
                            title: '{{ addslashes($blog->title) }}',
                            text: '{{ addslashes($blog->summary) }}',
                            url: window.location.href
                        },
                        async share() {
                            if (navigator.share) {
                                try { await navigator.share(this.shareData); } catch (err) { console.error('Share failed:', err); }
                            } else {
                                window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(this.shareData.url), '_blank');
                            }
                        }
                    }">
                        <button @click="share()" class="p-3 bg-slate-50 text-slate-400 hover:bg-primary/5 hover:text-primary transition-all rounded-xl" title="Share Article">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        </button>
                    </div>
                </div>
            </header>

            @if($blog->featured_image)
            <div class="max-w-6xl mx-auto px-6 mb-20">
                <div class="rounded-[3rem] overflow-hidden shadow-2xl shadow-slate-200/50">
                    <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" class="w-full h-auto">
                </div>
            </div>
            @endif

            <div class="prose-node px-6 article-content mb-32">
                {!! $blog->content !!}
            </div>

            <!-- Recommended Colleges Node 🧬 -->
            @if(isset($recommendedColleges) && $recommendedColleges->count() > 0)
            <div class="bg-slate-50/50 py-32">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="flex items-end justify-between mb-12">
                        <div class="max-w-xl">
                            <h3 class="text-3xl font-black text-secondary tracking-tight">Recommended Campus Hubs</h3>
                            <p class="text-slate-500 font-bold mt-3 italic uppercase text-[10px] tracking-widest">Synergized institutions matching your discovery path.</p>
                        </div>
                    </div>

                    <div class="flex gap-8 overflow-x-auto custom-scrollbar pb-10">
                        @foreach($recommendedColleges as $college)
                        <div class="min-w-[320px] bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
                            <a href="{{ route('colleges.show', $college->slug) }}" class="block aspect-[16/9] bg-slate-100 overflow-hidden">
                                <img src="{{ app(\App\Services\ImageKitService::class)->getUrl($college->thumbnail_url ?? 'default-college.jpg', ['w' => 400, 'q' => 70]) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </a>
                            <div class="p-8">
                                <p class="text-[9px] font-black text-primary uppercase tracking-[0.2em] mb-3">{{ $college->city ?? 'Multiverse' }}</p>
                                <h4 class="text-lg font-black text-secondary leading-tight mb-6">
                                    <a href="{{ route('colleges.show', $college->slug) }}">{{ $college->name }}</a>
                                </h4>
                                <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                                    <div class="flex items-center gap-1.5 text-amber-500">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="text-xs font-black">{{ number_format($college->rating ?? 0, 1) }}</span>
                                    </div>
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic">{{ $college->reviews_count ?? 0 }} CITIZENS</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Comment Nexus -->
            <section class="prose-node px-6 py-32 border-t border-slate-50">
                <h3 class="text-3xl font-black text-secondary tracking-tight mb-12 italic">Citizen Dialogue ({{ $blog->comments->count() }})</h3>
                
                @auth
                <form action="{{ route('community.comment') }}" method="POST" class="mb-20">
                    @csrf
                    <input type="hidden" name="commentable_id" value="{{ $blog->id }}">
                    <input type="hidden" name="commentable_type" value="App\Models\Blog">
                    <textarea name="content" required rows="5" class="w-full bg-slate-50/50 border-2 border-slate-100 rounded-[2rem] px-8 py-8 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all placeholder-slate-400" placeholder="Contribute your perspective to the multiverse..."></textarea>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-10 py-4 bg-primary text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">Manifest Comment</button>
                    </div>
                </form>
                @endauth

                <div class="space-y-12">
                    @forelse($blog->comments as $comment)
                    <div class="flex gap-6 italic">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-primary font-black shrink-0">
                            {{ substr(optional($comment->user)->name ?? 'M', 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-[10px] font-black text-secondary uppercase tracking-widest">{{ optional($comment->user)->name ?? 'Multiverse Member' }}</p>
                                <p class="text-[9px] text-slate-300 font-bold uppercase tracking-tight">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-[15px] text-slate-600 font-bold leading-relaxed bg-slate-50/30 p-8 rounded-[2rem] border border-slate-50">
                                {{ $comment->content }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-20 bg-slate-50/30 rounded-[3rem] border border-dashed border-slate-200">
                        <p class="text-slate-400 font-bold italic text-sm">The dialogue has not yet begun for this article node.</p>
                    </div>
                    @endforelse
                </div>
            </section>
        </div>
    </article>

    @push('scripts')
    <script>
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            document.getElementById('read-progress').style.width = scrolled + '%';
        });
    </script>
    @endpush
</x-app-layout>
