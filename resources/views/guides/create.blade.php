@extends('layouts.hub')

@section('title', 'Manifest Academic Knowledge | Academic Hub')

@section('content')
    <div class="max-w-4xl mx-auto px-6 py-12">
        <div class="mb-16 text-center">
            <h1 class="text-5xl font-black text-slate-900 tracking-tight mb-4 leading-tight italic uppercase">Manifest <span class="gradient-text">Knowledge.</span></h1>
            <p class="text-slate-400 font-bold text-sm uppercase tracking-[0.3em] opacity-80">Architecting the Academic Multiverse Node</p>
        </div>

        <form action="{{ route('guides.store') }}" method="POST" id="manifest-form" enctype="multipart/form-data" class="space-y-10 pb-32">
            @csrf
            
            <div class="glass p-10 md:p-16 rounded-[4rem] border border-slate-100 shadow-2xl space-y-12">
                <!-- SEO Status Node (Mirroring Blog Engine) -->
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white mb-10 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 rounded-bl-[5rem] -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-1">SEO Health Node</h4>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Live Multiverse Synchronization</p>
                            </div>
                            <div class="text-right">
                                <span id="seo-score" class="text-3xl font-black text-white">0%</span>
                            </div>
                        </div>
                        <div class="w-full h-1.5 bg-white/5 rounded-full mb-8">
                            <div id="seo-progress" class="h-full bg-primary rounded-full transition-all duration-700" style="width: 0%"></div>
                        </div>
                        
                        <!-- Checklist Mirror -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="seo-checklist">
                            <div class="flex items-center gap-3 text-[9px] font-black uppercase tracking-widest text-slate-400" data-rule="title">
                                <span class="status-icon text-slate-600">⚪</span> Title Range (30-60)
                            </div>
                            <div class="flex items-center gap-3 text-[9px] font-black uppercase tracking-widest text-slate-400" data-rule="meta">
                                <span class="status-icon text-slate-600">⚪</span> Meta Description
                            </div>
                            <div class="flex items-center gap-3 text-[9px] font-black uppercase tracking-widest text-slate-400" data-rule="depth">
                                <span class="status-icon text-slate-600">⚪</span> Content Depth
                            </div>
                            <div class="flex items-center gap-3 text-[9px] font-black uppercase tracking-widest text-slate-400" data-rule="keywords">
                                <span class="status-icon text-slate-600">⚪</span> Keyword Sync
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Intel -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center font-black">01</div>
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-900">Primary Node Data</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Title / Identifier</label>
                            <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. B.Tech AKTU 2nd Year Syllabus" class="w-full h-16 bg-white border border-slate-100 rounded-[1.5rem] px-8 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all shadow-sm">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Sector (Category)</label>
                                <select name="category" required class="w-full h-16 bg-white border border-slate-100 rounded-[1.5rem] px-8 text-sm font-bold text-slate-600 focus:ring-4 focus:ring-primary/5 transition-all shadow-sm">
                                    @foreach(['Syllabus', 'College Guide', 'Admission', 'Career', 'Notice'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Target Node (University)</label>
                                <input type="text" name="target_university" value="{{ old('target_university') }}" placeholder="e.g. AKTU, Mumbai Univ" class="w-full h-16 bg-white border border-slate-100 rounded-[1.5rem] px-8 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Matrix -->
                <div class="space-y-8" x-data="{ manifestMode: 'standard' }">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center font-black">02</div>
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-900">Knowledge Manifestation</h3>
                        </div>

                        <!-- Mode Switcher 🛰️ -->
                        <div class="bg-slate-100 p-1 rounded-2xl flex gap-1 border border-slate-200 w-fit">
                            <button type="button" @click="manifestMode = 'standard'" :class="manifestMode === 'standard' ? 'bg-white shadow-sm text-primary' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest transition-all">
                                Standard Prose
                            </button>
                            <button type="button" @click="manifestMode = 'html'" :class="manifestMode === 'html' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest transition-all flex items-center gap-2">
                                ⚡ Smart HTML
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">
                            <span x-show="manifestMode === 'standard'">Description / Guide Content</span>
                            <span x-show="manifestMode === 'html'" class="text-indigo-500">Raw Manuscript Code (HTML/CSS)</span>
                        </label>

                        <!-- Standard Editor (Quill) -->
                        <div x-show="manifestMode === 'standard'" id="editor-wrapper" class="rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm transition-all" x-transition>
                            <div id="editor" class="h-96 bg-white font-sans text-base"></div>
                        </div>

                        <!-- Smart HTML Editor -->
                        <div x-show="manifestMode === 'html'" x-cloak x-transition class="transition-all" style="display: none;" :style="manifestMode === 'html' ? 'display: block' : 'display: none'">
                            <textarea id="html-editor" placeholder="Paste your high-fidelity HTML/CSS code here... (e.g. PPS Smart Prep Node)" 
                                      class="w-full h-96 bg-slate-900 text-indigo-300 font-mono text-sm p-8 rounded-[2rem] border border-slate-800 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-2xl resize-none placeholder:text-slate-700"></textarea>
                        </div>

                        <input type="hidden" name="content" id="content-input" value="{{ old('content') }}">
                    </div>

                    <!-- PDF Upload Component -->
                    <div class="bg-slate-50/50 p-8 rounded-[2.5rem] border border-dashed border-slate-200">
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl shadow-sm">📄</div>
                            <div class="flex-1 text-center md:text-left">
                                <h4 class="text-sm font-black text-slate-900 uppercase tracking-tight mb-1">Upload Supplementary PDF</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Attach official syllabus, notice or document (Max 10MB)</p>
                            </div>
                            <div class="relative">
                                <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="bg-white px-8 py-3 rounded-xl border border-slate-100 text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all">Select File</div>
                            </div>
                        </div>
                        <div id="file-name" class="mt-4 text-center text-[10px] font-black text-primary uppercase tracking-widest hidden"></div>
                    </div>
                </div>

                <!-- SEO Meta Overrides -->
                <div class="space-y-8 pt-8 border-t border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center font-black">03</div>
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">SEO Strategy (Optional)</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="SEO Meta Title (Max 60 chars)" class="w-full h-12 bg-slate-50 border-none rounded-xl px-6 text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-primary/5 transition-all">
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="SEO Meta Keywords (comma separated)" class="w-full h-12 bg-slate-50 border-none rounded-xl px-6 text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-primary/5 transition-all">
                        <textarea name="meta_description" placeholder="SEO Meta Description (Max 160 chars)" rows="2" class="w-full bg-slate-50 border-none rounded-xl px-6 py-4 text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-primary/5 transition-all">{{ old('meta_description') }}</textarea>
                    </div>
                </div>

                <!-- Action Node -->
                <div class="pt-10">
                    <button type="submit" id="submit-btn" class="w-full h-20 bg-primary text-white font-black text-xs uppercase tracking-[0.3em] rounded-[2.5rem] shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-4">
                        Broadcast to Academic Hub 🛰️
                    </button>
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest text-center mt-8">By broadcasting, you verify this intel as authentic academic property.</p>
                </div>
            </div>
        </form>
    </div>

    @push('head')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow { border: none !important; background: #F8FAFC; padding: 1.5rem !important; border-bottom: 1px solid #F1F5F9 !important; }
        .ql-container.ql-snow { border: none !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 16px !important; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Architect your academic knowledge here...',
                modules: { toolbar: [[{ 'header': [1, 2, 3, false] }], ['bold', 'italic', 'underline'], ['blockquote', 'code-block'], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['link', 'clean']] }
            });

            // Restore content
            var oldContent = document.querySelector('#content-input').value;
            if (oldContent) quill.root.innerHTML = oldContent;

            // File name display
            document.getElementById('pdf_file').addEventListener('change', function(e) {
                var name = e.target.files[0] ? e.target.files[0].name : '';
                var el = document.getElementById('file-name');
                if(name) { el.textContent = 'Attached: ' + name; el.classList.remove('hidden'); }
            });

            // --- Aggressive SEO Engine ---
            function updateSeo() {
                const title = document.querySelector('input[name="title"]').value;
                const metaTitle = document.querySelector('input[name="meta_title"]').value;
                const metaDesc = document.querySelector('textarea[name="meta_description"]').value;
                const keywords = document.querySelector('input[name="meta_keywords"]').value;
                
                // Content extraction based on active mode 🛰️
                const htmlEditor = document.getElementById('html-editor');
                let content = '';
                let rawHtml = '';
                
                if (htmlEditor && htmlEditor.offsetParent !== null) {
                    rawHtml = htmlEditor.value;
                    // Strip tags for SEO word count
                    content = rawHtml.replace(/<[^>]*>?/gm, ' ');
                } else {
                    content = quill.getText();
                    rawHtml = quill.root.innerHTML;
                }

                let score = 0;
                let rules = { title: false, meta: false, depth: false, keywords: false };

                // 1. Title/Meta Title Sync
                const mainTitle = metaTitle || title;
                if (mainTitle.length >= 30 && mainTitle.length <= 60) { score += 25; rules.title = true; }

                // 2. Meta Description
                if (metaDesc.length >= 120 && metaDesc.length <= 160) { score += 25; rules.meta = true; }

                // 3. Content Depth
                const words = content.trim().split(/\s+/).length;
                if (words >= 300 || (rawHtml.length > 1000 && htmlEditor.offsetParent !== null)) { score += 25; rules.depth = true; }

                // 4. Keyword presence
                if (keywords.length > 3) {
                    const firstKeyword = keywords.split(',')[0].trim().toLowerCase();
                    if (content.toLowerCase().includes(firstKeyword) || rawHtml.toLowerCase().includes(firstKeyword)) { score += 25; rules.keywords = true; }
                }

                // UI Update
                document.getElementById('seo-score').innerText = score + '%';
                document.getElementById('seo-progress').style.width = score + '%';

                // Checklist UI
                Object.keys(rules).forEach(rule => {
                    const el = document.querySelector(`[data-rule="${rule}"]`);
                    const icon = el.querySelector('.status-icon');
                    if (rules[rule]) {
                        icon.innerText = '✅';
                        el.classList.remove('text-slate-400');
                        el.classList.add('text-primary');
                    } else {
                        icon.innerText = '⚪';
                        el.classList.remove('text-primary');
                        el.classList.add('text-slate-400');
                    }
                });
            }

            // Real-time Pulse
            quill.on('text-change', updateSeo);
            document.getElementById('html-editor').addEventListener('input', updateSeo);
            document.querySelectorAll('input, textarea').forEach(el => el.addEventListener('input', updateSeo));
            updateSeo(); // Init

            var form = document.getElementById('manifest-form');
            form.addEventListener('submit', function(e) {
                const htmlEditor = document.getElementById('html-editor');
                let finalContent = '';
                
                if (htmlEditor && htmlEditor.offsetParent !== null) {
                    finalContent = htmlEditor.value;
                    if (finalContent.trim().length < 10) {
                        alert('Bhai, HTML code toh dalo!');
                        e.preventDefault(); return false;
                    }
                } else {
                    finalContent = quill.root.innerHTML;
                    if (quill.getText().trim().length === 0 && finalContent.indexOf('<img') === -1) {
                        alert('Please enter some content before broadcasting.');
                        e.preventDefault(); return false;
                    }
                }

                document.getElementById('content-input').value = finalContent;
                document.getElementById('submit-btn').innerHTML = 'Broadcasting Node... 🛰️';
                document.getElementById('submit-btn').disabled = true;
            });
        });
    </script>
    @endpush
@endsection
