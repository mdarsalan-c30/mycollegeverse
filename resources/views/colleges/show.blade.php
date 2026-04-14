<x-verse-layout>
    @section('title', $seoTitle ?? $college->name . ' - Campus Hub | MyCollegeVerse')
    @section('meta_description', $seoDescription ?? 'Explore ' . $college->name . '. Get verified notes, faculty reviews, and join campus discussions.')

    @push('structured-data')
    <script type="application/ld+json">
        {!! json_encode($schema) !!}
    </script>
    @endpush
    <div class="flex w-full h-screen bg-slate-50 overflow-hidden" x-data="{ 
        tab: 'overview', 
        sidebarOpen: false,
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
        <!-- VerseOS Navigation Sidebar (Desktop & Mobile) -->
        <aside 
            class="fixed inset-y-0 left-0 z-[60] w-80 bg-white border-r border-slate-200/60 flex flex-col p-8 space-y-12 shrink-0 transition-transform duration-500 lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0 shadow-2xl shadow-primary/20' : '-translate-x-full lg:translate-x-0'"
            @click.away="sidebarOpen = false">
            
            <div class="flex justify-between items-center">
                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">College OS</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] opacity-80">Academic Curator</p>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">✕</button>
            </div>

            <nav class="flex-1 space-y-3 overflow-y-auto">
                @foreach([
                    ['icon' => '🏠', 'label' => 'Dashboard', 'route' => 'dashboard'],
                    ['icon' => '🏛️', 'label' => 'Colleges', 'route' => 'colleges.index', 'active' => true],
                    ['icon' => '📄', 'label' => 'Notes', 'route' => 'notes.index'],
                    ['icon' => '🤝', 'label' => 'Community', 'route' => 'community.index'],
                    ['icon' => '👨‍🏫', 'label' => 'Professors', 'route' => 'professors.index']
                ] as $item)
                <a href="{{ $item['route'] == '#' ? '#' : (Route::has($item['route']) ? route($item['route']) : '#') }}" 
                   class="flex items-center gap-4 px-6 py-4 rounded-[2rem] transition-all group {{ ($item['active'] ?? false) ? 'bg-indigo-50 text-primary shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span class="text-xl group-hover:scale-125 transition-transform duration-300">{{ $item['icon'] }}</span>
                    <span class="text-sm font-extrabold">{{ $item['label'] }}</span>
                </a>
                @endforeach
            </nav>

            <div class="space-y-8">
                <a href="#" class="flex items-center gap-4 px-6 py-3 text-slate-400 hover:text-secondary group transition-colors">
                    <span class="text-xl group-hover:rotate-90 transition-transform duration-500 text-slate-300">⚙️</span>
                    <span class="text-sm font-bold">Settings</span>
                </a>
                <button class="w-full bg-primary text-white py-5 rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] shadow-2xl shadow-primary/30 hover:shadow-primary/40 hover:-translate-y-1 transition-all duration-300">
                    Launch Deep Work
                </button>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 overflow-y-auto px-4 md:px-16 py-6 md:py-10 space-y-12 bg-white/30 backdrop-blur-3xl relative">
            <!-- VerseOS Header Bar -->
            <header class="flex justify-between items-center bg-white/40 backdrop-blur-xl sticky top-0 z-50 py-6 -mt-6 -mx-4 md:-mx-16 px-4 md:px-16 border-b border-white/20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden w-12 h-12 rounded-2xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-sm mr-2 transition-transform active:scale-95">
                        <span>☰</span>
                    </button>
                    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                        <a href="{{ route('colleges.index') }}" class="hover:text-primary transition-colors hidden sm:inline">Nexus</a>
                        <span class="opacity-30 hidden sm:inline">/</span>
                        <span class="text-slate-900 truncate max-w-[120px] md:max-w-none">{{ $college->name }}</span>
                    </nav>
                </div>
                <div class="flex items-center gap-3 md:gap-6">
                    @auth
                    <button class="w-10 h-10 md:w-12 md:h-12 rounded-2xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-sm hover:scale-110 transition-all group">
                        <span class="group-hover:animate-pulse">🔔</span>
                    </button>
                    @endauth

                    <div class="flex items-center gap-4 pl-3 md:pl-6 border-l border-slate-200 relative" x-data="{ profileOpen: false }">
                        @auth
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-black text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] font-black text-primary uppercase mt-1">Verse Alpha</p>
                        </div>
                        <button @click="profileOpen = !profileOpen" class="relative group">
                            <img src="{{ Auth::user()->profile_photo_url }}" class="w-10 h-10 md:w-12 md:h-12 rounded-2xl shadow-xl border-2 border-white transition-transform active:scale-95 group-hover:shadow-primary/20 object-cover" />
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                        </button>

                        <!-- High-Fidelity Profile Dropdown -->
                        <div x-show="profileOpen" 
                             @click.away="profileOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             class="absolute right-0 top-full mt-4 w-64 glass bg-white/95 backdrop-blur-xl rounded-[2.5rem] border border-slate-100 shadow-2xl p-4 space-y-2 z-[100]">
                            <div class="px-6 py-4 border-b border-slate-50 lg:hidden">
                                <p class="text-sm font-black text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Academic Curator</p>
                            </div>

                            <a href="{{ route('profile.show', Auth::user()->username) }}" class="flex items-center gap-4 px-6 py-4 text-slate-600 hover:bg-primary/5 hover:text-primary rounded-2xl transition-all group">
                                <span class="text-xl group-hover:scale-110 transition-transform">👤</span>
                                <span class="text-xs font-bold uppercase tracking-widest">My Profile</span>
                            </a>

                            <div class="pt-2 border-t border-slate-50">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-4 w-full px-6 py-4 text-slate-400 hover:bg-red-50 hover:text-red-600 rounded-2xl transition-all group">
                                        <span class="text-xl group-hover:rotate-12 transition-transform">🚪</span>
                                        <span class="text-xs font-bold uppercase tracking-widest text-left">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                            Sign In
                        </a>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Digital Astral Hero -->
            <div class="relative h-[300px] md:h-[480px] rounded-[2.5rem] md:rounded-[3.5rem] overflow-hidden shadow-2xl group cursor-default">
                <img src="{{ $college->thumbnail_url ?? 'https://images.unsplash.com/photo-1541339907198-e08756ebafe3?q=80&w=1200' }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" alt="{{ $college->name }}">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                
                <!-- Floating Institutional Card -->
                <div class="absolute bottom-6 left-6 right-6 md:bottom-12 md:left-12 md:right-12 glass bg-white/10 backdrop-blur-3xl border-white/20 p-6 md:p-10 rounded-[2rem] md:rounded-[3rem] flex flex-col md:flex-row justify-between items-center gap-6 md:gap-8 shadow-2xl">
                    <div class="flex items-center gap-4 md:gap-8 w-full md:w-auto">
                        <div class="hidden sm:flex w-16 h-16 md:w-24 md:h-24 bg-white rounded-[1.5rem] md:rounded-[2rem] items-center justify-center text-2xl md:text-4xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] shrink-0">
                            🏛️
                        </div>
                        <div class="space-y-1 md:space-y-2">
                            <div class="flex items-center gap-4">
                                <h1 class="text-2xl md:text-4xl font-black text-white leading-tight tracking-tight">{{ $college->name }}</h1>
                                <span class="bg-primary/20 backdrop-blur-md text-white border border-white/30 px-2 py-1 rounded-lg text-[8px] md:text-[10px] font-black uppercase tracking-widest hidden sm:inline">Global Node</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 md:gap-6 text-white/70 font-bold text-[10px] md:text-sm">
                                <span class="flex items-center gap-2 tracking-wide">
                                    <span class="text-sm md:text-xl">📍</span> {{ Str::limit($college->location, 20) }}
                                </span>
                                <span class="text-white/30">•</span>
                                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/20">
                                    <span class="text-amber-400 text-sm md:text-xl shrink-0">⭐</span>
                                    @php $avg = $college->average_rating; @endphp
                                    <span @class([
                                        'text-white font-black leading-none',
                                        'text-sm md:text-lg' => is_numeric($avg),
                                        'text-[8px] md:text-[10px] uppercase tracking-widest' => !is_numeric($avg)
                                    ])>
                                        {{ is_numeric($avg) ? number_format($avg, 1) : $avg }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="w-full md:w-auto bg-white text-primary px-8 py-4 md:px-10 md:py-5 rounded-[1.5rem] md:rounded-[2rem] font-black text-[10px] md:text-[11px] uppercase tracking-[0.2em] shadow-2xl hover:bg-primary hover:text-white hover:-translate-y-1 transition-all duration-300">
                        Quick Transition
                    </button>
                </div>
            </div>

            <!-- Tabbed Navigation System -->
            <div class="space-y-12">
                <nav class="flex gap-8 md:gap-12 border-b border-slate-200 px-4 md:px-8 overflow-x-auto no-scrollbar whitespace-nowrap">
                    @foreach(['overview' => 'Overview', 'reviews' => 'Reviews', 'notes' => 'Archive', 'community' => 'Community', 'professors' => 'Faculty'] as $key => $label)
                    <button @click="tab = '{{ $key }}'" 
                            class="pb-6 md:pb-8 text-[10px] md:text-[11px] font-black uppercase tracking-[0.3em] transition-all relative group shrink-0"
                            :class="tab === '{{ $key }}' ? 'text-primary' : 'text-slate-400 hover:text-slate-900'">
                        {{ $label }}
                        <div x-show="tab === '{{ $key }}'" 
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="scale-x-0 opacity-0"
                             x-transition:enter-end="scale-x-100 opacity-100"
                             class="absolute bottom-0 left-0 right-0 h-1 bg-primary rounded-full shadow-[0_0_20px_rgba(37,99,235,0.4)]"></div>
                    </button>
                    @endforeach
                </nav>

                <div class="pb-32 md:pb-20 overflow-hidden">
                    <!-- Tab Content: Overview -->
                    <div x-show="tab === 'overview'" class="grid lg:grid-cols-12 gap-8 md:gap-16" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                        <div class="lg:col-span-8 space-y-12">
                            <div class="space-y-6 md:space-y-8">
                                <h3 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight">Institutional Architecture</h3>
                                <p class="text-slate-500 text-lg md:text-xl font-medium leading-relaxed max-w-3xl">
                                    {{ $college->description }}
                                </p>
                            </div>

                            <!-- Summary Cards Row: Dynamic Placement Intel 🚀 -->
                            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
                                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] md:rounded-[3rem] border border-slate-100 shadow-[0_10px_40px_rgba(0,0,0,0.02)] space-y-3 group hover:border-primary/20 transition-all duration-500">
                                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">Avg Placement</p>
                                    <p class="text-3xl md:text-4xl font-black text-slate-900 group-hover:text-primary transition-colors">{{ $college->placement_stats['avg'] }}</p>
                                    <p class="text-[8px] md:text-[9px] font-bold text-slate-400 italic">
                                        @if($college->placement_stats['count'] > 0)
                                            Based on {{ $college->placement_stats['count'] }} reports
                                        @else
                                            Awaiting Hub Data
                                        @endif
                                    </p>
                                </div>

                                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] md:rounded-[3rem] border border-slate-100 shadow-[0_10px_40px_rgba(0,0,0,0.02)] space-y-3 group hover:border-primary/20 transition-all duration-500">
                                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">Highest Package</p>
                                    <p class="text-3xl md:text-4xl font-black text-slate-900 group-hover:text-emerald-500 transition-colors">{{ $college->placement_stats['max'] }}</p>
                                    <p class="text-[8px] md:text-[9px] font-bold text-slate-400 italic">Peer Verified Max</p>
                                </div>

                                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm space-y-2 group hover:border-primary/20 transition-all duration-300">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lowest Package</p>
                                    <p class="text-2xl md:text-3xl font-bold text-slate-900 leading-none">{{ $college->placement_stats['min'] }}</p>
                                    <p class="text-[9px] font-medium text-slate-400">Institutional Floor</p>
                                </div>
                            </div>

                            <!-- Simplified Review Trigger ✍️ -->
                            <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200 shadow-sm relative overflow-hidden group cursor-pointer" @click="tab = 'reviews'; toggleReviewForm()">
                                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                                    <div class="space-y-4 text-center md:text-left">
                                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/5 rounded-full border border-primary/10">
                                            <span class="text-[10px] font-bold text-primary uppercase tracking-widest">Student Feedback</span>
                                        </div>
                                        <h4 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight leading-tight italic">Help your juniors. <br>Rate this college now.</h4>
                                        <p class="text-slate-500 font-medium text-sm max-w-sm">Share your real experience with the community.</p>
                                    </div>
                                    <button class="bg-primary text-white px-8 py-4 rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all">Write Review ✍️</button>
                                </div>
                            </div>

                            <!-- Institutional Pillars: Student Community 🛡️ -->
                            <div class="grid md:grid-cols-2 gap-8 md:gap-10">
                                <div class="bg-primary rounded-3xl p-8 md:p-10 text-white relative overflow-hidden shadow-xl group">
                                    <div class="relative z-10 space-y-4">
                                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl">🤝</div>
                                        <h5 class="text-xl font-bold leading-tight">Active Students</h5>
                                        <p class="text-sm font-medium text-white/80 leading-relaxed">
                                            {{ number_format($college->users_count) }}+ students from this college are part of our ecosystem.
                                        </p>
                                    </div>
                                </div>
                                <div class="bg-slate-900 rounded-3xl p-8 md:p-10 text-white relative overflow-hidden shadow-xl group">
                                    <div class="relative z-10 space-y-4">
                                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-2xl">📚</div>
                                        <h5 class="text-xl font-bold leading-tight">Study Resources</h5>
                                        <p class="text-sm font-medium text-slate-400 leading-relaxed">
                                            {{ $college->notes()->count() }} Study Notes & {{ $college->professors()->withCount('reviews')->get()->sum('reviews_count') }} Faculty Reviews available.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Side Metrics: College Ratings 🛡️ -->
                        <div class="lg:col-span-4 space-y-12">
                            <div class="bg-white p-8 md:p-10 rounded-2xl border border-slate-100 shadow-sm space-y-8">
                                <h4 class="text-xl font-bold text-slate-900">College Ratings</h4>
                                <div class="space-y-6 md:space-y-8">
                                    @foreach($college->academic_metrics as $metric)
                                    <div class="space-y-2 md:space-y-3">
                                        <div class="flex justify-between items-end">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $metric['label'] }}</span>
                                            <span class="text-[10px] md:text-xs font-bold text-slate-900">{{ $metric['text'] }}</span>
                                        </div>
                                        <div class="h-2 w-full bg-slate-50 border border-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-primary rounded-full transition-all duration-1000" style="width: {{ $metric['percent'] }}%"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Reviews -->
                    <div x-show="tab === 'reviews'" class="space-y-10 md:space-y-16 max-w-5xl mx-auto" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                        <!-- Student Rating Summary 🛡️ -->
                        <div class="bg-white p-8 md:p-10 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-8 mb-12">
                            <div class="flex items-center gap-6">
                                <div class="bg-slate-50 p-6 rounded-2xl flex flex-col items-center border border-slate-100">
                                    <span class="text-4xl md:text-5xl font-bold text-slate-900">{{ number_format($college->reviews()->where('status', 'approved')->avg(DB::raw('(campus_rating + faculty_rating + academic_rating) / 3')), 1) ?: '0.0' }}</span>
                                    <div class="flex gap-0.5 mt-2">
                                        @foreach(range(1,5) as $i)
                                        <span class="text-[10px]">{{ $i <= ($college->reviews()->where('status', 'approved')->avg(DB::raw('(campus_rating + faculty_rating + academic_rating) / 3')) ?? 0) ? '⭐' : '☆' }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Student Reviews</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Based on {{ $college->reviews()->where('status', 'approved')->count() }} ratings</p>
                                </div>
                            </div>

                            @auth
                                @php
                                    $myPendingReview = $college->reviews()->where('user_id', Auth::id())->where('status', 'pending')->first();
                                    $myApprovedReview = $college->reviews()->where('user_id', Auth::id())->where('status', 'approved')->first();
                                @endphp

                                @if($myPendingReview)
                                    <div class="bg-amber-50 text-amber-600 px-6 py-3 rounded-xl border border-amber-100 text-[10px] font-bold uppercase tracking-widest">Processing...</div>
                                @elseif($myApprovedReview)
                                    <div class="bg-emerald-50 text-emerald-600 px-6 py-3 rounded-xl border border-emerald-100 text-[10px] font-bold uppercase tracking-widest">Review Active ✅</div>
                                @else
                                    <button @click="toggleReviewForm()" class="bg-primary text-white px-8 py-4 rounded-xl font-bold text-[11px] uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all">Write a Review ✍️</button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="bg-slate-900 text-white px-8 py-4 rounded-xl font-bold text-[11px] uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all">Sign in to Review</a>
                            @endauth
                        </div>

                        <!-- Smooth Review Form Anchor -->
                        <div id="review-form-anchor"></div>

                        @auth
                        @if(!$myPendingReview && !$myApprovedReview)
                        <div x-show="showReviewForm" 
                             x-collapse
                             class="mb-12">
                            <div class="bg-slate-900 rounded-3xl p-8 md:p-12 space-y-8 relative overflow-hidden shadow-xl">
                                <div class="relative z-10 space-y-8">
                                    <div class="flex justify-between items-start">
                                        <div class="space-y-1">
                                            <h3 class="text-xl md:text-2xl font-bold text-white">Rate this College</h3>
                                            <p class="text-slate-400 text-sm">Your feedback helps thousands of students.</p>
                                        </div>
                                        <button @click="showReviewForm = false" class="text-white/30 hover:text-white transition-colors text-xl">✕</button>
                                    </div>

                                    <form action="{{ route('colleges.rate', $college->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-8 md:space-y-12">
                                        @csrf
                                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-10">
                                            @foreach(['campus' => 'Culture', 'faculty' => 'Faculty', 'academic' => 'Academic'] as $key => $l)
                                            <div class="space-y-4 md:space-y-5">
                                                <label class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] ml-2">{{ $l }}</label>
                                                <select name="{{ $key }}_rating" class="w-full bg-white/5 border border-white/10 rounded-2xl md:rounded-3xl py-4 md:py-5 px-6 md:px-8 text-white focus:ring-2 focus:ring-primary outline-none">
                                                    @foreach(range(5, 1) as $s)
                                                    <option value="{{ $s }}" class="bg-slate-900 text-white">{{ $s }} Stars</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endforeach
                                        </div>

                                        <!-- Placement Intelligence Inputs 💰 -->
                                        <div class="bg-white/5 p-8 md:p-12 rounded-[2.5rem] border border-white/10 space-y-8">
                                            <div class="flex items-center gap-4 mb-2">
                                                <span class="text-xl">💰</span>
                                                <div>
                                                    <h4 class="text-xs font-black text-white uppercase tracking-widest leading-none">Placement Intelligence</h4>
                                                    <p class="text-[9px] text-white/40 font-bold mt-1 italic">Optional but highly encouraged for peer verification (LPA)</p>
                                                </div>
                                            </div>
                                            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
                                                <div class="space-y-4">
                                                    <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] ml-2">Avg Package</label>
                                                    <input type="number" step="0.1" name="average_package" class="w-full bg-white/5 border border-white/10 rounded-xl py-4 px-6 text-white text-sm" placeholder="e.g. 7.5">
                                                </div>
                                                <div class="space-y-4">
                                                    <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] ml-2">Lowest Package</label>
                                                    <input type="number" step="0.1" name="lowest_package" class="w-full bg-white/5 border border-white/10 rounded-xl py-4 px-6 text-white text-sm" placeholder="e.g. 3.5">
                                                </div>
                                                <div class="space-y-4">
                                                    <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] ml-2">Highest Package</label>
                                                    <input type="number" step="0.1" name="highest_package" class="w-full bg-white/5 border border-white/10 rounded-xl py-4 px-6 text-white text-sm" placeholder="e.g. 24.0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-4 md:space-y-5">
                                            <label class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] ml-2">Experience Narrative</label>
                                            <textarea name="comment" rows="4" class="w-full bg-white/5 border border-white/10 rounded-[2rem] md:rounded-[3rem] p-6 md:p-10 text-white placeholder-white/20 focus:ring-2 focus:ring-primary outline-none text-base md:text-lg" placeholder="Describe the day-to-day..."></textarea>
                                        </div>

                                        @if(!Auth::user()->id_card_url)
                                        <div class="space-y-6 bg-white/5 p-8 rounded-[2rem] border border-dashed border-white/10">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-xl">🆔</div>
                                                <div>
                                                    <p class="text-xs font-black text-white uppercase tracking-widest">Identity Attestation Required</p>
                                                    <p class="text-[10px] text-white/40 font-bold mt-1">Institutional reviews require a one-time ID card submission to prevent synthetic feedback.</p>
                                                </div>
                                            </div>
                                            <div class="grid md:grid-cols-2 gap-6">
                                                <div class="space-y-3">
                                                    <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] ml-2">Verification Number</label>
                                                    <input type="text" name="verification_id" class="w-full bg-white/5 border border-white/5 rounded-xl py-4 px-6 text-white text-xs" placeholder="Roll No / Reg ID..." required>
                                                </div>
                                                <div class="space-y-3">
                                                    <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] ml-2">Upload ID Card Image</label>
                                                    <input type="file" name="id_card_image" class="w-full bg-white/5 border border-white/5 rounded-xl py-3 px-6 text-white text-[10px]" required>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <input type="hidden" name="verification_id" value="PREVIOUSLY_VERIFIED">
                                        <div class="flex items-center gap-4 bg-green-500/10 p-6 rounded-2xl border border-green-500/20">
                                            <span class="text-2xl">✅</span>
                                            <div>
                                                <p class="text-xs font-black text-green-400 uppercase tracking-widest leading-none">Identity Verified</p>
                                                <p class="text-[9px] text-green-400/60 font-medium mt-1">Your institutional credentials are on file. No further attestation needed.</p>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="flex justify-end">
                                            <button type="submit" class="w-full md:w-auto bg-white text-slate-900 px-12 h-16 md:h-20 rounded-2xl md:rounded-[2rem] font-black text-[10px] md:text-[11px] uppercase tracking-[0.3em] hover:bg-primary hover:text-white transition-all shadow-xl shadow-white/5">Publish Broadcast</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        </div>
                        @endif
                        @else
                        <div class="bg-slate-950 rounded-[2.5rem] md:rounded-[4rem] p-12 text-center text-white shadow-2xl relative overflow-hidden">
                            <div class="relative z-10">
                                <h3 class="text-2xl font-black mb-4">Want to review {{ $college->name }}?</h3>
                                <p class="text-slate-400 mb-8 max-w-lg mx-auto">Only verified nodes can contribute evaluations. Sign in to share your experience with the verse.</p>
                                <a href="{{ route('login') }}" class="inline-block bg-primary text-white px-10 py-4 rounded-2xl font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all">Sign In to Evaluate</a>
                            </div>
                            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl -mr-32 -mt-32"></div>
                        </div>
                        @endauth

                        <!-- Feed Items -->
                        <div class="space-y-8 md:space-y-10">
                            @forelse($college->reviews as $review)
                            <div class="bg-white p-8 md:p-16 rounded-[2.5rem] md:rounded-[4rem] border border-slate-100 shadow-[0_20px_60px_rgba(0,0,0,0.02)] space-y-8 md:space-y-10">
                                <div class="flex flex-col sm:flex-row justify-between items-start gap-6">
                                    <div class="flex items-center gap-6 md:gap-8">
                                        <a href="{{ route('profile.show', $review->user->username) }}" class="flex-shrink-0">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=F1F5F9&color=2563EB" class="w-16 h-16 md:w-24 md:h-24 rounded-[1.5rem] md:rounded-[2.5rem] hover:scale-105 transition-transform" alt="Avatar">
                                        </a>
                                        <div>
                                            <a href="{{ route('profile.show', $review->user->username) }}" class="font-black text-slate-900 text-xl md:text-2xl tracking-tight text-wrap hover:text-primary transition-colors">{{ $review->user->name }}</a>
                                            <p class="text-[9px] md:text-[10px] font-black text-primary uppercase tracking-[0.2em] mt-1">Verified Node</p>
                                        </div>
                                    </div>
                                    <div class="sm:text-right w-full sm:w-auto flex sm:flex-col justify-between items-center sm:items-end">
                                        <div class="flex items-center gap-2">
                                            <span class="text-amber-400 text-2xl md:text-3xl">⭐</span>
                                            <span class="text-amber-400 text-2xl">⭐</span>
                                            <span class="text-3xl md:text-4xl font-black text-slate-900">{{ number_format(($review->campus_rating + $review->faculty_rating + $review->academic_rating) / 3, 1) }}</span>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest mt-2">{{ $review->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <p class="text-slate-600 font-medium leading-relaxed italic text-lg max-w-4xl selection:bg-primary/10">"{{ $review->comment }}"</p>
                            </div>
                            @empty
                            <div class="text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 font-black uppercase tracking-widest text-xs italic">Awaiting first verification...</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab Content: Community -->
                    <div x-show="tab === 'community'" class="space-y-10 md:space-y-16 max-w-5xl mx-auto" x-transition:enter="transition ease-out duration-300">
                        <!-- Quick Post -->
                        @auth
                        <div class="bg-white p-8 md:p-12 rounded-[2.5rem] md:rounded-[4rem] border border-slate-100 space-y-8">
                            <div class="flex items-center gap-6">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-[1.2rem] md:rounded-[2rem] bg-slate-50 flex items-center justify-center text-2xl md:text-3xl">✍️</div>
                                <div>
                                    <h3 class="text-xl md:text-2xl font-black text-slate-900 leading-tight">Post to {{ Str::limit($college->name, 20) }}</h3>
                                    <p class="text-[9px] md:text-[10px] font-black text-primary uppercase tracking-[0.2em]">Institutional Synergy</p>
                                </div>
                            </div>
                            <form action="{{ route('community.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="college_id" value="{{ $college->id }}">
                                <input type="hidden" name="category" value="Campus Discussion">
                                <input type="text" name="title" class="w-full bg-slate-50 border-none rounded-xl md:rounded-2xl py-4 md:py-5 px-6 md:px-8 text-sm" placeholder="Post title...">
                                <textarea name="content" rows="3" class="w-full bg-slate-50 border-none rounded-2xl md:rounded-3xl py-5 md:py-6 px-6 md:px-8 text-sm" placeholder="Share with campus..."></textarea>
                                <button type="submit" class="w-full md:w-auto bg-slate-900 text-white px-8 py-4 rounded-xl font-black text-[9px] uppercase tracking-widest">Broadcast</button>
                            </form>
                        </div>
                        @else
                        <div class="bg-primary/5 p-12 rounded-[2.5rem] md:rounded-[4rem] border border-primary/10 text-center">
                            <h3 class="text-xl font-black text-primary mb-4">Campus Access Locked</h3>
                            <p class="text-slate-600 mb-8 max-w-md mx-auto font-medium">You need to be signed in as a student to participate in the {{ $college->name }} campus discussion.</p>
                            <a href="{{ route('login') }}" class="inline-block bg-primary text-white px-10 py-4 rounded-2xl font-black shadow-lg shadow-primary/20 hover:scale-105 transition-all">Sign In to Participate</a>
                        </div>
                        @endauth

                        <!-- Posts Feed -->
                        <div class="space-y-8">
                            @forelse($college->posts as $post)
                            <div class="bg-white p-8 md:p-16 rounded-[3rem] md:rounded-[4.5rem] border border-slate-100">
                                <div class="flex items-center gap-4 md:gap-8 mb-8">
                                    <a href="{{ route('profile.show', $post->user->username) }}" class="flex-shrink-0">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&background=F1F5F9" class="w-12 h-12 md:w-20 md:h-20 rounded-[1.2rem] md:rounded-[2rem] hover:scale-105 transition-transform">
                                    </a>
                                    <div>
                                        <a href="{{ route('profile.show', $post->user->username) }}" class="font-black text-slate-900 text-lg md:text-xl hover:text-primary transition-colors">{{ $post->user->name }}</a>
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <h5 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">{{ $post->title }}</h5>
                                    <p class="text-slate-500 font-medium leading-relaxed text-base md:text-lg">{{ $post->content }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-slate-400 py-20 italic">No broadcasts yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab Content: Archive -->
                    <div x-show="tab === 'notes'" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10" x-transition:enter="transition ease-out duration-300">
                        @foreach($college->notes as $note)
                        <a href="{{ route('notes.show', $note->id) }}" class="bg-white p-8 md:p-12 rounded-[2.5rem] md:rounded-[4rem] border border-slate-100 shadow-sm hover:-translate-y-2 transition-all">
                            <div class="w-14 h-14 md:w-20 md:h-20 rounded-[1.5rem] md:rounded-[2rem] bg-slate-50 flex items-center justify-center text-2xl md:text-4xl mb-6 md:mb-8">📄</div>
                            <h4 class="text-xl md:text-2xl font-black text-slate-900 leading-tight mb-4">{{ $note->title }}</h4>
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($note->user->name) }}" class="w-6 h-6 rounded-lg">
                                <span class="text-[9px] md:text-[11px] font-bold text-slate-400 italic">By {{ $note->user->name }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <!-- Tab Content: Faculty -->
                    <div x-show="tab === 'professors'" class="grid sm:grid-cols-1 md:grid-cols-2 gap-8 md:gap-12" x-transition:enter="transition ease-out duration-300">
                        @foreach($college->professors as $prof)
                        <div class="bg-white p-8 md:p-12 rounded-[2.5rem] md:rounded-[4rem] border border-slate-100 flex items-center justify-between gap-6 group transition-all">
                            <div class="flex items-center gap-6 md:gap-8">
                                <div class="w-16 h-16 md:w-24 md:h-24 rounded-[1.5rem] md:rounded-[2.5rem] bg-slate-50 flex items-center justify-center text-2xl md:text-4xl">👨‍🏫</div>
                                <div>
                                    <h4 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">{{ $prof->name }}</h4>
                                    <p class="text-[9px] md:text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ Str::limit($prof->department, 15) }}</p>
                                </div>
                            </div>
                            <div class="text-right flex flex-col items-end">
                                <span class="text-amber-400 text-xl md:text-2xl">⭐</span>
                                <span class="text-2xl md:text-3xl font-black text-slate-900 tracking-tighter">{{ number_format($prof->reviews_avg_rating ?: 4.9, 1) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-verse-layout>
