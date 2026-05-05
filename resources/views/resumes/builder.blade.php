@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50" x-data="resumeBuilder()">
    <!-- Top Navigation Hub -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('resumes.index') }}" class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-lg font-bold text-slate-800 tracking-tight">Career OS <span class="text-primary">/ Resume Builder</span></h1>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="flex bg-slate-100 p-1 rounded-lg mr-4">
                    <button @click="viewMode = 'edit'" :class="viewMode === 'edit' ? 'bg-white shadow-sm text-primary' : 'text-slate-500'" class="px-4 py-1.5 rounded-md text-sm font-bold transition-all">Edit Mode</button>
                    <button @click="viewMode = 'preview'" :class="viewMode === 'preview' ? 'bg-white shadow-sm text-primary' : 'text-slate-500'" class="px-4 py-1.5 rounded-md text-sm font-bold transition-all lg:hidden">Preview</button>
                </div>

                <button @click="saveResume()" class="bg-primary text-white px-6 py-2 rounded-xl font-black text-sm uppercase tracking-widest hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Manifest Resume
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-[1600px] mx-auto flex flex-col lg:flex-row h-[calc(100vh-64px)]">
        <!-- Editor Sidebar (Left) -->
        <aside class="w-full lg:w-[450px] bg-white border-r border-slate-200 overflow-y-auto custom-scrollbar p-6" x-show="viewMode === 'edit'">
            <div class="space-y-10">
                <!-- Section: Personal Intelligence -->
                <section>
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <h2 class="font-black text-slate-800 uppercase tracking-widest text-xs">Personal Identity</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Full Name</label>
                            <input type="text" x-model="resume.personal.name" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-primary/20 text-sm font-bold text-slate-700">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Professional Role</label>
                                <input type="text" x-model="resume.personal.role" placeholder="e.g. Frontend Dev" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-primary/20 text-sm font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Location</label>
                                <input type="text" x-model="resume.personal.location" placeholder="e.g. Noida, IN" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-primary/20 text-sm font-bold text-slate-700">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Bio / Summary</label>
                            <textarea x-model="resume.personal.summary" rows="3" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-primary/20 text-sm font-bold text-slate-700 resize-none"></textarea>
                        </div>
                    </div>
                </section>

                <hr class="border-slate-100">

                <!-- Section: Proof-of-Work (Projects) -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                            </div>
                            <h2 class="font-black text-slate-800 uppercase tracking-widest text-xs">Proof of Work</h2>
                        </div>
                        <button @click="addProject()" class="text-xs font-black text-primary uppercase tracking-wider">+ Add Link</button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(proj, index) in resume.projects" :key="index">
                            <div class="bg-slate-50 p-4 rounded-xl relative group">
                                <button @click="removeProject(index)" class="absolute -top-2 -right-2 w-6 h-6 bg-rose-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">×</button>
                                <div class="space-y-3">
                                    <input type="text" x-model="proj.title" placeholder="Project Name" class="w-full bg-transparent border-b border-slate-200 focus:border-primary px-0 py-1 text-sm font-bold text-slate-700">
                                    <input type="text" x-model="proj.link" placeholder="GitHub / Live Link" class="w-full bg-transparent border-b border-slate-200 focus:border-primary px-0 py-1 text-xs font-medium text-slate-500">
                                    <textarea x-model="proj.description" placeholder="Impact (Action verbs...)" class="w-full bg-transparent border-b border-slate-200 focus:border-primary px-0 py-1 text-xs font-medium text-slate-600 resize-none"></textarea>
                                </div>
                            </div>
                        </template>
                        
                        @if(count($existingProjects) > 0)
                        <div class="mt-4 p-4 border-2 border-dashed border-slate-200 rounded-xl">
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 text-center">Import from Verse Portfolio</label>
                            <select @change="importProject($event.target.value); $event.target.value=''" class="w-full bg-white border-slate-200 rounded-lg text-xs font-bold text-slate-600">
                                <option value="">Select a project...</option>
                                @foreach($existingProjects as $p)
                                <option value="{{ json_encode(['title' => $p->title, 'link' => $p->github_link ?? $p->live_link, 'description' => $p->short_description]) }}">{{ $p->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </section>

                <hr class="border-slate-100">

                <!-- Section: Skill Grid -->
                <section>
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <h2 class="font-black text-slate-800 uppercase tracking-widest text-xs">Knowledge Stack</h2>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <template x-for="(skill, index) in resume.skills" :key="index">
                            <div class="bg-slate-100 px-3 py-1.5 rounded-lg flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-600" x-text="skill"></span>
                                <button @click="removeSkill(index)" class="text-slate-400 hover:text-rose-500">×</button>
                            </div>
                        </template>
                        <input type="text" @keydown.enter.prevent="addSkill($event.target.value); $event.target.value=''" placeholder="Add skill..." class="bg-transparent border-0 focus:ring-0 text-xs font-bold text-primary w-24">
                    </div>
                </section>

                <hr class="border-slate-100">

                <!-- Section: AI Roast & Review -->
                <section class="bg-slate-900 rounded-2xl p-6 text-white overflow-hidden relative group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/20 blur-2xl group-hover:bg-primary/40 transition-all"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-xl">🔥</span>
                            <h3 class="font-black uppercase tracking-widest text-[10px]">AI Roast Mode</h3>
                        </div>
                        <p class="text-xs text-slate-400 mb-6 leading-relaxed">Let the AI brutally critique your resume to find weak spots and action-verb gaps.</p>
                        <button @click="roastResume()" :disabled="isRoasting" class="w-full bg-white text-slate-900 py-3 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-primary hover:text-white transition-all disabled:opacity-50">
                            <span x-show="!isRoasting">Ignite Roast</span>
                            <span x-show="isRoasting">Calibrating Roast...</span>
                        </button>
                    </div>
                </section>
            </div>
        </aside>

        <!-- Live Preview (Right) -->
        <section class="flex-1 bg-slate-200 overflow-y-auto p-4 lg:p-12" :class="viewMode === 'preview' ? 'block' : 'hidden lg:block'">
            <div class="max-w-[800px] mx-auto bg-white shadow-2xl min-h-[1100px] p-12 transition-all" id="resume-canvas">
                <!-- Dynamic Template Component -->
                <div class="space-y-8">
                    <!-- Personal Info -->
                    <div class="text-center">
                        <h1 class="text-4xl font-black text-slate-900 uppercase tracking-tighter" x-text="resume.personal.name || 'Your Name'"></h1>
                        <p class="text-lg font-bold text-primary mt-1" x-text="resume.personal.role || 'Professional Role'"></p>
                        <div class="flex items-center justify-center gap-4 mt-4 text-xs font-bold text-slate-500 uppercase tracking-widest">
                            <span x-text="resume.personal.email"></span>
                            <span x-show="resume.personal.phone" class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            <span x-text="resume.personal.phone"></span>
                            <span x-show="resume.personal.location" class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            <span x-text="resume.personal.location"></span>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div x-show="resume.personal.summary">
                        <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] border-b-2 border-primary/10 pb-1 mb-4">Professional Identity</h3>
                        <p class="text-sm text-slate-600 leading-relaxed font-medium" x-text="resume.personal.summary"></p>
                    </div>

                    <!-- Skills -->
                    <div x-show="resume.skills.length > 0">
                        <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] border-b-2 border-primary/10 pb-1 mb-4">Knowledge Stack</h3>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="skill in resume.skills">
                                <span class="bg-slate-50 border border-slate-200 px-3 py-1 rounded text-[11px] font-black text-slate-700" x-text="skill"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Projects -->
                    <div x-show="resume.projects.length > 0">
                        <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] border-b-2 border-primary/10 pb-1 mb-4">Proof of Work (Evidence)</h3>
                        <div class="space-y-6">
                            <template x-for="proj in resume.projects">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-black text-slate-800" x-text="proj.title"></h4>
                                        <span class="text-[10px] font-black text-primary uppercase tracking-wider" x-show="proj.link">Verify Link ↗</span>
                                    </div>
                                    <p class="text-xs text-slate-600 mt-2 leading-relaxed" x-text="proj.description"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Roast Overlay Modal -->
    <div x-show="showRoast" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/90 backdrop-blur-sm" x-transition>
        <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-rose-500 via-orange-500 to-rose-500"></div>
            <h2 class="text-2xl font-black text-slate-900 mb-4 flex items-center gap-2">
                <span>🔥</span> The Roast is Served
            </h2>
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 mb-6">
                <p class="text-sm text-rose-700 italic font-medium leading-relaxed" x-text="roastContent"></p>
            </div>
            <div class="flex justify-end">
                <button @click="showRoast = false" class="bg-slate-900 text-white px-6 py-2 rounded-xl font-black text-xs uppercase tracking-widest">Back to Forge</button>
            </div>
        </div>
    </div>
