<x-recruiter-layout>
    <x-slot name="title">Review Work | {{ $assignment->title }}</x-slot>

    @push('head')
        <!-- Marked.js Markdown Engine ⚙️ -->
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    @endpush

    <div class="space-y-10">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('recruiter.assessments.index') }}" class="w-12 h-12 glass rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Review <span class="text-primary">Submissions</span></h1>
                    <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em] mt-1">{{ $assignment->title }} • {{ $submissions->count() }} Submissions</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button class="h-12 px-6 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-emerald-600 transition-all">
                    📢 Bulk Notify
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @forelse($submissions as $submission)
                <div x-data="{ open: false }" class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden transition-all">
                    <div class="p-8 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition-colors" @click="open = !open">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 bg-primary/5 rounded-xl flex items-center justify-center text-primary font-black">
                                {{ substr($submission->candidate_name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900">{{ $submission->candidate_name }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $submission->candidate_email }} • Submitted {{ $submission->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            @if($submission->score)
                                <div class="px-4 py-2 bg-emerald-50 rounded-lg border border-emerald-100">
                                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Score: {{ $submission->score }}/100</span>
                                </div>
                            @endif
                            
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg 
                                    @if($submission->status == 'pending') bg-amber-50 text-amber-500 border border-amber-100
                                    @elseif($submission->status == 'reviewed') bg-blue-50 text-blue-500 border border-blue-100
                                    @elseif($submission->status == 'shortlisted') bg-emerald-50 text-emerald-500 border border-emerald-100
                                    @else bg-red-50 text-red-500 border border-red-100 @endif">
                                    {{ strtoupper($submission->status) }}
                                </span>
                            </div>
                            
                            <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-slate-300 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <div x-show="open" x-collapse class="border-t border-slate-50 bg-slate-50/50 p-10">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <!-- Submission Content 📦 -->
                            <div class="space-y-6">
                                <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Submission Deliverables</h5>
                                
                                @if($submission->submission_link)
                                    <a href="{{ $submission->submission_link }}" target="_blank" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 hover:border-primary transition-all group">
                                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">🔗</div>
                                        <div>
                                            <p class="text-xs font-black text-slate-900">Shared Resource Link</p>
                                            <p class="text-[10px] text-primary font-bold truncate max-w-xs">{{ $submission->submission_link }}</p>
                                        </div>
                                    </a>
                                @endif

                                @if($submission->file_path)
                                    <a href="{{ $submission->file_path }}" target="_blank" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 hover:border-primary transition-all group">
                                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">📁</div>
                                        <div>
                                            <p class="text-xs font-black text-slate-900">Uploaded Task Asset</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Expires in: {{ $submission->expires_at ? $submission->expires_at->diffForHumans() : 'N/A' }}</p>
                                        </div>
                                    </a>
                                @endif

                                @if($submission->submission_text)
                                    <div class="p-6 bg-white rounded-2xl border border-slate-100">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Written Submission</p>
                                        <div id="submission-text-{{ $submission->id }}" class="text-sm font-medium text-slate-700 leading-relaxed overflow-hidden prose-sm max-w-none">
                                            {{ $submission->submission_text }}
                                        </div>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const target = document.getElementById('submission-text-{{ $submission->id }}');
                                            target.innerHTML = marked.parse(target.innerText);
                                        });
                                    </script>
                                @endif
                            </div>

                            <!-- Evaluation Panel ⭐ -->
                            <div class="space-y-8 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                                <h5 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Skill Evaluation Panel</h5>
                                
                                <form action="{{ route('recruiter.assessments.evaluate', $submission->id) }}" method="POST" class="space-y-6">
                                    @csrf
                                    <div class="space-y-4">
                                        @foreach(['Quality', 'Creativity', 'Communication'] as $crit)
                                            <div class="flex items-center justify-between">
                                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $crit }}</label>
                                                <input type="range" name="criteria[{{ $crit }}]" min="0" max="100" value="{{ $submission->evaluations->where('criteria', $crit)->first()->score ?? 0 }}"
                                                       class="w-48 h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-primary">
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="space-y-2 pt-4">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Overall Score (0-100)</label>
                                        <input type="number" name="score" value="{{ $submission->score ?? '' }}" required
                                               class="w-full h-12 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Review Feedback</label>
                                        <textarea name="feedback" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-xl p-4 text-sm font-bold">{{ $submission->recruiter_notes }}</textarea>
                                    </div>

                                    <button type="submit" class="w-full h-12 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:shadow-lg hover:shadow-primary/20 transition-all">
                                        Lock Evaluation 🛰️
                                    </button>
                                </form>

                                <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                                    <form action="{{ route('recruiter.assessments.submission.status', $submission->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="status" value="shortlisted">
                                        <button type="submit" class="w-full h-12 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all">
                                            Shortlist
                                        </button>
                                    </form>
                                    <form action="{{ route('recruiter.assessments.submission.status', $submission->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="w-full h-12 bg-slate-100 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-50 hover:text-red-500 transition-all">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[3rem] border border-dashed border-slate-200 p-20 text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-3xl flex items-center justify-center text-4xl mx-auto mb-6">🏜️</div>
                    <h2 class="text-2xl font-black text-slate-900">Zero Gravity Submissions</h2>
                    <p class="text-slate-400 font-bold mt-2">No candidates have submitted their work node yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-recruiter-layout>
