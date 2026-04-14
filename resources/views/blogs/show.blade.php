@extends('layouts.app')

@section('title', $blog->meta_title ?? $blog->title . ' | MyCollegeVerse Blog')
@section('meta_description', $blog->meta_description ?? $blog->summary)

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; }
    .article-content {
        line-height: 1.8;
        color: #1e293b;
    }
    .article-content h2 {
        font-weight: 800;
        font-size: 1.875rem;
        margin-top: 2.5rem;
        margin-bottom: 1.25rem;
        color: #0f172a;
    }
    .article-content p {
        margin-bottom: 1.5rem;
        font-size: 1.125rem;
    }
    .college-card {
        min-width: 280px;
        border-radius: 0;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    .college-card:hover {
        border-color: #3b82f6;
        transform: translateY(-4px);
    }
    /* Simple Typography Focus */
    .prose-node {
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
    }
</style>
@endpush

@section('content')
<article class="py-20 bg-white">
    <!-- Header Node -->
    <header class="prose-node px-6 mb-16">
        <div class="flex items-center gap-4 text-[11px] font-black text-primary uppercase tracking-[0.2em] mb-6">
            <span>Official Editorial</span>
            <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
            <span>{{ $blog->published_at->format('M d, Y') }}</span>
        </div>
        
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-[1.1] tracking-tight mb-8">
            {{ $blog->title }}
        </h1>
        
        <div class="flex items-center justify-between py-8 border-t border-b border-slate-50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold">
                    {{ substr($blog->author->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-900 uppercase tracking-widest">{{ $blog->author->name }}</p>
                    <p class="text-xs text-slate-400 font-medium mt-0.5 italic">Multiverse Contributor</p>
                </div>
            </div>

            <!-- Share Nexus 📡 -->
            <div class="flex items-center gap-3" x-data="{
                shareData: {
                    title: '{{ addslashes($blog->title) }}',
                    text: '{{ addslashes($blog->summary) }}',
                    url: window.location.href
                },
                async share() {
                    if (navigator.share) {
                        try {
                            await navigator.share(this.shareData);
                        } catch (err) {
                            console.error('Share failed:', err);
                        }
                    } else {
                        // Fallback logic for Reddit/Twitter etc if needed
                        window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(this.shareData.title) + '&url=' + encodeURIComponent(this.shareData.url), '_blank');
                    }
                }
            }">
                <button @click="share()" class="p-3 bg-slate-50 text-slate-400 hover:bg-primary/5 hover:text-primary transition-all rounded-xl" title="Share Article">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Content Node -->
    @if($blog->featured_image)
    <div class="max-w-6xl mx-auto px-6 mb-16">
        <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" class="w-full h-auto shadow-2xl shadow-slate-200">
    </div>
    @endif

    <div class="prose-node px-6 article-content mb-24">
        {!! $blog->content !!}
    </div>

    <!-- Institutional Mapping (Recommended Colleges) 🧬 -->
    @if($recommendedColleges->count() > 0)
    <div class="bg-slate-50/50 py-24 mb-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-end justify-between mb-12">
                <div class="max-w-xl">
                    <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">Recommended Campus Hubs</h3>
                    <p class="text-slate-500 font-medium mt-3 italic">Discover elite institutions matching your discovery path.</p>
                </div>
                <div class="hidden md:flex gap-3">
                    <button @click="$refs.scrollBox.scrollBy({left: -400, behavior: 'smooth'})" class="p-4 bg-white border border-slate-100 rounded-2xl text-slate-400 hover:text-primary transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="$refs.scrollBox.scrollBy({left: 400, behavior: 'smooth'})" class="p-4 bg-white border border-slate-100 rounded-2xl text-slate-400 hover:text-primary transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <div x-ref="scrollBox" class="flex gap-6 overflow-x-auto custom-scrollbar pb-10 nav-mask italic">
                @foreach($recommendedColleges as $college)
                <div class="college-card flex flex-col bg-white overflow-hidden shadow-sm shadow-slate-100">
                    <a href="{{ route('colleges.show', $college->slug) }}" class="block aspect-[16/9] bg-slate-100 overflow-hidden">
                        @if($college->thumbnail_url)
                            <img src="{{ app(\App\Services\ImageKitService::class)->getUrl($college->thumbnail_url, ['w' => 400, 'q' => 70]) }}" class="w-full h-full object-cover">
                        @endif
                    </a>
                    <div class="p-6 flex-1 flex flex-col">
                        <p class="text-[9px] font-black text-primary uppercase tracking-[0.2em] mb-2">{{ $college->city }}</p>
                        <h4 class="text-sm font-bold text-slate-900 leading-tight mb-4 flex-1">
                            <a href="{{ route('colleges.show', $college->slug) }}">{{ $college->name }}</a>
                        </h4>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                            <div class="flex items-center gap-1.5 text-amber-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-black">{{ $college->avg_rating ?? 'N/A' }}</span>
                            </div>
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">{{ $college->reviews_count }} CITIZENS</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Engagement Node (Comments) -->
    <section class="prose-node px-6 border-t border-slate-100 pt-20">
        <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-10">Citizen Dialogue ({{ $blog->comments->count() }})</h3>
        
        @auth
        <form action="{{ route('comments.store') }}" method="POST" class="mb-12">
            @csrf
            <input type="hidden" name="commentable_id" value="{{ $blog->id }}">
            <input type="hidden" name="commentable_type" value="App\Models\Blog">
            <textarea name="content" required rows="4" class="w-full bg-slate-50 border-none px-6 py-6 text-sm font-medium focus:ring-2 focus:ring-primary/20 transition-all placeholder-slate-400" placeholder="Contribute your perspective to the multiverse..."></textarea>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-primary text-white text-[11px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 transition-all">Submit Entry</button>
            </div>
        </form>
        @else
        <div class="p-8 bg-slate-50 text-center mb-12">
            <p class="text-sm text-slate-500 font-bold mb-4">Identity verification required to join the dialogue.</p>
            <a href="{{ route('login') }}" class="inline-block text-[11px] font-black text-primary uppercase tracking-widest border-b-2 border-primary">Authenticate Now</a>
        </div>
        @endauth

        <div class="space-y-8">
            @forelse($blog->comments as $comment)
            <div class="flex gap-4 pb-8 border-b border-slate-50 last:border-0 italic">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xs shrink-0">
                    {{ substr($comment->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-900 uppercase tracking-widest">{{ $comment->user->name }}</p>
                    <p class="text-[10px] text-slate-400 font-bold mt-0.5 mb-3">{{ $comment->created_at->diffForHumans() }}</p>
                    <div class="text-sm text-slate-600 font-medium leading-relaxed">
                        {{ $comment->content }}
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-400 italic text-sm py-10">The dialogue has not yet begun for this article.</p>
            @endforelse
        </div>
    </section>
</article>

<!-- Social Nexus Integration 📡 -->
<div class="fixed bottom-10 left-10 hidden lg:flex flex-col gap-3">
    @php
        $platforms = [
            ['name' => 'WhatsApp', 'url' => 'https://api.whatsapp.com/send?text=', 'color' => 'bg-emerald-500', 'icon' => 'M12 2C6.48 2 2 6.48 2 12c0 1.73.44 3.36 1.22 4.79L2 22l5.21-1.22C8.64 21.56 10.27 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z'],
            ['name' => 'LinkedIn', 'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=', 'color' => 'bg-blue-600', 'icon' => 'M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a2.7 2.7 0 0 0-2.7-2.7c-1.2 0-2 .7-2.3 1.2v-1h-2.3v7.8h2.3v-4.1a1.2 1.2 0 1 1 2.4 0v4.1h2.3M7.8 8.9c.7 0 1.3-.6 1.3-1.3A1.3 1.3 0 0 0 7.8 6.3a1.3 1.3 0 0 0-1.3 1.3 1.3 1.3 0 0 0 1.3 1.3m1.1 9.6V10.7H6.7v7.8h2.2z'],
            ['name' => 'Twitter', 'url' => 'https://twitter.com/intent/tweet?url=', 'color' => 'bg-sky-500', 'icon' => 'M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z'],
            ['name' => 'Reddit', 'url' => 'https://www.reddit.com/submit?url=', 'color' => 'bg-orange-600', 'icon' => 'M12 1.3C5 1.3 0 6.3 0 12.5a11.2 11.2 0 0 0 11.2 11.2c6.2 0 11.3-5.1 11.3-11.2S19 1.3 12 1.3zM18 13c.6 0 1.1.5 1.1 1.1s-.5 1.1-1.1 1.1-1.1-.3-1.1-.9.5-1.3 1.1-1.3zM12 18.8c-1.3 0-2.5-.5-3.5-1.4l-.3-.3 1-1 .3.3a3.6 3.6 0 0 0 5 0l.3-.3 1 1c-1 1-2.2 1.4-3.5 1.4zm-4.9-5.8c.6 0 1.1.5 1.1 1.1s-.5 1.1-1.1 1.1-1.1-.5-1.1-1.1.5-1.1 1.1-1.1zm11.2-5l-2 1.4c-.4-.1-.7-.2-1.1-.2a4.4 4.4 0 0 0-4.3 3.5h-.1c-2-.1-3.6-1.7-3.6-3.7a3.8 3.8 0 0 1 3.8-3.7c1.3 0 2.5.7 3.2 1.7.4-.2.9-.3 1.3-.3a1.4 1.4 0 0 1 1.4 1.4c0 .3-.1.6-.2.9z'],
        ];
    @endphp
    @foreach($platforms as $p)
    <a href="{{ $p['url'] . urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 {{ $p['color'] }} text-white flex items-center justify-center shadow-lg hover:scale-110 transition-all ripple" title="Share on {{ $p['name'] }}">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $p['icon'] }}"/></svg>
    </a>
    @endforeach
</div>
@endsection
