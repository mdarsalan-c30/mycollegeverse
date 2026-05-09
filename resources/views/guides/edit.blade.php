@extends('layouts.hub')

@section('title', 'Edit Guide Node | Academic Hub')

@section('content')
    <div class="max-w-4xl mx-auto px-6 py-12">
        <div class="mb-16 text-center">
            <h1 class="text-5xl font-black text-slate-900 tracking-tight mb-4 leading-tight italic uppercase">Re-Manifest <span class="gradient-text">Node.</span></h1>
            <p class="text-slate-400 font-bold text-sm uppercase tracking-[0.3em] opacity-80">Re-architecting the Academic Knowledge Base</p>
        </div>

        <form action="{{ route('guides.update', $guide->id) }}" method="POST" id="manifest-form" enctype="multipart/form-data" class="space-y-10 pb-32">
            @csrf
            @method('PUT')
            
            <div class="glass p-10 md:p-16 rounded-[4rem] border border-slate-100 shadow-2xl space-y-12">
                <!-- Basic Intel -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center font-black">01</div>
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-900">Node Identifier</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Title</label>
                            <input type="text" name="title" value="{{ old('title', $guide->title) }}" required class="w-full h-16 bg-white border border-slate-100 rounded-[1.5rem] px-8 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all shadow-sm">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Sector</label>
                                <select name="category" required class="w-full h-16 bg-white border border-slate-100 rounded-[1.5rem] px-8 text-sm font-bold text-slate-600 focus:ring-4 focus:ring-primary/5 transition-all shadow-sm">
                                    @foreach(['Syllabus', 'College Guide', 'Admission', 'Career', 'Notice'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category', $guide->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Target University</label>
                                <input type="text" name="target_university" value="{{ old('target_university', $guide->target_university) }}" class="w-full h-16 bg-white border border-slate-100 rounded-[1.5rem] px-8 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Matrix -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center font-black">02</div>
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-900">Knowledge Update</h3>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Description / Content</label>
                        <div id="editor-wrapper" class="rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm">
                            <div id="editor" class="h-96 bg-white font-sans text-base"></div>
                        </div>
                        <input type="hidden" name="content" id="content-input" value="{{ old('content', $guide->content) }}">
                    </div>

                    <!-- PDF Update Component -->
                    <div class="bg-slate-50/50 p-8 rounded-[2.5rem] border border-dashed border-slate-200">
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl shadow-sm">
                                @if($guide->file_path) ✅ @else 📄 @endif
                            </div>
                            <div class="flex-1 text-center md:text-left">
                                <h4 class="text-sm font-black text-slate-900 uppercase tracking-tight mb-1">
                                    {{ $guide->file_path ? 'Update Supplementary PDF' : 'Attach Supplementary PDF' }}
                                </h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    {{ $guide->file_path ? 'Current: ' . basename($guide->file_path) : 'Attach official documents (Max 10MB)' }}
                                </p>
                            </div>
                            <div class="relative">
                                <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="bg-white px-8 py-3 rounded-xl border border-slate-100 text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all">
                                    {{ $guide->file_path ? 'Change File' : 'Select File' }}
                                </div>
                            </div>
                        </div>
                        <div id="file-name" class="mt-4 text-center text-[10px] font-black text-primary uppercase tracking-widest hidden"></div>
                    </div>
                </div>

                <!-- SEO Matrix -->
                <div class="space-y-8 pt-8 border-t border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center font-black">03</div>
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">SEO Maintenance</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <input type="text" name="meta_title" value="{{ old('meta_title', $guide->meta_title) }}" placeholder="SEO Meta Title" class="w-full h-12 bg-slate-50 border-none rounded-xl px-6 text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-primary/5 transition-all">
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $guide->meta_keywords) }}" placeholder="SEO Meta Keywords" class="w-full h-12 bg-slate-50 border-none rounded-xl px-6 text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-primary/5 transition-all">
                        <textarea name="meta_description" rows="2" class="w-full bg-slate-50 border-none rounded-xl px-6 py-4 text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-primary/5 transition-all">{{ old('meta_description', $guide->meta_description) }}</textarea>
                    </div>
                </div>

                <!-- Action Node -->
                <div class="pt-10">
                    <button type="submit" id="submit-btn" class="w-full h-20 bg-slate-900 text-white font-black text-xs uppercase tracking-[0.3em] rounded-[2.5rem] shadow-2xl shadow-black/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-4">
                        Update Multiverse Node 🛰️
                    </button>
                    <div class="text-center mt-6">
                        <a href="{{ route('guides.show', $guide->slug) }}" class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em] hover:text-primary transition-all">Cancel Update</a>
                    </div>
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
                placeholder: 'Update your academic knowledge here...',
                modules: { toolbar: [[{ 'header': [1, 2, 3, false] }], ['bold', 'italic', 'underline'], ['blockquote', 'code-block'], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['link', 'clean']] }
            });

            // Restore content
            var oldContent = document.querySelector('#content-input').value;
            if (oldContent) quill.root.innerHTML = oldContent;

            // File name display
            document.getElementById('pdf_file').addEventListener('change', function(e) {
                var name = e.target.files[0] ? e.target.files[0].name : '';
                var el = document.getElementById('file-name');
                if(name) { el.textContent = 'New File Attached: ' + name; el.classList.remove('hidden'); }
            });

            var form = document.getElementById('manifest-form');
            form.addEventListener('submit', function(e) {
                document.getElementById('content-input').value = quill.root.innerHTML;
                document.getElementById('submit-btn').innerHTML = 'Syncing Update... 🛰️';
                document.getElementById('submit-btn').disabled = true;
            });
        });
    </script>
    @endpush
@endsection
