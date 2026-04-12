<x-admin-layout>
    <div class="space-y-8">
        <!-- Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary tracking-tight">Feedback Governance</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Moderating the reputational signal of the multiverse</p>
            </div>
            
            <div class="flex items-center gap-4">
                <form action="{{ route('admin.reviews') }}" method="GET" class="relative group">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search comments..." class="w-80 h-12 bg-white border border-admin-border rounded-2xl px-6 pl-12 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    <div class="absolute left-4 top-3.5 text-slate-300 group-focus-within:text-admin-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-100 pb-px text-xs font-black uppercase tracking-widest italic">
            <a href="{{ route('admin.reviews', ['type' => 'college']) }}" class="px-6 py-3 transition-all {{ $type == 'college' ? 'text-admin-primary border-b-2 border-admin-primary' : 'text-slate-400 hover:text-slate-600' }}">Institutional (Colleges)</a>
            <a href="{{ route('admin.reviews', ['type' => 'professor']) }}" class="px-6 py-3 transition-all {{ $type == 'professor' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">Faculty (Professors)</a>
        </div>

        <!-- Review Grid (Stitch UI Mirror) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($reviews as $review)
            <div class="glass p-8 rounded-[2.5rem] border-white/50 shadow-xl shadow-slate-200/50 group hover:bg-white transition-all flex flex-col italic relative overflow-hidden">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-slate-300">
                            {{ substr($review->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-admin-dark">{{ $review->user->name }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="text-xs">⭐</span>
                        <span class="text-[10px] font-black text-admin-primary">{{ number_format($type == 'college' ? ($review->campus_rating + $review->faculty_rating + $review->academic_rating) / 3 : $review->rating, 1) }}</span>
                    </div>
                </div>

                <div class="bg-slate-50/50 rounded-2xl p-5 mb-6 group-hover:bg-white transition-colors border border-slate-50 flex-1">
                    <p class="text-xs font-bold text-admin-secondary leading-relaxed line-clamp-4">"{{ $review->comment }}"</p>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-between mt-auto">
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Target Identity</p>
                        <p class="text-[10px] font-black text-admin-primary italic">
                            {{ $type == 'college' ? ($review->college->name ?? 'Unknown Node') : ($review->professor->name ?? 'Unknown Advisor') }}
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-1">
                        <form action="{{ route('admin.reviews.destroy', [$type, $review->id]) }}" method="POST" class="inline" onsubmit="return confirm('Purge this reputational signal?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-3 text-red-500 hover:bg-red-500/10 transition-all rounded-xl" title="Purge Feedback">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full p-24 text-center glass rounded-[2.5rem] border-dashed border-slate-200">
                <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.3em] italic">No feedback signals detected for this registry type.</p>
            </div>
            @endforelse
        </div>

        <!-- High-Fidelity Pagination -->
        <div class="pt-10">
            {{ $reviews->links() }}
        </div>
    </div>
</x-admin-layout>
