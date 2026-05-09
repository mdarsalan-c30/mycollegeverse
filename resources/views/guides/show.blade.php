@extends('layouts.app')

@section('title', $guide->meta_title ?? $guide->title)

@push('meta')
    <meta name="description" content="{{ $guide->meta_description }}">
    <meta name="keywords" content="{{ $guide->meta_keywords }}">
    <meta property="og:title" content="{{ $guide->title }}">
    <meta property="og:description" content="{{ $guide->meta_description }}">
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="{{ $guide->created_at->toIso8601String() }}">
    <meta property="article:section" content="{{ $guide->category }}">
@endpush

@section('content')
<div class="bg-white min-h-screen">
    <!-- Breadcrumbs -->
    <div class="bg-gray-50 border-b border-gray-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="text-sm font-medium flex items-center space-x-2 text-gray-500">
                <a href="/" class="hover:text-indigo-600">Home</a>
                <span>/</span>
                <a href="{{ route('guides.index') }}" class="hover:text-indigo-600">Academic Hub</a>
                <span>/</span>
                <span class="text-gray-900 truncate">{{ $guide->title }}</span>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Main Content -->
            <article class="lg:w-2/3">
                <header class="mb-8">
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider mb-4 inline-block">
                        {{ $guide->category }}
                    </span>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-4">
                        {{ $guide->title }}
                    </h1>
                    
                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs mr-2">
                                {{ substr($guide->user->name, 0, 1) }}
                            </div>
                            <span class="font-bold text-gray-700">{{ $guide->user->name }}</span>
                        </div>
                        <time datetime="{{ $guide->created_at->toDateString() }}">
                            {{ $guide->created_at->format('M d, Y') }}
                        </time>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ number_format($guide->views) }} Views
                        </span>
                    </div>
                </header>

                @if($guide->featured_image)
                    <div class="mb-8 rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ $guide->featured_image }}" alt="{{ $guide->title }}" class="w-full h-auto">
                    </div>
                @endif

                <!-- Article Body -->
                <div class="prose prose-lg max-w-none text-gray-800 guide-content">
                    {!! $guide->content !!}
                </div>

                @if(Auth::check() && (Auth::id() === $guide->user_id || Auth::user()->role === 'admin'))
                <div class="mt-12 p-6 bg-gray-50 rounded-xl border border-dashed border-gray-300 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-gray-900">Owner Actions</h4>
                        <p class="text-sm text-gray-500">You manifested this guide node.</p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('guides.edit', $guide->id) }}" class="text-indigo-600 hover:underline font-bold text-sm">Edit Node</a>
                        <form action="{{ route('guides.destroy', $guide->id) }}" method="POST" onsubmit="return confirm('Purge this guide from the multiverse?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline font-bold text-sm">Purge Node</button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Share Section -->
                <div class="mt-12 py-8 border-t border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Share this Knowledge 🛰️</h3>
                    <div class="flex space-x-4">
                        <button onclick="window.open('https://twitter.com/intent/tweet?text={{ urlencode($guide->title) }}&url={{ urlcurrent() }}')" class="p-3 bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </button>
                        <button onclick="window.open('https://api.whatsapp.com/send?text={{ urlencode($guide->title . ' ' . urlcurrent()) }}')" class="p-3 bg-green-50 text-green-600 rounded-full hover:bg-green-100 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </button>
                    </div>
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="lg:w-1/3">
                <div class="sticky top-24 space-y-8">
                    <!-- Related Guides -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Related Guides</h3>
                        <div class="space-y-6">
                            @forelse($related as $rel)
                            <div class="flex group">
                                <div class="flex-grow">
                                    <h4 class="text-sm font-bold text-gray-800 group-hover:text-indigo-600 leading-snug">
                                        <a href="{{ route('guides.show', $rel->slug) }}">{{ $rel->title }}</a>
                                    </h4>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $rel->category }}</span>
                                </div>
                            </div>
                            @empty
                            <p class="text-sm text-gray-400 italic">No related nodes found.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- CTA Card -->
                    <div class="bg-indigo-600 rounded-2xl p-8 text-white text-center shadow-xl">
                        <div class="text-4xl mb-4">📚</div>
                        <h3 class="text-xl font-bold mb-2">Need specific notes?</h3>
                        <p class="text-indigo-100 text-sm mb-6">Explore thousands of student-verified notes and previous year papers.</p>
                        <a href="{{ route('notes.index') }}" class="block w-full py-3 bg-white text-indigo-600 font-bold rounded-xl hover:bg-gray-100 transition">Explore Notes</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<style>
    .guide-content h1 { @apply text-2xl font-bold mt-8 mb-4; }
    .guide-content h2 { @apply text-xl font-bold mt-6 mb-3; }
    .guide-content h3 { @apply text-lg font-bold mt-5 mb-2; }
    .guide-content p { @apply mb-4 leading-relaxed; }
    .guide-content ul { @apply list-disc ml-6 mb-4; }
    .guide-content ol { @apply list-decimal ml-6 mb-4; }
    .guide-content li { @apply mb-1; }
    .guide-content blockquote { @apply border-l-4 border-indigo-500 pl-4 italic text-gray-600 my-6; }
</style>
@endsection
