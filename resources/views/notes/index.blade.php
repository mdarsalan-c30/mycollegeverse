<x-app-layout>
    @section('title', 'Study Notes Repository | MyCollegeVerse')
    @section('meta_description', 'Browse and download verified academic resources, lecture notes, and semester prep materials from top colleges.')

    <div class="space-y-10 pb-20" x-data="{ 
        showUploadModal: false,
        search: '{{ request('search') }}',
        activeCourse: '{{ request('course_id', 'All') }}',
        activeSemester: '{{ request('semester', 'All') }}',
        showVerifiedOnly: {{ request('is_verified') ? 'true' : 'false' }},

        // For Upload Modal
        uploadStep: 1,
        selectedCourse: '',
        selectedSemester: '',
        selectedSubject: '',
        allSubjects: {{ $subjects->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'course_id' => $s->course_id, 'semester' => $s->semester])->toJson() }},

        get filteredSubjects() {
            if (!this.selectedCourse || !this.selectedSemester) return [];
            return this.allSubjects.filter(s => s.course_id == this.selectedCourse && s.semester == this.selectedSemester);
        },

        submitFilters() {
            this.$refs.filterForm.submit();
        }
    }">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-4xl font-extrabold text-secondary mb-2">Notes Repository</h1>
                <p class="text-slate-500 font-medium">Browse and download verified academic resources.</p>
            </div>
            
            @auth
            <button @click="showUploadModal = true; uploadStep = 1" class="bg-primary text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Upload New Note
            </button>
            @else
            <a href="{{ route('login') }}" class="bg-slate-100 text-slate-600 px-8 py-4 rounded-[1.5rem] font-bold hover:bg-primary hover:text-white transition-all flex items-center gap-2 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Sign in to Upload
            </a>
            @endauth
        </div>

        <!-- Filters & Search (Intel Hub) 🛰️ -->
        <form action="{{ route('notes.index') }}" method="GET" x-ref="filterForm" class="glass p-5 rounded-[2.5rem] shadow-sm border-white/40 flex flex-wrap items-center gap-6">
            <!-- Search -->
            <div class="flex-1 min-w-[250px] relative">
                <input type="text" name="search" x-model="search" @keydown.enter="submitFilters()" placeholder="Search by title, subject or college..." class="w-full h-14 bg-white/50 border border-slate-100 rounded-2xl px-12 focus:ring-primary/20 focus:border-primary text-sm font-medium transition-all">
                <svg class="absolute left-4 top-4.5 h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <!-- Course Filter -->
            <div class="min-w-[150px]">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Academic Path</label>
                <select name="course_id" x-model="activeCourse" @change="submitFilters()" class="w-full h-12 bg-white/60 border border-slate-100 rounded-xl px-4 text-xs font-bold text-slate-600 focus:ring-primary/20 transition-all">
                    <option value="All">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Semester Filter -->
            <div class="min-w-[150px]">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Semester</label>
                <select name="semester" x-model="activeSemester" @change="submitFilters()" class="w-full h-12 bg-white/60 border border-slate-100 rounded-xl px-4 text-xs font-bold text-slate-600 focus:ring-primary/20 transition-all">
                    <option value="All">All Semesters</option>
                    @foreach($availableSemesters as $sem)
                        <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>Semester {{ $sem }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Verified Toggle -->
            <div class="flex flex-col items-center">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Verified</label>
                <input type="hidden" name="is_verified" :value="showVerifiedOnly ? '1' : ''">
                <button type="button" @click="showVerifiedOnly = !showVerifiedOnly; submitFilters();" 
                        :class="showVerifiedOnly ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400'"
                        class="h-12 px-6 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                    🛡️ <span class="ml-1" x-text="showVerifiedOnly ? 'ON' : 'OFF'"></span>
                </button>
            </div>
        </form>

        <!-- Notes Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($notes as $note)
            <a href="{{ route('notes.show', $note->slug ?? $note->id) }}" 
               class="glass p-6 rounded-[2.5rem] shadow-glass border-white hover:shadow-xl transition-all group relative overflow-hidden block">
                <!-- Priority Badge -->
                @if(Auth::check() && $note->college_id == Auth::user()->college_id)
                <div class="absolute top-0 right-0 z-10">
                    <div class="bg-primary text-white text-[9px] font-black uppercase px-4 py-1.5 rounded-bl-2xl shadow-sm tracking-widest">
                        Your College
                    </div>
                </div>
                @endif
                
                @if($note->exam_help_rate >= 80 && $note->reviews()->count() > 0)
                <div class="absolute top-0 left-0 z-10">
                    <div class="bg-emerald-500 text-white text-[9px] font-black uppercase px-4 py-1.5 rounded-br-2xl shadow-sm tracking-widest flex items-center gap-1">
                        <span class="animate-pulse">🛡️</span> {{ $note->exam_help_rate }}% Exam Trusted
                    </div>
                </div>
                @endif
                
                <div class="flex justify-between items-start mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @php 
                        $rating = $note->avg_rating; 
                        $reviewCount = $note->reviews()->count();
                    @endphp
                    <div class="flex flex-col items-end gap-1">
                        <div class="flex items-center gap-1 bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter shadow-sm ring-4 ring-amber-50">
                            ⭐ {{ number_format($rating, 1) }}
                        </div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $reviewCount }} Validations</span>
                    </div>
                </div>

                <h4 class="text-xl font-extrabold text-slate-800 mb-2 truncate group-hover:text-primary transition-colors">{{ $note->title }}</h4>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">{{ optional($note->subject)->name ?? 'Subject' }} • {{ optional($note->subject->course)->name ?? 'Global' }}</p>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-200 overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(optional($note->user)->name ?? 'Unknown') }}&background=random" alt="{{ optional($note->user)->name ?? 'Unknown' }}" />
                        </div>
                        <span class="text-xs font-bold text-slate-600">{{ optional($note->user)->name ?? 'Unknown User' }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-slate-400 font-bold text-xs uppercase group-hover:text-primary">
                        Access Hub
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-24 h-24 bg-slate-100 rounded-full mx-auto flex items-center justify-center text-3xl mb-4">📭</div>
                <h3 class="text-xl font-black text-slate-800">No notes found!</h3>
                <p class="text-slate-500 font-medium">Be the first to share your knowledge with the verse.</p>
            </div>
            @endforelse
        </div>

        <!-- Upload Modal (Refined Step Flow) -->
        <div x-show="showUploadModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div @click="showUploadModal = false" class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true"></div>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform glass rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border-white">
                    <div class="px-8 md:px-12 pt-10 pb-12 bg-white/40">
                        <div class="flex justify-between items-center mb-10">
                            <div>
                                <h3 class="text-2xl font-black text-secondary">Share Your Knowledge</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic">Step <span x-text="uploadStep"></span> of 2: Meta Configuration</p>
                            </div>
                            <button @click="showUploadModal = false" class="text-slate-400 hover:text-primary transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <!-- Step 1: Core Selection -->
                            <div x-show="uploadStep === 1" class="space-y-6" x-transition:enter="duration-300 transform" x-transition:enter-start="translate-x-4 opacity-0">
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Note Title</label>
                                    <input type="text" name="title" required placeholder="e.g. Operating System Unit 1 Notes" class="w-full h-14 bg-white/60 border border-slate-100 rounded-2xl px-6 focus:ring-primary/20 focus:border-primary text-sm font-bold">
                                </div>

                                <div class="grid grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Academic Path</label>
                                        <select x-model="selectedCourse" required class="w-full h-14 bg-white/60 border border-slate-100 rounded-2xl px-6 text-sm font-bold text-slate-700">
                                            <option value="">Select Course</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Semester</label>
                                        <select x-model="selectedSemester" required class="w-full h-14 bg-white/60 border border-slate-100 rounded-2xl px-6 text-sm font-bold text-slate-700">
                                            <option value="">Select Sem</option>
                                            @for($i=1; $i<=8; $i++)
                                                <option value="{{ $i }}">Semester {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Subject Node</label>
                                    <select name="subject_id" x-model="selectedSubject" :disabled="!selectedCourse || !selectedSemester" required class="w-full h-14 bg-white/60 border border-slate-100 rounded-2xl px-6 text-sm font-bold text-slate-700 disabled:opacity-50 transition-opacity">
                                        <option value="">Select Subject</option>
                                        <template x-for="subject in filteredSubjects" :key="subject.id">
                                            <option :value="subject.id" x-text="subject.name"></option>
                                        </template>
                                    </select>
                                </div>

                                <button type="button" 
                                        @click="if(selectedSubject) uploadStep = 2" 
                                        :disabled="!selectedSubject"
                                        class="w-full h-16 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:bg-black disabled:opacity-30 transition-all">
                                    Next: File Upload
                                </button>
                            </div>

                            <!-- Step 2: File Upload -->
                            <div x-show="uploadStep === 2" class="space-y-8" x-transition:enter="duration-300 transform" x-transition:enter-start="translate-x-4 opacity-0">
                                <div class="relative group flex justify-center px-10 pt-10 pb-12 border-2 border-slate-200 border-dashed rounded-3xl hover:border-primary/50 transition-all bg-white/30">
                                    <div class="space-y-4 text-center">
                                        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                            <svg class="h-10 w-10 text-primary" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M24 14v20M14 24h20" stroke-width="4" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                        <div class="flex flex-col text-sm text-slate-600">
                                            <label for="file-upload" class="relative cursor-pointer font-black text-primary text-lg hover:underline">
                                                <span>Click to Select Node File</span>
                                                <input id="file-upload" name="file" type="file" class="sr-only" required @change="fileName = $el.files[0].name">
                                            </label>
                                            <p class="mt-2 text-xs text-slate-400 font-bold uppercase italic tracking-tighter">Academic Documents (PDF/Doc) up to 10MB</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <button type="button" @click="uploadStep = 1" class="flex-1 h-16 bg-slate-100 text-slate-500 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">Back</button>
                                    <button type="submit" class="flex-[2] h-16 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all outline-none">
                                        Share with the Verse
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
