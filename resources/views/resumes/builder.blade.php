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
                        <p class="text-slate-400 font-bold text-sm leading-relaxed">Fast, role-based auto-fill and professional templates.</p>
                    </button>
                    <button @click="mode = 'latex'" class="bg-slate-900 p-10 rounded-[3rem] shadow-xl group text-left">
                        <h3 class="text-2xl font-black text-white uppercase italic mb-2">LaTeX Expert</h3>
                        <p class="text-slate-400 font-bold text-sm leading-relaxed">Full control via LaTeX code. Real-time preview and raw code management.</p>
                    </button>
                </div>
            </div>

            <!-- LATEX EXPERT MODE -->
            <div x-show="mode === 'latex'" style="display: none;" class="animate-fade-in h-[85vh]">
                <div class="flex items-center justify-between mb-8">
                    <button @click="mode = null" class="text-slate-400 font-black uppercase text-[10px] tracking-widest flex items-center gap-2 hover:text-primary transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg> Back</button>
                    <div class="flex items-center gap-4">
                        <button @click="recompile" class="bg-slate-800 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl">Recompile Preview</button>
                        <button @click="saveResume" class="bg-primary text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl hover:bg-primary-dark transition-all">Save & Manifest</button>
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
        </div>
    </div>

    @push('scripts')
    <script>
        function resumeBuilder() {
            return {
                mode: null, currentStep: 0, roleSelected: false, steps: ['Basic Details', 'Projects', 'Skills'],
                roleTemplates: @json($roleTemplates), resume: @json($initialData), latexCode: @json($defaultLatex),
                init() { setTimeout(() => this.recompile(), 500); },
                
                recompile() {
                    let code = this.latexCode;
                    code = code.replace(/%.*$/gm, ''); // Strip comments

                    // Variables (\newcommand)
                    const vars = {};
                    const newCommandRegex = /\\newcommand\{\\([^}]+)\}\{([^}]+)\}/g;
                    let match;
                    while ((match = newCommandRegex.exec(code)) !== null) { vars[match[1]] = match[2]; }
                    Object.keys(vars).forEach(key => { code = code.replace(new RegExp('\\\\' + key, 'g'), vars[key]); });

                    let html = '<div class="text-slate-900 space-y-4">';
                    
                    // Header
                    const name = code.match(/\\huge \\textbf\{([^}]+)\}/)?.[1] || vars['name'] || "Name";
                    const role = code.match(/\\small ([^}]+)\}/)?.[1] || "Professional Profile";
                    html += `<div class="flex justify-between items-start mb-6 border-b-2 border-black pb-4">
                        <div><h1 class="text-3xl font-bold tracking-tight">${name}</h1><p class="text-xs font-bold text-slate-700 uppercase">${role}</p></div>
                        <div class="text-right text-[10px] space-y-0.5 font-medium">`;
                    if (code.match(/([^\\n\r\t{}&]+, India)/)) html += `<p>${code.match(/([^\\n\r\t{}&]+, India)/)[1].trim()}</p>`;
                    if (vars['email']) html += `<p><a href="mailto:${vars['email']}" class="text-blue-700 underline">${vars['email']}</a></p>`;
                    if (vars['phone']) html += `<p>+91-${vars['phone']}</p>`;
                    if (code.match(/LinkedIn/)) html += `<p><a href="#" class="text-blue-700 underline font-bold">LinkedIn</a></p>`;
                    html += `</div></div>`;

                    // Sections
                    const sections = code.match(/\\section\{([^}]+)\}([\s\S]*?)(?=\\section|\\end\{document\})/g);
                    if (sections) {
                        sections.forEach(sec => {
                            const title = sec.match(/\\section\{([^}]+)\}/)[1];
                            let content = sec.replace(/\\section\{[^}]+\}/, '').trim();
                            
                            // Cleanup structural junk but keep text
                            content = content.replace(/\\begin\{(tabular|tabularx)\}[\s\S]*?\\end\{\1\}/g, ' ');
                            
                            // Subheadings
                            content = content.replace(/\\resumeSubheading\s*\{([^}]+)\}\s*\{([^}]+)\}/g, '<div class="flex justify-between font-bold text-[12px] mt-1"><span>$1</span><span class="text-right">$2</span></div>');
                            
                            // Bold conversion (IMPORTANT: Before itemize)
                            content = content.replace(/\\textbf\{([^}]+)\}/g, '<strong>$1</strong>');

                            let sectionHtml = `<div><h3 class="text-[12.5px] font-bold uppercase border-b border-black pb-0.5 mb-1 tracking-wider">${title}</h3>`;

                            // Improved Itemize Extraction (Handles nested bold tags)
                            if (content.includes('\\item')) {
                                const itemMatches = content.match(/\\item\s+([\s\S]*?)(?=\\item|\\end\{itemize\})/g);
                                if (itemMatches) {
                                    sectionHtml += `<ul class="list-disc ml-5 mt-1 space-y-0.5">`;
                                    itemMatches.forEach(i => {
                                        let itemText = i.replace('\\item ', '').trim();
                                        // Specific cleanup while preserving <strong>
                                        itemText = itemText.replace(/\\begin\{itemize\}[^]*?\\end\{itemize\}/g, '');
                                        itemText = itemText.replace(/\\\\[a-zA-Z]+|[{}]|&|\\\\/g, (m) => (m === '<strong>' || m === '</strong>') ? m : ' ');
                                        // Final clean for stray backslashes
                                        itemText = itemText.replace(/[\\{}]/g, '');
                                        sectionHtml += `<li class="text-[11px] leading-snug">${itemText.trim()}</li>`;
                                    });
                                    sectionHtml += `</ul>`;
                                }
                            } else {
                                sectionHtml += `<div class="text-[11px] text-justify leading-snug mt-1">${content.replace(/[\\{}&]|\\\\/g, ' ').trim()}</div>`;
                            }
                            
                            sectionHtml += `</div>`;
                            html += sectionHtml;
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
