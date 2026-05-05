<x-app-layout>
    @section('title', 'Professional Resume Builder | MyCollegeVerse')
    <div class="min-h-screen bg-slate-50 py-12" x-data="resumeBuilder()" x-init="init()">
        <div class="max-w-7xl mx-auto px-4">
            
            <!-- INITIAL CHOICE SCREEN -->
            <div x-show="!mode" class="max-w-4xl mx-auto text-center py-20 animate-fade-in">
                <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase mb-4">Choose Your <span class="text-primary">Building Mode</span></h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <button @click="mode = 'guided'" class="bg-white p-10 rounded-[3rem] shadow-xl group text-left">
                        <h3 class="text-2xl font-black uppercase italic mb-2 text-slate-800">Guided Builder</h3>
                        <p class="text-slate-400 font-bold text-sm leading-relaxed">Fast, role-based auto-fill.</p>
                    </button>
                    <button @click="mode = 'latex'" class="bg-slate-900 p-10 rounded-[3rem] shadow-xl group text-left">
                        <h3 class="text-2xl font-black text-white uppercase italic mb-2">LaTeX Expert</h3>
                        <p class="text-slate-400 font-bold text-sm leading-relaxed">Full control via LaTeX code.</p>
                    </button>
                </div>
            </div>

            <!-- LATEX EXPERT MODE -->
            <div x-show="mode === 'latex'" style="display: none;" class="animate-fade-in h-[85vh]">
                <div class="flex items-center justify-between mb-8">
                    <button @click="mode = null" class="text-slate-400 font-black uppercase text-[10px] tracking-widest flex items-center gap-2 hover:text-primary transition-colors">Back</button>
                    <div class="flex items-center gap-4">
                        @if(isset($resume_model))
                        <button @click="copyEditorLink" class="bg-white border-2 border-slate-900 text-slate-900 px-6 py-4 rounded-2xl font-black uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                            Copy Editor Link
                        </button>
                        @endif
                        <button @click="recompile" class="bg-slate-800 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl">Recompile Preview</button>
                        <button @click="saveResume" class="bg-primary text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl hover:bg-primary-dark transition-all">Save & Manifest</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-full pb-20">
                    <div class="bg-slate-900 rounded-[2.5rem] p-6 shadow-2xl flex flex-col">
                        <textarea x-model="latexCode" @input.debounce.500ms="recompile" class="flex-1 w-full bg-transparent border-none text-slate-300 font-mono text-xs focus:ring-0 resize-none p-4 custom-scrollbar" spellcheck="false"></textarea>
                    </div>
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-200 overflow-y-auto custom-scrollbar">
                        <div id="latex-preview-mount" class="font-serif"><!-- Preview Content --></div>
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
                currentStep: 0, roleSelected: false, steps: ['Basic Details', 'Projects', 'Skills'],
                roleTemplates: @json($roleTemplates), resume: @json($initialData), latexCode: @json($defaultLatex),
                init() { setTimeout(() => this.recompile(), 500); },

                copyEditorLink() {
                    const url = window.location.origin + '/resume/edit/{{ $resume_model->slug ?? '' }}';
                    navigator.clipboard.writeText(url).then(() => {
                        alert('🚀 Shareable Editor Link Copied!');
                    });
                },
                
                recompile() {
                    let code = this.latexCode;
                    code = code.replace(/%.*$/gm, ''); // Strip comments

                    // Variables (\newcommand)
                    const vars = {};
                    const newCommandRegex = /\\newcommand\{\\([^}]+)\}\{([^}]+)\}/g;
                    let match;
                    while ((match = newCommandRegex.exec(code)) !== null) { vars[match[1]] = match[2]; }
                    Object.keys(vars).forEach(key => { 
                        const regex = new RegExp('\\\\' + key + '(?![a-zA-Z])', 'g');
                        code = code.replace(regex, vars[key]); 
                    });

                    let html = '<div class="text-slate-900 space-y-6">';
                    
                    // Header
                    const name = vars['name'] || code.match(/\\huge \\textbf\{([^}]+)\}/)?.[1] || "Name";
                    const role = code.match(/\\small ([^}]+)\}/)?.[1] || "Professional Profile";
                    
                    html += `<div class="flex justify-between items-start mb-6 border-b-2 border-black pb-5">
                        <div>
                            <h1 contenteditable="true" @blur="syncToLatex($event, 'name')" class="text-4xl font-bold tracking-tight mb-1 outline-none focus:bg-yellow-50">${name}</h1>
                            <p contenteditable="true" @blur="syncToLatex($event, 'role')" class="text-sm font-bold text-slate-700 uppercase outline-none focus:bg-yellow-50">${role}</p>
                        </div>
                        <div class="text-right text-[10px] space-y-0.5 font-medium">`;
                    
                    if (code.match(/([^\\n\r\t{}&]+, India)/)) html += `<p>${code.match(/([^\\n\r\t{}&]+, India)/)[1].trim()}</p>`;
                    if (vars['email']) html += `<p><a href="mailto:${vars['email']}" class="text-blue-700 underline">${vars['email']}</a></p>`;
                    if (vars['phone']) html += `<p>+91-${vars['phone']}</p>`;
                    if (code.match(/LinkedIn/)) html += `<p><a href="#" class="text-blue-700 underline font-bold">LinkedIn</a></p>`;
                    html += `</div></div>`;

                    // Sections
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
                                const cleanContent = content.replace(/[\\{}&]|\\\\/g, ' ').trim();
                                sectionHtml += `<div contenteditable="true" @blur="syncSectionToLatex($event, ${sIdx})" class="text-[13.5px] text-justify leading-relaxed mt-1 outline-none focus:bg-yellow-50">${cleanContent}</div>`;
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
                        // Try \huge \textbf{...} or \newcommand{\name}{...}
                        if (this.latexCode.includes('\\huge \\textbf')) {
                            this.latexCode = this.latexCode.replace(/(\\huge \\textbf\{)([^}]+)(\})/, `$1${newVal}$3`);
                        }
                        if (this.latexCode.includes('\\newcommand{\\name}')) {
                            this.latexCode = this.latexCode.replace(/(\\newcommand\{\\name\}\{)([^}]+)(\})/, `$1${newVal}$3`);
                        }
                    } else if (type === 'role') {
                        this.latexCode = this.latexCode.replace(/(\\small\s+)([^}]+)(\})/, `$1${newVal}$3`);
                    }
                },

                syncSectionToLatex(e, sIdx) {
                    const newVal = e.target.innerText.trim();
                    const sections = this.latexCode.match(/\\section\{([^}]+)\}([\s\S]*?)(?=\\section|\\end\{document\})/g);
                    if (sections && sections[sIdx]) {
                        const oldSec = sections[sIdx];
                        const newSec = oldSec.replace(/(\}\\section\{[^}]+\}|(?:\n|^))([\s\S]+)$/, `$1${newVal}`);
                        // This is a bit complex due to regex, simpler approach:
                        // Just find the summary text if it's the summary section
                        if (oldSec.includes('Professional Summary')) {
                            this.latexCode = this.latexCode.replace(/(\\section\{Professional Summary\})(?:\s*)([\s\S]*?)(?=\\section|\\end\{document\})/, `$1\n\n${newVal}\n\n`);
                        }
                    }
                },

                syncItemToLatex(e, sIdx, iIdx) {
                    const newVal = e.target.innerText.trim();
                    // Find the section, then find the i-th \item
                    const sections = this.latexCode.match(/\\section\{([^}]+)\}([\s\S]*?)(?=\\section|\\end\{document\})/g);
                    if (sections && sections[sIdx]) {
                        const items = sections[sIdx].match(/\\item\s+([\s\S]*?)(?=\\item|\\end\{itemize\})/g);
                        if (items && items[iIdx]) {
                            const oldItem = items[iIdx];
                            const newItem = `\\item ${newVal} `;
                            this.latexCode = this.latexCode.replace(oldItem, newItem);
                        }
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
