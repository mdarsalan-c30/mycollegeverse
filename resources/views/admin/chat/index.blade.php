<x-admin-layout>
    <div class="space-y-8">
        <!-- Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary tracking-tight">Chat Monitoring</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Privacy-sensitive oversight for social integrity</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="px-6 py-3 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-4">
                    <span class="text-[10px] font-black text-red-600 uppercase tracking-widest italic">Flagged Keyword Mode: <span class="bg-red-600 text-white px-2 py-0.5 rounded">ACTIVE</span></span>
                </div>
            </div>
        </div>

        <!-- System Warning -->
        <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-200">
            <div class="relative z-10 flex items-center gap-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl">⚖️</div>
                <div>
                    <h3 class="text-lg font-black tracking-tight leading-none mb-1">Privacy Protocols (Tier 1 Oversight)</h3>
                    <p class="text-xs font-bold text-indigo-100 opacity-80 leading-relaxed italic max-w-2xl">To uphold citizen privacy, only messages containing pre-defined social-risk keywords or reported interactions are surfaced in this terminal. Direct monitoring of private hubs is prohibited by platform ethics.</p>
                </div>
            </div>
            <div class="absolute -right-8 -top-8 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Chat Ingestion Terminal (Stitch UI Mirror) -->
        <div class="bg-white border border-admin-border rounded-[2.5rem] overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-admin-border">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Sender Identity</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Type</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Flagged Content</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 italic">
                    @forelse($messages as $message)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-slate-400">
                                    {{ substr($message->sender->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-black text-admin-dark">{{ $message->sender->name ?? 'Unknown Citizen' }}</p>
                                    <p class="text-[9px] font-bold text-slate-300 uppercase italic tracking-tighter">{{ $message->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-admin-surface border border-admin-border rounded-full text-[9px] font-black uppercase tracking-widest text-slate-500">
                                {{ $message->type }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 max-w-xl group-hover:bg-white transition-colors">
                                <p class="text-xs font-bold text-admin-secondary leading-relaxed">
                                    @php
                                        $content = $message->message;
                                        foreach($keywords as $word) {
                                            $content = str_ireplace($word, "<span class='bg-red-500 text-white px-1.5 rounded'>$word</span>", $content);
                                        }
                                    @endphp
                                    {!! $content !!}
                                </p>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users', ['search' => $message->sender->username ?? '']) }}" class="p-3 text-slate-400 hover:text-admin-primary transition-all rounded-xl" title="Audit Citizen Node">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </a>
                                <button type="button" class="p-3 text-red-500 hover:bg-red-500/10 transition-all rounded-xl" title="Flag Conversation">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.3em] italic">Zero flagged interactions detected in the latest social cycle.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- High-Fidelity Pagination -->
        <div class="pt-6">
            {{ $messages->links() }}
        </div>
    </div>
</x-admin-layout>
