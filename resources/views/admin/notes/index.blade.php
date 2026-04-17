<x-admin-layout>
    <div class="space-y-8" x-data="{ 
        openPreview: false, 
        previewUrl: '',
        showBulkModal: false,
        {{-- Staging Logic --}}
        selectedSubject: '',
        detailLevel: 'detailed',
        topicsInput: '',
        topicsList: [],
        isGenerating: false,

        prepareStaging() {
            if (!this.topicsInput.trim()) return;
            const lines = this.topicsInput.split('\n').map(l => l.trim()).filter(l => l.length > 0);
            this.topicsList = lines.map(title => ({
                title: title,
                status: 'pending',
                error: null,
                selected: true,
                noteId: null
            }));
        },

        async generateItem(item) {
            if (item.status === 'done' || item.status === 'loading') return;
            item.status = 'loading';
            item.error = null;

            try {
                const response = await fetch('{{ route('admin.notes.generate.single') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        topic: item.title,
                        subject_id: this.selectedSubject,
                        detail_level: this.detailLevel
                    })
                });
                const data = await response.json();
                if (data.success) {
                    item.status = 'done';
                    item.noteId = data.note_id;
                } else {
                    item.status = 'error';
                    item.error = data.error || 'Server error';
                }
            } catch (e) {
                item.status = 'error';
                item.error = 'System failure';
            }
        },

        async generateSelected() {
            if (this.isGenerating) return;
            this.isGenerating = true;
            const toProcess = this.topicsList.filter(i => i.selected && i.status === 'pending');
            for (const item of toProcess) {
                await this.generateItem(item);
            }
            this.isGenerating = false;
        }
    }">
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

                <button type="button" @click="showBulkModal = true" class="h-12 bg-gradient-to-r from-violet-600 to-indigo-600 text-white px-6 rounded-2xl flex items-center gap-2 text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">
                    <span>🤖 Bulk Generate AI</span>
                </button>

                <form action="{{ route('admin.notes') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or citizen..." class="w-72 h-12 bg-white border border-admin-border rounded-2xl px-6 pl-12 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    <div class="absolute left-4 top-3.5 text-slate-300 group-focus-within:text-admin-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>
            </div>
        </div>

        <!-- 🚀 Gemini Intelligence Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass p-6 rounded-[2rem] border-white/60 bg-gradient-to-br from-violet-500 to-indigo-600 text-white">
                <p class="text-[8px] font-black uppercase tracking-[0.2em] opacity-80">Today's Token Flow</p>
                <h4 class="text-3xl font-black mt-1">{{ number_format($stats['today_tokens']) }}</h4>
                <div class="flex items-center gap-2 mt-4 text-[10px] bg-white/10 w-fit px-3 py-1 rounded-full font-bold">
                    <div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div>
                    Real-time Sync
                </div>
            </div>
            <div class="glass p-6 rounded-[2rem] border-white/60">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em]">Career Total Tokens</p>
                <h4 class="text-3xl font-black text-admin-secondary mt-1">{{ number_format($stats['total_tokens']) }}</h4>
                <p class="text-[9px] font-bold text-slate-400 mt-2 italic">~{{ number_format($stats['total_tokens'] / 1500) }} Full Articles</p>
            </div>
            <div class="glass p-6 rounded-[2rem] border-white/60">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em]">Intelligence Volume</p>
                <h4 class="text-3xl font-black text-admin-secondary mt-1">{{ number_format($stats['total_generations']) }}</h4>
                <p class="text-[9px] font-bold text-green-500 mt-2">+{{ $stats['today_generations'] }} Deployments today</p>
            </div>
            <div class="glass p-6 rounded-[2rem] border-white/60 border-indigo-100 bg-indigo-50/30">
                <p class="text-[8px] font-black text-indigo-400 uppercase tracking-[0.2em]">Quota Monitor</p>
                <div class="mt-2 space-y-1">
                    <div class="flex justify-between items-center text-[10px] font-black text-indigo-900">
                        <span>Free Tier</span>
                        <span>ACTIVE</span>
                    </div>
                    <div class="w-full bg-indigo-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-indigo-500 h-full w-[15%]"></div>
                    </div>
                    <p class="text-[7px] font-bold text-indigo-400 italic">250,000 TPM Capacity</p>
                </div>
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

        <!-- 🤖 AI Bulk Generation Modal (Phase 5 Stable) -->
        <div x-show="showBulkModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[110] flex items-center justify-center bg-slate-900/80 backdrop-blur-xl px-4 py-4"
             x-cloak>
            
            <div @click.away="!isGenerating && (showBulkModal = false)" 
                 class="bg-white w-full max-w-5xl max-h-[90vh] rounded-[3rem] shadow-2xl flex flex-col overflow-hidden border border-white/20">
                
                {{-- Modal Header --}}
                <div class="px-10 py-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-violet-500 to-indigo-600 text-white rounded-[1.5rem] flex items-center justify-center text-2xl shadow-lg shadow-indigo-500/20">🤖</div>
                        <div>
                            <h3 class="text-2xl font-black text-admin-secondary tracking-tight">AI Staging Area</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic">Manifesting academic intelligence into the multiverse</p>
                        </div>
                    </div>
                    <button type="button" @click="showBulkModal = false" :disabled="isGenerating" class="w-12 h-12 rounded-full hover:bg-slate-200 flex items-center justify-center text-slate-400 transition-all disabled:opacity-20">✕</button>
                </div>

                {{-- Modal Body --}}
                <div class="flex-1 overflow-y-auto p-10 custom-scrollbar">
                    
                    {{-- STEP 1: INPUT (Visible when topicsList is empty) --}}
                    <div x-show="topicsList.length === 0" class="space-y-8" x-transition>
                        {{-- Topics --}}
                        <div class="space-y-4">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest px-1">Note Topics (One topic per line)</label>
                            <textarea x-model="topicsInput" rows="6" 
                                      placeholder="e.g. Introduction to Quantum Computing&#10;Schrödinger's Cat Paradox"
                                      class="w-full bg-slate-50 border-slate-100 rounded-[2rem] px-8 py-6 focus:ring-4 focus:ring-violet-500/5 focus:border-violet-500 text-lg font-bold placeholder:text-slate-300 transition-all"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Subject Selector --}}
                            <div class="space-y-4">
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest px-1">Target Subject Node</label>
                                <select x-model="selectedSubject" class="w-full h-16 bg-slate-50 border-slate-100 rounded-[1.5rem] px-6 text-sm font-bold text-slate-800 focus:ring-4 focus:ring-violet-500/5 transition-all">
                                    <option value="">Select Target Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">[{{ optional($subject->course)->name }}] {{ $subject->name }} (Sem {{ $subject->semester }})</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Complexity --}}
                            <div class="space-y-4">
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest px-1">Generation Complexity</label>
                                <div class="flex gap-4">
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" x-model="detailLevel" value="quick" class="peer hidden">
                                        <div class="h-16 border-2 border-slate-100 rounded-[1.5rem] flex items-center justify-center gap-2 font-bold text-slate-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                                            ⚡ Quick
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" x-model="detailLevel" value="detailed" class="peer hidden">
                                        <div class="h-16 border-2 border-slate-100 rounded-[1.5rem] flex items-center justify-center gap-2 font-bold text-slate-400 peer-checked:border-violet-500 peer-checked:bg-violet-50 peer-checked:text-violet-600 transition-all">
                                            📚 Detailed
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="button" @click="prepareStaging()" :disabled="!topicsInput.trim() || !selectedSubject" 
                                class="w-full h-20 bg-admin-secondary text-white rounded-[2rem] font-black text-sm uppercase tracking-widest shadow-2xl shadow-indigo-500/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50">
                            🔬 Initialize Staging Area
                        </button>
                    </div>

                    {{-- STEP 2: STAGING (Visible when topicsList has items) --}}
                    <div x-show="topicsList.length > 0" class="space-y-6" x-transition>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-6">
                            <div>
                                <h4 class="text-xl font-black text-admin-secondary">Batch Configuration</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Ready for atomic manifestation</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="topicsList = []; topicsInput = ''" :disabled="isGenerating" class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-rose-500 transition-colors">Clear Batch</button>
                                <button type="button" @click="generateSelected()" :disabled="isGenerating || topicsList.filter(i => i.selected && i.status === 'pending').length === 0"
                                        class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:scale-105 transition-all disabled:opacity-50 flex items-center gap-3">
                                    <svg x-show="isGenerating" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    <span x-text="isGenerating ? 'Deploying Intelligence...' : 'Launch Manifestation'"></span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(item, index) in topicsList" :key="index">
                                <div class="flex items-center gap-6 p-6 rounded-[2rem] border transition-all"
                                     :class="{
                                         'bg-emerald-50 border-emerald-100': item.status === 'done',
                                         'bg-rose-50 border-rose-100': item.status === 'error',
                                         'bg-slate-50 border-slate-100': item.status === 'pending',
                                         'bg-blue-50 border-blue-100 animate-pulse': item.status === 'loading'
                                     }">
                                    
                                    <input type="checkbox" x-model="item.selected" :disabled="item.status !== 'pending' || isGenerating"
                                           class="h-6 w-6 rounded-lg border-slate-300 text-indigo-600 focus:ring-indigo-500/20">

                                    <div class="flex-1">
                                        <h5 class="font-black text-slate-800" x-text="item.title"></h5>
                                        <p x-show="item.status === 'error'" class="text-[10px] text-rose-500 font-bold mt-1" x-text="item.error"></p>
                                        <p x-show="item.status === 'done'" class="text-[10px] text-emerald-600 font-black mt-1 uppercase tracking-tighter">Manifestation Verified</p>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <template x-if="item.status === 'loading'">
                                            <div class="flex items-center gap-2 text-[10px] font-black text-indigo-600 uppercase italic">
                                                Scanning...
                                            </div>
                                        </template>
                                        <template x-if="item.status === 'done'">
                                            <div class="h-10 w-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24 font-bold"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" /></svg>
                                            </div>
                                        </template>
                                        <template x-if="item.status === 'error'">
                                            <button type="button" @click="generateItem(item)" class="bg-rose-500 text-white px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-lg shadow-rose-500/20">Retry</button>
                                        </template>
                                        <template x-if="item.status === 'pending'">
                                            <button type="button" :disabled="isGenerating" @click="generateItem(item)" class="bg-white text-slate-600 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-slate-200 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm">
                                                Single Launch
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="px-10 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Hostinger Stable Engine Active • No Page Reload Required</p>
                    <button type="button" @click="showBulkModal = false" :disabled="isGenerating" class="text-xs font-black text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Exit Staging Area</button>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>

