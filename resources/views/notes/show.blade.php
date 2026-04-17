<x-app-layout>
    @section('title', $seoTitle ?? $note->title . ' | MyCollegeVerse')
    @section('meta_description', $seoDescription ?? 'Download academic notes for ' . $note->title)

    @push('structured-data')
    <script type="application/ld+json">
        {!! json_encode($schema) !!}
    </script>
    @endpush

    <div class="grid lg:grid-cols-3 gap-10 pb-20">
        <!-- Main Content (Left) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Breadcrumbs -->
            <nav class="flex text-sm font-bold text-slate-400 gap-2">
                <a href="{{ route('notes.index') }}" class="hover:text-primary">Notes</a>
                <span>/</span>
                <span class="text-secondary">{{ $note->subject->name ?? 'General' }}</span>
                <span>/</span>
                <span class="text-slate-800">{{ $note->title }}</span>
            </nav>

            <!-- Note Viewer -->
            <div class="glass rounded-[3rem] overflow-hidden border-white/60 shadow-glass relative">
                @if($note->isAiGenerated())
                {{-- AI Content Renderer --}}
                <div class="p-8 md:p-12 bg-white/60">
                    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-slate-100">
                        <div class="bg-violet-100 text-violet-600 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest flex items-center gap-2">
                            🤖 AI Generated
                        </div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Powered by Gemini</span>
                    </div>
                    <article class="prose prose-lg prose-slate max-w-none ai-notes-display">
                        <style>
                            .ai-notes-display {
                                font-family: 'Inter', sans-serif;
                                line-height: 1.8;
                                color: #334155;
                            }
                            .ai-notes-display h2 {
                                color: #1e293b !important;
                                font-weight: 900 !important;
                                font-size: 1.875rem !important;
                                border-left: 6px solid #6366f1;
                                padding-left: 1.25rem;
                                margin-top: 3.5rem !important;
                                margin-bottom: 1.5rem !important;
                                background: linear-gradient(to right, #f8fafc, transparent);
                                padding-top: 0.5rem;
                                padding-bottom: 0.5rem;
                            }
                            .ai-notes-display h3 {
                                color: #475569 !important;
                                font-weight: 800 !important;
                                font-size: 1.5rem !important;
                                margin-top: 2.5rem !important;
                                margin-bottom: 1.25rem !important;
                            }
                            .ai-notes-display .info-box {
                                background: linear-gradient(135deg, #f0f7ff 0%, #e0effe 100%);
                                border: 1px solid #bae6fd;
                                border-radius: 2rem;
                                padding: 2.5rem;
                                margin: 3rem 0;
                                position: relative;
                            }
                            .ai-notes-display .info-box::before {
                                content: '📖 DEFINITION';
                                position: absolute;
                                top: -0.75rem;
                                left: 2rem;
                                background: #0369a1;
                                color: white;
                                padding: 0.25rem 1.25rem;
                                border-radius: 1rem;
                                font-size: 0.65rem;
                                font-weight: 900;
                            }
                            .ai-notes-display .exam-tip {
                                background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
                                border: 2px dashed #f59e0b;
                                border-radius: 2rem;
                                padding: 2.5rem;
                                margin: 3rem 0;
                                color: #92400e;
                                position: relative;
                            }
                            .ai-notes-display .exam-tip::before {
                                content: '🎓 EXAM FOCUS';
                                position: absolute;
                                top: -0.75rem;
                                left: 2rem;
                                background: #d97706;
                                color: white;
                                padding: 0.25rem 1.25rem;
                                border-radius: 1rem;
                                font-size: 0.65rem;
                                font-weight: 900;
                            }
                            .ai-notes-display .diagram-box {
                                background: #f8fafc;
                                border: 2px solid #e2e8f0;
                                border-radius: 2.5rem;
                                padding: 4rem 2rem;
                                margin: 4rem 0;
                                text-align: center;
                                font-style: italic;
                                color: #64748b;
                                position: relative;
                                border-style: dashed;
                            }
                            .ai-notes-display .diagram-box::after {
                                content: '📸 CONCEPTUAL ILLUSTRATION';
                                position: absolute;
                                bottom: 1.5rem;
                                left: 50%;
                                transform: translateX(-50%);
                                font-size: 0.6rem;
                                font-weight: 800;
                                letter-spacing: 0.2em;
                                opacity: 0.4;
                            }
                            .ai-notes-display table {
                                border-radius: 1.5rem;
                                overflow: hidden;
                                border: 1px solid #e2e8f0;
                                background: white;
                            }
                            .ai-notes-display th {
                                background: #f1f5f9;
                                color: #475569 !important;
                                font-weight: 900 !important;
                                text-transform: uppercase;
                                letter-spacing: 0.05em;
                            }
                            .ai-notes-display td {
                                border-bottom: 1px solid #f1f5f9;
                            }
                            /* Mermaid Diagram Styling */
                            .ai-notes-display .mermaid {
                                background: white;
                                padding: 2rem;
                                border-radius: 2.5rem;
                                border: 1px solid #e2e8f0;
                                margin: 3rem 0;
                                display: flex;
                                justify-content: center;
                                overflow-x: auto;
                            }
                        </style>

                        {!! $note->ai_content !!}

                        @if($note->note_type === 'ai')
                            <script type="module">
                                import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
                                mermaid.initialize({ 
                                    startOnLoad: true,
                                    theme: 'neutral',
                                    fontFamily: 'Inter',
                                    securityLevel: 'loose'
                                });
                            </script>
                        @endif
                    </article>
                </div>
                @else
                {{-- PDF Viewer --}}
                <div class="aspect-[3/4] bg-slate-100 relative">
                    <embed src="{{ filter_var($note->file_path, FILTER_VALIDATE_URL) ? $note->file_path : asset('storage/' . $note->file_path) }}#toolbar=0&navpanes=0&scrollbar=1" type="application/pdf" width="100%" height="100%" class="rounded-[2.5rem]" />
                    
                    <!-- Fallback for browsers that don't support embed -->
                    <div class="absolute inset-0 flex items-center justify-center p-10 text-center bg-slate-50 z-[-1]">
                        <div class="space-y-4">
                            <div class="w-16 h-16 bg-primary/10 rounded-2xl mx-auto flex items-center justify-center text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <h3 class="font-black text-slate-800">Preview not available</h3>
                            <p class="text-sm text-slate-500 font-medium">Your browser doesn't support PDF previews. Please download to view.</p>
                            
                            @auth
                            <a href="{{ route('notes.download', $note->id) }}" class="inline-block bg-primary text-white px-6 py-2 rounded-xl font-bold text-sm">Download Instead</a>
                            @else
                            <a href="{{ route('login') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-xl font-bold text-sm">Sign in to Download</a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Notes Meta & Discussion -->
            <div class="glass p-10 rounded-[3rem] border-white/60">
                 <div class="flex flex-col sm:flex-row justify-between items-start gap-8 mb-10 pb-10 border-b border-slate-100/50">
                     <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h2 class="text-4xl font-black text-secondary tracking-tight">{{ $note->title }}</h2>
                            @auth
                            <form action="{{ route('notes.save', $note->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-3 rounded-2xl transition-all {{ $isSaved ? 'bg-rose-50 text-rose-500' : 'bg-slate-50 text-slate-300 hover:text-rose-400' }}" title="{{ $isSaved ? 'Remove from library' : 'Save to library' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $isSaved ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </form>
                            @endauth
                        </div>
                        <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px] flex items-center gap-2">
                           <img src="https://ui-avatars.com/api/?name={{ urlencode($note->user->name ?? 'U') }}&background=6366f1&color=fff" class="w-5 h-5 rounded-md" />
                           Uploaded by <span class="text-slate-600 font-black">{{ optional($note->user)->name ?? 'Unknown Scholar' }}</span> • {{ $note->created_at->diffForHumans() }}
                        </p>
                     </div>
                     
                     <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                         <!-- Share Actions -->
                         <div class="flex items-center bg-slate-50 p-1.5 rounded-2xl gap-1">
                             <a href="https://wa.me/?text={{ urlencode('Check out these study notes: ' . $note->title . ' ' . route('notes.show', $note->slug)) }}" target="_blank" class="p-2.5 text-emerald-500 hover:bg-emerald-50 rounded-xl transition-all" title="Share on WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.891 11.891-11.891 3.181 0 6.167 1.24 8.413 3.488 2.247 2.248 3.488 5.232 3.488 8.412 0 6.557-5.332 11.892-11.891 11.892-1.996 0-3.951-.502-5.69-1.451l-6.31 1.659zm6.273-3.692l.454.269c1.402.83 3.205 1.27 5.116 1.27 5.518 0 10.011-4.493 10.011-10.011 0-2.675-1.042-5.187-2.936-7.078-1.891-1.891-4.404-2.934-7.076-2.934-5.517 0-10.011 4.492-10.011 10.011 0 1.83.479 3.618 1.386 5.201l.298.513-1.112 4.062 4.155-1.091zm11.366-7.398c-.141-.07-.835-.412-.963-.46-.128-.048-.222-.07-.315.071-.093.14-.361.46-.443.553-.082.094-.164.105-.304.035-.14-.07-.591-.218-1.125-.694-.417-.371-.698-.828-.781-.968-.081-.141-.009-.217.06-.287.063-.063.141-.164.212-.246.07-.082.094-.141.141-.235.047-.094.024-.176-.012-.246-.035-.07-.315-.758-.432-1.037-.113-.274-.228-.237-.315-.241l-.269-.004c-.093 0-.245.035-.374.175-.128.141-.491.481-.491 1.173 0 .693.504 1.361.574 1.455.07.094.992 1.514 2.404 2.122.336.144.598.23.803.295.337.107.644.092.886.056.27-.04.835-.341 1.053-.67s.218-.611.152-.67c-.066-.059-.245-.093-.386-.164z"/></svg>
                             </a>
                             <a href="https://twitter.com/intent/tweet?text={{ urlencode('Check out these study notes: ' . $note->title . ' ' . route('notes.show', $note->slug)) }}" target="_blank" class="p-2.5 text-slate-800 hover:bg-slate-200 rounded-xl transition-all" title="Share on X">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                             </a>
                             <button onclick="copyNoteLink()" class="p-2.5 text-primary hover:bg-primary/10 rounded-xl transition-all" title="Copy Link">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                             </button>
                         </div>

                         @auth
                         <a href="{{ route('notes.download', $note->id) }}" class="bg-primary text-white px-8 py-4 rounded-3xl font-black shadow-xl shadow-primary/20 flex items-center justify-center gap-3 hover:scale-105 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" /></svg>
                            Download PDF
                         </a>
                         @else
                         <a href="{{ route('login') }}" class="bg-slate-900 text-white px-8 py-4 rounded-3xl font-black flex items-center justify-center gap-3 hover:bg-primary transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                            Log in to Access
                         </a>
                         @endauth
                     </div>
                 </div>

                 <!-- Copy Link Logic & Toast -->
                 <script>
                    function copyNoteLink() {
                        const url = "{{ route('notes.show', $note->slug) }}";
                        navigator.clipboard.writeText(url).then(() => {
                            // Professional Toast Notification
                            const toast = document.createElement('div');
                            toast.className = 'fixed bottom-10 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-8 py-4 rounded-full font-black text-xs uppercase tracking-widest shadow-2xl z-50 animate-bounce';
                            toast.innerHTML = '📋 Link copied to clipboard!';
                            document.body.appendChild(toast);
                            setTimeout(() => toast.remove(), 3000);
                        });
                    }
                 </script>

                 @if($note->reviews()->count() > 0)
                 <div class="mb-12 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                            Verified Student Validation
                            <span class="bg-emerald-100 text-emerald-600 text-[10px] px-2 py-0.5 rounded-md uppercase tracking-tighter shadow-sm">Peer Reviewed</span>
                        </h3>
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $note->reviews()->count() }} Responses</div>
                    </div>

                    <div class="grid gap-4">
                        @foreach($note->reviews()->latest()->take(5)->get() as $review)
                        <div class="glass p-6 rounded-[2rem] border-emerald-100/50 bg-emerald-50/20">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                <div class="flex gap-4">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=10b981&color=fff" class="w-10 h-10 rounded-xl shadow-sm" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-slate-800 text-sm">{{ $review->user->name }}</p>
                                            <div class="flex text-[10px]">
                                                @for($i=1; $i<=5; $i++)
                                                    <span class="{{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}">⭐</span>
                                                @endfor
                                            </div>
                                        </div>
                                        @if($review->helped_in_exam)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-black text-emerald-600 uppercase tracking-tighter mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                            Helped in Exam
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if($review->feedback)
                            <p class="mt-4 text-sm font-medium text-slate-600 leading-relaxed italic">"{{ $review->feedback }}"</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                 </div>
                 @endif

                 <div class="space-y-6">

                    <h3 class="text-xl font-black text-slate-800">Discussion ({{ $note->comments->count() }})</h3>
                    
                    <div id="comment-list-{{ $note->id }}" class="space-y-6 mb-8">
                        @foreach($note->comments as $comment)
                        <div class="flex gap-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(optional($comment->user)->name ?? 'Unknown') }}&background=random" class="w-10 h-10 rounded-xl" />
                            <div>
                                <p class="font-bold text-slate-800">{{ optional($comment->user)->name ?? 'Unknown User' }}</p>
                                <p class="text-sm text-slate-600 font-medium">{{ $comment->content }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @auth
                    <form onsubmit="submitComment(event, {{ $note->id }}, 'App\\Models\\Note')" class="flex gap-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" class="w-10 h-10 rounded-xl shadow-sm"/>
                        <textarea placeholder="Ask a doubt or leave a review..." class="flex-1 bg-slate-50 border border-slate-100 rounded-2xl p-4 text-sm font-medium focus:ring-primary/20"></textarea>
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-xl font-bold flex items-center justify-center">Post</button>
                    </form>
                    @else
                    <div class="bg-slate-50 rounded-[2rem] p-8 text-center border border-dashed border-slate-200">
                        <p class="text-sm font-bold text-slate-500 mb-4">You must be logged in to join the discussion.</p>
                        <a href="{{ route('login') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-all">Sign In to Comment</a>
                    </div>
                    @endauth
                 </div>
            </div>
        </div>

        <!-- Sidebar (Right) -->
        <div class="space-y-8">
             <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm text-center space-y-6">
                <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-600 px-4 py-1.5 rounded-full text-sm font-black ring-4 ring-amber-50">
                    ⭐ {{ number_format($note->avg_rating, 1) }} <span class="text-amber-400/50">/ 5.0</span>
                </div>
                <div class="space-y-1">
                    <p class="text-2xl font-black text-slate-800">{{ $note->reviews()->count() }} Students</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-loose">Academic Authority Index</p>
                </div>
            </div>

            <!-- Note Authority & Validation Poll -->
            <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm space-y-6">
                <!-- Validation logic already here -->
            </div>

            <!-- Note Authority & Validation Poll -->
            <div class="glass p-8 rounded-[2.5rem] border-white/60 shadow-sm space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h4 class="text-lg font-black text-secondary">Exam Readiness</h4>
                </div>
                
                <p class="text-sm font-medium text-slate-500 leading-relaxed">Is this resource helpful for your exams? Your validation builds the multiverse trust.</p>

                @auth
                <form action="{{ route('notes.review', $note->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer group">
                            <input type="radio" name="helped_in_exam" value="1" required class="peer hidden">
                            <div class="h-14 border-2 border-slate-100 rounded-2xl flex items-center justify-center gap-2 font-bold text-slate-400 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 group-hover:bg-slate-50 transition-all">
                                <span>👍</span> Yes
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="helped_in_exam" value="0" class="peer hidden">
                            <div class="h-14 border-2 border-slate-100 rounded-2xl flex items-center justify-center gap-2 font-bold text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-600 group-hover:bg-slate-50 transition-all">
                                <span>👎</span> No
                            </div>
                        </label>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rate Academic Quality</label>
                        <select name="rating" required class="w-full h-12 bg-slate-50 border-slate-100 rounded-xl text-sm font-bold focus:ring-primary/20">
                            <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                            <option value="4">⭐⭐⭐⭐ Great</option>
                            <option value="3">⭐⭐⭐ Good</option>
                            <option value="2">⭐⭐ Fair</option>
                            <option value="1">⭐ Poor</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Academic Feedback (Optional)</label>
                        <textarea name="feedback" placeholder="Help other students by explaining your rating..." class="w-full h-24 bg-slate-50 border-slate-100 rounded-xl text-sm font-medium p-4 focus:ring-primary/20"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white h-14 rounded-2xl font-bold shadow-xl hover:scale-[1.02] active:scale-95 transition-all text-sm flex items-center justify-center gap-2">
                        Submit & Earn +5 Karma
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="block w-full text-center py-4 bg-slate-100 text-slate-500 rounded-2xl font-bold hover:bg-slate-200 transition-all text-sm">
                    Sign in to Validate
                </a>
                @endauth
            </div>

            <div class="glass p-8 rounded-[2.5rem] border-white/60">
                <h4 class="text-lg font-black text-secondary mb-6">Related from {{ $note->subject->name ?? 'General' }}</h4>
                <div class="space-y-6">
                    @forelse($related as $rel)
                    <a href="{{ route('notes.show', $rel->slug ?? $rel->id) }}" class="flex gap-4 group cursor-pointer">
                        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all text-xl">📄</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate group-hover:text-primary transition-colors">{{ $rel->title }}</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ optional($note->subject)->name ?? 'General' }} • {{ optional($rel->user)->name ?? 'Unknown' }}</p>
                        </div>
                    </a>
                    @empty
                    <p class="text-xs text-slate-400 italic">No related notes yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        async function submitComment(e, id, type) {
            e.preventDefault();
            const input = e.target.querySelector('textarea');
            const content = input.value;
            if (!content) return;

            const res = await fetch('{{ route("community.comment") }}', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    commentable_id: id,
                    commentable_type: type,
                    content: content
                })
            });

            const data = await res.json();
            if (data.status === 'success') {
                document.getElementById(`comment-list-${id}`).insertAdjacentHTML('beforeend', data.html);
                input.value = '';
            }
        }
    </script>
</x-app-layout>
