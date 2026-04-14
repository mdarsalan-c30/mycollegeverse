@extends('layouts.admin')

@section('title', 'Editorial Hub | Control Tower')

@section('content')
<div class="space-y-10">
    <!-- Header Node -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-4xl font-black text-secondary tracking-tight">Editorial Hub</h1>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-2 italic">Command Nexus for High-Performance SEO Content</p>
        </div>
        <a href="{{ route('admin.blogs.create') }}" class="px-8 py-4 bg-admin-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-admin-primary/20 hover:scale-105 transition-all flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Manifest New Article
        </a>
    </div>

    <!-- Analytics Pulse -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Avg SEO Health</p>
            <h3 class="text-3xl font-black text-emerald-500">{{ round($blogs->getCollection()->avg('seo_score') ?? 0) }}%</h3>
        </div>
        <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Insights</p>
            <h3 class="text-3xl font-black text-secondary">{{ $blogs->total() }} Articles</h3>
        </div>
        <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Multiverse Views</p>
            <h3 class="text-3xl font-black text-blue-500">{{ number_format($blogs->sum('views')) }}</h3>
        </div>
    </div>

    <!-- Article Registry -->
    <div class="glass rounded-[2.5rem] border-white/60 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-50 bg-slate-50/30">
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Article Focus</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">SEO Hub</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Command</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($blogs as $blog)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 flex-shrink-0 overflow-hidden">
                                @if($blog->featured_image)
                                    <img src="{{ $blog->featured_image_url }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-black text-secondary text-sm group-hover:text-admin-primary transition-colors">{{ $blog->title }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 mt-1 italic">{{ optional($blog->author)->name ?? 'System Archon' }} · {{ optional($blog->created_at)->format('M d, Y') ?? 'Eternal' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-6">
                            <div class="text-center">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-tighter mb-1">SEO</p>
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black {{ $blog->seo_score >= 80 ? 'bg-emerald-100 text-emerald-600' : ($blog->seo_score >= 50 ? 'bg-amber-100 text-amber-600' : 'bg-rose-100 text-rose-600') }}">
                                    {{ $blog->seo_score }}%
                                </span>
                            </div>
                            <div class="text-center">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-tighter mb-1">AI</p>
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black bg-purple-100 text-purple-600">
                                    {{ $blog->ai_score }}%
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        @if($blog->is_published)
                            <span class="flex items-center gap-1.5 text-[9px] font-black text-emerald-500 uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse shadow-sm shadow-emerald-500/50"></span>
                                Live
                            </span>
                        @else
                            <span class="flex items-center gap-1.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="p-2 bg-slate-100 text-slate-400 hover:bg-admin-primary hover:text-white rounded-lg transition-all ripple">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('Eradicate this article from the multiverse?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-slate-100 text-slate-400 hover:bg-rose-500 hover:text-white rounded-lg transition-all">
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
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="text-slate-400 font-bold italic tracking-wide">No editorial assets manifested yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $blogs->links() }}
    </div>
</div>
@endsection
