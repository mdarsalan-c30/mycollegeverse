@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{
    {{-- Configuration --}}
    selectedCourse: '',
    selectedSemester: '',
    selectedSubject: '',
    detailLevel: 'detailed',
    topicsInput: '',
    topicsList: [],
    showStaging: false,
    {{-- Robust variable initialization --}}
    allSubjects: {{ $subjects->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'course_id' => $s->course_id, 'semester' => $s->semester])->toJson() }},

    get filteredSubjects() {
        if (!this.selectedCourse || !this.selectedSemester) return [];
        return this.allSubjects.filter(s => s.course_id == this.selectedCourse && s.semester == this.selectedSemester);
    },

    {{-- Staging Logic --}}
    prepareStaging() {
        if (!this.topicsInput.trim()) return;
        const lines = this.topicsInput.split('\n').map(l => l.trim()).filter(l => l.length > 0);
        this.topicsList = lines.map(title => ({
            title: title,
            status: 'pending',
            error: null,
            selected: true,
            noteId: null
        }));
        this.showStaging = true;
    },

    resetStaging() {
        this.showStaging = false;
        this.topicsList = [];
    },

    async generateItem(item) {
        if (item.status === 'done' || item.status === 'loading') return;
        
        item.status = 'loading';
        item.error = null;

        try {
            const response = await fetch('{{ route('admin.notes.generate.single') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    topic: item.title,
                    subject_id: this.selectedSubject,
                    detail_level: this.detailLevel
                })
            });

            const data = await response.json();

            if (data.success) {
                item.status = 'done';
                item.noteId = data.note_id;
            } else {
                item.status = 'error';
                item.error = data.error || 'Server error';
            }
        } catch (e) {
            item.status = 'error';
            item.error = 'Network or system critical failure.';
        }
    },

    async generateSelected() {
        const toProcess = this.topicsList.filter(i => i.selected && i.status === 'pending');
        for (const item of toProcess) {
            await this.generateItem(item);
        }
    }
}">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">AI Staging Area</h1>
            <p class="text-slate-500 font-medium" x-show="!showStaging">Paste topics and choose a subject to prepare for generation.</p>
            <p class="text-slate-500 font-medium" x-show="showStaging">Review your topics and generate them individually or in bulk.</p>
        </div>
        <div class="flex items-center gap-3">
             <button type="button" x-show="showStaging" @click="resetStaging()" class="bg-slate-100 text-slate-600 px-6 py-3 rounded-2xl font-bold hover:bg-slate-200 transition-all">
                Back to Edit
            </button>
            <a href="{{ route('admin.notes') }}" x-show="!showStaging" class="inline-flex items-center gap-2 bg-slate-100 text-slate-600 px-6 py-3 rounded-2xl font-bold hover:bg-slate-200 transition-all">
                Back to Hub
            </a>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-600 px-6 py-4 rounded-2xl font-bold flex items-center gap-3">
        <span class="text-xl">⚠️</span>
        {{ session('error') }}
    </div>
    @endif

    {{-- Main Interface --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- INPUT VIEW --}}
        <div class="lg:col-span-2" x-show="!showStaging" x-transition>
            <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-8">
                {{-- Step 1: Topics --}}
                <div class="space-y-3">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest px-1">Note Topics (One per line)</label>
                    <textarea x-model="topicsInput" rows="8" required
                              placeholder="e.g. Introduction to Neural Networks&#10;Backpropagation Algorithm"
                              class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 focus:ring-primary/20 focus:border-primary text-lg font-bold placeholder:text-slate-300"></textarea>
                    <p class="text-[10px] text-slate-400 font-bold italic px-1">Enter multiple topics. You can selective choice what to generate in the next step.</p>
                </div>

                {{-- Step 2: Subject Mapping --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Course Hub</label>
                        <select x-model="selectedCourse" class="w-full h-14 bg-slate-50 border-slate-100 rounded-2xl px-4 text-sm font-bold text-slate-800">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Semester Node</label>
                        <select x-model="selectedSemester" class="w-full h-14 bg-slate-50 border-slate-100 rounded-2xl px-4 text-sm font-bold text-slate-800">
                            <option value="">Select Sem</option>
                            @for($i=1; $i<=8; $i++)
                                <option value="{{ $i }}">Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Subject Node</label>
                        <select x-model="selectedSubject" required
                                class="w-full h-14 bg-slate-50 border-slate-100 rounded-2xl px-4 text-sm font-bold text-slate-800">
                            <option value="">Select Subject</option>
                            <template x-for="subject in filteredSubjects" :key="subject.id">
                                <option :value="subject.id" x-text="subject.name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Step 3: Complexity --}}
                <div class="space-y-3">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest px-1">Generation Complexity</label>
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer group">
                            <input type="radio" x-model="detailLevel" value="quick" class="peer hidden">
                            <div class="h-14 border-2 border-slate-100 rounded-2xl flex items-center justify-center gap-2 font-bold text-slate-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                                <span>⚡</span> Quick (500w)
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer group">
                            <input type="radio" x-model="detailLevel" value="detailed" class="peer hidden" checked>
                            <div class="h-14 border-2 border-slate-100 rounded-2xl flex items-center justify-center gap-2 font-bold text-slate-400 peer-checked:border-violet-500 peer-checked:bg-violet-50 peer-checked:text-violet-600 transition-all">
                                <span>📚</span> Detailed (800w)
                            </div>
                        </label>
                    </div>
                </div>

                <button type="button" @click="prepareStaging()" :disabled="!topicsInput.trim() || !selectedSubject" 
                        class="w-full h-16 bg-slate-900 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl hover:scale-[1.01] active:scale-95 transition-all disabled:opacity-50">
                    🔬 Preview & Process Topics
                </button>
            </div>
        </div>

        {{-- STAGING AREA VIEW --}}
        <div class="lg:col-span-2" x-show="showStaging" x-transition>
            <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-6">
                    <div>
                        <h3 class="text-xl font-black text-slate-800">Batch Staging Area</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                            Target Subject: <span class="text-primary" x-text="allSubjects.find(s => s.id == selectedSubject)?.name"></span>
                        </p>
                    </div>
                    <button type="button" @click="generateSelected()" 
                            :disabled="topicsList.filter(i => i.selected && i.status === 'pending').length === 0"
                            class="bg-primary text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 transition-all disabled:opacity-50">
                        Launch Selected
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, index) in topicsList" :key="index">
                        <div class="flex items-center gap-4 p-4 rounded-2xl border transition-all"
                             :class="{
                                 'bg-emerald-50 border-emerald-100': item.status === 'done',
                                 'bg-rose-50 border-rose-100': item.status === 'error',
                                 'bg-slate-50 border-slate-100': item.status === 'pending',
                                 'bg-blue-50 border-blue-100 animate-pulse': item.status === 'loading'
                             }">
                            
                            {{-- Checkbox --}}
                            <input type="checkbox" x-model="item.selected" :disabled="item.status !== 'pending'"
                                   class="h-5 w-5 rounded-lg border-slate-300 text-primary focus:ring-primary/20">

                            {{-- Topic Title --}}
                            <div class="flex-1">
                                <h4 class="font-bold text-sm text-slate-800" x-text="item.title"></h4>
                                <p x-show="item.status === 'error'" class="text-[10px] text-rose-500 font-bold mt-1" x-text="item.error"></p>
                                <p x-show="item.status === 'done'" class="text-[10px] text-emerald-600 font-black mt-1 uppercase tracking-tighter">Asset manifested successfully!</p>
                            </div>

                            {{-- Status & Actions --}}
                            <div class="flex items-center gap-2">
                                {{-- Loading --}}
                                <template x-if="item.status === 'loading'">
                                    <div class="flex items-center gap-2 text-[10px] font-black text-blue-600 uppercase">
                                        <svg class="animate-spin h-3 w-3 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                        Generating...
                                    </div>
                                </template>

                                {{-- Success --}}
                                <template x-if="item.status === 'done'">
                                    <div class="h-8 w-8 bg-emerald-500 text-white rounded-lg flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                </template>

                                {{-- Error --}}
                                <template x-if="item.status === 'error'">
                                    <button type="button" @click="generateItem(item)" class="text-rose-600 hover:text-rose-700 font-black text-[10px] uppercase underline">Retry</button>
                                </template>

                                {{-- Pending Action --}}
                                <template x-if="item.status === 'pending'">
                                    <button type="button" @click="generateItem(item)" class="bg-white text-slate-600 px-4 py-1.5 rounded-lg text-[10px] font-black uppercase border border-slate-200 hover:bg-primary hover:text-white hover:border-primary transition-all">
                                        Generate
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Help Side --}}
        <div class="space-y-6">
            <div class="bg-violet-600 p-8 rounded-[2rem] text-white space-y-4">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">⚡</div>
                <h3 class="text-xl font-bold">Smart AI Governance</h3>
                <p class="text-xs text-violet-100 leading-relaxed font-medium">
                    New Staging Area protects you from wasting credits and server crashes.
                </p>
                <div class="space-y-4 pt-4 border-t border-white/10">
                    <div class="flex gap-3">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full shrink-0 animate-pulse mt-1"></div>
                        <p class="text-[10px] font-bold text-violet-100">Atomic Generation: Prevents 500 Server Errors by generating one at a time via AJAX.</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full shrink-0 animate-pulse mt-1"></div>
                        <p class="text-[10px] font-bold text-violet-100">Itemized Review: Check status of each note in real-time without refreshing the page.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 space-y-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Energy Stats</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-500">API Status</span>
                        <span class="text-emerald-500 font-black flex items-center gap-1">
                             OPERATIONAL
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-500">Generation Core</span>
                        <span class="text-slate-800">Gemini 2.0 Flash</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
