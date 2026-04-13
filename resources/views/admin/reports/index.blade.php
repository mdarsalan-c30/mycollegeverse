<x-admin-layout>
    <div class="space-y-8" x-data="{ openResolve: false, activeReport: {id: '', name: ''} }">
        <!-- Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary tracking-tight">Resolution Center</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Centralized node for platform safety and dispute resolution</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="px-6 py-3 bg-white border border-admin-border rounded-2xl shadow-sm flex items-center gap-4">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Security Status: <span class="text-indigo-600">STABLE</span></span>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-100 pb-px text-xs font-black uppercase tracking-widest italic">
            <a href="{{ route('admin.reports', ['status' => 'pending']) }}" class="px-6 py-3 transition-all {{ request('status', 'pending') == 'pending' ? 'text-admin-primary border-b-2 border-admin-primary' : 'text-slate-400 hover:text-slate-600' }}">Open Flags</a>
            <a href="{{ route('admin.reports', ['status' => 'resolved']) }}" class="px-6 py-3 transition-all {{ request('status') == 'resolved' ? 'text-green-600 border-b-2 border-green-600' : 'text-slate-400 hover:text-slate-600' }}">Archived/Resolved</a>
            <a href="{{ route('admin.reports', ['status' => 'all']) }}" class="px-6 py-3 transition-all {{ request('status') == 'all' ? 'text-slate-600 border-b-2 border-slate-600' : 'text-slate-400 hover:text-slate-600' }}">Master Audit Log</a>
        </div>

        <!-- Resolution Queue (Stitch UI Mirror) -->
        <div class="bg-white border border-admin-border rounded-[2.5rem] overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-admin-border">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Reporter Citizen</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Flagged Target</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Reason for Alert</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Registry Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 italic">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-slate-400">
                                    {{ substr(optional($report->reporter)->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-black text-admin-dark">{{ optional($report->reporter)->name ?? 'Unknown Citizen' }}</p>
                                    <p class="text-[9px] font-bold text-slate-300 uppercase italic tracking-tighter">{{ $report->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[9px] font-black uppercase tracking-widest w-fit mb-1">{{ $report->reportable_type }}</span>
                                <p class="text-[10px] font-bold text-slate-400">ID: {{ $report->reportable_id }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-xs font-bold text-admin-dark leading-tight max-w-md">{{ $report->reason }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ 
                                $report->status === 'pending' ? 'bg-red-500/10 text-red-600' : 
                                ($report->status === 'resolved' ? 'bg-green-500/10 text-green-600' : 'bg-slate-100 text-slate-400') 
                            }}">
                                {{ $report->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @php
                                    $link = '#';
                                    if($report->reportable_type == 'Note') $link = route('admin.notes', ['search' => $report->reportable_id]);
                                    if($report->reportable_type == 'User') $link = route('admin.users', ['search' => $report->reportable_id]);
                                @endphp
                                <a href="{{ $link }}" class="p-3 text-slate-400 hover:text-admin-primary transition-all hover:bg-admin-primary/5 rounded-xl" title="Deep Scan Target">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>

                                @if($report->status === 'pending')
                                <button type="button" @click="activeReport = {id: '{{ $report->id }}', name: '{{ $report->id }}'}; openResolve = true" title="Resolve Security Flag" class="p-3 text-green-500 hover:bg-green-500/10 transition-all rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.3em] italic">Multiverse Security Stable. Zero pending flags detected.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- High-Fidelity Pagination -->
        <div class="pt-6">
            {{ $reports->links() }}
        </div>

        <!-- Resolution Modal (Alpine.js) - Moved out of table -->
        <template x-if="openResolve">
            <div class="fixed inset-0 z-[100] flex items-center justify-center bg-admin-secondary/40 backdrop-blur-sm px-4">
                <div @click.away="openResolve = false" class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-10 space-y-6 text-left border border-slate-100 flex flex-col italic">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-xl">🛡️</div>
                        <div>
                            <h3 class="text-xl font-black text-admin-secondary leading-none">Security Clearance</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Resolve Flag #<span x-text="activeReport.id"></span></p>
                        </div>
                    </div>

                    <form :action="`/admin/reports/${activeReport.id}/resolve`" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="status" value="resolved" class="hidden peer" checked>
                                    <div class="px-6 py-3 rounded-2xl border-2 border-slate-100 peer-checked:border-green-600 peer-checked:bg-green-50 text-[10px] font-black uppercase tracking-widest transition-all">Resolved</div>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="status" value="ignored" class="hidden peer">
                                    <div class="px-6 py-3 rounded-2xl border-2 border-slate-100 peer-checked:border-slate-600 peer-checked:bg-slate-50 text-[10px] font-black uppercase tracking-widest transition-all">Ignore</div>
                                </label>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-2">Resolution Notes</label>
                                <textarea name="admin_notes" rows="3" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 transition-all italic" placeholder="Enter security clearance details..."></textarea>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <button type="button" @click="openResolve = false" class="flex-1 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cancel</button>
                            <button type="submit" class="flex-[2] py-4 bg-admin-secondary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-admin-secondary/20 hover:scale-[1.02] active:scale-95 transition-all">Finalize Resolution</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-admin-layout>
