<x-app-layout>
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div class="space-y-1">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Verse Pipeline</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-relaxed">Tracking your professional trajectory across corporate nodes</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl text-xs font-bold uppercase tracking-widest animate-pulse">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-12 gap-10 pb-20">
        <!-- Main Application Feed -->
        <div class="lg:col-span-8 space-y-6">
            @forelse($applications as $app)
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 group hover:shadow-xl transition-all relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[5rem] -mr-16 -mt-16 group-hover:scale-110 transition-transform"></div>
                    
                    <div class="flex flex-col md:flex-row justify-between gap-6 relative z-10">
                        <div class="flex gap-6">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-2xl shadow-inner group-hover:bg-primary group-hover:text-white transition-all">
                                @if($app->job->type === 'Internship') 🎓 @else 💼 @endif
                            </div>
                            <div>
                                <span class="px-3 py-1 bg-primary/10 text-primary text-[8px] font-black rounded-lg uppercase tracking-widest mb-2 inline-block">{{ $app->job->type }}</span>
                                <h3 class="text-xl font-black text-slate-900 leading-tight">{{ $app->job->title }}</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $app->job->recruiter->company_name }} • {{ $app->job->location }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Status</span>
                                <span class="px-4 py-1.5 bg-{{ $app->status === 'pending' ? 'amber' : ($app->status === 'rejected' ? 'red' : 'green') }}-50 text-{{ $app->status === 'pending' ? 'amber' : ($app->status === 'rejected' ? 'red' : 'green') }}-600 text-[10px] font-black rounded-xl uppercase tracking-widest border border-{{ $app->status === 'pending' ? 'amber' : ($app->status === 'rejected' ? 'red' : 'green') }}-100">
                                    {{ $app->status }}
                                </span>
                            </div>
                            <p class="text-[8px] font-bold text-slate-300 uppercase italic">Applied {{ $app->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-slate-50 flex flex-wrap gap-3">
                        <button onclick="window.location='{{ route('jobs.show', $app->job->id) }}'" class="h-10 px-6 bg-slate-50 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all">Review Posting</button>
                        
                        @if($app->status !== 'pending' && $app->status !== 'rejected')
                            <button onclick="window.location='{{ route('chat.index', $app->job->recruiter->username) }}'" class="h-10 px-6 bg-primary text-white text-[9px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">Nexus Response Node</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center px-10">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-4xl mb-6 shadow-sm">⏳</div>
                    <h4 class="text-xl font-black text-slate-900 mb-2 uppercase tracking-tight">No Active Signals</h4>
                    <p class="text-sm font-bold text-slate-400 max-w-xs mb-8">Initialize your first professional signal on the discovery board to begin your recruitment journey.</p>
                    <a href="{{ route('jobs.index') }}" class="px-10 h-14 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-black/20 hover:scale-105 transition-all flex items-center justify-center">Enter Discovery Board</a>
                </div>
            @endforelse
        </div>

        <!-- Progress Sidebar -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-slate-900 rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-lg font-black text-white tracking-tight mb-6">Pipeline Health</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center bg-white/5 p-4 rounded-2xl border border-white/5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Pursuits</p>
                            <p class="text-xl font-black text-white">{{ $applications->count() }}</p>
                        </div>
                        <div class="flex justify-between items-center bg-white/5 p-4 rounded-2xl border border-white/5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nexus Responses</p>
                            <p class="text-xl font-black text-white">{{ $applications->whereIn('status', ['reviewed', 'shortlisted'])->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recruitment Tip -->
            <div class="bg-primary rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl shadow-primary/20">
                <div class="relative z-10">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl mb-4">💡</div>
                    <h4 class="font-black italic text-lg mb-2 tracking-tight">Verse Protocol</h4>
                    <p class="text-[11px] font-bold leading-relaxed opacity-80 uppercase tracking-wider">Recruiters prioritize students with high academic reputation scores. Keep contributing to the community to increase your visibility.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
