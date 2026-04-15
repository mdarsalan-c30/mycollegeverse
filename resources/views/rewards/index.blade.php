<x-app-layout>
    @section('title', 'Academic Perks Hub | MyCollegeVerse')

    <div class="space-y-10 pb-20">
        <!-- Header Node: The Perks Nexus -->
        <div class="relative overflow-hidden bg-gradient-to-br from-primary to-indigo-700 rounded-[3rem] p-10 text-white shadow-2xl shadow-primary/30">
            <div class="relative z-10 max-w-2xl">
                <h1 class="text-5xl font-black tracking-tight mb-4">Academic Perks Hub</h1>
                <p class="text-indigo-100 text-lg font-medium">Manifest your hard-earned Karma into professional assets. Redem courses, tools, and institutional benefits.</p>
                
                <div class="mt-8 flex items-center gap-6">
                    <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-3xl border border-white/10 shadow-lg">
                        <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1">Available Karma balance</p>
                        <div class="flex items-center gap-2">
                            <span class="text-3xl font-black">{{ number_format(Auth::user()->karma) }}</span>
                            <span class="text-xs font-bold text-indigo-300">KP</span>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-3xl border border-white/10 shadow-lg hidden md:block">
                        <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1">Redemptions Made</p>
                        <div class="flex items-center gap-2">
                            <span class="text-3xl font-black">{{ Auth::user()->claimedRewards->count() }}</span>
                            <span class="text-xs font-bold text-indigo-300">Perks</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Abstract background art -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute right-10 bottom-10 w-40 h-40 bg-indigo-400/20 rounded-full blur-2xl"></div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" class="bg-emerald-50 border border-emerald-100 p-6 rounded-3xl flex items-center gap-4 animate-bounce-subtle">
                <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Nexus Synchronized</p>
                    <p class="text-sm font-bold text-slate-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-amber-50 border border-amber-100 p-6 rounded-3xl flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Gateway Locked</p>
                    <p class="text-sm font-bold text-slate-700">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <!-- Rewards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($rewards as $reward)
                @php
                    $isClaimed = in_array($reward->id, $claimedRewardIds);
                    $canAfford = Auth::user()->karma >= $reward->karma_required;
                    $isAvailable = $reward->is_available;
                @endphp
                <div class="group relative bg-white border border-slate-100 rounded-[2.5rem] p-8 transition-all hover:shadow-2xl hover:shadow-primary/5 hover:-translate-y-2 {{ !$isAvailable && !$isClaimed ? 'opacity-75' : '' }}">
                    @if($isClaimed)
                        <div class="absolute -top-4 -right-4 bg-emerald-500 text-white px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-200 z-20">
                            Claimed
                        </div>
                    @elseif(!$isAvailable)
                        <div class="absolute -top-4 -right-4 bg-rose-500 text-white px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-200 z-20">
                            {{ $reward->usage_count >= $reward->max_usage ? 'Sold Out' : 'Expired' }}
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Icon/Thumb -->
                        <div class="w-16 h-16 rounded-[1.5rem] {{ $isClaimed ? 'bg-emerald-100 text-emerald-600' : 'bg-indigo-50 text-primary' }} flex items-center justify-center transition-colors group-hover:scale-110 duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>

                        <!-- Content -->
                        <div>
                            <h3 class="text-xl font-black text-secondary leading-tight line-clamp-2 group-hover:text-primary transition-colors">{{ $reward->title }}</h3>
                            <p class="text-slate-400 text-sm font-medium mt-2 line-clamp-3 italic tracking-tight">{{ $reward->description }}</p>
                        </div>

                        <!-- Meta: Stock & Cost -->
                        <div class="flex items-center justify-between border-y border-slate-50 py-4">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Requirement</p>
                                <p class="font-black text-amber-500">{{ number_format($reward->karma_required) }} KP</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Left in Pool</p>
                                <p class="font-black text-emerald-500">{{ $reward->max_usage - $reward->usage_count }} / {{ $reward->max_usage }}</p>
                            </div>
                        </div>

                        <!-- Stock Progress -->
                        <div class="space-y-1.5">
                            <div class="h-1.5 bg-slate-50 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-primary to-indigo-500 transition-all duration-1000" style="width: {{ ($reward->usage_count / $reward->max_usage) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- Action -->
                        @if($isClaimed)
                            <a href="{{ $reward->claim_link }}" target="_blank" class="block w-full text-center py-4 bg-emerald-50 text-emerald-600 font-black text-xs uppercase tracking-widest rounded-2xl border-2 border-emerald-100 hover:bg-emerald-500 hover:text-white transition-all shadow-xl shadow-emerald-500/5">
                                Access Reward 🚀
                            </a>
                        @elseif(!$isAvailable)
                            <button disabled class="w-full py-4 bg-slate-50 text-slate-300 font-black text-xs uppercase tracking-widest rounded-2xl cursor-not-allowed">
                                Manifestation Closed
                            </button>
                        @elseif(!$canAfford)
                            <button disabled class="w-full py-4 bg-slate-50 text-slate-400 font-black text-xs uppercase tracking-widest rounded-2xl border border-slate-100">
                                Need More Karma ({{ number_format($reward->karma_required - Auth::user()->karma) }}+)
                            </button>
                        @else
                            <form action="{{ route('rewards.claim', $reward) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Deduct {{ number_format($reward->karma_required) }} Karma Points to manifest this perk?')" class="w-full py-4 bg-primary text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl shadow-primary/20 hover:scale-[1.03] active:scale-95 transition-all">
                                    Finalize Redemption 🎁
                                </button>
                            </form>
                        @endif

                        @if($reward->expires_at && !$isClaimed)
                            <div class="flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest text-rose-400 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Timeline ends: {{ $reward->expires_at->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-100 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745V20a2 2 0 002 2h14a2 2 0 002-2v-6.745zM16 8V5a2 2 0 00-2-2H10a2 2 0 00-2 2v3m4 6.138V21" /></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-500 uppercase tracking-widest">No Perks Manifested</h3>
                    <p class="text-slate-400 font-bold italic mt-2">Institutional rewards are currently being calibrated.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
