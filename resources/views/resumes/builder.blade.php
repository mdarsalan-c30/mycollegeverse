<x-app-layout>
    @section('title', 'Resume Builder | Professional Profiles')
    
    <div class="min-h-screen bg-slate-50 py-12" x-data="resumeBuilder()">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase">Resume <span class="text-primary">Builder</span></h1>
                    <p class="text-slate-500 font-bold mt-1 uppercase text-xs tracking-widest">Create a professional resume in minutes.</p>
                </div>
                <div class="flex items-center gap-4">
                    <button @click="saveResume" class="bg-primary text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-primary-dark transition-all shadow-xl flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                        Save Resume
                    </button>
                </div>
            </div>

            <!-- Role Selection (Initial State) -->
            <div x-show="!roleSelected" class="bg-white rounded-[2.5rem] p-12 text-center border border-slate-100 shadow-xl mb-12">
                <h2 class="text-3xl font-black text-slate-800 mb-4">Choose Your Career Path</h2>
                <p class="text-slate-500 font-bold mb-10">We'll auto-fill some suggestions based on your target role.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($roleTemplates as $role => $data)
                    <button @click="selectRole('{{ $role }}')" class="p-8 bg-slate-50 rounded-3xl border-2 border-transparent hover:border-primary hover:bg-primary/5 transition-all group">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-sm">
                            <span class="text-3xl">
                                @if($role === 'SDE') 💻 @elseif($role === 'Frontend') 🎨 @elseif($role === 'QA') 🛡️ @else 📊 @endif
                            </span>
                        </div>
                        <h3 class="font-black text-slate-800 uppercase tracking-tight">{{ $role }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold mt-2">Engineer / Developer</p>
                    </button>
                    @endforeach
                </div>
                <div class="mt-10">
                    <button @click="roleSelected = true" class="text-slate-400 font-bold hover:text-primary transition-colors underline">Skip, I'll start from scratch</button>
                </div>
            </div>

            <div x-show="roleSelected" class="grid grid-cols-1 lg:grid-cols-2 gap-12">
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
                            <h2 class="text-2xl font-black text-slate-800 uppercase italic">Basic <span class="text-primary">Details</span></h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Full Name</label>
                                    <input type="text" x-model="resume.personal.name" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Target Role</label>
                                    <input type="text" x-model="resume.personal.role" placeholder="e.g. Frontend Developer" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Email Address</label>
                                    <input type="email" x-model="resume.personal.email" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Phone</label>
                                    <input type="text" x-model="resume.personal.phone" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 focus:ring-2 focus:ring-primary/20">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Professional Summary</label>
                                <textarea x-model="resume.personal.summary" rows="4" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700 focus:ring-2 focus:ring-primary/20"></textarea>
                            </div>
                        </div>

                        <!-- Step 1: Projects -->
                        <div x-show="currentStep === 1" class="space-y-8 animate-fade-in">
                            <div class="flex justify-between items-center">
                                <h2 class="text-2xl font-black text-slate-800 uppercase italic">Key <span class="text-primary">Projects</span></h2>
                                <button @click="addProject" class="text-primary font-black text-[10px] uppercase tracking-widest hover:underline">+ Add Project</button>
                            </div>

                            <div class="space-y-6">
                                <template x-for="(proj, index) in resume.projects" :key="index">
                                    <div class="bg-slate-50 p-6 rounded-3xl relative group border border-transparent hover:border-slate-200 transition-all">
                                        <button @click="removeProject(index)" class="absolute -top-2 -right-2 bg-white text-rose-500 p-1.5 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <input type="text" x-model="proj.title" placeholder="Project Title" class="bg-transparent border-none font-black text-slate-800 p-0 focus:ring-0">
                                            <input type="text" x-model="proj.link" placeholder="Link (GitHub/Live)" class="bg-transparent border-none font-bold text-primary text-xs p-0 focus:ring-0">
                                        </div>
                                        <textarea x-model="proj.description" placeholder="Short description..." rows="2" class="w-full bg-white border-none rounded-xl p-3 text-xs font-medium focus:ring-1 focus:ring-primary/10"></textarea>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Step 2: Skills -->
                        <div x-show="currentStep === 2" class="space-y-8 animate-fade-in">
                            <h2 class="text-2xl font-black text-slate-800 uppercase italic">Core <span class="text-primary">Skills</span></h2>
                            <div class="space-y-4">
                                <div class="flex gap-2">
                                    <input type="text" x-model="newSkill" @keydown.enter.prevent="addSkill" placeholder="e.g. React, SQL" class="flex-1 bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-700">
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

                        <!-- Step 3: Education -->
                        <div x-show="currentStep === 3" class="space-y-8 animate-fade-in">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-black text-slate-800 uppercase italic">Academic <span class="text-primary">Background</span></h2>
                                <button @click="addEducation" class="text-primary font-black text-[10px] uppercase tracking-widest">+ Add Edu</button>
                            </div>
                            <div class="space-y-4">
                                <template x-for="(edu, index) in resume.education" :key="index">
                                    <div class="bg-slate-50 p-6 rounded-3xl border border-transparent hover:border-slate-200">
                                        <input type="text" x-model="edu.institution" placeholder="College Name" class="w-full bg-transparent border-none font-black text-slate-800 p-0 mb-1">
                                        <div class="grid grid-cols-2 gap-4">
                                            <input type="text" x-model="edu.degree" placeholder="Degree" class="bg-transparent border-none font-bold text-slate-500 text-xs p-0">
                                            <input type="text" x-model="edu.year" placeholder="Year" class="bg-transparent border-none font-bold text-slate-500 text-xs p-0 text-right">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Step 4: Template Selector -->
                        <div x-show="currentStep === 4" class="space-y-8 animate-fade-in">
                            <h2 class="text-2xl font-black text-slate-800 uppercase italic">Choose <span class="text-primary">Template</span></h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <button @click="resume.template_id = 'ats-clean'" 
                                        :class="resume.template_id === 'ats-clean' ? 'border-primary ring-4 ring-primary/10' : 'border-slate-100'"
                                        class="p-6 bg-white rounded-3xl border-2 text-left transition-all">
                                    <h3 class="font-black text-slate-800 uppercase text-sm">ATS Clean</h3>
                                    <p class="text-[10px] text-slate-400 font-bold mt-1">Simple, high-conversion template.</p>
                                </button>
                                <button @click="resume.template_id = 'latex-classic'" 
                                        :class="resume.template_id === 'latex-classic' ? 'border-primary ring-4 ring-primary/10' : 'border-slate-100'"
                                        class="p-6 bg-white rounded-3xl border-2 text-left transition-all">
                                    <h3 class="font-black text-slate-800 uppercase text-sm">LaTeX Classic</h3>
                                    <p class="text-[10px] text-slate-400 font-bold mt-1">Academic & Professional (Black/White).</p>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Side (Minimal & Clean) -->
                <div class="hidden lg:block sticky top-32 h-[calc(100vh-200px)] overflow-y-auto custom-scrollbar bg-white rounded-[2.5rem] shadow-2xl border border-slate-200 p-8">
                    <div class="flex justify-between items-center mb-8">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Live Preview</span>
                        <div class="flex gap-2">
                            <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                        </div>
                    </div>

                    <!-- Resume Live Sheet -->
                    <div id="resume-preview" class="bg-white text-slate-900 font-sans p-4 border border-slate-50 min-h-full">
                        <div class="border-b-2 border-slate-900 pb-6 mb-6">
                            <h1 class="text-3xl font-black uppercase tracking-tighter mb-2" x-text="resume.personal.name || 'YOUR NAME'"></h1>
                            <p class="text-sm font-bold text-primary uppercase mb-4" x-text="resume.personal.role"></p>
                            <div class="flex flex-wrap gap-4 text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                                <span x-text="resume.personal.email"></span>
                                <span x-text="resume.personal.phone"></span>
                            </div>
                        </div>

                        <div class="mb-8" x-show="resume.personal.summary">
                            <p class="text-xs leading-relaxed font-medium" x-text="resume.personal.summary"></p>
                        </div>

                        <div class="mb-8" x-show="resume.projects.length > 0">
                            <h4 class="text-[10px] font-black uppercase text-slate-400 mb-4 tracking-[0.2em]">Projects</h4>
                            <div class="space-y-4">
                                <template x-for="proj in resume.projects">
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs font-black uppercase" x-text="proj.title"></span>
                                        </div>
                                        <p class="text-[10px] leading-relaxed text-slate-600" x-text="proj.description"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="mb-8" x-show="resume.skills.length > 0">
                            <h4 class="text-[10px] font-black uppercase text-slate-400 mb-3 tracking-[0.2em]">Skills</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="skill in resume.skills">
                                    <span class="bg-slate-50 px-3 py-1 rounded text-[9px] font-black text-slate-600 uppercase border border-slate-100" x-text="skill"></span>
                                </template>
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
                roleSelected: false,
                steps: ['Basic Details', 'Projects', 'Skills', 'Education', 'Choose Template'],
                roleTemplates: @json($roleTemplates),
                newSkill: '',
                resume: @json($initialData),

                init() {
                    this.resume.template_id = 'ats-clean';
                },

                selectRole(role) {
                    const template = this.roleTemplates[role];
                    this.resume.personal.role = role;
                    this.resume.personal.summary = template.summary;
                    this.resume.skills = [...new Set([...this.resume.skills, ...template.skills])];
                    this.roleSelected = true;
                },

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
                removeProject(index) {
                    this.resume.projects.splice(index, 1);
                },
                addEducation() {
                    this.resume.education.push({ institution: '', degree: '', year: '' });
                },

                async saveResume() {
                    const title = prompt("Name your resume:", "My Professional Resume");
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
                                template_id: this.resume.template_id
                            })
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            window.location.href = data.redirect;
                        }
                    } catch (e) {
                        alert("Error saving resume.");
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
