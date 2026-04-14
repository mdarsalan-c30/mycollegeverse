<x-verse-layout>
    @section('title', $seoTitle ?? $college->name . ' - Campus Hub | MyCollegeVerse')
    @section('meta_description', $seoDescription ?? 'Explore ' . $college->name . '. Get verified notes, faculty reviews, and join campus discussions.')

    @push('structured-data')
    <script type="application/ld+json">
        {!! json_encode($schema) !!}
    </script>
    @endpush

    <div class="min-h-screen bg-slate-50 font-sans" x-data="{ 
        tab: 'overview', 
        mobileMenu: false,
        showReviewForm: false,
        toggleReviewForm() {
            this.showReviewForm = !this.showReviewForm;
            if(this.showReviewForm) {
                this.$nextTick(() => {
                    document.getElementById('review-form-anchor')?.scrollIntoView({ behavior: 'smooth' });
                });
            }
        }
    }">
        <!-- Desktop/Mobile Header -->
        <header class="sticky top-0 z-[100] bg-white border-b border-slate-200 px-4 md:px-16 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('colleges.index') }}" class="text-xl font-bold tracking-tighter text-slate-900 border-r border-slate-200 pr-4">MV</a>
                <nav class="hidden md:flex items-center gap-6 text-[10px] uppercase font-bold tracking-widest text-slate-400">
                    <a href="{{ route('dashboard') }}" class="hover:text-black">Dash</a>
                    <a href="{{ route('colleges.index') }}" class="text-black">Colleges</a>
                    <a href="{{ route('notes.index') }}" class="hover:text-black">Archive</a>
                    <a href="{{ route('community.index') }}" class="hover:text-black">Nodes</a>
                </nav>
            </div>

            <div class="flex items-center gap-6">
                @auth
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-bold text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[8px] font-bold text-slate-400 uppercase mt-1 tracking-widest">Verified Hub</p>
                    </div>
                    <img src="{{ Auth::user()->profile_photo_url }}" class="w-8 h-8 rounded-none border border-slate-200 object-cover" />
                </div>
                @else
                <a href="{{ route('login') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-900 border border-slate-900 px-4 py-2 hover:bg-slate-900 hover:text-white transition-all">Sign In</a>
                @endauth
            </div>
        </header>

        <!-- College Hero -->
        <section class="relative bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto grid lg:grid-cols-2">
                <div class="p-8 md:p-16 space-y-8 flex flex-col justify-center">
                    <nav class="text-[10px] font-bold uppercase tracking-widest text-slate-400 flex items-center gap-2">
                        <a href="{{ route('colleges.index') }}">Colleges</a>
                        <span>/</span>
                        <span class="text-slate-900">{{ $college->name }}</span>
                    </nav>
                    <div class="space-y-4">
                        <h1 class="text-3xl md:text-5xl font-bold text-slate-900 leading-tight tracking-tight">{{ $college->name }}</h1>
                        <p class="flex items-center gap-2 text-slate-500 font-bold text-sm tracking-wide">
                            <span class="text-xl">📍</span> {{ $college->location }}
                        </p>
                    </div>
                    <div class="flex items-center gap-8 pt-4 border-t border-slate-100">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Avg Stats</p>
                            <p class="text-2xl font-bold text-slate-900">{{ number_format($college->reviews->avg('rating') ?: 4.8, 1) }} ★</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Placement</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $college->placement_stats['avg'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button @click="toggleReviewForm()" class="bg-slate-900 text-white px-10 py-5 rounded-none font-bold text-[11px] uppercase tracking-widest hover:bg-black transition-all">
                            Signify Your Influence ✍️
                        </button>
                    </div>
                </div>
                <div class="relative h-[300px] lg:h-auto border-l border-slate-200">
                    <img src="{{ $college->thumbnail_url ?? 'https://images.unsplash.com/photo-1541339907198-e08756ebafe3?q=80&w=1200' }}" class="w-full h-full object-cover grayscale-[20%] hover:grayscale-0 transition-all duration-700">
                </div>
            </div>
        </section>

        <!-- Main Information Area -->
        <main class="max-w-7xl mx-auto px-4 md:px-16 py-12 md:py-20">
            <!-- Navigation -->
            <nav class="flex gap-8 md:gap-12 border-b border-slate-200 mb-12 overflow-x-auto no-scrollbar">
                @foreach(['overview' => 'Institutional Profile', 'reviews' => 'Student Reviews', 'notes' => 'Archive', 'community' => 'Hub', 'professors' => 'Faculty'] as $key => $label)
                <button @click="tab = '{{ $key }}'" 
                        class="pb-6 text-[10px] font-bold uppercase tracking-widest transition-all relative shrink-0"
                        :class="tab === '{{ $key }}' ? 'text-black' : 'text-slate-400 hover:text-slate-900'">
                    {{ $label }}
                    <div x-show="tab === '{{ $key }}'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-black"></div>
                </button>
                @endforeach
            </nav>

            <div class="space-y-20">
                <!-- Tab: Overview -->
                <div x-show="tab === 'overview'" class="grid lg:grid-cols-12 gap-12 md:gap-20">
                    <div class="lg:col-span-8 space-y-12">
                        <div class="prose prose-slate max-w-none">
                            <h3 class="text-2xl font-bold text-slate-900 mb-6">About this Institution</h3>
                            <p class="text-slate-600 text-lg leading-relaxed">{{ $college->description }}</p>
                        </div>
                        
                        <div class="grid sm:grid-cols-3 gap-8">
                            @foreach([
                                ['label' => 'Avg Package', 'value' => $college->placement_stats['avg'] ?? 'N/A', 'desc' => 'Peer Verified'],
                                ['label' => 'High Package', 'value' => $college->placement_stats['highest'] ?? 'N/A', 'desc' => 'Verified Node'],
                                ['label' => 'Low Package', 'value' => $college->placement_stats['lowest'] ?? 'N/A', 'desc' => 'Institutional']
                            ] as $stat)
                            <div class="bg-slate-50 p-6 border border-slate-200 rounded-none">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ $stat['label'] }}</p>
                                <p class="text-2xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase mt-1">{{ $stat['desc'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <aside class="lg:col-span-4 space-y-8">
                        <div class="bg-white border border-slate-200 p-8 rounded-none space-y-6">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-4">Institutional Details</h4>
                            <div class="space-y-4">
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-400 font-bold uppercase">Type</span>
                                    <span class="text-slate-900 font-bold">Public Research</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-400 font-bold uppercase">Tier</span>
                                    <span class="text-slate-900 font-bold">Verified Node-1</span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>

                <!-- Tab: Reviews -->
                <div x-show="tab === 'reviews'" class="space-y-12">
                    <div id="review-form-anchor"></div>
                    
                    @auth
                    @if(!$myPendingReview && !$myApprovedReview)
                    <div x-show="showReviewForm" class="bg-slate-900 p-8 md:p-12 rounded-none space-y-10">
                        <div class="flex justify-between items-center text-white">
                            <h3 class="text-2xl font-bold">Institutional Evaluation</h3>
                            <button @click="showReviewForm = false" class="text-white/50 hover:text-white">✕</button>
                        </div>

                        <form action="{{ route('colleges.rate', $college->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                            @csrf
                            <div class="grid md:grid-cols-3 gap-8">
                                @foreach(['campus' => 'Culture', 'faculty' => 'Faculty', 'academic' => 'Academic'] as $key => $l)
                                <div class="space-y-4">
                                    <label class="text-[10px] font-bold text-white/50 uppercase tracking-widest">{{ $l }}</label>
                                    <select name="{{ $key }}_rating" class="w-full bg-white/5 border border-white/10 rounded-none py-4 px-6 text-white focus:ring-1 focus:ring-white outline-none">
                                        @foreach(range(5, 1) as $s)
                                        <option value="{{ $s }}" class="bg-slate-900 text-white">{{ $s }} Stars</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endforeach
                            </div>

                            <div class="bg-white/5 p-8 border border-white/10 space-y-8 rounded-none">
                                <h4 class="text-[10px] font-bold text-white uppercase tracking-widest">Placement Intelligence (LPA)</h4>
                                <div class="grid md:grid-cols-3 gap-8">
                                    <input type="number" step="0.1" name="average_package" class="bg-transparent border-b border-white/20 py-4 text-white outline-none focus:border-white transition-all" placeholder="Avg Package">
                                    <input type="number" step="0.1" name="lowest_package" class="bg-transparent border-b border-white/20 py-4 text-white outline-none focus:border-white transition-all" placeholder="Lowest">
                                    <input type="number" step="0.1" name="highest_package" class="bg-transparent border-b border-white/20 py-4 text-white outline-none focus:border-white transition-all" placeholder="Highest">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="text-[10px] font-bold text-white/50 uppercase tracking-widest">Experience Commentary</label>
                                <textarea name="comment" rows="4" class="w-full bg-white/5 border border-white/10 rounded-none p-6 text-white outline-none focus:ring-1 focus:ring-white transition-all" placeholder="Detail your campus journey..."></textarea>
                            </div>

                            @if(!Auth::user()->id_card_url)
                            <div class="bg-white/5 p-8 border border-dashed border-white/20 rounded-none space-y-6">
                                <h4 class="text-[10px] font-bold text-white uppercase tracking-widest">Identify Attestation</h4>
                                <div class="grid md:grid-cols-2 gap-8">
                                    <input type="text" name="verification_id" class="bg-transparent border-b border-white/20 py-4 text-white outline-none focus:border-white transition-all" placeholder="Reg ID / Roll No" required>
                                    <input type="file" name="id_card_image" class="text-white/50 text-[10px] font-bold uppercase tracking-widest" required>
                                </div>
                            </div>
                            @endif

                            <button type="submit" class="w-full bg-white text-slate-900 py-6 font-bold uppercase tracking-widest hover:bg-slate-100 transition-all">Submit Evaluation</button>
                        </form>
                    </div>
                    @endif
                    @endauth

                    <div class="space-y-8">
                        @forelse($college->reviews()->where('status', 'approved')->get() as $review)
                        <div class="bg-white border border-slate-200 p-8 md:p-10 rounded-none flex flex-col md:flex-row gap-10">
                            <div class="shrink-0 flex flex-col items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=f8fafc&color=64748b&bold=true" class="w-20 h-20 rounded-none border border-slate-100">
                                <div class="bg-slate-50 px-3 py-1 border border-slate-100 text-[9px] font-bold text-slate-500 uppercase tracking-widest">Verified</div>
                            </div>
                            <div class="flex-1 space-y-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-xl font-bold text-slate-900 leading-none">{{ $review->user->name }}</h4>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mt-2 tracking-widest">{{ $review->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="bg-slate-900 text-white px-4 py-2 font-bold text-lg">
                                        {{ number_format(($review->campus_rating + $review->faculty_rating + $review->academic_rating) / 3, 1) }} ★
                                    </div>
                                </div>
                                <p class="text-slate-600 text-lg italic leading-relaxed">"{{ $review->comment }}"</p>
                                <div class="flex gap-6 border-t border-slate-50 pt-6">
                                    @foreach(['Campus' => 'campus_rating', 'Fac.' => 'faculty_rating', 'Acad.' => 'academic_rating'] as $l => $f)
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                        {{ $l }}: <span class="text-slate-900">{{ $review->$f }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="bg-slate-50 border border-slate-200 p-20 text-center rounded-none font-bold text-slate-400 uppercase tracking-widest text-[10px]">
                            Awaiting Digital Synergy
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tab: Community -->
                <div x-show="tab === 'community'" class="max-w-4xl mx-auto space-y-12">
                    @auth
                    <div class="bg-white border border-slate-200 p-8 rounded-none space-y-8">
                        <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                            <div class="w-10 h-10 bg-slate-50 flex items-center justify-center rounded-none text-xl">✍️</div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Campus Broadcast</h3>
                        </div>
                        <form action="{{ route('community.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="college_id" value="{{ $college->id }}">
                            <input type="hidden" name="category" value="Campus Discussion">
                            <input type="text" name="title" class="w-full bg-slate-50 border border-slate-200 p-5 rounded-none outline-none focus:border-slate-900 transition-all text-sm" placeholder="Post title">
                            <textarea name="content" rows="3" class="w-full bg-slate-50 border border-slate-200 p-5 rounded-none outline-none focus:border-slate-900 transition-all text-sm" placeholder="Message content"></textarea>
                            <button type="submit" class="bg-slate-900 text-white px-10 py-4 font-bold uppercase tracking-widest text-[10px] hover:bg-black transition-all">Publish Post</button>
                        </form>
                    </div>
                    @endauth

                    <div class="space-y-8">
                        @foreach($college->posts as $post)
                        <div class="bg-white border border-slate-200 p-8 rounded-none space-y-6">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&background=f8fafc&bold=true" class="w-10 h-10 rounded-none border border-slate-100">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 leading-none">{{ $post->user->name }}</h4>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <h5 class="text-xl font-bold text-slate-900">{{ $post->title }}</h5>
                                <p class="text-slate-500 leading-relaxed">{{ $post->content }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tab: Archive -->
                <div x-show="tab === 'notes'" class="grid md:grid-cols-3 gap-8">
                    @foreach($college->notes as $note)
                    <a href="{{ route('notes.show', $note->id) }}" class="bg-white border border-slate-200 p-8 rounded-none hover:border-black transition-all group">
                        <div class="w-12 h-12 bg-slate-50 flex items-center justify-center rounded-none text-2xl mb-6 group-hover:bg-slate-100 transition-colors">📄</div>
                        <h4 class="text-lg font-bold text-slate-900 leading-tight mb-4">{{ $note->title }}</h4>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">By {{ $note->user->name }}</p>
                    </a>
                    @endforeach
                </div>

                <!-- Tab: Faculty -->
                <div x-show="tab === 'professors'" class="grid md:grid-cols-2 gap-8">
                    @foreach($college->professors as $prof)
                    <div class="bg-white border border-slate-200 p-8 rounded-none flex justify-between items-center">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-none text-2xl">👨‍🏫</div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-900 leading-none">{{ $prof->name }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">{{ $prof->department }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-slate-900">{{ number_format($prof->reviews_avg_rating ?: 4.9, 1) }} ★</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 py-12 px-4 md:px-16 text-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.4em]">MyCollegeVerse Intelligence Engine</p>
            <p class="text-[9px] font-bold text-slate-300 uppercase mt-4">© 2026 Institutional Governance</p>
        </footer>
    </div>
</x-verse-layout>
