@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{
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
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Bulk AI Generation</h1>
            <p class="text-slate-500 font-medium">Generate up to 10 AI topics at once into a specific subject hub.</p>
        </div>
        <a href="{{ route('admin.notes') }}" class="inline-flex items-center gap-2 bg-slate-100 text-slate-600 px-6 py-3 rounded-2xl font-bold hover:bg-slate-200 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Hub
        </a>
    </div>

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-600 px-6 py-4 rounded-2xl font-bold flex items-center gap-3">
        <span class="text-xl">⚠️</span>
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Form Side --}}
        <div class="lg:col-span-2">
            <form action="{{ route('admin.notes.bulk.store') }}" method="POST" @submit="isGenerating = true" class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-8">
                @csrf
                
                {{-- Step 1: Topics --}}
                <div class="space-y-3">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest px-1">Note Topics (One per line)</label>
                    <textarea name="topics" rows="8" required
                              placeholder="e.g. Introduction to Neural Networks&#10;Backpropagation Algorithm&#10;Convolutional Neural Networks Explained"
                              class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 focus:ring-primary/20 focus:border-primary text-lg font-bold placeholder:text-slate-300"></textarea>
                    <p class="text-[10px] text-slate-400 font-bold italic px-1">Max 10 lines recommended to avoid timeout. Topics will be generated as individual verified notes.</p>
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
                        <select name="subject_id" x-model="selectedSubject" required
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
                            <input type="radio" name="detail_level" value="quick" class="peer hidden">
                            <div class="h-14 border-2 border-slate-100 rounded-2xl flex items-center justify-center gap-2 font-bold text-slate-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                                <span>⚡</span> Quick (500w)
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer group">
                            <input type="radio" name="detail_level" value="detailed" class="peer hidden" checked>
                            <div class="h-14 border-2 border-slate-100 rounded-2xl flex items-center justify-center gap-2 font-bold text-slate-400 peer-checked:border-violet-500 peer-checked:bg-violet-50 peer-checked:text-violet-600 transition-all">
                                <span>📚</span> Detailed (800w)
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Action --}}
                <button type="submit" :disabled="isGenerating || !selectedSubject"
                        class="w-full h-16 bg-gradient-to-r from-violet-600 to-indigo-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:scale-[1.01] active:scale-95 transition-all disabled:opacity-50 disabled:cursor-wait flex items-center justify-center gap-3">
                    <span x-show="!isGenerating">🚀 Launch Bulk Generation</span>
                    <span x-show="isGenerating" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Engaging Multiverse Intelligence...
                    </span>
                </button>
            </form>
        </div>

        {{-- Help Side --}}
        <div class="space-y-6">
            <div class="bg-violet-600 p-8 rounded-[2rem] text-white space-y-4">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">💡</div>
                <h3 class="text-xl font-bold">Admin Best Practices</h3>
                <p class="text-xs text-violet-100 leading-relaxed font-medium">
                    Bulk generation is powerful for SEO. Map topics strictly to their subjects to improve user search experience.
                </p>
                <ul class="space-y-3 pt-2">
                    <li class="flex items-center gap-2 text-[11px] font-bold">
                        <span class="w-5 h-5 bg-white/10 rounded-lg flex items-center justify-center">✅</span>
                        Topic-rich titles work best
                    </li>
                    <li class="flex items-center gap-2 text-[11px] font-bold">
                        <span class="w-5 h-5 bg-white/10 rounded-lg flex items-center justify-center">✅</span>
                        Use 'Detailed' for core topics
                    </li>
                    <li class="flex items-center gap-2 text-[11px] font-bold">
                        <span class="w-5 h-5 bg-white/10 rounded-lg flex items-center justify-center">✅</span>
                        Bulk notes are auto-verified
                    </li>
                </ul>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 space-y-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">System Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-500">API Gateway</span>
                        <span class="text-emerald-500 flex items-center gap-1">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            Standby
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-500">Engine</span>
                        <span class="text-slate-800">Gemini 2.0 Flash</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