</div>

<script>
function resumeBuilder() {
    return {
        viewMode: 'edit',
        viewType: 'desktop',
        isRoasting: false,
        showRoast: false,
        roastContent: '',
        resume: {!! json_encode($initialData) !!},
        
        addProject() {
            this.resume.projects.push({ title: '', link: '', description: '' });
        },
        
        removeProject(index) {
            this.resume.projects.splice(index, 1);
        },
        
        importProject(data) {
            if (!data) return;
            const proj = JSON.parse(data);
            this.resume.projects.push(proj);
        },
        
        addSkill(skill) {
            if (skill && !this.resume.skills.includes(skill)) {
                this.resume.skills.push(skill);
            }
        },
        
        removeSkill(index) {
            this.resume.skills.splice(index, 1);
        },
        
        async roastResume() {
            this.isRoasting = true;
            try {
                const response = await fetch("{{ route('resumes.ai-review') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ resume: this.resume, type: 'roast' })
                });
                const data = await response.json();
                this.roastContent = data.feedback;
                this.showRoast = true;
            } catch (error) {
                console.error("Roast failed", error);
            } finally {
                this.isRoasting = false;
            }
        },
        
        async saveResume() {
            try {
                const response = await fetch("{{ route('resumes.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        title: this.resume.personal.name + "'s Resume",
                        data: this.resume
                    })
                });
                const data = await response.json();
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                }
            } catch (error) {
                console.error("Save failed", error);
            }
        }
    }
}
</script>

<style>
@media print {
    body * { visibility: hidden; }
    #resume-canvas, #resume-canvas * { visibility: visible; }
    #resume-canvas { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; padding: 0; margin: 0; }
}
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection
