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
                        <div><p class="text-xl font-black text-slate-800">128</p><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Followers</p></div>
                        <div><p class="text-xl font-black text-slate-800">450</p><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Downloads</p></div>
                        <div><p class="text-xl font-black text-slate-800">14k</p><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Points</p></div>
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

        <div class="grid lg:grid-cols-3 gap-10">
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
                         @for($i=1; $i<=3; $i++)
                            <div class="aspect-square glass rounded-2xl flex items-center justify-center text-3xl shadow-inner border-white/40 bg-white/10" title="Badge {{$i}}">
                                🏆
                            </div>
                         @endfor
                         <div class="aspect-square glass rounded-2xl flex items-center justify-center text-slate-300 font-black text-xs border-dashed border-2 border-slate-200">
                             +12
                         </div>
                    </div>
                </div>
            </div>

            <!-- Right Activity Feed -->
            <div class="lg:col-span-2 space-y-8">
                 <div class="flex gap-8 border-b border-slate-100 pb-2">
                    <button class="text-primary font-black border-b-2 border-primary pb-4">My Uploads</button>
                    <button class="text-slate-400 font-bold pb-4 hover:text-secondary transition-colors">Saved Notes</button>
                    <button class="text-slate-400 font-bold pb-4 hover:text-secondary transition-colors">Contributions</button>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    @for($i=0; $i<4; $i++)
                    <div class="glass p-6 rounded-[2.5rem] shadow-glass border-white hover:shadow-xl transition-all group">
                         <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                📄
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Uploaded 2d ago</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-800 mb-1 truncate">Algorithmic Logic v1</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">Computer Science</p>
                        <div class="flex items-center gap-4 text-xs font-bold text-slate-500">
                            <span class="flex items-center gap-1">📥 120</span>
                            <span class="flex items-center gap-1">⭐ 4.9</span>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
