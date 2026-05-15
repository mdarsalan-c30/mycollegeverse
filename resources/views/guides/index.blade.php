@extends('layouts.hub')

@section('title', 'Academic Hub | MyCollegeVerse')

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-12">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
            <div class="max-w-2xl">
                <h1 class="text-5xl md:text-6xl font-black text-slate-900 tracking-tight mb-6 leading-tight italic">Academic <span class="gradient-text">Hub.</span></h1>
                <p class="text-lg font-bold text-slate-500 leading-relaxed">The multiverse of syllabi, college guides, and academic insights. Peer-verified and strategically organized.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Hub Status</p>
                    <p class="text-xs font-bold text-green-500 flex items-center justify-end gap-2">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        Live Nodes
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="glass p-2 rounded-[2.5rem] mb-16 shadow-2xl shadow-primary/5">
            <form action="{{ route('guides.index') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                <div class="flex-1 relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by title, university, or subject..." class="w-full h-16 bg-white/50 border-none rounded-[2rem] px-8 pl-14 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all">
                    <div class="absolute left-6 top-5.5 text-slate-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <div class="flex gap-2 p-1">
                    <select name="category" class="h-14 bg-white border border-slate-100 rounded-[1.5rem] px-6 text-[10px] font-black uppercase tracking-widest text-slate-500 focus:ring-4 focus:ring-primary/5 transition-all">
                        <option value="">All Categories</option>
                        @foreach(['Syllabus', 'Notes', 'Practical', 'Project', 'College Guide', 'Admission', 'Career', 'Notice'] as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="h-14 px-10 bg-primary text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-[1.5rem] hover:bg-blue-600 transition-all shadow-lg shadow-primary/20">
                        Filter Nodes
                    </button>
                </div>
            </form>
        </div>

        <!-- Guides Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($guides as $guide)
            <div class="group bg-white rounded-[3rem] p-10 shadow-sm border border-slate-50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[5rem] -mr-16 -mt-16 group-hover:scale-110 transition-transform"></div>

                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-2xl group-hover:bg-primary/10 transition-colors">
                            @if($guide->category == 'Syllabus') 📑
                            @elseif($guide->category == 'Notes') 📄
                            @elseif($guide->category == 'Practical') 🔬
                            @elseif($guide->category == 'Project') 🏗️
                            @elseif($guide->category == 'College Guide') 🏫
                            @elseif($guide->category == 'Notice') 🔔
                            @else 💡
                            @endif
                        </div>
                        <span class="px-4 py-1.5 bg-white border border-slate-100 text-slate-400 text-[9px] font-black uppercase tracking-widest rounded-full">
                            {{ $guide->category }}
                        </span>
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 leading-tight group-hover:text-primary transition-colors mb-6 min-h-[4rem]">
                        <a href="{{ route('guides.show', $guide->slug) }}">{{ $guide->title }}</a>
                    </h3>

                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-8 line-clamp-2">
                        {{ Str::limit(strip_tags($guide->content), 100) }}
                    </p>

                    <div class="flex items-center justify-between pt-8 border-t border-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-400">
                                {{ substr($guide->user->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="text-left">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Archivist</p>
                                <p class="text-[10px] font-bold text-slate-600">{{ $guide->user->name ?? 'Archivist' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($guide->file_path)
                            <span class="w-8 h-8 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-xs shadow-sm shadow-red-100" title="PDF Available">PDF</span>
                            @endif
                            <a href="{{ route('guides.show', $guide->slug) }}" class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-primary transition-colors shadow-lg shadow-black/5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-32 text-center glass rounded-[4rem]">
                <div class="text-8xl mb-8 opacity-20">🔭</div>
                <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-4">Signal Silence</h3>
                <p class="text-slate-500 font-bold text-sm max-w-sm mx-auto mb-10">No guides found in this academic sector. Be the first to manifest knowledge.</p>
                @auth
                <a href="{{ route('guides.create') }}" class="inline-flex items-center px-10 py-4 bg-primary text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">Manifest New Node</a>
                @endauth
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-20">
            {{ $guides->links() }}
        </div>
    </div>
@endsection
