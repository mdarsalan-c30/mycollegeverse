<x-app-layout>
    @section('title', 'Professional Resume Builder | MyCollegeVerse')
    
    <div class="min-h-screen bg-slate-50 py-12" x-data="resumeBuilder()" x-init="init()">
        <div class="max-w-7xl mx-auto px-4">
            
            <!-- INITIAL CHOICE SCREEN -->
            <div x-show="!mode" class="max-w-4xl mx-auto text-center py-20 animate-fade-in">
                <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase mb-4">Choose Your <span class="text-primary">Building Mode</span></h1>
                <p class="text-slate-500 font-bold mb-12 uppercase tracking-widest text-xs text-center">How do you want to manifest your professional identity?</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <button @click="mode = 'guided'" class="bg-white p-10 rounded-[3rem] border-2 border-transparent hover:border-primary transition-all shadow-xl group text-left">
                        <div class="w-20 h-20 bg-primary/10 rounded-3xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform"><svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg></div>
                        <h3 class="text-2xl font-black text-slate-800 uppercase italic mb-2">Guided Builder</h3>
                        <p class="text-slate-400 font-bold text-sm leading-relaxed">Fast, role-based auto-fill and professional templates.</p>
                        <div class="mt-8 flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-widest">Start Building <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg></div>
                    </button>
                    <button @click="mode = 'latex'" class="bg-slate-900 p-10 rounded-[3rem] border-2 border-transparent hover:border-primary transition-all shadow-xl group text-left">
                        <div class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform"><svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg></div>
                        <h3 class="text-2xl font-black text-white uppercase italic mb-2">LaTeX Expert</h3>
                        <p class="text-slate-400 font-bold text-sm leading-relaxed">Full control via LaTeX code. Real-time preview and raw code management.</p>
                        <div class="mt-8 flex items-center gap-2 text-white font-black text-[10px] uppercase tracking-widest">Open Editor <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg></div>
                    </button>
                </div>
            </div>

            <!-- LATEX EXPERT MODE -->
            <div x-show="mode === 'latex'" style="display: none;" class="animate-fade-in h-[85vh]">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <button @click="mode = null" class="text-slate-400 font-black text-[10px] uppercase tracking-widest mb-2 flex items-center gap-2 hover:text-primary transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg> Back</button>
                        <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase italic">LaTeX <span class="text-primary">Expert</span></h1>
                    </div>
                    <div class="flex items-center gap-4">
                        <button @click="recompile" class="bg-slate-800 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl">Recompile Preview</button>
                        <button @click="saveResume" class="bg-primary text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-primary-dark transition-all shadow-xl">Save & Manifest</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-full pb-20">
                    <div class="bg-slate-900 rounded-[2.5rem] p-6 shadow-2xl relative overflow-hidden flex flex-col">
                        <textarea x-model="latexCode" @input.debounce.500ms="recompile" class="flex-1 w-full bg-transparent border-none text-slate-300 font-mono text-xs focus:ring-0 resize-none p-4 custom-scrollbar" spellcheck="false"></textarea>
                    </div>
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-200 overflow-y-auto custom-scrollbar">
                        <div id="latex-preview-mount" class="font-serif"><!-- Preview Content --></div>
                    </div>
                </div>
            </div>
            <!-- Guided Mode logic stays here but omitting for brevity -->
        </div>
    </div>

    @push('scripts')
    <script>
        function resumeBuilder() {
            return {
                mode: null, currentStep: 0, roleSelected: false, steps: ['Basic Details', 'Projects', 'Skills'],
                roleTemplates: @json($roleTemplates), resume: @json($initialData), latexCode: @json($defaultLatex),
                init() { setTimeout(() => this.recompile(), 500); },
                selectRole(role) { const template = this.roleTemplates[role]; this.resume.personal.role = role; this.resume.personal.summary = template.summary; this.resume.skills = [...new Set([...this.resume.skills, ...template.skills])]; this.roleSelected = true; },
                addSkill() { if (this.newSkill.trim()) { this.resume.skills.push(this.newSkill.trim()); this.newSkill = ''; } },
                removeSkill(index) { this.resume.skills.splice(index, 1); },
                addProject() { this.resume.projects.push({ title: '', description: '' }); },

                recompile() {
                    let code = this.latexCode;
                    code = code.replace(/%.*$/gm, ''); // Strip comments
                    code = code.replace(/\\\\/g, ' '); // Strip double backslashes
                    let html = '<div class="text-slate-900 space-y-5">';
                    
                    // Header
                    const name = code.match(/\\huge \\textbf\{([^}]+)\}/)?.[1] || "Name";
                    const degree = code.match(/\\small ([^}]+)\}/)?.[1] || "";
                    html += `<div class="flex justify-between items-start mb-6 border-b-2 border-black pb-4">
                        <div><h1 class="text-2xl font-bold uppercase">${name}</h1><p class="text-xs font-bold text-slate-600">${degree}</p></div>
                        <div class="text-right text-[10px] space-y-0.5 font-medium">`;
                    if (code.match(/([^\\n\r\t{}&]+, India)/)) html += `<p>${code.match(/([^\\n\r\t{}&]+, India)/)[1].trim().replace(/[\\{}]/g, '')}</p>`;
                    if (code.match(/\\email\{([^}]+)\}/)) html += `<p><a href="mailto:${code.match(/\\email\{([^}]+)\}/)[1]}" class="text-blue-700 underline">${code.match(/\\email\{([^}]+)\}/)[1]}</a></p>`;
                    if (code.match(/\\phone\{([0-9+\-]+)\}/)) html += `<p>+91-${code.match(/\\phone\{([0-9+\-]+)\}/)[1]}</p>`;
                    if (code.match(/\\href\{([^}]+)\}\{LinkedIn\}/)) html += `<p><a href="${code.match(/\\href\{([^}]+)\}\{LinkedIn\}/)[1]}" target="_blank" class="text-blue-700 underline">LinkedIn</a></p>`;
                    html += `</div></div>`;

                    // Sections
                    const sections = code.match(/\\section\{([^}]+)\}([\s\S]*?)(?=\\section|\\end\{document\})/g);
                    if (sections) {
                        sections.forEach(sec => {
                            const title = sec.match(/\\section\{([^}]+)\}/)[1];
                            let content = sec.replace(/\\section\{[^}]+\}/, '').trim();
                            content = content.replace(/\\resumeSubheading\s*\{([^}]+)\}\s*\{([^}]+)\}/g, '<div class="flex justify-between font-bold text-xs"><span>$1</span><span class="text-right">$2</span></div>');
                            content = content.replace(/\\textbf\{([^}]+)\}/g, '<strong class="font-bold">$1</strong>');
                            content = content.replace(/\\href\{([^}]+)\}\{([^}]+)\}/g, '<a href="$1" target="_blank" class="text-blue-700 underline">$2</a>');

                            if (strpos(content, '\\item') !== false) {
                                preg_match_all('/\\\\item\s+([^\n\\%]+)/', content, $items); // Note: Simplified for JS
                                // Fallback for JS regex limits
                                const items = content.match(/\\item\s+([^\n\\%]+)/g);
                                if (items) {
                                    html += `<ul class="list-disc ml-4 mt-1">`;
                                    items.forEach(i => {
                                        html += `<li class="text-[11px] mb-0.5">${i.replace('\\item ', '').trim().replace(/[\\{}&]/g, '')}</li>`;
                                    });
                                    html += `</ul>`;
                                }
                            } else {
                                html += `<p class="text-[11px] text-justify leading-snug">${content.replace(/[\\{}&]/g, ' ')}</p>`;
                            }

                            html += `<div><h3 class="text-xs font-black uppercase border-b border-black pb-1 mb-2">${title}</h3></div>`;
                        });
                    }
                    html += '</div>';
                    document.getElementById('latex-preview-mount').innerHTML = html;
                },

                async saveResume() {
                    const title = prompt("Resume Title:", "My Resume");
                    if (!title) return;
                    try {
                        const res = await fetch('{{ route('resumes.store') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ title: title, data: this.mode === 'latex' ? { raw_latex: this.latexCode } : this.resume, template_id: 'latex-classic' })
                        });
                        const data = await res.json();
                        if (data.status === 'success') window.location.href = data.redirect;
                    } catch (e) { alert("Error."); }
                }
            }
        }
        function strpos(haystack, needle) { return haystack.indexOf(needle) !== -1; }
    </script>
    @endpush
</x-app-layout>
