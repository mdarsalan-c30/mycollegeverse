<x-blog-layout>
    @section('title', 'Editorial Hub | MyCollegeVerse')

    <!-- Hero Section 🌌 -->
    <header class="bg-white pt-20 pb-24 border-b border-slate-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h4 class="text-primary font-black uppercase tracking-[0.3em] text-[10px] mb-6">The Multiverse Registry</h4>
            <h1 class="text-6xl font-black text-secondary tracking-tighter mb-8 lg:leading-[1.1]">Deep Intelligence for the <br><span class="text-slate-400">Academic Explorer.</span></h1>
            <p class="max-w-2xl mx-auto text-slate-500 font-medium text-lg leading-relaxed">Strategic roadmaps, campus insights, and career intelligence from the MyCollegeVerse editorial node.</p>
        </div>
    </header>

    <!-- Featured Articles (Horizontal Scroll for High Impact) -->
    @if(count($featuredBlogs) > 0)
    <section class="max-w-7xl mx-auto px-6 -mt-12 mb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredBlogs as $blog)
            <a href="{{ route('blogs.show', $blog->slug) }}" class="group bg-white p-4 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-900/5 hover:-translate-y-2 transition-all duration-500">
                <div class="aspect-[4/3] rounded-[1.5rem] overflow-hidden mb-6 relative">
                    <img src="{{ $blog->featured_image ? (str_contains($blog->featured_image, 'http') ? $blog->featured_image : 'https://ik.imagekit.io/studycubsfranchise/' . $blog->featured_image) : 'https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80' }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $blog->title }}">
                    <div class="absolute top-4 left-4">
                        <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md text-[10px] font-black text-secondary rounded-full uppercase tracking-widest">Featured</span>
                    </div>
                </div>
                <div class="px-2 pb-2">
                    <h3 class="text-xl font-black text-secondary leading-tight group-hover:text-primary transition-colors">{{ $blog->title }}</h3>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-4">{{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Recent' }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Category Grouped Sections 🏙️ -->
    <div class="max-w-7xl mx-auto px-6 pb-24 space-y-32">
        @foreach($categories as $category)
            @if($category->blogs->count() > 0)
            <div class="space-y-12">
                <div class="flex items-end justify-between border-b-2 border-slate-900 pb-6">
                    <div>
                        <h2 class="text-3xl font-black text-secondary tracking-tight">{{ $category->name }}</h2>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">{{ $category->description ?? 'Editorial Segment' }}</p>
                    </div>
                    <a href="#" class="text-[10px] font-black text-primary uppercase tracking-widest border-b-2 border-primary pb-1 hover:text-secondary hover:border-secondary transition-all">Explore All</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                    @foreach($category->blogs as $blog)
                    <article class="group">
                        <a href="{{ route('blogs.show', $blog->slug) }}" class="block space-y-6">
                            <div class="aspect-video rounded-3xl overflow-hidden bg-slate-50 border border-slate-100 shadow-sm relative">
                                <img src="{{ $blog->featured_image ? (str_contains($blog->featured_image, 'http') ? $blog->featured_image : 'https://ik.imagekit.io/studycubsfranchise/' . $blog->featured_image) : 'https://images.unsplash.com/photo-1523050338692-7b83b907024f?auto=format&fit=crop&q=80' }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $blog->title }}">
                            </div>
                            <div class="space-y-3">
                                <h3 class="text-xl font-extrabold text-secondary leading-snug group-hover:text-primary transition-colors">{{ $blog->title }}</h3>
                                <p class="text-slate-500 text-sm line-clamp-2">{{ $blog->excerpt ?? 'Strategic insights into the academic multiverse...' }}</p>
                                <div class="flex items-center gap-3 pt-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center">
                                        <span class="text-[10px] font-black text-slate-400">{{ substr($blog->author->name ?? 'A', 0, 1) }}</span>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $blog->author->name ?? 'Editorial' }} &bull; {{ $blog->published_at ? $blog->published_at->diffForHumans() : 'Recent' }}</span>
                                </div>
                            </div>
                        </a>
                    </article>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach
    </div>

    <!-- Global Discovery Node: Recent Insights 🛰️ -->
    <section class="max-w-7xl mx-auto px-6 pb-32">
        <div class="flex items-end justify-between border-b-2 border-slate-100 pb-6 mb-12">
            <div>
                <h2 class="text-3xl font-black text-secondary tracking-tight">Recent Insights</h2>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Latest articles from across the academic multiverse</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($recentInsights as $blog)
            <article class="group relative bg-white border border-slate-100 p-4 rounded-[2rem] hover:shadow-xl hover:-translate-y-1 transition-all duration-500">
                <a href="{{ route('blogs.show', $blog->slug) }}" class="block space-y-4">
                    <div class="aspect-square rounded-[1.5rem] overflow-hidden bg-slate-50 relative">
                        <img src="{{ $blog->featured_image ? (str_contains($blog->featured_image, 'http') ? $blog->featured_image : 'https://ik.imagekit.io/studycubsfranchise/' . $blog->featured_image) : 'https://images.unsplash.com/photo-1434031216660-c50938c8f3ef?auto=format&fit=crop&q=80' }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $blog->title }}">
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-[8px] font-black text-secondary rounded-full uppercase tracking-widest">{{ $blog->category->name ?? 'Insight' }}</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-md font-black text-secondary leading-tight group-hover:text-primary transition-colors line-clamp-2 uppercase tracking-tighter">{{ $blog->title }}</h3>
                        <p class="text-slate-400 text-[10px] font-bold mt-4 uppercase tracking-widest">{{ $blog->published_at ? $blog->published_at->diffForHumans() : 'Recent' }}</p>
                    </div>
                </a>
            </article>
            @empty
            <div class="col-span-full py-20 text-center border-2 border-dashed border-slate-100 rounded-[3rem]">
                <p class="text-slate-300 font-black text-xs uppercase tracking-widest italic">The multiverse is expanding... insights arriving soon.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-16 flex justify-center">
            {{ $recentInsights->links() }}
        </div>
    </section>

    <!-- Newsletter Hub 🛰️ -->
    <section class="bg-slate-900 py-24 text-center">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-4xl font-black text-white tracking-tighter mb-6">Never miss a critical node.</h2>
            <p class="text-slate-400 font-medium mb-12">Join 10,000+ students receiving weekly career roadmaps and campus intelligence.</p>
            <div class="flex flex-col sm:flex-row max-w-lg mx-auto gap-4">
                <input type="email" placeholder="Enter your email node..." class="flex-1 h-14 bg-white/5 border border-white/10 rounded-2xl px-6 text-white font-medium focus:border-white transition-all outline-none">
                <button class="px-8 py-4 bg-white text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary hover:text-white transition-all">Subscribe</button>
            </div>
        </div>
    </section>
</x-blog-layout>
