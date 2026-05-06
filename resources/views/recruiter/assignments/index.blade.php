<x-recruiter-layout>
    <x-slot name="title">MCV Assess | TaskFlow Management</x-slot>

    <div class="space-y-10">
        <!-- Header Signal 📡 -->
        <div class="flex items-center justify-between bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden relative">
            <div class="relative z-10">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">MCV Assess <span class="text-primary">TaskFlow</span></h1>
                <p class="text-slate-500 font-bold text-sm mt-2 uppercase tracking-widest">Recruitment Intelligence & Skill Validation</p>
            </div>
            <a href="{{ route('recruiter.assessments.create') }}" class="relative z-10 h-14 px-10 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] flex items-center gap-3 hover:scale-105 hover:shadow-xl hover:shadow-primary/20 transition-all group">
                <span>Manifest New Task</span>
                <span class="text-lg group-hover:rotate-90 transition-transform">➕</span>
            </a>
            
            <!-- Background Decoration -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl"></div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-8 py-4 rounded-2xl font-bold text-sm flex items-center gap-3 animate-bounce">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <!-- Assessment Grid 📊 -->
        <div class="grid grid-cols-1 gap-6">
            @forelse($assignments as $assignment)
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8 hover:border-primary/20 transition-all group">
                    <div class="flex items-start justify-between">
                        <div class="flex gap-6">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-primary group-hover:text-white transition-colors">
                                @if($assignment->task_type == 'Blog') ✍️ @elseif($assignment->task_type == 'Video') 🎬 @elseif($assignment->task_type == 'Sales') 🤝 @else 🧪 @endif
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900">{{ $assignment->title }}</h3>
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="px-3 py-1 bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-lg border border-slate-100">{{ $assignment->role ?? 'General' }}</span>
                                    <span class="text-slate-300">•</span>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Submissions: <span class="text-primary">{{ $assignment->submissions_count }}</span>
                                    </span>
                                    @if($assignment->deadline)
                                        <span class="text-slate-300">•</span>
                                        <span class="text-[10px] font-black @if($assignment->deadline->isPast()) text-red-400 @else text-slate-400 @endif uppercase tracking-widest">
                                            Deadline: {{ $assignment->deadline->format('d M, Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button onclick="copyLink('{{ route('assignments.show', $assignment->slug) }}')" class="h-12 px-6 glass rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-600 hover:text-primary transition-all flex items-center gap-2">
                                🔗 Share Link
                            </button>
                            <a href="{{ route('recruiter.assessments.review', $assignment->id) }}" class="h-12 px-8 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-primary transition-all">
                                👁️ Review Work
                            </a>
                            <form action="{{ route('recruiter.assessments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Purge this assignment and all its work nodes from the multiverse? This cannot be undone.')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-12 h-12 flex items-center justify-center bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all" title="Purge Assessment">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full @if($assignment->status == 'active') bg-emerald-500 @else bg-slate-300 @endif"></div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ strtoupper($assignment->status) }}</span>
                        </div>
                        <div class="flex -space-x-2">
                            @foreach($assignment->submissions->take(5) as $sub)
                                <div class="w-8 h-8 rounded-lg bg-slate-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-slate-400 uppercase">
                                    {{ substr($sub->candidate_name, 0, 1) }}
                                </div>
                            @endforeach
                            @if($assignment->submissions_count > 5)
                                <div class="w-8 h-8 rounded-lg bg-primary text-white border-2 border-white flex items-center justify-center text-[10px] font-bold">
                                    +{{ $assignment->submissions_count - 5 }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[3rem] border border-dashed border-slate-200 p-20 text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-3xl flex items-center justify-center text-4xl mx-auto mb-6">🛰️</div>
                    <h2 class="text-2xl font-black text-slate-900">No Assessment Nodes Found</h2>
                    <p class="text-slate-400 font-bold mt-2">Start validating talent by creating your first TaskFlow assignment.</p>
                    <a href="{{ route('recruiter.assessments.create') }}" class="inline-flex h-14 px-10 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] items-center gap-3 mt-8 hover:scale-105 transition-all">
                        Manifest First Task
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function copyLink(link) {
            navigator.clipboard.writeText(link);
            alert('Shareable Task link copied to multiverse clipboard! 🔗');
        }
    </script>
</x-recruiter-layout>
