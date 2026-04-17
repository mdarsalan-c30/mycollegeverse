<x-dynamic-component :component="$layout.'-layout'">
    <div class="space-y-10 pb-20" x-data="{ activeTab: 'portfolio' }">
        <!-- Profile Header -->
        <div class="glass p-10 rounded-[3rem] shadow-glass border-white/60 relative overflow-hidden">
             <!-- Cover Gradient -->
            <div class="absolute top-0 left-0 w-full h-40 bg-gradient-to-r from-primary/20 via-violet-500/10 to-transparent"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center md:items-end gap-10 pt-16" 
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
                    <img :src="previewUrl" class="w-48 h-48 rounded-[3.5rem] shadow-2xl border-4 border-white ring-8 ring-primary/5 group-hover:scale-105 transition-transform object-cover" :class="uploading ? 'opacity-50 grayscale' : ''"/>
                    
                    @if(Auth::id() == $user->id)
                    <label class="absolute bottom-4 right-4 w-12 h-12 bg-white rounded-2xl shadow-xl flex items-center justify-center text-primary border border-slate-100 cursor-pointer hover:bg-slate-50 active:scale-90 transition-all border-2 border-primary/10">
                        <template x-if="!uploading">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                        </template>
                        <template x-if="uploading">
                            <svg class="animate-spin h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <input type="file" class="hidden" @change="handleUpload" accept="image/*">
                    </label>
                    @endif
                </div>
                
                <div class="flex-1 text-center md:text-left space-y-3">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                        <h2 class="text-5xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h2>
                        @if($user->ars_score >= 80)
                            <span class="bg-amber-100 text-amber-600 px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-amber-200">Elite Talent Tier</span>
                        @else
                            <span class="bg-primary/5 text-primary px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-primary/10">Academic Explorer</span>
                        @endif
                    </div>
                    <p class="text-slate-500 font-bold text-lg">{{ $user->career_role ?? 'Academic Identity in formation' }}</p>
                    <div class="flex items-center justify-center md:justify-start gap-10 pt-6">
                        <div class="text-center group cursor-help">
                            <p class="text-3xl font-black text-slate-900 group-hover:text-primary transition-colors">{{ number_format($user->karma) }}</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Reputation</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-black text-slate-900">{{ $user->ars_score }}<span class="text-sm">/100</span></p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">AR Visibility</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-black text-slate-900">{{ $projects->where('is_official', true)->count() }}</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Verified PoW</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    @if(Auth::id() == $user->id)
                    <button class="bg-slate-900 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-slate-900/20 hover:scale-105 transition-all">Workspace →</button>
                    @else
                    <a href="{{ route('chat.index', $user->username) }}" class="bg-primary text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-primary/30 hover:scale-105 transition-all flex items-center gap-3">
                        <span>Signal Message</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-12">
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
                 <div class="flex gap-10 border-b border-slate-100 pb-2 overflow-x-auto no-scrollbar whitespace-nowrap">
                    <button @click="activeTab = 'portfolio'" :class="activeTab === 'portfolio' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-4 pb-4 transition-all text-xs uppercase tracking-widest">Proof of Work</button>
                    <button @click="activeTab = 'resume'" :class="activeTab === 'resume' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-4 pb-4 transition-all text-xs uppercase tracking-widest">Academic Record</button>
                    <button @click="activeTab = 'uploads'" :class="activeTab === 'uploads' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-4 pb-4 transition-all text-xs uppercase tracking-widest">Knowledge Shared</button>
                    <button @click="activeTab = 'contributions'" :class="activeTab === 'contributions' ? 'text-primary border-primary' : 'text-slate-400 border-transparent'" class="font-black border-b-4 pb-4 transition-all text-xs uppercase tracking-widest">Broadcasts</button>
                </div>

                <!-- Tab content: Portfolio (Proof of Work) -->
                <div x-show="activeTab === 'portfolio'" x-transition class="space-y-8">
                    @if(Auth::id() == $user->id)
                    <div class="flex justify-between items-center bg-slate-50/50 p-6 rounded-[2rem] border border-dashed border-slate-200">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Manifest an Artifact to your Verse Profile</p>
                        <button onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'manifest-artifact' }))" class="bg-white text-primary border border-primary/20 px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary hover:text-white transition-all shadow-sm">
                            + Manifest Artifact
                        </button>
                    </div>
                    @endif

                    <div class="grid md:grid-cols-2 gap-8">
                        @forelse($projects as $project)
                        <div class="glass group relative overflow-hidden rounded-[2.5rem] border-white/60 hover:shadow-2xl transition-all duration-500">
                            @if($project->cover_image_path)
                                <img src="{{ app(\App\Services\ImageKitService::class)->getUrl($project->cover_image_path, ['w' => 600, 'h' => 400, 'fo' => 'auto']) }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-700"/>
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center text-4xl">📂</div>
                            @endif
                            
                            <div class="p-8 space-y-4">
                                <div class="flex justify-between items-start">
                                    <span class="bg-primary/5 text-primary px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ $project->type }}</span>
                                    @if($project->is_official)
                                        <span class="text-amber-500 text-lg" title="Verified Proof-of-Work">🏅</span>
                                    @endif
                                </div>
                                
                                <h4 class="text-xl font-black text-slate-800 leading-tight">{{ $project->title }}</h4>
                                <p class="text-xs text-slate-500 font-bold line-clamp-2 leading-relaxed">{{ $project->description }}</p>
                                
                                <div class="pt-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex -space-x-2">
                                            @foreach($project->endorsements->take(3) as $endorsement)
                                                <img src="{{ $endorsement->recruiter->profile_photo_url }}" class="w-6 h-6 rounded-full border-2 border-white shadow-sm" title="Endorsed by {{ $endorsement->recruiter->name }}">
                                            @endforeach
                                        </div>
                                        @if($project->endorsements->count() > 0)
                                            <span class="text-[9px] font-black text-slate-400 uppercase">+{{ $project->endorsements->count() }} Endorsements</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if(Auth::id() == $user->id)
                                        <form action="{{ route('showcase.store') }}" method="POST" onsubmit="return confirm('Archive this artifact?');" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-300 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ $project->artifact_url }}" target="_blank" class="bg-slate-900 text-white w-10 h-10 rounded-xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="md:col-span-2 py-20 text-center bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-100">
                            <p class="text-slate-400 font-black uppercase tracking-widest text-[10px] italic">The vault is currently silent. No artifacts manifested yet.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tab content: Academic Record (Resume) -->
                <div x-show="activeTab === 'resume'" x-transition class="space-y-12">
                    <!-- Professional Experience -->
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Industrial Experience</h4>
                            @if(Auth::id() == $user->id)
                                <button onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-experience' }))" class="text-primary font-black text-[10px] uppercase tracking-widest">+ Add Milestone</button>
                            @endif
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($experiences as $exp)
                            <div class="glass p-8 rounded-[2rem] border-white/60 flex gap-6 group hover:shadow-lg transition-all">
                                <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-2xl group-hover:bg-primary/5 transition-colors">🏢</div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex justify-between items-start">
                                        <h5 class="text-lg font-black text-slate-800">{{ $exp->title }}</h5>
                                        <div class="flex items-center gap-3">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $exp->duration }}</span>
                                            @if(Auth::id() == $user->id)
                                                <form action="{{ route('portfolio.experience.destroy', $exp->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-slate-300 hover:text-rose-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm font-bold text-primary">{{ $exp->company }} • {{ $exp->type }}</p>
                                    @if($exp->description)
                                        <p class="text-xs text-slate-500 font-medium pt-2 leading-relaxed">{{ $exp->description }}</p>
                                    @endif
                                </div>
                            </div>
                            @empty
                                <p class="text-[10px] font-bold text-slate-300 uppercase italic">No industrial milestones recorded.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Academic History -->
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Academic Trajectory</h4>
                            @if(Auth::id() == $user->id)
                                <button onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-education' }))" class="text-primary font-black text-[10px] uppercase tracking-widest">+ Add Record</button>
                            @endif
                        </div>

                        <div class="space-y-4">
                            @forelse($educations as $edu)
                            <div class="glass p-8 rounded-[2rem] border-white/60 flex gap-6 group hover:shadow-lg transition-all">
                                <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-2xl group-hover:bg-violet-50 transition-colors">🎓</div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex justify-between items-start">
                                        <h5 class="text-lg font-black text-slate-800">{{ $edu->institution }}</h5>
                                        <div class="flex items-center gap-3">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $edu->year }}</span>
                                            @if(Auth::id() == $user->id)
                                                <form action="{{ route('portfolio.education.destroy', $edu->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-slate-300 hover:text-rose-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm font-bold text-violet-600">{{ $edu->degree }} {{ $edu->field_of_study ? 'in '.$edu->field_of_study : '' }}</p>
                                </div>
                            </div>
                            @empty
                                <p class="text-[10px] font-bold text-slate-300 uppercase italic">Fundamental education node not set.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Tab content: Saved Notes -->
                <div x-show="activeTab === 'saved'" x-transition class="py-20 text-center glass rounded-[3rem] border-white/60">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">🔖</div>
                    <h5 class="text-lg font-black text-slate-800">Archive Empty</h5>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-2">Personal library coming in the next update.</p>
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
    <!-- Phase 9: Portfolio Modals -->
    @if(Auth::id() == $user->id)
    <!-- Manifest Artifact Modal -->
    <x-modal name="manifest-artifact" focusable>
        <form action="{{ route('showcase.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            <div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Manifest Proof of Work</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Add a verifiable artifact to your Verse Identity</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-2 col-span-2">
                    <x-input-label value="Artifact Title" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <x-text-input name="title" class="w-full" placeholder="e.g. Finance Valuation: Zomato Case Study" required />
                </div>
                <div class="space-y-2">
                    <x-input-label value="Stream/Niche" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <x-text-input name="stream" class="w-full" placeholder="e.g. Financial Analytics" required />
                </div>
                <div class="space-y-2">
                    <x-input-label value="Artifact Type" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <select name="type" class="w-full border-slate-200 rounded-2xl text-sm font-bold focus:ring-primary">
                        <option value="case_study">Case Study</option>
                        <option value="research">Research Paper</option>
                        <option value="design">Portfolio/Design</option>
                        <option value="code">Open Source/Code</option>
                        <option value="essay">Academic Essay</option>
                        <option value="other">Other Artifact</option>
                    </select>
                </div>
                <div class="space-y-2 col-span-2">
                    <x-input-label value="Direct Artifact Link" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <x-text-input name="artifact_url" class="w-full" placeholder="Google Drive, Notion, Behance, or Medium link" required />
                </div>
                <div class="space-y-2 col-span-2">
                    <x-input-label value="Cover Image" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <input type="file" name="cover_image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-primary/5 file:text-primary hover:file:bg-primary/10" accept="image/*" />
                </div>
                <div class="space-y-2 col-span-2">
                    <x-input-label value="Brief Description" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <textarea name="description" class="w-full border-slate-200 rounded-3xl text-sm font-medium focus:ring-primary h-32" placeholder="Describe the scope, tools used, and key outcomes..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button>Publish to Vault</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Experience Modal -->
    <x-modal name="add-experience" focusable>
        <form action="{{ route('portfolio.experience.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Record Milestone</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Document your industrial trajectory</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-input-label value="Role Title" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <x-text-input name="title" class="w-full" placeholder="e.g. Marketing Intern" required />
                </div>
                <div class="space-y-2">
                    <x-input-label value="Organization" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <x-text-input name="company" class="w-full" placeholder="e.g. Goldman Sachs" required />
                </div>
                <div class="space-y-2">
                    <x-input-label value="Contract Type" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <select name="type" class="w-full border-slate-200 rounded-2xl text-sm font-bold focus:ring-primary">
                        <option value="Internship">Internship</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Contract">Contract</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <x-input-label value="Duration" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <x-text-input name="duration" class="w-full" placeholder="e.g. June 2025 - Present" required />
                </div>
                <div class="space-y-2 col-span-2">
                    <x-input-label value="Brief Overview" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <textarea name="description" class="w-full border-slate-200 rounded-3xl text-sm font-medium focus:ring-primary h-24"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button>Update History</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Education Modal -->
    <x-modal name="add-education" focusable>
        <form action="{{ route('portfolio.education.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Academic Record</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Manifest your educational nodes</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-2 col-span-2">
                    <x-input-label value="Institution Name" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <x-text-input name="institution" class="w-full" placeholder="e.g. Chandigarh University" required />
                </div>
                <div class="space-y-2">
                    <x-input-label value="Degree/Certification" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <x-text-input name="degree" class="w-full" placeholder="e.g. BBA (Hons)" required />
                </div>
                <div class="space-y-2">
                    <x-input-label value="Year Span" class="text-[10px] uppercase font-black tracking-widest text-slate-400"/>
                    <x-text-input name="year" class="w-full" placeholder="e.g. 2022 - 2026" required />
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button>Update Node</x-primary-button>
            </div>
        </form>
    </x-modal>
    @endif
</x-app-layout>
