<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $seoTitle ?? $job->title . ' | Career Verse' }}</title>
        <meta name="description" content="{{ $seoDescription ?? 'Apply for university jobs and internships.' }}">
        <link rel="canonical" href="{{ url()->current() }}" />

        <!-- Analytics Infrastructure 📡 -->
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-5TTR79WQ');</script>
        <!-- End Google Tag Manager -->

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-K06L0YE2PH"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'G-K06L0YE2PH');
        </script>

        <!-- Microsoft Clarity -->
        <script type="text/javascript">
            (function(c,l,a,r,i,t,y){
                c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            })(window, document, "clarity", "script", "wa301yp28x");
        </script>

        <!-- Structured Data -->
        @if(isset($schema))
        <script type="application/ld+json">
            {!! json_encode($schema) !!}
        </script>
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Tailwind CDN with Custom Config -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        
        <!-- Uploadcare Widget -->
        <script>
            UPLOADCARE_PUBLIC_KEY = "{{ env('UPLOADCARE_PUBLIC_KEY') }}";
            UPLOADCARE_TABS = "file pdf google_drive dropbox";
            UPLOADCARE_EFFECTS = "crop";
            UPLOADCARE_PREVIEW_STEP = true;
            UPLOADCARE_CLEARABLE = true;
            UPLOADCARE_VALIDATION_ERROR_ONLY = true;
        </script>
        <script src="https://ucarecdn.com/libs/widget/3.x/uploadcare.full.min.js"></script>

        <style>
            [x-cloak] { display: none !important; }
            /* Customizing Uploadcare Widget to match Verse aesthetic */
            .uploadcare--widget__button_type_open {
                background-color: #0f172a !important;
                border: none !important;
                border-radius: 12px !important;
                color: white !important;
                font-size: 10px !important;
                font-weight: 900 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.1em !important;
                height: 56px !important;
                width: 100% !important;
                transition: all 0.3s ease !important;
            }
            .uploadcare--widget__button_type_open:hover {
                background-color: #3b82f6 !important;
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.2) !important;
            }
        </style>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#3B82F6',
                            secondary: '#6177A5',
                        },
                        fontFamily: {
                            sans: ['Plus Jakarta Sans', 'sans-serif'],
                        },
                    }
                }
            }
        </script>

        <style>
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .hero-pattern {
                background-color: #ffffff;
                background-image: radial-gradient(#3b82f61a 1px, transparent 1px);
                background-size: 30px 30px;
            }
        </style>
    </head>
    <body class="antialiased bg-slate-50 text-slate-900 hero-pattern min-h-screen">
        <nav class="fixed top-0 w-full z-50 px-6 py-4">
            <div class="max-w-7xl mx-auto glass rounded-2xl px-6 py-3 flex justify-between items-center shadow-sm">
                <a href="{{ route('jobs.index') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-xl">←</span>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-secondary">Back to Board</span>
                </a>
                
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-slate-600 font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 font-medium hover:text-primary">Login</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="pt-32 pb-20 px-6">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-[3rem] p-12 shadow-2xl border border-slate-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-bl-[8rem] -mr-32 -mt-32"></div>

                    <div class="relative z-10">
                        <div class="flex items-start justify-between mb-12">
                            <div>
                                <span class="px-4 py-1.5 bg-primary/10 text-primary text-[10px] font-black rounded-xl uppercase tracking-widest mb-4 inline-block">{{ $job->type }}</span>
                                <h1 class="text-5xl font-black text-slate-900 tracking-tighter leading-tight">{{ $job->title }}</h1>
                                <p class="text-xl font-bold text-slate-500 mt-2">{{ $job->recruiter->company_name }} — Verified Node</p>
                            </div>
                            <div class="w-24 h-24 bg-slate-50 rounded-3xl flex items-center justify-center text-4xl shadow-inner">
                                 @if($job->type === 'Internship') 🎓 @else 💼 @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16 border-y border-slate-50 py-10">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Location</p>
                                <p class="font-bold text-slate-800">{{ $job->location ?? 'Remote Global' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Compensation</p>
                                <p class="font-bold text-slate-800">{{ $job->salary_range ?? 'Competitive' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Target</p>
                                <p class="font-bold text-primary">{{ $job->targetCollege->name ?? 'Open to All' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Posted</p>
                                <p class="font-bold text-slate-800">{{ $job->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="prose prose-slate max-w-none mb-16">
                            <h3 class="text-xl font-black text-slate-900 mb-6">Execution & Responsibilities</h3>
                            <div class="text-slate-600 leading-relaxed font-medium space-y-4 whitespace-pre-wrap">{{ $job->description }}</div>
                        </div>

                        <div x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }" class="flex flex-col md:flex-row gap-6 items-center">
                            @auth
                                @php
                                    $hasApplied = \App\Models\JobApplication::where('job_id', $job->id)->where('student_id', Auth::id())->exists();
                                @endphp

                                @if(session('error'))
                                    <div class="fixed top-24 left-1/2 -translate-x-1/2 z-[110] bg-red-50 border border-red-100 text-red-600 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if($hasApplied)
                                    <div class="flex flex-col gap-2">
                                        <button class="w-full md:w-auto px-12 h-16 bg-green-50 text-green-600 border-2 border-green-200 text-[12px] font-black uppercase tracking-widest rounded-2xl flex items-center justify-center gap-2 cursor-default">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Signal Initialized
                                        </button>
                                        <a href="{{ route('pipeline.index') }}" class="text-[10px] font-black text-primary uppercase tracking-widest text-center hover:underline">View in Pipeline</a>
                                    </div>
                                @else
                                    <button @click="open = true" class="w-full md:w-auto px-12 h-16 bg-slate-900 text-white text-[12px] font-black uppercase tracking-widest rounded-2xl shadow-2xl shadow-black/20 hover:scale-105 transition-all flex items-center justify-center">
                                        Initialize Application Signal
                                    </button>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Formalizes your candidacy for this role</p>
                                @endif

                                <!-- Application Modal -->
                                <div x-show="open" 
                                     x-cloak
                                     style="display: none;" 
                                     class="fixed inset-0 z-[100] overflow-y-auto">
                                    <div class="flex items-center justify-center min-h-screen p-6">
                                        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="open = false"></div>

                                        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative z-50 bg-white rounded-[3rem] shadow-2xl max-w-2xl w-full p-12 overflow-hidden border border-slate-100 text-left">
                                            
                                            <!-- Validation Error List -->
                                            @if($errors->any())
                                                <div class="mb-8 p-6 bg-red-50 rounded-2xl border border-red-100">
                                                    <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-3 italic">Signal Interference Localized:</p>
                                                    <ul class="space-y-1">
                                                        @foreach($errors->all() as $error)
                                                            <li class="text-[10px] font-bold text-red-500">• {{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <div class="mb-10 text-center">
                                                <h2 class="text-2xl font-black text-slate-900 mb-2 italic">Candidacy Brief</h2>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest underline decoration-primary decoration-4 underline-offset-4">Secure Professional Submission</p>
                                            </div>

                                            <form x-data="{ submitting: false }" @submit="submitting = true" method="POST" action="{{ route('jobs.apply', $job->id) }}" class="space-y-6">
                                                @csrf
                                                <div class="space-y-3">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1 italic">Professional Artifact (PDF Only)</label>
                                                    <div class="uc-container">
                                                        <input type="hidden" 
                                                               role="uploadcare-uploader" 
                                                               name="resume" 
                                                               required 
                                                               data-public-key="{{ env('UPLOADCARE_PUBLIC_KEY') }}"
                                                               data-tabs="file gdrive dropbox"
                                                               data-input-accept-types=".pdf"
                                                               data-max-size="10485760" />
                                                    </div>
                                                </div>

                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Executive Summary (Tell us about you)</label>
                                                    <textarea name="about_me" required rows="3" placeholder="Brief professional overview..." class="w-full bg-slate-50 border-none rounded-[2rem] p-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all"></textarea>
                                                </div>

                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Motivational Brief (Why should we hire you?)</label>
                                                    <textarea name="why_hire" required rows="3" placeholder="Explain your alignment with this node..." class="w-full bg-slate-50 border-none rounded-[2rem] p-6 text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all"></textarea>
                                                </div>

                                                <div class="pt-4">
                                                    <button type="submit" :disabled="submitting" class="w-full h-16 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl shadow-2xl shadow-black/20 hover:bg-black transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                                                        <template x-if="!submitting"><span>Submit Candidacy to Hub</span></template>
                                                        <template x-if="submitting">
                                                            <div class="flex items-center gap-2">
                                                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                                <span>Transmitting Signal...</span>
                                                            </div>
                                                        </template>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}?redirect={{ request()->fullUrl() }}" class="w-full md:w-auto px-12 h-16 bg-primary text-white text-[12px] font-black uppercase tracking-widest rounded-2xl shadow-2xl shadow-primary/20 hover:scale-105 transition-all flex items-center justify-center">
                                    Login to Connect
                                </a>
                                <p class="text-xs font-bold text-slate-400">Authentication required for professional contact</p>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
