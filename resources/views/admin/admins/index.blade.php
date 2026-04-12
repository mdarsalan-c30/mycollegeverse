<x-admin-layout>
    <div class="space-y-8" x-data="{ openCreate: false }">
        <!-- Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary tracking-tight">Command Admin Registry</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Authorized administrative identities and security staff</p>
            </div>
            
            <button type="button" @click="openCreate = true" class="px-6 py-4 bg-admin-secondary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-admin-secondary/20 hover:scale-[1.02] active:scale-95 transition-all">
                Authorize New Identity
            </button>
        </div>

        <!-- Admin Registry Table (Stitch UI Mirror) -->
        <div class="bg-white border border-admin-border rounded-[2.5rem] overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-admin-border">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Admin Identity</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Email Endpoint</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Authority Level</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 italic">
                    @foreach($admins as $admin)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-admin-secondary text-white flex items-center justify-center font-bold">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-black text-admin-dark">{{ $admin->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-300 uppercase italic tracking-tighter">@ {{ $admin->username }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-black text-slate-500 leading-none">{{ $admin->email }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[9px] font-black uppercase tracking-widest">
                                {{ $admin->id === 1 ? 'Master Authority' : 'Moderator Node' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($admin->id !== Auth::id() && $admin->id !== 1)
                                <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="inline" onsubmit="return confirm('Revoke all administrative authority for this identity?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-3 text-red-500 hover:bg-red-500/10 transition-all rounded-xl" title="Revoke Authority">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Authorize Modal -->
        <template x-if="openCreate">
            <div class="fixed inset-0 z-[100] flex items-center justify-center bg-admin-secondary/40 backdrop-blur-md px-4 overflow-y-auto italic">
                <div @click.away="openCreate = false" class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl p-12 my-10 space-y-8 text-left border border-slate-100 flex flex-col">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-admin-secondary text-white rounded-2xl flex items-center justify-center text-2xl shadow-xl shadow-admin-secondary/20">👑</div>
                            <div>
                                <h3 class="text-2xl font-black text-admin-secondary leading-none">Authorize Identity</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">New Administrative Node</p>
                            </div>
                        </div>
                        <button type="button" @click="openCreate = false" class="text-slate-300 hover:text-slate-600">✕</button>
                    </div>

                    <form action="{{ route('admin.admins.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-5 text-xs font-bold">
                            <input type="text" name="name" required placeholder="Full Name" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <input type="email" name="email" required placeholder="Email Address" class="h-14 bg-slate-50 border-none rounded-2xl px-6">
                                <input type="text" name="username" required placeholder="Username" class="h-14 bg-slate-50 border-none rounded-2xl px-6">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <input type="password" name="password" required placeholder="Secure Password" class="h-14 bg-slate-50 border-none rounded-2xl px-6">
                                <input type="password" name="password_confirmation" required placeholder="Confirm Password" class="h-14 bg-slate-50 border-none rounded-2xl px-6">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-5 bg-admin-secondary text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-admin-secondary/20 hover:scale-[1.02] active:scale-95 transition-all">
                            Initialize Authority
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-admin-layout>
