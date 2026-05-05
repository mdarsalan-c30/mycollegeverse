<x-app-layout>
    @section('title', 'Best Free LaTeX Resume Maker & Builder | MyCollegeVerse')
    @section('meta_description', 'Build professional LaTeX resumes for free. High-fidelity LaTeX editor with live preview, ATS-friendly templates, and one-click PDF export.')
    @section('meta_keywords', 'free resume builder, latex resume maker, professional resume templates, ATS friendly resume, MyCollegeVerse resume, student resume builder, overleaf alternative')

    <div class="min-h-screen bg-slate-50 py-12" x-data="resumeBuilder()" x-init="init()">
        <div class="max-w-7xl mx-auto px-4">
            
            <!-- INITIAL CHOICE SCREEN -->
            <div x-show="!mode" class="max-w-4xl mx-auto text-center py-20 animate-fade-in">
                <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase mb-4 leading-none">The Ultimate <span class="text-primary italic">Identity</span> Builder</h1>
                <p class="text-slate-400 font-bold text-lg mb-12 uppercase tracking-widest">Choose your path to professional excellence.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <button @click="mode = 'guided'" class="bg-white p-12 rounded-[3.5rem] shadow-xl group text-left border-b-8 border-primary hover:-translate-y-2 transition-all">
                        <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform text-3xl">✨</div>
                        <h3 class="text-3xl font-black uppercase italic mb-2 text-slate-800 tracking-tighter">Guided Builder</h3>
                        <p class="text-slate-400 font-bold text-sm leading-relaxed">Fill a simple form, get a professional PDF in minutes.</p>
                    </button>
                    <button @click="mode = 'latex'" class="bg-slate-900 p-12 rounded-[3.5rem] shadow-xl group text-left border-b-8 border-slate-700 hover:-translate-y-2 transition-all">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform text-3xl">⚛️</div>
                        <h3 class="text-3xl font-black text-white uppercase italic mb-2 tracking-tighter">LaTeX Expert</h3>
                        <p class="text-slate-500 font-bold text-sm leading-relaxed">Full control via LaTeX code. Overleaf-like live preview.</p>
                    </button>
                </div>
            </div>

            <!-- GUIDED MODE UI -->
            <div x-show="mode === 'guided'" style="display: none;" class="animate-fade-in pb-20">
                <div class="flex items-center justify-between mb-12">
                    <button @click="mode = null" class="text-slate-400 font-black uppercase text-[10px] tracking-widest flex items-center gap-2 hover:text-primary transition-colors">← Back to Modes</button>
                    <div class="flex items-center gap-4">
                        <div class="flex gap-2 mr-6">
                            <template x-for="(s, i) in steps">
                                <div class="h-1.5 rounded-full transition-all duration-500" :class="currentStep === i ? 'w-8 bg-primary' : 'w-2 bg-slate-200'"></div>
                            </template>
                        </div>
                        <button @click="saveResume" class="bg-primary text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl hover:bg-primary-dark">Manifest Identity</button>
                    </div>
                </div>

                <div class="max-w-4xl mx-auto">
                    <!-- Step 0: Basic Details -->
                    <div x-show="currentStep === 0" class="space-y-8 animate-fade-in">
                        <h2 class="text-4xl font-black text-slate-900 uppercase italic">Basic <span class="text-primary">Details</span></h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2"><label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Full Name</label><input x-model="resume.personal.name" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold focus:border-primary focus:ring-0 transition-all"></div>
                            <div class="space-y-2"><label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Target Role</label><input x-model="resume.personal.role" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold focus:border-primary focus:ring-0 transition-all"></div>
                            <div class="space-y-2"><label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Email Address</label><input x-model="resume.personal.email" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold focus:border-primary focus:ring-0 transition-all"></div>
                            <div class="space-y-2"><label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Phone Number</label><input x-model="resume.personal.phone" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold focus:border-primary focus:ring-0 transition-all"></div>
                        </div>
                        <div class="space-y-2"><label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Professional Summary</label><textarea x-model="resume.personal.summary" rows="4" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-4 font-bold focus:border-primary focus:ring-0 transition-all"></textarea></div>
                        <div class="flex justify-end"><button @click="currentStep = 1" class="bg-slate-900 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest">Next Phase →</button></div>
                    </div>

                    <!-- Step 1: Projects -->
                    <div x-show="currentStep === 1" class="space-y-8 animate-fade-in">
                        <div class="flex justify-between items-end"><h2 class="text-4xl font-black text-slate-900 uppercase italic">Key <span class="text-primary">Projects</span></h2><button @click="resume.projects.push({title:'', link:'', description:''})" class="text-primary font-black uppercase text-[10px] tracking-widest">+ Add Project</button></div>
                        <div class="space-y-4">
                            <template x-for="(p, i) in resume.projects" :key="i">
                                <div class="bg-white p-6 rounded-3xl border-2 border-slate-100 space-y-4 relative group">
                                    <button @click="resume.projects.splice(i, 1)" class="absolute top-4 right-4 text-slate-300 hover:text-rose-500">×</button>
                                    <div class="grid grid-cols-2 gap-4">
                                        <input x-model="p.title" placeholder="Project Title" class="bg-slate-50 border-none rounded-xl p-3 text-sm font-bold">
                                        <input x-model="p.link" placeholder="Project Link (GitHub/Live)" class="bg-slate-50 border-none rounded-xl p-3 text-sm font-bold">
                                    </div>
                                    <textarea x-model="p.description" placeholder="Project description..." rows="2" class="w-full bg-slate-50 border-none rounded-xl p-3 text-sm font-medium"></textarea>
                                </div>
                            </template>
                        </div>
                        <div class="flex justify-between"><button @click="currentStep = 0" class="text-slate-400 font-black uppercase text-xs tracking-widest underline">Previous</button><button @click="currentStep = 2" class="bg-slate-900 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest">Next Phase →</button></div>
                    </div>

                    <!-- Step 2: Skills -->
                    <div x-show="currentStep === 2" class="space-y-8 animate-fade-in">
                        <h2 class="text-4xl font-black text-slate-900 uppercase italic">Skill <span class="text-primary">Matrix</span></h2>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Core Skills (Comma separated)</label>
                            <input x-model="resume.skills" class="w-full bg-white border-2 border-slate-100 rounded-2xl p-6 font-bold text-lg focus:border-primary focus:ring-0 transition-all placeholder:text-slate-200" placeholder="Python, React, AWS, Docker, Kubernetes...">
                        </div>
                        <div class="flex justify-between"><button @click="currentStep = 1" class="text-slate-400 font-black uppercase text-xs tracking-widest underline">Previous</button><button @click="saveResume" class="bg-primary text-white px-12 py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-primary/20">Finalize & Preview →</button></div>
                    </div>
                </div>
            </div>

            <!-- LATEX EXPERT MODE -->
            <div x-show="mode === 'latex'" style="display: none;" class="animate-fade-in h-[85vh]">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-6">
                        <button @click="mode = null" class="text-slate-400 font-black uppercase text-[10px] tracking-widest flex items-center gap-2 hover:text-primary transition-colors">← Exit</button>
                        <div class="h-6 w-px bg-slate-200"></div>
                        <div class="flex gap-2">
                            <button @click="loadTemplate('ats')" class="text-[10px] font-black uppercase px-3 py-1 bg-slate-100 rounded-lg hover:bg-slate-200">ATS Clean</button>
                            <button @click="loadTemplate('modern')" class="text-[10px] font-black uppercase px-3 py-1 bg-slate-100 rounded-lg hover:bg-slate-200">Modern Minimal</button>
                            <button @click="loadTemplate('creative')" class="text-[10px] font-black uppercase px-3 py-1 bg-slate-100 rounded-lg hover:bg-slate-200">Creative Bold</button>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button @click="generateAndShare" class="bg-white border-2 border-primary text-primary px-6 py-4 rounded-2xl font-black uppercase tracking-widest flex items-center gap-2 hover:bg-primary hover:text-white transition-all shadow-lg shadow-primary/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                            Instant Share
                        </button>
                        <button @click="recompile" class="bg-slate-800 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl">Recompile</button>
                        <button @click="saveResume" class="bg-primary text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl hover:bg-primary-dark transition-all">Manifest</button>
                    </div>
                </div>

                <!-- SHARE MODAL -->
                <div x-show="showShareModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
                    <div class="bg-white rounded-[3rem] p-10 max-w-lg w-full shadow-2xl relative overflow-hidden text-center">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary to-blue-500"></div>
                        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tighter mb-2">Manifest <span class="text-primary">Live!</span></h3>
                        <p class="text-slate-500 font-bold mb-8 text-sm uppercase tracking-widest">Share these unique links.</p>
                        
                        <div class="space-y-4">
                            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 text-left">
                                <label class="text-[10px] font-black uppercase tracking-widest text-primary mb-2 block">Playground Link (Editor)</label>
                                <div class="flex items-center gap-3"><input readonly x-model="editorUrl" class="flex-1 bg-transparent border-none text-xs font-mono text-slate-600 focus:ring-0"><button @click="copyToClipboard(editorUrl)" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase">Copy</button></div>
                            </div>
                            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 text-left">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Professional Link (View)</label>
                                <div class="flex items-center gap-3"><input readonly x-model="manifestUrl" class="flex-1 bg-transparent border-none text-xs font-mono text-slate-600 focus:ring-0"><button @click="copyToClipboard(manifestUrl)" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase">Copy</button></div>
                            </div>
                        </div>
                        <button @click="showShareModal = false" class="w-full mt-8 bg-slate-100 text-slate-400 py-4 rounded-2xl font-black uppercase text-xs tracking-widest">Done</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-full pb-20">
                    <div class="bg-slate-900 rounded-[2.5rem] p-6 shadow-2xl flex flex-col relative group">
                        <div class="absolute top-4 right-8 text-[10px] font-black text-slate-600 uppercase tracking-widest">LaTeX Engine v2.1</div>
                        <textarea x-model="latexCode" @input.debounce.500ms="recompile" class="flex-1 w-full bg-transparent border-none text-slate-300 font-mono text-xs focus:ring-0 resize-none p-4 custom-scrollbar" spellcheck="false"></textarea>
                    </div>
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-200 overflow-y-auto custom-scrollbar">
                        <div id="latex-preview-mount" class="font-serif"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function resumeBuilder() {
            return {
                mode: @json(isset($resume_model) ? 'latex' : null), 
                currentStep: 0, steps: ['Basic', 'Projects', 'Skills'],
                roleTemplates: @json($roleTemplates), resume: @json($initialData), latexCode: @json($defaultLatex),
                showShareModal: false, editorUrl: '', manifestUrl: '',
                init() { setTimeout(() => this.recompile(), 500); },

                loadTemplate(t) {
                    if(t === 'ats') this.latexCode = `\\documentclass[letterpaper,10pt]{article}\n\\newcommand{\\name}{Vanshika Singh}\n\\section{Professional Summary}\nCloud Engineer focused on GCP...\n\\section{Education}\n\\resumeSubheading{B.Tech}{AKTU}\n\\end{document}`;
                    if(t === 'modern') this.latexCode = `\\documentclass[letterpaper,11pt]{article}\n\\newcommand{\\name}{Rahman S.}\n\\section{Summary}\nSoftware Engineer with 2+ years of experience...\n\\section{Skills}\nJava, Python, Docker\n\\end{document}`;
                    if(t === 'creative') this.latexCode = `\\documentclass[letterpaper,12pt]{article}\n\\newcommand{\\name}{Creative Mind}\n\\section{About Me}\nDesign focused developer...\n\\section{Projects}\nBuilt the Metaverse\n\\end{document}`;
                    this.recompile();
                },

                copyToClipboard(text) {
                    navigator.clipboard.writeText(text).then(() => alert('🚀 Link Copied!'));
                },

                async generateAndShare() {
                    let title = prompt("Name this Identity Manifest:", "Collaborative Resume");
                    if (!title) return;
                    try {
                        const res = await fetch('{{ route('resumes.store') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ title: title, data: { raw_latex: this.latexCode }, template_id: 'latex-classic' })
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            const slug = data.redirect.split('/').pop();
                            this.manifestUrl = data.redirect;
                            this.editorUrl = window.location.origin + '/resume/edit/' + slug;
                            this.showShareModal = true;
                        }
                    } catch (e) { alert("Error generating playground link."); }
                },
                
                recompile() {
                    let code = this.latexCode;
                    code = code.replace(/%.*$/gm, '');
                    const vars = {};
                    const newCommandRegex = /\\newcommand\{\\([^}]+)\}\{([^}]+)\}/g;
                    let match;
                    while ((match = newCommandRegex.exec(code)) !== null) { vars[match[1]] = match[2]; }
                    Object.keys(vars).forEach(key => { 
                        const regex = new RegExp('\\\\' + key + '(?![a-zA-Z])', 'g');
                        code = code.replace(regex, vars[key]); 
                    });

                    let html = '<div class="text-slate-900 space-y-6">';
                    const name = vars['name'] || code.match(/\\huge \\textbf\{([^}]+)\}/)?.[1] || "Name";
                    const role = code.match(/\\small ([^}]+)\}/)?.[1] || "Professional Profile";
                    
                    html += `<div class="flex justify-between items-start mb-6 border-b-2 border-black pb-5"><div><h1 contenteditable="true" @blur="syncToLatex($event, 'name')" class="text-4xl font-bold tracking-tight mb-1 outline-none focus:bg-yellow-50">${name}</h1><p contenteditable="true" @blur="syncToLatex($event, 'role')" class="text-sm font-bold text-slate-700 uppercase outline-none focus:bg-yellow-50">${role}</p></div><div class="text-right text-[10px] space-y-0.5 font-medium">`;
                    if (code.match(/([^\\n\r\t{}&]+, India)/)) html += `<p>${code.match(/([^\\n\r\t{}&]+, India)/)[1].trim()}</p>`;
                    if (vars['email']) html += `<p><a href="mailto:${vars['email']}" class="text-blue-700 underline">${vars['email']}</a></p>`;
                    if (vars['phone']) html += `<p>+91-${vars['phone']}</p>`;
                    if (code.match(/LinkedIn/)) html += `<p><a href="#" class="text-blue-700 underline font-bold">LinkedIn</a></p>`;
                    html += `</div></div>`;

                    const sections = code.match(/\\section\{([^}]+)\}([\s\S]*?)(?=\\section|\\end\{document\})/g);
                    if (sections) {
                        sections.forEach((sec, sIdx) => {
                            const title = sec.match(/\\section\{([^}]+)\}/)[1];
                            let content = sec.replace(/\\section\{[^}]+\}/, '').trim();
                            content = content.replace(/\\begin\{(tabular|tabularx)\}[\s\S]*?\\end\{\1\}/g, ' ');
                            content = content.replace(/\\resumeSubheading\s*\{([^}]+)\}\s*\{([^}]+)\}/g, '<div class="flex justify-between font-bold text-[14px] mt-1"><span>$1</span><span class="text-right">$2</span></div>');
                            content = content.replace(/\\textbf\{([^}]+)\}/g, '<strong>$1</strong>');
                            let sectionHtml = `<div><h3 class="text-[15px] font-bold uppercase border-b border-black pb-1 mb-2 tracking-wider">${title}</h3>`;
                            if (content.includes('\\item')) {
                                const itemMatches = content.match(/\\item\s+([\s\S]*?)(?=\\item|\\end\{itemize\})/g);
                                if (itemMatches) {
                                    sectionHtml += `<ul class="list-disc ml-6 mt-1 space-y-1">`;
                                    itemMatches.forEach((i, iIdx) => {
                                        let itemText = i.replace('\\item ', '').trim();
                                        itemText = itemText.replace(/[\\{}]/g, '');
                                        sectionHtml += `<li contenteditable="true" @blur="syncItemToLatex($event, ${sIdx}, ${iIdx})" class="text-[13.5px] leading-relaxed text-justify outline-none focus:bg-yellow-50">${itemText.trim()}</li>`;
                                    });
                                    sectionHtml += `</ul>`;
                                }
                            } else {
                                sectionHtml += `<div contenteditable="true" @blur="syncSectionToLatex($event, ${sIdx})" class="text-[13.5px] text-justify leading-relaxed mt-1 outline-none focus:bg-yellow-50">${content.replace(/[\\{}&]|\\\\/g, ' ').trim()}</div>`;
                            }
                            sectionHtml += `</div>`;
                            html += sectionHtml;
                        });
                    }
                    html += '</div>';
                    document.getElementById('latex-preview-mount').innerHTML = html;
                },

                syncToLatex(e, type) {
                    const newVal = e.target.innerText.trim();
                    if (type === 'name') {
                        if (this.latexCode.includes('\\huge \\textbf')) this.latexCode = this.latexCode.replace(/(\\huge \\textbf\{)([^}]+)(\})/, `$1${newVal}$3`);
                        if (this.latexCode.includes('\\newcommand{\\name}')) this.latexCode = this.latexCode.replace(/(\\newcommand\{\\name\}\{)([^}]+)(\})/, `$1${newVal}$3`);
                    } else if (type === 'role') {
                        this.latexCode = this.latexCode.replace(/(\\small\s+)([^}]+)(\})/, `$1${newVal}$3`);
                    }
                },

                syncSectionToLatex(e, sIdx) {
                    const newVal = e.target.innerText.trim();
                    const sections = this.latexCode.match(/\\section\{([^}]+)\}([\s\S]*?)(?=\\section|\\end\{document\})/g);
                    if (sections && sections[sIdx]) {
                        if (sections[sIdx].includes('Summary')) this.latexCode = this.latexCode.replace(/(\\section\{[^}]*Summary\})(?:\s*)([\s\S]*?)(?=\\section|\\end\{document\})/, `$1\n\n${newVal}\n\n`);
                    }
                },

                syncItemToLatex(e, sIdx, iIdx) {
                    const newVal = e.target.innerText.trim();
                    const sections = this.latexCode.match(/\\section\{([^}]+)\}([\s\S]*?)(?=\\section|\\end\{document\})/g);
                    if (sections && sections[sIdx]) {
                        const items = sections[sIdx].match(/\\item\s+([\s\S]*?)(?=\\item|\\end\{itemize\})/g);
                        if (items && items[iIdx]) this.latexCode = this.latexCode.replace(items[iIdx], `\\item ${newVal} `);
                    }
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
