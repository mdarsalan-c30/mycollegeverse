<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scroll-behavior:smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Intelligence 🔍 -->
        <title>@yield('title', 'MyCollegeVerse | The Academic Multiverse')</title>
        <meta name="description" content="@yield('meta_description', 'The ultimate academic multiverse for students. Share notes, review professors, and join your campus verse.')">
        <link rel="canonical" href="{{ url()->current() }}" />
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('title', 'MyCollegeVerse | The Academic Multiverse')">
        <meta property="og:description" content="@yield('meta_description', 'The ultimate academic multiverse for students. Share notes, review professors, and join your campus verse.')">
        <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">

        <!-- Twitter Card -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="@yield('title', 'MyCollegeVerse | The Academic Multiverse')">
        <meta property="twitter:description" content="@yield('meta_description', 'The ultimate academic multiverse for students. Share notes, review professors, and join your campus verse.')">
        <meta property="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">

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

        @stack('structured-data')
        @yield('meta')
        @stack('head')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Tailwind CDN -->
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
                            surface: '#F8FAFC',
                        },
                        fontFamily: {
                            sans: ['Plus Jakarta Sans', 'sans-serif'],
                        },
                        boxShadow: {
                            'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                        }
                    }
                }
            }
        </script>

        <style>
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .sidebar-bg {
                background: linear-gradient(180deg, #FFFFFF 0%, #F1F5F9 100%);
            }
            .active-link {
                background: rgba(59, 130, 246, 0.1);
                color: #3B82F6;
                border-right: 4px solid #3B82F6;
            }
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #CBD5E1; }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-900 bg-surface">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5TTR79WQ"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar (Expanded Desktop) -->
            <aside class="hidden lg:flex flex-col w-72 h-full sidebar-bg border-r border-slate-200 shadow-sm relative z-40 transition-all duration-300">
                <div class="px-8 py-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
                            <span class="text-white font-bold text-xl uppercase">M</span>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-secondary">CollegeVerse</span>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                    @php
                        $links = [
                            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'Notes Repository', 'route' => 'notes.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['label' => 'Community Hub', 'route' => 'community.index', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                            ['label' => 'Leaderboard', 'route' => 'leaderboard.index', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                            ['label' => 'Professors', 'route' => 'professors.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                            ['label' => 'Colleges', 'route' => 'colleges.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                            ['label' => 'Messages', 'route' => 'chat.index', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                            ['label' => 'Verse Pipeline', 'route' => 'pipeline.index', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745V20a2 2 0 002 2h14a2 2 0 002-2v-6.745zM16 8V5a2 2 0 00-2-2H10a2 2 0 00-2 2v3m4 6.138V21M7.074 21.33l6.574-6.574m0 0l-6.574-6.574'],
                        ];
                    @endphp

                    @foreach($links as $link)
                        <a href="{{ route($link['route']) }}" class="flex items-center justify-between px-6 py-4 rounded-xl font-semibold transition-all duration-200 {{ Request::routeIs($link['route']) ? 'active-link' : 'text-slate-500 hover:text-primary hover:bg-primary/5' }}">
                            <div class="flex items-center gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                                </svg>
                                {{ $link['label'] }}
                            </div>
                            
                            @if(auth()->check() && $link['label'] == 'Messages' && auth()->user()->unread_messages_count > 0)
                                <span class="bg-primary text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-lg shadow-primary/20">
                                    {{ auth()->user()->unread_messages_count }}
                                </span>
                            @endif

                            @if(auth()->check() && $link['label'] == 'Verse Pipeline' && auth()->user()->unread_pipeline_count > 0)
                                <span class="bg-primary text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-lg shadow-primary/20 animate-pulse">
                                    {{ auth()->user()->unread_pipeline_count }}
                                </span>
                            @endif
                        </a>
@endforeach
                </nav>

                @auth
                <div class="px-4 py-8 mt-auto border-t border-slate-100">
                    <a href="{{ route('profile.show') }}" class="bg-primary/5 p-4 rounded-2xl flex items-center gap-4 hover:bg-primary/10 transition-colors">
                        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate">View Profile</p>
                        </div>
                    </a>
                </div>
                @else
                <div class="px-6 py-8 mt-auto border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Join the community</p>
                    <a href="{{ route('login') }}" class="block w-full text-center py-4 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                        Sign In
                    </a>
                </div>
                @endauth
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 bg-surface overflow-hidden">
                <!-- Header (Always Visible) -->
                <header class="h-24 glass flex items-center justify-between px-8 py-4 relative z-30 shadow-sm shrink-0">
                    <div class="flex-1 max-w-2xl">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" placeholder="Search notes, colleges, or students..." class="block w-full pl-12 pr-4 h-12 bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-medium">
                        </div>
                    </div>

                    <div class="flex items-center gap-4" x-data="{ open: false }">
                        <button class="w-12 h-12 flex items-center justify-center glass rounded-xl text-slate-500 hover:text-primary transition-colors relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-3 right-3 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                        </button>
                        
                        <div class="h-10 w-[1px] bg-slate-200 mx-1"></div>

                        @auth
                        <!-- Profile Dropdown (Top Nav) -->
                        <div class="relative">
                            <button @click="open = !open" class="flex items-center gap-3 p-1 rounded-2xl hover:bg-slate-50 transition-all border-2 border-transparent" :class="open ? 'border-primary/20 bg-slate-50' : ''">
                                <span class="flex-shrink-0">
                                    <img src="{{ Auth::user()->profile_photo_url }}" class="w-10 h-10 rounded-xl shadow-sm border border-white group-hover:scale-105 transition-transform object-cover"/>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 class="absolute right-0 mt-3 w-64 glass bg-white/95 backdrop-blur-xl rounded-[2rem] border border-slate-100 shadow-2xl p-4 space-y-2 z-[100]">
                                <div class="px-6 py-4 border-b border-slate-50">
                                    <p class="text-sm font-black text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Campus Node Alpha</p>
                                </div>
                                
                                <a href="{{ route('profile.show', Auth::user()->username) }}" class="flex items-center gap-4 px-6 py-4 text-slate-600 hover:bg-primary/5 hover:text-primary rounded-2xl transition-all group">
                                    <span class="text-xl group-hover:scale-110 transition-transform">👤</span>
                                    <span class="text-xs font-bold uppercase tracking-widest">My Profile</span>
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-4 w-full px-6 py-4 text-slate-400 hover:bg-red-50 hover:text-red-600 rounded-2xl transition-all group lg:hidden">
                                        <span class="text-xl group-hover:rotate-12 transition-transform">🚪</span>
                                        <span class="text-xs font-bold uppercase tracking-widest text-left">Sign Out</span>
                                    </button>
                                </form>

                                <div class="hidden lg:block pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-center py-4 text-[10px] font-black text-slate-400 hover:text-red-500 uppercase tracking-widest transition-colors border-t border-slate-50">
                                            Quick Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('register') }}" class="hidden sm:block text-xs font-black text-primary uppercase tracking-widest hover:text-secondary transition-colors">
                            Create Account
                        </a>
                        @endauth
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto px-8 py-8 md:pb-8 pb-32">
                    @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-28 right-8 z-[100] glass px-6 py-4 rounded-2xl border-green-500/30 shadow-xl flex items-center gap-4 animate-bounce-subtle">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-800">{{ session('success') }}</p>
                    </div>
                    @endif

                    @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-28 right-8 z-[100] glass px-6 py-4 rounded-2xl border-red-500/30 shadow-xl flex items-center gap-4">
                        <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-800">{{ session('error') }}</p>
                    </div>
                    @endif

                    @if($errors->any())
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" class="fixed top-28 right-8 z-[100] glass px-6 py-4 rounded-2xl border-red-500/30 shadow-xl space-y-2">
                        @foreach($errors->all() as $error)
                        <div class="flex items-center gap-4">
                            <div class="w-6 h-6 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-700">{{ $error }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Mobile Bottom Nav -->
        <div class="lg:hidden fixed bottom-0 left-0 right-0 h-16 bg-white border-t border-slate-200 flex items-center justify-around px-2 z-50">
            @php
                $mobileLinks = [
                    ['route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'colleges.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['route' => 'notes.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['route' => 'chat.index', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                ];
            @endphp

            @foreach($mobileLinks as $mLink)
                <a href="{{ route($mLink['route']) }}" class="flex flex-col items-center gap-0.5 relative transition-all duration-300 {{ Request::routeIs($mLink['route']) ? 'text-primary' : 'text-slate-400' }}">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $mLink['icon'] }}" />
                        </svg>
                        @if($mLink['route'] == 'chat.index' && auth()->check() && auth()->user()->unread_messages_count > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-primary text-white text-[8px] font-black flex items-center justify-center rounded-full shadow-sm ring-2 ring-white">
                                {{ auth()->user()->unread_messages_count }}
                            </span>
                        @endif
                    </div>
                    <span class="text-[9px] font-bold uppercase tracking-tight">{{ Str::replace('.index', '', Str::replace('dashboard', 'home', $mLink['route'])) }}</span>
                </a>
            @endforeach
        </div>
    </body>
</html>
