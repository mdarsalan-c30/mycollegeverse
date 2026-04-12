<x-admin-layout>
    <div class="space-y-8">
        <!-- Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary tracking-tight">Community Moderation</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Oversee, highlight, and secure the social multiverse</p>
            </div>
            
            <div class="flex items-center gap-4">
                <form action="{{ route('admin.community') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search discussions or citizens..." class="w-80 h-12 bg-white border border-admin-border rounded-2xl px-6 pl-12 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    <div class="absolute left-4 top-3.5 text-slate-300 group-focus-within:text-admin-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-100 pb-px text-xs font-black uppercase tracking-widest italic">
            <a href="{{ route('admin.community', ['type' => 'all']) }}" class="px-6 py-3 transition-all {{ request('type', 'all') == 'all' ? 'text-admin-primary border-b-2 border-admin-primary' : 'text-slate-400 hover:text-slate-600' }}">Live Feed</a>
            <a href="{{ route('admin.community', ['type' => 'pinned']) }}" class="px-6 py-3 transition-all {{ request('type') == 'pinned' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">Pinned Spotlight</a>
            <a href="{{ route('admin.community', ['type' => 'reported']) }}" class="px-6 py-3 transition-all {{ request('type') == 'reported' ? 'text-red-600 border-b-2 border-red-600' : 'text-slate-400 hover:text-slate-600' }}">Flagged Interactions</a>
        </div>

        <!-- Community Interaction Grid (Stitch UI Mirror) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($posts as $post)
            <div class="glass p-8 rounded-[2.5rem] border-white/50 shadow-xl shadow-slate-200/50 group hover:bg-white transition-all flex flex-col items-start italic relative overflow-hidden">
                <!-- Status Badge -->
                @if($post->is_pinned)
                    <div class="absolute -right-12 top-6 bg-indigo-600 text-white text-[8px] font-bold uppercase tracking-[0.2em] transform rotate-45 py-2 w-48 text-center shadow-lg">
                        Spotlight
                    </div>
                @endif

                <div class="flex items-center gap-4 mb-6">
                    <img src="{{ $post->user->profile_photo_url }}" class="w-10 h-10 rounded-xl object-cover shadow-sm bg-slate-50 border border-slate-100" />
                    <div>
                        <p class="text-[11px] font-black text-admin-dark">{{ $post->user->name }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="flex-1 w-full">
                    <div class="bg-slate-50/50 rounded-2xl p-5 mb-6 group-hover:bg-white transition-colors border border-slate-50">
                        <p class="text-xs font-bold text-admin-secondary leading-relaxed line-clamp-4">{{ $post->content }}</p>
                    </div>

                    <div class="flex items-center gap-6 mb-8 mt-auto">
                        <div class="flex items-center gap-2">
                            <span class="text-xs">❤️</span>
                            <span class="text-[10px] font-black text-slate-400">{{ $post->likes_count }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs">💬</span>
                            <span class="text-[10px] font-black text-slate-400">{{ $post->comments_count }}</span>
                        </div>
                    </div>
                </div>

                <div class="w-full pt-6 border-t border-slate-100 flex items-center justify-between mt-auto">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $post->college->name ?? 'Global Verse' }}</p>
                    
                    <div class="flex items-center gap-1">
                        <!-- Toggle Pin -->
                        <form action="{{ route('admin.community.pin', $post) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-3 {{ $post->is_pinned ? 'text-indigo-600' : 'text-slate-300 hover:text-indigo-600' }} transition-all hover:bg-indigo-50 rounded-xl" title="Toggle Spotlight">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                            </button>
                        </form>

                        <!-- Purge Action -->
                        <form action="{{ route('admin.community.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Purge this social node from the multiverse?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-3 text-red-500 hover:bg-red-500/10 transition-all rounded-xl" title="Purge Discussion">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full p-24 text-center glass rounded-[2.5rem] border-dashed border-slate-200 shadow-inner">
                <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.3em] italic">No social activity matching your node search.</p>
            </div>
            @endforelse
        </div>

        <!-- High-Fidelity Pagination -->
        <div class="pt-10">
            {{ $posts->links() }}
        </div>
    </div>
</x-admin-layout>
