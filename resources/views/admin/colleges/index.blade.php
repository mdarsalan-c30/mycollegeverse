<x-admin-layout>
    <div class="space-y-8" x-data="{ 
        openImport: false, 
        openCreate: false,
        openEdit: false,
        editNode: { 
            id: '', 
            name: '', 
            type: '',
            streams: [],
            state: '',
            city: '',
            location: '', 
            description: '', 
            thumbnail_url: '', 
            tags: '' 
        }
    }">
        <!-- Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary tracking-tight">Institutional Registry</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Managing the academic nodes of the multiverse</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="button" @click="openImport = true" class="px-6 py-4 bg-admin-dark text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-admin-dark/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Bulk Injection 🚀
                </button>
                <button type="button" @click="openCreate = true" class="px-6 py-4 bg-admin-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-admin-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Initialize Node
                </button>
            </div>
        </div>

        <!-- College Registry Table (Stitch UI Mirror) -->
        <div class="bg-white border border-admin-border rounded-[2.5rem] overflow-hidden shadow-sm shadow-slate-100">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-admin-border">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Institutional Node</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Attributes</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Location</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 italic">
                    @forelse($colleges as $college)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <img src="{{ $college->thumbnail_url }}" class="w-14 h-14 rounded-2xl object-cover shadow-sm bg-slate-50 border border-slate-100 group-hover:scale-105 transition-transform" />
                                <div>
                                    <p class="text-sm font-black text-admin-dark">{{ $college->name }}</p>
                                    <p class="text-[9px] font-bold text-admin-primary uppercase tracking-widest">/{{ $college->slug }}</p>
                                    <div class="flex gap-1 mt-1">
                                        @foreach(array_slice($college->streams ?? [], 0, 2) as $s)
                                            <span class="text-[7px] font-black bg-slate-100 text-slate-400 px-1 rounded uppercase tracking-tighter">{{ $s }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">{{ $college->type ?? 'General' }}</p>
                                <p class="text-[9px] font-bold text-slate-400 italic">⭐ {{ number_format($college->cached_rating ?? 0, 1) }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-black text-slate-600 italic leading-none">{{ $college->city }}, {{ $college->state }}</p>
                            <p class="text-[8px] font-bold text-slate-400 mt-1 uppercase tracking-widest">{{ Str::limit($college->location, 20) }}</p>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" 
                                @click="openEdit = true; editNode = { 
                                    id: {{ json_encode($college->slug) }}, 
                                    name: {{ json_encode($college->name) }}, 
                                    type: {{ json_encode($college->type) }},
                                    streams: {{ json_encode($college->streams ?? []) }},
                                    state: {{ json_encode($college->state) }},
                                    city: {{ json_encode($college->city) }},
                                    location: {{ json_encode($college->location) }}, 
                                    description: {{ json_encode($college->description) }}, 
                                    thumbnail_url: {{ json_encode($college->thumbnail_url) }}, 
                                    tags: {{ json_encode(is_array($college->tags) ? implode(', ', $college->tags) : ($college->tags ?? '')) }} 
                                }"
                                        class="p-3 text-slate-400 hover:text-admin-primary transition-all rounded-xl" title="Deep Scan Hub">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <form action="{{ route('admin.colleges.destroy', $college) }}" method="POST" class="inline" onsubmit="return confirm('Purge this institution node?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-3 text-red-500 hover:bg-red-500/10 transition-all rounded-xl" title="Purge Node">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.3em] italic">No institutions established in this sector.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $colleges->links() }}
        </div>

        <!-- Massive Injection Modal -->
        <div x-show="openImport" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-admin-secondary/40 backdrop-blur-md px-4 overflow-y-auto"
             x-cloak>
            <div @click.away="openImport = false" class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl p-12 my-10 space-y-8 text-left border border-slate-100 flex flex-col italic">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-admin-dark text-white rounded-2xl flex items-center justify-center text-2xl shadow-xl shadow-admin-dark/20">🚀</div>
                        <div>
                            <h3 class="text-2xl font-black text-admin-secondary leading-none">Bulk Injection Engine</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Inject massive institutional data nodes</p>
                        </div>
                    </div>
                    <button type="button" @click="openImport = false" class="text-slate-300 hover:text-slate-600">✕</button>
                </div>

                <form action="{{ route('admin.colleges.import') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                    @csrf
                    <div class="space-y-4">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Pathway A: Standard CSV Upload</label>
                        <div class="border-2 border-dashed border-slate-100 rounded-[2.5rem] p-8 flex flex-col items-center justify-center bg-slate-50/50 hover:bg-slate-50 transition-colors group relative cursor-pointer">
                            <input type="file" name="import_file" class="absolute inset-0 opacity-0 cursor-pointer">
                            <span class="text-2xl mb-2 group-hover:scale-125 transition-transform">📄</span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Drop node or browse<br><span class="opacity-50 text-[8px]">Name | Type | Streams | State | City | Location | Description | Logo | Tags</span></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Pathway B: Terminal (ChatGPT Format)</label>
                        <textarea name="paste_data" rows="6" class="w-full bg-slate-50 border-none rounded-[2.5rem] px-8 py-8 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 transition-all italic placeholder:text-slate-300" placeholder="IIT Delhi | Government | Engineering, MBA | Delhi | New Delhi | Hauz Khas | ..."></textarea>
                    </div>

                    <div class="flex items-center gap-6 pt-4">
                        <button type="button" @click="openImport = false" class="flex-1 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Abort</button>
                        <button type="submit" class="flex-[3] py-5 bg-admin-dark text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-admin-dark/20 hover:scale-[1.02] active:scale-95 transition-all">Start Injection Flux</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Node Initialization Modal -->
        <div x-show="openCreate" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-admin-secondary/40 backdrop-blur-md px-4 overflow-y-auto"
             x-cloak>
            <div @click.away="openCreate = false" class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl p-12 my-10 space-y-8 text-left border border-slate-100 flex flex-col italic">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-admin-primary text-white rounded-2xl flex items-center justify-center text-2xl shadow-xl shadow-admin-primary/20">🏛️</div>
                        <div>
                            <h3 class="text-2xl font-black text-admin-secondary leading-none">New Node Initialization</h3>
                        </div>
                    </div>
                    <button type="button" @click="openCreate = false" class="text-slate-300 hover:text-slate-600">✕</button>
                </div>

                <form action="{{ route('admin.colleges.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs font-bold">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Institution Name</label>
                            <input type="text" name="name" required placeholder="e.g. IIT Madras" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">College Type</label>
                            <select name="type" required class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                                <option value="">Select Type</option>
                                @foreach(config('college_metadata.types') as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Advanced Streams (Multi-select)</label>
                        <div class="grid grid-cols-3 md:grid-cols-4 gap-2 bg-slate-50 p-6 rounded-3xl border border-slate-100 max-h-40 overflow-y-auto">
                            @foreach(config('college_metadata.streams') as $stream)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="streams[]" value="{{ $stream }}" class="rounded bg-white border-slate-200 text-admin-primary focus:ring-admin-primary">
                                    <span class="text-[9px] font-bold text-slate-500 group-hover:text-admin-secondary transition-colors">{{ $stream }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs font-bold">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">State Hub</label>
                            <select name="state" required class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                                <option value="">Select State</option>
                                @foreach(config('college_metadata.states') as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">City Node</label>
                            <input type="text" name="city" required placeholder="e.g. Chennai" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Global Coordinates (Address/Location)</label>
                        <input type="text" name="location" required placeholder="e.g. Sardar Patel Road, Adyar" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-xs font-bold">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">High-Fidelity Description</label>
                        <textarea name="description" rows="3" required placeholder="Institutional narrative..." class="w-full bg-slate-50 border-none rounded-2xl px-6 py-5 text-xs font-bold"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs font-bold">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Thumbnail/Logo URL</label>
                            <input type="url" name="thumbnail_url" placeholder="https://..." class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Core Tags (comma separated)</label>
                            <input type="text" name="tags" placeholder="AKTU, NIRF-1, Heritage" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-5 bg-admin-primary text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-admin-primary/20 hover:scale-[1.01] active:scale-95 transition-all">Finalize Initialization Flux</button>
                </form>
            </div>
        </div>

        <!-- Node Re-calibration Modal (Edit) -->
        <div x-show="openEdit" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[102] flex items-center justify-center bg-admin-secondary/40 backdrop-blur-md px-4 overflow-y-auto"
             x-cloak>
            <div @click.away="openEdit = false" class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl p-12 my-10 space-y-8 text-left border border-slate-100 flex flex-col italic">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-admin-primary text-white rounded-2xl flex items-center justify-center text-2xl shadow-xl shadow-admin-primary/20">⚙️</div>
                        <div>
                            <h3 class="text-2xl font-black text-admin-secondary leading-none">Node Re-calibration</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Adjusting institutional coordinates</p>
                        </div>
                    </div>
                    <button type="button" @click="openEdit = false" class="text-slate-300 hover:text-slate-600">✕</button>
                </div>

                <form :action="`/admin/colleges/${editNode.id}`" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs font-bold">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Institution Name</label>
                            <input type="text" name="name" x-model="editNode.name" required class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">College Type</label>
                            <select name="type" x-model="editNode.type" required class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                                <option value="">Select Type</option>
                                @foreach(config('college_metadata.types') as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Advanced Streams (Multi-select)</label>
                        <div class="grid grid-cols-3 md:grid-cols-4 gap-2 bg-slate-50 p-6 rounded-3xl border border-slate-100 max-h-40 overflow-y-auto">
                            @foreach(config('college_metadata.streams') as $stream)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="streams[]" value="{{ $stream }}" 
                                           :checked="editNode.streams.includes('{{ $stream }}')"
                                           class="rounded bg-white border-slate-200 text-admin-primary focus:ring-admin-primary">
                                    <span class="text-[9px] font-bold text-slate-500 group-hover:text-admin-secondary transition-colors">{{ $stream }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs font-bold">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">State Hub</label>
                            <select name="state" x-model="editNode.state" required class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                                <option value="">Select State</option>
                                @foreach(config('college_metadata.states') as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">City Node</label>
                            <input type="text" name="city" x-model="editNode.city" required class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-xs font-bold">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Global Coordinates (Location)</label>
                        <input type="text" name="location" x-model="editNode.location" required class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">High-Fidelity Description</label>
                        <textarea name="description" rows="3" x-model="editNode.description" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-5 text-xs font-bold"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs font-bold">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Thumbnail/Logo URL</label>
                            <input type="url" name="thumbnail_url" x-model="editNode.thumbnail_url" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-xs font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Core Tags</label>
                            <input type="text" name="tags" x-model="editNode.tags" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-xs font-bold">
                        </div>
                    </div>

                    <div class="flex items-center gap-6 pt-4">
                        <button type="button" @click="openEdit = false" class="flex-1 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest transition-all">Abort</button>
                        <button type="submit" class="flex-[3] py-5 bg-admin-primary text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-admin-primary/20 hover:scale-[1.02] active:scale-95 transition-all">Submit Calibration Flux</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
