<x-verse-layout>
    @section('title', $seoTitle ?? $college->name . ' - Campus Hub | MyCollegeVerse')
    @section('meta_description', $seoDescription ?? 'Explore ' . $college->name . '. Get verified notes, faculty reviews, and join campus discussions.')

    @push('structured-data')
    <script type="application/ld+json">
        {!! json_encode($schema) !!}
    </script>
    @endpush

    <div class="bg-slate-50 min-h-screen" x-data="{ 
        tab: 'overview', 
        showReviewForm: false,
        toggleReviewForm() {
            this.tab = 'reviews';
            this.showReviewForm = !this.showReviewForm;
            if(this.showReviewForm) {
                this.$nextTick(() => {
                    document.getElementById('review-form-anchor')?.scrollIntoView({ behavior: 'smooth' });
                });
            }
        }
    }">
        <!-- Hero Section: Premium Institutional Branding -->
        <div class="relative h-[65vh] overflow-hidden">
            <img src="{{ $college->thumbnail_url ?? 'https://images.unsplash.com/photo-1541339907198-e08756ebafe3?q=80&w=1200' }}" class="w-full h-full object-cover" alt="{{ $college->name }}">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 right-0 p-8 md:p-20">
                <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-end gap-12">
                    <div class="space-y-8">
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-4xl shadow-2xl shrink-0">🏛️</div>
                            <div class="space-y-1">
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/20 backdrop-blur-md rounded-full border border-white/20 text-white text-[9px] font-black uppercase tracking-widest">
                                    ⭐ INSTITUTIONAL NODE
                                </div>
                                <h1 class="text-4xl md:text-7xl font-black text-white tracking-tighter leading-tight italic">
                                    {{ $college->name }}
                                </h1>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-8 text-white/70 font-bold text-sm">
                            <span class="flex items-center gap-2">📍 {{ Str::limit($college->location, 30) }}</span>
                            <span class="w-1.5 h-1.5 bg-white/30 rounded-full"></span>
                            <span class="flex items-center gap-2">👨‍🎓 {{ number_format($college->users_count) }}+ Active Nodes</span>
                            <span class="w-1.5 h-1.5 bg-white/30 rounded-full"></span>
                            <span class="text-amber-400">★ {{ is_numeric($college->average_rating) ? number_format($college->average_rating, 1) : 'Yet to Review' }} Rating</span>
                        </div>
                    </div>
                    
                    <div class="pb-4">
                        @auth
                        <button @click="tab = 'reviews'; showReviewForm = true; $nextTick(() => { document.getElementById('review-form-section')?.scrollIntoView({ behavior: 'smooth' }); })" class="bg-white text-slate-900 px-12 py-6 rounded-[2rem] font-black text-[12px] uppercase tracking-widest shadow-2xl hover:bg-primary hover:text-white hover:scale-105 transition-all flex items-center gap-3">
                            Write Review ✍️
                        </button>
                        @else
                        <a href="{{ route('login') }}" class="bg-white text-slate-900 px-12 py-6 rounded-[2rem] font-black text-[12px] uppercase tracking-widest shadow-2xl hover:bg-primary hover:text-white hover:scale-105 transition-all flex items-center gap-3">
                            Login to Review ✍️
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- PLACEMENT HUB: HIGH VISIBILITY LAYER 🚀 -->
        <div class="bg-white border-b border-slate-100 shadow-sm relative z-20">
            <div class="max-w-7xl mx-auto px-6 py-10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Avg Placement</p>
                        <p class="text-3xl font-black text-primary italic">{{ $college->placement_stats['avg'] }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Highest Package</p>
                        <p class="text-3xl font-black text-slate-900 italic">{{ $college->placement_stats['max'] }}</p>
                    </div>
                    <div class="space-y-1 border-l border-slate-100 pl-8 hidden md:block">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lowest Package</p>
                        <p class="text-2xl font-black text-slate-500 italic">{{ $college->placement_stats['min'] }}</p>
                    </div>
                    <div class="flex items-center gap-4 border-l border-slate-100 pl-8">
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl">🛡️</div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Verified Data</p>
                            <p class="text-xs font-bold text-slate-900">Peer Audited ROI</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation Bar (Sticky) -->
        <div class="sticky top-0 z-50 bg-white/80 backdrop-blur-2xl border-b border-slate-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-6">
                <nav class="flex gap-10 md:gap-16 overflow-x-auto no-scrollbar whitespace-nowrap">
                    @foreach(['overview' => 'Intelligence', 'reviews' => 'Peer Reviews', 'notes' => 'Academic Archive', 'community' => 'Nexus Discussion', 'professors' => 'Faculty'] as $key => $label)
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
                
                <!-- Left: Content Workspace -->
                <div class="lg:col-span-8 space-y-20">
                    
                    <!-- TAB: OVERVIEW -->
                    <div x-show="tab === 'overview'" x-transition class="space-y-16">
                        <div class="bg-white p-10 md:p-16 rounded-[4rem] border border-slate-100 shadow-2xl space-y-12">
                            <div class="prose prose-slate max-w-none">
                                <h2 class="text-4xl font-black text-slate-900 italic tracking-tight mb-8">Institutional Architecture</h2>
                                <p class="text-slate-500 text-xl leading-relaxed mb-8">
                                    {{ $college->description }}
                                </p>

                                @if($college->academic_metrics)
                                <h3 class="text-2xl font-black text-slate-900 mt-12 mb-6">Academic Performance & Ecosystem</h3>
                                <div class="grid md:grid-cols-2 gap-8 my-10">
                                    <div class="bg-slate-50 p-8 rounded-3xl space-y-4">
                                        <span class="text-3xl">📚</span>
                                        <h4 class="text-lg font-black text-slate-900">Study Resources</h4>
                                        <p class="text-sm text-slate-500 font-medium">{{ $college->notes()->count() }} Community Notes available for download.</p>
                                    </div>
                                    <div class="bg-primary/5 p-8 rounded-3xl space-y-4">
                                        <span class="text-3xl">👨‍🏫</span>
                                        <h4 class="text-lg font-black text-slate-900">Faculty Intel</h4>
                                        <p class="text-sm text-slate-500 font-medium">Ratings and reality checks available for {{ $college->professors()->count() }} faculty members.</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Secondary Info Row -->
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="bg-white p-12 rounded-[3.5rem] border border-slate-100 shadow-xl space-y-6">
                                <h4 class="text-xl font-black italic">Campus Culture</h4>
                                <p class="text-sm text-slate-500 leading-relaxed">Join the most vibrant student network. Participate in discussions, events, and pulse updates happening live on campus.</p>
                                <button @click="tab = 'community'" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-primary transition-all">Enter Discussion 🗣️</button>
                            </div>
                            <div class="bg-primary p-12 rounded-[3.5rem] text-white space-y-6 relative overflow-hidden">
                                <div class="relative z-10">
                                    <h4 class="text-xl font-black italic">Archive Access</h4>
                                    <p class="text-sm text-white/70 leading-relaxed">Access previous year questions, class notes, and institutional blueprints shared by verified seniors.</p>
                                    <button @click="tab = 'notes'" class="bg-white text-primary px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest">Browse Archive 📄</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: REVIEWS (Student Reality) -->
                    <div x-show="tab === 'reviews'" x-transition class="space-y-12">
                        <div id="review-form-anchor"></div>
                        
                        <!-- Review Header -->
                        <div class="bg-white p-12 rounded-[4rem] border border-slate-100 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
                            <div class="flex items-center gap-8">
                                <div class="w-24 h-24 bg-slate-900 rounded-[2rem] flex flex-col items-center justify-center text-white">
                                    <span class="text-4xl font-black italic">{{ is_numeric($college->average_rating) ? number_format($college->average_rating, 1) : '0.0' }}</span>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-primary">Rating</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 italic">Institutional Evaluations</h3>
                                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">Based on {{ $college->reviews()->where('status', 'approved')->count() }} verified experiences</p>
                                </div>
                            </div>
                            <button @click="toggleReviewForm()" class="bg-primary text-white px-10 py-5 rounded-[2rem] font-black text-[11px] uppercase tracking-widest shadow-2xl shadow-primary/20 hover:scale-105 transition-all">Write Review ✍️</button>
                        </div>

                        <!-- REVIEW FORM (INTEGRATED) -->
                        @auth
                        <div id="review-form-section" x-show="showReviewForm" x-collapse>
                            <div class="bg-slate-900 rounded-[4rem] p-12 space-y-10 relative overflow-hidden shadow-2xl mb-16">
                                <div class="relative z-10">
                                    <div class="flex justify-between items-start mb-12">
                                        <div class="space-y-1">
                                            <h3 class="text-3xl font-black text-white italic">Evaluate {{ $college->name }}</h3>
                                            <p class="text-white/40 text-sm font-medium">Your node contribute to the overall institutional intelligence.</p>
                                        </div>
                                        <button @click="showReviewForm = false" class="text-white/20 hover:text-white transition-colors text-2xl">✕</button>
                                    </div>

                                    <form action="{{ route('colleges.rate', $college->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                                        @csrf
                                        <div class="grid md:grid-cols-3 gap-10">
                                            @foreach(['campus' => 'Campus Culture', 'faculty' => 'Faculty Quality', 'academic' => 'Academic Rigor'] as $key => $l)
                                            <div class="space-y-4">
                                                <label class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] ml-2">{{ $l }}</label>
                                                <select name="{{ $key }}_rating" class="w-full bg-white/5 border border-white/10 rounded-3xl py-5 px-8 text-white focus:ring-4 focus:ring-primary/20 outline-none appearance-none">
                                                    @foreach(range(5, 1) as $s)
                                                    <option value="{{ $s }}" class="bg-slate-900 text-white">{{ $s }} Stars</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endforeach
                                        </div>

                                        <div class="bg-white/5 p-12 rounded-[3.5rem] border border-white/10 space-y-10">
                                            <div class="flex items-center gap-4">
                                                <span class="text-2xl">🧬</span>
                                                <h4 class="text-xs font-black text-white uppercase tracking-widest">Reality Check Grid</h4>
                                            </div>
                                            <div class="grid md:grid-cols-2 gap-6">
                                                @foreach([
                                                    'canteen' => ['label' => 'Canteen Status', 'options' => ['Premium Menu', 'Survival Mode', 'Only Maggi Lives Here']],
                                                    'infra' => ['label' => 'Lab Infrastructure', 'options' => ['High-end Tech', 'Standard', 'Windows 98 Vibes']],
                                                    'attendance' => ['label' => 'Attendance Policy', 'options' => ['Chill/Liberal', 'Moderate', 'Strict as a Jail']],
                                                    'social' => ['label' => 'Campus Social Life', 'options' => ['Vibrant/Active', 'Academic Focus', 'Dry as a Desert']]
                                                ] as $key => $card)
                                                <div class="space-y-3">
                                                    <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] ml-2">{{ $card['label'] }}</label>
                                                    <select name="reality_tags[{{ $key }}]" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white text-[10px] focus:border-primary outline-none">
                                                        <option value="" class="bg-slate-900">Select Reality...</option>
                                                        @foreach($card['options'] as $option)
                                                        <option value="{{ $option }}" class="bg-slate-900">{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="space-y-4">
                                            <label class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] ml-2">Experience Narrative</label>
                                            <textarea name="comment" rows="5" class="w-full bg-white/5 border border-white/10 rounded-[3rem] p-10 text-white placeholder-white/20 focus:ring-4 focus:ring-primary/10 outline-none text-lg" placeholder="Be brutal, be honest. How is it really?"></textarea>
                                        </div>

                                        @if(!Auth::user()->id_card_url)
                                        <div class="bg-primary/10 border-2 border-dashed border-primary/20 rounded-[3rem] p-10 space-y-6">
                                            <div class="flex items-center gap-4">
                                                <span class="text-2xl">🆔</span>
                                                <h4 class="text-sm font-black text-white uppercase tracking-widest">Identity Verification</h4>
                                            </div>
                                            <div class="grid md:grid-cols-2 gap-8">
                                                <input type="text" name="verification_id" placeholder="Roll Number / Reg ID" required class="bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white text-xs">
                                                <input type="file" name="id_card_image" required class="text-[10px] text-white/50 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-white file:text-slate-900">
                                            </div>
                                        </div>
                                        @else
                                        <input type="hidden" name="verification_id" value="PREVIOUSLY_VERIFIED">
                                        @endif

                                        <button type="submit" class="w-full bg-white text-slate-900 h-20 rounded-[2rem] font-black text-[11px] uppercase tracking-[0.3em] hover:bg-primary hover:text-white transition-all shadow-2xl">Manifest Evaluation ⚔️</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endauth

                        <!-- Review Feed -->
                        <div class="space-y-12">
                            @forelse($college->reviews()->where('status', 'approved')->get() as $review)
                            <div class="bg-white p-16 rounded-[4rem] border border-slate-100 shadow-2xl space-y-10">
                                <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                                    <div class="flex items-center gap-6">
                                        <img src="{{ $review->user->profile_photo_url }}" class="w-20 h-20 rounded-3xl shadow-xl object-cover">
                                        <div>
                                            <h4 class="text-xl font-black text-slate-900 italic">{{ $review->user->name }}</h4>
                                            <p class="text-[10px] font-black text-primary uppercase tracking-widest mt-1">Verified • {{ $review->user->year }} Year</p>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-black text-slate-900 bg-slate-50 px-6 py-4 rounded-2xl border border-slate-100">
                                        {{ number_format(($review->campus_rating + $review->faculty_rating + $review->academic_rating) / 3, 1) }}
                                    </div>
                                </div>
                                <p class="text-slate-500 text-xl font-medium leading-relaxed italic">"{{ $review->comment }}"</p>
                                
                                @if($review->reality_tags)
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-8 border-t border-slate-50">
                                    @foreach($review->reality_tags as $k => $v)
                                    <div class="bg-slate-50 p-4 rounded-2xl">
                                        <p class="text-[8px] font-black text-slate-400 uppercase mb-1">{{ ucfirst($k) }}</p>
                                        <p class="text-[10px] font-bold text-slate-900">{{ $v }}</p>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-20 bg-slate-50 rounded-[4rem] border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 font-black uppercase tracking-widest text-xs italic">Awaiting first institutional pulse...</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- TAB: NOTES (Restored 📚) -->
                    <div x-show="tab === 'notes'" x-transition class="space-y-12">
                        <div class="grid sm:grid-cols-2 gap-8">
                            @forelse($college->notes as $note)
                            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-xl space-y-6 group hover:scale-[1.02] transition-all">
                                <div class="flex justify-between items-start">
                                    <div class="w-14 h-14 bg-primary/10 text-primary rounded-2xl flex items-center justify-center text-2xl group-hover:bg-primary group-hover:text-white transition-colors">📄</div>
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">{{ $note->subject->code ?? 'GEN' }}</span>
                                </div>
                                <div>
                                    <h4 class="text-lg font-black text-slate-900 leading-tight mb-2">{{ $note->title }}</h4>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $note->subject->name ?? 'General Resource' }}</p>
                                </div>
                                <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $note->user->profile_photo_url }}" class="w-8 h-8 rounded-full">
                                        <span class="text-[10px] font-bold text-slate-500">{{ $note->user->name }}</span>
                                    </div>
                                    <a href="{{ route('notes.show', $note->slug) }}" class="text-primary text-[10px] font-black uppercase tracking-widest">Access Node ⚡</a>
                                </div>
                            </div>
                            @empty
                            <div class="col-span-full text-center py-20 bg-slate-50 rounded-[4rem] border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 font-black uppercase tracking-widest text-xs italic">Archive is currently empty...</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- TAB: COMMUNITY -->
                    <div x-show="tab === 'community'" x-transition class="space-y-12">
                        @auth
                        <div class="bg-white p-12 rounded-[3.5rem] border border-slate-100 shadow-xl space-y-8">
                            <h4 class="text-2xl font-black italic">Campus Discussion</h4>
                            <form action="{{ route('community.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="college_id" value="{{ $college->id }}">
                                <input type="text" name="title" placeholder="Topic title..." class="w-full bg-slate-50 border-none rounded-2xl py-5 px-8 font-bold">
                                <textarea name="content" rows="3" placeholder="Share with the verse..." class="w-full bg-slate-50 border-none rounded-3xl p-8 font-medium"></textarea>
                                <button type="submit" class="bg-slate-900 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest">Broadcast 📡</button>
                            </form>
                        </div>
                        @endauth

                        <div class="space-y-8">
                            @foreach($college->posts as $post)
                            <div class="bg-white p-16 rounded-[4rem] border border-slate-100 shadow-xl">
                                <div class="flex items-center gap-6 mb-8">
                                    <img src="{{ $post->user->profile_photo_url }}" class="w-16 h-16 rounded-2xl">
                                    <div>
                                        <p class="font-black text-slate-900 text-lg">{{ $post->user->name }}</p>
                                        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <h5 class="text-2xl font-black mb-4">{{ $post->title }}</h5>
                                <p class="text-slate-500 font-medium leading-relaxed">{{ $post->content }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- TAB: PROFESSORS (Restored 👨‍🏫) -->
                    <div x-show="tab === 'professors'" x-transition class="space-y-12">
                        <div class="grid sm:grid-cols-2 gap-8">
                            @forelse($college->professors as $prof)
                            <div class="bg-white p-12 rounded-[3.5rem] border border-slate-100 shadow-xl space-y-8 group">
                                <div class="flex items-center gap-6">
                                    <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center text-4xl group-hover:bg-primary/10 transition-colors">👨‍🏫</div>
                                    <div>
                                        <h4 class="text-xl font-black text-slate-900 italic">{{ $prof->name }}</h4>
                                        <p class="text-[10px] font-black text-primary uppercase tracking-widest mt-1">{{ $prof->department }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-slate-50 p-4 rounded-2xl">
                                        <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Teaching</p>
                                        <p class="text-xs font-black text-slate-900">{{ $prof->teaching_rating }}/5</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl">
                                        <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Strictness</p>
                                        <p class="text-xs font-black text-slate-900">{{ $prof->strictness_rating }}/5</p>
                                    </div>
                                </div>
                                <a href="{{ route('professors.show', $prof->slug) }}" class="block w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-[10px] text-center uppercase tracking-widest hover:bg-primary transition-all">View Intel 🛡️</a>
                            </div>
                            @empty
                            <div class="col-span-full text-center py-20 bg-slate-50 rounded-[4rem] border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 font-black uppercase tracking-widest text-xs italic">No faculty evaluations logged yet...</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Right: Institutional Metrics & Widgets -->
                <div class="lg:col-span-4 space-y-12">
                    
                    <!-- COLLEGE RATINGS: HIGH FIDELITY -->
                    <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-xl space-y-10">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest border-b border-slate-50 pb-6">College Ratings</h4>
                        <div class="space-y-8">
                            @foreach($college->academic_metrics as $metric)
                            <div class="space-y-3">
                                <div class="flex justify-between items-end">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $metric['label'] }}</span>
                                    <span class="text-[11px] font-black text-slate-900 italic">{{ $metric['text'] }}</span>
                                </div>
                                <div class="h-2 w-full bg-slate-50 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary rounded-full transition-all duration-1000" style="width: {{ $metric['percent'] }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Batch Explorer Widget -->
                    <div class="bg-slate-900 p-10 rounded-[3.5rem] text-white shadow-2xl relative overflow-hidden">
                        <div class="relative z-10 space-y-8">
                            <div class="w-16 h-16 bg-primary/20 rounded-2xl flex items-center justify-center text-3xl">🧬</div>
                            <div class="space-y-2">
                                <h4 class="text-2xl font-black tracking-tight italic">Batch Explorer</h4>
                                <p class="text-xs text-white/50 font-medium">See who else is in your class. Connect early!</p>
                            </div>
                            
                            <div class="flex -space-x-4">
                                @foreach($college->users()->take(4)->get() as $u)
                                <img src="{{ $u->profile_photo_url }}" class="w-12 h-12 rounded-full border-4 border-slate-900">
                                @endforeach
                                <div class="w-12 h-12 rounded-full bg-primary border-4 border-slate-900 flex items-center justify-center text-[10px] font-black">+{{ max(0, $college->users_count - 4) }}</div>
                            </div>

                            <a href="{{ route('colleges.batchmates', ['college' => $college->slug, 'year' => 2024]) }}" class="block w-full bg-white text-slate-900 py-5 rounded-[2rem] font-black text-[11px] text-center uppercase tracking-widest hover:bg-primary hover:text-white transition-all shadow-xl">Connect Early 🤝</a>
                        </div>
                    </div>

                    <!-- Career Destinations -->
                    @if($college->career_destinations->isNotEmpty())
                    <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-xl space-y-10">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest border-b border-slate-50 pb-6">Career Destinations</h4>
                        <div class="space-y-8">
                            @foreach($college->career_destinations as $dest)
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $dest['label'] }}</span>
                                    <span class="text-[11px] font-black text-primary">{{ $dest['percent'] }}%</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-50 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary rounded-full transition-all duration-1000" style="width: {{ $dest['percent'] }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</x-verse-layout>
