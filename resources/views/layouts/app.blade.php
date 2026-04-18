<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scroll-behavior:smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Multiverse Branding 💎 -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('mcv/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="{{ asset('mcv/site.webmanifest') }}">
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
        <link rel="icon" type="image/x-icon" href="/favicon.ico">

        <!-- SEO Intelligence 🔍 -->
        <title>@yield('title', 'MyCollegeVerse | The Academic Multiverse')</title>
        <meta name="description" content="@yield('meta_description', 'The ultimate academic multiverse for students. Share notes, review professors, and join your campus verse.')">
        <meta name="keywords" content="@yield('meta_keywords', 'college notes, professor reviews, campus community, academic identity, BTech notes, AKTU notes, student OS, MyCollegeVerse')">
        <meta name="author" content="MyCollegeVerse Team">
        <meta name="robots" content="index, follow, max-image-preview:large">
        <link rel="canonical" href="{{ url()->current() }}" />

        <!-- Google Search Console Verification 🔎 -->
        <meta name="google-site-verification" content="aAhlYLCkOzDk3VELTlux0gNO8eRUbas3N8I4sEayXaU" />
        
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
            [x-cloak] { display: none !important; }
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #CBD5E1; }
        </style>
        <!-- Alpine.js Engineering ⚙️ -->
        <script defer src="https://unpkg.com/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased text-slate-900 bg-surface" x-data="{ sidebarOpen: $persist(true) }">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5TTR79WQ"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar (Expanded Desktop) -->
            <aside class="hidden lg:flex flex-col bg-white border-r border-slate-200 transition-all duration-500 relative z-40 overflow-hidden" 
                   :class="sidebarOpen ? 'w-72' : 'w-0 border-none shadow-none'">
                <div class="w-72 flex flex-col h-full sidebar-bg shrink-0">
                <div class="px-8 py-8">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/mcv/mycollegeverse.png') }}" class="h-16 md:h-20 w-auto" alt="MyCollegeVerse — Student OS">
                        <span class="font-bold text-xl tracking-tight text-secondary sr-only">CollegeVerse</span>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                    @php
                        $links = [
                            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'Notes Repository', 'route' => 'notes.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['label' => 'Community Hub', 'route' => 'community.index', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                            ['label' => 'Leaderboard', 'route' => 'leaderboard.index', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                            ['label' => 'Perks Hub', 'route' => 'rewards.index', 'icon' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7'],
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
                    <div class="flex items-center gap-6 flex-1 max-w-4xl">
                        <!-- Sidebar Toggle -->
                        <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex w-10 h-10 items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-primary/5 hover:text-primary transition-all border border-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-500" :class="!sidebarOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            </svg>
                        </button>

                        <form action="{{ route('notes.index') }}" method="GET" class="flex-1 relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search notes, colleges, or students..." class="block w-full pl-12 pr-4 h-12 bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-medium">
                        </form>
                    </div>

                    <div class="flex items-center gap-4" x-data="{ 
                        isOpen: false, 
                        loading: false, 
                        notifications: [], 
                        unreadCount: {{ auth()->check() ? auth()->user()->unreadNotifications->count() : 0 }},
                        async fetchNotifications() {
                            if (this.notifications.length > 0 && !this.loading) return;
                            this.loading = true;
                            try {
                                const res = await fetch('{{ route('notifications.index') }}');
                                const data = await res.json();
                                if (data.status === 'success') {
                                    this.notifications = data.notifications;
                                    this.unreadCount = data.unread_count;
                                }
                            } catch (e) { console.error('Signal Hub Failure', e); }
                            this.loading = false;
                        },
                        async markAsRead(id, url) {
                            try {
                                await fetch(`/api/notifications/${id}/read`, {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                });
                                window.location.href = url;
                            } catch (e) { window.location.href = url; }
                        },
                        async markAllRead() {
                            try {
                                await fetch('{{ route('notifications.mark-all-read') }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                });
                                this.unreadCount = 0;
                                this.notifications.forEach(n => n.read_at = new Date());
                            } catch (e) { console.error('Clear Signals Failed', e); }
                        }
                    }">
                        <div class="relative">
                            <button @click="isOpen = !isOpen; if(isOpen) fetchNotifications()" 
                                    class="w-12 h-12 flex items-center justify-center glass rounded-xl text-slate-500 hover:text-primary transition-all relative"
                                    :class="isOpen ? 'bg-slate-50 text-primary' : ''">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <template x-if="unreadCount > 0">
                                    <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse"></span>
                                </template>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="isOpen" 
                                 @click.away="isOpen = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 class="absolute right-0 mt-3 w-80 sm:w-96 glass bg-white/95 backdrop-blur-xl rounded-[2.5rem] border border-slate-100 shadow-2xl overflow-hidden z-[100]"
                                 x-cloak>
                                
                                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Verse Signals</h4>
                                    <button @click="markAllRead()" class="text-[9px] font-black text-primary hover:underline uppercase tracking-widest">Clear All</button>
                                </div>

                                <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                                    <template x-if="loading">
                                        <div class="p-12 text-center space-y-4">
                                            <div class="w-8 h-8 border-4 border-primary/20 border-t-primary rounded-full animate-spin mx-auto"></div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Decrypting Signals...</p>
                                        </div>
                                    </template>

                                    <template x-if="!loading && notifications.length === 0">
                                        <div class="p-12 text-center space-y-3">
                                            <div class="text-3xl opacity-30">📡</div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">Multiverse clear. <br>No incoming academic signals detected.</p>
                                        </div>
                                    </template>

                                    <div class="divide-y divide-slate-50">
                                        <template x-for="n in notifications" :key="n.id">
                                            <div @click="markAsRead(n.id, n.data.action_url)" 
                                                 class="p-6 hover:bg-slate-50 transition-all cursor-pointer group relative"
                                                 :class="!n.read_at ? 'bg-primary/[0.02]' : ''">
                                                
                                                <div class="flex gap-4">
                                                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-xl group-hover:scale-110 transition-transform shrink-0"
                                                         x-text="n.data.icon || '⚡'">
                                                    </div>
                                                    <div class="space-y-1 min-w-0">
                                                        <p class="text-[10px] font-black uppercase tracking-widest text-primary" x-text="n.data.title"></p>
                                                        <p class="text-xs font-bold text-slate-700 leading-tight" x-text="n.data.message"></p>
                                                        <div class="flex items-center gap-3 pt-1">
                                                            <p class="text-[9px] font-bold text-slate-400" x-text="n.created_at"></p>
                                                            <template x-if="n.data.due_date">
                                                                <span class="text-[9px] font-black text-rose-500 uppercase tracking-tight" x-text="'Due: ' + n.data.due_date"></span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <template x-if="!n.read_at">
                                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-primary rounded-full"></div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-50 text-center">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Signal Protocol v1.0 • End-to-End Latency: Minimal</p>
                                </div>
                            </div>
                        </div>

                        <div class="h-10 w-[1px] bg-slate-200 mx-1"></div>

                        @auth
                        <!-- Profile Dropdown (Top Nav) -->
                        <div class="relative" x-data="{ open: false }">
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

                    <!-- Professional Multiverse Footer 🚀 -->
                    <footer class="bg-white border-t border-slate-100 pt-16 pb-12 mt-20 -mx-8 px-8">
                        <div class="max-w-7xl mx-auto">
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12 mb-12 text-left">
                                <!-- Brand Node -->
                                <div class="col-span-2 lg:col-span-1 space-y-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shadow-lg shadow-primary/20">
                                            <span class="text-white font-bold text-base">M</span>
                                        </div>
                                        <span class="font-bold text-lg tracking-tight text-secondary">MyCollegeVerse</span>
                                    </div>
                                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest leading-relaxed">
                                        The high-performance Student OS. Delhi NCR Node.
                                    </p>
                                </div>

                                <!-- Platform Nodes -->
                                <div>
                                    <h4 class="font-black text-slate-900 text-[10px] uppercase tracking-widest mb-6">Platform</h4>
                                    <ul class="space-y-3 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        <li><a href="{{ route('notes.index') }}" class="hover:text-primary transition-colors">Notes Repo</a></li>
                                        <li><a href="/blog" class="hover:text-primary transition-colors">Editorial</a></li>
                                        <li><a href="{{ route('community.index') }}" class="hover:text-primary transition-colors">Community</a></li>
                                        <li><a href="{{ route('colleges.index') }}" class="hover:text-primary transition-colors">Campus Hubs</a></li>
                                    </ul>
                                </div>

                                <!-- Company Nodes -->
                                <div>
                                    <h4 class="font-black text-slate-900 text-[10px] uppercase tracking-widest mb-6">Company</h4>
                                    <ul class="space-y-3 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        <li><a href="{{ route('pages.show', 'about-us') }}" class="hover:text-primary transition-colors">Mission</a></li>
                                        <li><a href="{{ route('pages.careers') }}" class="hover:text-primary transition-colors">Careers</a></li>
                                        <li><a href="{{ route('pages.partner') }}" class="hover:text-primary transition-colors">Partner</a></li>
                                        <li><a href="mailto:mycollegeverse@gmail.com" class="hover:text-primary transition-colors">Contact</a></li>
                                    </ul>
                                </div>

                                <!-- Legal Nodes -->
                                <div>
                                    <h4 class="font-black text-slate-900 text-[10px] uppercase tracking-widest mb-6">Legal</h4>
                                    <ul class="space-y-3 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        <li><a href="{{ route('pages.show', 'privacy-policy') }}" class="hover:text-primary transition-colors italic">Privacy</a></li>
                                        <li><a href="{{ route('pages.show', 'terms-of-service') }}" class="hover:text-primary transition-colors italic">Terms</a></li>
                                        <li><a href="{{ route('pages.faq') }}" class="hover:text-primary transition-colors italic">FAQ</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="pt-8 border-t border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">© 2026 MyCollegeVerse. Organising the Academic Multiverse.</p>
                                <div class="flex gap-4">
                                    @php
                                        $insta = \App\Models\Setting::get('instagram_link', 'https://www.instagram.com/mycollegeverse.xyz/');
                                    @endphp
                                    <a href="{{ $insta }}" target="_blank" class="text-slate-300 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </footer>
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
