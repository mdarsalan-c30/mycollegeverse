@php $layout = Auth::user()->role === 'recruiter' ? 'recruiter' : 'app'; @endphp
<x-dynamic-component :component="$layout.'-layout'">
    <div class="space-y-10 pb-20">
        <!-- Profile Header -->
        <div class="glass p-10 rounded-[3rem] shadow-glass border-white/60 relative overflow-hidden">
             <!-- Cover Gradient -->
            <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-r from-primary to-violet-500 opacity-20"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center md:items-end gap-8 pt-12" 
                 x-data="{ 
                    uploading: false, 
                    previewUrl: '{{ $user->profile_photo_url }}',
                    handleUpload(e) {
                        const file = e.target.files[0];
                        if (!file) return;
                        
                        this.uploading = true;
                        this.previewUrl = URL.createObjectURL(file);
                        
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
                                this.previewUrl = data.url;
                            }
                        })
                        .finally(() => {
                            this.uploading = false;
                        });
                    }
                 }">
                <div class="relative group">
                    <img :src="previewUrl" class="w-40 h-40 rounded-[2.5rem] shadow-2xl border-4 border-white ring-8 ring-primary/5 group-hover:scale-105 transition-transform object-cover" :class="uploading ? 'opacity-50 grayscale' : ''"/>
                    
                    @if(Auth::id() == $user->id)
                    <label class="absolute bottom-2 right-2 w-10 h-10 bg-white rounded-xl shadow-lg flex items-center justify-center text-primary border border-slate-100 cursor-pointer hover:bg-slate-50 active:scale-90 transition-all">
                        <template x-if="!uploading">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                        </template>
                        <template x-if="uploading">
                            <svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <input type="file" class="hidden" @change="handleUpload" accept="image/*">
                    </label>
                    @endif
                </div>
                
                <div class="flex-1 text-center md:text-left space-y-2">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                        <h2 class="text-4xl font-black text-secondary">{{ $user->name }}</h2>
                        <span class="bg-primary/10 text-primary px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest border border-primary/20">Elite Contributor</span>
                    </div>
                    <p class="text-slate-500 font-bold max-w-lg">{{ $user->college->name ?? 'Campus Node curator' }} • Passionate about Academic Excellence.</p>
                    <div class="flex items-center justify-center md:justify-start gap-6 pt-4">
                        <div>
                            <p class="text-xl font-black text-slate-800">{{ $user->followers_count ?? 0 }}</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Followers</p>
                        </div>
                        <div>
                            <p class="text-xl font-black text-slate-800">{{ number_format($user->notes()->sum('downloads')) }}</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Downloads</p>
                        </div>
                        <div>
                            <p class="text-xl font-black text-slate-800">{{ number_format($user->karma) }}</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Points</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    @if(Auth::id() == $user->id)
                    <button class="bg-primary text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-all">Edit Profile</button>
                    @else
                    <a href="{{ route('chat.index', $user->username) }}" class="bg-primary text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-all flex items-center gap-2 text-sm">
                        <span>Message</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                    </a>
                    <button class="glass px-8 py-3.5 rounded-2xl text-slate-600 font-bold hover:bg-slate-50 transition-all text-sm">Follow</button>
                    @endif
                    <button class="glass p-3.5 rounded-2xl text-slate-400 hover:text-primary transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg></button>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-10" x-data="{ activeTab: 'uploads' }">
            <!-- Left Info -->
            <div class="space-y-8">
                <div class="glass p-8 rounded-[2.5rem] shadow-sm border-white/50">
                    <h4 class="text-lg font-black text-secondary mb-6">Academic Identity</h4>
                    <div class="space-y-6">
                        <div class="flex gap-4 items-center">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-lg">🏢</div>
                            <div><p class="text-[10px] font-black text-slate-400 uppercase">College</p><p class="text-sm font-bold text-slate-700">{{ $user->college->name ?? 'Not Assigned' }}</p></div>
                        </div>
                        <div class="flex gap-4 items-center">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-lg">🎓</div>
                            <div><p class="text-[10px] font-black text-slate-400 uppercase">Year/Status</p><p class="text-sm font-bold text-slate-700">{{ $user->year ?? 'Undergraduate' }}</p></div>
                        </div>
                        <div class="flex gap-4 items-center">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-lg">📧</div>
                            <div><p class="text-[10px] font-black text-slate-400 uppercase">Network Email</p><p class="text-sm font-bold text-slate-700">{{ $user->college_email ?? $user->email }}</p></div>
                        </div>
                    </div>
                </div>

                <div class="glass p-8 rounded-[2.5rem] shadow-sm border-white/50">
                    <h4 class="text-lg font-black text-secondary mb-6">Achievements</h4>
                    <div class="grid grid-cols-3 gap-4">
                         @forelse($user->badges as $badge)
                            <div class="aspect-square {{ $badge['color'] }} rounded-2xl flex items-center justify-center text-3xl shadow-lg ring-1 ring-inset border-white/40" title="{{ $badge['name'] }}">
                                {{ $badge['icon'] }}
                            </div>
                         @empty
                            <div class="col-span-3 py-6 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-100">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Novice Explorer</p>
                            </div>
                         @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Activity Feed -->
            <div class="lg:col-span-2 space-y-8">
                 <div class="flex gap-8 border-b border-slate-100 pb-2 overflow-x-auto no-scrollbar whitespace-nowrap">
                    <button @click="activeTab = 'uploads'" :class="activeTab === 'uploads' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-2 pb-4 transition-all">My Uploads</button>
                    <button @click="activeTab = 'projects'" :class="activeTab === 'projects' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-2 pb-4 transition-all">🗂️ Proof of Work</button>
                    <button @click="activeTab = 'saved'" :class="activeTab === 'saved' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-2 pb-4 transition-all">Saved Notes</button>
                    <button @click="activeTab = 'contributions'" :class="activeTab === 'contributions' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-2 pb-4 transition-all">Contributions</button>
                </div>

                <!-- Tab content: Proof of Work (PoW) -->
                <div x-show="activeTab === 'projects'" x-transition class="space-y-8">
                    <div class="flex justify-between items-center">
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Evidence of Talent Showcase</h4>
                        @if(Auth::id() == $user->id)
                        <a href="{{ route('projects.create') }}" class="text-xs font-black text-primary hover:underline uppercase tracking-widest">+ Manifest New Talent</a>
                        @endif
                    </div>

                    <div class="grid md:grid-cols-2 gap-8">
                        @php
                            $projects = collect();
                            try {
                                if (method_exists($user, 'projects')) {
                                    $projects = $user->projects()->with('endorsements.user')->latest()->get();
                                }
                            } catch (\Exception $e) {
                                \Log::error("PoW Render Exception: " . $e->getMessage());
                                $projects = collect();
                            }
                        @endphp

                        @forelse($projects as $project)
                        <div class="group relative bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden hover:shadow-2xl transition-all hover:-translate-y-2">
                            <!-- Premium Cover Image -->
                            <div class="aspect-[16/10] overflow-hidden relative">
                                <img src="{{ $project->cover_image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"/>
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                                <div class="absolute bottom-6 left-8">
                                    <span class="bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border border-white/20">
                                        {{ $project->icon }} {{ $project->stream }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-8 space-y-3">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-xl font-black text-slate-900 group-hover:text-primary transition-colors">{{ $project->title }}</h4>
                                    <div class="flex items-center gap-1.5 text-amber-500">
                                        <span class="text-xs font-black underline">{{ $project->visibility_score }}</span>
                                        <span class="text-[10px] font-black uppercase tracking-tighter">Signal</span>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 font-bold leading-relaxed line-clamp-2">{{ $project->description }}</p>
                                
                                <div class="pt-4 flex items-center justify-between border-t border-slate-50">
                                    <div class="flex -space-x-2 overflow-hidden">
                                        @foreach($project->endorsements->take(3) as $end)
                                            <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white" src="{{ $end->user->profile_photo_url }}" title="{{ $end->user->name }}">
                                        @endforeach
                                        @if($project->endorsements->count() > 3)
                                            <span class="flex items-center justify-center h-6 w-6 rounded-full bg-slate-100 text-[8px] font-black text-slate-400 ring-2 ring-white">+{{ $project->endorsements->count() - 3 }}</span>
                                        @endif
                                    </div>
                                    <div class="flex gap-4">
                                        <a href="{{ $project->file_url }}" target="_blank" class="text-[9px] font-black text-slate-400 hover:text-primary uppercase tracking-widest transition-colors tracking-tighter">Explore Artifact →</a>
                                        @if(Auth::id() !== $user->id)
                                        <form action="{{ route('projects.endorse', $project->id) }}" method="POST">
                                            @csrf
                                            <button class="text-[9px] font-black text-primary hover:underline uppercase tracking-widest">Endorse 🤝</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="md:col-span-2 py-24 text-center glass rounded-[3rem] border border-dashed border-slate-200">
                            <div class="text-4xl opacity-20 mb-4">🗂️</div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest italic">The Talent Showcase is empty. <br>Manifest your proof of work and lead the Verse.</p>
                            @if(Auth::id() == $user->id)
                            <a href="{{ route('projects.create') }}" class="mt-6 inline-block bg-primary text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-all">Begin Manifestation</a>
                            @endif
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tab content: My Uploads -->
                <div x-show="activeTab === 'uploads'" x-transition class="grid md:grid-cols-2 gap-6">
                    @forelse($user->notes()->with('subject')->latest()->get() as $pNote)
                    <div class="glass p-6 rounded-[2.5rem] shadow-glass border-white hover:shadow-xl transition-all group">
                         <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                📄
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Uploaded {{ $pNote->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-800 mb-1 truncate">{{ $pNote->title }}</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">{{ $pNote->subject->name ?? 'General' }}</p>
                        <div class="flex items-center gap-4 text-xs font-bold text-slate-500">
                            <span class="flex items-center gap-1">📥 {{ $pNote->downloads }}</span>
                            <span class="flex items-center gap-1">⭐ 5.0</span>
                        </div>
                    </div>
                    @empty
                    <div class="md:col-span-2 text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-100">
                        <p class="text-slate-400 font-black uppercase tracking-widest text-xs italic">No knowledge assets shared yet.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Tab content: Saved Notes -->
                <div x-show="activeTab === 'saved'" x-transition class="space-y-6">
                    @php
                        $savedNotes = collect();
                        try {
                            if (method_exists($user, 'savedNotes')) {
                                $savedNotes = $user->savedNotes()->with(['user', 'subject'])->latest('saved_notes.created_at')->get();
                            }
                        } catch (\Throwable $e) {
                            \Log::error("Saved Notes Render Exception: " . $e->getMessage());
                            $savedNotes = collect();
                        }
                    @endphp

                    <div class="grid md:grid-cols-2 gap-6">
                        @forelse($savedNotes as $sNote)
                        <div class="glass p-6 rounded-[2.5rem] shadow-glass border-white hover:shadow-xl transition-all group">
                             <div class="flex justify-between items-start mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                    🔖
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter block">Saved Archive</span>
                                    <span class="text-[8px] font-bold text-slate-300 uppercase tracking-widest italic">By {{ $sNote->user->name ?? 'Curator' }}</span>
                                </div>
                            </div>
                            <h4 class="text-lg font-extrabold text-slate-800 mb-1 truncate">{{ $sNote->title }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">{{ $sNote->subject->name ?? 'General' }}</p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4 text-xs font-bold text-slate-500">
                                    <span class="flex items-center gap-1">📥 {{ $sNote->downloads }}</span>
                                </div>
                                <a href="{{ route('notes.show', $sNote->slug) }}" class="text-[10px] font-black text-primary hover:underline uppercase tracking-widest">Access Vault →</a>
                            </div>
                        </div>
                        @empty
                        <div class="md:col-span-2 text-center py-24 glass rounded-[3rem] border border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">🔖</div>
                            <h5 class="text-xs font-black text-slate-400 uppercase tracking-widest">Archive Empty</h5>
                            <p class="text-[10px] text-slate-300 font-bold uppercase tracking-widest mt-2 italic">Save high-quality notes to manifest them in your personal vault.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tab content: Contributions -->
                <div x-show="activeTab === 'contributions'" x-transition class="space-y-6">
                    @forelse($user->posts()->latest()->get() as $pPost)
                    <div class="glass p-8 rounded-[2.5rem] shadow-sm border-white">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-sm">💬</div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Campus Broadcast • {{ $pPost->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="text-xl font-black text-slate-800 mb-3">{{ $pPost->title }}</h4>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed">{{ Str::limit($pPost->content, 150) }}</p>
                    </div>
                    @empty
                    <div class="text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-100">
                        <p class="text-slate-400 font-black uppercase tracking-widest text-xs italic">Silence in the verse... no posts recorded.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
