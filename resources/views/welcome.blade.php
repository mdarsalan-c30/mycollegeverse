<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MyCollegeVerse — Building Academic Identity | The Student OS</title>
        <meta name="description" content="The ultimate academic multiverse for students. Access high-quality BTech notes, AKTU study material, professor reviews, and campus community hubs. Build your academic identity with MyCollegeVerse.">
        <meta name="keywords" content="college notes, BTech notes pdf, AKTU notes, professor reviews, student community, exam preparation, academic identity, MyCollegeVerse, engineering notes">
        <meta name="author" content="MyCollegeVerse">
        <meta name="robots" content="index, follow, max-image-preview:large">
        <link rel="canonical" href="{{ url('/') }}" />

        <!-- Google Search Console Verification 🔎 -->
        <meta name="google-site-verification" content="aAhlYLCkOzDk3VELTlux0gNO8eRUbas3N8I4sEayXaU" />

        <!-- Open Graph / SEO Social -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="MyCollegeVerse — Building Academic Identity | The Student OS">
        <meta property="og:description" content="Access high-quality BTech notes, official AKTU material, and professor reviews in the ultimate student multiverse.">
        <meta property="og:image" content="{{ asset('assets/mcv/og-landing.jpg') }}">

        <!-- Twitter Card -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/') }}">
        <meta property="twitter:title" content="MyCollegeVerse — Building Academic Identity">
        <meta property="twitter:description" content="The absolute College OS for every student. Share notes, review faculty, and dominate your academics.">
        <meta property="twitter:image" content="{{ asset('assets/mcv/og-landing.jpg') }}">

        <!-- Structured Data: Organization (JSON-LD) -->
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Organization",
          "name": "MyCollegeVerse",
          "alternateName": "MCV",
          "url": "https://mycollegeverse.in/",
          "logo": {
            "@type": "ImageObject",
            "url": "https://mycollegeverse.in/assets/mcv/mycollegeverse.png",
            "width": 512,
            "height": 512
          },
          "description": "MyCollegeVerse is India's ultimate academic identity platform for students — share notes, review professors, join campus communities, and access high-quality BTech & AKTU study material.",
          "foundingDate": "2024",
          "areaServed": "IN",
          "knowsAbout": ["BTech Notes", "AKTU Study Material", "Professor Reviews", "Campus Community", "Student Networking", "Engineering Notes"],
          "contactPoint": {
            "@type": "ContactPoint",
            "email": "mycollegeverse@gmail.com",
            "contactType": "customer support",
            "availableLanguage": ["English", "Hindi"]
          },
          "sameAs": [
            "https://www.instagram.com/mycollegeverse.xyz/",
            "https://www.youtube.com/@mcvmycollegeverse160",
            "https://www.linkedin.com/company/mycollegeverse/",
            "https://mycollegeverse.in/"
          ]
        }
        </script>

        <!-- Structured Data: WebSite + Sitelinks Searchbox (JSON-LD) -->
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "MyCollegeVerse",
          "alternateName": "MCV — The Student OS",
          "url": "https://mycollegeverse.in/",
          "potentialAction": {
            "@type": "SearchAction",
            "target": {
              "@type": "EntryPoint",
              "urlTemplate": "https://mycollegeverse.in/notes?search={search_term_string}"
            },
            "query-input": "required name=search_term_string"
          }
        }
        </script>
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

        <!-- Multiverse Branding 💎 -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/mcv/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="{{ asset('assets/mcv/site.webmanifest') }}">
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
        <link rel="icon" type="image/x-icon" href="/favicon.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Tailwind CDN with Custom Config -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
            [x-cloak] { display: none !important; }
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .gradient-text {
                background: linear-gradient(135deg, #3B82F6 0%, #6177A5 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .hero-pattern {
                background-color: #ffffff;
                background-image: radial-gradient(#3b82f61a 1px, transparent 1px);
                background-size: 30px 30px;
            }
            
            @keyframes animate-blob {
                0%, 100% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
            }
            .animate-blob {
                animation: animate-blob 7s infinite;
            }
            .animation-delay-2000 { animation-delay: 2s; }
            .animation-delay-4000 { animation-delay: 4s; }

            .hide-scrollbar::-webkit-scrollbar { display: none; }
            .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            html, body { overflow-x: hidden; width: 100%; position: relative; }
        </style>
    </head>
    <body class="antialiased bg-slate-50 text-slate-900 hero-pattern min-h-screen overflow-x-hidden" x-data="{ mobileMenu: false }">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5TTR79WQ"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50 px-4 md:px-6 py-4">
            <div class="max-w-7xl mx-auto glass rounded-2xl px-4 md:px-6 py-3 flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-1 md:gap-2">
                    <img src="{{ asset('assets/mcv/mycollegeverse.png') }}" class="h-16 md:h-28 w-auto" alt="MyCollegeVerse — Academic Identity Platform">
                    <span class="font-bold text-lg md:text-xl tracking-tight text-secondary sr-only">MyCollegeVerse</span>
                </div>
                
                <div class="hidden md:flex items-center gap-8 font-medium text-slate-600">
                    <a href="{{ route('notes.index') }}" class="hover:text-primary transition-colors">Browse Notes</a>
                    <a href="{{ route('resumes.create') }}" class="hover:text-primary transition-colors">Resume</a>
                    <a href="/blog" class="hover:text-primary transition-colors">Editorial Hub</a>
                    <a href="{{ route('community.index') }}" class="hover:text-primary transition-colors">Community Hub</a>
                    <a href="{{ route('jobs.index') }}" class="hover:text-primary transition-colors">Jobs</a>
                    <a href="{{ route('professors.index') }}" class="hover:text-primary transition-colors">Professors</a>
                    <a href="{{ route('colleges.index') }}" class="hover:text-primary transition-colors">Colleges Hub</a>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-slate-600 font-medium">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-slate-600 font-medium hover:text-primary">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-primary text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg shadow-primary/25 hover:scale-105 transition-transform">Get Started</a>
                                @endif
                            @endauth
                        @endif
                    </div>

                    <!-- Mobile Toggle -->
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-slate-600 hover:text-primary transition-colors focus:outline-none">
                        <svg x-show="!mobileMenu" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <svg x-show="mobileMenu" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Side Menu Overlay (Backdrop) -->
            <div x-show="mobileMenu" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileMenu = false"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[60] md:hidden"
                 x-cloak></div>

            <!-- Mobile Side Menu Drawer -->
            <div x-show="mobileMenu" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="fixed inset-y-0 right-0 w-[85%] max-w-xs glass bg-white/95 backdrop-blur-2xl z-[70] md:hidden shadow-2xl p-8 flex flex-col h-full overflow-y-auto"
                 x-cloak>
                <div class="flex justify-between items-center mb-10">
                    <img src="{{ asset('assets/mcv/mycollegeverse.png') }}" class="h-10 w-auto" alt="MyCollegeVerse — Pocket OS">
                    <button @click="mobileMenu = false" class="p-2 text-slate-400 hover:text-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-6 text-left">
                    <div class="space-y-1">
                        <a href="{{ route('notes.index') }}" class="block py-4 text-slate-600 font-bold text-lg hover:text-primary transition-all border-b border-slate-50">Browse NotesHub</a>
                        <a href="{{ route('resumes.create') }}" class="block py-4 text-slate-600 font-bold text-lg hover:text-primary transition-all border-b border-slate-50">Build Free Resume</a>
                        <a href="/blog" class="block py-4 text-slate-600 font-bold text-lg hover:text-primary transition-all border-b border-slate-50">Editorial Hub</a>
                        <a href="{{ route('community.index') }}" class="block py-4 text-slate-600 font-bold text-lg hover:text-primary transition-all border-b border-slate-50">Community Verse</a>
                        <a href="{{ route('jobs.index') }}" class="block py-4 text-slate-600 font-bold text-lg hover:text-primary transition-all border-b border-slate-50">Carrier Pipeline</a>
                        <a href="{{ route('professors.index') }}" class="block py-4 text-slate-600 font-bold text-lg hover:text-primary transition-all border-b border-slate-50">Professor Intel</a>
                        <a href="{{ route('colleges.index') }}" class="block py-4 text-slate-600 font-bold text-lg hover:text-primary transition-all border-b border-slate-50">Campus Nodes</a>
                    </div>
                    
                    <div class="pt-8 space-y-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-full text-center py-5 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 italic text-lg">Enter Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center py-4 text-slate-600 font-bold hover:text-primary transition-colors">Sign In</a>
                            <a href="{{ route('register') }}" class="block w-full text-center py-5 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 italic text-lg">Create Identity</a>
                        @endauth
                    </div>
                </div>

                <div class="mt-auto pt-10 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">© 2026 MyCollegeVerse</p>
                </div>
        </nav>

        <!-- Hero Section -->
        <main class="pt-24 pb-20 px-2 md:px-6 overflow-hidden">
            <div class="max-w-7xl mx-auto w-full overflow-hidden">
                <div class="flex flex-col lg:grid lg:grid-cols-2 gap-10 items-center">
                <div class="space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary border border-primary/20 font-semibold text-sm mx-auto lg:mx-0">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                        </span>
                        Academic Identity Platform
                    </div>
                    
                    <h1 class="text-xl xs:text-2xl sm:text-4xl md:text-5xl lg:text-7xl font-extrabold leading-tight text-slate-900 tracking-tight break-words px-2">
                        The ultimate <span class="gradient-text">College OS</span><br class="md:hidden"> for every student.
                    </h1>
                    
                    <p class="text-lg md:text-xl text-slate-600 max-w-lg leading-relaxed mx-auto lg:mx-0">
                        Access high-quality structured notes, interact with peers, and review professors. All in one place.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 w-full max-w-[280px] md:max-w-md mx-auto lg:mx-0">
                        <form action="{{ route('notes.index') }}" method="GET" class="flex-1 glass p-2 rounded-2xl flex items-center gap-4 shadow-xl shadow-slate-200/50">
                            <input type="text" name="search" placeholder="Search..." class="bg-transparent border-none focus:ring-0 px-2 w-full text-slate-700 font-medium h-10 text-xs">
                            <button type="submit" class="bg-primary text-white p-2 rounded-xl shadow-lg shadow-primary/30 hover:scale-105 transition-transform flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 justify-center lg:justify-start max-w-[280px] md:max-w-none mx-auto lg:mx-0">
                        <div class="flex -space-x-4">
                            @for ($i = 1; $i <= 4; $i++)
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border-4 border-white overflow-hidden shadow-sm">
                                    <img src="https://ui-avatars.com/api/?name=User+{{$i}}&background=random" alt="User">
                                </div>
                            @endfor
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border-4 border-white bg-slate-100 flex items-center justify-center text-slate-600 font-bold shadow-sm text-xs md:text-base">
                                +{{ $stats['users'] > 100 ? round($stats['users']/100)*100 : $stats['users'] }}
                            </div>
                        </div>
                        <p class="text-[9px] md:text-xs text-slate-500 font-bold uppercase tracking-widest leading-tight">Joined by students in<br class="md:hidden"> {{ $stats['colleges'] }} Campus Hubs</p>
                    </div>
                </div>

                <div class="relative">
                    <!-- Glass Card Mockups -->
                    <div class="glass p-4 md:p-8 rounded-[2rem] shadow-2xl relative z-10 border-white/50 w-full max-w-[300px] md:max-w-full mx-auto scale-95 md:scale-100 origin-center">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-lg md:text-xl text-secondary">Recent Notes</h3>
                            <a href="{{ route('notes.index') }}" class="text-primary font-semibold text-xs md:text-sm">View All</a>
                        </div>
                        
                        <div class="space-y-3 md:space-y-4">
                            @forelse ($recentNotes as $note)
                            <a href="{{ route('notes.show', $note->id) }}" class="block p-3 md:p-4 rounded-xl md:rounded-2xl bg-white/50 border border-white/80 hover:bg-white transition-all group flex items-center justify-between shadow-sm">
                                <div class="flex items-center gap-3 md:gap-4 overflow-hidden">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-primary/10 rounded-lg md:rounded-xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-sm md:text-base font-bold text-slate-800 truncate">{{ $note->title }}</p>
                                        <p class="text-[10px] md:text-xs text-slate-500 truncate">{{ $note->subject->name ?? 'General Info' }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <div class="flex items-center gap-1 bg-amber-50 text-amber-600 px-2 py-0.5 rounded-lg text-[10px] font-black italic">
                                        ⭐ {{ number_format($note->avg_rating, 1) }}
                                    </div>
                                    <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest">{{ $note->reviews()->count() }} Studs</span>
                                </div>
                            </a>
                            @empty
                            <div class="p-8 text-center text-slate-400 font-bold italic">No notes uploaded yet.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Abstract decorative elements (Blobs) - Disabled on mobile for stability -->
                    <div class="absolute inset-0 -z-10 overflow-hidden rounded-3xl pointer-events-none hidden lg:block">
                        <div class="absolute -top-20 -right-20 w-80 h-80 bg-primary/30 rounded-full blur-3xl animate-blob"></div>
                        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-400/20 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-violet-400/20 rounded-full blur-3xl animate-blob animation-delay-4000"></div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Horizontal College Discovery -->
        <section class="max-w-7xl mx-auto px-6 py-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-black text-secondary">Discover Active Hubs</h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Direct access to campus verses</p>
                </div>
                <a href="{{ route('colleges.index') }}" class="text-primary font-bold text-sm hover:underline">See all colleges</a>
            </div>

            <div class="flex gap-6 overflow-x-auto pb-8 hide-scrollbar snap-x snap-mandatory">
                @foreach ($topColleges as $college)
                <a href="{{ route('colleges.show', $college->slug) }}" class="snap-start min-w-[300px] group">
                    <div class="glass p-8 rounded-[2.5rem] border-white/50 hover:bg-white transition-all hover:shadow-xl shadow-glass flex flex-col gap-5 relative overflow-hidden h-full">
                        {{-- Advanced Rating Badge 📡 --}}
                        @php $avg = $college->average_rating; @endphp
                        <div @class([
                            'absolute top-4 right-4 backdrop-blur-2xl border border-white/20 text-white px-3 py-1.5 flex flex-col items-center justify-center rounded-2xl min-w-[3rem] z-10',
                            'bg-emerald-500/40' => is_numeric($avg) && $avg >= 4,
                            'bg-indigo-500/40' => is_numeric($avg) && $avg < 4,
                            'bg-slate-500/40 italic' => !is_numeric($avg)
                        ])>
                            @if(is_numeric($avg))
                                <span class="text-xs font-black leading-none">{{ number_format($avg, 1) }}</span>
                                <span class="text-[7px] font-black uppercase tracking-tighter mt-0.5">Rating</span>
                            @else
                                <span class="text-[8px] font-black uppercase leading-tight text-center">{{ $avg }}</span>
                            @endif
                        </div>

                        <div class="w-14 h-14 rounded-2xl bg-primary/5 flex items-center justify-center text-primary text-2xl group-hover:scale-110 transition-transform">
                            🏫
                        </div>
                        <div class="flex-1">
                            <h4 class="font-black text-slate-800 group-hover:text-primary transition-colors text-lg leading-tight">{{ $college->name }}</h4>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">📍 {{ $college->city ?? $college->location }}</span>
                            </div>
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mt-2">{{ number_format($college->posts_count) }} Active Nodes</p>
                        </div>
                        <div class="flex items-center justify-between mt-2 pt-4 border-t border-slate-50">
                            <span class="text-[10px] font-black text-primary uppercase tracking-widest">Enter Hub Verse</span>
                            <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:translate-x-1 transition-transform">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 py-20">
            <div class="grid lg:grid-cols-2 gap-20">
                <div class="space-y-12">
                     <div class="space-y-4">
                        <h2 class="text-4xl font-extrabold text-slate-900">Trending in the Community</h2>
                        <p class="text-slate-500 max-w-lg">Join the most active discussions happening across all campus verses.</p>
                    </div>

                    <div class="space-y-6">
                        @foreach ($trendingDiscussions as $post)
                        <div class="flex gap-6 items-start group">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-primary group-hover:text-white transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 hover:text-primary transition-colors cursor-pointer">{{ $post->title }}</h4>
                                <div class="flex gap-4 mt-1">
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $post->comments_count }} Replies</span>
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $post->likes->sum('value') }} Points</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <a href="{{ route('community.index') }}" class="inline-block bg-secondary text-white px-8 py-3 rounded-2xl font-black text-sm shadow-xl shadow-secondary/20 border-b-4 border-black/10 active:border-b-0 active:translate-y-1 transition-all">Launch Community Hub</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 h-fit">
                    <!-- Feature 1 -->
                    <div class="glass p-8 rounded-3xl hover:shadow-xl transition-all border-none">
                        <div class="w-14 h-14 bg-blue-500/10 text-primary rounded-2xl flex items-center justify-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-4 text-secondary">Verified Notes</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">No more hunting through chaotic groups. Get structured notes verified by toppers.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="glass p-8 rounded-3xl hover:shadow-xl transition-all border-none translate-y-8">
                        <div class="w-14 h-14 bg-emerald-500/10 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-4 text-secondary">Community First</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ask questions, share insights, and collaborate with students from your college.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Multiverse Intelligence Bento Grid 🌌 -->
        <section class="max-w-7xl mx-auto px-6 py-24 relative overflow-hidden">
            <div class="mb-16 text-center lg:text-left">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight">
                    Beyond just notes. <br class="hidden md:block"> This is your <span class="gradient-text">Academic Command Center.</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <!-- Large Feature: Syllabus Synced Nodes -->
                <div class="md:col-span-4 lg:col-span-4 glass p-10 rounded-[3rem] border-white/80 shadow-glass flex flex-col justify-between group overflow-hidden relative">
                    <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary border border-primary/20 font-bold text-[10px] uppercase tracking-widest mb-6">
                            Syllabus-Synced Nodes
                        </div>
                        <h3 class="text-3xl font-black text-slate-800 mb-6 italic">Precision Knowledge Hub</h3>
                        <p class="text-slate-500 font-medium leading-relaxed max-w-md">
                            Get precise study material mapped to your specific syllabus (<b>AKTU</b>, <b>BTech</b>, <b>DU</b>) with one-click access. No more dead links.
                        </p>
                    </div>

                    <div class="mt-12 flex flex-wrap gap-3 relative z-10">
                        @php $tags = ['DBMS', 'OS', 'Quantum', 'Mathematics', 'AKTU', 'BTech', 'Python']; @endphp
                        @foreach($tags as $tag)
                        <span class="px-4 py-2 glass rounded-xl text-xs font-black text-secondary border-primary/10 animate-pulse shadow-sm hover:scale-110 transition-transform cursor-default">
                            #{{ $tag }}
                        </span>
                        @endforeach
                    </div>
                </div>

                <!-- Small Feature: Faculty Intel -->
                <div class="md:col-span-2 lg:col-span-2 bg-slate-900 p-10 rounded-[3rem] text-white flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-transparent"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-xl mb-6">🧠</div>
                        <h3 class="text-2xl font-black leading-tight italic">Campus <br> Intel</h3>
                    </div>
                    <p class="text-slate-400 text-xs font-medium leading-loose relative z-10 mt-6">
                        Read honest reviews about your faculty and teaching styles before you enter the classroom.
                    </p>
                </div>

                <!-- Small Feature: Karma Identity -->
                <div class="md:col-span-2 lg:col-span-2 glass p-10 rounded-[3rem] border-white/80 shadow-glass flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-xl mb-6 animate-bounce">✨</div>
                        <h3 class="text-2xl font-black text-slate-800 italic">Karma <br> Economy</h3>
                    </div>
                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Share. Earn. Build.</p>
                        <p class="text-slate-400 text-xs mt-2 font-medium">Unlock exclusive career pipelines.</p>
                    </div>
                </div>

                <!-- Large Feature: Multiverse Hub -->
                <div class="md:col-span-4 lg:col-span-4 glass p-10 rounded-[3rem] border-primary/20 bg-primary/5 flex flex-col md:flex-row gap-10 items-center justify-between group overflow-hidden">
                    <div class="flex-1">
                        <h3 class="text-2xl md:text-3xl font-black text-secondary leading-tight mb-4 italic">The Multiverse Advantage</h3>
                        <p class="text-sm text-slate-600 leading-relaxed font-medium mb-6">
                            Whether you're looking for <b>DBMS questions</b>, <b>Revision notes</b>, or <b>Placement reviews</b>, we provide a peer-verified identity platform.
                        </p>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-2xl font-black text-xs shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                            Create Identity
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                    <div class="w-full md:w-1/3 glass p-6 rounded-[2rem] border-white text-center shadow-lg transform group-hover:rotate-2 transition-transform">
                        <p class="text-4xl font-black text-primary mb-1">Join</p>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Thousands of Students</p>
                    </div>
                </div>
            </div>
        <!-- Latest Insights Editorial Matrix 🚀 -->
        @if(count($latestBlogs) > 0)
        <section class="max-w-7xl mx-auto px-6 py-24 border-t border-slate-100">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-black text-secondary tracking-tight italic text-left uppercase">Latest Insights</h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.3em] mt-2 italic">Strategic Intelligence from the Editorial Node</p>
                </div>
                <a href="/blog" class="px-6 py-2 bg-slate-100 text-slate-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary hover:text-white transition-all shadow-sm">View Full Hub</a>
            </div>

            <div class="flex gap-8 overflow-x-auto pb-10 hide-scrollbar snap-x snap-mandatory">
                @foreach($latestBlogs as $blog)
                <a href="{{ route('blogs.show', $blog->slug) }}" class="snap-start min-w-[280px] md:min-w-[350px] group bg-white p-4 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500">
                    <div class="aspect-[4/3] rounded-[2rem] overflow-hidden mb-6 relative">
                        <img src="{{ $blog->featured_image ? (str_contains($blog->featured_image, 'http') ? $blog->featured_image : 'https://ik.imagekit.io/studycubsfranchise/' . $blog->featured_image) : 'https://images.unsplash.com/photo-1523050338692-7b83b907024f?auto=format&fit=crop&q=80' }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $blog->title }}">
                        <div class="absolute top-4 left-4">
                            <span class="px-4 py-1.5 bg-white/95 backdrop-blur-md text-[9px] font-black text-secondary rounded-full uppercase tracking-widest border border-white/50">{{ optional($blog->category)->name ?? 'Intelligence' }}</span>
                        </div>
                    </div>
                    <div class="px-2 pb-2">
                        <h3 class="text-lg font-black text-secondary leading-tight group-hover:text-primary transition-colors min-h-[3rem] line-clamp-2 uppercase tracking-tighter">{{ $blog->title }}</h3>
                        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-50">
                            <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-400">{{ substr($blog->author->name ?? 'A', 0, 1) }}</div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Recent' }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <section class="max-w-7xl mx-auto px-6 py-20 text-center space-y-8">
            <h2 class="text-4xl font-black text-slate-900">Ready to enter your <span class="gradient-text">Academic Multiverse?</span></h2>
            <div class="flex flex-wrap justify-center gap-6">
                <a href="{{ route('register') }}" class="bg-primary text-white px-10 py-4 rounded-2xl font-black shadow-2xl shadow-primary/20 hover:scale-105 transition-transform">Create My Account</a>
                <a href="{{ route('colleges.index') }}" class="glass text-secondary px-10 py-4 rounded-2xl font-black">Browse All Colleges</a>
            </div>
        </section>

        <footer class="bg-white border-t border-slate-200 pt-20 pb-10">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12 mb-16">
                    <!-- Brand Column -->
                    <div class="col-span-2 lg:col-span-2 space-y-6">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('assets/mcv/mycollegeverse.png') }}" class="h-16 w-auto" alt="MyCollegeVerse — Building Academic Identity">
                        </div>
                        <p class="text-slate-500 text-sm leading-relaxed max-w-xs">
                            The ultimate Academic Multiverse for students. Organising the world's college resources for a high-performance future.
                        </p>
                        <div class="flex gap-4">
                            @php
                                $insta = \App\Models\Setting::get('instagram_link', 'https://www.instagram.com/mycollegeverse.xyz/');
                                $reddit = \App\Models\Setting::get('reddit_link', '#');
                                $x_link = \App\Models\Setting::get('x_link', '#');
                                $yt = \App\Models\Setting::get('youtube_link', '#');
                                $email = \App\Models\Setting::get('contact_email', 'mycollegeverse@gmail.com');
                            @endphp
                            <a href="{{ $insta }}" target="_blank" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Platform Nodes -->
                    <div>
                        <h4 class="font-black text-slate-900 text-xs uppercase tracking-[0.2em] mb-8 italic">Platform</h4>
                        <ul class="space-y-4 text-sm font-bold text-slate-500">
                            <li><a href="{{ route('notes.index') }}" class="hover:text-primary transition-colors">Digital Identity Hub</a></li>
                            <li><a href="{{ route('resumes.create') }}" class="hover:text-primary transition-colors font-black text-primary">Build Free Resume</a></li>
                            <li><a href="/blog" class="hover:text-primary transition-colors">Editorial Hub</a></li>
                            <li><a href="{{ route('community.index') }}" class="hover:text-primary transition-colors">Community Verse</a></li>
                            <li><a href="{{ route('colleges.index') }}" class="hover:text-primary transition-colors">Campus Nodes</a></li>
                            <li><a href="{{ route('professors.index') }}" class="hover:text-primary transition-colors">Professor Intel</a></li>
                        </ul>
                    </div>

                    <!-- Company Nodes -->
                    <div>
                        <h4 class="font-black text-slate-900 text-xs uppercase tracking-[0.2em] mb-8 italic">Company</h4>
                        <ul class="space-y-4 text-sm font-bold text-slate-500">
                            <li><a href="{{ route('pages.show', 'about-us') }}" class="hover:text-primary transition-colors">Mission Hub</a></li>
                            <li><a href="{{ route('pages.careers') }}" class="hover:text-primary transition-colors">Careers / Interns</a></li>
                            <li><a href="{{ route('pages.partner') }}" class="hover:text-primary transition-colors">Partner with Us</a></li>
                            <li><a href="{{ route('pages.faq') }}" class="hover:text-primary transition-colors">Base Support</a></li>
                            <li><a href="mailto:{{ $email }}" class="hover:text-primary transition-colors">Contact Base</a></li>
                        </ul>
                    </div>

                    <!-- Legal Nodes -->
                    <div>
                        <h4 class="font-black text-slate-900 text-xs uppercase tracking-[0.2em] mb-8 italic">Legal</h4>
                        <ul class="space-y-4 text-sm font-bold text-slate-500">
                            <li><a href="{{ route('pages.show', 'privacy-policy') }}" class="hover:text-primary transition-colors italic">Privacy Protocol</a></li>
                            <li><a href="{{ route('pages.show', 'terms-of-service') }}" class="hover:text-primary transition-colors italic">Terms of Use</a></li>
                            <li><a href="{{ route('pages.show', 'cookies-policy') }}" class="hover:text-primary transition-colors italic">Cookie Sync</a></li>
                        </ul>
                    </div>
                </div>

                <div class="pt-10 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">© 2026 MyCollegeVerse. All rights reserved. Building the Student OS of India.</p>
                    <div class="flex gap-8 text-[10px] font-black text-slate-300 uppercase tracking-widest">
                        <span>Delhi NCR Node</span>
                        <span>Multi-Campus Network</span>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
