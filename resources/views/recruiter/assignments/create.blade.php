<x-recruiter-layout>
    <x-slot name="title">Manifest Assessment | TaskFlow Builder</x-slot>

    @push('head')
        <!-- SimpleMDE Markdown Editor ✍️ -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
        <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    @endpush

    <div class="max-w-4xl mx-auto">
        <div class="mb-10 flex items-center gap-4">
            <a href="{{ route('recruiter.assessments.index') }}" class="w-12 h-12 glass rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Manifest <span class="text-primary">Task</span></h1>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em] mt-1">Configure candidate validation parameters</p>
            </div>
        </div>

        <form action="{{ route('recruiter.assessments.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Core Configuration ⚙️ -->
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-10 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Task Title</label>
                        <input type="text" name="title" placeholder="e.g. Content Writing Intern Test" required
                               class="w-full h-14 bg-white border border-slate-300 rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all placeholder:text-slate-300 shadow-sm">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Linked Job (Optional)</label>
                        <select name="job_id" class="w-full h-14 bg-white border border-slate-300 rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all shadow-sm">
                            <option value="">Independent Assessment</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}">{{ $job->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Candidate Role</label>
                        <input type="text" name="role" placeholder="e.g. Video Editor"
                               class="w-full h-14 bg-white border border-slate-300 rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all placeholder:text-slate-300 shadow-sm">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Task Category</label>
                        <select name="task_type" class="w-full h-14 bg-white border border-slate-300 rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all shadow-sm">
                            <option value="General">General Assessment</option>
                            <option value="Blog">✍️ Blog / Content Writing</option>
                            <option value="Video">🎬 Video Editing / Creative</option>
                            <option value="Sales">🤝 Sales / Business Dev</option>
                            <option value="Code">💻 Technical / Coding</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Task Instructions (Markdown Support)</label>
                    <div class="prose max-w-none">
                        <textarea id="markdown-editor" name="instructions" placeholder="Describe the task in detail. What are the deliverables?" required
                              class="w-full bg-slate-50 border border-slate-100 rounded-3xl p-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all"></textarea>
                    </div>
                </div>
            </div>

            <script>
                var simplemde = new SimpleMDE({ 
                    element: document.getElementById("markdown-editor"),
                    spellChecker: false,
                    placeholder: "Detail the task nodes... Support for headings, bullets, and links manifested.",
                    status: false,
                    autosave: { enabled: true, uniqueId: "mcv_assess_builder", delay: 1000 },
                });
            </script>

            <!-- Submission Logistics 📥 -->
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-10 space-y-8">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Submission Logistics</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Allowed Submission Modes</label>
                        <div class="flex flex-wrap gap-4">
                            <label class="flex items-center gap-3 bg-slate-50 px-5 py-3 rounded-xl border border-slate-100 cursor-pointer hover:border-primary transition-all">
                                <input type="checkbox" name="submission_types[]" value="link" checked class="w-4 h-4 rounded text-primary focus:ring-primary">
                                <span class="text-[10px] font-black uppercase tracking-widest">🔗 Drive Link</span>
                            </label>
                            <label class="flex items-center gap-3 bg-slate-50 px-5 py-3 rounded-xl border border-slate-100 cursor-pointer hover:border-primary transition-all">
                                <input type="checkbox" name="submission_types[]" value="file" class="w-4 h-4 rounded text-primary focus:ring-primary">
                                <span class="text-[10px] font-black uppercase tracking-widest">📁 File Upload</span>
                            </label>
                            <label class="flex items-center gap-3 bg-slate-50 px-5 py-3 rounded-xl border border-slate-100 cursor-pointer hover:border-primary transition-all">
                                <input type="checkbox" name="submission_types[]" value="text" class="w-4 h-4 rounded text-primary focus:ring-primary">
                                <span class="text-[10px] font-black uppercase tracking-widest">📝 Text Content</span>
                            </label>
                        </div>
                        <p class="text-[9px] text-slate-400 font-bold italic">* Note: MCV File uploads auto-delete after 10 days to preserve space node integrity.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Submission Deadline</label>
                        <input type="datetime-local" name="deadline"
                               class="w-full h-14 bg-white border border-slate-300 rounded-2xl px-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all shadow-sm">
                    </div>
                </div>

                <div class="flex items-center gap-4 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex items-center h-5">
                        <input name="is_public" type="checkbox" value="1" checked class="w-5 h-5 rounded text-primary focus:ring-primary">
                    </div>
                    <div class="ml-3 text-sm">
                        <label class="font-black text-slate-900 uppercase tracking-widest text-[10px]">Allow External Submissions</label>
                        <p class="text-slate-400 text-[10px] font-bold">Generated link will be accessible without MCV Auth.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="h-16 px-12 bg-slate-900 text-white rounded-[1.5rem] font-black text-[10px] uppercase tracking-[0.2em] flex items-center gap-3 hover:bg-primary hover:scale-105 transition-all shadow-xl shadow-slate-200">
                    Manifest Task node 🛰️
                </button>
            </div>
        </form>
    </div>
</x-recruiter-layout>
