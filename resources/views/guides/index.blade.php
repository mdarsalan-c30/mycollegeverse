<x-app-layout>
    @section('title', 'Academic Hub | MyCollegeVerse')
    @section('meta_description', 'The ultimate repository for syllabi, college guides, and academic notices. Manifest your knowledge.')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2 uppercase">Academic Hub 🏛️</h1>
                <p class="text-slate-500 font-bold text-sm">The multiverse of syllabi, guides, and academic insights.</p>
            </div>
            
            @if(Auth::check() && Auth::user()->role !== 'recruiter')
            <a href="{{ route('guides.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                Manifest New Guide 🌌
            </a>
            @endif
        </div>

        <!-- Filter & Search -->
        <div class="glass p-6 rounded-[2.5rem] mb-10">
            <form action="{{ route('guides.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by title, university, or subject..." class="w-full h-14 bg-white/50 border border-slate-100 rounded-2xl px-6 pl-12 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all">
                    <div class="absolute left-4 top-4.5 text-slate-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <select name="category" class="h-14 bg-white/50 border border-slate-100 rounded-2xl px-6 text-sm font-bold text-slate-600 focus:ring-4 focus:ring-primary/5 transition-all">
                    <option value="">All Nodes</option>
                    @php
                        $categories = ['Syllabus', 'College Guide', 'Admission', 'Career', 'Notice'];
                    @endphp
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <button type="submit" class="h-14 px-10 bg-slate-900 text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-black transition-all">
                    Filter Nodes
                </button>
            </form>
        </div>

        <!-- Guides Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($guides as $guide)
            <div class="group bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all p-8 flex flex-col relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6">
                    <span class="px-3 py-1 bg-primary/5 text-primary text-[10px] font-black uppercase tracking-widest rounded-full ring-1 ring-primary/20">
                        {{ $guide->category }}
                    </span>
                </div>

                <div class="mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        @if($guide->category == 'Syllabus') 📑
                        @elseif($guide->category == 'College Guide') 🏫
                        @elseif($guide->category == 'Notice') 🔔
                        @else 💡
                        @endif
                    </div>
                    <h3 class="text-lg font-black text-slate-900 leading-tight group-hover:text-primary transition-colors">
                        <a href="{{ route('guides.show', $guide->slug) }}">{{ $guide->title }}</a>
                    </h3>
                </div>

                <p class="text-slate-500 text-xs font-bold leading-relaxed mb-6 line-clamp-3">
                    {{ Str::limit(strip_tags($guide->content), 120) }}
                </p>

                <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs mr-2">
                            {{ substr($guide->user->name, 0, 1) }}
                        </div>
                        <span class="text-xs text-gray-500 font-medium">{{ $guide->user->name }}</span>
                    </div>
                    <div class="flex items-center text-[10px] font-black text-slate-300 uppercase tracking-widest">
                        <span>{{ $guide->views }} Views</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center glass rounded-[3rem]">
                <div class="text-6xl mb-6">🪐</div>
                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-2">No guides found in this sector</h3>
                <p class="text-slate-400 font-bold text-sm">Be the first to manifest academic knowledge for your peers.</p>
                @if(Auth::check() && Auth::user()->role !== 'recruiter')
                <div class="mt-8">
                    <a href="{{ route('guides.create') }}" class="text-primary font-black text-xs uppercase tracking-widest hover:underline">Start Manifesting →</a>
                </div>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $guides->links() }}
        </div>
    </div>
</x-app-layout>
