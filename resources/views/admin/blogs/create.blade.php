@extends('layouts.admin')

@section('title', 'Manifest Article | Editorial Hub')

@section('content')
<form action="{{ route('admin.blogs.store') }}" method="POST" class="space-y-10" x-data="{ 
    autoRecommend: true,
    title: '',
    metaDesc: '',
    keywords: '',
    seoScore: 0,
    updateSeo() {
        // Simple client-side preview of SEO Score
        let score = 0;
        if(this.title.length >= 30 && this.title.length <= 60) score += 25;
        if(this.metaDesc.length >= 120 && this.metaDesc.length <= 160) score += 25;
        if(this.keywords.length > 5) score += 25;
        this.seoScore = score;
    }
}">
    @csrf
    
    <!-- Top Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-4xl font-black text-secondary tracking-tight">Manifest Article</h1>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-2 italic">Crafting High-Readability Knowledge for the Multiverse</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.blogs.index') }}" class="px-6 py-4 bg-white border border-slate-100 text-slate-400 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">
                Abort Node
            </a>
            <button type="submit" class="px-8 py-4 bg-admin-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-admin-primary/20 hover:scale-105 transition-all outline-none">
                Manifest to Verse
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="glass p-6 rounded-2xl border-rose-100 bg-rose-50/30 text-rose-500 text-xs font-bold">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main Composition Node -->
        <div class="lg:col-span-2 space-y-8">
            <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Primary Focus (Title)</label>
                    <input type="text" name="title" x-model="title" @input="updateSeo()" required placeholder="Enter article headline..." 
                           class="w-full h-16 bg-white border-slate-100 rounded-2xl px-6 text-lg font-black text-secondary focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Article Content</label>
                    <textarea name="content" id="article-editor" required class="w-full bg-white border-slate-100 rounded-2xl p-6 text-sm font-medium leading-relaxed min-h-[500px] focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all" placeholder="Begin the knowledge flow..."></textarea>
                    <p class="text-[10px] text-slate-400 mt-2 font-bold italic px-1">Tip: Use clean headers (H2, H3) for premium readability. No fancy curves, just clear insights.</p>
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Short Excerpt (Manual Preview)</label>
                    <textarea name="excerpt" rows="3" class="w-full bg-white border-slate-100 rounded-2xl p-6 text-xs font-medium focus:ring-4 focus:ring-admin-primary/5" placeholder="Optional: Summarize the article for feed previews..."></textarea>
                </div>
            </div>

            <!-- Institutional Mapping Node 🧬 -->
            <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-black text-secondary">Institutional Mapping</h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic mt-1">Linking Knowledge to Campus Nodes</p>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <span class="text-[10px] font-black text-slate-400 group-hover:text-admin-primary transition-colors">AUTO PILOT</span>
                        <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out">
                            <input type="checkbox" name="auto_recommend_colleges" x-model="autoRecommend" class="hidden peer">
                            <div class="w-full h-full bg-slate-200 rounded-full peer-checked:bg-emerald-500 transition-all shadow-inner"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:translate-x-6"></div>
                        </div>
                    </label>
                </div>

                <div x-show="!autoRecommend" x-transition class="space-y-4">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Explicit College Selection</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto custom-scrollbar pr-2 p-1">
                        @foreach($colleges as $college)
                        <label class="p-4 border-2 border-slate-50 rounded-2xl flex items-center gap-4 cursor-pointer hover:border-admin-primary/20 hover:bg-slate-50 transition-all h-16">
                            <input type="checkbox" name="college_ids[]" value="{{ $college->id }}" class="w-5 h-5 rounded-lg text-admin-primary focus:ring-admin-primary/20 bg-white border-slate-200">
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-secondary truncate">{{ $college->name }}</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $college->city }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Mastery Sidebar -->
        <div class="space-y-8">
            <!-- SEO Pulse Card 🧠 -->
            <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm space-y-6 overflow-hidden relative">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-admin-primary/5 rounded-full blur-3xl"></div>
                <h4 class="text-lg font-black text-secondary relative">SEO Mastery</h4>
                
                <div class="flex items-end gap-3 mb-6 relative">
                    <h2 class="text-6xl font-black text-secondary leading-none" x-text="seoScore + '%'">0%</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Score Projection</p>
                </div>
                
                <div class="space-y-6 relative">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Meta Title</label>
                        <input type="text" name="meta_title" x-model="title" class="w-full h-12 bg-white border-slate-100 rounded-xl px-4 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Meta Description (Max 160)</label>
                        <textarea name="meta_description" x-model="metaDesc" @input="updateSeo()" rows="3" class="w-full bg-white border-slate-100 rounded-xl p-4 text-xs font-medium focus:ring-4 focus:ring-admin-primary/5"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Target Keywords (Comma-separated)</label>
                        <input type="text" name="meta_keywords" x-model="keywords" @input="updateSeo()" placeholder="education, college, guide..."
                               class="w-full h-12 bg-white border-slate-100 rounded-xl px-4 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">AI Originality Score (%)</label>
                        <input type="number" name="ai_score" value="100" class="w-full h-12 bg-white border-slate-100 rounded-xl px-4 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5">
                    </div>
                </div>
            </div>

            <!-- Configuration & Discovery -->
            <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm space-y-6">
                <h4 class="text-lg font-black text-secondary">Discovery Mode</h4>
                
                <div class="space-y-4">
                    <label class="p-4 border-2 border-slate-50 rounded-2xl flex items-center justify-between cursor-pointer hover:bg-slate-50 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-xs font-black text-secondary tracking-tight">Public Presence (Live)</span>
                        </div>
                        <input type="checkbox" name="is_published" value="1" class="w-6 h-6 rounded-lg text-emerald-500 border-slate-200 focus:ring-emerald-500/20 shadow-sm">
                    </label>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Discovery Illustration (Featured Image)</label>
                        <input type="text" name="featured_image" placeholder="ImageKit Path or Remote URL..." 
                               class="w-full h-12 bg-white border-slate-100 rounded-xl px-4 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
