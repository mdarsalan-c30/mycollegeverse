<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $assignment->title }} | Free Candidate Assessment Node</title>
    
    <!-- SEO Optimization 🚀 -->
    <meta name="description" content="Validate talent with {{ $assignment->title }}. Use MyCollegeVerse Assess (TaskFlow) to manage candidate assignments, reviews, and evaluations for free. Ideal for startups and fast-growing teams.">
    <meta name="keywords" content="Free Assessment Tool, Candidate Task Management, Startup Hiring, Skill Validation, MyCollegeVerse, TaskFlow, {{ $assignment->role }}, {{ $assignment->task_type }} Test">
    <meta property="og:title" content="{{ $assignment->title }} | Talent Validation Node">
    <meta property="og:description" content="Complete the assessment for {{ $assignment->recruiter->company_name ?? 'MyCollegeVerse' }} and showcase your skills.">
    <meta name="robots" content="index, follow">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
    </style>
    <!-- Marked.js Markdown Engine ⚙️ -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
</head>
<body class="antialiased text-slate-900">
    <div class="min-h-screen flex flex-col">
        <!-- Brand Header -->
        <header class="h-20 flex items-center justify-between px-8 lg:px-20 bg-white/50 backdrop-blur-md border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">M</div>
                <span class="font-extrabold text-sm tracking-tight text-slate-900">MyCollegeVerse <span class="text-blue-600">Assess</span></span>
            </div>
            @guest
                <a href="{{ route('login') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-colors">Sign in for better tracking</a>
            @else
                <div class="flex items-center gap-3">
                    <img src="{{ auth()->user()->profile_photo_url }}" class="w-8 h-8 rounded-full border border-slate-100">
                    <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">{{ auth()->user()->name }}</span>
                </div>
            @endguest
        </header>

        <main class="flex-1 max-w-5xl mx-auto w-full px-6 py-12 lg:py-20 grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Left Side: Task Info -->
            <div class="lg:col-span-2 space-y-10">
                <div class="space-y-4">
                    <div class="inline-flex px-4 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-100">
                        Task Assessment Node
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        {{ $assignment->title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-6 pt-2">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">Hiring Org:</span>
                            <span class="text-slate-900 font-black text-[10px] uppercase tracking-widest">{{ $assignment->recruiter->company_name }}</span>
                        </div>
                        @if($assignment->deadline)
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">Ends:</span>
                            <span class="text-rose-500 font-black text-[10px] uppercase tracking-widest">{{ $assignment->deadline->format('d M, Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="prose prose-slate max-w-none">
                    <h3 class="text-lg font-black text-slate-900 uppercase tracking-widest mb-4">Instructions</h3>
                    <div id="instructions-content" class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm text-slate-600 leading-relaxed overflow-hidden">
                        {{ $assignment->instructions }}
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const content = document.getElementById('instructions-content');
                        content.innerHTML = marked.parse(content.innerText);
                    });
                </script>

                <div class="p-8 bg-blue-600 rounded-[2rem] text-white space-y-4 shadow-xl shadow-blue-200">
                    <h3 class="text-sm font-black uppercase tracking-widest">Recruiter's Vision</h3>
                    <p class="text-blue-50 text-sm font-medium leading-relaxed">
                        "We are looking for candidates who can demonstrate {{ strtolower($assignment->role ?? 'the required') }} skills with precision and creativity. Show us your best work node."
                    </p>
                </div>
            </div>

            <!-- Right Side: Submission Form -->
            <div class="lg:col-span-1">
                <div class="sticky top-10">
                    <form action="{{ route('assignments.submit', $assignment->slug) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-[2.5rem] border border-slate-200 shadow-2xl p-6 lg:p-8 space-y-8">
                        @csrf
                        <div class="text-center space-y-2">
                            <h2 class="text-xl lg:text-2xl font-black text-slate-900">Submit Work</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Secure Submission Protocol</p>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Full Name</label>
                                <input type="text" name="candidate_name" required placeholder="Your full name"
                                       value="{{ auth()->user()->name ?? '' }}"
                                       class="w-full h-12 bg-white border border-slate-300 rounded-xl px-4 text-sm font-bold focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all shadow-sm placeholder:text-slate-300">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Email Address</label>
                                <input type="email" name="candidate_email" required placeholder="you@example.com"
                                       value="{{ auth()->user()->email ?? '' }}"
                                       class="w-full h-12 bg-white border border-slate-300 rounded-xl px-4 text-sm font-bold focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all shadow-sm placeholder:text-slate-300">
                            </div>
                            
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Phone Number (WhatsApp Preferred)</label>
                                <input type="text" name="candidate_phone" placeholder="+91 00000 00000"
                                       class="w-full h-12 bg-white border border-slate-300 rounded-xl px-4 text-sm font-bold focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all shadow-sm placeholder:text-slate-300">
                            </div>
                        </div>

                        <div class="space-y-6">
                            @if(in_array('link', $assignment->submission_types))
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Work Link (Drive/Portfolio)</label>
                                <input type="url" name="submission_link" placeholder="https://drive.google.com/..."
                                       class="w-full h-12 bg-white border border-slate-300 rounded-xl px-4 text-sm font-bold focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all shadow-sm placeholder:text-slate-300">
                            </div>
                            @endif

                            @if(in_array('file', $assignment->submission_types))
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Upload Asset</label>
                                <div class="relative group">
                                    <input type="file" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="h-24 bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center group-hover:border-blue-400 group-hover:bg-blue-50/30 transition-all">
                                        <span class="text-2xl">📁</span>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Select submission file</span>
                                    </div>
                                </div>
                                <p class="text-[9px] text-slate-400 text-center font-medium mt-2">Max 20MB. Nodes purged after 10 days.</p>
                            </div>
                            @endif

                            @if(in_array('text', $assignment->submission_types))
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Written Deliverable / Notes</label>
                                <textarea name="submission_text" rows="5" placeholder="Paste your article or notes here..."
                                          class="w-full bg-white border border-slate-300 rounded-2xl p-4 text-sm font-bold focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all shadow-sm placeholder:text-slate-300"></textarea>
                            </div>
                            @endif
                        </div>

                        <button type="submit" class="w-full h-16 bg-blue-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-900 transition-all shadow-xl shadow-blue-100 active:scale-95">
                            Beam Submission 🚀
                        </button>
                    </form>
                </div>
            </div>
        </main>

        <footer class="py-10 text-center border-t border-slate-100">
            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">
                Powered by MCV Assess Node • Delhi NCR Academic Multiverse
            </p>
        </footer>
    </div>
</body>
</html>
