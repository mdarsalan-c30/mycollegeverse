<x-admin-layout>
    <x-slot name="title">Pages Hub | Admin Control Tower</x-slot>

    <div class="space-y-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-admin-dark tracking-tight">Portal Pages Hub</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Manage all SEO-critical nodes and legal content</p>
            </div>
            <a href="{{ route('admin.pages.create') }}" class="bg-admin-primary text-white px-6 py-3 rounded-2xl font-black text-xs shadow-lg shadow-admin-primary/25 hover:scale-105 transition-transform flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                INITIALIZE NEW PAGE
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-500/10 border border-green-500/20 text-green-600 rounded-2xl text-xs font-bold italic">
                {{ session('success') }}
            </div>
        @endif

        <div class="glass overflow-hidden rounded-[2.5rem] border-white/50 shadow-sm">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 border-b border-admin-border italic">
                    <tr>
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Page Identity</th>
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Slug Node</th>
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Updated At</th>
                        <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Commands</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border/50">
                    @forelse($pages as $page)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-10 py-6">
                                <p class="font-bold text-admin-dark text-sm">{{ $page->title }}</p>
                                <p class="text-[10px] text-slate-400 truncate max-w-xs">{{ Str::limit($page->meta_description, 50) }}</p>
                            </td>
                            <td class="px-10 py-6">
                                <code class="text-[10px] bg-slate-100 px-2 py-1 rounded text-admin-primary font-bold">/p/{{ $page->slug }}</code>
                            </td>
                            <td class="px-10 py-6">
                                @if($page->is_active)
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[9px] font-black bg-green-100 text-green-600 uppercase tracking-widest">Active Node</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-[9px] font-black bg-slate-100 text-slate-400 uppercase tracking-widest">Offline</span>
                                @endif
                            </td>
                            <td class="px-10 py-6">
                                <p class="text-[10px] font-bold text-slate-500 uppercase">{{ $page->updated_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="p-2 text-slate-300 hover:text-admin-primary transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="p-2 text-slate-300 hover:text-admin-primary transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Terminate this page node permanently?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-300 hover:text-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-20 text-center">
                                <p class="text-sm font-bold text-slate-400 italic">No Portal Pages specialized yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
