@php 
    $layout = (Auth::check() && Auth::user()->role === 'recruiter') ? 'recruiter' : 'app'; 
    $isOwner = Auth::id() === $user->id;
    $socialLinks = $user->social_links ?? [];
    $skills = $user->skills ?? [];
@endphp

<x-dynamic-component :component="$layout.'-layout'">
    <div class="space-y-12 pb-24 relative" x-data="{ 
        editMode: false,
        activeTab: 'projects',
        coverUploading: false,
        avatarUploading: false,
        avatarPreview: '{{ $user->profile_photo_url }}',
        coverPreview: '{{ $user->cover_photo_url ?? 'https://images.unsplash.com/photo-1614850523296-d8c1af93d400?q=80&w=2070&auto=format&fit=crop' }}',
        
        handleCoverUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.coverUploading = true;
            this.coverPreview = URL.createObjectURL(file);
            
            const formData = new FormData();
            formData.append('cover', file);
            formData.append('_token', '{{ csrf_token() }}');
            
            fetch('{{ route('profile.update-cover') }}', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    this.coverPreview = data.url;
                }
            })
            .finally(() => this.coverUploading = false);
        },

        handleAvatarUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.avatarUploading = true;
            this.avatarPreview = URL.createObjectURL(file);
            
            const formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', '{{ csrf_token() }}');
            
            fetch('{{ route('profile.update-photo') }}', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    this.avatarPreview = data.url;
                }
            })
            .finally(() => this.avatarUploading = false);
        }
    }">

        <!-- Premium Bento Hero (Level 10) -->
        <div class="relative group h-[450px] md:h-[550px] w-full rounded-[4rem] overflow-hidden shadow-2xl bg-slate-950 transition-all duration-700 hover:shadow-primary/10">
            <!-- Cover Layer -->
            <img :src="coverPreview" class="absolute inset-0 w-full h-full object-cover opacity-50 blur-[2px] scale-110 transition-transform duration-[3000ms] group-hover:scale-100 group-hover:blur-0" />
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-950/40 to-slate-950"></div>
            
            <!-- Floating Hero Info -->
            <div class="absolute inset-x-0 bottom-0 p-12 md:p-20 flex flex-col md:flex-row items-center md:items-end justify-between gap-12 z-10 transition-all duration-500">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-10 text-center md:text-left">
                    <!-- High-Res Avatar Bento -->
                    <div class="relative">
                        <div class="w-48 h-48 md:w-56 md:h-56 rounded-[4rem] border-[12px] border-white/5 backdrop-blur-3xl shadow-2xl overflow-hidden bg-slate-900/50 relative overflow-hidden group/avatar ring-1 ring-white/20">
                            <img :src="avatarPreview" class="w-full h-full object-cover transition-transform duration-700 group-hover/avatar:scale-110" />
                            <div class="absolute inset-0 bg-primary/10 mix-blend-overlay"></div>
                        </div>
                        @if($isOwner)
                        <label class="absolute -bottom-2 -right-2 w-14 h-14 bg-white text-primary rounded-[1.5rem] shadow-2xl flex items-center justify-center cursor-pointer hover:scale-110 transition-transform border-4 border-slate-950 group/upload">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover/upload:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <input type="file" class="hidden" @change="handleAvatarUpload" accept="image/*">
                        </label>
                        @endif
                    </div>

                    <!-- Visual Identity Header -->
                    <div class="space-y-4 pb-2">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mb-2">
                            <h1 class="text-4xl md:text-7xl font-black text-white tracking-tighter leading-none drop-shadow-2xl">{{ $user->name }}</h1>
                            @if($user->karma >= 1000)
                            <div class="px-6 py-2 bg-primary/20 backdrop-blur-xl border border-white/20 rounded-full flex items-center gap-3">
                                <span class="w-2 h-2 bg-primary rounded-full animate-ping"></span>
                                <span class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Alpha Node</span>
                            </div>
                            @endif
                        </div>
                        <p class="text-xl md:text-2xl font-black text-white/60 tracking-tight flex items-center justify-center md:justify-start gap-4">
                            <span>{{ $user->career_role ?? 'Academic Manifesto' }}</span>
                            <span class="w-2 h-2 rounded-full bg-white/20"></span>
                            <span class="opacity-80">{{ $user->college->name ?? 'Campus Verse' }}</span>
                        </p>
                        
                        <!-- Social Connectivity (Glass) -->
                        <div class="flex items-center justify-center md:justify-start gap-4 pt-4">
                            @if(isset($socialLinks['linkedin']))
                            <a href="{{ $socialLinks['linkedin'] }}" target="_blank" class="w-14 h-14 rounded-2xl bg-white/5 backdrop-blur-2xl border border-white/10 flex items-center justify-center text-white/50 hover:text-[#0077b5] hover:bg-white hover:scale-110 transition-all">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                            @endif
                            @if(isset($socialLinks['github']))
                            <a href="{{ $socialLinks['github'] }}" target="_blank" class="w-14 h-14 rounded-2xl bg-white/5 backdrop-blur-2xl border border-white/10 flex items-center justify-center text-white/50 hover:text-black hover:bg-white hover:scale-110 transition-all">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                            </a>
                            @endif
                            @if($isOwner)
                            <button @click="editMode = true" class="text-[11px] font-black text-white/40 uppercase tracking-[0.2em] hover:text-primary transition-colors ml-4 border-b border-white/10 pb-1">+ Configure Social Network</button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recruiter Quick-Actions (Bento Floating) -->
                @if(!$isOwner)
                <div class="flex items-center gap-4">
                    <a href="{{ route('chat.index', $user->username) }}" class="h-20 px-12 bg-primary text-white font-black uppercase tracking-widest text-xs flex items-center justify-center rounded-[2.5rem] shadow-2xl shadow-primary/40 hover:scale-105 active:scale-95 transition-all">
                        Initiate Connection
                    </a>
                </div>
                @endif
            </div>

            @if($isOwner)
            <label class="absolute top-12 right-12 glass px-10 py-4 rounded-[1.5rem] text-white text-[10px] font-black uppercase tracking-widest cursor-pointer hover:bg-white/20 transition-all border border-white/20 z-20">
                <span x-show="!coverUploading">⚡ Customize Environment</span>
                <span x-show="coverUploading" class="animate-pulse">Mapping Coverage...</span>
                <input type="file" class="hidden" @change="handleCoverUpload" accept="image/*">
            </label>
            @endif
        </div>

        <!-- The Bento Multiverse Grid (12-Columns) -->
        <div class="grid lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Command Block: Metrics & Visual Signals (Span 4) -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Bento Box: Identity Meta -->
                <div class="glass p-10 rounded-[3.5rem] shadow-glass border-white relative overflow-hidden group/bento transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute top-0 right-0 p-8 opacity-5 text-8xl font-black rotate-12 group-hover/bento:rotate-45 transition-transform duration-1000">🧬</div>
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 border-b border-slate-50 pb-4">Academic Node Info</h4>
                    
                    <div class="space-y-10">
                        <div class="flex items-center gap-6 group/item">
                            <div class="w-14 h-14 rounded-2xl bg-primary/5 flex items-center justify-center text-2xl group-hover/item:bg-primary group-hover/item:text-white transition-all">🏗️</div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Campus Deployment</p>
                                <p class="font-black text-slate-800 tracking-tighter italic leading-none">{{ $user->college->short_name ?? $user->college->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 group/item">
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-2xl group-hover/item:bg-amber-400 group-hover/item:text-white transition-all">🛡️</div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Current Lifecycle</p>
                                <p class="font-black text-slate-800 tracking-tighter leading-none">{{ $user->year ?? 'Undergrad' }} Year • Sem {{ $user->semester }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 group/item">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-2xl group-hover/item:bg-indigo-500 group-hover/item:text-white transition-all">💠</div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Signals Manifested</p>
                                <p class="font-black text-slate-800 tracking-tighter leading-none">{{ number_format($user->karma) }} ARS Points</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bento Box: Skills Matrix (Interactive) -->
                <div class="glass p-10 rounded-[3.5rem] shadow-glass border-white relative overflow-hidden transition-all duration-500 hover:-translate-y-2">
                    <div class="flex justify-between items-center mb-10 border-b border-slate-50 pb-4">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em]">Skills Matrix</h4>
                        @if($isOwner)
                        <button @click="editMode = true" class="text-[10px] font-black text-primary hover:underline uppercase">Sync Matrix</button>
                        @endif
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        @forelse($skills as $skill)
                        <div class="relative group/skill">
                            <div class="absolute inset-0 bg-primary/10 blur-xl opacity-0 group-hover/skill:opacity-100 transition-opacity"></div>
                            <span class="relative block bg-white border border-slate-100 px-5 py-3 rounded-2xl text-[11px] font-black text-slate-600 tracking-tight hover:border-primary/50 hover:text-primary transition-all cursor-default shadow-sm active:scale-95">
                                {{ $skill }}
                            </span>
                        </div>
                        @empty
                        <div class="w-full py-12 text-center bg-slate-50/50 rounded-3xl border-2 border-dashed border-slate-100 italic">
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Awaiting Neural Mapping</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Bento Box: Achievement Signaling -->
                <div class="glass p-10 rounded-[3.5rem] shadow-glass border-white transition-all duration-500 hover:-translate-y-2">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 border-b border-slate-50 pb-4">Signal Achievements</h4>
                    <div class="grid grid-cols-4 gap-4">
                        @forelse($user->badges as $badge)
                        <div class="aspect-square {{ $badge['color'] }} rounded-[1.5rem] flex items-center justify-center text-3xl shadow-sm border-4 border-white transform hover:scale-125 hover:rotate-6 transition-all duration-500 cursor-help" title="{{ $badge['name'] }}">
                            {{ $badge['icon'] }}
                        </div>
                        @empty
                        <div class="col-span-4 py-12 text-center glass rounded-3xl">
                            <p class="text-[10px] font-black text-slate-300 uppercase italic">Nodes Unlocked: 0</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Content Block: High Intensity Activity & Proof of Work (Span 8) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Bento Box: Activity Pulse (Hero Analytic) -->
                <div class="glass p-10 rounded-[4rem] shadow-glass border-white relative overflow-hidden transition-all duration-500 hover:-translate-y-2">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
                        <div>
                            <h4 class="text-lg font-black text-slate-800 tracking-tighter italic">Contribution Vitality</h4>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Mapping Consistency across the Multiverse</p>
                        </div>
                        <div class="flex items-center gap-4 bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100">
                            <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-pulse shadow-lg shadow-emerald-400/20"></span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Real-time Sync Active</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto no-scrollbar py-2" x-data="{
                        heatmap: {{ json_encode($heatmapData) }},
                        days: Array.from({length: 365}, (_, i) => {
                            const d = new Date();
                            d.setDate(d.getDate() - (364 - i));
                            return d.toISOString().split('T')[0];
                        }),
                        getColor(date) {
                            const count = this.heatmap[date] || 0;
                            if (count === 0) return 'bg-slate-100/40 border-slate-100/10';
                            if (count <= 2) return 'bg-primary/20 border-primary/10 scale-105 shadow-sm';
                            if (count <= 5) return 'bg-primary/50 border-primary/20 scale-110 shadow-md';
                            return 'bg-primary border-primary/30 scale-125 shadow-lg shadow-primary/20';
                        }
                    }">
                        <!-- The Grid Canvas (Expanded for impact) -->
                        <div class="grid grid-flow-col grid-rows-7 gap-1.5 min-w-[800px]">
                            <template x-for="date in days" :key="date">
                                <div class="w-3 h-3 rounded-[3px] transition-all hover:scale-[2.5] hover:z-20 cursor-crosshair border" 
                                     :class="getColor(date)"
                                     :title="date + ': ' + (heatmap[date] || 0) + ' contributions'">
                                </div>
                            </template>
                        </div>

                        <!-- Heatmap Meta info -->
                        <div class="flex items-center justify-between pt-10 mt-4 border-t border-slate-100/50">
                            <div class="flex items-center gap-6">
                                <div class="flex items-center gap-3">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Inactive</span>
                                    <div class="w-3 h-3 rounded-[3px] bg-slate-100"></div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-[3px] bg-primary"></div>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Apex Activity</span>
                                </div>
                            </div>
                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic leading-none">Total Contribution Matrix v2.0 • Data verified by Campus Gatekeeper</p>
                        </div>
                    </div>
                </div>

                <!-- Proof of Work & Knowledge Node (Tabs System) -->
                <div class="glass rounded-[4rem] shadow-glass border-white overflow-hidden transition-all duration-500 min-h-[600px]">
                    <div class="px-10 py-8 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between overflow-x-auto no-scrollbar">
                        <div class="flex gap-10">
                            <button @click="activeTab = 'projects'" :class="activeTab === 'projects' ? 'text-primary' : 'text-slate-400'" class="group relative py-2 transition-all">
                                <span class="text-xs font-black uppercase tracking-[0.2em] relative z-10">Manifested Projects</span>
                                <div x-show="activeTab === 'projects'" class="absolute -bottom-2 left-0 w-full h-1.5 bg-primary rounded-full blur-[2px]" x-transition></div>
                            </button>
                            <button @click="activeTab = 'uploads'" :class="activeTab === 'uploads' ? 'text-primary' : 'text-slate-400'" class="group relative py-2 transition-all">
                                <span class="text-xs font-black uppercase tracking-[0.2em] relative z-10">Knowledge Assets</span>
                                <div x-show="activeTab === 'uploads'" class="absolute -bottom-2 left-0 w-full h-1.5 bg-primary rounded-full blur-[2px]" x-transition></div>
                            </button>
                            <button @click="activeTab = 'contributions'" :class="activeTab === 'contributions' ? 'text-primary' : 'text-slate-400'" class="group relative py-2 transition-all">
                                <span class="text-xs font-black uppercase tracking-[0.2em] relative z-10">Social Broadcasts</span>
                                <div x-show="activeTab === 'contributions'" class="absolute -bottom-2 left-0 w-full h-1.5 bg-primary rounded-full blur-[2px]" x-transition></div>
                            </button>
                        </div>
                        
                        @if($isOwner)
                        <a href="{{ route('projects.create') }}" class="text-[10px] font-black text-primary hover:underline uppercase tracking-widest">+ Manifest New</a>
                        @endif
                    </div>

                    <div class="p-10">
                        <!-- Tab Content: Projects (Bento Cards) -->
                        <div x-show="activeTab === 'projects'" x-transition class="grid md:grid-cols-2 gap-8">
                            @forelse($user->projects as $project)
                            <div class="group bg-white border border-slate-100 rounded-[3rem] overflow-hidden hover:shadow-2xl transition-all duration-700">
                                <div class="aspect-[16/10] relative overflow-hidden">
                                    <img src="{{ $project->cover_image_url }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-60"></div>
                                    <div class="absolute bottom-6 left-6 right-6 flex items-center justify-between">
                                        <span class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl text-[9px] font-black text-white uppercase tracking-widest border border-white/20">{{ $project->stream }}</span>
                                        <span class="text-white font-black text-xs">{{ $project->visibility_score }} Signal</span>
                                    </div>
                                </div>
                                <div class="p-8 space-y-4">
                                    <h5 class="text-xl font-black text-slate-800 tracking-tight group-hover:text-primary transition-colors">{{ $project->title }}</h5>
                                    <p class="text-xs text-slate-500 font-bold leading-relaxed line-clamp-2">{{ $project->description }}</p>
                                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                                        <div class="flex -space-x-3">
                                            @foreach($project->endorsements->take(3) as $end)
                                            <img class="w-8 h-8 rounded-full border-2 border-white ring-2 ring-slate-100 shadow-sm" src="{{ $end->user->profile_photo_url }}">
                                            @endforeach
                                        </div>
                                        <a href="{{ $project->file_url }}" target="_blank" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-primary transition-colors flex items-center gap-2">View Node 🔭</a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-span-2 py-40 text-center glass rounded-[3.5rem] border-2 border-dashed border-slate-100 italic">
                                <div class="text-5xl mb-6 opacity-20">🗂️</div>
                                <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.2em]">Evidence of work unmapped. Begin manifestation protocol.</p>
                            </div>
                            @endforelse
                        </div>

                        <!-- Tab Content: Uploads -->
                        <div x-show="activeTab === 'uploads'" x-transition class="grid md:grid-cols-2 gap-6">
                             @forelse($user->notes()->with('subject')->latest()->get() as $pNote)
                             <div class="bg-slate-50/50 border border-slate-100 p-8 rounded-[2.5rem] hover:bg-white hover:shadow-xl transition-all group">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-xl group-hover:bg-primary group-hover:text-white transition-all">📄</div>
                                    <span class="text-[9px] font-black text-slate-300 uppercase italic">v1.2 Secure</span>
                                </div>
                                <h5 class="text-lg font-black text-slate-800 mb-2 truncate group-hover:text-primary transition-colors">{{ $pNote->title }}</h5>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $pNote->subject->name ?? 'Core Academic' }}</p>
                                <div class="mt-8 flex items-center gap-6 text-[9px] font-black text-slate-400 uppercase tracking-widest border-t border-slate-100 pt-6">
                                    <span>📥 {{ $pNote->downloads }} Downloads</span>
                                    <span>⚡ Highly Verified</span>
                                </div>
                             </div>
                             @empty
                             <div class="col-span-2 py-32 text-center">
                                <p class="text-[11px] font-black text-slate-300 uppercase tracking-widest italic">Node assets: Missing.</p>
                             </div>
                             @endforelse
                        </div>

                        <!-- Tab Content: Contributions -->
                        <div x-show="activeTab === 'contributions'" x-transition class="space-y-6">
                             @forelse($user->posts()->latest()->get() as $pPost)
                             <div class="glass p-10 rounded-[3rem] border-white group hover:translate-x-2 transition-all">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-8 h-8 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center text-sm font-black italic">!</div>
                                    <span class="text-[9px] font-black text-primary uppercase tracking-widest italic leading-none">Broadcast Recorded: {{ $pPost->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="text-2xl font-black text-slate-800 tracking-tighter mb-4 group-hover:text-primary transition-colors">{{ $pPost->title }}</h4>
                                <p class="text-sm text-slate-500 font-bold leading-relaxed mb-6">{{ Str::limit($pPost->content, 180) }}</p>
                                <a href="{{ route('community.show', [$user->username, $pPost->slug]) }}" class="text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-[0.2em]">Open Channel Connectivity →</a>
                             </div>
                             @empty
                             <div class="py-32 text-center italic">
                                <p class="text-[11px] font-black text-slate-300 uppercase tracking-widest">Radio silence in the multiverse node.</p>
                             </div>
                             @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Sync Modal (Slide-over Edit High-Fidelity) -->
        <template x-if="editMode">
        <div class="fixed inset-0 z-[100] flex overflow-hidden">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-xl" @click="editMode = false"></div>
            <div class="relative w-full max-w-2xl bg-white shadow-2xl p-12 overflow-y-auto no-scrollbar ml-auto border-l border-white/10 animate-slide-left">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tighter italic">Persona Manifestation Hub</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2 leading-none">Mapping your professional existence in the Verse.</p>
                    </div>
                    <button @click="editMode = false" class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-12" 
                      x-data="{ 
                        skillInput: '',
                        skills: {{ json_encode($skills) }},
                        addSkill() {
                            if (this.skillInput.trim()) {
                                if (!this.skills.includes(this.skillInput.trim())) {
                                    this.skills.push(this.skillInput.trim());
                                }
                                this.skillInput = '';
                            }
                        },
                        removeSkill(index) { this.skills.splice(index, 1); }
                      }">
                    @csrf
                    
                    <!-- Identity Node Upgrade -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 bg-primary rounded-xl flex items-center justify-center text-white text-[10px] font-black">01</span>
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-[0.3em]">Core Persona</h4>
                        </div>
                        <div class="grid gap-8">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Professional Identifier (Role)</label>
                                <input type="text" name="career_role" value="{{ $user->career_role }}" class="w-full bg-slate-50 border-slate-100 rounded-3xl px-8 py-5 font-black text-slate-800 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm" placeholder="AI Researcher, Quant Developer, etc.">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Executive Summary (Curated Bio)</label>
                                <textarea name="bio" rows="4" class="w-full bg-slate-50 border-slate-100 rounded-3xl px-8 py-5 font-black text-slate-800 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm leading-relaxed" placeholder="Narrate your trajectory through the Multiverse...">{{ $user->bio }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Skills Neural Network -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 bg-amber-400 rounded-xl flex items-center justify-center text-white text-[10px] font-black">02</span>
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-[0.3em]">Technical Mapping</h4>
                        </div>
                        <div>
                            <div class="flex gap-4 mb-6">
                                <input type="text" x-model="skillInput" @keydown.enter.prevent="addSkill()" class="flex-1 bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 text-xs font-black" placeholder="Manifest a skill (e.g. PyTorch)...">
                                <button type="button" @click="addSkill()" class="bg-slate-900 text-white px-8 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all">Deploy</button>
                            </div>
                            <div class="flex flex-wrap gap-3 p-6 bg-slate-50 rounded-[2.5rem] border border-slate-100 min-h-[100px]">
                                <template x-for="(skill, index) in skills" :key="index">
                                    <div class="bg-white border border-slate-200 px-5 py-2.5 rounded-xl text-[10px] font-black text-slate-800 flex items-center gap-4 shadow-sm group">
                                        <span x-text="skill"></span>
                                        <button type="button" @click="removeSkill(index)" class="text-slate-300 hover:text-red-500 transition-colors">×</button>
                                        <input type="hidden" name="skills[]" :value="skill">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- External Connectivity -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 bg-indigo-500 rounded-xl flex items-center justify-center text-white text-[10px] font-black">03</span>
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-[0.3em]">External Nodes (Socials)</h4>
                        </div>
                        <div class="grid gap-4">
                            <div class="flex items-center gap-6 bg-slate-50 p-5 rounded-3xl border border-slate-100 group focus-within:ring-4 focus-within:ring-primary/10 transition-all">
                                <div class="w-10 h-10 rounded-xl bg-white text-[#0077b5] flex items-center justify-center font-black shadow-sm">L</div>
                                <input type="url" name="social_links[linkedin]" value="{{ $socialLinks['linkedin'] ?? '' }}" class="flex-1 bg-transparent border-none focus:ring-0 text-xs font-black text-slate-800" placeholder="LinkedIn Deployment URL">
                            </div>
                            <div class="flex items-center gap-6 bg-slate-50 p-5 rounded-3xl border border-slate-100 focus-within:ring-4 focus-within:ring-primary/10 transition-all">
                                <div class="w-10 h-10 rounded-xl bg-white text-black flex items-center justify-center font-black shadow-sm">G</div>
                                <input type="url" name="social_links[github]" value="{{ $socialLinks['github'] ?? '' }}" class="flex-1 bg-transparent border-none focus:ring-0 text-xs font-black text-slate-800" placeholder="GitHub Repository URL">
                            </div>
                            <div class="flex items-center gap-6 bg-slate-50 p-5 rounded-3xl border border-slate-100 focus-within:ring-4 focus-within:ring-primary/10 transition-all">
                                <div class="w-10 h-10 rounded-xl bg-white text-[#1769ff] flex items-center justify-center font-black shadow-sm">B</div>
                                <input type="url" name="social_links[behance]" value="{{ $socialLinks['behance'] ?? '' }}" class="flex-1 bg-transparent border-none focus:ring-0 text-xs font-black text-slate-800" placeholder="Behance Case Study URL">
                            </div>
                        </div>
                    </div>

                    <div class="pt-12 pb-12">
                        <button type="submit" class="w-full h-24 bg-primary text-white rounded-[3rem] font-black uppercase tracking-[0.3em] shadow-2xl shadow-primary/40 hover:scale-[1.02] active:scale-95 transition-all text-xs">Synchronize Persona with Verse Node</button>
                    </div>
                </form>
            </div>
        </div>
        </template>

    </div>

    <style>
        @keyframes slide-left {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        .animate-slide-left {
            animation: slide-left 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-dynamic-component>
