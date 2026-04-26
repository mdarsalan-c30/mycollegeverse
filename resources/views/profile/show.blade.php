@php 
    $layout = (Auth::check() && Auth::user()->role === 'recruiter') ? 'recruiter' : 'app'; 
    $isOwner = Auth::id() === $user->id;
    $socialLinks = $user->social_links ?? [];
    $skills = $user->skills ?? [];
    
    // SEO & Social Identity 🧬
    $seoTitle = $user->name . " | " . ($user->career_role ?? 'Academic Identity') . " on MyCollegeVerse";
    $seoDescription = "View " . $user->name . "'s academic profile, projects, and verified notes at " . ($user->college->name ?? 'the Verse') . ". Connect via the Multiverse Pipeline.";
    $ogImage = $user->profile_photo_path 
               ? $user->profile_photo_url 
               : "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=3B82F6&color=fff&size=512&bold=true";
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)

@push('head')
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="profile">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $seoTitle }}">
    <meta property="twitter:description" content="{{ $seoDescription }}">
    <meta property="twitter:image" content="{{ $ogImage }}">
@endpush

<x-dynamic-component :component="$layout.'-layout'">
    <div class="space-y-10 pb-24 relative" x-data="{ 
        editMode: false,
        activeTab: 'projects',
        coverUploading: false,
        avatarUploading: false,
        avatarPreview: '{{ $user->profile_photo_url }}',
        coverPreview: '{{ $user->cover_photo_url ?? 'https://images.unsplash.com/photo-1614850523296-d8c1af93d400?q=80&w=2070&auto=format&fit=crop' }}',
        
        switchTab(tab) {
            this.activeTab = tab;
        },

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

        <!-- Level 10 Bento Hero (Refined) -->
        <div class="relative group h-[400px] md:h-[500px] w-full rounded-[3.5rem] overflow-hidden shadow-2xl bg-slate-950 transition-all duration-700">
            <!-- Cinematic Cover -->
            <img :src="coverPreview" class="absolute inset-0 w-full h-full object-cover opacity-60 transition-transform duration-[4000ms] group-hover:scale-105" />
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-950/40 to-slate-950"></div>
            
            <!-- Hero Interface -->
            <div class="absolute inset-x-0 bottom-0 p-10 md:p-16 flex flex-col md:flex-row items-center md:items-end justify-between gap-10 z-10">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-8 text-center md:text-left">
                    <!-- High-Res Avatar -->
                    <div class="relative">
                        <div class="w-40 h-40 md:w-48 md:h-48 rounded-[3rem] border-[10px] border-white/5 backdrop-blur-3xl shadow-2xl bg-slate-900/50 relative overflow-hidden group/avatar ring-1 ring-white/20">
                            <img :src="avatarPreview" class="w-full h-full object-cover transition-transform duration-700 group-hover/avatar:scale-110" />
                            <div class="absolute inset-0 bg-primary/10 mix-blend-overlay"></div>
                        </div>
                        @if($isOwner)
                        <label class="absolute -bottom-2 -right-2 w-12 h-12 bg-white text-primary rounded-[1.2rem] shadow-2xl flex items-center justify-center cursor-pointer hover:scale-110 transition-transform border-4 border-slate-950 group/upload">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <input type="file" class="hidden" @change="handleAvatarUpload" accept="image/*">
                        </label>
                        @endif
                    </div>

                    <!-- Identity Block -->
                    <div class="space-y-3 pb-2">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mb-1">
                            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-none whitespace-nowrap">{{ $user->name }}</h1>
                            @if($user->karma >= 1000)
                            <div class="px-5 py-1.5 bg-primary/20 backdrop-blur-xl border border-white/20 rounded-full flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
                                <span class="text-[9px] font-black text-white uppercase tracking-widest">Verified Node</span>
                            </div>
                            @endif
                        </div>
                        <p class="text-lg md:text-xl font-bold text-white/50 tracking-tight flex items-center justify-center md:justify-start gap-3">
                            <span>{{ $user->career_role ?? 'Professional Academic' }}</span>
                            <span class="w-1 h-1 rounded-full bg-white/20"></span>
                            <span class="opacity-80">{{ $user->college->name ?? 'Campus Verse' }}</span>
                        </p>
                        
                        <!-- Social Glass Hub -->
                        <div class="flex items-center justify-center md:justify-start gap-3 pt-3">
                            @if(isset($socialLinks['linkedin']) && $socialLinks['linkedin'])
                            <a href="{{ $socialLinks['linkedin'] }}" target="_blank" class="w-12 h-12 rounded-xl bg-white/5 backdrop-blur-2xl border border-white/10 flex items-center justify-center text-white/50 hover:text-[#0077b5] hover:bg-white transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                            @endif
                            @if(isset($socialLinks['github']) && $socialLinks['github'])
                            <a href="{{ $socialLinks['github'] }}" target="_blank" class="w-12 h-12 rounded-xl bg-white/5 backdrop-blur-2xl border border-white/10 flex items-center justify-center text-white/50 hover:text-black hover:bg-white transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                            </a>
                            @endif
                            @if($isOwner)
                            <button type="button" @click.stop.prevent="editMode = true" class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] hover:text-primary transition-colors ml-4 border-b border-white/10 pb-1">Edit Persona</button>
                            @endif

                            <div x-data="{ copied: false }" class="ml-auto md:ml-4">
                                <button @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)" 
                                        class="flex items-center gap-2 px-4 py-2 bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-white hover:text-primary transition-all group">
                                    <span x-show="!copied">📡 Broadcast Identity</span>
                                    <span x-show="copied" x-cloak class="text-green-400 flex items-center gap-1">✅ Node Copied</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recruiter Interaction -->
                @if(!$isOwner)
                <div class="flex items-center">
                    <a href="{{ route('chat.index', $user->username) }}" class="h-16 px-10 bg-primary text-white font-black uppercase tracking-widest text-[10px] flex items-center justify-center rounded-2xl shadow-xl shadow-primary/30 hover:scale-105 active:scale-95 transition-all">
                        Connect Now
                    </a>
                </div>
                @endif
            </div>

            @if($isOwner)
            <label class="absolute top-10 right-10 glass px-8 py-3 rounded-2xl text-white text-[9px] font-black uppercase tracking-widest cursor-pointer hover:bg-white/20 transition-all border border-white/20 z-20">
                <span x-show="!coverUploading">⚡ Wall Transformation</span>
                <span x-show="coverUploading" class="animate-pulse italic">Mapping...</span>
                <input type="file" class="hidden" @change="handleCoverUpload" accept="image/*">
            </label>
            @endif
        </div>

        <!-- Metric Command Bar (Bento Metrics) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="glass p-8 rounded-[2.5rem] flex items-center gap-5 group hover:bg-white transition-all">
                <div class="w-12 h-12 rounded-2xl bg-primary/5 flex items-center justify-center text-xl group-hover:bg-primary group-hover:text-white transition-all">🏗️</div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Campus</p>
                    <p class="font-black text-slate-800 tracking-tighter">{{ $user->college->short_name ?? 'Academic' }}</p>
                </div>
            </div>
            <div class="glass p-8 rounded-[2.5rem] flex items-center gap-5 group hover:bg-white transition-all">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-xl group-hover:bg-amber-400 group-hover:text-white transition-all">🛡️</div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Cycle</p>
                    <p class="font-black text-slate-800 tracking-tighter">Year {{ $user->year ?? '1' }} • Sem {{ $user->semester }}</p>
                </div>
            </div>
            <div class="glass p-8 rounded-[2.5rem] flex items-center gap-5 group hover:bg-white transition-all">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-xl group-hover:bg-emerald-500 group-hover:text-white transition-all">🧬</div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Karma</p>
                    <p class="font-black text-slate-800 tracking-tighter">{{ number_format($user->karma) }} ARS</p>
                </div>
            </div>
            <div class="glass p-8 rounded-[2.5rem] flex items-center gap-5 group hover:bg-white transition-all">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-xl group-hover:bg-indigo-500 group-hover:text-white transition-all">📡</div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nodes</p>
                    <p class="font-black text-slate-800 tracking-tighter">{{ number_format($user->projects->count() + $user->notes->count()) }} Repos</p>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-12 gap-10">
            <!-- Left Sidebar: Meta & Visibility -->
            <div class="lg:col-span-4 space-y-10">
                <!-- Activity Vitality (Compact Heatmap) -->
                <div class="glass p-10 rounded-[3rem] shadow-glass border-white relative overflow-hidden group/bento transition-all duration-500 hover:-translate-y-2">
                    <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.3em] mb-8 border-b border-slate-50 pb-4">Contribution Pulse</h4>
                    <div class="overflow-x-auto no-scrollbar" x-data="{
                        heatmap: {{ json_encode($heatmapData) }},
                        days: Array.from({length: 119}, (_, i) => {
                            const d = new Date();
                            d.setDate(d.getDate() - (118 - i));
                            return d.toISOString().split('T')[0];
                        }),
                        getColor(date) {
                            const count = this.heatmap[date] || 0;
                            if (count === 0) return 'bg-slate-100/40';
                            if (count <= 2) return 'bg-primary/20 scale-105';
                            return 'bg-primary scale-110';
                        }
                    }">
                        <div class="grid grid-flow-col grid-rows-7 gap-1 min-w-full pb-2">
                            <template x-for="date in days" :key="date">
                                <div class="w-2.5 h-2.5 rounded-[2px] transition-all" 
                                     :class="getColor(date)"
                                     :title="date + ': ' + (heatmap[date] || 0)">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Skills Neural Hub -->
                <div class="glass p-10 rounded-[3rem] shadow-glass border-white transition-all duration-500 hover:-translate-y-2">
                    <div class="flex justify-between items-center mb-8 border-b border-slate-50 pb-4">
                        <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Neural Skills</h4>
                        @if($isOwner)
                        <button type="button" @click.stop.prevent="editMode = true" class="text-[9px] font-black text-primary uppercase">Config</button>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2.5">
                        @forelse($skills as $skill)
                        <span class="bg-white border border-slate-50 px-4 py-2.5 rounded-2xl text-[10px] font-bold text-slate-600 shadow-sm hover:border-primary/40 hover:text-primary transition-all cursor-default">
                            {{ $skill }}
                        </span>
                        @empty
                        <p class="text-[10px] font-black text-slate-300 italic">Neural map offline.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Achievements (Node Unlocks) -->
                @php $badges = $user->badges; @endphp
                @if(count($badges) > 0)
                <div class="glass p-10 rounded-[3rem] shadow-glass border-white">
                    <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-8 border-b border-slate-50 pb-4">Node Achievements</h4>
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($badges as $badge)
                        <div class="aspect-square {{ $badge['color'] }} rounded-2xl flex items-center justify-center text-2xl shadow-sm border-2 border-white hover:scale-110 transition-transform cursor-help" title="{{ $badge['name'] }}">
                            {{ $badge['icon'] }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right: Content Hub (Bulletproof Navigation) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Professional Hub Command Bar (Horizontal) -->
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 bg-slate-900/5 p-4 rounded-[2.5rem] border border-white relative z-20" data-turbo="false">
                    <button type="button" @click.stop.prevent="switchTab('projects')" :class="activeTab === 'projects' ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'text-slate-400 hover:bg-white'" class="flex-1 flex items-center justify-center gap-4 py-5 rounded-3xl transition-all group">
                        <span class="text-xl">🗂️</span>
                        <span class="text-xs font-black uppercase tracking-[0.2em]">Projects</span>
                    </button>
                    <button type="button" @click.stop.prevent="switchTab('uploads')" :class="activeTab === 'uploads' ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/20' : 'text-slate-400 hover:bg-white'" class="flex-1 flex items-center justify-center gap-4 py-5 rounded-3xl transition-all group">
                        <span class="text-xl">📄</span>
                        <span class="text-xs font-black uppercase tracking-[0.2em]">Assets</span>
                    </button>
                    <button type="button" @click.stop.prevent="switchTab('contributions')" :class="activeTab === 'contributions' ? 'bg-violet-600 text-white shadow-xl shadow-violet-600/20' : 'text-slate-400 hover:bg-white'" class="flex-1 flex items-center justify-center gap-4 py-5 rounded-3xl transition-all group">
                        <span class="text-xl">📡</span>
                        <span class="text-xs font-black uppercase tracking-[0.2em]">Social</span>
                    </button>
                    
                    @if($isOwner)
                    <a href="{{ route('projects.create') }}" class="px-8 py-5 !bg-white text-primary border border-primary/20 rounded-3xl text-[9px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all text-center">
                        + New Node
                    </a>
                    @endif
                </div>

                <!-- Hub Feed: Null-Safe & Identity Mapped -->
                <div class="relative min-h-[600px]">
                    <!-- Feed: Projects -->
                    <div x-show="activeTab === 'projects'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @forelse($user->projects as $project)
                        <div class="group bg-white border border-slate-100 rounded-[3rem] overflow-hidden hover:shadow-2xl transition-all duration-500 flex flex-col h-full">
                            <div class="aspect-[16/9] relative overflow-hidden shrink-0">
                                @if($project->cover_image_url)
                                <img src="{{ $project->cover_image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                                @else
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center text-4xl opacity-20">🛡️</div>
                                @endif
                                <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-slate-900 to-transparent">
                                    <span class="bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-xl text-[8px] font-black text-white uppercase tracking-widest border border-white/20">{{ $project->stream ?? 'Nexus' }}</span>
                                </div>
                            </div>
                            <div class="p-8 flex flex-col flex-1">
                                <h5 class="text-2xl font-black text-slate-800 tracking-tighter italic mb-3 group-hover:text-primary transition-colors leading-none">{{ $project->title }}</h5>
                                <p class="text-[11px] text-slate-500 font-bold leading-relaxed mb-6 line-clamp-2">{{ $project->description }}</p>
                                <div class="mt-auto flex items-center justify-between pt-6 border-t border-slate-50">
                                    <div class="flex -space-x-3">
                                        @foreach(($project->endorsements ?? collect())->take(3) as $end)
                                            @if($end->user)
                                            <img class="w-8 h-8 rounded-full border-2 border-white shadow-sm" src="{{ $end->user->profile_photo_url }}" title="{{ $end->user->name }}">
                                            @endif
                                        @endforeach
                                    </div>
                                    @if($project->file_url)
                                    <a href="{{ $project->file_url }}" target="_blank" class="text-[9px] font-black text-primary hover:underline uppercase tracking-widest">Explore Node →</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full py-40 text-center opacity-30 italic">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Evidence Repository empty.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Feed: Assets (Notes) -->
                    <div x-show="activeTab === 'uploads'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-6">
                        @forelse($user->notes()->with('subject')->latest()->get() as $pNote)
                        <div class="bg-white border border-slate-100 p-8 rounded-[2.5rem] hover:border-indigo-200 hover:shadow-xl transition-all flex items-center gap-8 group">
                            <div class="w-16 h-16 rounded-3xl bg-indigo-50 flex items-center justify-center text-3xl group-hover:bg-indigo-600 group-hover:text-white transition-all">📘</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <h5 class="text-xl font-black text-slate-800 truncate tracking-tight">{{ $pNote->title }}</h5>
                                    <span class="px-3 py-1 bg-slate-50 rounded-lg text-[8px] font-black text-indigo-400 tracking-widest uppercase">Verified</span>
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $pNote->subject->name ?? 'Core Domain' }}</p>
                            </div>
                            <!-- Bulletproof Slug Mapping -->
                            <a href="{{ route('notes.show', ['slug' => $pNote->slug]) }}" class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                            </a>
                        </div>
                        @empty
                        <div class="py-40 text-center opacity-30 italic">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">No verified assets found.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Feed: Social (Posts) -->
                    <div x-show="activeTab === 'contributions'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-6">
                        @forelse($user->posts()->latest()->get() as $pPost)
                        <div class="bg-white border border-slate-100 p-10 rounded-[3.5rem] hover:border-violet-300 transition-all group">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-violet-600/10 text-violet-600 flex items-center justify-center text-xs font-black shadow-sm">V</div>
                                <div>
                                    <p class="text-[9px] font-black text-violet-400 uppercase tracking-widest">Broadcast Channel Active</p>
                                    <p class="text-[8px] font-bold text-slate-300 uppercase mt-0.5 tracking-widest">{{ $pPost->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <h4 class="text-2xl font-black text-slate-800 tracking-tighter mb-4 group-hover:text-violet-600 transition-colors italic leading-none">{{ $pPost->title }}</h4>
                            <p class="text-xs text-slate-500 font-bold leading-relaxed mb-8 line-clamp-3">{{ Str::limit($pPost->content, 260) }}</p>
                            <!-- Bulletproof Community Mapping -->
                            <a href="{{ route('community.show', ['user' => $user->username, 'post' => $pPost->slug]) }}" class="text-[9px] font-black text-violet-400 hover:text-violet-600 uppercase tracking-widest flex items-center gap-3 transition-colors">Enter Node Perspective <span class="group-hover:translate-x-2 transition-transform">→</span></a>
                        </div>
                        @empty
                        <div class="py-40 text-center opacity-30 italic">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Broadcast signal offline.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sync Terminal (Edit Slide-over) -->
        <template x-if="editMode" data-turbo="false">
        <div class="fixed inset-0 z-[100] flex overflow-hidden">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-xl" @click="editMode = false"></div>
            <div class="relative w-full max-w-xl bg-white shadow-2xl p-10 overflow-y-auto no-scrollbar ml-auto animate-slide-left">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter italic">Persona Manifestation Hub</h3>
                    <button type="button" @click="editMode = false" class="text-slate-400 hover:text-slate-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-10" 
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
                    
                    <div class="space-y-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Academic Identifier</label>
                        <input type="text" name="career_role" value="{{ $user->career_role }}" class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 font-bold text-slate-800 focus:ring-4 focus:ring-primary/5 transition-all text-xs" placeholder="Research Dev, Designer, etc.">
                    </div>

                    <div class="space-y-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Neural Bio</label>
                        <textarea name="bio" rows="4" class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 font-bold text-slate-800 focus:ring-4 focus:ring-primary/5 transition-all text-xs" placeholder="Manifest your trajectory...">{{ $user->bio }}</textarea>
                    </div>

                    <div class="space-y-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Skill Matrix</label>
                        <div class="flex gap-3 mb-4">
                            <input type="text" x-model="skillInput" @keydown.enter.prevent="addSkill()" class="flex-1 bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-xs font-bold" placeholder="Add node...">
                            <button type="button" @click="addSkill()" class="bg-slate-900 text-white px-6 rounded-xl font-black text-[10px] uppercase">Deploy</button>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <template x-for="(skill, index) in skills" :key="index">
                                <span class="bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                                    <span x-text="skill"></span>
                                    <button type="button" @click="removeSkill(index)" class="text-red-400">×</button>
                                    <input type="hidden" name="skills[]" :value="skill">
                                </span>
                            </template>
                        </div>
                    </div>

                    <div class="pt-10">
                        <button type="submit" class="w-full h-20 bg-primary text-white rounded-3xl font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all text-[10px]">Synchronize Nodes</button>
                    </div>
                </form>
            </div>
        </div>
        </template>

    </div>

    <style>
        @keyframes slide-left { from { transform: translateX(100%); } to { transform: translateX(0); } }
        .animate-slide-left { animation: slide-left 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</x-dynamic-component>
