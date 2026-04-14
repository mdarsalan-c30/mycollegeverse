<x-app-layout>
    @section('title', 'College Directory - Explore Campuses | MyCollegeVerse')
    @section('meta_description', 'Discover and explore colleges across India. Get insights into campus culture, academic performance, and verified student reviews.')
    <div class="space-y-12 pb-20">
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
                    Connect with {{ number_format($colleges->count()) }} institutions, access shared lecture notes, and join regional academic communities.
                </p>
                
                <form action="{{ route('colleges.index') }}" method="GET" class="pt-4 space-y-6">
                    <div class="relative max-w-md group">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for your campus..." class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-12 pr-4 text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/50 transition-all outline-none backdrop-blur-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>

                    <!-- Horizontal Filter Hub 🛰️ -->
                    <div class="flex flex-wrap gap-3">
                        {{-- Type Filter --}}
                        <div class="relative">
                            <select name="type" onchange="this.form.submit()" class="appearance-none bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs font-bold text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500/50 backdrop-blur-xl pr-8 cursor-pointer hover:bg-white/10 transition-all">
                                <option value="" class="bg-slate-900">All Types</option>
                                @foreach(config('college_metadata.types') as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }} class="bg-slate-900">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Stream Filter --}}
                        <div class="relative">
                            <select name="stream" onchange="this.form.submit()" class="appearance-none bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs font-bold text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500/50 backdrop-blur-xl pr-8 cursor-pointer hover:bg-white/10 transition-all">
                                <option value="" class="bg-slate-900">All Streams</option>
                                @foreach(config('college_metadata.streams') as $stream)
                                    <option value="{{ $stream }}" {{ request('stream') == $stream ? 'selected' : '' }} class="bg-slate-900">{{ $stream }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- State Filter --}}
                        <div class="relative">
                            <select name="state" onchange="this.form.submit()" class="appearance-none bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs font-bold text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500/50 backdrop-blur-xl pr-8 cursor-pointer hover:bg-white/10 transition-all">
                                <option value="" class="bg-slate-900">All States</option>
                                @foreach(config('college_metadata.states') as $state)
                                    <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }} class="bg-slate-900">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Rating Filter --}}
                        <div class="relative">
                            <select name="rating" onchange="this.form.submit()" class="appearance-none bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs font-bold text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500/50 backdrop-blur-xl pr-8 cursor-pointer hover:bg-white/10 transition-all">
                                <option value="" class="bg-slate-900">Any Rating</option>
                                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }} class="bg-slate-900">⭐ 4.0+</option>
                                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }} class="bg-slate-900">⭐ 3.0+</option>
                            </select>
                        </div>

                        <a href="{{ route('colleges.index') }}" class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-white transition-colors flex items-center">
                            Reset Filters
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Abstract astral shapes -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/20 rounded-full blur-[120px] -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-500/20 rounded-full blur-[100px] -mb-32 mr-32"></div>
        </div>

        <!-- Recommended Institution Verses -->
        <div class="grid lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-8">
                <div class="flex justify-between items-center px-2">
                    <h2 class="text-2xl font-black text-secondary">
                        @if(request()->anyFilled(['search', 'type', 'stream', 'state', 'rating']))
                            Filtering institutional multiverse...
                        @else
                            Recommended Institution Verses
                        @endif
                    </h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $colleges->count() }} Results</span>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    @forelse ($colleges as $college)
                    <div class="glass overflow-hidden rounded-[2.5rem] border-white/60 shadow-glass group hover:scale-[1.02] transition-all duration-500">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $college->campusimg ?? 'https://images.unsplash.com/photo-1541339907198-e08756ebafe3?q=80&w=600' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $college->name }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <div class="flex flex-wrap gap-2 mb-2">
                                    {{-- Priority Streams established by Admin --}}
                                    @if($college->streams)
                                        @foreach(array_slice($college->streams, 0, 3) as $stream)
                                            <span class="bg-indigo-500/40 backdrop-blur-md text-white px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest border border-white/10">{{ $stream }}</span>
                                        @endforeach
                                    @endif
                                    @foreach($college->tags ?? [] as $tag)
                                        <span class="bg-white/20 backdrop-blur-md text-white px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest">{{ $tag }}</span>
                                    @endforeach
                                </div>
                                <h3 class="text-xl font-bold text-white line-clamp-1">{{ $college->name }}</h3>
                                <p class="text-[10px] text-white/60 font-black uppercase tracking-widest mt-1">
                                    📍 {{ $college->city ?? $college->location }}, {{ $college->state ?? 'Unknown' }}
                                </p>
                            </div>
                            
                            {{-- Intelligent Rating Badge 🛰️ --}}
                            @php $avg = $college->average_rating; @endphp
                            <div @class([
                                'absolute top-4 right-4 backdrop-blur-2xl border border-white/20 text-white p-2 flex flex-col items-center justify-center rounded-2xl min-w-[3.5rem]',
                                'bg-emerald-500/40' => is_numeric($avg) && $avg >= 4,
                                'bg-indigo-500/40' => is_numeric($avg) && $avg < 4,
                                'bg-slate-500/40 italic' => !is_numeric($avg)
                            ])>
                                @if(is_numeric($avg))
                                    <span class="text-xs font-black leading-none">{{ number_format($avg, 1) }}</span>
                                    <span class="text-[7px] font-black uppercase tracking-tighter mt-0.5">Rating</span>
                                @else
                                    <span class="text-[8px] font-black uppercase leading-tight text-center">{{ $avg }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-8 space-y-4">
                            <p class="text-slate-500 text-sm font-medium line-clamp-2 leading-relaxed h-10">
                                {{ $college->description }}
                            </p>
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 px-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Students</span>
                                    <span class="text-sm font-bold text-primary">{{ number_format($college->users_count) }}</span>
                                </div>
                                <div class="flex flex-col text-right">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Type</span>
                                    <span class="text-sm font-bold text-primary">{{ $college->type ?? 'General' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('colleges.show', $college) }}" class="block w-full text-center bg-secondary text-white px-6 py-2.5 rounded-xl font-bold text-xs hover:bg-primary transition-all active:scale-95">
                                Enter Verse
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center glass rounded-[2.5rem] border-white/60">
                        <div class="bg-slate-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🏜️</div>
                        <h3 class="text-xl font-black text-secondary">No hub discovered in this sector.</h3>
                        <p class="text-slate-400 font-medium mt-2">Try adjusting your spectral filters or reset to see all hubs.</p>
                        <a href="{{ route('colleges.index') }}" class="inline-block mt-6 text-primary font-black text-xs uppercase tracking-widest border-b-2 border-primary/20 hover:border-primary transition-all">Reset Multiverse</a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Side Activity Feed -->
            <div class="space-y-8">
                <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-glass">
                    <h3 class="text-lg font-black text-secondary mb-6">Regional Hub Activity</h3>
                    <div class="space-y-8">
                        @foreach(App\Models\ChatMessage::with('sender')->latest()->take(5)->get() as $activity)
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
                        
                        @foreach(App\Models\CollegeReview::with(['user', 'college'])->latest()->take(3)->get() as $revAct)
                            <a href="{{ route('profile.show', $revAct->user->username) }}" class="flex gap-4 group cursor-pointer hover:bg-slate-50 p-2 rounded-xl transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all text-sm">
                                    ⭐
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800"><span class="text-primary">{{ $revAct->user->name }}</span> reviewed {{ $revAct->college->name ?? 'hub' }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{{ $revAct->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div x-data="{ showModal: false }" class="bg-indigo-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-500/20">
                    <div class="relative z-10 space-y-4">
                        <h4 class="text-lg font-black leading-tight">Can't find your campus?</h4>
                        <p class="text-white/60 text-xs font-medium">Join 500+ other students in pioneering your college's digital presence.</p>

                        @if($myPendingRequest)
                            <div class="bg-white/20 backdrop-blur-sm w-full py-3 rounded-xl font-black text-xs uppercase tracking-widest text-center">
                                ⏳ Request Pending: {{ $myPendingRequest->college_name }}
                            </div>
                        @else
                            <button @click="showModal = true" class="bg-white text-indigo-600 w-full py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-black/10 hover:bg-white/90 hover:scale-[1.02] active:scale-[0.98] transition-all">
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
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-left block">City *</label>
                                        <input type="text" name="city" required placeholder="e.g. Delhi"
                                               class="w-full h-11 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 placeholder-slate-300" />
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-left block">State *</label>
                                        <select name="state" required class="w-full h-11 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30">
                                            <option value="">Select State</option>
                                            @foreach(config('college_metadata.states') as $state)
                                                <option value="{{ $state }}">{{ $state }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-left block">Your College Email <span class="text-slate-300">(optional)</span></label>
                                    <input type="email" name="student_email" placeholder="you@college.edu"
                                           class="w-full h-11 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 placeholder-slate-300" />
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-left block">Why should we add it? <span class="text-slate-300">(optional)</span></label>
                                    <textarea name="message" rows="3" placeholder="Tell us a bit about your college community..."
                                              class="w-full bg-slate-50 border border-slate-100 rounded-xl p-4 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 resize-none placeholder-slate-300"></textarea>
                                </div>

                                <button type="submit"
                                        class="w-full bg-indigo-600 text-white h-12 rounded-xl font-black text-sm shadow-lg shadow-primary/20 hover:bg-indigo-700 hover:scale-[1.01] active:scale-[0.99] transition-all">
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
