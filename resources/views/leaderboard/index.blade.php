<x-app-layout>
<div class="space-y-10 pb-24">

    {{-- ═══════ HEADER ═══════ --}}
    <div class="text-center space-y-3 pt-2">
        <div class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest mb-2">
            🏆 Academic Reputation Score
        </div>
        <h1 class="text-4xl md:text-5xl font-black text-slate-900">Academic Leaderboard</h1>
        <p class="text-slate-500 font-medium max-w-xl mx-auto text-sm">
            Real rankings based on contributions — notes, posts, reviews, likes & downloads.
        </p>

        {{-- My Rank Badge --}}
        @if($myRank)
        <div class="inline-flex items-center gap-3 mt-4 bg-white border border-slate-100 px-6 py-3 rounded-2xl shadow-sm">
            <span class="text-2xl">🎯</span>
            <div class="text-left">
                <div class="text-xs text-slate-400 font-bold uppercase tracking-widest">Your Rank</div>
                <div class="font-black text-slate-900">#{{ $myRank }} &bull; {{ number_format($myScore) }} pts</div>
            </div>
        </div>
        @endif
    </div>

    {{-- ═══════ TOP 3 PODIUM ═══════ --}}
    @if($top3->count() >= 1)
    <div class="grid md:grid-cols-3 gap-6 items-end max-w-4xl mx-auto">

        {{-- 2nd Place (Silver) --}}
        @if($top3->count() >= 2)
        @php $silver = $top3->get(1); @endphp
        <div class="order-2 md:order-1 glass p-8 rounded-[2.5rem] text-center space-y-5 border-slate-200/50 shadow-sm relative overflow-hidden" style="min-height:360px;display:flex;flex-direction:column;justify-content:center;">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-slate-300 to-slate-400"></div>
            <div class="relative inline-block mx-auto">
                <img src="{{ $silver->profile_photo_path ? 'https://ik.imagekit.io/studycubsfranchise'.$silver->profile_photo_path : 'https://ui-avatars.com/api/?name='.urlencode($silver->name).'&background=94a3b8&color=fff&size=128&bold=true' }}"
                     class="w-24 h-24 rounded-[2rem] mx-auto shadow-xl ring-4 ring-slate-100 object-cover" />
                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-gradient-to-br from-slate-300 to-slate-500 rounded-full flex items-center justify-center text-white font-black text-sm border-4 border-white shadow">2</div>
            </div>
            <div>
                <h4 class="text-lg font-black text-slate-800">{{ $silver->name }}</h4>
                <p class="text-xs font-bold text-slate-400 mt-0.5">{{ $silver->college ?? 'Student' }}</p>
            </div>
            <div class="bg-slate-100 text-slate-700 px-5 py-2 rounded-xl font-black text-base mx-auto">
                🥈 {{ number_format($silver->total_score) }} pts
            </div>
            <div class="flex flex-wrap justify-center gap-1.5 text-[10px] font-bold">
                <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-lg">📄 {{ floor($silver->note_pts / 50) }} notes</span>
                <span class="bg-green-50 text-green-600 px-2 py-1 rounded-lg">❤️ {{ floor($silver->like_pts / 5) }} likes</span>
            </div>
        </div>
        @endif

        {{-- 1st Place (Gold) --}}
        @php $gold = $top3->get(0); @endphp
        <div class="order-1 md:order-2 glass p-10 rounded-[3rem] text-center space-y-6 relative overflow-hidden shadow-2xl shadow-primary/10" style="min-height:420px;display:flex;flex-direction:column;justify-content:center;">
            <div class="absolute top-0 left-0 w-full h-3 bg-gradient-to-r from-primary via-violet-500 to-primary animate-pulse"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-primary/5 to-transparent pointer-events-none"></div>
            <div class="relative inline-block mx-auto">
                <img src="{{ $gold->profile_photo_path ? 'https://ik.imagekit.io/studycubsfranchise'.$gold->profile_photo_path : 'https://ui-avatars.com/api/?name='.urlencode($gold->name).'&background=2563eb&color=fff&size=160&bold=true' }}"
                     class="w-32 h-32 rounded-[2.5rem] mx-auto shadow-2xl ring-4 ring-primary/20 object-cover scale-105" />
                <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-black border-4 border-white shadow-lg text-sm">1</div>
                <div class="absolute -top-3 -left-2 text-3xl">👑</div>
            </div>
            <div>
                <a href="{{ route('profile.show', $gold->username) }}" class="text-2xl font-black text-slate-900 hover:text-primary transition-colors">{{ $gold->name }}</a>
                <p class="text-sm font-bold text-primary mt-1">{{ $gold->college ?? 'Student' }}</p>
            </div>
            <div class="bg-primary text-white px-7 py-3 rounded-2xl font-black text-xl shadow-lg shadow-primary/20 mx-auto">
                🥇 {{ number_format($gold->total_score) }} pts
            </div>
            <div class="flex flex-wrap justify-center gap-2 text-[10px] font-bold">
                <span class="bg-white/80 text-blue-600 px-2 py-1 rounded-lg">📄 {{ floor($gold->note_pts / 50) }} notes</span>
                <span class="bg-white/80 text-green-600 px-2 py-1 rounded-lg">❤️ {{ floor($gold->like_pts / 5) }} likes</span>
                <span class="bg-white/80 text-violet-600 px-2 py-1 rounded-lg">💬 {{ floor($gold->comment_pts / 10) }} comments</span>
            </div>
        </div>

        {{-- 3rd Place (Bronze) --}}
        @if($top3->count() >= 3)
        @php $bronze = $top3->get(2); @endphp
        <div class="order-3 glass p-8 rounded-[2.5rem] text-center space-y-5 border-amber-100/50 shadow-sm relative overflow-hidden" style="min-height:320px;display:flex;flex-direction:column;justify-content:center;">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-amber-600 to-amber-400"></div>
            <div class="relative inline-block mx-auto">
                <img src="{{ $bronze->profile_photo_path ? 'https://ik.imagekit.io/studycubsfranchise'.$bronze->profile_photo_path : 'https://ui-avatars.com/api/?name='.urlencode($bronze->name).'&background=b45309&color=fff&size=120&bold=true' }}"
                     class="w-20 h-20 rounded-[1.75rem] mx-auto shadow-xl ring-4 ring-amber-50 object-cover" />
                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-amber-600 rounded-full flex items-center justify-center text-white font-black text-xs border-4 border-white shadow">3</div>
            </div>
            <div>
                <h4 class="text-lg font-black text-slate-800">{{ $bronze->name }}</h4>
                <p class="text-xs font-bold text-slate-400 mt-0.5">{{ $bronze->college ?? 'Student' }}</p>
            </div>
            <div class="bg-amber-50 text-amber-700 px-5 py-2 rounded-xl font-black text-base mx-auto">
                🥉 {{ number_format($bronze->total_score) }} pts
            </div>
        </div>
        @endif

    </div>
    @endif

    {{-- ═══════ SCORING KEY ═══════ --}}
    <div class="max-w-4xl mx-auto bg-slate-50 rounded-[2rem] p-6 grid grid-cols-2 md:grid-cols-3 gap-3">
        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
            <div class="text-lg font-black text-primary">+50 pts</div>
            <div class="text-xs text-slate-500 font-bold">📄 Note Uploaded</div>
        </div>
        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
            <div class="text-lg font-black text-green-600">+10 pts</div>
            <div class="text-xs text-slate-500 font-bold">⬇️ Per Download</div>
        </div>
        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
            <div class="text-lg font-black text-violet-600">+20 pts</div>
            <div class="text-xs text-slate-500 font-bold">📢 Community Post</div>
        </div>
        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
            <div class="text-lg font-black text-pink-500">+5 pts</div>
            <div class="text-xs text-slate-500 font-bold">❤️ Like Received</div>
        </div>
        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
            <div class="text-lg font-black text-amber-500">+10 pts</div>
            <div class="text-xs text-slate-500 font-bold">💬 Comment Posted</div>
        </div>
        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
            <div class="text-lg font-black text-indigo-500">+15 pts</div>
            <div class="text-xs text-slate-500 font-bold">⭐ Prof Review</div>
        </div>
    </div>

    {{-- ═══════ FULL RANKINGS TABLE ═══════ --}}
    <div class="max-w-4xl mx-auto glass rounded-[2.5rem] overflow-hidden shadow-sm border-white/50">
        <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-black text-slate-900">Community Rankings</h3>
                <p class="text-xs text-slate-400 font-bold mt-0.5">All-time ARS scores from real activity</p>
            </div>
            <span class="px-4 py-1.5 bg-primary/10 text-primary rounded-xl font-black text-xs">Live Data</span>
        </div>

        @if($rest->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/60">
                        <th class="px-6 py-4">Rank</th>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4 hidden md:table-cell">Breakdown</th>
                        <th class="px-6 py-4 text-right">ARS Score</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($rest as $user)
                    <tr class="hover:bg-primary/5 transition-colors group {{ $user->id === Auth::id() ? 'bg-primary/5' : '' }}">
                        <td class="px-6 py-5">
                            <span class="font-black text-slate-400 group-hover:text-primary transition-colors text-sm">
                                @if($user->id === Auth::id()) 👤 @endif
                                #{{ $user->rank }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('profile.show', $user->username) }}">
                                    <img src="{{ $user->profile_photo_path ? 'https://ik.imagekit.io/studycubsfranchise'.$user->profile_photo_path.'?tr=w-80,q-80' : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&size=80&bold=true' }}"
                                         class="w-10 h-10 rounded-xl shadow-sm object-cover group-hover:scale-105 transition-transform" />
                                </a>
                                <div>
                                    <a href="{{ route('profile.show', $user->username) }}" class="font-black text-slate-800 text-sm hover:text-primary transition-colors">{{ $user->name }}</a>
                                    @if($user->college)
                                    <div class="text-[10px] text-slate-400 font-bold">{{ $user->college }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 hidden md:table-cell">
                            <div class="flex gap-1.5 flex-wrap">
                                @if($user->note_pts > 0)
                                    <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-black rounded-md">📄 {{ floor($user->note_pts/50) }}n</span>
                                @endif
                                @if($user->post_pts > 0)
                                    <span class="px-1.5 py-0.5 bg-violet-50 text-violet-600 text-[9px] font-black rounded-md">📢 {{ floor($user->post_pts/20) }}p</span>
                                @endif
                                @if($user->like_pts > 0)
                                    <span class="px-1.5 py-0.5 bg-pink-50 text-pink-600 text-[9px] font-black rounded-md">❤️ {{ floor($user->like_pts/5) }}l</span>
                                @endif
                                @if($user->comment_pts > 0)
                                    <span class="px-1.5 py-0.5 bg-amber-50 text-amber-600 text-[9px] font-black rounded-md">💬 {{ floor($user->comment_pts/10) }}c</span>
                                @endif
                                @if($user->review_pts > 0)
                                    <span class="px-1.5 py-0.5 bg-indigo-50 text-indigo-600 text-[9px] font-black rounded-md">⭐ {{ floor($user->review_pts/15) }}r</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <span class="bg-slate-100 group-hover:bg-primary group-hover:text-white px-4 py-1.5 rounded-xl text-sm font-black transition-all duration-200">
                                {{ number_format($user->total_score) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-16 text-slate-400 font-bold">
            <div class="text-4xl mb-3">📊</div>
            <p>Rankings below rank 3 will appear as more users contribute!</p>
        </div>
        @endif
    </div>

</div>
</x-app-layout>
