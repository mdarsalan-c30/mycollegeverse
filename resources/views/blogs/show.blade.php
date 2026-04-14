<x-blog-layout>
    @section('title', $blog->meta_title ?? ($blog->title . ' | Editorial Hub'))
    @section('meta_description', $blog->meta_description)

    @push('head')
    <style>
        .progress-bar { position: fixed; top: 0; left: 0; height: 4px; background: #3B82F6; z-index: 100; transition: width 0.1s ease; }
    </style>
    @endpush

    <div class="progress-bar" id="reading-progress"></div>

    <!-- Article Header Node 🌌 -->
    <header class="bg-white pt-24 pb-16">
        <div class="reading-container px-6">
            <div class="flex items-center gap-3 mb-8">
                <a href="/blog" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-primary transition-all">Editorial Hub</a>
                <span class="text-slate-200">/</span>
                <span class="text-[10px] font-black text-primary uppercase tracking-widest">{{ $blog->category->name ?? 'Uncategorized' }}</span>
            </div>
            
            <h1 class="text-5xl lg:text-6xl font-black text-secondary tracking-tighter leading-[1.1] mb-10">{{ $blog->title }}</h1>
            
            <div class="flex items-center justify-between py-8 border-y border-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center font-black text-slate-400 text-lg">
                        {{ substr($blog->author->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-black text-secondary">{{ $blog->author->name ?? 'Editorial Node' }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Recently Manifested' }} &bull; {{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Social Shares -->
                    <button class="p-3 bg-slate-50 rounded-xl text-slate-400 hover:text-primary transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    </button>
                    <button class="p-3 bg-slate-50 rounded-xl text-slate-400 hover:text-primary transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Featured Image -->
    @if($blog->featured_image)
    <div class="max-w-7xl mx-auto px-6 mb-20">
        <div class="aspect-[21/9] rounded-[3rem] overflow-hidden shadow-2xl shadow-slate-900/10">
            <img src="{{ str_contains($blog->featured_image, 'http') ? $blog->featured_image : 'https://ik.imagekit.io/studycubsfranchise/' . $blog->featured_image }}" 
                 class="w-full h-full object-cover" alt="{{ $blog->title }}">
        </div>
    </div>
    @endif

    <!-- Article Content Node 🛡️ -->
    <article class="reading-container px-6 mb-32">
        <div class="blog-content">
            {!! $blog->content !!}
        </div>

        <!-- Tags / Footer -->
        @if($blog->meta_keywords)
        <div class="mt-20 pt-10 border-t border-slate-100 flex flex-wrap gap-2">
            @foreach(explode(',', $blog->meta_keywords) as $tag)
            <span class="px-4 py-2 bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-xl">#{{ trim($tag) }}</span>
            @endforeach
        </div>
        @endif
    </article>

    <!-- Institutional Recommendations 🧬 -->
    @if($blog->auto_recommend_colleges)
    <section class="bg-slate-50 py-32 border-y border-slate-100 mb-32">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-secondary tracking-tighter mb-4">Strategic Connections</h2>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest italic">Institutional nodes related to this insight</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($colleges->take(4) as $college)
                <a href="{{ route('colleges.show', $college->slug) }}" class="group bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-500">
                    <img src="{{ $college->featured_image ? 'https://ik.imagekit.io/studycubsfranchise/' . $college->featured_image : 'https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80' }}" 
                         class="w-full h-32 object-cover rounded-2xl mb-6 shadow-sm" alt="{{ $college->name }}">
                    <h3 class="text-sm font-black text-secondary mb-2 group-hover:text-primary transition-colors line-clamp-2">{{ $college->name }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $college->city }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Citizen Discussion (Comments) 💬 -->
    <section class="reading-container px-6 pb-32">
        <div class="flex items-center gap-4 mb-12">
            <h2 class="text-2xl font-black text-secondary tracking-tight">Citizen Discussion</h2>
            <div class="px-3 py-1 bg-slate-100 text-slate-500 font-black text-[10px] rounded-full uppercase tracking-widest">{{ $blog->comments_count ?? 0 }}</div>
        </div>

        @auth
        <form action="{{ route('community.comment') }}" method="POST" class="mb-16">
            @csrf
            <input type="hidden" name="commentable_type" value="App\Models\Blog">
            <input type="hidden" name="commentable_id" value="{{ $blog->id }}">
            <div class="bg-white border-2 border-slate-100 rounded-3xl p-6 shadow-sm focus-within:border-primary transition-all">
                <textarea name="content" rows="4" placeholder="Share your insight with the multiverse..."
                          class="w-full resize-none border-0 focus:ring-0 text-sm font-medium outline-none text-slate-700"></textarea>
                <div class="flex items-center justify-between pt-4 border-t border-slate-50 mt-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Respect the editorial nodes</p>
                    <button type="submit" class="px-8 py-3 bg-secondary text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all">Publish Comment</button>
                </div>
            </div>
        </form>
        @else
        <div class="p-12 bg-slate-50 rounded-[2.5rem] text-center mb-16 border border-slate-100">
            <h4 class="text-xl font-black text-secondary mb-4 tracking-tight">Join the Discussion</h4>
            <p class="text-slate-400 font-medium text-sm mb-8">Sign in to share your thoughts on this article.</p>
            <a href="/login" class="px-8 py-3 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:scale-105 transition-all inline-block">Citizen Login</a>
        </div>
        @endauth

        <!-- Comments List Node -->
        <div class="space-y-10">
            @forelse($blog->comments ?? [] as $comment)
            <div class="flex gap-6 group">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center font-black text-slate-400">
                    {{ substr($comment->user->name, 0, 1) }}
                </div>
                <div class="flex-1 space-y-3 pb-10 border-b border-slate-50 group-last:border-0">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-black text-secondary">{{ $comment->user->name }}</span>
                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest cursor-default">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm font-medium text-slate-600 leading-relaxed">{{ $comment->content }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest italic">The multiverse is quiet... be the first to speak.</p>
            </div>
            @endforelse
        </div>
    </section>

    @push('scripts')
    <script>
        window.onscroll = function() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById("reading-progress").style.width = scrolled + "%";
        };
    </script>
    @endpush
</x-blog-layout>
