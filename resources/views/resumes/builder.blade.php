<x-app-layout>
    @section('title', 'Professional Resume Builder | MyCollegeVerse')
    
    <div class="min-h-screen bg-slate-50 py-12" x-data="resumeBuilder()" x-init="init()">
        <div class="max-w-7xl mx-auto px-4">
            
            <!-- INITIAL CHOICE SCREEN -->
            <div x-show="!mode" class="max-w-4xl mx-auto text-center py-20 animate-fade-in">
                <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase mb-4">Choose Your <span class="text-primary">Building Mode</span></h1>
                <p class="text-slate-500 font-bold mb-12 uppercase tracking-widest text-xs">How do you want to manifest your professional identity?</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <button @click="mode = 'guided'" class="bg-white p-10 rounded-[3rem] border-2 border-transparent hover:border-primary transition-all shadow-xl group text-left">
                        <div class="w-20 h-20 bg-primary/10 rounded-3xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 uppercase italic mb-2">Guided Builder</h3>
                        <p class="text-slate-400 font-bold text-sm leading-relaxed">Fast, role-based auto-fill and professional templates.</p>
                        <div class="mt-8 flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-widest">Start Building <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg></div>
                    </button>

                    <button @click="mode = 'latex'" class="bg-slate-900 p-10 rounded-[3rem] border-2 border-transparent hover:border-primary transition-all shadow-xl group text-left">
                        <div class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white uppercase italic mb-2">LaTeX Expert</h3>
                        <p class="text-slate-400 font-bold text-sm leading-relaxed">Full control via LaTeX code. Real-time preview and raw code management.</p>
                        <div class="mt-8 flex items-center gap-2 text-white font-black text-[10px] uppercase tracking-widest">Open Editor <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg></div>
                    </button>
                </div>
            </div>

            <!-- GUIDED BUILDER FLOW -->
            <div x-show="mode === 'guided'" style="display: none;" class="animate-fade-in">
                 <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
                    <div>
                        <button @click="mode = null" class="text-slate-400 font-black text-[10px] uppercase tracking-widest mb-2 flex items-center gap-2 hover:text-primary transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg> Back to Modes
                        </button>
                        <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase">Guided <span class="text-primary">Builder</span></h1>
                    </div>
                    <div class="flex items-center gap-4">
                        <button @click="saveResume" class="bg-primary text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-primary-dark transition-all shadow-xl">
                            Save & Manifest
                        </button>
                    </div>
                </div>

                <div x-show="!roleSelected" class="bg-white rounded-[2.5rem] p-12 text-center border border-slate-100 shadow-xl mb-12">
                    <h2 class="text-3xl font-black text-slate-800 mb-4 uppercase italic">Choose Your Role 🚀</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($roleTemplates as $role => $data)
                        <button @click="selectRole('{{ $role }}')" class="p-8 bg-slate-50 rounded-3xl border-2 border-transparent hover:border-primary hover:bg-primary/5 transition-all group">
                            <h3 class="font-black text-slate-800 uppercase tracking-tight">{{ $role }}</h3>
                        </button>
                        @endforeach
                    </div>
                </div>

                <div x-show="roleSelected" class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                     <div class="space-y-8">
                        <div class="flex items-center gap-2 overflow-x-auto pb-4 custom-scrollbar">
                            <template x-for="(step, index) in steps" :key="index">
                                <button @click="currentStep = index" :class="currentStep === index ? 'bg-primary text-white' : 'bg-white text-slate-400'" class="px-6 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all shrink-0 border border-slate-100">
                                    <span x-text="step"></span>
                                </button>
                            </template>
                        </div>
                        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-sm border border-slate-100 min-h-[600px]">
                            <div x-show="currentStep === 0" class="space-y-8 animate-fade-in">
                                <h2 class="text-2xl font-black text-slate-800 uppercase italic">Basic <span class="text-primary">Details</span></h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <input type="text" x-model="resume.personal.name" placeholder="Full Name" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                                    <input type="text" x-model="resume.personal.role" placeholder="Target Role" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                                    <input type="email" x-model="resume.personal.email" placeholder="Email" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                                    <input type="text" x-model="resume.personal.phone" placeholder="Phone" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold">
                                </div>
                                <textarea x-model="resume.personal.summary" placeholder="Summary" rows="4" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:block sticky top-32 h-[calc(100vh-200px)] overflow-y-auto bg-white rounded-[2.5rem] shadow-2xl border border-slate-200 p-8">
                        <div id="resume-preview-guided" class="min-h-full font-serif">
                            <div class="border-b border-slate-900 pb-4 mb-4">
                                <h1 class="text-2xl font-bold uppercase" x-text="resume.personal.name || 'YOUR NAME'"></h1>
                                <p class="text-xs font-bold text-slate-500 uppercase" x-text="resume.personal.role"></p>
                            </div>
                            <div class="mb-6"><p class="text-[11px] text-justify leading-relaxed" x-text="resume.personal.summary"></p></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LATEX EXPERT MODE -->
            <div x-show="mode === 'latex'" style="display: none;" class="animate-fade-in h-[85vh]">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <button @click="mode = null" class="text-slate-400 font-black text-[10px] uppercase tracking-widest mb-2 flex items-center gap-2 hover:text-primary transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg> Back
                        </button>
                        <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase italic">LaTeX <span class="text-primary">Expert</span></h1>
                    </div>
                    <div class="flex items-center gap-4">
                        <button @click="recompile" class="bg-slate-800 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl flex items-center gap-3">
                            Recompile Preview
                        </button>
                        <button @click="saveResume" class="bg-primary text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-primary-dark transition-all shadow-xl">
                            Save & Manifest
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-full pb-20">
                    <div class="bg-slate-900 rounded-[2.5rem] p-6 shadow-2xl relative overflow-hidden flex flex-col">
                        <div class="absolute top-0 right-0 p-4"><span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-600">tex_editor_v1.0</span></div>
                        <textarea x-model="latexCode" @input.debounce.500ms="recompile" class="flex-1 w-full bg-transparent border-none text-slate-300 font-mono text-xs focus:ring-0 resize-none p-4 custom-scrollbar" spellcheck="false"></textarea>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-200 overflow-y-auto custom-scrollbar">
                        <div id="latex-preview-mount" class="font-serif">
                            <!-- Preview Content -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function resumeBuilder() {
            return {
                mode: null,
                currentStep: 0,
                roleSelected: false,
                steps: ['Basic Details', 'Projects', 'Skills'],
                roleTemplates: @json($roleTemplates),
                newSkill: '',
                resume: @json($initialData),
                latexCode: @json($defaultLatex),

                init() {
                    this.resume.template_id = 'ats-clean';
                    setTimeout(() => this.recompile(), 500);
                },

                selectRole(role) {
                    const template = this.roleTemplates[role];
                    this.resume.personal.role = role;
                    this.resume.personal.summary = template.summary;
                    this.resume.skills = [...new Set([...this.resume.skills, ...template.skills])];
                    this.roleSelected = true;
                },

                addSkill() { if (this.newSkill.trim()) { this.resume.skills.push(this.newSkill.trim()); this.newSkill = ''; } },
                removeSkill(index) { this.resume.skills.splice(index, 1); },
                addProject() { this.resume.projects.push({ title: '', description: '' }); },

                recompile() {
                    let code = this.latexCode;
                    // Strip comments
                    code = code.replace(/%.*$/gm, '');
                    
                    let html = '<div class="text-slate-900 space-y-5">';
                    
                    // Parse Header
                    const name = code.match(/\\huge \\textbf\{([^}]+)\}/)?.[1] || "Name";
                    const degree = code.match(/\\small ([^}]+)\}/)?.[1] || "";
                    
                    html += `<div class="flex justify-between items-start mb-6 border-b-2 border-black pb-4">
                        <div><h1 class="text-2xl font-bold uppercase">${name}</h1><p class="text-xs font-bold">${degree}</p></div>
                        <div class="text-right text-[10px] space-y-0.5 font-medium">`;
                    
                    if (code.match(/([^\\n\r\t{}&]+, India)/)) html += `<p>${code.match(/([^\\n\r\t{}&]+, India)/)[1].trim().replace(/[\\{}]/g, '')}</p>`;
                    if (code.match(/\\email\{([^}]+)\}/)) html += `<p>${code.match(/\\email\{([^}]+)\}/)[1]}</p>`;
                    if (code.match(/\\phone\{([^}]+)\}/)) html += `<p>+91-${code.match(/\\phone\{([^}]+)\}/)[1]}</p>`;
                    if (code.match(/LinkedIn/)) html += `<p>LinkedIn</p>`;
                    html += `</div></div>`;

                    // Parse Sections
                    const sections = code.match(/\\section\{([^}]+)\}([\s\S]*?)(?=\\section|\\end\{document\})/g);
                    if (sections) {
                        sections.forEach(sec => {
                            const title = sec.match(/\\section\{([^}]+)\}/)[1];
                            let content = sec.replace(/\\section\{[^}]+\}/, '').trim();
                            
                            // Cleanup formatting
                            content = content.replace(/\\textbf\{([^}]+)\}/g, '<strong class="font-bold">$1</strong>');
                            content = content.replace(/\\resumeSubheading\{([^}]+)\}\{([^}]+)\}/g, '<div class="flex justify-between font-bold text-xs"><span>$1</span><span>$2</span></div>');
                            
                            // Specific Tabular Cleanup
                            content = content.replace(/\\begin\{tabular\}[\s\S]*?\\end\{tabular\}/g, (m) => m.replace(/\\begin\{tabular\}[^}]*\}|\\end\{tabular\}|&|\\\\|{tabular}/g, ' '));
                            content = content.replace(/\\begin\{tabularx\}[\s\S]*?\\end\{tabularx\}/g, (m) => m.replace(/\\begin\{tabularx\}[^}]*\}|\\end\{tabularx\}|&|\\\\|{tabularx}/g, ' '));

                            // Lists
                            content = content.replace(/\\begin\{itemize\}[\s\S]*?\\end\{itemize\}/g, (list) => {
                                const items = list.match(/\\item\s+([^\n\\%]+)/g);
                                if (!items) return '';
                                return `<ul class="list-disc ml-4 mt-1">${items.map(i => `<li class="text-[11px] mb-0.5">${i.replace('\\item ', '').trim().replace(/[\\{}&]/g, '')}</li>`).join('')}</ul>`;
                            });

                            html += `<div><h3 class="text-xs font-black uppercase border-b border-black pb-1 mb-2">${title}</h3><div class="text-[11px] text-justify leading-snug">${content.replace(/[\\{}&]/g, ' ')}</div></div>`;
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
    </script>
    @endpush
</x-app-layout>
