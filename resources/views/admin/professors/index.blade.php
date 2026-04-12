<x-admin-layout>
    <div class="space-y-8" x-data="{ openCreate: false }">
        <!-- Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary tracking-tight">Faculty Registry</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Managing the academic advisors of the multiverse</p>
            </div>
            
            <button type="button" @click="openCreate = true" class="px-6 py-4 bg-admin-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-admin-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                Initialize Faculty Node
            </button>
        </div>

        <!-- Faculty Registry Table (Stitch UI Mirror) -->
        <div class="bg-white border border-admin-border rounded-[2.5rem] overflow-hidden shadow-sm shadow-slate-100">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-admin-border">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Faculty Identity</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Department Node</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Institutional Anchor</th>
                        <th class="px-8 py-6 text-[10px) font-black text-slate-400 uppercase tracking-widest italic text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 italic">
                    @forelse($professors as $professor)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <img src="{{ $professor->profile_pic ?? 'https://via.placeholder.com/100?text=Advisor' }}" class="w-12 h-12 rounded-xl object-cover shadow-sm bg-slate-100 border border-slate-200" />
                                <div>
                                    <p class="text-sm font-black text-admin-dark">{{ $professor->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Employee Node #{{ $professor->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-4 py-1.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-full">
                                {{ $professor->department }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-black text-admin-primary italic leading-none">{{ $professor->college->name ?? 'Global Hub' }}</p>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.professors.destroy', $professor) }}" method="POST" class="inline" onsubmit="return confirm('Purge this faculty node?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-3 text-red-500 hover:bg-red-500/10 transition-all rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.3em] italic">No faculty nodes initialized.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $professors->links() }}
        </div>

        <!-- Create Modal -->
        <div x-show="openCreate" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-admin-secondary/40 backdrop-blur-md px-4 overflow-y-auto"
             x-cloak>
            <div @click.away="openCreate = false" class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl p-12 my-10 space-y-8 text-left border border-slate-100 flex flex-col italic">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-admin-primary text-white rounded-2xl flex items-center justify-center text-2xl shadow-xl shadow-admin-primary/20">🎓</div>
                        <div>
                            <h3 class="text-2xl font-black text-admin-secondary leading-none">New Faculty Identity</h3>
                        </div>
                    </div>
                    <button type="button" @click="openCreate = false" class="text-slate-300 hover:text-slate-600">✕</button>
                </div>

                <form action="{{ route('admin.professors.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-5 text-xs font-bold">
                        <input type="text" name="name" required placeholder="Full Identity Name" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <input type="text" name="department" required placeholder="Department Node" class="h-14 bg-slate-50 border-none rounded-2xl px-6">
                            <select name="college_id" required class="h-14 bg-slate-50 border-none rounded-2xl px-6">
                                <option value="">Institutional Anchor</option>
                                @foreach(\App\Models\College::all() as $college)
                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="url" name="profile_pic" placeholder="Profile Identity URL (Image)" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                    </div>

                    <button type="submit" class="w-full py-5 bg-admin-primary text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-admin-primary/20">Establish Faculty Identity</button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
