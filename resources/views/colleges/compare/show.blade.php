<x-verse-layout>
    @section('title', $colleges->pluck('name')->implode(' vs ') . ' | College Battle - MyCollegeVerse')

    <div class="max-w-7xl mx-auto px-4 md:px-8 space-y-12 pb-24 pt-8 md:pt-12">
        <!-- Battle Header -->
        <div class="relative bg-slate-900 rounded-[3.5rem] p-8 md:p-20 overflow-hidden shadow-2xl">
            <div class="relative z-10 text-center space-y-6">
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.4em]">The Institutional Showdown</p>
                <h1 class="text-3xl md:text-6xl font-black text-white leading-tight">
                    @foreach($colleges as $index => $college)
                        {{ $college->name }}
                        @if(!$loop->last) <span class="text-primary italic mx-2 md:mx-4">vs</span> @endif
                    @endforeach
                </h1>
                <p class="text-slate-400 text-lg md:text-xl font-medium max-w-2xl mx-auto leading-relaxed italic">
                    Side-by-side analytics powered by verified student data. Choose your next home wisely.
                </p>
            </div>
            <!-- Decorative blur -->
            <div class="absolute -left-20 -top-20 w-96 h-96 bg-primary/20 rounded-full blur-[100px]"></div>
            <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px]"></div>
        </div>

        <!-- The Battle Board -->
        <div class="grid @if($colleges->count() == 3) lg:grid-cols-3 @else lg:grid-cols-2 @endif gap-8">
            @foreach($colleges as $college)
                <div class="bg-white rounded-[4rem] border border-slate-100 p-8 md:p-12 shadow-sm relative overflow-hidden group">
                    <!-- College Header -->
                    <div class="space-y-8 relative z-10">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 md:w-24 md:h-24 bg-slate-50 rounded-[1.5rem] md:rounded-[2.5rem] flex items-center justify-center text-4xl shadow-sm">
                                🏛️
                            </div>
                            <div class="space-y-1">
                                <h2 class="text-2xl md:text-3xl font-black text-slate-900 group-hover:text-primary transition-colors leading-tight">{{ $college->name }}</h2>
                                <p class="text-xs font-bold text-slate-400 tracking-wider">{{ $college->location }}, {{ $college->state }}</p>
                            </div>
                        </div>

                        <!-- Core Stats Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Average Placement -->
                            @php 
                                $isWinnerPkg = $colleges->max(fn($c) => (float) filter_var($c->placement_stats['avg'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) == (float) filter_var($college->placement_stats['avg'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $isWinnerRating = $colleges->max('rating') == $college->rating;
                            @endphp
                            
                            <div class="bg-slate-50 p-6 rounded-[2.5rem] border {{ $isWinnerPkg ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-100' }} group-hover:scale-[1.02] transition-transform">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Avg Package</p>
                                <p class="text-xl font-black text-slate-900">{{ $college->placement_stats['avg'] }}</p>
                                @if($isWinnerPkg && $college->placement_stats['has_data'])
                                    <span class="text-[8px] font-bold text-emerald-600 uppercase tracking-widest">🏆 Leader</span>
                                @endif
                            </div>

                            <div class="bg-slate-50 p-6 rounded-[2.5rem] border {{ $isWinnerRating ? 'border-amber-200 bg-amber-50/30' : 'border-slate-100' }}">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Hub Rating</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-xl font-black text-slate-900">{{ number_format($college->rating, 1) }}</span>
                                    <span class="text-amber-400">⭐</span>
                                </div>
                                @if($isWinnerRating)
                                    <span class="text-[8px] font-bold text-amber-600 uppercase tracking-widest">🎖️ Preferred</span>
                                @endif
                            </div>
                        </div>

                        <!-- Academic Breakdown -->
                        <div class="space-y-6">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] italic">Verse Intelligence</h3>
                            <div class="space-y-4">
                                @foreach($college->academic_metrics as $metric)
                                <div class="space-y-2">
                                    <div class="flex justify-between items-end">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $metric['label'] }}</span>
                                        <span class="text-[10px] font-black text-slate-900">{{ $metric['text'] }}</span>
                                    </div>
                                    <div class="h-2 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100">
                                        <div class="h-full bg-primary rounded-full" style="width: {{ $metric['percent'] }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Career Destinations -->
                        <div class="bg-slate-900 p-8 rounded-[3rem] text-white space-y-6 relative overflow-hidden">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-white/40">Career Paths</h3>
                            @if($college->career_destinations->isNotEmpty())
                                <div class="space-y-4">
                                    @foreach($college->career_destinations as $dest)
                                    <div class="flex justify-between items-center bg-white/5 p-3 rounded-2xl border border-white/5">
                                        <span class="text-[10px] font-bold">{{ $dest['label'] }}</span>
                                        <span class="text-[10px] font-black text-primary">{{ $dest['percent'] }}%</span>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-[10px] font-bold text-white/30 italic">No destination data yet...</p>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-4">
                            <a href="{{ route('colleges.show', $college->slug) }}" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-widest text-center hover:bg-primary transition-all">
                                Visit Campus
                            </a>
                            <a href="{{ route('colleges.batchmates', ['college' => $college->slug, 'year' => 2024]) }}" class="w-full bg-slate-50 text-slate-500 py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-widest text-center hover:bg-slate-100 transition-all border border-slate-100">
                                See Batchmates
                            </a>
                        </div>
                    </div>

                    <!-- Watermark -->
                    <div class="absolute -right-10 -bottom-10 opacity-[0.03] group-hover:opacity-10 transition-opacity">
                        <span class="text-[12rem] font-black italic">{{ $loop->iteration }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Social Share Node -->
        <div class="bg-indigo-600 rounded-[3rem] p-12 text-center text-white shadow-2xl overflow-hidden relative">
            <div class="relative z-10 space-y-6">
                <h3 class="text-3xl font-black italic">Was this battle helpful?</h3>
                <p class="text-indigo-100 font-medium max-w-lg mx-auto">Share this comparison with your school friends who are confused about these colleges!</p>
                <div class="flex justify-center gap-4">
                    <button onclick="window.navigator.share({title: 'College Battle!', url: window.location.href})" class="bg-white text-indigo-600 px-10 py-5 rounded-3xl font-black text-xs uppercase tracking-widest shadow-xl hover:-translate-y-1 transition-all">
                        Broadcast to WhatsApp
                    </button>
                </div>
            </div>
            <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -ml-32 -mt-32"></div>
        </div>
    </div>
</x-verse-layout>
