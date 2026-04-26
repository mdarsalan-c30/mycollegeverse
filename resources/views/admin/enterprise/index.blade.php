<x-admin-layout>
<div class="space-y-8" x-data="{ activeTab: 'jobs' }">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl font-bold text-sm">✅ {{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="bg-amber-50 border border-amber-200 text-amber-700 px-6 py-4 rounded-2xl font-bold text-sm">⚠️ {{ session('warning') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 mb-2 tracking-tight">Enterprise Hub 🏢</h1>
            <p class="text-slate-400 font-bold uppercase text-[10px] tracking-[0.2em]">Manage Recruiters & Job Approvals</p>
        </div>
        <div class="flex gap-2 bg-slate-100 p-1.5 rounded-2xl">
            <button @click="activeTab = 'jobs'"
                :class="activeTab === 'jobs' ? 'bg-white shadow text-blue-600' : 'text-slate-400'"
                class="px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                Pending Jobs ({{ $stats['pending_jobs'] }})
            </button>
            <button @click="activeTab = 'recruiters'"
                :class="activeTab === 'recruiters' ? 'bg-white shadow text-blue-600' : 'text-slate-400'"
                class="px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                Recruiters ({{ $stats['total_recruiters'] }})
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Recruiters</p>
            <p class="text-3xl font-black text-slate-800">{{ $stats['total_recruiters'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-amber-100 shadow-sm">
            <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest mb-1">Pending Approval</p>
            <p class="text-3xl font-black text-amber-600">{{ $stats['pending_jobs'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-emerald-100 shadow-sm">
            <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">Live Jobs</p>
            <p class="text-3xl font-black text-emerald-600">{{ $stats['active_jobs'] }}</p>
        </div>
    </div>

    {{-- Pending Jobs Tab --}}
    <div x-show="activeTab === 'jobs'" x-transition>
        @if($pendingJobs->isEmpty())
            <div class="bg-white border-2 border-dashed border-slate-200 rounded-[2.5rem] p-20 text-center">
                <div class="text-4xl mb-4">✅</div>
                <h3 class="text-xl font-black text-slate-800">No Pending Jobs!</h3>
                <p class="text-slate-400 text-sm mt-2">All job posts have been reviewed.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($pendingJobs as $job)
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="font-black text-slate-800 text-lg">{{ $job->title }}</h3>
                            <span class="bg-amber-100 text-amber-600 text-[8px] font-black uppercase px-3 py-1 rounded-full tracking-widest">Pending</span>
                        </div>
                        <p class="text-sm text-slate-500">By <strong>{{ $job->recruiter->name ?? 'Unknown' }}</strong> • {{ $job->type }} • {{ $job->location ?? 'Remote' }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ Str::limit($job->description, 100) }}</p>
                    </div>
                    <div class="flex gap-3 shrink-0">
                        <form action="{{ route('admin.enterprise.jobs.approve', $job) }}" method="POST">
                            @csrf
                            <button class="h-10 px-6 bg-emerald-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition-all">
                                ✅ Approve
                            </button>
                        </form>
                        <form action="{{ route('admin.enterprise.jobs.reject', $job) }}" method="POST" onsubmit="return confirm('Reject and delete this job?')">
                            @csrf
                            @method('DELETE')
                            <button class="h-10 px-6 bg-rose-50 text-rose-500 border border-rose-200 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">
                                ❌ Reject
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Recruiters Tab --}}
    <div x-show="activeTab === 'recruiters'" x-transition>
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Recruiter</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Company</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jobs Posted</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recruiters as $recruiter)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-black text-slate-800">{{ $recruiter->name }}</p>
                            <p class="text-[10px] text-slate-400">{{ $recruiter->email }}</p>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-600">{{ $recruiter->company_name ?? '—' }}</td>
                        <td class="px-6 py-4 font-bold text-slate-600">{{ $recruiter->job_postings_count }}</td>
                        <td class="px-6 py-4">
                            @if($recruiter->status === 'active')
                                <span class="bg-emerald-100 text-emerald-600 text-[9px] font-black uppercase px-3 py-1 rounded-full">Active</span>
                            @else
                                <span class="bg-rose-100 text-rose-600 text-[9px] font-black uppercase px-3 py-1 rounded-full">Banned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.enterprise.recruiters.toggle', $recruiter) }}" method="POST">
                                @csrf
                                <button class="text-[10px] font-black uppercase {{ $recruiter->status === 'active' ? 'text-rose-500' : 'text-emerald-500' }} hover:underline">
                                    {{ $recruiter->status === 'active' ? 'Ban' : 'Unban' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 font-bold">No recruiters onboarded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</x-admin-layout>
