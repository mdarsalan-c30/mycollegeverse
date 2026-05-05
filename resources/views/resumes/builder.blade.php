<x-app-layout>
    @section('title', 'Resume Forge | Student Career OS')
    
    <div class="min-h-screen bg-slate-50 py-12" x-data="resumeBuilder()">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase italic">Identity <span class="text-primary">Forge</span></h1>
                    <p class="text-slate-500 font-bold mt-1 uppercase text-xs tracking-widest">Manifesting your professional proof-of-work.</p>
                </div>
                <div class="flex items-center gap-4">
                    <button @click="saveResume" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-black transition-all shadow-xl flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                        Manifest Identity
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Editor Side -->
                <div class="space-y-8">
                    <!-- Step Indicator -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-4 custom-scrollbar">
                        <template x-for="(step, index) in steps" :key="index">
                            <button @click="currentStep = index" 
                                    :class="currentStep === index ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white text-slate-400 hover:text-slate-600'"
                                    class="px-6 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all shrink-0 border border-slate-100">
                                <span x-text="step"></span>
                            </button>
                        </template>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-sm border border-slate-100 min-h-[600px]">
                        <!-- Step 0: Personal -->
                        <div x-show="currentStep === 0" class="space-y-8 animate-fade-in">
                            <h2 class="text-2xl font-black text-slate-800 uppercase italic">Base Protocol <span class="text-primary">/ Info</span></h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Full Name</label>
                                    <input type="text" x-model="resume.personal.name" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Email Address</label>
                                    <input type="email" x-model="resume.personal.email" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Phone Signal</label>
                                    <input type="text" x-model="resume.personal.phone" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Location</label>
                                    <input type="text" x-model="resume.personal.location" placeholder="e.g. Noida, India" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Professional Manifesto (Summary)</label>
                                <textarea x-model="resume.personal.summary" rows="4" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 focus:ring-2 focus:ring-primary/20"></textarea>
                            </div>
                        </div>

                        <!-- Step 1: Proof of Work (Projects) -->
                        <div x-show="currentStep === 1" class="space-y-8 animate-fade-in">
                            <div class="flex justify-between items-center">
                                <h2 class="text-2xl font-black text-slate-800 uppercase italic">Proof of <span class="text-primary">Work</span></h2>
                                <button @click="addProject" class="text-primary font-black text-[10px] uppercase tracking-widest hover:underline">+ Add Manually</button>
                            </div>

                            @if($existingProjects->count() > 0)
                            <div class="bg-primary/5 p-6 rounded-3xl border border-primary/10 mb-8">
                                <label class="text-[10px] font-black uppercase tracking-widest text-primary mb-4 block">Manifest from Verse Library</label>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($existingProjects as $p)
                                    <button @click="importProject({{ $p->toJson() }})" class="bg-white border border-slate-200 px-4 py-2 rounded-xl text-xs font-bold hover:border-primary hover:text-primary transition-all">
                                        + {{ $p->title }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="space-y-6">
                                <template x-for="(proj, index) in resume.projects" :key="index">
                                    <div class="bg-slate-50 p-6 rounded-3xl relative group border border-transparent hover:border-slate-200 transition-all">
                                        <button @click="removeProject(index)" class="absolute -top-2 -right-2 bg-white text-rose-500 p-1.5 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <input type="text" x-model="proj.title" placeholder="Project Title" class="bg-transparent border-none font-black text-slate-800 p-0 focus:ring-0">
                                            <input type="text" x-model="proj.link" placeholder="Live Link (GitHub/Demo)" class="bg-transparent border-none font-bold text-primary text-xs p-0 focus:ring-0">
                                        </div>
                                        <textarea x-model="proj.description" placeholder="What did you manifest?" rows="2" class="w-full bg-white border-none rounded-xl p-3 text-xs font-medium focus:ring-1 focus:ring-primary/10"></textarea>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Step 2: Knowledge Stack (Skills) -->
                        <div x-show="currentStep === 2" class="space-y-8 animate-fade-in">
                            <h2 class="text-2xl font-black text-slate-800 uppercase italic">Knowledge <span class="text-primary">Stack</span></h2>
                            <div class="space-y-4">
                                <div class="flex gap-2">
                                    <input type="text" x-model="newSkill" @keydown.enter.prevent="addSkill" placeholder="Enter skill (e.g. Laravel, React)" class="flex-1 bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700">
                                    <button @click="addSkill" class="bg-primary text-white px-6 rounded-2xl font-black uppercase text-[10px]">Add</button>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(skill, index) in resume.skills" :key="index">
                                        <span class="bg-slate-100 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 flex items-center gap-2 group">
                                            <span x-text="skill"></span>
                                            <button @click="removeSkill(index)" class="text-slate-400 hover:text-rose-500">×</button>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Education & Experience -->
                        <div x-show="currentStep === 3" class="space-y-8 animate-fade-in">
                            <div>
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Academic Nodes</h3>
                                    <button @click="addEducation" class="text-primary font-black text-[10px] uppercase tracking-widest">+ Add Edu</button>
                                </div>
                                <div class="space-y-4">
                                    <template x-for="(edu, index) in resume.education" :key="index">
                                        <div class="bg-slate-50 p-6 rounded-3xl border border-transparent hover:border-slate-200">
                                            <input type="text" x-model="edu.institution" placeholder="College/University" class="w-full bg-transparent border-none font-black text-slate-800 p-0 mb-1">
                                            <div class="grid grid-cols-2 gap-4">
                                                <input type="text" x-model="edu.degree" placeholder="Degree/Course" class="bg-transparent border-none font-bold text-slate-500 text-xs p-0">
                                                <input type="text" x-model="edu.year" placeholder="Year" class="bg-transparent border-none font-bold text-slate-500 text-xs p-0 text-right">
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="pt-8 border-t border-slate-100">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Work Experience</h3>
                                    <button @click="addExperience" class="text-primary font-black text-[10px] uppercase tracking-widest">+ Add Exp</button>
                                </div>
                                <div class="space-y-4">
                                    <template x-for="(exp, index) in resume.experience" :key="index">
                                        <div class="bg-slate-50 p-6 rounded-3xl border border-transparent hover:border-slate-200">
                                            <input type="text" x-model="exp.company" placeholder="Company/Organization" class="w-full bg-transparent border-none font-black text-slate-800 p-0 mb-1">
                                            <div class="grid grid-cols-2 gap-4">
                                                <input type="text" x-model="exp.role" placeholder="Your Role" class="bg-transparent border-none font-bold text-slate-500 text-xs p-0">
                                                <input type="text" x-model="exp.duration" placeholder="Duration" class="bg-transparent border-none font-bold text-slate-500 text-xs p-0 text-right">
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: AI Roast & Score -->
                        <div x-show="currentStep === 4" class="space-y-8 animate-fade-in text-center">
                            <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="text-4xl animate-pulse">🔥</span>
                            </div>
                            <h2 class="text-2xl font-black text-slate-800 uppercase italic">AI Review <span class="text-primary">/ Roast</span></h2>
                            <p class="text-slate-500 font-bold max-w-sm mx-auto">Get brutally honest feedback from the Multiverse AI to refine your manifesto.</p>
                            
                            <div class="flex flex-col gap-4 mt-8">
                                <button @click="roastResume" :disabled="isRoasting" class="w-full h-16 bg-rose-600 text-white rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-rose-500/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50">
                                    <span x-text="isRoasting ? 'Igniting Roast...' : 'Ignite AI Roast 🔥'"></span>
                                </button>
                                <button @click="improveResume" :disabled="isRoasting" class="w-full h-16 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-black transition-all disabled:opacity-50">
                                    Get Actionable Feedback 📈
                                </button>
                            </div>

                            <div x-show="aiFeedback" x-transition class="mt-12 bg-slate-900 p-8 rounded-[2rem] text-left relative overflow-hidden">
                                <div class="absolute top-0 right-0 p-4">
                                    <span class="text-xs font-black text-rose-500 uppercase tracking-widest animate-pulse">AI Transmission</span>
                                </div>
                                <p class="text-slate-300 font-mono text-sm leading-relaxed" x-text="aiFeedback"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Side (High Fidelity) -->
                <div class="hidden lg:block sticky top-32 h-[calc(100vh-200px)] overflow-y-auto custom-scrollbar bg-white rounded-[2.5rem] shadow-2xl border border-slate-200 p-8">
                    <div class="flex justify-between items-center mb-8">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Live Manifestation Preview</span>
                        <div class="flex gap-2">
                            <div class="w-2 h-2 rounded-full bg-red-400"></div>
                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                        </div>
                    </div>

                    <!-- Resume Live Sheet -->
                    <div id="resume-preview" class="bg-white text-slate-900 font-sans p-4 border border-slate-50 min-h-full">
                        <!-- Header -->
                        <div class="border-b-2 border-slate-900 pb-6 mb-6">
                            <h1 class="text-3xl font-black uppercase tracking-tighter mb-2" x-text="resume.personal.name || 'YOUR IDENTITY'"></h1>
                            <div class="flex flex-wrap gap-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                <span x-text="resume.personal.email"></span>
                                <span x-text="resume.personal.phone"></span>
                                <span x-text="resume.personal.location"></span>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="mb-8" x-show="resume.personal.summary">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-2">Manifesto</h4>
                            <p class="text-xs leading-relaxed font-medium" x-text="resume.personal.summary"></p>
                        </div>

                        <!-- Projects -->
                        <div class="mb-8" x-show="resume.projects.length > 0">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-4 italic">Proof of Work</h4>
                            <div class="space-y-4">
                                <template x-for="proj in resume.projects">
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm font-black uppercase" x-text="proj.title"></span>
                                            <span class="text-[9px] font-bold text-slate-400" x-text="proj.link"></span>
                                        </div>
                                        <p class="text-[11px] leading-relaxed text-slate-600" x-text="proj.description"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Experience -->
                        <div class="mb-8" x-show="resume.experience.length > 0">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-4 italic">Verse Contribution (Experience)</h4>
                            <div class="space-y-4">
                                <template x-for="exp in resume.experience">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="text-sm font-black uppercase" x-text="exp.role"></p>
                                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tight" x-text="exp.company"></p>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-400" x-text="exp.duration"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Skills -->
                        <div class="mb-8" x-show="resume.skills.length > 0">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-3 italic">Knowledge Stack</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="skill in resume.skills">
                                    <span class="bg-slate-50 px-3 py-1 rounded text-[10px] font-black text-slate-600 uppercase border border-slate-100" x-text="skill"></span>
                                </template>
                            </div>
                        </div>

                        <!-- Education -->
                        <div class="mb-8" x-show="resume.education.length > 0">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-4 italic">Academic Protocols</h4>
                            <div class="space-y-4">
                                <template x-for="edu in resume.education">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="text-sm font-black uppercase" x-text="edu.degree"></p>
                                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tight" x-text="edu.institution"></p>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-400" x-text="edu.year"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Verification Footer -->
                        <div class="mt-12 pt-8 border-t border-slate-100 flex justify-between items-end">
                            <div class="space-y-1">
                                <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest">MyCollegeVerse • Student Career OS</p>
                                <p class="text-[8px] font-bold text-slate-200 italic uppercase">Authenticated Manifest ID: MCV-7781-RESUME</p>
                            </div>
                            <div class="w-12 h-12 bg-slate-50 rounded flex items-center justify-center grayscale opacity-20">
                                <span class="text-2xl">⚡</span>
                            </div>
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
                currentStep: 0,
                steps: ['Personal', 'Proof of Work', 'Knowledge Stack', 'Academic Nodes', 'AI Review'],
                isRoasting: false,
                aiFeedback: '',
                newSkill: '',
                resume: @json($initialData),

                addSkill() {
                    if (this.newSkill.trim()) {
                        this.resume.skills.push(this.newSkill.trim());
                        this.newSkill = '';
                    }
                },
                removeSkill(index) {
                    this.resume.skills.splice(index, 1);
                },
                addProject() {
                    this.resume.projects.push({ title: '', link: '', description: '' });
                },
                importProject(proj) {
                    this.resume.projects.push({
                        title: proj.title,
                        link: proj.live_url || proj.github_url || '',
                        description: proj.description || ''
                    });
                },
                removeProject(index) {
                    this.resume.projects.splice(index, 1);
                },
                addEducation() {
                    this.resume.education.push({ institution: '', degree: '', year: '', description: '' });
                },
                addExperience() {
                    this.resume.experience.push({ company: '', role: '', duration: '', description: '' });
                },
                async roastResume() {
                    this.isRoasting = true;
                    try {
                        const res = await fetch('{{ route('resumes.ai-review') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ resume: this.resume, type: 'roast' })
                        });
                        const data = await res.json();
                        this.aiFeedback = data.feedback;
                        this.currentStep = 4;
                    } catch (e) {
                        this.aiFeedback = "AI connection failed. The Verse is quiet.";
                    }
                    this.isRoasting = false;
                },
                async improveResume() {
                    this.isRoasting = true;
                    try {
                        const res = await fetch('{{ route('resumes.ai-review') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ resume: this.resume, type: 'review' })
                        });
                        const data = await res.json();
                        this.aiFeedback = data.feedback;
                        this.currentStep = 4;
                    } catch (e) {
                        this.aiFeedback = "AI connection failed.";
                    }
                    this.isRoasting = false;
                },
                async saveResume() {
                    const title = prompt("Enter a title for this Identity Manifesto:", "My Professional Resume");
                    if (!title) return;

                    try {
                        const res = await fetch('{{ route('resumes.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                title: title,
                                data: this.resume,
                                template_id: 'ats-clean'
                            })
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            window.location.href = data.redirect;
                        }
                    } catch (e) {
                        alert("Manifestation failed. Check your data link.");
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
