<x-recruiter-layout>
    <div x-data="{ 
            activeTab: window.location.hash.replace('#', '') || 'overview',
            talentSearch: '',
            copied: false,
            init() {
                window.addEventListener('hashchange', () => {
                    this.activeTab = window.location.hash.replace('#', '') || 'overview';
                });
            },
            copyKey() {
                const key = '{{ Auth::user()->integration_token }}';
                navigator.clipboard.writeText(key);
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            }
         }" class="space-y-10">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 px-10 pt-10">
            <div class="space-y-1">
                <template x-if="activeTab === 'overview'">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Enterprise Overview</h1>
                </template>
                <template x-if="activeTab === 'talent'">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Talent Scout Hub</h1>
                </template>
                <template x-if="activeTab === 'postings'">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Role Management</h1>
                </template>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-relaxed">Verified Corporate Access — {{ Auth::user()->company_name }}</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex bg-white/50 p-1.5 rounded-2xl shadow-sm border border-slate-100 items-center">
                    <button @click="activeTab = 'overview'; window.location.hash = 'overview'" :class="activeTab === 'overview' ? 'bg-white shadow-sm text-primary' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Overview</button>
                    <button @click="activeTab = 'talent'; window.location.hash = 'talent'" :class="activeTab === 'talent' ? 'bg-white shadow-sm text-primary' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Talent Hub</button>
                    <button @click="activeTab = 'postings'; window.location.hash = 'postings'" :class="activeTab === 'postings' ? 'bg-white shadow-sm text-primary' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Postings</button>
                    <button @click="activeTab = 'integration'; window.location.hash = 'integration'" :class="activeTab === 'integration' ? 'bg-white shadow-sm text-primary' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Integration</button>
                </div>
                <button type="button" @click="$dispatch('open-modal', 'post-job')" class="h-14 px-8 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl flex items-center justify-center shadow-xl shadow-black/20 hover:bg-black transition-all">
                    + Advertise Role
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="px-10 pb-10">
            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-10">
                <!-- Recent Applicants (PRIORITY) -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-6">
                            <h2 class="text-xl font-black text-slate-900 tracking-tight italic">Recent Professional Signals</h2>
                            
                            <!-- Integrated Stats Widget -->
                            <div class="hidden md:flex items-center gap-1.5 bg-slate-900 px-4 py-2 rounded-xl shadow-lg border border-white/5">
                                <div class="flex items-center gap-3 pr-3 border-r border-white/10">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Active Roles</span>
                                    <span class="text-[10px] font-black text-white italic leading-none">{{ Auth::user()->jobPostings()->count() }}</span>
                                </div>
                                <div class="flex items-center gap-3 pl-2">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Signals</span>
                                    <span class="text-[10px] font-black text-primary italic leading-none">{{ \App\Models\JobApplication::whereHas('job', fn($q) => $q->where('recruiter_id', Auth::id()))->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="#postings" @click="activeTab = 'postings'; window.location.hash = 'postings'" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline">Manage All Roles</a>
                    </div>
                    
                    <div class="grid lg:grid-cols-3 gap-8">
                        @forelse($recentApplicants as $application)
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 hover:shadow-xl transition-all group relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-[4rem] -mr-12 -mt-12 group-hover:scale-110 transition-transform"></div>
                            
                            <div class="flex items-center gap-4 relative z-10 mb-6">
                                <img src="{{ $application->student->profile_photo_url }}" class="w-12 h-12 rounded-xl object-cover shadow-sm"/>
                                <div>
                                    <h4 class="font-black text-slate-900 leading-tight">{{ $application->student->name }}</h4>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $application->job->title }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-[10px] font-black border-t border-slate-50 pt-6">
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg uppercase tracking-widest">{{ $application->status }}</span>
                                <a href="{{ route('recruiter.jobs.applicants', $application->job->id) }}" class="text-primary hover:underline italic">Inspect Brief →</a>
                            </div>
                        </div>
                        @empty
                        <div class="lg:col-span-3 py-12 bg-slate-50/50 rounded-[2.5rem] border-2 border-dashed border-slate-200 text-center">
                            <p class="text-sm font-black text-slate-400 uppercase tracking-widest">No Recent Signals Received</p>
                            <p class="text-[10px] font-bold text-slate-300 mt-1 italic tracking-widest uppercase">Roles are currently broadcasting on the discovery board</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="h-px bg-slate-100"></div>

                <div>
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-black text-slate-900 tracking-tight italic">Global Talent Scout</h2>
                        <a href="#talent" @click="activeTab = 'talent'; window.location.hash = 'talent'" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline italic">Discover Nexus Bench →</a>
                    </div>
                    
                    <div class="grid lg:grid-cols-4 gap-6">
                        @foreach($topTalent as $student)
                        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 hover:shadow-xl transition-all group relative overflow-hidden">
                            <div class="flex flex-col items-center text-center space-y-4">
                                <img src="{{ $student->profile_photo_url }}" class="w-16 h-16 rounded-2xl object-cover shadow-lg border-2 border-white"/>
                                
                                <div class="space-y-1">
                                    <h3 class="font-black text-slate-900 text-sm leading-tight">{{ $student->name }}</h3>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest truncate w-full px-2">{{ $student->college->name ?? 'Verse Native' }}</p>
                                </div>

                                <div class="flex items-center gap-6 w-full pt-2 border-t border-slate-50">
                                    <div class="flex-1 text-center">
                                        <p class="text-[8px] font-black text-primary uppercase tracking-tighter leading-none">ARS Score</p>
                                        <p class="text-lg font-black text-slate-800 tracking-tighter">{{ $student->ars_score }}</p>
                                    </div>
                                    <button onclick="window.location='{{ route('chat.index', $student->username) }}'" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl hover:bg-primary hover:text-white transition-all flex items-center justify-center shadow-inner">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid lg:grid-cols-4 gap-8">
                    <div class="lg:col-span-1">
                         <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100">
                            <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-6">Top Talent Sources</h4>
                            <div class="space-y-4">
                                @foreach($talentSources as $source)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-[8px] font-black text-slate-400 uppercase">{{ substr($source->name, 0, 3) }}</div>
                                        <p class="text-[10px] font-bold text-slate-700 truncate w-24 leading-none">{{ $source->name }}</p>
                                    </div>
                                    <span class="text-[10px] font-black text-primary">{{ $source->student_count }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 flex flex-col justify-between h-full relative overflow-hidden group">
                            <!-- Background Accent -->
                            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-bl-full -mr-32 -mt-32 transition-transform group-hover:scale-110"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-10">
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 tracking-tight italic">Pipeline Intelligence Node</h3>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Real-time Network Health Monitoring</p>
                                    </div>
                                    <div class="bg-primary/10 px-4 py-2 rounded-xl">
                                        <span class="text-[10px] font-black text-primary uppercase">Conversion: {{ $conversionRate }}%</span>
                                    </div>
                                </div>

                                <div class="space-y-10">
                                    <!-- Pipeline Health Bar -->
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-end">
                                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">Status Distribution</p>
                                            <p class="text-[10px] font-bold text-slate-400 leading-none">{{ $totalApps }} Total Signals</p>
                                        </div>
                                        <div class="h-4 w-full bg-slate-50 rounded-full flex overflow-hidden p-0.5 border border-slate-100">
                                            @if($totalApps > 0)
                                                <div style="width:{{ ($pipelineStats['shortlisted'] / $totalApps) * 100 }}%" class="h-full bg-primary rounded-full" title="Shortlisted"></div>
                                                <div style="width:{{ ($pipelineStats['reviewed'] / $totalApps) * 100 }}%" class="h-full bg-slate-400 opacity-50 rounded-full mx-0.5" title="Reviewed"></div>
                                                <div style="width:{{ ($pipelineStats['rejected'] / $totalApps) * 100 }}%" class="h-full bg-red-400 opacity-30 rounded-full" title="Rejected"></div>
                                            @else
                                                <div class="w-full h-full bg-slate-100 animate-pulse rounded-full"></div>
                                            @endif
                                        </div>
                                        <div class="flex gap-6 mt-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-primary"></div>
                                                <span class="text-[9px] font-bold text-slate-500 uppercase">{{ $pipelineStats['shortlisted'] }} Shortlisted</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-slate-400/50"></div>
                                                <span class="text-[9px] font-bold text-slate-500 uppercase">{{ $pipelineStats['reviewed'] }} Reviewed</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-red-400/30"></div>
                                                <span class="text-[9px] font-bold text-slate-500 uppercase">{{ $pipelineStats['rejected'] }} Rejected</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Strategic Insight Card -->
                                    <div class="bg-slate-50 rounded-3xl p-6 border border-slate-100 flex items-center justify-between">
                                        <div class="space-y-1">
                                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Network Pulse</p>
                                            <p class="text-[9px] font-bold text-slate-400 leading-relaxed max-w-[240px]">
                                                @if($totalApps > 0)
                                                    Market analysis indicates a {{ $conversionRate > 20 ? 'high' : 'steady' }} demand for your broadcasted roles across campus nodes.
                                                @else
                                                    Awaiting initial candidacy signals. Expand your broadcast reach to more college hubs to optimize sourcing.
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[8px] font-black text-slate-400 uppercase leading-none mb-1">Response Grade</p>
                                            <p class="text-2xl font-black text-slate-900 italic tracking-tighter">
                                                {{ $totalApps > 0 ? ($pipelineStats['pending'] > 0 ? 'B+' : 'A+') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Talent Hub Tab -->
            <div x-show="activeTab === 'talent'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-slate-100">
                    <div class="mb-10 flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Campus Talent Verified Pool</h3>
                         <div class="flex gap-2">
                              <input type="text" x-model="talentSearch" placeholder="Search by name or node..." class="h-10 bg-slate-50 border-none rounded-xl px-4 text-xs font-bold w-64 focus:ring-2 focus:ring-primary/20">
                         </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-slate-100">
                                    <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Talent Node</th>
                                    <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">University Origin</th>
                                    <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Score</th>
                                    <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($topTalent as $student)
                                <tr x-show="talentSearch === '' || '{{ strtolower($student->name) }}'.includes(talentSearch.toLowerCase()) || '{{ strtolower($student->username) }}'.includes(talentSearch.toLowerCase())" 
                                    class="group hover:bg-slate-50/50 transition-colors">
                                    <td class="py-6">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ $student->profile_photo_url }}" class="w-10 h-10 rounded-xl object-cover shadow-sm" />
                                            <div>
                                                <p class="text-sm font-black text-slate-900 leading-none">{{ $student->name }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 mt-1">@ {{ $student->username }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-6">
                                        <span class="px-3 py-1.5 bg-white border border-slate-100 rounded-lg text-[10px] font-bold text-slate-500 uppercase">{{ $student->college->name ?? 'Verse Native' }}</span>
                                    </td>
                                    <td class="py-6 text-center">
                                        <span class="text-lg font-black text-primary tracking-tighter">{{ $student->ars_score }}</span>
                                    </td>
                                    <td class="py-6 text-right">
                                        <button onclick="window.location='{{ route('chat.index', $student->username) }}'" class="px-6 py-2.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-black/10 hover:scale-105 active:scale-95 transition-all">Engage</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Role Management Tab -->
            <div x-show="activeTab === 'postings'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                 <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse(\App\Models\JobPosting::where('recruiter_id', Auth::id())->get() as $job)
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary font-black group-hover:bg-primary group-hover:text-white transition-all text-xl">💼</div>
                            <span class="px-3 py-1 bg-{{ $job->is_approved ? 'green' : 'amber' }}-50 text-{{ $job->is_approved ? 'green' : 'amber' }}-600 text-[10px] font-black rounded-lg uppercase tracking-widest">{{ $job->is_approved ? 'Live' : 'Pending' }}</span>
                        </div>
                        <h4 class="text-lg font-black text-slate-900 mb-1">{{ $job->title }}</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">{{ $job->location }} • {{ $job->type }}</p>
                        
                        <div class="flex items-center justify-between text-[10px] font-black border-t border-slate-50 pt-6">
                             <span class="text-slate-400 uppercase tracking-widest">Applications</span>
                             <a href="{{ route('recruiter.jobs.applicants', $job->id) }}" class="text-primary hover:underline">{{ $job->applications()->count() }} Total</a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-4xl mb-6 shadow-sm ring-8 ring-white/50">📣</div>
                        <h4 class="text-xl font-black text-slate-900 mb-2 uppercase tracking-tight">No Published Pipelines</h4>
                        <p class="text-sm font-bold text-slate-400 max-w-xs mb-8">Start broadcasting your opportunities to curated campus talent across the Verse network.</p>
                        <button @click="$dispatch('open-modal', 'post-job')" class="px-10 h-14 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-black/20 hover:scale-105 transition-all">Initialize First Role</button>
                    </div>
                    @endforelse
                 </div>
            </div>

            <!-- Integrated Node Tab -->
            <div x-show="activeTab === 'integration'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-slate-900 rounded-[3rem] p-16 text-center shadow-2xl relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10">
                        <svg class="h-full w-full" fill="none" viewBox="0 0 400 400">
                            <defs>
                                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnPoints">
                                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                                </pattern>
                            </defs>
                            <rect width="400" height="400" fill="url(#grid)"/>
                        </svg>
                    </div>
                    
                    <div class="relative z-10 max-w-lg mx-auto space-y-8">
                        <div class="w-24 h-24 bg-primary/20 rounded-3xl flex items-center justify-center text-4xl mx-auto shadow-inner ring-8 ring-primary/5">🔌</div>
                        <h3 class="text-3xl font-black text-white tracking-tight italic">Enterprise Integration Node</h3>
                        
                        @if(Auth::user()->integration_token)
                            <div class="space-y-6">
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest leading-relaxed">Your secure Verse Node Key is active. Use this to sync your pipeline with external ATS or Slack nodes.</p>
                                
                                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 relative group">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 text-left">Internal API Key</p>
                                    <div class="flex items-center justify-between">
                                        <code class="text-primary font-black tracking-wider text-sm">{{ Auth::user()->integration_token }}</code>
                                        <button @click="copyKey" class="text-slate-400 hover:text-white transition-colors">
                                            <template x-if="!copied">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                            </template>
                                            <template x-if="copied">
                                                <span class="text-[10px] font-black text-green-400 uppercase tracking-widest">Copied</span>
                                            </template>
                                        </button>
                                    </div>
                                </div>

                                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 text-left">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Simulated Webhook Endpoint</p>
                                    <code class="text-slate-300 text-[10px]">https://mycollegeverse.in/api/v1/hooks/{{ Auth::id() }}</code>
                                </div>

                                <form action="{{ route('recruiter.integration.initialize') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-all">Refresh Key Node</button>
                                </form>
                            </div>
                        @else
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest leading-relaxed">Synchronize your Verse recruiting pipeline directly with your internal ATS, Slack, or HRIS system.</p>
                            
                            <div class="pt-6">
                                <form action="{{ route('recruiter.integration.initialize') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-10 h-16 bg-white text-slate-900 text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:scale-105 transition-all">Initialize API Bridge</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Post Job Modal (Alpine.js) -->
    <div x-data="{ open: false }" 
         x-show="open" 
         @open-modal.window="if($event.detail == 'post-job') open = true"
         @keydown.escape.window="open = false"
         style="display: none;"
         class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-6">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="open = false"></div>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-[3rem] shadow-2xl max-w-2xl w-full p-12 overflow-hidden border border-slate-100">
                <div class="absolute top-0 right-0 p-8">
                    <button @click="open = false" class="text-slate-300 hover:text-slate-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="mb-10 text-center">
                    <h2 class="text-2xl font-black text-slate-900 mb-2 italic">Broadcast Role</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Enterprise Opportunity Publication Hub</p>
                </div>

                <form method="POST" action="{{ route('recruiter.jobs.store') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Opportunity Identity</label>
                        <input type="text" name="title" required placeholder="Senior Core Systems Node" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Contract Tier</label>
                            <select name="type" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all appearance-none cursor-pointer">
                                <option>Internship</option>
                                <option selected>Full-time</option>
                                <option>Contract</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Deployment Node</label>
                            <input type="text" name="location" placeholder="Remote / Global / Local" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Target Audience</label>
                        <select name="target_college_id" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all appearance-none cursor-pointer">
                            <option value="">Open to All (Global Verse)</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}">Only {{ $college->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Remuneration Package</label>
                        <input type="text" name="salary_range" placeholder="₹ Competitive Performance Tier" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Description Brief</label>
                        <textarea name="description" required rows="4" placeholder="Briefly describe the responsibilities and tech stack..." class="w-full bg-slate-50 border-none rounded-[2rem] p-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full h-16 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-2xl shadow-black/20 hover:bg-black hover:scale-[1.01] transition-all">
                            Initialize Broadcast
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-recruiter-layout>
