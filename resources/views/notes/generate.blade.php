<x-app-layout>
    @section('title', 'Generate AI Study Notes | MyCollegeVerse')
    @section('meta_description', 'Generate comprehensive AI-powered study notes on any topic instantly.')

    <div class="max-w-3xl mx-auto space-y-10 pb-20" x-data="{
        selectedCourse: '',
        selectedSemester: '',
        selectedSubject: '',
        isGenerating: false,
        allSubjects: {{ App\Models\Subject::all()->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'course_id' => $s->course_id, 'semester' => $s->semester])->toJson() }},

        get filteredSubjects() {
            if (!this.selectedCourse || !this.selectedSemester) return [];
            return this.allSubjects.filter(s => s.course_id == this.selectedCourse && s.semester == this.selectedSemester);
        }
    }">
        {{-- Header --}}
        <div class="text-center space-y-4">
            <div class="inline-flex items-center gap-2 bg-violet-100 text-violet-600 px-5 py-2 rounded-full text-xs font-black uppercase tracking-widest">
                🤖 AI Powered
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-secondary">Generate Study Notes</h1>
            <p class="text-slate-500 font-medium max-w-lg mx-auto">Enter any topic and let AI create comprehensive, exam-ready study notes instantly.</p>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-600 px-6 py-4 rounded-2xl font-bold text-sm">
            {{ session('error') }}
        </div>
        @endif

        {{-- Generation Form --}}
        <form action="{{ route('notes.generate.store') }}" method="POST" @submit="isGenerating = true" class="glass p-8 md:p-12 rounded-[3rem] border-white/60 shadow-glass space-y-8">
            @csrf

            {{-- Topic Input --}}
            <div class="space-y-3">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest px-1">Topic / Question</label>
                <input type="text" name="topic" value="{{ old('topic') }}" required
                       placeholder="e.g. Operating System Process Scheduling Algorithms"
                       class="w-full h-16 bg-white/60 border border-slate-100 rounded-2xl px-6 focus:ring-primary/20 focus:border-primary text-lg font-bold placeholder:text-slate-300">
                @error('topic')
                    <p class="text-rose-500 text-xs font-bold px-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Course + Semester Row --}}
            <div class="grid grid-cols-2 gap-5">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Academic Path</label>
                    <select x-model="selectedCourse" class="w-full h-14 bg-white/60 border border-slate-100 rounded-2xl px-6 text-sm font-bold text-slate-700">
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Semester</label>
                    <select x-model="selectedSemester" class="w-full h-14 bg-white/60 border border-slate-100 rounded-2xl px-6 text-sm font-bold text-slate-700">
                        <option value="">Select Sem</option>
                        @for($i=1; $i<=8; $i++)
                            <option value="{{ $i }}">Semester {{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            {{-- Subject --}}
            <div class="space-y-3">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest px-1">Subject</label>
                <select name="subject_id" x-model="selectedSubject" required
                        class="w-full h-14 bg-white/60 border border-slate-100 rounded-2xl px-6 text-sm font-bold text-slate-700">
                    <option value="">Select Subject</option>
                    <option value="other" class="text-primary font-black">Other / Custom Subject ✍️</option>
                    <template x-for="subject in filteredSubjects" :key="subject.id">
                        <option :value="subject.id" x-text="subject.name"></option>
                    </template>
                </select>
            </div>

            {{-- Custom Subject --}}
            <div x-show="selectedSubject === 'other'" x-transition class="bg-primary/5 p-6 rounded-[2rem] border border-primary/10 space-y-3">
                <label class="block text-[10px] font-black text-primary uppercase tracking-[0.2em] italic">Custom Subject Name</label>
                <input type="text" name="custom_subject" value="{{ old('custom_subject') }}" placeholder="Enter full subject name..."
                       class="w-full h-14 bg-white border-white/50 rounded-2xl px-6 text-sm font-bold focus:ring-primary/20 focus:border-primary shadow-sm">
            </div>

            {{-- Detail Level --}}
            <div class="space-y-3">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest px-1">Detail Level</label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="cursor-pointer group">
                        <input type="radio" name="detail_level" value="quick" class="peer hidden">
                        <div class="h-24 border-2 border-slate-100 rounded-2xl flex flex-col items-center justify-center gap-1 font-bold text-slate-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 group-hover:bg-slate-50 transition-all">
                            <span class="text-2xl">⚡</span>
                            <span class="text-xs">Quick</span>
                            <span class="text-[9px] text-slate-400">~800 words</span>
                        </div>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="detail_level" value="detailed" class="peer hidden" checked>
                        <div class="h-24 border-2 border-slate-100 rounded-2xl flex flex-col items-center justify-center gap-1 font-bold text-slate-400 peer-checked:border-violet-500 peer-checked:bg-violet-50 peer-checked:text-violet-600 group-hover:bg-slate-50 transition-all">
                            <span class="text-2xl">📚</span>
                            <span class="text-xs">Detailed</span>
                            <span class="text-[9px] text-slate-400">~2000 words</span>
                        </div>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="detail_level" value="exam" class="peer hidden">
                        <div class="h-24 border-2 border-slate-100 rounded-2xl flex flex-col items-center justify-center gap-1 font-bold text-slate-400 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-600 group-hover:bg-slate-50 transition-all">
                            <span class="text-2xl">🎯</span>
                            <span class="text-xs">Exam Ready</span>
                            <span class="text-[9px] text-slate-400">~3000 words</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" :disabled="isGenerating"
                    class="w-full h-16 bg-gradient-to-r from-violet-600 to-primary text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50 disabled:cursor-wait flex items-center justify-center gap-3">
                <template x-if="!isGenerating">
                    <span class="flex items-center gap-3">🤖 Generate Notes</span>
                </template>
                <template x-if="isGenerating">
                    <span class="flex items-center gap-3">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Generating... (15-30 seconds)
                    </span>
                </template>
            </button>
        </form>

        {{-- Info Card --}}
        <div class="glass p-8 rounded-[2.5rem] border-white/60 text-center space-y-4">
            <h3 class="text-lg font-black text-slate-800">How it works</h3>
            <div class="grid grid-cols-3 gap-6">
                <div class="space-y-2">
                    <div class="w-12 h-12 bg-violet-100 rounded-2xl flex items-center justify-center text-xl mx-auto">1️⃣</div>
                    <p class="text-xs font-bold text-slate-500">Enter your topic</p>
                </div>
                <div class="space-y-2">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-xl mx-auto">🤖</div>
                    <p class="text-xs font-bold text-slate-500">AI generates notes</p>
                </div>
                <div class="space-y-2">
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-xl mx-auto">✅</div>
                    <p class="text-xs font-bold text-slate-500">Read & share</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
