@extends('layouts.app')

@section('title', 'Academic Hub | Guides, Syllabuses & College Updates')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- Hero Section -->
    <div class="bg-indigo-900 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Academic Hub 🏛️</h1>
            <p class="text-xl text-indigo-100 max-w-2xl mx-auto">Your encyclopedia for college guides, detailed syllabuses, and official academic updates.</p>
            
            <div class="mt-8 max-w-xl mx-auto">
                <form action="{{ route('guides.index') }}" method="GET" class="flex shadow-lg rounded-lg overflow-hidden">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for guides, syllabuses..." class="w-full px-6 py-4 text-gray-900 focus:outline-none">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 px-8 py-4 font-bold transition">Search</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-8">
        <!-- Category Filters -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            @php
                $categories = ['Syllabus', 'College Guide', 'Admission', 'Career', 'Notice'];
                $currentCat = request('category');
            @endphp
            <a href="{{ route('guides.index') }}" class="px-6 py-3 rounded-full font-medium shadow-sm {{ !$currentCat ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">All Guides</a>
            @foreach($categories as $cat)
                <a href="{{ route('guides.index', ['category' => $cat]) }}" class="px-6 py-3 rounded-full font-medium shadow-sm {{ $currentCat == $cat ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        @if(Auth::check() && Auth::user()->role !== 'recruiter')
        <div class="flex justify-end mb-8">
            <a href="{{ route('guides.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold shadow-md flex items-center transition">
                <span class="mr-2">Manifest New Guide</span> 🚀
            </a>
        </div>
        @endif

        <!-- Guides Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($guides as $guide)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all border border-gray-100 flex flex-col h-full overflow-hidden">
                <div class="p-6 flex-grow">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full uppercase tracking-wider">
                            {{ $guide->category }}
                        </span>
                        <span class="text-gray-400 text-xs flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ $guide->views }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-3 hover:text-indigo-600">
                        <a href="{{ route('guides.show', $guide->slug) }}">{{ $guide->title }}</a>
                    </h2>
                    <p class="text-gray-600 text-sm line-clamp-3">
                        {{ Str::limit(strip_tags($guide->content), 120) }}
                    </p>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs mr-2">
                            {{ substr($guide->user->name, 0, 1) }}
                        </div>
                        <span class="text-xs text-gray-500 font-medium">{{ $guide->user->name }}</span>
                    </div>
                    <a href="{{ route('guides.show', $guide->slug) }}" class="text-indigo-600 font-bold text-sm flex items-center hover:underline">
                        Read More 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="text-6xl mb-4">🏜️</div>
                <h3 class="text-2xl font-bold text-gray-400">No guides found in this sector.</h3>
                <p class="text-gray-500 mt-2">Try adjusting your search or category filters.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $guides->links() }}
        </div>
    </div>
</div>
@endsection
