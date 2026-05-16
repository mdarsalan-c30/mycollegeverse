@extends('layouts.hub')

@section('title', $seoTitle ?? $guide->meta_title ?? $guide->title . ' | Academic Hub')
@section('meta_description', $seoDescription ?? $guide->meta_description)
@section('meta_keywords', $guide->meta_keywords)

@push('structured-data')
<script type="application/ld+json">
    {!! json_encode($schema) !!}
</script>
@endpush

@push('head')
<style>
    /* Premium Editorial Typography for Academic Hub 🏛️ */
    .guide-content { 
        font-size: 1.125rem; 
        line-height: 1.8; 
        color: #334155; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .guide-content h2 { font-size: 2.25rem; font-weight: 800; color: #0f172a; margin-top: 3.5rem; margin-bottom: 1.5rem; letter-spacing: -0.025em; line-height: 1.2; }
    .guide-content h3 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-top: 2.5rem; margin-bottom: 1rem; line-height: 1.3; }
    .guide-content h4, .guide-content h5, .guide-content h6 { font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-top: 2rem; margin-bottom: 1rem; }
    .guide-content p { margin-bottom: 1.5rem; display: block; }
    .guide-content ul { list-style-type: disc; margin-bottom: 1.5rem; padding-left: 1.5rem; }
    .guide-content ol { list-style-type: decimal; margin-bottom: 1.5rem; padding-left: 1.5rem; }
    .guide-content li { margin-bottom: 0.75rem; padding-left: 0.5rem; }
    .guide-content strong, .guide-content b { font-weight: 700; color: #0f172a; }
    .guide-content blockquote { 
        border-left: 4px solid #3B82F6; 
        background: #F8FAFC; 
        padding: 2rem; 
        border-radius: 0 1.5rem 1.5rem 0; 
        font-style: italic; 
        margin: 2.5rem 0;
        color: #475569;
    }
    .guide-content img { border-radius: 2rem; margin: 3rem 0; box-shadow: 0 20px 50px rgba(0,0,0,0.05); }
    .guide-content a { color: #3B82F6; font-weight: 700; text-decoration: underline; text-decoration-thickness: 2px; text-underline-offset: 4px; }
</style>
@endpush

@section('content')
    <div class="max-w-5xl mx-auto px-6 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex items-center text-[10px] font-black uppercase tracking-widest text-slate-400 mb-12 overflow-x-auto whitespace-nowrap pb-2">
            <a href="{{ route('guides.index') }}" class="hover:text-primary transition-colors">Academic Hub</a>
            <span class="mx-4 opacity-30">/</span>
            <span class="text-slate-300">{{ $guide->category }}</span>
        </nav>

        <article class="bg-white rounded-[4rem] overflow-hidden border border-slate-100 shadow-2xl relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-bl-[10rem] -mr-32 -mt-32"></div>

            <!-- Hero Header -->
            <div class="px-5 md:px-20 pt-16 md:pt-20 pb-12 md:pb-16 relative">
                <div class="flex items-center gap-4 mb-6 md:mb-8">
                    <span class="px-4 py-1.5 bg-primary/5 text-primary text-[10px] font-black uppercase tracking-widest rounded-full ring-1 ring-primary/20">
                        {{ $guide->category }}
                    </span>
                    @if($guide->target_university)
                    <span class="px-4 py-1.5 bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-full">
                        {{ $guide->target_university }}
                    </span>
                    @endif
                </div>

                <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-[1.1] tracking-tight mb-10 italic">
                    {{ $guide->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500 flex items-center justify-center text-white font-black text-sm shadow-xl shadow-indigo-200">
                            {{ substr($guide->user->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-300">Verified Archivist</p>
                            <p class="text-xs font-bold text-slate-900">{{ $guide->user->name ?? 'Archivist' }}</p>
                        </div>
                    </div>

                    <div class="h-10 w-[1px] bg-slate-100"></div>

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-xl shadow-inner">👁️</div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-300">Intel Access</p>
                            <p class="text-xs font-bold text-slate-900">{{ number_format($guide->views) }} Views</p>
                        </div>
                    </div>

                    <div class="h-10 w-[1px] bg-slate-100"></div>

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-xl shadow-inner">🕒</div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-300">Last Synced</p>
                            <p class="text-xs font-bold text-slate-900">{{ $guide->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PDF Interactive Hub (If exists) -->
            @if($guide->file_path)
            <div class="mx-5 md:mx-20 mb-12 space-y-6">
                <!-- PDF Viewer Node -->
                <div class="glass rounded-[2rem] md:rounded-[3rem] overflow-hidden border border-slate-100 shadow-xl aspect-[4/5] md:aspect-video relative group">
                    @php
                        $pdfUrl = $guide->file_path;
                        if (\Illuminate\Support\Str::contains($pdfUrl, 'http')) {
                            $pdfUrl = $pdfUrl;
                        } else {
                            $pdfUrl = asset('storage/' . $pdfUrl);
                        }
                        $downloadUrl = $pdfUrl;
                    @endphp
                    <embed src="{{ $pdfUrl }}#toolbar=0&navpanes=0&scrollbar=1" type="application/pdf" width="100%" height="100%" class="rounded-[3rem]" />
                    
                    <!-- Premium Overlay -->
                    <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ $pdfUrl }}" target="_blank" class="bg-white text-slate-900 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl flex items-center gap-2 hover:bg-primary hover:text-white transition-all border border-slate-100">
                            🔍 Full Screen Intel
                        </a>
                    </div>
                </div>
 
                <!-- Download Bar -->
                <div class="p-6 bg-slate-900 rounded-[2.5rem] flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl shadow-primary/20">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-2xl">📄</div>
                        <div>
                            <p class="text-white font-black text-xs uppercase tracking-widest mb-0.5">Supplementary PDF Node</p>
                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest italic">Official Syllabus / Notice Attachment</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <a href="{{ $downloadUrl }}" download="{{ $guide->title }}.pdf" class="flex-1 md:flex-none px-10 py-4 bg-white text-slate-900 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-primary hover:text-white transition-all text-center">
                            Download Intel PDF ⬇️
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Content Area / Digital Manuscript Manifestation ⚡ -->
            <div class="px-5 md:px-20 py-12 md:py-16" x-data="{ viewMode: '{{ $guide->hasFullHtml() ? 'html' : 'prose' }}' }">
                
                <!-- View Mode Switcher Node 🛰️ -->
                <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-2xl w-fit mb-8 md:mb-12 border border-slate-200">
                    <button @click="viewMode = 'prose'" 
                            :class="viewMode === 'prose' ? 'bg-white shadow-sm text-primary' : 'text-slate-400 hover:text-slate-600'" 
                            class="px-8 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                        Standard Prose
                    </button>
                    <button @click="viewMode = 'html'" 
                            :class="viewMode === 'html' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-400 hover:text-slate-600'" 
                            class="px-8 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all flex items-center gap-2">
                        ⚡ Smart HTML
                    </button>
                </div>

                <div class="relative">
                    <!-- Standard Prose View -->
                    <div x-show="viewMode === 'prose'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="guide-content">
                            {!! $guide->content !!}
                        </div>
                    </div>

                    <!-- Smart HTML View (Isolated) -->
                    <div x-show="viewMode === 'html'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="glass rounded-[3rem] overflow-hidden border border-slate-100 shadow-2xl relative">
                            <div class="bg-slate-900 px-8 py-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                </div>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Isolated Academic Node • Smart HTML Manifest</span>
                            </div>
                            <iframe id="manuscript-frame" 
                                    class="w-full h-[800px] border-none" 
                                    src="data:text/html;base64,{{ base64_encode($guide->content) }}"
                                    sandbox="allow-scripts allow-popups allow-forms allow-same-origin"></iframe>
                        </div>
                    </div>
                </div>


                <!-- Direct Manifestation Hub 🛰️ -->
                <div class="mt-16 p-10 bg-slate-50 rounded-[3.5rem] border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-4xl shadow-sm border border-slate-100">🎓</div>
                        <div>
                            <h4 class="text-lg font-black text-slate-900 uppercase tracking-tight mb-1">Manifest Guide to PDF</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Generate a branded academic manuscript with institutional watermarking.</p>
                        </div>
                    </div>
                    
                    <button id="manifest-btn" onclick="manifestGuide()" class="w-full md:w-auto px-12 py-5 bg-slate-900 text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl hover:bg-primary hover:scale-105 transition-all flex items-center justify-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" /></svg>
                        Manifest PDF ⬇️
                    </button>
                </div>
            </div>

            <!-- Author Actions -->
            @if(Auth::check() && (Auth::id() === $guide->user_id || Auth::user()->role === 'admin'))
            <div class="px-8 md:px-20 py-10 border-t border-slate-50 flex items-center justify-end gap-6 bg-slate-50/30">
                <a href="{{ route('guides.edit', $guide->id) }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition-all">Edit Node 📝</a>
                <form action="{{ route('guides.destroy', $guide->id) }}" method="POST" onsubmit="return confirm('Purge this intel from the multiverse?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-red-300 hover:text-red-500 transition-all">Purge Node 🗑️</button>
                </form>
            </div>
            @endif
        </article>

        <!-- CTA -->
        <div class="mt-32 py-20 px-8 glass rounded-[4rem] text-center bg-gradient-to-br from-primary/5 to-indigo-500/5 border-primary/10">
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 uppercase tracking-tight mb-6 italic">Contribute to the <span class="gradient-text">Multiverse.</span></h2>
            <p class="text-slate-500 font-bold text-sm max-w-xl mx-auto mb-12">Your verified academic guides can help thousands of students navigate their journey. Manifest your knowledge today.</p>
            <a href="{{ route('guides.create') }}" class="inline-flex items-center px-12 py-4 bg-primary text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl shadow-2xl shadow-primary/30 hover:scale-105 transition-all">Manifest New Node 🌌</a>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        async function manifestGuide() {
            const btn = document.getElementById('manifest-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="animate-pulse italic">🛰️ Syncing Multiverse...</span>';
            btn.disabled = true;

            try {
                let element;
                @if($guide->hasFullHtml())
                    // Fix: Avoid cross-origin iframe document access error 🛰️
                    const contentDiv = document.createElement('div');
                    contentDiv.innerHTML = `{!! addslashes($guide->content) !!}`;
                    element = contentDiv;
                @else
                    element = document.querySelector('.guide-content').cloneNode(true);
                @endif

                // Branded Watermark Manifestation 🏛️
                const watermark = document.createElement('div');
                watermark.style.cssText = "position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 50px; color: rgba(0,0,0,0.08); font-weight: 900; z-index: 1000; pointer-events: none; white-space: nowrap; font-family: 'Inter', sans-serif; text-transform: uppercase; letter-spacing: 0.2em; width: 100%; text-align: center;";
                watermark.innerText = 'MYCOLLEGEVERSE.IN • ACADEMIC HUB';
                
                const pdfContainer = document.createElement('div');
                pdfContainer.style.padding = '40px';
                pdfContainer.style.background = 'white';
                pdfContainer.style.position = 'relative';
                pdfContainer.appendChild(watermark);
                pdfContainer.appendChild(element);

                const opt = {
                    margin: [15, 15],
                    filename: '{{ $guide->slug }}_academic_intel.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true, letterRendering: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                await html2pdf().set(opt).from(pdfContainer).save();
                
                btn.innerHTML = '✅ Manifested';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 2000);
            } catch (e) {
                console.error('Manifestation Failed:', e);
                alert('Bhai, manifestation failed. Check console for intel.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    </script>
    @endpush
@endsection
