<x-app-layout>
    @section('title', 'Study Notes Repository | MyCollegeVerse')
    @section('meta_description', 'Browse and download verified academic resources, lecture notes, and semester prep materials from top colleges.')

    <div class="space-y-10 pb-20" x-data="{ 
        showUploadModal: false,
        search: '',
        activeFilter: 'All Courses',
        matches(note) {
            const searchMatch = !this.search || 
                note.title.toLowerCase().includes(this.search.toLowerCase()) || 
                note.subject.toLowerCase().includes(this.search.toLowerCase()) ||
                note.college.toLowerCase().includes(this.search.toLowerCase());
            
            let filterMatch = true;
            if (this.activeFilter === 'Semester 1') {
                filterMatch = note.semester == 1;
            } else if (this.activeFilter === 'Engineering') {
                filterMatch = note.course.toLowerCase().includes('engineering');
            } else if (this.activeFilter === 'Verified Only') {
                filterMatch = note.is_verified;
            }
            
            return searchMatch && filterMatch;
        }
    }">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-4xl font-extrabold text-secondary mb-2">Notes Repository</h1>
                <p class="text-slate-500 font-medium">Browse and download verified academic resources.</p>
            </div>
            
            @auth
            <button @click="showUploadModal = true" class="bg-primary text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-all flex items-center gap-2">
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

        <!-- Filters & Search -->
        <div class="glass p-4 rounded-[2rem] shadow-sm border-white/40 flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <input type="text" x-model="search" @keydown.enter="$el.blur()" placeholder="Search by title or subject..." class="w-full h-12 bg-white/50 border border-slate-100 rounded-2xl px-12 focus:ring-primary/20 focus:border-primary text-sm font-medium">
                <svg class="absolute left-4 top-3.5 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            @php
                $filters = ['All Courses', 'Semester 1', 'Engineering', 'Verified Only'];
            @endphp

            @foreach($filters as $filter)
                <button 
                    @click="activeFilter = '{{ $filter }}'"
                    :class="activeFilter === '{{ $filter }}' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-600 hover:text-primary hover:border-primary/50'"
                    class="px-5 py-2.5 glass rounded-xl text-sm font-bold transition-all">
                    {{ $filter }}
                </button>
            @endforeach
        </div>

        <!-- Notes Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($notes as $note)
            <a href="{{ route('notes.show', $note->id) }}" 
               x-show="matches({ 
                   title: '{{ addslashes($note->title) }}', 
                   subject: '{{ addslashes($note->subject->name ?? '') }}', 
                   college: '{{ addslashes($note->college->name ?? '') }}',
                   semester: '{{ $note->subject->semester ?? 0 }}',
                   course: '{{ addslashes($note->subject->course ?? '') }}',
                   is_verified: {{ $note->is_verified ? 'true' : 'false' }} 
               })"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="opacity-0 scale-95"
               x-transition:enter-end="opacity-100 scale-100"
               class="glass p-6 rounded-[2.5rem] shadow-glass border-white hover:shadow-xl transition-all group relative overflow-hidden block">
                <!-- Priority Badge -->
                @if(Auth::check() && $note->college_id == Auth::user()->college_id)
                <div class="absolute top-0 right-0">
                    <div class="bg-primary text-white text-[9px] font-black uppercase px-4 py-1.5 rounded-bl-2xl shadow-sm tracking-widest">
                        Your College
                    </div>
                </div>
                @endif
                
                <div class="flex justify-between items-start mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @php $rating = 4.5 + (rand(0, 5) / 10); @endphp
                    <div class="flex items-center gap-1 bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-xs font-black">
                        ⭐ {{ $rating }}
                    </div>
                </div>

                <h4 class="text-xl font-extrabold text-slate-800 mb-2 truncate group-hover:text-primary transition-colors">{{ $note->title }}</h4>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">{{ $note->subject->name ?? 'Subject' }} • {{ $note->college->name ?? 'Global' }}</p>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-200 overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($note->user->name) }}&background=random" alt="{{ $note->user->name }}" />
                        </div>
                        <span class="text-xs font-bold text-slate-600">{{ $note->user->name }}</span>
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

        <!-- Upload Modal -->
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
                    <div class="px-8 pt-8 pb-10 bg-white/40">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-2xl font-black text-secondary">Upload New Resource</h3>
                            <button @click="showUploadModal = false" class="text-slate-400 hover:text-primary transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Note Title</label>
                                <input type="text" name="title" required placeholder="e.g. Quantum Mechanics Lecture Notes" class="w-full h-14 bg-white/60 border border-slate-100 rounded-2xl px-6 focus:ring-primary/20 focus:border-primary text-sm font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Subject Category</label>
                                <select name="subject_id" required class="w-full h-14 bg-white/60 border border-slate-100 rounded-2xl px-6 focus:ring-primary/20 focus:border-primary text-sm font-bold text-slate-700">
                                    <option value="">Select a subject...</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->course }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Upload File (PDF/DOC/ZIP)</label>
                                <div class="relative group mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl hover:border-primary/50 transition-all bg-white/30">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-primary transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-slate-600">
                                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-bold text-primary hover:underline">
                                                <span>Select a file</span>
                                                <input id="file-upload" name="file" type="file" class="sr-only" required>
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-slate-500 font-medium">PDF, DOC up to 10MB</p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full bg-primary text-white h-14 rounded-2xl font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all text-sm">
                                    Share with the Verse
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
