<x-recruiter-layout>
    <div class="space-y-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 px-10 pt-10">
            <div class="space-y-1">
                <nav class="flex items-center gap-2 mb-2">
                    <a href="{{ route('recruiter.dashboard') }}" class="text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-widest">Dashboard</a>
                    <span class="text-slate-300">/</span>
                    <span class="text-[10px] font-black text-primary uppercase tracking-widest">Applicant Bench</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $job->title }}</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-relaxed">Management Console — {{ $applications->count() }} Candidates Identified</p>
            </div>
        </div>

        <div class="px-10 pb-10">
            <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-slate-100 min-h-[700px] overflow-visible">
                <div class="overflow-visible pb-72">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-slate-100 italic">
                                <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Candidate Node</th>
                                <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">University Origin</th>
                                <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Candidacy Brief</th>
                                <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Action Hub</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($applications as $app)
                            <tr x-data="{ showBrief: false }" class="group hover:bg-slate-50/50 transition-colors">
                                <td class="py-8">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $app->student->profile_photo_url }}" class="w-12 h-12 rounded-2xl object-cover shadow-sm" />
                                        <div>
                                            <p class="text-sm font-black text-slate-900 leading-none">{{ $app->student->name }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 mt-1">@ {{ $app->student->username }} • ARS {{ $app->student->ars_score }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-8 text-center">
                                    <span class="px-3 py-1.5 bg-white border border-slate-100 rounded-lg text-[10px] font-bold text-slate-500 uppercase">{{ $app->student->college->name ?? 'External Node' }}</span>
                                </td>
                                <td class="py-8 max-w-xs text-center">
                                    <button @click="showBrief = !showBrief" class="text-[10px] font-black text-primary uppercase tracking-widest flex items-center justify-center gap-2 hover:underline w-full">
                                        Inspect Motivation
                                        <svg :class="showBrief ? 'rotate-180' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="showBrief" x-collapse x-cloak class="mt-4 space-y-4 text-left">
                                        <div>
                                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Executive Summary</p>
                                            <p class="text-[11px] font-medium text-slate-600 leading-relaxed">{{ $app->about_me }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Why Hire</p>
                                            <p class="text-[11px] font-medium text-slate-600 leading-relaxed">{{ $app->why_hire }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-8 text-center">
                                    <span class="px-3 py-1.5 bg-{{ $app->status === 'pending' ? 'amber' : ($app->status === 'rejected' ? 'red' : 'green') }}-50 text-{{ $app->status === 'pending' ? 'amber' : ($app->status === 'rejected' ? 'red' : 'green') }}-600 text-[9px] font-black rounded-lg uppercase tracking-widest border border-{{ $app->status === 'pending' ? 'amber' : ($app->status === 'rejected' ? 'red' : 'green') }}-100">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="py-8 text-right">
                                    <div class="flex items-center justify-end gap-3" x-data="{ openMenu: false }">
                                        <a href="{{ explode('/-/', $app->resume_shared_link)[0] }}" 
                                           target="_blank" 
                                           class="h-10 px-5 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl flex items-center gap-2 shadow-lg shadow-black/10 hover:scale-105 transition-all">
                                            📄 Review PDF
                                        </a>
                                        
                                        <div class="relative">
                                            <button @click="openMenu = !openMenu" class="h-10 w-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                            </button>
                                            
                                            <div x-show="openMenu" @click.away="openMenu = false" class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-2xl border border-slate-100 p-2 z-50">
                                                <form action="{{ route('recruiter.applications.status', $app->id) }}" method="POST">
                                                    @csrf
                                                    <button name="status" value="shortlisted" class="w-full text-left px-4 py-2 text-[9px] font-black text-green-600 uppercase tracking-widest hover:bg-green-50 rounded-xl transition-all">Shortlist Candidate</button>
                                                    <button name="status" value="reviewed" class="w-full text-left px-4 py-2 text-[9px] font-black text-slate-600 uppercase tracking-widest hover:bg-slate-50 rounded-xl transition-all">Mark Reviewed</button>
                                                    <button name="status" value="rejected" class="w-full text-left px-4 py-2 text-[9px] font-black text-red-600 uppercase tracking-widest hover:bg-red-50 rounded-xl transition-all">Decline Signal</button>
                                                </form>
                                                <div class="h-px bg-slate-50 my-2"></div>
                                                <a href="{{ route('chat.index', $app->student->username) }}" class="block px-4 py-2 text-[9px] font-black text-primary uppercase tracking-widest hover:bg-primary/5 rounded-xl transition-all">Open Nexus Chat</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="text-4xl mb-4 opacity-20">📡</div>
                                    <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Signal Silence</h4>
                                    <p class="text-xs font-bold text-slate-300 mt-2 italic">No talent nodes have broadcasted candidacy for this role yet.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-recruiter-layout>
