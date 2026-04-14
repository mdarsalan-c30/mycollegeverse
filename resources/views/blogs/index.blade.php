<x-app-layout>
    @section('title', 'Editorial Hub | MyCollegeVerse Discovery')
    @section('meta_description', 'Explore deep insights, college comparisons, and academic strategy from the MyCollegeVerse editorial team.')

    <div class="space-y-10 pb-20">
        <!-- Header Node -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-6">
            <div class="max-w-2xl">
                <h1 class="text-5xl font-extrabold text-slate-900 leading-tight mb-4 tracking-tight">The Editorial Hub</h1>
                <p class="text-lg text-slate-500 font-medium leading-relaxed italic">Curated intelligence for the modern academic multiverse.</p>
            </div>
            <div class="flex items-center gap-4 bg-white/50 p-2 rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-6 py-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <p class="text-[10px] font-black uppercase tracking-widest leading-none mb-1">Total Assets</p>
                    <p class="text-lg font-black">{{ $blogs->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Discovery Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($blogs as $blog)
            <article class="group bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-sm shadow-slate-100/50 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500 flex flex-col">
                <a href="{{ route('blogs.show', $blog->slug) }}" class="block aspect-[16/10] overflow-hidden relative">
                    @if($blog->featured_image)
                        <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                        <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="absolute top-6 left-6">
                        <span class="px-4 py-2 bg-white/90 backdrop-blur text-[10px] font-black text-secondary tracking-widest uppercase rounded-xl border border-white shadow-sm">
                            Insight Node
                        </span>
                    </div>
                </a>
                
                <div class="p-8 flex-1 flex flex-col">
                    <p class="text-[10px] font-black tracking-widest text-slate-400 mb-2">{{ optional($blog->author)->name ?? 'Multiverse Member' }} · {{ optional($blog->published_at)->format('M d, Y') ?? 'Luminated' }}</p>
                    
                    <h2 class="text-xl font-bold text-slate-900 leading-tight mb-4 group-hover:text-primary transition-colors">
                        <a href="{{ route('blogs.show', $blog->slug) }}">{{ $blog->title }}</a>
                    </h2>
                    
                    <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-8 italic">
                        {{ $blog->summary }}
                    </p>
                    
                    <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-[9px] font-black tracking-widest text-primary uppercase bg-primary/5 px-3 py-1.5 rounded-full">
                            {{ ceil(str_word_count(strip_tags($blog->content ?? '')) / 200) }} MIN READ
                        </span>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xs border border-white">
                                {{ substr(optional($blog->author)->name ?? 'M', 0, 1) }}
                            </div>
                            <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">{{ optional($blog->author)->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-xl font-black text-slate-800">No insights manifested yet.</h3>
                <p class="text-slate-500 font-medium">The multiverse editorial team is currently drafting deep intelligence nodes.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $blogs->links() }}
        </div>
    </div>
</x-app-layout>
