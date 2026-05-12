<x-verse-layout>
    @section('title', 'AIIMS Delhi | Institutional Intelligence - Full Feature Demo')
    
    <div class="bg-slate-50 min-h-screen" x-data="{ tab: 'overview', sidebarOpen: false }">
        <!-- Hero Section: Article Style -->
        <div class="relative h-[65vh] overflow-hidden">
            <img src="https://images.unsplash.com/photo-1519452635265-7b1fbfd1e4e0?q=80&w=1200" class="w-full h-full object-cover" alt="Hero">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 right-0 p-8 md:p-20">
                <div class="max-w-7xl mx-auto space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl shadow-2xl">🏛️</div>
                        <div class="space-y-1">
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/20 backdrop-blur-md rounded-full border border-white/20 text-white text-[9px] font-black uppercase tracking-widest">
                                ⭐ NIRF RANK #1
                            </div>
                            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter leading-tight italic">
                                AIIMS <span class="text-primary">Delhi.</span>
                            </h1>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-8 text-white/70 font-bold text-sm">
                        <span class="flex items-center gap-2">📍 Ansari Nagar, New Delhi</span>
                        <span class="w-1.5 h-1.5 bg-white/30 rounded-full"></span>
                        <span class="flex items-center gap-2">👨‍🎓 1,500+ Active Nodes</span>
                        <span class="w-1.5 h-1.5 bg-white/30 rounded-full"></span>
                        <span class="text-amber-400">4.9/5 Institutional Rating</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation Bar (Sticky) -->
        <div class="sticky top-0 z-50 bg-white/80 backdrop-blur-2xl border-b border-slate-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-6">
                <nav class="flex gap-10 md:gap-16 overflow-x-auto no-scrollbar whitespace-nowrap">
                    @foreach(['overview' => 'Intelligence', 'reviews' => 'Peer Reviews', 'archive' => 'Academic Archive', 'community' => 'Nexus Discussion', 'professors' => 'Faculty'] as $key => $label)
                    <button @click="tab = '{{ $key }}'" 
                            class="py-8 text-[10px] md:text-[11px] font-black uppercase tracking-[0.3em] transition-all relative group shrink-0"
                            :class="tab === '{{ $key }}' ? 'text-primary' : 'text-slate-400 hover:text-slate-900'">
                        {{ $label }}
                        <div x-show="tab === '{{ $key }}'" 
                             x-transition:enter="transition ease-out duration-500"
                             class="absolute bottom-0 left-0 right-0 h-1 bg-primary rounded-full shadow-[0_0_20px_rgba(37,99,235,0.4)]"></div>
                    </button>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="max-w-7xl mx-auto px-6 py-12 md:py-20 pb-40">
            <div class="grid lg:grid-cols-12 gap-16">
                
                <!-- Left: Dynamic Viewport -->
                <div class="lg:col-span-8 space-y-20">
                    
                    <!-- TAB: OVERVIEW -->
                    <div x-show="tab === 'overview'" x-transition class="space-y-16">
                        <!-- Article Content -->
                        <div class="bg-white p-10 md:p-16 rounded-[4rem] border border-slate-100 shadow-2xl space-y-12">
                            <div class="prose prose-slate max-w-none">
                                <h2 class="text-4xl font-black text-slate-900 italic tracking-tight mb-8">The Pinnacle of Medical Excellence</h2>
                                <p class="text-slate-500 text-xl leading-relaxed mb-8">
                                    Established in 1956, the All India Institute of Medical Sciences (AIIMS) has stood as the definitive landmark for medical education in Southeast Asia. This guide explores the institutional architecture that makes it the dream of every medical aspirant in India.
                                </p>

                                <h3 class="text-2xl font-black text-slate-900 mt-12 mb-6">Why AIIMS Delhi?</h3>
                                <div class="grid md:grid-cols-2 gap-8 my-10">
                                    <div class="bg-slate-50 p-8 rounded-3xl space-y-4">
                                        <span class="text-3xl">🧬</span>
                                        <h4 class="text-lg font-black text-slate-900">Research Focus</h4>
                                        <p class="text-sm text-slate-500 font-medium">Over 25% of India's medical research papers originate from these halls.</p>
                                    </div>
                                    <div class="bg-primary/5 p-8 rounded-3xl space-y-4">
                                        <span class="text-3xl">💉</span>
                                        <h4 class="text-lg font-black text-slate-900">Clinical Load</h4>
                                        <p class="text-sm text-slate-500 font-medium">Exposure to rare cases that you won't find anywhere else in the world.</p>
                                    </div>
                                </div>

                                <h3 class="text-2xl font-black text-slate-900 mt-12 mb-6">Academic Rigor & Campus Life</h3>
                                <p class="text-slate-500 text-lg leading-relaxed">
                                    Life at AIIMS is a blend of extreme academic pressure and the most vibrant student community. From the legendary 'Pulse' fest to the long hours in the library, every second here is a manifestation of hard work.
                                </p>
                            </div>
                        </div>

                        <!-- Placement/Stats Widget -->
                        <div class="grid md:grid-cols-3 gap-8">
                            <div class="bg-slate-900 p-10 rounded-[3rem] text-white space-y-2">
                                <p class="text-[10px] font-black uppercase tracking-widest text-primary">Avg Package</p>
                                <p class="text-4xl font-black italic">18 LPA</p>
                                <p class="text-[9px] font-bold text-white/30 italic">Institution Data</p>
                            </div>
                            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-xl space-y-2">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Highest PG Prep</p>
                                <p class="text-4xl font-black text-slate-900 italic">Top 1%</p>
                                <p class="text-[9px] font-bold text-slate-400 italic">Peer Verified</p>
                            </div>
                            <div class="bg-primary p-10 rounded-[3rem] text-white space-y-2">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/60">Global Ranking</p>
                                <p class="text-4xl font-black italic">#151</p>
                                <p class="text-[9px] font-bold text-white/40 italic">QS Rankings</p>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: REVIEWS (The Reality Check) -->
                    <div x-show="tab === 'reviews'" x-transition class="space-y-12">
                        <!-- Review Summary -->
                        <div class="bg-white p-12 rounded-[4rem] border border-slate-100 shadow-xl flex items-center justify-between">
                            <div class="flex items-center gap-8">
                                <div class="w-24 h-24 bg-slate-900 rounded-[2rem] flex flex-col items-center justify-center text-white">
                                    <span class="text-4xl font-black italic">4.9</span>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-primary">Rating</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 italic">Student Evaluation</h3>
                                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">Based on 156 verified experiences</p>
                                </div>
                            </div>
                            <button class="bg-primary text-white px-10 py-5 rounded-[2rem] font-black text-[11px] uppercase tracking-widest shadow-2xl shadow-primary/20 hover:scale-105 transition-all">Write Review ✍️</button>
                        </div>

                        <!-- Sample Review Node -->
                        <div class="bg-white p-16 rounded-[4rem] border border-slate-100 shadow-2xl space-y-8">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-6">
                                    <img src="https://ui-avatars.com/api/?name=Arsalan+Khan&background=0F172A&color=fff" class="w-20 h-20 rounded-3xl shadow-xl">
                                    <div>
                                        <h4 class="text-xl font-black text-slate-900 italic">Arsalan Khan</h4>
                                        <p class="text-[10px] font-black text-primary uppercase tracking-widest mt-1">Final Year • AIIMS Delhi</p>
                                    </div>
                                </div>
                                <div class="flex gap-1">
                                    @foreach(range(1,5) as $i) <span class="text-amber-400 text-xl">⭐</span> @endforeach
                                </div>
                            </div>
                            <p class="text-slate-500 text-xl font-medium leading-relaxed italic italic">
                                "The patient load here is insane. You see cases that other doctors only read about in textbooks. The hostel life is chill once you get past the first year academic shock."
                            </p>
                            <!-- Reality Tags -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-8 border-t border-slate-50">
                                <div class="bg-slate-50 p-4 rounded-2xl">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Canteen</p>
                                    <p class="text-[10px] font-bold text-slate-900">🍲 Budget Friendly</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Labs</p>
                                    <p class="text-[10px] font-bold text-slate-900">🔬 Global Standard</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Attendance</p>
                                    <p class="text-[10px] font-bold text-slate-900">👮 Moderate</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Social</p>
                                    <p class="text-[10px] font-bold text-slate-900">🎉 Vibrant (Pulse!)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: ARCHIVE -->
                    <div x-show="tab === 'archive'" x-transition class="grid md:grid-cols-2 gap-8">
                        @foreach([1,2,3,4] as $i)
                        <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-xl group hover:-translate-y-2 transition-all duration-500">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-primary/10 transition-colors">📄</div>
                            <h4 class="text-xl font-black text-slate-900 italic mb-2">Histology Verified Notes</h4>
                            <p class="text-[10px] font-black text-primary uppercase tracking-widest">Shared by Dr. Verma</p>
                        </div>
                        @endforeach
                    </div>

                </div>

                <!-- Right: Multiverse Sidebars -->
                <div class="lg:col-span-4 space-y-12">
                    
                    <!-- Batch Explorer Widget -->
                    <div class="bg-slate-900 p-10 rounded-[3.5rem] text-white shadow-2xl relative overflow-hidden group">
                        <div class="relative z-10 space-y-8">
                            <div class="w-16 h-16 bg-primary/20 rounded-2xl flex items-center justify-center text-3xl">🧬</div>
                            <div class="space-y-2">
                                <h4 class="text-2xl font-black tracking-tight italic">Batch Explorer</h4>
                                <p class="text-xs text-white/50 font-medium">Discover who else is in the Class of <span class="text-primary font-black">Final Year.</span></p>
                            </div>
                            
                            <!-- Avatars -->
                            <div class="flex -space-x-4">
                                @foreach(range(1,5) as $i)
                                <img src="https://ui-avatars.com/api/?name=User+{{$i}}&background=random" class="w-12 h-12 rounded-full border-4 border-slate-900 shadow-xl">
                                @endforeach
                                <div class="w-12 h-12 rounded-full bg-primary border-4 border-slate-900 flex items-center justify-center text-[10px] font-black">+45</div>
                            </div>

                            <button class="w-full bg-white text-slate-900 py-5 rounded-[2rem] font-black text-[11px] uppercase tracking-widest hover:bg-primary hover:text-white transition-all shadow-xl">Connect Early 🤝</button>
                        </div>
                        <!-- Decoration -->
                        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl"></div>
                    </div>

                    <!-- Active Nodes Widget -->
                    <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-xl space-y-10">
                        <div class="space-y-1">
                            <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Multiverse Presence</h4>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">AIIMS Delhi Sub-Registry</p>
                        </div>

                        <div class="space-y-8">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <span class="text-2xl">🤝</span>
                                    <div>
                                        <p class="text-xl font-black text-slate-900 italic leading-none">1,500+</p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase">Active Students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <span class="text-2xl">📚</span>
                                    <div>
                                        <p class="text-xl font-black text-slate-900 italic leading-none">156</p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase">Study Resources</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <span class="text-2xl">👨‍🏫</span>
                                    <div>
                                        <p class="text-xl font-black text-slate-900 italic leading-none">42</p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase">Faculty Reviews</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Institutional Feedback Trigger -->
                    <div class="bg-primary p-12 rounded-[3.5rem] text-white space-y-6 relative overflow-hidden group">
                        <div class="relative z-10">
                            <h4 class="text-2xl font-black italic mb-2 leading-tight">Help your juniors. Rate AIIMS now.</h4>
                            <p class="text-xs text-white/70 font-medium mb-8">Share your day-to-day experience with the community.</p>
                            <button class="bg-white text-primary px-10 h-16 rounded-[1.5rem] font-black text-[11px] uppercase tracking-widest hover:scale-105 transition-all">Write Review ✍️</button>
                        </div>
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-1000"></div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-verse-layout>
