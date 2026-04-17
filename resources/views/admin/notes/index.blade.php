<x-admin-layout>
    <div class="space-y-8" x-data="{ openPreview: false, previewUrl: '' }">
        <!-- Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary tracking-tight">Knowledge Moderation</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Scan, verify, and authorize academic assets</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden lg:flex items-center gap-2 mr-4 bg-slate-50 p-2 rounded-2xl border border-slate-100">
                    <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 text-[9px] font-black uppercase tracking-widest text-slate-500 hover:text-admin-primary hover:bg-white rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        Academic Paths
                    </a>
                    <a href="{{ route('admin.subjects.index') }}" class="px-4 py-2 text-[9px] font-black uppercase tracking-widest text-slate-500 hover:text-admin-primary hover:bg-white rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                        Subject Nodes
                    </a>
                </div>

                <a href="{{ route('admin.notes.bulk') }}" class="h-12 bg-gradient-to-r from-violet-600 to-indigo-600 text-white px-6 rounded-2xl flex items-center gap-2 text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">
                    <span>🤖 Bulk Generate AI</span>
                </a>

                <form action="{{ route('admin.notes') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or citizen..." class="w-72 h-12 bg-white border border-admin-border rounded-2xl px-6 pl-12 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    <div class="absolute left-4 top-3.5 text-slate-300 group-focus-within:text-admin-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-100 pb-px">
            <a href="{{ route('admin.notes', ['status' => 'pending']) }}" class="px-6 py-3 text-[10px] font-black uppercase tracking-widest transition-all {{ request('status', 'pending') == 'pending' ? 'text-admin-primary border-b-2 border-admin-primary' : 'text-slate-400 hover:text-slate-600' }}">Pending Verification</a>
            <a href="{{ route('admin.notes', ['status' => 'verified']) }}" class="px-6 py-3 text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') == 'verified' ? 'text-green-600 border-b-2 border-green-600' : 'text-slate-400 hover:text-slate-600' }}">Institutional Assets (Verified)</a>
            <a href="{{ route('admin.notes', ['status' => 'all']) }}" class="px-6 py-3 text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') == 'all' ? 'text-slate-600 border-b-2 border-slate-600' : 'text-slate-400 hover:text-slate-600' }}">All Uploads</a>
        </div>

        <!-- Notes Moderation Grid (Stitch UI Mirror) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($notes as $note)
            <div class="glass p-8 rounded-[2.5rem] border-white/50 shadow-xl shadow-slate-200/50 group hover:bg-white transition-all flex flex-col items-start italic">
                <div class="flex items-center justify-between w-full mb-6">
                    <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform">
                        📄
                    </div>
                    @if(!$note->is_verified)
                        <span class="px-4 py-1.5 bg-yellow-500/10 text-yellow-600 text-[9px] font-black uppercase tracking-widest rounded-full">Moderation Hub</span>
                    @else
                        <span class="px-4 py-1.5 bg-green-500/10 text-green-600 text-[9px] font-black uppercase tracking-widest rounded-full">Authorized Asset</span>
                    @endif
                </div>

                <div class="flex-1 space-y-2">
                    <h3 class="text-lg font-black text-admin-secondary leading-tight line-clamp-2">{{ $note->title }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ optional($note->college)->name ?? 'Global Hub' }}</p>
                </div>

                <div class="w-full mt-8 pt-8 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-admin-surface text-[10px] font-black text-slate-400 flex items-center justify-center border border-slate-100">
                            {{ substr(optional($note->user)->name ?? '?', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-admin-dark truncate w-24">{{ optional($note->user)->name ?? 'Unknown' }}</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase">{{ $note->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Preview Action -->
                        <button type="button" @click="openPreview = true; previewUrl = '{{ $note->pdf_url }}'" class="p-3 text-slate-400 hover:text-admin-primary transition-all hover:bg-admin-primary/5 rounded-xl" title="Deep Scan Asset">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                        
                        @if(!$note->is_verified)
                        <!-- Verify Action -->
                        <form action="{{ route('admin.notes.verify', $note) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-3 text-green-500 hover:bg-green-500/10 transition-all rounded-xl" title="Authorize Deployment">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            </button>
                        </form>

                        <!-- Reject Action -->
                        <form action="{{ route('admin.notes.destroy', $note) }}" method="POST" class="inline" onsubmit="return confirm('Confirm asset purge from the multiverse?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-3 text-red-500 hover:bg-red-500/10 transition-all rounded-xl" title="Purge Asset">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full p-24 text-center glass rounded-[2.5rem] border-dashed border-slate-200">
                <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.3em] italic">Verification queue cleared. No pending assets.</p>
            </div>
            @endforelse
        </div>

        <!-- Integrated Node Viewer (High-Fidelity Modal) -->
        <div x-show="openPreview" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-admin-secondary/60 backdrop-blur-md px-6 py-6"
             x-cloak>
            <div @click.away="openPreview = false" class="bg-white w-full h-full max-w-6xl rounded-[3rem] shadow-2xl flex flex-col overflow-hidden border border-white/20">
                <!-- Modal Header -->
                <div class="px-10 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-admin-primary text-white rounded-xl flex items-center justify-center text-xl">📄</div>
                        <div>
                            <h3 class="text-lg font-black text-admin-secondary leading-none">Asset Deep Scan</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic">Verifying node integrity before multiverse deployment</p>
                        </div>
                    </div>
                    <button type="button" @click="openPreview = false" class="w-10 h-10 rounded-full hover:bg-slate-200 flex items-center justify-center text-slate-400 transition-colors">✕</button>
                </div>

                <!-- Viewer Node -->
                <div class="flex-1 bg-slate-900 overflow-hidden relative">
                    <iframe :src="previewUrl" class="w-full h-full border-none" x-show="previewUrl"></iframe>
                    <div class="absolute inset-0 flex items-center justify-center" x-show="!previewUrl">
                        <p class="text-white font-black text-xs uppercase tracking-[0.3em] animate-pulse">Initializing Scan...</p>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-10 py-6 bg-white flex items-center justify-end gap-4">
                    <button type="button" @click="openPreview = false" class="px-8 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Close Scan</button>
                </div>
            </div>
        </div>

        <!-- High-Fidelity Pagination -->
        <div class="pt-10 italic">
            {{ $notes->links() }}
        </div>
    </div>
</x-admin-layout>
