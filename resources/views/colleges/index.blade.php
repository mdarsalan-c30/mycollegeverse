<x-app-layout>
    @section('title', 'College Directory - Explore Campuses | MyCollegeVerse')
    @section('meta_description', 'Discover and explore colleges across India. Get insights into campus culture, academic performance, and verified student reviews.')
    <div x-data="{ 
        search: '',
        matches(college) {
            if (!this.search) return true;
            const s = this.search.toLowerCase();
            return college.name.toLowerCase().includes(s) || 
                   college.description.toLowerCase().includes(s) ||
                   college.tags.some(tag => tag.toLowerCase().includes(s));
        }
    }" class="space-y-12 pb-20">
        <!-- Digital Astral Hero -->
        <div class="relative overflow-hidden bg-slate-900 rounded-[3rem] p-12 shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 via-purple-500/10 to-transparent"></div>
            <div class="relative z-10 space-y-6 max-w-2xl">
                <div class="inline-flex items-center gap-2 bg-indigo-500/20 text-indigo-300 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest border border-indigo-500/30">
                    ✨ Institutional Explorer
                </div>
                <h1 class="text-4xl md:text-6xl font-black text-white leading-tight">
                    Explore the <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Digital Astral</span> of Higher Ed
                </h1>
                <p class="text-slate-400 text-lg font-medium leading-relaxed">
                    Connect with 4,000+ institutions, access shared lecture notes, and join regional academic communities.
                </p>
                
                <div class="pt-4">
                    <div class="relative max-w-md group">
                        <input type="text" x-model="search" @keydown.enter="$el.blur()" placeholder="Search for your campus..." class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-12 pr-4 text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/50 transition-all outline-none backdrop-blur-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                </div>
            </div>
            
            <!-- Abstract astral shapes -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/20 rounded-full blur-[120px] -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-500/20 rounded-full blur-[100px] -mb-32 mr-32"></div>
        </div>

        <!-- Recommended Institution Verses -->
        <div class="grid lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-8">
                <div class="flex justify-between items-center px-2">
                    <h2 class="text-2xl font-black text-secondary">Recommended Institution Verses</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Based on academic profile</span>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    @foreach ($colleges as $college)
                    <div x-show="matches({ 
                             name: '{{ addslashes($college->name) }}',
                             description: '{{ addslashes($college->description) }}',
                             tags: @json($college->tags ?? [])
                         })"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="glass overflow-hidden rounded-[2.5rem] border-white/60 shadow-glass group hover:scale-[1.02] transition-all duration-500">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $college->thumbnail_url ?? 'https://images.unsplash.com/photo-1541339907198-e08756ebafe3?q=80&w=600' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $college->name }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <div class="flex gap-2 mb-2">
                                    @foreach($college->tags ?? [] as $tag)
                                    <span class="bg-white/20 backdrop-blur-md text-white px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ $tag }}</span>
                                    @endforeach
                                </div>
                                <h3 class="text-xl font-bold text-white">{{ $college->name }}</h3>
                            </div>
                            <div class="absolute top-4 right-4 bg-white/10 backdrop-blur-xl border border-white/20 text-white w-10 h-10 rounded-xl flex items-center justify-center font-black">
                                {{ number_format($college->rating, 1) }}
                            </div>
                        </div>
                        <div class="p-8 space-y-4">
                            <p class="text-slate-500 text-sm font-medium line-clamp-2 leading-relaxed">
                                {{ $college->description }}
                            </p>
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 px-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Students</span>
                                    <span class="text-sm font-bold text-primary">{{ number_format($college->users_count) }}</span>
                                </div>
                                <div class="flex flex-col text-right">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Notes</span>
                                    <span class="text-sm font-bold text-primary">{{ number_format($college->notes_count) }} Shared</span>
                                </div>
                            </div>
                            <a href="{{ route('colleges.show', $college) }}" class="block w-full text-center bg-secondary text-white px-6 py-2 rounded-xl font-bold text-xs hover:bg-primary transition-colors">
                                Enter Verse
                            </a>
                        </div>
                    </div>
                    @endforeach

                    <!-- No Results State -->
                    <div x-show="!$el.parentElement.querySelector('div[x-show]:not([style*=\'display: none\'])')" 
                         class="col-span-full py-12 text-center glass rounded-[2.5rem]" 
                         style="display: none;">
                        <p class="text-slate-400 font-bold">No campus found matching your astral search.</p>
                    </div>
                </div>
            </div>

            <!-- Side Activity Feed -->
            <div class="space-y-8">
                <div class="glass p-8 rounded-[2.5rem] border-white/60">
                    <h3 class="text-lg font-black text-secondary mb-6">Regional Hub Activity</h3>
                    <div class="space-y-8">
                        @foreach(App\Models\ChatMessage::latest()->take(5)->get() as $activity)
                            @if($activity->sender)
                            <a href="{{ route('profile.show', $activity->sender->username) }}" class="flex gap-4 group cursor-pointer hover:bg-slate-50 p-2 rounded-xl transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all text-sm">
                                    💬
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800"><span class="text-primary">{{ $activity->sender->name }}</span> sent a message</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @endif
                        @endforeach
                        
                        @foreach(App\Models\Review::latest()->take(3)->get() as $revAct)
                            <a href="{{ route('profile.show', $revAct->user->username) }}" class="flex gap-4 group cursor-pointer hover:bg-slate-50 p-2 rounded-xl transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all text-sm">
                                    ⭐
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800"><span class="text-primary">{{ $revAct->user->name }}</span> reviewed a campus</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{{ $revAct->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div x-data="{ showModal: false }" class="bg-primary rounded-[2.5rem] p-8 text-white relative overflow-hidden">
                    <div class="relative z-10 space-y-4">
                        <h4 class="text-lg font-black leading-tight">Can't find your campus?</h4>
                        <p class="text-white/60 text-xs font-medium">Join 500+ other students in pioneering your college's digital presence.</p>

                        @if($myPendingRequest)
                            <div class="bg-white/20 backdrop-blur-sm w-full py-3 rounded-xl font-black text-xs uppercase tracking-widest text-center">
                                ⏳ Request Pending: {{ $myPendingRequest->college_name }}
                            </div>
                        @else
                            <button @click="showModal = true" class="bg-white text-primary w-full py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-black/10 hover:bg-white/90 hover:scale-[1.02] active:scale-[0.98] transition-all">
                                Request Admission
                            </button>
                        @endif
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>

                    {{-- Modal Overlay --}}
                    <div x-show="showModal" x-transition.opacity
                         class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                         @click.self="showModal = false"
                         style="display:none">

                        <div x-show="showModal" x-transition
                             class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8 relative text-slate-900"
                             @click.stop>

                            {{-- Close --}}
                            <button @click="showModal = false" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            <div class="mb-6">
                                <h3 class="text-xl font-black text-slate-900">🎓 Request Your College</h3>
                                <p class="text-xs text-slate-400 font-bold mt-1">Tell us about your campus and we'll add it to the network.</p>
                            </div>

                            <form action="{{ route('colleges.request') }}" method="POST" class="space-y-4">
                                @csrf

                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">College Name *</label>
                                    <input type="text" name="college_name" required placeholder="e.g. IIT Delhi, VIT Vellore..."
                                           class="w-full h-11 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 placeholder-slate-300" />
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">City *</label>
                                        <input type="text" name="city" required placeholder="e.g. Delhi"
                                               class="w-full h-11 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 placeholder-slate-300" />
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">State *</label>
                                        <input type="text" name="state" required placeholder="e.g. Delhi"
                                               class="w-full h-11 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 placeholder-slate-300" />
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Your College Email <span class="text-slate-300">(optional)</span></label>
                                    <input type="email" name="student_email" placeholder="you@college.edu"
                                           class="w-full h-11 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 placeholder-slate-300" />
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Why should we add it? <span class="text-slate-300">(optional)</span></label>
                                    <textarea name="message" rows="3" placeholder="Tell us a bit about your college community..."
                                              class="w-full bg-slate-50 border border-slate-100 rounded-xl p-4 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 resize-none placeholder-slate-300"></textarea>
                                </div>

                                <button type="submit"
                                        class="w-full bg-primary text-white h-12 rounded-xl font-black text-sm shadow-lg shadow-primary/20 hover:bg-primary/90 hover:scale-[1.01] active:scale-[0.99] transition-all">
                                    Submit Request 🚀
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
