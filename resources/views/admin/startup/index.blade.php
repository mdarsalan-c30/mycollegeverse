<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black text-admin-dark italic">Startup <span class="text-admin-primary">Control Tower</span></h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-2">Managing Institutional Identity & Social Matrix</p>
            </div>
            <div class="flex gap-4">
                <a href="/" target="_blank" class="px-6 py-3 glass rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-admin-primary transition-all shadow-sm">View Base Site</a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10">
        <!-- Social Matrix Configuration 📡 -->
        <div class="glass p-10 rounded-[3rem] border-admin-border/50 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 p-10 opacity-5">
                <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </div>

            <div class="relative z-10">
                <h3 class="text-xl font-black text-admin-dark mb-8 flex items-center gap-4 italic">
                    <span class="w-2 h-8 bg-admin-primary rounded-full"></span>
                    Social Hub Synchronization
                </h3>

                <form action="{{ route('admin.startup.social.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Instagram Link</label>
                        <input type="text" name="instagram_link" value="{{ $socials['instagram'] }}" class="w-full h-14 bg-white border border-admin-border rounded-2xl px-6 font-bold text-xs focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Reddit Community</label>
                        <input type="text" name="reddit_link" value="{{ $socials['reddit'] }}" class="w-full h-14 bg-white border border-admin-border rounded-2xl px-6 font-bold text-xs focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">X (Twitter) Profile</label>
                        <input type="text" name="x_link" value="{{ $socials['x_social'] }}" class="w-full h-14 bg-white border border-admin-border rounded-2xl px-6 font-bold text-xs focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">YouTube Channel</label>
                        <input type="text" name="youtube_link" value="{{ $socials['youtube'] }}" class="w-full h-14 bg-white border border-admin-border rounded-2xl px-6 font-bold text-xs focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Primary Contact Email</label>
                        <input type="email" name="contact_email" value="{{ $socials['contact_email'] }}" class="w-full h-14 bg-white border border-admin-border rounded-2xl px-6 font-bold text-xs focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    </div>

                    <div class="md:col-span-2 pt-4">
                        <button type="submit" class="bg-admin-dark text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-admin-dark/10 hover:scale-105 active:scale-95 transition-all">
                            Synchronize Social Matrix
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Institutional Pages Node 🏛️ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="glass p-10 rounded-[3rem] border-admin-border/50 shadow-sm relative overflow-hidden">
                <h3 class="text-xl font-black text-admin-dark mb-8 flex items-center gap-4 italic">
                    <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                    Company Identity Nodes
                </h3>

                <div class="space-y-4">
                    @foreach($slugs as $slug)
                    @php $page = $pages->get($slug); @endphp
                    <div class="p-6 bg-white border border-admin-border rounded-[1.5rem] flex items-center justify-between group hover:border-admin-primary transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-admin-primary transition-colors italic font-black text-xs">
                                {{ strtoupper(substr($slug, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 uppercase tracking-widest">{{ str_replace('-', ' ', $slug) }}</p>
                                <p class="text-[9px] font-bold {{ $page ? 'text-emerald-500' : 'text-red-400 animate-pulse' }} uppercase tracking-widest mt-0.5">
                                    {{ $page ? 'Node Manifested' : 'Missing Initialization' }}
                                </p>
                            </div>
                        </div>
                        @if($page)
                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="p-3 bg-admin-primary/5 text-admin-primary rounded-xl hover:bg-admin-primary hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.171-5.225a1.71 1.71 0 00-2.207 2.193l.353.353a1.71 1.71 0 002.193-2.207l-.353-.353z"/></svg>
                        </a>
                        @else
                        <a href="{{ route('admin.pages.create', ['slug' => $slug]) }}" class="px-4 py-2 bg-slate-100 text-slate-500 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-admin-primary hover:text-white transition-all">
                            Initialize
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="glass p-10 rounded-[3rem] border-admin-border/50 shadow-sm relative overflow-hidden flex flex-col justify-center text-center">
                <div class="w-20 h-20 bg-admin-primary/10 text-admin-primary rounded-[2rem] flex items-center justify-center text-4xl mx-auto mb-8 animate-bounce">
                    🚀
                </div>
                <h3 class="text-2xl font-black text-admin-dark italic">Multiverse Branding</h3>
                <p class="text-slate-500 text-xs font-medium leading-loose mt-4 px-10">
                    Use this Hub to maintain your strategic presence. Update policies, careers, and social links to ensure the Student OS remains professional and current.
                </p>
                <div class="mt-8 pt-8 border-t border-slate-50">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Delhi NCR Node Status: High Performance</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
