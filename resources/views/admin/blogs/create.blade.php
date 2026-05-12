<x-admin-layout>
    @section('title', 'Manifest New Article | Editorial Hub')

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="blog-form">
        @csrf
        
        <!-- Header Node -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-8 bg-white border border-slate-100 rounded-3xl gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Manifest New Article</h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Populating the Knowledge Multiverse</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.blogs.index') }}" class="px-6 py-4 bg-slate-50 text-slate-400 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-100 transition-all">
                    Discard Node
                </a>
                <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                    Commit To Registry
                </button>
            </div>
        </div>

        @if ($errors->any() || session('error'))
            <div class="p-6 bg-rose-50 border border-rose-100 rounded-2xl text-rose-500 text-xs font-bold space-y-2">
                @if(session('error'))
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if($errors->any())
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Composition Cluster -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Primary Inputs -->
                <div class="bg-white border border-slate-100 p-8 rounded-[2rem] shadow-sm space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Article Title</label>
                        <input type="text" name="title" required placeholder="Enter a high-impact title..."
                               class="w-full h-16 bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 text-xl font-black text-slate-900 focus:border-slate-900 focus:bg-white transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Editorial Segment (Category)</label>
                        <select name="category_id" class="w-full h-14 bg-slate-50 border-2 border-slate-100 rounded-xl px-6 text-sm font-bold text-slate-700 focus:border-slate-900 focus:bg-white transition-all outline-none appearance-none">
                            <option value="">Uncategorized Segment</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rich Text Editor Container 📝 -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Article Body (formatting power enabled)</label>
                        <div class="bg-slate-50 border-2 border-slate-100 rounded-2xl overflow-hidden min-h-[500px]">
                            <textarea name="content" id="editor" class="hidden"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Short Excerpt Node -->
                <div class="bg-white border border-slate-100 p-8 rounded-[2rem] shadow-sm">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Short Preview Excerpt</label>
                    <textarea name="excerpt" rows="3" placeholder="Briefly summarize the article for search results..."
                               class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-6 text-sm font-medium focus:border-slate-900 focus:bg-white transition-all outline-none"></textarea>
                </div>
            </div>

            <!-- Configuration Sidebar -->
            <div class="space-y-8">
                <!-- Automated SEO Pulse 🧠 -->
                <div class="bg-slate-900 p-8 rounded-[2rem] text-white space-y-6">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-black uppercase tracking-widest">SEO Health</h4>
                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[10px] font-black rounded-full">AUTOMATED</span>
                    </div>
                    <div>
                        <div class="text-6xl font-black mb-2" id="seo-score">0%</div>
                        <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden mb-6">
                            <div class="h-full bg-emerald-500 transition-all duration-500" id="seo-bar" style="width: 0%"></div>
                        </div>

                        <!-- SEO Checklist 📋 -->
                        <div id="seo-checklist" class="space-y-3">
                            <div data-check="title" class="flex items-center gap-3 text-[10px] font-black text-white/30 transition-all">
                                <span class="status-icon">⚪</span> <span>Title Range (30-60)</span>
                            </div>
                            <div data-check="desc" class="flex items-center gap-3 text-[10px] font-black text-white/30 transition-all">
                                <span class="status-icon">⚪</span> <span>Meta Description (120-160)</span>
                            </div>
                            <div data-check="words" class="flex items-center gap-3 text-[10px] font-black text-white/30 transition-all">
                                <span class="status-icon">⚪</span> <span>Content Depth (300+ words)</span>
                            </div>
                            <div data-check="density" class="flex items-center gap-3 text-[10px] font-black text-white/30 transition-all">
                                <span class="status-icon">⚪</span> <span>Keyword Density (0.5-2.5%)</span>
                            </div>
                            <div data-check="structure" class="flex items-center gap-3 text-[10px] font-black text-white/30 transition-all">
                                <span class="status-icon">⚪</span> <span>Heading Hierarchy (H2/H3)</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-400 text-xs font-medium italic">This score is computed in real-time based on your content depth, keyword density, and heading structure.</p>
                </div>

                <!-- Meta Configuration -->
                <div class="bg-white border border-slate-100 p-8 rounded-[2rem] space-y-6">
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Metadata Nucleus</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Meta Title</label>
                            <input type="text" name="meta_title" class="w-full h-12 bg-slate-50 border border-slate-100 rounded-xl px-4 text-xs font-bold focus:border-slate-900 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Meta Keywords</label>
                            <input type="text" name="meta_keywords" placeholder="Value1, Value2..."
                                   class="w-full h-12 bg-slate-50 border border-slate-100 rounded-xl px-4 text-xs font-bold focus:border-slate-900 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Meta Description</label>
                            <textarea name="meta_description" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-xl p-4 text-xs font-medium focus:border-slate-900 transition-all"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Nexus Linker: Internal SEO 🧬 -->
                <div class="bg-indigo-50 border border-indigo-100 p-8 rounded-[2rem] space-y-6" x-data="{ search: '' }">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">🧬</span>
                        <h4 class="text-sm font-black text-indigo-900 uppercase tracking-widest">Nexus Linker</h4>
                    </div>
                    <p class="text-[9px] font-bold text-indigo-400 uppercase tracking-widest">Internal Linking Accelerator</p>
                    
                    <input type="text" x-model="search" placeholder="Search Colleges..." 
                           class="w-full h-12 bg-white border border-indigo-100 rounded-xl px-4 text-xs font-bold focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                    
                    <div class="max-h-60 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                        @foreach($colleges as $college)
                        <div x-show="search === '' || '{{ strtolower($college->name) }}'.includes(search.toLowerCase())"
                             class="flex items-center justify-between p-3 bg-white rounded-xl border border-indigo-50 hover:border-indigo-200 transition-all group">
                            <span class="text-[10px] font-bold text-slate-700 truncate max-w-[140px]">{{ $college->name }}</span>
                            <button type="button" 
                                    @click="navigator.clipboard.writeText('{{ route('colleges.show', $college->slug) }}'); $el.innerText = 'Copied!';"
                                    class="text-[8px] font-black text-indigo-500 uppercase tracking-widest hover:text-indigo-700 transition-colors">
                                Link
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-[9px] text-indigo-400 font-medium italic">Click 'Link' to copy URL, then paste in Article Body.</p>
                </div>

                <!-- Public Status -->
                <div class="bg-white border border-slate-100 p-8 rounded-[2rem] space-y-6">
                    <label class="flex items-center justify-between cursor-pointer group">
                        <span class="text-sm font-black text-slate-900 uppercase tracking-widest">Live Presence</span>
                        <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out">
                            <input type="checkbox" name="is_published" value="1" class="hidden peer">
                            <div class="w-full h-full bg-slate-100 rounded-full peer-checked:bg-emerald-500 transition-all"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:translate-x-6 shadow-sm"></div>
                        </div>
                    </label>

                    <div class="space-y-4 pt-4 border-t border-slate-50">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Upload Illustration (Compressed)</label>
                            <input type="file" name="featured_image_file" accept="image/*"
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-all">
                        </div>
                        
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-slate-100"></div>
                            </div>
                            <div class="relative flex justify-center text-[8px] font-black uppercase tracking-widest">
                                <span class="bg-white px-2 text-slate-300">OR PROVIDE URL</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Discovery URL</label>
                            <input type="text" name="featured_image" placeholder="Remote image link..." 
                                   class="w-full h-12 bg-slate-50 border border-slate-100 rounded-xl px-4 text-xs font-bold focus:border-slate-900 transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Image ALT Text (SEO)</label>
                            <input type="text" name="featured_image_alt" placeholder="Describe the image..." 
                                   class="w-full h-12 bg-slate-50 border border-slate-100 rounded-xl px-4 text-xs font-bold focus:border-slate-900 transition-all">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <!-- CKEditor 5 Node 🛠️ -->
        <script src="https://cdn.ckeditor.com/ckeditor5/35.2.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
                })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                        const data = editor.getData();
                        document.querySelector('#editor').value = data;
                        updateSeo(data);
                    });
                })
                .catch(error => { console.error(error); });

            function updateSeo(content) {
                let score = 0;
                const text = content.replace(/<[^>]*>/g, '');
                const words = text.split(/\s+/).filter(w => w.length > 0).length;
                const checks = { title: false, desc: false, words: false, density: false, structure: false };

                // 1. Title Analysis (20 pts)
                const title = document.querySelector('input[name="title"]').value;
                if (title.length >= 30 && title.length <= 60) {
                    score += 20;
                    checks.title = true;
                }

                // 2. Meta Description (20 pts)
                const metaDesc = document.querySelector('textarea[name="meta_description"]').value;
                if (metaDesc.length >= 120 && metaDesc.length <= 160) {
                    score += 20;
                    checks.desc = true;
                }

                // 3. Word Count (20 pts)
                if (words >= 300) {
                    score += 20;
                    checks.words = true;
                }

                // 4. Keyword Density (30 pts)
                const keywordInput = document.querySelector('input[name="meta_keywords"]').value;
                if (keywordInput.trim() && words > 0) {
                    const firstKeyword = keywordInput.split(',')[0].trim().toLowerCase();
                    if (firstKeyword) {
                        const escaped = firstKeyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                        const regex = new RegExp(escaped, 'gi');
                        const count = (text.toLowerCase().match(regex) || []).length;
                        const density = (count / words) * 100;
                        if (density >= 0.5 && density <= 2.5) {
                            score += 30;
                            checks.density = true;
                        }
                    }
                }

                // 5. Structure (10 pts)
                const headings = (content.match(/<h[2-6]/g) || []).length;
                if (headings > 0) {
                    score += 10;
                    checks.structure = true;
                }

                const rounded = Math.round(score);
                document.getElementById('seo-score').innerText = rounded + '%';
                document.getElementById('seo-bar').style.width = rounded + '%';

                // Update Checklist UI
                Object.keys(checks).forEach(key => {
                    const el = document.querySelector(`[data-check="${key}"]`);
                    if (el) {
                        const icon = el.querySelector('.status-icon');
                        if (checks[key]) {
                            el.classList.replace('text-white/30', 'text-emerald-400');
                            icon.innerText = '✅';
                        } else {
                            el.classList.replace('text-emerald-400', 'text-white/30');
                            icon.innerText = '⚪';
                        }
                    }
                });
            }

            // Trigger update on meta changes too
            ['input[name="title"]', 'textarea[name="meta_description"]', 'input[name="meta_keywords"]'].forEach(selector => {
                document.querySelector(selector).addEventListener('input', () => {
                    const editorData = document.querySelector('#editor').value;
                    updateSeo(editorData);
                });
            });
        </script>
        <style>
            .ck-editor__editable { min-height: 500px !important; border: 0 !important; background: transparent !important; padding: 2rem !important; font-size: 1rem !important; }
            .ck-toolbar { border: 0 !important; border-bottom: 2px solid #f1f5f9 !important; background: #ffffff !important; padding: 1rem !important; }
            .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) { border: 0 !important; }
            .ck-focused { box-shadow: none !important; }
        </style>
    @endpush
</x-admin-layout>
