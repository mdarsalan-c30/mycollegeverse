<x-app-layout>
    @section('title', $seoTitle ?? $professor->name . ' - Faculty Review | MyCollegeVerse')
    @section('meta_description', $seoDescription ?? 'Read reviews and ratings for professor ' . $professor->name . ' (' . $professor->department . ') at ' . (optional($professor->college)->name ?? 'Campus') . '. Get insights into teaching style and academic results.')

    @push('structured-data')
    <script type="application/ld+json">
        {!! json_encode($schema ?? []) !!}
    </script>
    @endpush
<div class="space-y-8 pb-24">

    {{-- ═══════════════════════════════════════════════════
         PROFESSOR HERO HEADER
    ═══════════════════════════════════════════════════ --}}
    <div class="glass rounded-[3rem] overflow-hidden shadow-glass border-white/60 relative">
        {{-- Cover Banner --}}
        <div class="h-36 bg-gradient-to-br from-blue-500/20 via-violet-400/15 to-indigo-500/20 relative">
            <div class="absolute inset-0" style="background: radial-gradient(ellipse at 30% 50%, rgba(99,102,241,0.18) 0%, transparent 70%), radial-gradient(ellipse at 80% 50%, rgba(59,130,246,0.12) 0%, transparent 60%);"></div>
        </div>

        <div class="px-8 pb-8 relative">
            {{-- Avatar overlapping banner --}}
            <div class="flex flex-col md:flex-row md:items-end gap-6 -mt-12">
                <img src="{{ $professor->profile_photo_url }}"
                     class="w-28 h-28 md:w-32 md:h-32 rounded-[1.75rem] shadow-2xl border-[3px] border-white ring-4 ring-primary/10 flex-shrink-0 object-cover" />

                <div class="flex-1 md:pb-2 space-y-2">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight">{{ $professor->name }}</h1>
                            <p class="text-slate-500 font-semibold mt-1 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                {{ $professor->department }} &bull; {{ optional($professor->college)->name ?? 'Institutional Hub' }}
                            </p>
                        </div>

                        {{-- Rating Badge --}}
                        @php $avg = $professor->reviews->avg('rating') ?? 0; $total = $professor->reviews->count(); @endphp
                        <div class="flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-sm border border-slate-100">
                            <div class="text-center">
                                <div class="text-3xl font-black text-slate-900 leading-none">{{ number_format($avg, 1) }}</div>
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">/ 5.0</div>
                            </div>
                            <div>
                                <div class="flex gap-0.5 mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= round($avg) ? 'text-amber-400 fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M10 1l2.6 6.3h6.6l-5.3 4.1 2 6.6-5.9-4.5-5.9 4.5 2-6.6-5.3-4.1h6.6z"/></svg>
                                    @endfor
                                </div>
                                <div class="text-[10px] font-bold text-slate-400">{{ $total }} {{ Str::plural('review', $total) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        @if($professor->designation ?? null)
                            <span class="px-3 py-1 bg-primary/10 text-primary text-xs font-black rounded-full">{{ $professor->designation }}</span>
                        @endif
                        <span class="px-3 py-1 bg-violet-50 text-violet-700 text-xs font-black rounded-full">{{ optional($professor->college)->name ?? 'Campus Verse' }}</span>
                        @if($avg >= 4.5)
                            <span class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-black rounded-full">⭐ Top Rated</span>
                        @endif

                        {{-- CTA: Rate This Professor --}}
                        @if(Auth::check() && Auth::user()->college_id === $professor->college_id)
                        <a href="#rate-form"
                           class="ml-auto inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-xs font-black shadow-lg shadow-primary/20 hover:bg-primary/90 hover:scale-[1.02] active:scale-[0.98] transition-all"
                           style="scroll-behavior:smooth">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Record Faculty Intel
                        </a>
                        @elseif(Auth::check())
                        <div class="ml-auto px-4 py-2 bg-slate-100 text-slate-400 text-[10px] font-black rounded-xl uppercase tracking-widest border border-slate-200">
                            Registry Lock: Different Hub
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         MAIN CONTENT: TWO COLUMN LAYOUT
    ═══════════════════════════════════════════════════ --}}
    <div class="grid lg:grid-cols-[1fr_380px] gap-8 items-start">

        {{-- LEFT: Reviews --}}
        <div class="space-y-6">
            {{-- Section Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-black text-slate-900">Faculty Intel Feed</h2>
                    <p class="text-xs text-slate-400 font-bold mt-0.5">Based on {{ $total }} verified observations</p>
                </div>
                <span class="px-4 py-1.5 bg-slate-100 text-slate-600 text-xs font-black rounded-full">{{ $total }} signals</span>
            </div>

            {{-- AGGREGATE INTEL SUMMARY 📡 --}}
            @if($total > 0)
            <div class="glass p-8 rounded-[2.5rem] border-primary/10 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                
                <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    Aggregate Verse Intel
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {{-- Common Tags --}}
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Interaction Style</p>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $allTags = [];
                                foreach($professor->reviews as $r) { if($r->tags) $allTags = array_merge($allTags, $r->tags); }
                                $tagCounts = array_count_values($allTags);
                                arsort($tagCounts);
                                $topTags = array_slice($tagCounts, 0, 4);
                            @endphp
                            @forelse($topTags as $tag => $count)
                                <span class="px-3 py-1.5 bg-white border border-slate-100 rounded-xl text-[10px] font-black text-secondary shadow-sm">#{{ $tag }}</span>
                            @empty
                                <span class="text-[10px] italic text-slate-300">No tags synchronized yet.</span>
                            @endforelse
                        </div>
                    </div>

                    {{-- Avg Internal Difficulty --}}
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Internal Difficulty</p>
                        @php $avgDiff = $professor->reviews->avg('internal_difficulty') ?? 0; @endphp
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-black text-slate-900 leading-none">{{ number_format($avgDiff, 1) }}</span>
                            <span class="text-[10px] font-bold text-slate-400 mb-1">/ 5.0</span>
                        </div>
                        <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden w-32">
                            <div class="bg-indigo-500 h-full rounded-full" style="width: {{ $avgDiff * 20 }}%"></div>
                        </div>
                    </div>

                    {{-- Most Cited Units --}}
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Exam Emphasis</p>
                        @php
                            $units = $professor->reviews->pluck('unit_focus')->filter()->unique()->take(2);
                        @endphp
                        <div class="space-y-2">
                            @forelse($units as $u)
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                                    <span class="text-[10px] font-black text-slate-700 uppercase">{{ $u }}</span>
                                </div>
                            @empty
                                <span class="text-[10px] italic text-slate-300">Scanning exam patterns...</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Review Cards --}}
            @forelse($professor->reviews->sortByDesc('created_at') as $review)
            @if($review->user)
            <div class="glass p-6 md:p-8 rounded-[2rem] border-white/80 shadow-sm hover:shadow-md transition-shadow group">
                {{-- Reviewer --}}
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.show', optional($review->user)->username) }}" class="flex-shrink-0">
                            <img src="{{ $review->user->profile_photo_url }}"
                                 class="w-11 h-11 rounded-[0.85rem] border-2 border-white shadow-sm object-cover group-hover:scale-105 transition-transform" />
                        </a>
                        <div>
                            <a href="{{ route('profile.show', optional($review->user)->username) }}" class="text-sm font-black text-slate-900 hover:text-primary transition-colors">{{ $review->user->name }}</a>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[10px] text-slate-400 font-bold">{{ $review->created_at->diffForHumans() }}</span>
                                @if($review->created_at->gt(now()->subDays(7)))
                                    <span class="px-1.5 py-0.5 bg-green-100 text-green-700 text-[9px] font-black rounded-full">NEW</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Star Rating --}}
                    <div class="flex flex-col items-end gap-1 flex-shrink-0">
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-400 fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M10 1l2.6 6.3h6.6l-5.3 4.1 2 6.6-5.9-4.5-5.9 4.5 2-6.6-5.3-4.1h6.6z"/></svg>
                            @endfor
                        </div>
                        <span class="text-[10px] font-black text-slate-500">{{ $review->rating }}.0 / 5.0</span>
                    </div>
                </div>

                {{-- Review Body --}}
                <blockquote class="text-sm text-slate-700 font-medium leading-relaxed border-l-[3px] border-primary/30 pl-4 italic">
                    "{{ $review->comment }}"
                </blockquote>

                {{-- Review Metadata chips --}}
                <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-slate-50">
                    <span class="px-2.5 py-1 {{ $review->rating >= 4 ? 'bg-green-50 text-green-700' : ($review->rating >= 3 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }} text-[10px] font-black rounded-lg">
                        {{ $review->rating >= 4 ? '👍 High Fidelity' : ($review->rating >= 3 ? '😐 Neutral' : '👎 Low Fidelity') }}
                    </span>
                    
                    @if($review->unit_focus)
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black rounded-lg">🎯 Unit: {{ $review->unit_focus }}</span>
                    @endif

                    @if($review->internal_difficulty)
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-lg">📈 Diff: {{ $review->internal_difficulty }}/5</span>
                    @endif

                    @if($review->tags)
                        @foreach($review->tags as $tag)
                            <span class="px-2.5 py-1 bg-white border border-slate-100 text-secondary text-[10px] font-black rounded-lg shadow-sm">#{{ $tag }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif
            @empty
            <div class="text-center py-20 glass rounded-[2rem] border-white/80">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">🎓</div>
                <h3 class="font-black text-slate-800 text-lg mb-1">No Reviews Yet</h3>
                <p class="text-slate-400 text-sm font-bold">Be the first to share your experience with this professor.</p>
            </div>
            @endforelse
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="space-y-6">

            {{-- Performance Metrics --}}
            <div class="glass p-6 rounded-[2rem] border-white/80 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Performance Metrics
                </h3>

                @php
                    $metrics = [
                        ['label' => 'Overall Rating', 'score' => $avg, 'max' => 5, 'color' => 'bg-primary'],
                        ['label' => 'Would Recommend', 'score' => $professor->reviews->where('rating', '>=', 4)->count() / max($total, 1) * 5, 'max' => 5, 'color' => 'bg-green-500'],
                        ['label' => '4+ Star Reviews', 'score' => $professor->reviews->where('rating', '>=', 4)->count() / max($total, 1) * 5, 'max' => 5, 'color' => 'bg-amber-400'],
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach($metrics as $metric)
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-xs font-bold text-slate-600">{{ $metric['label'] }}</span>
                            <span class="text-xs font-black text-slate-900">{{ number_format($metric['score'], 1) }}/5</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="{{ $metric['color'] }} h-full rounded-full transition-all duration-700"
                                 style="width: {{ min(($metric['score'] / $metric['max']) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Rating Distribution --}}
                <div class="mt-6 pt-5 border-t border-slate-50 space-y-2">
                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Rating Distribution</p>
                    @for($star = 5; $star >= 1; $star--)
                        @php $count = $professor->reviews->where('rating', $star)->count(); $pct = $total ? ($count/$total)*100 : 0; @endphp
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-slate-500 w-4">{{ $star }}★</span>
                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="bg-amber-400 h-full rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 w-6 text-right">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Student Tip Card --}}
            @if($total > 0 && $professor->reviews->sortByDesc('rating')->first())
            <div class="bg-amber-50 border border-amber-100 p-6 rounded-[2rem]">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-lg">💡</span>
                    <h3 class="text-xs font-black text-amber-900 uppercase tracking-widest">Student Tip</h3>
                </div>
                <p class="text-sm text-amber-800 font-medium leading-relaxed italic">
                    "{{ Str::limit($professor->reviews->sortByDesc('rating')->first()->comment, 150) }}"
                </p>
                <p class="text-[10px] text-amber-600 font-black mt-2">— Highest rated review</p>
            </div>
            @endif

            {{-- Write Review Form --}}
            <div id="rate-form" class="glass p-6 rounded-[2rem] border-white/80 shadow-sm scroll-mt-8">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Rate {{ explode(' ', $professor->name)[0] }}
                </h3>

                @auth
                @if(Auth::user()->college_id === $professor->college_id)
                <form action="{{ route('professors.rate', $professor->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Star Rating Selector --}}
                    <div x-data="{ rating: 0 }" class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Global Fidelity Rating</label>
                        <div class="flex gap-2">
                            @for($s = 1; $s <= 5; $s++)
                            <button type="button" @click="rating = {{ $s }}"
                                    class="text-3xl transition-transform hover:scale-110 active:scale-95"
                                    :class="rating >= {{ $s }} ? 'filter-none' : 'grayscale opacity-30'">⭐</button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" x-model="rating" required />
                    </div>

                    {{-- Interaction Tags --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Interaction Tags (Multiple Required)</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Practical Oriented', 'Strict Attendance', 'Fair Evaluator', 'Research Depth', 'Digital Native', 'Project Focus', 'Notes Heavy', 'Industry Context'] as $tag)
                                <label class="cursor-pointer group">
                                    <input type="checkbox" name="tags[]" value="{{ $tag }}" class="hidden peer">
                                    <span class="px-3 py-2 border border-slate-100 rounded-xl text-[10px] font-black text-slate-400 peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary transition-all inline-block group-hover:bg-slate-50">#{{ $tag }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Internal Intel --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Internal Difficulty</label>
                            <select name="internal_difficulty" class="w-full h-11 bg-white border border-slate-100 rounded-xl px-4 text-[10px] font-black text-slate-600 appearance-none">
                                <option value="1">1 (Easy)</option>
                                <option value="2">2 (Moderate)</option>
                                <option value="3">3 (Average)</option>
                                <option value="4">4 (Hard)</option>
                                <option value="5">5 (Extreme)</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Exam Unit Focus</label>
                            <input type="text" name="unit_focus" placeholder="e.g. Unit 3 & 4" class="w-full h-11 bg-white border border-slate-100 rounded-xl px-4 text-[10px] font-black text-slate-600 placeholder-slate-300" />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Academic Observations</label>
                        <textarea name="comment" required rows="4"
                                  placeholder="Observations on teaching style, research depth, or internal patterns..."
                                  class="w-full bg-white border border-slate-100 rounded-xl p-4 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 resize-none placeholder-slate-300"></textarea>
                    </div>

                    @if(!Auth::user()->id_card_url)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">🆔</span>
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Identity Manifestation</p>
                        </div>
                        <p class="text-[9px] text-slate-400 leading-tight font-medium">Verify your ID once to synchronize your academic signals at a higher priority.</p>
                        <input type="file" name="id_card_image" class="w-full text-[9px] text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:bg-slate-200 file:text-slate-600 hover:file:bg-slate-300" />
                    </div>
                    @endif

                    <button type="submit"
                            class="w-full bg-primary text-white h-12 rounded-xl font-black text-sm shadow-xl shadow-primary/20 hover:bg-primary/90 hover:scale-[1.01] active:scale-[0.99] transition-all">
                        Transmit Intel to Council
                    </button>
                </form>
                @else
                <div class="text-center py-10 bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-xl mx-auto mb-4 border border-slate-100 shadow-sm">🔒</div>
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-2">Institutional Lock</h4>
                    <p class="text-[10px] text-slate-500 font-medium px-4">You are currently in the <b>{{ Auth::user()->college->name ?? 'External' }}</b> Hub. You can only record signals for faculty within your own institutional node.</p>
                </div>
                @endif
                @else
                <div class="text-center py-10">
                    <p class="text-slate-500 font-medium mb-6 text-sm italic">Join the Multiverse to synchronize faculty intel.</p>
                    <a href="{{ route('login') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-2xl font-black text-xs shadow-xl shadow-primary/20 hover:scale-110 transition-all italic">Launch Identity</a>
                </div>
                @endauth
            </div>

            {{-- Back Link --}}
            <a href="{{ route('professors.index') }}"
               class="flex items-center gap-2 text-slate-500 hover:text-primary font-bold text-sm transition-colors group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Professors Directory
            </a>
        </div>
    </div>
</div>
</x-app-layout>
