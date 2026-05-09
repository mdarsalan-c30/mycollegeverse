<x-app-layout>
    @section('title', $guide->meta_title ?? $guide->title . ' | Academic Hub')
    @section('meta_description', $guide->meta_description)
    @section('meta_keywords', $guide->meta_keywords)

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex items-center text-[10px] font-black uppercase tracking-widest text-slate-400 mb-8 overflow-x-auto whitespace-nowrap pb-2">
            <a href="{{ route('guides.index') }}" class="hover:text-primary transition-colors">Academic Hub</a>
            <span class="mx-3 opacity-30">/</span>
            <span class="opacity-50">{{ $guide->category }}</span>
        </nav>

        <article class="glass rounded-[3rem] overflow-hidden border border-slate-100 shadow-2xl">
            <!-- Hero Header -->
            <div class="px-8 md:px-16 pt-16 pb-12 bg-white border-b border-slate-50 relative">
                <div class="absolute top-0 right-0 p-8">
                    <span class="px-4 py-1.5 bg-primary/5 text-primary text-[10px] font-black uppercase tracking-widest rounded-full ring-1 ring-primary/20">
                        {{ $guide->category }}
                    </span>
                </div>

                <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-[1.1] tracking-tight mb-8">
                    {{ $guide->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-500 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-indigo-200">
                            {{ substr($guide->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Archivist</p>
                            <p class="text-xs font-bold text-slate-900">{{ $guide->user->name }}</p>
                        </div>
                    </div>

                    <div class="h-10 w-[1px] bg-slate-100 hidden sm:block"></div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-xl">👁️</div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Views</p>
                            <p class="text-xs font-bold text-slate-900">{{ number_format($guide->views) }}</p>
                        </div>
                    </div>

                    <div class="h-10 w-[1px] bg-slate-100 hidden sm:block"></div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-xl">🕒</div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Last Synced</p>
                            <p class="text-xs font-bold text-slate-900">{{ $guide->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="px-8 md:px-16 py-16 prose prose-slate max-w-none prose-headings:font-black prose-headings:uppercase prose-headings:tracking-tight prose-p:text-slate-600 prose-p:font-medium prose-p:leading-relaxed prose-a:text-primary prose-a:font-bold hover:prose-a:underline">
                {!! $guide->content !!}
            </div>

            <!-- Meta Nodes (SEO Data) -->
            @if($guide->target_university || $guide->target_course)
            <div class="px-8 md:px-16 py-10 bg-slate-50/50 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-8">
                @if($guide->target_university)
                <div class="space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Target Node (University)</p>
                    <p class="text-sm font-bold text-slate-900 italic">{{ $guide->target_university }}</p>
                </div>
                @endif
                @if($guide->target_course)
                <div class="space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Target Sequence (Course)</p>
                    <p class="text-sm font-bold text-slate-900 italic">{{ $guide->target_course }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Author Actions -->
            @if(Auth::check() && (Auth::id() === $guide->user_id || Auth::user()->role === 'admin'))
            <div class="px-8 md:px-16 py-8 border-t border-slate-100 flex items-center justify-end gap-4 bg-white/50">
                <a href="{{ route('guides.edit', $guide->id) }}" class="px-6 py-2 border-2 border-slate-100 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:border-primary hover:text-primary transition-all">
                    Edit Node 📝
                </a>
                <form action="{{ route('guides.destroy', $guide->id) }}" method="POST" onsubmit="return confirm('Purge this guide from the multiverse?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 border-2 border-transparent text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition-all">
                        Purge 🗑️
                    </button>
                </form>
            </div>
            @endif
        </article>

        <!-- Related Nodes -->
        @if($related->count() > 0)
        <div class="mt-20">
            <h2 class="text-xl font-black text-slate-900 uppercase tracking-widest mb-10 text-center">Related Academic Nodes 🪐</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($related as $rel)
                <a href="{{ route('guides.show', $rel->slug) }}" class="group bg-white p-8 rounded-[2rem] border border-slate-100 hover:shadow-xl transition-all block">
                    <span class="text-[10px] font-black uppercase tracking-widest text-primary mb-4 block">{{ $rel->category }}</span>
                    <h4 class="font-black text-slate-900 group-hover:text-primary transition-colors leading-tight mb-2">{{ $rel->title }}</h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Read Sequence →</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Call to Action -->
        <div class="mt-20 py-16 px-8 glass rounded-[3rem] text-center bg-gradient-to-br from-primary/5 to-indigo-500/5">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tight mb-4">Have something to contribute? 🧠</h2>
            <p class="text-slate-500 font-bold text-sm max-w-xl mx-auto mb-10">Your insights can help thousands of students navigate their academic journey. Manifest your knowledge today.</p>
            @guest
                <a href="{{ route('login') }}" class="inline-flex items-center px-10 py-4 bg-slate-900 text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-black transition-all">
                    Join the Multiverse 🌌
                </a>
            @else
                <a href="{{ route('guides.create') }}" class="inline-flex items-center px-10 py-4 bg-primary text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                    Manifest New Node 🌌
                </a>
            @endguest
        </div>
    </div>
</x-app-layout>
