<x-admin-layout>
    @section('title', 'Perks & Rewards | Control Tower')

    <div class="space-y-10">
        <!-- Header Node -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-4xl font-black text-secondary tracking-tight">Perks Hub</h1>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-2 italic">Redemption Center Management for Elite Citizens</p>
            </div>
            <a href="{{ route('admin.rewards.create') }}" class="px-8 py-4 bg-admin-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-admin-primary/20 hover:scale-105 transition-all flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Manifest New Perk
            </a>
        </div>

        <!-- Pulse Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Active Perks</p>
                <h3 class="text-3xl font-black text-secondary">{{ \App\Models\Reward::active()->count() }}</h3>
            </div>
            <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Redemptions</p>
                <h3 class="text-3xl font-black text-blue-500">{{ \DB::table('reward_claims')->count() }}</h3>
            </div>
            <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Stock Level</p>
                <h3 class="text-3xl font-black text-emerald-500">{{ \App\Models\Reward::active()->sum('max_usage') - \App\Models\Reward::active()->sum('usage_count') }}</h3>
            </div>
        </div>

        <!-- Perks Registry -->
        <div class="glass rounded-[2.5rem] border-white/60 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50 bg-slate-50/30">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Reward Asset</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Threshold</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Stock & Expiry</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Command</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($rewards as $reward)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-secondary text-sm group-hover:text-admin-primary transition-colors line-clamp-1 max-w-[200px]">{{ $reward->title }}</h4>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 line-clamp-1 italic max-w-[200px]">{{ $reward->description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-lg text-[10px] font-black tracking-tighter shadow-sm shadow-amber-500/10">
                                    {{ number_format($reward->karma_required) }} KP
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-[9px] font-black uppercase tracking-widest text-slate-400">
                                    <span>Usage Pool</span>
                                    <span>{{ $reward->usage_count }}/{{ $reward->max_usage }}</span>
                                </div>
                                <div class="w-32 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 transition-all" style="width: {{ ($reward->usage_count / $reward->max_usage) * 100 }}%"></div>
                                </div>
                                @if($reward->expires_at)
                                <p class="text-[8px] font-bold text-rose-400">Ends: {{ $reward->expires_at->format('M d, H:i') }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.rewards.edit', $reward) }}" class="p-2 bg-slate-100 text-slate-400 hover:bg-admin-primary hover:text-white rounded-lg transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.rewards.destroy', $reward) }}" method="POST" onsubmit="return confirm('Purge this perk? Claims will remain in history.');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-slate-100 text-slate-400 hover:bg-rose-500 hover:text-white rounded-lg transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                </div>
                                <p class="text-slate-400 font-bold italic tracking-wide">No perks manifested in the pool.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $rewards->links() }}
        </div>
    </div>
</x-admin-layout>
