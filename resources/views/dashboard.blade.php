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
                        @if($weeklyCredits > 0)
                            Your academic progress is looking great this week. You've earned **{{ number_format($weeklyCredits) }} credits** from new note contributions!
                        @else
                            Start sharing your academy insights today! You can earn **50 credits** for every high-quality note verified in the Multiverse.
                        @endif
                    </p>
                    <div class="pt-4 flex flex-wrap gap-4">
                        @php $resumeRoute = $myNotes->isNotEmpty() ? route('notes.show', $myNotes->first()->id) : route('notes.index'); @endphp
                        <a href="{{ $resumeRoute }}" class="bg-white text-primary px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-black/10 hover:scale-105 transition-transform">
                            Resume Learning
                        </a>

                        @if(!Auth::user()->career_role)
                        <form action="{{ route('dashboard') }}" method="POST" class="flex gap-2">
                            @csrf
                            <select name="career_role" onchange="this.form.submit()" class="bg-primary-600/50 backdrop-blur-md border border-white/20 text-white px-6 py-3.5 rounded-2xl font-bold text-sm outline-none focus:ring-2 focus:ring-white">
                                <option value="">Set Career Goal 🎯</option>
                                <option value="Software Engineer">Software Engineer</option>
                                <option value="Data Scientist">Data Scientist</option>
                                <option value="Product Manager">Product Manager</option>
                                <option value="UI/UX Designer">UI/UX Designer</option>
                                <option value="MBA Aspirant">MBA Aspirant</option>
                                <option value="Startup Founder">Startup Founder</option>
                                <option value="Civil Services">Civil Services</option>
                            </select>
                        </form>
                        @endif
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
        <div class="grid md:grid-cols-4 gap-8">
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

            <div class="glass p-8 rounded-[2rem] shadow-glass border-white/40 flex items-center gap-6 group">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm transition-transform group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Discovery</h4>
                    <form action="{{ route('profile.batch.toggle') }}" method="POST">
                        @csrf
                        <button type="submit" name="visible" value="{{ Auth::user()->is_batch_visible ? 0 : 1 }}" class="flex items-center gap-2">
                            <span class="text-lg font-black {{ Auth::user()->is_batch_visible ? 'text-emerald-500' : 'text-slate-400' }}">
                                {{ Auth::user()->is_batch_visible ? 'Public' : 'Hidden' }}
                            </span>
                            <div class="w-8 h-4 bg-slate-200 rounded-full relative">
                                <div class="absolute top-0.5 w-3 h-3 bg-white rounded-full transition-all {{ Auth::user()->is_batch_visible ? 'left-4.5 bg-emerald-500' : 'left-0.5 shadow-sm' }}"></div>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <div class="glass p-8 rounded-[2rem] shadow-glass border-white/40 flex items-center gap-6 group">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center text-primary shadow-sm group-hover:rotate-12 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Rank</h4>
                    <p class="text-xl font-black text-primary truncate max-w-[100px]">Explorer</p>
                </div>
            </div>
        </div>

        </div>

        <!-- 🥁 THE ACADEMIC PULSE: Personalized Deadline Stream 🛡️ -->
        <div class="space-y-6">
            <div class="flex justify-between items-center px-2">
                <div class="space-y-1">
                    <h3 class="text-2xl font-extrabold text-secondary flex items-center gap-2">
                        Academic Pulse
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                        </span>
                    </h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic leading-none">Live manifestation of upcoming academic hurdles</p>
                </div>
                <button @click="$dispatch('open-pulse-modal')" class="group flex items-center gap-2 bg-slate-50 hover:bg-white px-5 py-2.5 rounded-2xl border border-slate-100 transition-all">
                    <span class="w-8 h-8 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center text-xs group-hover:rotate-12 transition-transform">➕</span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Manifest Task</span>
                </button>
            </div>

            <div class="grid lg:grid-cols-2 gap-8">
                @forelse($academicPulse as $event)
                    <div class="glass p-8 rounded-[2.5rem] shadow-glass border-white/40 group hover:scale-[1.01] transition-all relative overflow-hidden">
                        {{-- Priority Indicator --}}
                        <div class="absolute top-0 right-0 p-6">
                            @if($event->priority === 'high')
                                <span class="px-3 py-1 bg-rose-500 text-white text-[8px] font-black uppercase tracking-widest rounded-lg shadow-lg shadow-rose-500/20">Critical</span>
                            @elseif($event->priority === 'medium')
                                <span class="px-3 py-1 bg-amber-500 text-white text-[8px] font-black uppercase tracking-widest rounded-lg">Imminent</span>
                            @else
                                <span class="px-3 py-1 bg-emerald-500 text-white text-[8px] font-black uppercase tracking-widest rounded-lg">Steady</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-8 relative z-10">
                            {{-- Event Type Icon --}}
                            <div class="w-20 h-20 rounded-[1.5rem] bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center text-4xl shadow-sm border border-white">
                                @php
                                    $icons = ['exam' => '📁', 'mst' => '📑', 'project' => '🏗️', 'assignment' => '📝', 'quiz' => '⏱️', 'lab' => '🧪', 'other' => '🗓️'];
                                @endphp
                                {{ $icons[$event->type] ?? '🗓️' }}
                            </div>

                            <div class="flex-1 space-y-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[9px] font-black text-primary uppercase tracking-widest">{{ $event->subject->name ?? 'Global' }}</span>
                                        @if($event->is_official)
                                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                            <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest flex items-center gap-1 italic">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                Institutional Broadcast
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="text-xl font-black text-slate-900 group-hover:text-primary transition-colors leading-tight">{{ $event->title }}</h4>
                                </div>

                                <div class="flex items-center gap-6">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $event->due_date_label }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $event->due_date->format('d M, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hover Effect Glow --}}
                        <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors"></div>
                    </div>
                @empty
                    <div class="lg:col-span-2 border-2 border-dashed border-slate-100 rounded-[2.5rem] py-16 flex flex-col items-center justify-center text-center space-y-4">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-3xl opacity-50">🧘‍♂️</div>
                        <div>
                            <p class="text-sm font-black text-slate-400 uppercase tracking-widest italic">All pulses stable</p>
                            <p class="text-[10px] font-bold text-slate-300 uppercase tracking-tighter mt-1">No upcoming assessments or personal tasks detected in the Verse</p>
                        </div>
                        <button @click="$dispatch('open-pulse-modal')" class="text-xs font-black text-primary hover:underline uppercase tracking-widest mt-4">Manual Manifestation →</button>
                    </div>
                @endforelse
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

        <div class="grid lg:grid-cols-3 gap-10">
            <!-- Mentorship Protocol Card 🤝 -->
            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-6 lg:col-span-1">
                <div class="flex justify-between items-start">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Guide Protocol</p>
                        <h4 class="text-xl font-black text-slate-900">Guide Mode</h4>
                    </div>
                    <div @class([
                        'px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border',
                        'bg-emerald-50 text-emerald-600 border-emerald-100' => Auth::user()->is_mentor,
                        'bg-slate-100 text-slate-400 border-slate-200' => !Auth::user()->is_mentor,
                    ])>
                        {{ Auth::user()->is_mentor ? 'Active' : 'Offline' }}
                    </div>
                </div>
                
                <p class="text-[10px] text-slate-500 font-bold leading-relaxed italic">
                    @if(Auth::user()->is_mentor_eligible)
                        Share your institutional wisdom and earn **100 Karma** for every helpful session.
                    @else
                        Unlock Guide Status by reaching **Final Year** or earning **500+ Karma**.
                    @endif
                </p>

                @if(Auth::user()->is_mentor_eligible)
                <form action="{{ route('mentorship.toggle') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-slate-900/10">
                        {{ Auth::user()->is_mentor ? 'Deactivate Guide Status' : 'Activate Guide Mode' }}
                    </button>
                </form>
                @endif
            </div>

            <!-- Discovery Protocol (Existing) -->
            <div class="bg-slate-900 p-10 rounded-[2.5rem] shadow-2xl space-y-6 lg:col-span-2 relative overflow-hidden">
                <div class="relative z-10 space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-white/40 uppercase tracking-widest">Peer Discovery</p>
                            <h4 class="text-xl font-black text-white uppercase italic tracking-tighter">Batch Visibility</h4>
                        </div>
                        {{-- Toggle Component --}}
                        <form action="{{ route('profile.batch.toggle') }}" method="POST">
                            @csrf
                            <button type="submit" @class([
                                'w-12 h-6 rounded-full relative transition-colors duration-300',
                                'bg-primary' => Auth::user()->is_batch_visible,
                                'bg-white/20' => !Auth::user()->is_batch_visible
                            ])>
                                <div @class([
                                    'absolute top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300',
                                    'translate-x-7' => Auth::user()->is_batch_visible,
                                    'translate-x-1' => !Auth::user()->is_batch_visible
                                ])></div>
                            </button>
                        </form>
                    </div>
                    <p class="text-[10px] text-white/50 font-bold leading-relaxed pr-10">
                        When active, students joining **{{ Auth::user()->college->name ?? 'your college' }}** in your year can find and message you.
                    </p>
                </div>
                <!-- Decorative astral shape -->
                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-primary/20 rounded-full blur-[60px]"></div>
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
    </div>

    <!-- 🤖 Academic Pulse: Manifestation Modal (Phase 1) -->
    <div x-data="{ 
            open: false,
            isScanning: false,
            scanInput: '',
            formData: {
                title: '',
                type: 'assignment',
                due_date: '',
                subject_id: '',
                priority: 'medium',
                description: ''
            },
            async manifestPulse() {
                try {
                    const response = await fetch('{{ route('academic-pulse.store') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(this.formData)
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        window.location.reload();
                    }
                } catch (e) { console.error('Manifestation Failed', e); }
            },
            async scanNotice() {
                if (!this.scanInput.trim()) return;
                this.isScanning = true;
                try {
                    const response = await fetch('{{ route('academic-pulse.scan') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ text_data: this.scanInput })
                    });
                    const result = await response.json();
                    if (result.status === 'success' && result.data.length > 0) {
                        const first = result.data[0];
                        this.formData.title = first.title;
                        this.formData.type = first.type;
                        this.formData.due_date = first.due_date.split('T')[0];
                        this.formData.priority = first.priority;
                        this.formData.description = first.description;
                        this.scanInput = '';
                    }
                } catch (e) { console.error('Scan Failed', e); }
                this.isScanning = false;
            }
         }" 
         @open-pulse-modal.window="open = true"
         x-show="open" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-xl"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-cloak>
        
        <div @click.away="open = false" 
             class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden border border-white/20 flex flex-col max-h-[90vh]">
            
            <div class="px-10 py-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-indigo-500/20">🥁</div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Manifest Academic Pulse</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">Synchronizing assessment hurdles with the Multiverse</p>
                    </div>
                </div>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 font-bold p-2">✕</button>
            </div>

            <div class="flex-1 overflow-y-auto p-10 space-y-8 custom-scrollbar">
                <!-- AI NOTICE SCANNER (The "Zero Mehnat" Hack) ⚡ -->
                <div class="p-6 bg-violet-50 rounded-3xl border border-violet-100 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black text-violet-600 uppercase tracking-widest flex items-center gap-2">
                             <span class="animate-pulse">🤖</span> AI Notice Scanner
                        </h4>
                        <span class="text-[9px] font-black text-violet-400 uppercase tracking-widest italic">Zero Labor Protocol</span>
                    </div>
                    <div class="flex gap-3">
                        <textarea x-model="scanInput" 
                                  placeholder="Paste notice board text or deadline announcement here..."
                                  class="flex-1 bg-white border-violet-100 rounded-2xl p-4 text-xs font-bold focus:ring-4 focus:ring-violet-500/10 placeholder:text-violet-200 h-20 outline-none"></textarea>
                        <button @click="scanNotice()" :disabled="isScanning" class="bg-violet-600 text-white px-6 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-violet-500/20 hover:scale-105 transition-all disabled:opacity-50">
                            <span x-text="isScanning ? 'Syncing...' : 'Scan'"></span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Manifest Title</label>
                        <input type="text" x-model="formData.title" placeholder="e.g. MST 1 Submission" class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/5 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Event Type</label>
                        <select x-model="formData.type" class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/5 outline-none">
                            <option value="assignment">Assignment</option>
                            <option value="mst">MST / Mid-Term</option>
                            <option value="exam">Final Exam</option>
                            <option value="project">Project Work</option>
                            <option value="quiz">Quiz / MCQ</option>
                            <option value="lab">Lab / Practical</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Due Date</label>
                        <input type="date" x-model="formData.due_date" class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/5 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Subject Node</label>
                        <select x-model="formData.subject_id" class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/5 outline-none">
                            <option value="">Select Subject (Optional)</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Priority Protocol</label>
                    <div class="flex gap-4">
                        @foreach(['low' => 'bg-emerald-100 text-emerald-600', 'medium' => 'bg-amber-100 text-amber-600', 'high' => 'bg-rose-100 text-rose-600'] as $prio => $cls)
                            <button @click="formData.priority = '{{ $prio }}'" 
                                    :class="formData.priority === '{{ $prio }}' ? '{{ $cls }} ring-2 ring-current' : 'bg-slate-50 text-slate-400'"
                                    class="flex-1 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                                {{ $prio }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="p-10 bg-slate-50 border-t border-slate-100">
                <button @click="manifestPulse()" class="w-full h-16 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-widest text-xs shadow-xl shadow-slate-900/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Initialize Manifestation ($Reward+10)
                </button>
                <p class="text-[9px] text-center text-slate-400 font-bold uppercase tracking-widest mt-6">Contributing official data fuels your ARS Visibility Score</p>
            </div>
        </div>
    </div>
</x-app-layout>
