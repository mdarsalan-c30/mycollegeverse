@extends('layouts.admin')

@section('content')
<div class="space-y-8" x-data="{ activeTab: 'jobs' }">
    <!-- Header Node -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 mb-2 tracking-tight">Enterprise Hub 🏢</h1>
            <p class="text-slate-500 font-bold uppercase text-[10px] tracking-[0.2em]">Moderating the Corporate Nodes of the Multiverse</p>
        </div>
        <div class="flex gap-4 bg-slate-100 p-1.5 rounded-2xl border border-slate-200">
            <button @click="activeTab = 'jobs'" :class="activeTab === 'jobs' ? 'bg-white shadow-sm text-primary' : 'text-slate-500'" class="px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                Job Queue ({{ count($pendingJobs) }})
            </button>
            <button @click="activeTab = 'recruiters'" :class="activeTab === 'recruiters' ? 'bg-white shadow-sm text-primary' : 'text-slate-500'" class="px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                Recruiter Directory
            </button>
        </div>
    </div>

    <!-- Analytics Pulse -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass p-6 rounded-[2rem] border-white/40">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Recruiters</p>
            <div class="flex items-end gap-3">
                <span class="text-3xl font-black text-slate-800">{{ $stats['total_recruiters'] }}</span>
                <span class="text-[10px] font-bold text-emerald-500 mb-1.5">🚀 Active Nodes</span>
            </div>
        </div>
        <div class="glass p-6 rounded-[2rem] border-white/40">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Live Job Assets</p>
            <div class="flex items-end gap-3">
                <span class="text-3xl font-black text-slate-800">{{ $stats['active_jobs'] }}</span>
                <span class="text-[10px] font-bold text-primary mb-1.5">📡 Broadcasting</span>
            </div>
        </div>
        <div class="glass p-6 rounded-[2rem] border-white/40">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Pending Moderation</p>
            <div class="flex items-end gap-3">
                <span class="text-3xl font-black text-rose-600">{{ $stats['pending_jobs'] }}</span>
                <span class="text-[10px] font-bold text-rose-400 mb-1.5 italic">Awaiting Signal</span>
            </div>
        </div>
        <div class="glass p-6 rounded-[2rem] border-white/40">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Placements</p>
            <div class="flex items-end gap-3">
                <span class="text-3xl font-black text-emerald-600">{{ $stats['total_hired'] }}</span>
                <span class="text-[10px] font-bold text-emerald-400 mb-1.5">🎯 Talent Synced</span>
            </div>
        </div>
    </div>

    <!-- Job Moderation Queue -->
    <div x-show="activeTab === 'jobs'" x-transition class="space-y-6">
        @if($pendingJobs->isEmpty())
            <div class="bg-white/50 border-2 border-dashed border-slate-200 rounded-[2.5rem] p-20 text-center">
                <div class="text-4xl mb-4">🌈</div>
                <h3 class="text-xl font-black text-slate-800">Queue is Clear!</h3>
                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest mt-2">All corporate signals have been processed.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4">
                @foreach($pendingJobs as $job)
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6 group hover:shadow-xl hover:shadow-primary/5 transition-all">
                    <div class="flex gap-6 items-center flex-1">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-3xl">🏢</div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-black text-slate-800 text-lg">{{ $job->title }}</h3>
                                <span class="bg-amber-100 text-amber-600 text-[8px] font-black uppercase px-3 py-1 rounded-full tracking-widest italic">Awaiting Approval</span>
                            </div>
                            <p class="text-slate-500 font-bold text-xs">Posted by <span class="text-primary">{{ $job->recruiter->name }}</span> ({{ $job->recruiter->company_name ?? 'The Enterprise' }})</p>
                            <div class="flex gap-4 mt-3">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">📍 {{ $job->location ?? 'Remote' }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">💰 {{ $job->salary_range ?? 'Unspecified' }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">🕒 {{ $job->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <form action="{{ route('admin.enterprise.jobs.approve', $job) }}" method="POST">
                            @csrf
                            <button class="h-12 px-8 bg-emerald-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:scale-105 transition-all">
                                Approve Signal
                            </button>
                        </form>
                        <form action="{{ route('admin.enterprise.jobs.reject', $job) }}" method="POST" onsubmit="return confirm('Purge this job asset from the multiverse?')">
                            @csrf
                            @method('DELETE')
                            <button class="h-12 px-8 bg-rose-50 text-rose-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recruiter Directory -->
    <div x-show="activeTab === 'recruiters'" x-transition>
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Recruiter Node</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Company / Org</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Jobs Posted</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @foreach($recruiters as $recruiter)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-black text-xs">
                                    {{ substr($recruiter->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-black text-slate-800">{{ $recruiter->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold tracking-tight">{{ $recruiter->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs font-black text-slate-600 bg-slate-100 px-3 py-1 rounded-lg uppercase tracking-wider">
                                {{ $recruiter->company_name ?? 'Independent Entity' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 font-bold text-slate-600">{{ $recruiter->job_postings_count }}</td>
                        <td class="px-8 py-6">
                            @if($recruiter->status === 'active')
                                <span class="bg-emerald-100 text-emerald-600 text-[9px] font-black uppercase px-3 py-1 rounded-full tracking-widest">Active</span>
                            @else
                                <span class="bg-rose-100 text-rose-600 text-[9px] font-black uppercase px-3 py-1 rounded-full tracking-widest">Banned</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <form action="{{ route('admin.enterprise.recruiters.toggle', $recruiter) }}" method="POST">
                                @csrf
                                <button class="text-[10px] font-black uppercase tracking-widest {{ $recruiter->status === 'active' ? 'text-rose-500' : 'text-emerald-500' }} hover:underline">
                                    {{ $recruiter->status === 'active' ? 'Ban Node' : 'Authorize Node' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-8 border-t border-slate-50 bg-slate-50/30">
                {{ $recruiters->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
