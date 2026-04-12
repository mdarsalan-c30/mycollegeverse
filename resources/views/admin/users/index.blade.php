<x-admin-layout>
    <div class="space-y-8" x-data="{ openBlock: false, blockUser: {id: '', name: ''} }">
        <!-- Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary tracking-tight">Citizen Registry</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Master identity management for the academic multiverse</p>
            </div>
            
            <div class="flex items-center gap-4">
                <form action="{{ route('admin.users') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or id..." class="w-72 h-12 bg-white border border-admin-border rounded-2xl px-6 pl-12 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    <div class="absolute left-4 top-3.5 text-slate-300 group-focus-within:text-admin-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-100 pb-px text-xs font-black uppercase tracking-widest italic">
            <a href="{{ route('admin.users', ['status' => 'all']) }}" class="px-6 py-3 transition-all {{ request('status', 'all') == 'all' ? 'text-admin-primary border-b-2 border-admin-primary' : 'text-slate-400 hover:text-slate-600' }}">All Citizens</a>
            <a href="{{ route('admin.users', ['status' => 'active']) }}" class="px-6 py-3 transition-all {{ request('status') == 'active' ? 'text-green-600 border-b-2 border-green-600' : 'text-slate-400 hover:text-slate-600' }}">Active</a>
            <a href="{{ route('admin.users', ['status' => 'banned']) }}" class="px-6 py-3 transition-all {{ request('status') == 'banned' ? 'text-red-600 border-b-2 border-red-600' : 'text-slate-400 hover:text-slate-600' }}">Banned</a>
        </div>

        <!-- User Registry Table (Stitch UI Mirror) -->
        <div class="bg-white border border-admin-border rounded-[2.5rem] overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-admin-border">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Citizen Details</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Institutional Node</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Identity Role</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Registry Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 italic">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <img src="{{ $user->profile_photo_url }}" class="w-12 h-12 rounded-2xl object-cover shadow-sm group-hover:scale-105 transition-transform" />
                                <div>
                                    <p class="text-sm font-black text-admin-dark">{{ $user->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <p class="text-[11px] font-black text-slate-600 italic leading-none">{{ $user->college->name ?? 'Global Hub' }}</p>
                                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-tighter mt-1">ID: {{ $user->college_id ?? 'MCV' }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ 
                                $user->role === 'admin' ? 'bg-admin-dark text-white' : 
                                ($user->role === 'contributor' ? 'bg-indigo-500/10 text-indigo-600' : 'bg-slate-100 text-slate-500') 
                            }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                <span class="text-[10px] font-black uppercase tracking-tighter {{ $user->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $user->status }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($user->role === 'student' && Auth::id() !== $user->id)
                                    <form action="{{ route('admin.users.promote', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button title="Promote to Contributor" class="p-2 text-slate-400 hover:text-indigo-600 transition-all hover:scale-110">
                                            🚀
                                        </button>
                                    </form>
                                @endif

                                @if($user->status === 'active' && Auth::id() !== $user->id)
                                    <button @click="openBlock = true; blockUser = {id: '{{ $user->username }}', name: '{{ $user->name }}'}" title="Block Citizen" class="p-2 text-slate-400 hover:text-red-600 transition-all hover:scale-110">
                                        🚫
                                    </button>
                                @elseif(Auth::id() !== $user->id)
                                    <form action="{{ route('admin.users.status', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="active">
                                        <button title="Unblock Citizen" class="p-2 text-slate-400 hover:text-green-600 transition-all hover:scale-110">
                                            ✅
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] italic">No citizens found matching your search</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- High-Fidelity Pagination -->
        <div class="pt-6">
            {{ $users->links() }}
        </div>

        <!-- Block Modal (Alpine.js) - Moved out of table for stability -->
        <div x-show="openBlock" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-admin-secondary/40 backdrop-blur-sm px-4"
             x-cloak>
            <div @click.away="openBlock = false" class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-10 space-y-6 text-left border border-slate-100 flex flex-col italic">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center text-xl">⚠️</div>
                    <div>
                        <h3 class="text-xl font-black text-admin-secondary leading-none">Security Flag</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Restrict access for <span x-text="blockUser.name"></span></p>
                    </div>
                </div>

                <form :action="`/admin/users/${blockUser.id}/status`" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="status" value="banned">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Registry Note (Reason)</label>
                        <textarea name="reason" rows="3" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-xs font-bold focus:ring-4 focus:ring-red-500/10 transition-all" placeholder="Enter reason for administrative restriction..."></textarea>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="button" @click="openBlock = false" class="flex-1 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cancel</button>
                        <button type="submit" class="flex-[2] py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-600/20 hover:scale-[1.02] active:scale-95 transition-all">Apply Restriction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
