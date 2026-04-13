<x-app-layout>
    <div class="space-y-10 pb-20">
        <!-- Dashboard Hero -->
        <div class="relative overflow-hidden bg-primary rounded-[2.5rem] p-10 shadow-2xl shadow-primary/20">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="text-white space-y-4 max-w-xl">
                    <h1 class="text-4xl md:text-5xl font-black leading-tight">
                        Hey {{ Auth::user()->name }}, <br/>Ready to level up? 🚀
                    </h1>
                    <p class="text-primary-100/80 text-lg font-medium">
                        Your academic progress is looking great this week. You've earned **120 credits** from new note contributions!
                    </p>
                    <div class="pt-4 flex gap-4">
                        @php $resumeRoute = $myNotes->isNotEmpty() ? route('notes.show', $myNotes->first()->id) : route('notes.index'); @endphp
                        <a href="{{ $resumeRoute }}" class="bg-white text-primary px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-black/10 hover:scale-105 transition-transform">
                            Resume Learning
                        </a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <!-- Illustration Placeholder or 3D Object style -->
                    <div class="w-64 h-64 bg-white/10 rounded-full border-4 border-white/20 flex items-center justify-center backdrop-blur-3xl relative">
                        <div class="absolute inset-0 animate-pulse bg-white/5 rounded-full"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Metric Grid -->
        <div class="grid md:grid-cols-3 gap-8">
            <div class="glass p-8 rounded-[2rem] shadow-glass border-white/40 flex items-center gap-6">
                <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Total Karma</h4>
                    <p class="text-3xl font-black text-slate-800">{{ number_format(Auth::user()->karma ?? 0) }}</p>
                </div>
            </div>

            <div class="glass p-8 rounded-[2rem] shadow-glass border-white/40 flex items-center gap-6">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-primary shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Notes Shared</h4>
                    <p class="text-3xl font-black text-slate-800">{{ Auth::user()->notes()->count() }}</p>
                </div>
            </div>

            @php
                $karma = Auth::user()->karma ?? 0;
                $rank = 'Verse Novice';
                $rankColor = 'text-slate-500';
                $rankBg = 'bg-slate-100';
                
                if ($karma >= 1000) {
                    $rank = 'Verse Legend';
                    $rankColor = 'text-amber-600';
                    $rankBg = 'bg-amber-100';
                } elseif ($karma >= 500) {
                    $rank = 'Knowledge Vanguard';
                    $rankColor = 'text-violet-600';
                    $rankBg = 'bg-violet-100';
                } elseif ($karma >= 100) {
                    $rank = 'Academic Scholar';
                    $rankColor = 'text-primary';
                    $rankBg = 'bg-blue-100';
                }
            @endphp

            <div class="glass p-8 rounded-[2rem] shadow-glass border-white/40 flex items-center gap-6 group">
                <div class="w-16 h-16 {{ $rankBg }} rounded-2xl flex items-center justify-center {{ $rankColor }} shadow-sm group-hover:rotate-12 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Reputation</h4>
                    <p class="text-xl font-black {{ $rankColor }}">{{ $rank }}</p>
                </div>
            </div>
        </div>

    <!-- Subjects Grid -->
        <div class="space-y-6">
            <div class="flex justify-between items-center px-2">
                <h3 class="text-2xl font-extrabold text-secondary">Broaden your knowledge</h3>
                <a href="{{ route('notes.index') }}" class="text-primary font-bold hover:underline">See all subjects</a>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-6">
                @php
                    $icons = ['🎨', '💻', '⚛️', '📐', '🧬', '🏛️'];
                    $colors = ['bg-pink-100/50', 'bg-blue-100/50', 'bg-indigo-100/50', 'bg-amber-100/50', 'bg-emerald-100/50', 'bg-rose-100/50'];
                @endphp

                @foreach ($subjects as $index => $sub)
                    <a href="{{ route('notes.index', ['subject_id' => $sub->id]) }}" class="flex flex-col items-center gap-4 group">
                        <div class="w-full aspect-square {{ $colors[$index % 6] }} rounded-[2.5rem] flex items-center justify-center text-4xl group-hover:scale-110 transition-transform shadow-sm group-hover:shadow-xl border border-white">
                            {{ $icons[$index % 6] }}
                        </div>
                        <span class="font-bold text-slate-700 text-center text-sm truncate w-full px-2">{{ $sub->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-10">
            <!-- Recent Activity / Campus Notes -->
            <div class="glass p-10 rounded-[2.5rem] shadow-glass border-white/40">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-extrabold text-secondary">Campus Notes</h3>
                    <div class="flex gap-2">
                         <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Priority Feed</span>
                    </div>
                </div>
                
                <div class="space-y-6">
                    @forelse ($myNotes as $note)
                    <div class="flex gap-6 items-start group cursor-pointer" onclick="window.location='{{ route('notes.show', $note->id) }}'">
                        <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-xl shrink-0 group-hover:bg-primary group-hover:text-white transition-colors border border-slate-100">
                            📄
                        </div>
                        <div class="space-y-1">
                            <p class="font-bold text-slate-800 group-hover:text-primary transition-colors text-sm">{{ $note->title }}</p>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Verified by {{ $note->college->name ?? 'Community' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <p class="text-slate-400 font-bold italic text-sm">No notes from your campus yet. <br/>Be the first to share!</p>
                        <a href="{{ route('notes.index') }}" class="mt-4 inline-block text-primary font-black text-xs uppercase tracking-widest">Upload Now</a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Contributors -->
            <div class="glass p-10 rounded-[2.5rem] shadow-glass border-white/40">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-extrabold text-secondary">Top Performers</h3>
                    <a href="{{ route('leaderboard.index') }}" class="text-primary font-bold">Leaderboard</a>
                </div>

                <div class="space-y-6">
                    @forelse ($topPerformers as $performer)
                    <div class="flex items-center justify-between p-4 rounded-3xl bg-white/40 border border-white hover:bg-white transition-colors cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($performer->name ?? 'User') }}&background=random" class="w-12 h-12 rounded-2xl shadow-sm"/>
                            <p class="font-bold text-slate-800">{{ $performer->name ?? 'Shadow User' }}</p>
                        </div>
                        <div class="bg-primary/10 text-primary px-4 py-1.5 rounded-xl font-black text-sm group-hover:bg-primary group-hover:text-white">
                            {{ $performer->notes_count }} Shares
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-slate-400 font-bold py-10">No rankings yet.</p>
                    @endforelse
                </div>

                <!-- Career Match -->
                <div class="mt-12">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-2xl font-extrabold text-secondary">Career Matching</h3>
                        <a href="{{ route('jobs.index') }}" class="text-primary font-bold">Board</a>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($matchedJobs as $job)
                        <a href="{{ route('jobs.show', $job->id) }}" class="block glass p-5 rounded-[2rem] hover:bg-white transition-all transform hover:-translate-y-1 shadow-sm border-white group relative overflow-hidden">
                            @if($job->target_college_id)
                            <div class="absolute -top-1 -right-1">
                                <span class="bg-amber-500 w-3 h-3 rounded-full border-2 border-white absolute shadow-lg shadow-amber-500/50"></span>
                            </div>
                            @endif
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary font-black group-hover:bg-primary group-hover:text-white transition-colors">
                                    @if($job->type === 'Internship') 🎓 @else 💼 @endif
                                </div>
                                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">{{ $job->created_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="text-sm font-black text-slate-900 group-hover:text-primary transition-colors">{{ $job->title }}</h4>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">{{ $job->recruiter->company_name ?? 'Partner Agency' }}</p>
                        </a>
                        @empty
                        <div class="p-8 text-center bg-slate-50/50 rounded-[2rem] border-2 border-dashed border-slate-200">
                            <p class="text-xs font-bold text-slate-400">Scanning for broadcasts...</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
