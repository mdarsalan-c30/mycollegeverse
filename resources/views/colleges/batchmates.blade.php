<x-verse-layout>
    @section('title', 'Class of ' . $year . ' | ' . $college->name . ' - MyCollegeVerse')

    <div class="space-y-12 pb-24">
        <!-- Explorer Header -->
        <div class="relative bg-slate-900 rounded-[3rem] p-10 md:p-16 overflow-hidden shadow-2xl">
            <div class="relative z-10 max-w-4xl space-y-6">
                <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-white/40">
                    <a href="{{ route('colleges.index') }}" class="hover:text-white transition-colors">Nexus</a>
                    <span>/</span>
                    <a href="{{ route('colleges.show', $college->slug) }}" class="hover:text-white transition-colors">{{ $college->name }}</a>
                </nav>
                
                <h1 class="text-4xl md:text-6xl font-black text-white leading-tight">
                    Future Batchmates <br>
                    <span class="text-primary italic">Class of {{ $year }}</span>
                </h1>
                
                <p class="text-slate-400 text-lg md:text-xl font-medium max-w-2xl leading-relaxed">
                    Connect with the peers you'll be spending the next few years with. Build your network before the first lecture begins.
                </p>

                <!-- Viral Share Node -->
                <div class="flex flex-wrap gap-4 pt-4">
                    <button onclick="window.navigator.share({title: 'Meet our Batchmates!', url: window.location.href})" class="bg-primary text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 100-2.684 3 3 0 000 2.684zm0 12.684a3 3 0 100-2.684 3 3 0 000 2.684z"/></svg>
                        Invite Classmates
                    </button>
                    @auth
                        @if(!Auth::user()->is_batch_visible)
                        <form action="{{ route('profile.batch.toggle') }}" method="POST">
                            @csrf
                            <input type="hidden" name="visible" value="1">
                            <button type="submit" class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-white/20 transition-all">
                                Reveal My Profile to Batch
                            </button>
                        </form>
                        @endif
                    @endauth
                </div>
            </div>
            
            <!-- Abstract background art -->
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-primary/20 rounded-full blur-[100px]"></div>
            <div class="absolute right-20 bottom-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px]"></div>
        </div>

        <!-- Discovery Grid -->
        <div class="space-y-8">
            <div class="flex justify-between items-end px-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Digital Citizens</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Showing {{ $batchmates->total() }} future peers</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($batchmates as $mate)
                    <div class="group bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-primary/5 hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                        <div class="space-y-6 relative z-10">
                            <!-- Avatar Node -->
                            <div class="relative w-24 h-24 mx-auto">
                                <img src="{{ $mate->profile_photo_url }}" class="w-full h-full rounded-[2rem] object-cover shadow-xl border-4 border-white transition-transform group-hover:scale-110 duration-700">
                                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-emerald-500 border-4 border-white rounded-full flex items-center justify-center text-[10px] text-white">⚡</div>
                            </div>

                            <div class="text-center space-y-1">
                                <h3 class="text-xl font-black text-slate-900 group-hover:text-primary transition-colors">{{ $mate->name }}</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic truncate">{{ $mate->username }}</p>
                            </div>

                            <div class="flex items-center justify-center gap-4 border-t border-slate-50 pt-6">
                                <a href="{{ route('profile.show', $mate->username) }}" class="flex-1 bg-slate-50 text-slate-800 py-3 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-primary hover:text-white transition-all text-center">
                                    View Node
                                </a>
                                {{-- Assuming a follow system exists based on previous history --}}
                                @auth
                                    @if(Auth::id() !== $mate->id)
                                    <button class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                        
                        <!-- Background Pattern -->
                        <div class="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-10 transition-opacity">
                            <span class="text-6xl font-black">🎓</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[3rem] text-center space-y-4">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-4xl shadow-sm mx-auto">🔍</div>
                        <h3 class="text-xl font-black text-slate-400 uppercase tracking-widest">Horizon Empty</h3>
                        <p class="text-slate-400 font-medium italic">Be the first to reveal your digital identity to this batch!</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $batchmates->links() }}
            </div>
        </div>

        <!-- CTA Box -->
        <div class="bg-gradient-to-r from-primary to-indigo-600 rounded-[3rem] p-12 text-center text-white shadow-2xl relative overflow-hidden">
            <div class="relative z-10 max-w-2xl mx-auto space-y-6">
                <h2 class="text-3xl md:text-4xl font-black leading-tight italic">Missing your future team?</h2>
                <p class="text-white/80 font-medium text-lg">Send this page to your WhatsApp college group or Instagram story. Grow the batch!</p>
                <div class="flex justify-center flex-wrap gap-4 pt-2">
                    <a href="https://wa.me/?text={{ urlencode('Hey guys! Join me in the Class of ' . $year . ' batch on MyCollegeVerse: ' . request()->url()) }}" class="bg-white text-primary px-10 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl hover:-translate-y-1 transition-all">
                        Broadcast to WhatsApp
                    </a>
                </div>
            </div>
            
            <div class="absolute top-0 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        </div>
    </div>
</x-verse-layout>
