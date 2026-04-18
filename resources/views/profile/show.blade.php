@php 
    $layout = (Auth::check() && Auth::user()->role === 'recruiter') ? 'recruiter' : 'app'; 
    $isOwner = Auth::id() === $user->id;
    $socialLinks = $user->social_links ?? [];
    $skills = $user->skills ?? [];
@endphp

<x-dynamic-component :component="$layout.'-layout'">
    <div class="space-y-12 pb-24" x-data="{ 
        editMode: false,
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

        <!-- Premium Hero Section (Behance Style) -->
        <div class="relative group">
            <!-- Cover Photo Container -->
            <div class="h-64 md:h-96 w-full rounded-[3rem] overflow-hidden relative shadow-2xl bg-slate-900">
                <img :src="coverPreview" class="w-full h-full object-cover opacity-60 transition-transform duration-1000 group-hover:scale-105" />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-90"></div>
                
                @if($isOwner)
                <label class="absolute top-6 right-6 glass px-6 py-2.5 rounded-2xl text-white text-xs font-black uppercase tracking-widest cursor-pointer hover:bg-white/20 transition-all flex items-center gap-2">
                    <template x-if="!coverUploading">
                        <span class="flex items-center gap-2">🖼️ Change Cover</span>
                    </template>
                    <template x-if="coverUploading">
                        <span class="animate-pulse">Syncing...</span>
                    </template>
                    <input type="file" class="hidden" @change="handleCoverUpload" accept="image/*">
                </label>
                @endif
            </div>

            <!-- Profile Info Overlay -->
            <div class="absolute -bottom-12 left-1/2 -translate-x-1/2 w-full max-w-5xl px-6">
                <div class="glass p-8 rounded-[3rem] shadow-glass border-white/40 flex flex-col md:flex-row items-center md:items-end gap-8">
                    <!-- Avatar Area -->
                    <div class="relative -mt-24 md:-mt-32">
                        <div class="w-40 h-40 md:w-48 md:h-48 rounded-[3.5rem] border-8 border-white shadow-2xl overflow-hidden bg-white ring-12 ring-primary/5">
                            <img :src="avatarPreview" class="w-full h-full object-cover" :class="avatarUploading ? 'opacity-50 blur-sm' : ''" />
                        </div>
                        @if($isOwner)
                        <label class="absolute bottom-4 right-4 w-10 h-10 bg-primary text-white rounded-2xl shadow-lg border-2 border-white flex items-center justify-center cursor-pointer hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                            <input type="file" class="hidden" @change="handleAvatarUpload" accept="image/*">
                        </label>
                        @endif
                    </div>

                    <!-- Identity Text -->
                    <div class="flex-1 text-center md:text-left space-y-2 pb-2">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                            <h2 class="text-3xl md:text-5xl font-black text-white drop-shadow-2xl tracking-tighter">{{ $user->name }}</h2>
                            @if($user->karma >= 1000)
                            <span class="bg-amber-100/90 text-amber-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-amber-200">Tier-1 Signal</span>
                            @endif
                        </div>
                        <p class="text-slate-100 font-extrabold text-lg drop-shadow-md max-w-lg">{{ $user->career_role ?? 'Academic Curator' }} • {{ $user->college->name ?? 'Campus Node' }}</p>
                        
                        <!-- Social Connectors -->
                        <div class="flex items-center justify-center md:justify-start gap-5 pt-3">
                            @if(isset($socialLinks['linkedin']))
                            <a href="{{ $socialLinks['linkedin'] }}" target="_blank" class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur-md text-white flex items-center justify-center hover:bg-[#0077b5] hover:scale-110 transition-all border border-white/20">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                            @endif
                            @if(isset($socialLinks['github']))
                            <a href="{{ $socialLinks['github'] }}" target="_blank" class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur-md text-white flex items-center justify-center hover:bg-slate-900 hover:scale-110 transition-all border border-white/20">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                            </a>
                            @endif
                            @if(isset($socialLinks['behance']))
                            <a href="{{ $socialLinks['behance'] }}" target="_blank" class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur-md text-white flex items-center justify-center hover:bg-[#1769ff] hover:scale-110 transition-all border border-white/20">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 7h-7v-2h7v2zm-11.5 2.5c-1.4 0-2.5 1.1-2.5 2.5s1.1 2.5 2.5 2.5 2.5-1.1 2.5-2.5-1.1-2.5-2.5-2.5zm-4.5 4.5c0 1.4-1.1 2.5-2.5 2.5s-2.5-1.1-2.5-2.5 1.1-2.5 2.5-2.5 2.5 1.1 2.5 2.5zm0-4c0 1.4-1.1 2.5-2.5 2.5s-2.5-1.1-2.5-2.5 1.1-2.5 2.5-2.5 2.5 1.1 2.5 2.5zm13.5 4c0 1.4-1.1 2.5-2.5 2.5s-2.5-1.1-2.5-2.5 1.1-2.5 2.5-2.5 2.5 1.1 2.5 2.5z"/></svg>
                            </a>
                            @endif
                            @if($isOwner)
                            <button @click="editMode = true" class="text-[10px] font-black text-white/80 uppercase tracking-widest hover:text-white hover:underline ml-2 transition-colors">+ Connect socials</button>
                            @endif
                        </div>
                    </div>

                    <!-- Action Area -->
                    <div class="flex gap-4 md:pb-4">
                        @if($isOwner)
                        <button @click="editMode = true" class="bg-primary text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-xl shadow-primary/20 hover:scale-105 transition-all text-sm border-2 border-white/20">Professional Sync</button>
                        @else
                        <a href="{{ route('chat.index', $user->username) }}" class="bg-primary text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-xl shadow-primary/20 hover:scale-105 transition-all text-sm flex items-center gap-2 border-2 border-white/20">
                            <span>Connect</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                        </a>
                        <button class="glass px-8 py-4 rounded-[1.5rem] text-slate-600 font-bold hover:bg-white transition-all text-sm">Follow</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Content Grid -->
        <div class="pt-20 grid lg:grid-cols-3 gap-10" x-data="{ activeTab: 'projects' }">
            <!-- Left: Professional Identity -->
            <div class="space-y-8">
                <!-- Activity Heatmap (GitHub Style) -->
                <div class="glass p-8 rounded-[2.5rem] shadow-glass border-white/60">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Activity Pulse</h4>
                        <span class="text-[10px] font-bold text-slate-400 uppercase italic">Last 365 Days</span>
                    </div>

                    <div class="space-y-4" x-data="{
                        heatmap: {{ json_encode($heatmapData) }},
                        days: Array.from({length: 365}, (_, i) => {
                            const d = new Date();
                            d.setDate(d.getDate() - (364 - i));
                            return d.toISOString().split('T')[0];
                        }),
                        getColor(date) {
                            const count = this.heatmap[date] || 0;
                            if (count === 0) return 'bg-slate-100/50';
                            if (count <= 2) return 'bg-primary/20';
                            if (count <= 5) return 'bg-primary/50';
                            return 'bg-primary';
                        }
                    }">
                        <!-- The Grid Canvas -->
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="date in days" :key="date">
                                <div class="w-2.5 h-2.5 rounded-sm transition-all hover:scale-150 cursor-pointer" 
                                     :class="getColor(date)"
                                     :title="date + ': ' + (heatmap[date] || 0) + ' signals manifested'">
                                </div>
                            </template>
                        </div>

                        <!-- Legend -->
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Signal Intensity:</span>
                            <div class="flex gap-1">
                                <div class="w-2.5 h-2.5 rounded-sm bg-slate-100"></div>
                                <div class="w-2.5 h-2.5 rounded-sm bg-primary/20"></div>
                                <div class="w-2.5 h-2.5 rounded-sm bg-primary/50"></div>
                                <div class="w-2.5 h-2.5 rounded-sm bg-primary font-light"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Credentials -->
                <div class="glass p-8 rounded-[2.5rem] shadow-glass border-white/60">
                    <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-8 border-b border-slate-100 pb-4">Academic Identity</h4>
                    <div class="space-y-8">
                        <div class="flex gap-5 items-start">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">🏛️</div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Institution</p>
                                <p class="text-sm font-extrabold text-slate-700 leading-tight">{{ $user->college->name ?? 'Campus Curator' }}</p>
                            </div>
                        </div>
                        <div class="flex gap-5 items-start">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">📜</div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status / Semester</p>
                                <p class="text-sm font-extrabold text-slate-700 leading-tight">{{ $user->year ?? 'Undergrad' }} Year • Sem {{ $user->semester ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($user->career_role)
                        <div class="flex gap-5 items-start">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">🎯</div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Specialization</p>
                                <p class="text-sm font-extrabold text-slate-700 leading-tight">{{ $user->career_role }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Skills Matrix (Tag-based design) -->
                <div class="glass p-8 rounded-[2.5rem] shadow-glass border-white/60">
                    <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-4">
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Skills Matrix</h4>
                        @if($isOwner)
                        <button @click="editMode = true" class="text-[10px] font-black text-primary hover:underline uppercase tracking-widest">Update</button>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @forelse($skills as $skill)
                        <span class="bg-slate-50 border border-slate-100 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-primary/5 hover:border-primary/20 transition-all cursor-default">
                            {{ $skill }}
                        </span>
                        @empty
                        <div class="w-full py-8 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Matrix unmapped...</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Achievements & Badges -->
                <div class="glass p-8 rounded-[2.5rem] shadow-glass border-white/60">
                    <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-8 border-b border-slate-100 pb-4">Academic Signaling</h4>
                    <div class="grid grid-cols-4 gap-4">
                         @forelse($user->badges as $badge)
                            <div class="aspect-square {{ $badge['color'] }} rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-white/20 transform hover:scale-110 transition-transform" title="{{ $badge['name'] }}">
                                {{ $badge['icon'] }}
                            </div>
                         @empty
                            <div class="col-span-4 py-8 text-center glass rounded-2xl">
                                <p class="text-[10px] font-black text-slate-300 uppercase italic">Manifesting legacy...</p>
                            </div>
                         @endforelse
                    </div>
                </div>
            </div>

            <!-- Right: Professional Activity & Proof of Work -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Navigation Tabs (Professional Context) -->
                <div class="flex gap-8 border-b border-slate-100 pb-0 overflow-x-auto no-scrollbar whitespace-nowrap">
                    <button @click="activeTab = 'projects'" :class="activeTab === 'projects' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-2 py-4 transition-all text-xs uppercase tracking-widest flex items-center gap-2">
                        <span>🗂️ Proof of Work</span>
                        @if($user->projects()->count() > 0)
                        <span class="bg-primary/5 px-2 py-0.5 rounded-full text-[8px]">{{ $user->projects()->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'uploads'" :class="activeTab === 'uploads' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-2 py-4 transition-all text-xs uppercase tracking-widest">My Uploads</button>
                    @if($isOwner)
                    <button @click="activeTab = 'saved'" :class="activeTab === 'saved' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-2 py-4 transition-all text-xs uppercase tracking-widest">Archived Notes</button>
                    @endif
                    <button @click="activeTab = 'contributions'" :class="activeTab === 'contributions' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-2 py-4 transition-all text-xs uppercase tracking-widest">Contributions</button>
                </div>

                <!-- Tab content: Proof of Work (Focus Layer) -->
                <div x-show="activeTab === 'projects'" x-transition class="space-y-8">
                    @forelse($user->projects()->with('endorsements.user')->latest()->get() as $project)
                    <div class="group grid md:grid-cols-5 gap-0 bg-white rounded-[3rem] shadow-glass border border-slate-100 overflow-hidden hover:shadow-2xl transition-all duration-500">
                        <div class="md:col-span-2 aspect-[16/10] md:h-full relative overflow-hidden">
                            <img src="{{ $project->cover_image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"/>
                            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/60 via-transparent to-transparent"></div>
                            <div class="absolute top-6 left-6">
                                <span class="bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest px-4 py-2 rounded-full border border-white/20">
                                    {{ $project->icon }} {{ $project->stream }}
                                </span>
                            </div>
                        </div>
                        <div class="md:col-span-3 p-10 space-y-6 flex flex-col justify-center">
                            <div class="flex justify-between items-start">
                                <h4 class="text-2xl font-black text-slate-900 group-hover:text-primary transition-colors leading-tight">{{ $project->title }}</h4>
                                <div class="flex items-center gap-2 px-3 py-1 bg-amber-50 text-amber-600 rounded-full border border-amber-100">
                                    <span class="text-[10px] font-black underline">{{ $project->visibility_score }}</span>
                                    <span class="text-[8px] font-black uppercase tracking-widest">Signal</span>
                                </div>
                            </div>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed line-clamp-3">
                                {{ $project->description }}
                            </p>
                            <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                                <div class="flex -space-x-3">
                                    @foreach($project->endorsements->take(4) as $end)
                                    <img class="w-8 h-8 rounded-full border-2 border-white ring-4 ring-slate-50/50" src="{{ $end->user->profile_photo_url }}" title="{{ $end->user->name }}">
                                    @endforeach
                                    @if($project->endorsements->count() > 4)
                                    <span class="w-8 h-8 rounded-full bg-slate-100 text-[8px] font-black text-slate-400 flex items-center justify-center border-2 border-white">+{{ $project->endorsements->count() - 4 }}</span>
                                    @endif
                                </div>
                                <div class="flex gap-6">
                                    <a href="{{ $project->file_url }}" target="_blank" class="text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-widest flex items-center gap-2">
                                        Exploration View
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-24 text-center glass rounded-[3rem] border border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-4xl mx-auto mb-6 opacity-30 italic">🗂️</div>
                        <h5 class="text-lg font-black text-slate-400 uppercase tracking-widest">Showcase Empty</h5>
                        <p class="text-xs text-slate-300 font-bold uppercase tracking-widest mt-2 max-w-sm mx-auto leading-relaxed">The talent nodes are unmapped. Manifest your evidence of work to lead the verse.</p>
                        @if($isOwner)
                        <a href="{{ route('projects.create') }}" class="mt-8 inline-block bg-primary text-white px-10 py-4 rounded-2xl font-bold shadow-2xl shadow-primary/20 hover:scale-105 transition-all">Begin Manifestation</a>
                        @endif
                    </div>
                    @endforelse
                </div>

                <!-- Tab content: My Uploads -->
                <div x-show="activeTab === 'uploads'" x-transition class="grid md:grid-cols-2 gap-8">
                    @forelse($user->notes()->with('subject')->latest()->get() as $pNote)
                    <div class="glass p-8 rounded-[2.5rem] shadow-glass border-white hover:shadow-2xl transition-all group">
                        <div class="flex justify-between items-start mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-primary/5 text-primary flex items-center justify-center text-2xl group-hover:bg-primary group-hover:text-white transition-all duration-500">
                                📄
                            </div>
                            <span class="px-4 py-1.5 bg-slate-50 rounded-full text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $pNote->created_at->format('M Y') }}</span>
                        </div>
                        <h4 class="text-xl font-black text-slate-800 mb-2 truncate group-hover:text-primary transition-colors">{{ $pNote->title }}</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                             <span class="w-2 h-2 rounded-full bg-primary/40"></span>
                             {{ $pNote->subject->name ?? 'Core Academic Node' }}
                        </p>
                        <div class="flex items-center gap-6 pt-6 border-t border-slate-50 text-[10px] font-black text-slate-400">
                            <span class="flex items-center gap-1.5 underline">📥 {{ $pNote->downloads }} Downloads</span>
                            <span class="flex items-center gap-1.5">⭐ 5.0 Rating</span>
                        </div>
                    </div>
                    @empty
                    <div class="md:col-span-2 text-center py-24 glass rounded-[3rem] border-2 border-dashed border-slate-100 italic">
                        <p class="text-slate-400 font-black uppercase tracking-widest text-xs">No knowledge assets detected in this node.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Tab content: Contributions -->
                <div x-show="activeTab === 'contributions'" x-transition class="space-y-8">
                    @forelse($user->posts()->latest()->get() as $pPost)
                    <div class="glass p-10 rounded-[3rem] shadow-glass border-white hover:bg-white/40 transition-all">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center text-lg shrink-0">💬</div>
                            <div>
                                <p class="text-[10px] font-black text-primary uppercase tracking-widest">Campus Broadcast</p>
                                <p class="text-[10px] font-bold text-slate-300 uppercase italic">{{ $pPost->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <h4 class="text-2xl font-black text-slate-800 mb-4 tracking-tight leading-tight">{{ $pPost->title }}</h4>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">{{ Str::limit($pPost->content, 200) }}</p>
                        <a href="{{ route('community.show', [$user->username, $pPost->slug]) }}" class="text-[10px] font-black text-primary hover:underline uppercase tracking-widest">Read Full Entry →</a>
                    </div>
                    @empty
                    <div class="text-center py-24 glass rounded-[3rem] border border-dashed border-slate-200">
                        <p class="text-slate-400 font-black uppercase tracking-widest text-xs italic">Silence in the verse... no signal emitted.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Tab content: Saved Notes (OWNER ONLY) -->
                @if($isOwner)
                <div x-show="activeTab === 'saved'" x-transition class="grid md:grid-cols-2 gap-8">
                    @php
                        $savedNotes = collect();
                        try {
                            if (method_exists($user, 'savedNotes')) {
                                $savedNotes = $user->savedNotes()->with(['user', 'subject'])->latest('saved_notes.created_at')->get();
                            }
                        } catch (\Throwable $e) { $savedNotes = collect(); }
                    @endphp
                    @forelse($savedNotes as $sNote)
                    <div class="glass p-8 rounded-[2.5rem] shadow-glass border-white hover:shadow-2xl transition-all group">
                         <div class="flex justify-between items-start mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl">🔖</div>
                            <div class="text-right">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter block">Vaulted Asset</span>
                                <span class="text-[8px] font-bold text-slate-300 uppercase tracking-widest italic">Curant: {{ $sNote->user->name ?? 'MCV' }}</span>
                            </div>
                        </div>
                        <h4 class="text-xl font-black text-slate-800 mb-2 truncate">{{ $sNote->title }}</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8">{{ $sNote->subject->name ?? 'General Knowledge' }}</p>
                        <a href="{{ route('notes.show', $sNote->slug) }}" class="text-[10px] font-black text-primary hover:underline uppercase tracking-widest">Access Archive →</a>
                    </div>
                    @empty
                    <div class="md:col-span-2 text-center py-24 glass rounded-[3rem] border border-dashed border-slate-200">
                        <p class="text-[10px] text-slate-300 font-bold uppercase tracking-widest italic">Personal archive empty. Bookmark excellence to manifest it here.</p>
                    </div>
                    @endforelse
                </div>
                @endif
            </div>
        </div>

        <!-- Professional Sync Modal (Slide-over Edit) -->
        <template x-if="editMode">
        <div class="fixed inset-0 z-[100] flex overflow-hidden">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="editMode = false"></div>
            <div class="relative w-full max-w-xl bg-white shadow-2xl p-10 overflow-y-auto no-scrollbar ml-auto">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-2xl font-black text-secondary uppercase tracking-tighter italic">Professional Sync Hub 🛡️</h3>
                    <button @click="editMode = false" class="text-slate-400 hover:text-slate-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-8" 
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
                    
                    <!-- Career Identity Section -->
                    <div class="space-y-6">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-l-4 border-primary pl-4">Career Persona</h4>
                        <div class="grid gap-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Professional Role (e.g. Fintech Analyst, UI Designer)</label>
                                <input type="text" name="career_role" value="{{ $user->career_role }}" class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 font-bold text-slate-700 focus:ring-primary focus:border-primary" placeholder="Envisioning the future...">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">Executive Summary (Bio)</label>
                                <textarea name="bio" rows="4" class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 font-bold text-slate-700 focus:ring-primary focus:border-primary" placeholder="Narrate your academic journey...">{{ $user->bio }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Skills Matrix Section -->
                    <div class="space-y-6">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-l-4 border-amber-400 pl-4">Skills Matrix</h4>
                        <div>
                            <div class="flex gap-2 mb-4">
                                <input type="text" x-model="skillInput" @keydown.enter.prevent="addSkill()" class="flex-1 bg-slate-50 border-slate-100 rounded-xl px-4 py-3 text-xs font-bold" placeholder="Type a skill and hit Enter...">
                                <button type="button" @click="addSkill()" class="bg-primary text-white px-6 rounded-xl font-black text-[10px] uppercase">Add</button>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="(skill, index) in skills" :key="index">
                                    <div class="bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-lg text-[9px] font-black text-slate-600 flex items-center gap-2">
                                        <span x-text="skill"></span>
                                        <button type="button" @click="removeSkill(index)" class="text-slate-400 hover:text-red-500 font-black">×</button>
                                        <input type="hidden" name="skills[]" :value="skill">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Social Nexus Section -->
                    <div class="space-y-6">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-l-4 border-indigo-400 pl-4">Social Nexus Connectivity</h4>
                        <div class="grid gap-4">
                            <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <div class="w-8 h-8 rounded-lg bg-[#0077b5] text-white flex items-center justify-center shrink-0">L</div>
                                <input type="url" name="social_links[linkedin]" value="{{ $socialLinks['linkedin'] ?? '' }}" class="flex-1 bg-transparent border-none focus:ring-0 text-xs font-bold" placeholder="LinkedIn Profile URL">
                            </div>
                            <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <div class="w-8 h-8 rounded-lg bg-[#333] text-white flex items-center justify-center shrink-0">G</div>
                                <input type="url" name="social_links[github]" value="{{ $socialLinks['github'] ?? '' }}" class="flex-1 bg-transparent border-none focus:ring-0 text-xs font-bold" placeholder="GitHub Profile URL">
                            </div>
                            <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <div class="w-8 h-8 rounded-lg bg-[#1769ff] text-white flex items-center justify-center shrink-0">B</div>
                                <input type="url" name="social_links[behance]" value="{{ $socialLinks['behance'] ?? '' }}" class="flex-1 bg-transparent border-none focus:ring-0 text-xs font-bold" placeholder="Behance Profile URL">
                            </div>
                        </div>
                    </div>

                    <div class="pt-10">
                        <button type="submit" class="w-full bg-secondary text-white py-5 rounded-[1.5rem] font-black uppercase tracking-widest shadow-2xl hover:scale-[1.02] transition-all">Synchronize Portfolio State</button>
                    </div>
                </form>
            </div>
        </div>
        </template>

    </div>
</x-dynamic-component>
