<x-app-layout>
    @section('title', 'Manifest Academic Knowledge | Academic Hub')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2 uppercase italic">Manifest Knowledge 🌌</h1>
            <p class="text-slate-500 font-bold text-sm uppercase tracking-widest opacity-60">Architecting the Academic Multiverse</p>
        </div>

        <form action="{{ route('guides.store') }}" method="POST" id="manifest-form" class="space-y-8 pb-20">
            @csrf
            
            <div class="glass p-8 md:p-12 rounded-[3rem] border border-slate-100 shadow-xl space-y-10">
                <!-- Basic Intel -->
                <div class="space-y-6">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-primary flex items-center gap-3">
                        <span class="w-8 h-[1px] bg-primary/20"></span>
                        Basic Node Intel
                    </h3>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Guide Title (e.g. B.Tech AKTU 2nd Year Syllabus)</label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Enter a descriptive title..." class="w-full h-14 bg-white border border-slate-100 rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all shadow-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Category</label>
                            <select name="category" required class="w-full h-14 bg-white border border-slate-100 rounded-2xl px-6 text-sm font-bold text-slate-600 focus:ring-4 focus:ring-primary/5 transition-all shadow-sm">
                                @foreach(['Syllabus', 'College Guide', 'Admission', 'Career', 'Notice'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Target Node (University/College)</label>
                            <input type="text" name="target_university" value="{{ old('target_university') }}" placeholder="e.g. AKTU, Mumbai University" class="w-full h-14 bg-white border border-slate-100 rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Rich Text Editor -->
                <div class="space-y-6">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-primary flex items-center gap-3">
                        <span class="w-8 h-[1px] bg-primary/20"></span>
                        Knowledge Manifestation
                    </h3>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Content (Paste or Write detailed guide)</label>
                        <div id="editor-wrapper" class="rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                            <div id="editor" class="h-96 bg-white font-sans text-base"></div>
                        </div>
                        <input type="hidden" name="content" id="content-input" value="{{ old('content') }}">
                        @error('content')
                            <p class="text-red-500 text-[10px] font-black uppercase mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SEO Intelligence -->
                <div class="space-y-6 bg-slate-50/50 -mx-8 md:-mx-12 px-8 md:px-12 py-10 border-y border-slate-100">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 flex items-center gap-3">
                        <span class="w-8 h-[1px] bg-slate-200"></span>
                        SEO Intelligence (Manual Override)
                    </h3>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Meta Title (Max 60 chars)</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" maxlength="60" placeholder="SEO Title for Google..." class="w-full h-12 bg-white border border-slate-100 rounded-xl px-6 text-xs font-bold focus:ring-4 focus:ring-primary/5 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Meta Description (Max 160 chars)</label>
                        <textarea name="meta_description" maxlength="160" rows="2" placeholder="Brief summary for search results..." class="w-full bg-white border border-slate-100 rounded-xl px-6 py-4 text-xs font-bold focus:ring-4 focus:ring-primary/5 transition-all">{{ old('meta_description') }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="syllabus, guide, aktu, exams..." class="w-full h-12 bg-white border border-slate-100 rounded-xl px-6 text-xs font-bold focus:ring-4 focus:ring-primary/5 transition-all">
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-6">
                    <button type="submit" id="submit-btn" class="w-full h-16 bg-primary text-white font-black text-xs uppercase tracking-[0.2em] rounded-[2rem] shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-4">
                        Manifest Node to Hub 🛰️
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('head')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow {
            border: none !important;
            background: #F8FAFC;
            padding: 1rem !important;
            border-bottom: 1px solid #F1F5F9 !important;
        }
        .ql-container.ql-snow {
            border: none !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 16px !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Architect your academic knowledge here...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'clean']
                    ]
                }
            });

            // Restore old content if exists
            var oldContent = document.querySelector('#content-input').value;
            if (oldContent) {
                quill.root.innerHTML = oldContent;
            }

            var form = document.getElementById('manifest-form');
            form.addEventListener('submit', function(e) {
                var contentInput = document.getElementById('content-input');
                var html = quill.root.innerHTML;
                
                // If editor is empty or just contains empty tags
                if (quill.getText().trim().length === 0 && html.indexOf('<img') === -1) {
                    alert('Please enter some content before manifesting.');
                    e.preventDefault();
                    return false;
                }

                contentInput.value = html;
                document.getElementById('submit-btn').innerHTML = 'Synchronizing Multiverse... 🛰️';
                document.getElementById('submit-btn').disabled = true;
            });
        });
    </script>
    @endpush
</x-app-layout>
