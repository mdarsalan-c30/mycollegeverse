@extends('layouts.app')

@section('title', 'Knowledge Multiverse | MyCollegeVerse Blog')

@section('meta_description', 'Explore high-fidelity academic insights, college guides, and career strategies from the MyCollegeVerse editorial team.')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; }
    .clean-card {
        border-radius: 0;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    .clean-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.1);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-6 py-20">
    <!-- Header Node -->
    <div class="max-w-3xl mb-16">
        <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-6">Mastering the Multiverse: <br><span class="text-primary">Academic Insights</span></h1>
        <p class="text-lg text-slate-500 font-medium leading-relaxed">Strategic guides and high-fidelity insights curated to help you navigate your academic path with precision.</p>
    </div>

    <!-- Article Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($blogs as $blog)
        <article class="flex flex-col h-full bg-white clean-card overflow-hidden group">
            <a href="{{ route('blogs.show', $blog->slug) }}" class="block overflow-hidden bg-slate-100 aspect-[16/10]">
                @if($blog->featured_image)
                    <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"/></svg>
                    </div>
                @endif
            </a>
            
            <div class="p-8 flex-1 flex flex-col">
                <p class="text-[10px] font-black tracking-widest text-slate-400 mb-2">{{ optional($blog->author)->name ?? 'Multiverse Member' }} · {{ optional($blog->published_at)->format('M d, Y') ?? 'Luminated' }}</p>
                
                <h2 class="text-xl font-bold text-slate-900 leading-tight mb-4 group-hover:text-primary transition-colors">
                    <a href="{{ route('blogs.show', $blog->slug) }}">{{ $blog->title }}</a>
                </h2>
                
                <p class="text-sm text-slate-500 leading-relaxed mb-6 line-clamp-3">
                    {{ $blog->summary }}
                </p>
                
                <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[9px] font-black tracking-widest text-primary uppercase bg-primary/5 px-3 py-1.5 rounded-full">
                        {{ ceil(str_word_count(strip_tags($blog->content ?? '')) / 200) }} MIN READ
                    </span>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xs">
                            {{ substr(optional($blog->author)->name ?? 'M', 0, 1) }}
                        </div>
                        <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">{{ optional($blog->author)->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </article>
        @empty
        <div class="col-span-full py-20 text-center">
            <p class="text-slate-400 font-bold italic tracking-wide">The knowledge multiverse is currently recalibrating. Check back soon.</p>
        </div>
        @endforelse
    </div>

    <!-- Discovery Navigation -->
    <div class="mt-16">
        {{ $blogs->links() }}
    </div>
</div>
@endsection
