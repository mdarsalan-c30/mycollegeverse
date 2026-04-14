<x-admin-layout>
    @section('title', 'Reconfigure Article | Editorial Hub')

    <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" class="space-y-8" id="blog-form">
        @csrf
        @method('PATCH')
        
        <!-- Header Node -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-8 bg-white border border-slate-100 rounded-3xl gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Reconfigure Article</h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Optimizing Node: {{ $blog->title }}</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.blogs.index') }}" class="px-6 py-4 bg-slate-50 text-slate-400 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-100 transition-all">
                    Revert Changes
                </a>
                <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                    Commit Changes
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-6 bg-rose-50 border border-rose-100 rounded-2xl text-rose-500 text-xs font-bold">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Composition Cluster -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Primary Inputs -->
                <div class="bg-white border border-slate-100 p-8 rounded-[2rem] shadow-sm space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Article Title</label>
                        <input type="text" name="title" value="{{ $blog->title }}" required
                               class="w-full h-16 bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 text-xl font-black text-slate-900 focus:border-slate-900 focus:bg-white transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Editorial Segment (Category)</label>
                        <select name="category_id" class="w-full h-14 bg-slate-50 border-2 border-slate-100 rounded-xl px-6 text-sm font-bold text-slate-700 focus:border-slate-900 focus:bg-white transition-all outline-none appearance-none">
                            <option value="">Uncategorized Segment</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $blog->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rich Text Editor Container 📝 -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Article Body (formatting power enabled)</label>
                        <div class="bg-slate-50 border-2 border-slate-100 rounded-2xl overflow-hidden min-h-[500px]">
                            <textarea name="content" id="editor" class="hidden">{{ $blog->content }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Short Excerpt Node -->
                <div class="bg-white border border-slate-100 p-8 rounded-[2rem] shadow-sm">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Short Preview Excerpt</label>
                    <textarea name="excerpt" rows="3" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-6 text-sm font-medium focus:border-slate-900 focus:bg-white transition-all outline-none">{{ $blog->excerpt }}</textarea>
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
                        <div class="text-6xl font-black mb-2" id="seo-score">{{ $blog->seo_score }}%</div>
                        <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 transition-all duration-500" id="seo-bar" style="width: {{ $blog->seo_score }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Meta Configuration -->
                <div class="bg-white border border-slate-100 p-8 rounded-[2rem] space-y-6">
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Metadata Nucleus</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ $blog->meta_title }}" class="w-full h-12 bg-slate-50 border border-slate-100 rounded-xl px-4 text-xs font-bold focus:border-slate-900 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ $blog->meta_keywords }}"
                                class="w-full h-12 bg-slate-50 border border-slate-100 rounded-xl px-4 text-xs font-bold focus:border-slate-900 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Meta Description</label>
                            <textarea name="meta_description" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-xl p-4 text-xs font-medium focus:border-slate-900 transition-all">{{ $blog->meta_description }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Public Status -->
                <div class="bg-white border border-slate-100 p-8 rounded-[2rem] space-y-6">
                    <label class="flex items-center justify-between cursor-pointer group">
                        <span class="text-sm font-black text-slate-900 uppercase tracking-widest">Live Presence</span>
                        <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out">
                            <input type="checkbox" name="is_published" value="1" {{ $blog->is_published ? 'checked' : '' }} class="hidden peer">
                            <div class="w-full h-full bg-slate-100 rounded-full peer-checked:bg-emerald-500 transition-all"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:translate-x-6 shadow-sm"></div>
                        </div>
                    </label>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Discovery Illustration (Featured Image)</label>
                        <input type="text" name="featured_image" value="{{ $blog->featured_image }}"
                               class="w-full h-12 bg-slate-50 border border-slate-100 rounded-xl px-4 text-xs font-bold focus:border-slate-900 transition-all">
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
                        updateSeo(editor.getData());
                    });
                })
                .catch(error => { console.error(error); });

            function updateSeo(content) {
                let score = 0;
                const text = content.replace(/<[^>]*>/g, '');
                const words = text.split(/\s+/).filter(w => w.length > 0).length;
                const headings = (content.match(/<h[1-6]/g) || []).length;
                
                if (words >= 300) score += 40;
                else if (words > 0) score += (words / 300) * 40;
                
                if (headings > 0) score += 30;
                if (content.includes('<strong>') || content.includes('<b>')) score += 10;
                if (content.includes('<a ')) score += 10;
                if (content.includes('<ul>') || content.includes('<ol>')) score += 10;

                const rounded = Math.round(score);
                document.getElementById('seo-score').innerText = rounded + '%';
                document.getElementById('seo-bar').style.width = rounded + '%';
            }
        </script>
        <style>
            .ck-editor__editable { min-height: 500px !important; border: 0 !important; background: transparent !important; padding: 2rem !important; font-size: 1rem !important; }
            .ck-toolbar { border: 0 !important; border-bottom: 2px solid #f1f5f9 !important; background: #ffffff !important; padding: 1rem !important; }
            .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) { border: 0 !important; }
            .ck-focused { box-shadow: none !important; }
        </style>
    @endpush
</x-admin-layout>
