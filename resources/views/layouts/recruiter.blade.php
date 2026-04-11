<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scroll-behavior:smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Recruiter Dashboard | VerseOS Pipeline')</title>
        <meta name="description" content="@yield('meta_description', 'High-fidelity talent scouting and campus integration console for recruitment nodes.')">
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
                            secondary: '#1E293B',
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
                background: rgba(255, 255, 255, 0.4);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.5);
            }
            .sidebar-bg {
                background: rgba(15, 23, 42, 0.02);
            }
            .active-link {
                background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0) 100%);
                color: #3B82F6;
                border-left: 4px solid #3B82F6;
            }
            .nav-link:hover:not(.active-link) {
                background: rgba(255, 255, 255, 0.6);
                transform: translateX(4px);
            }
            ::-webkit-scrollbar { width: 4px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-[#F8FAFC]">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5TTR79WQ"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <div class="flex h-screen overflow-hidden">
            <!-- Recruiter Sidebar -->
            <aside class="hidden lg:flex flex-col w-72 h-full bg-white border-r border-slate-100 relative z-40 transition-all duration-300">
                <div class="px-10 py-12">
                    <div class="flex items-center gap-3 group cursor-pointer">
                        <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-2xl shadow-slate-900/20 group-hover:scale-110 transition-transform">
                            <span class="font-black text-xl italic leading-none">P</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-black text-lg tracking-tight text-slate-900">Pipeline <span class="text-primary">OS</span></span>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] -mt-1">Recruiter Hub</span>
                        </div>
                    </div>
                </div>

                <nav x-data="{ 
                        activeHash: window.location.hash || '#overview',
                        init() {
                            window.addEventListener('hashchange', () => {
                                this.activeHash = window.location.hash || '#overview';
                            });
                        }
                    }" 
                    class="flex-1 px-4 space-y-1.5 overflow-y-auto pt-4">
                    @php
                        $links = [
                            ['label' => 'Overview', 'route' => 'recruiter.dashboard', 'hash' => '#overview', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                            ['label' => 'Talent Scouts', 'route' => 'recruiter.dashboard', 'hash' => '#talent', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                            ['label' => 'Active Roles', 'route' => 'recruiter.dashboard', 'hash' => '#postings', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745V20a2 2 0 002 2h14a2 2 0 002-2v-6.745zM16 8V5a2 2 0 00-2-2H10a2 2 0 00-2 2v3m4 6.138V21M7.074 21.33l6.574-6.574m0 0l-6.574-6.574'],
                            ['label' => 'Nexus Comms', 'route' => 'chat.index', 'hash' => '', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                            ['label' => 'Integration', 'route' => 'recruiter.dashboard', 'hash' => '#integration', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                        ];
                    @endphp

                    @foreach($links as $link)
                        <a href="{{ $link['route'] == '#' ? '#' : (Route::has($link['route']) ? route($link['route']) . $link['hash'] : '#') }}" 
                           :class="activeHash === '{{ $link['hash'] }}' || (activeHash === '' && '{{ $link['hash'] }}' === '#overview') ? 'active-link' : 'text-slate-400 hover:text-slate-900'"
                           class="nav-link group flex items-center gap-4 px-8 py-4 rounded-r-[2rem] font-bold transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 :class="activeHash === '{{ $link['hash'] }}' || (activeHash === '' && '{{ $link['hash'] }}' === '#overview') ? 'text-primary' : 'text-slate-300 group-hover:text-slate-600'"
                                 class="w-5 h-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                            </svg>
                            <span class="text-[11px] uppercase tracking-[0.2em]">{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="px-6 py-10 mt-auto border-t border-slate-50 space-y-4">
                    <div class="bg-slate-50 rounded-2xl p-4 flex items-center gap-4">
                        <img src="{{ Auth::user()->profile_photo_url }}" class="w-10 h-10 rounded-xl object-cover border-2 border-white shadow-sm" />
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-black text-slate-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest truncate">{{ Auth::user()->company_name }}</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full h-12 bg-white border border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] rounded-xl flex items-center justify-center gap-3 hover:text-red-500 hover:border-red-100 hover:bg-red-50 transition-all group">
                            <span class="text-sm group-hover:rotate-12 transition-transform">🚪</span>
                            Terminal Session
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#F8FAFC]">
                <header class="h-24 glass flex items-center justify-between px-12 shrink-0 z-30 shadow-sm">
                    <div class="flex-1 max-w-2xl">
                        <div class="relative group">
                            <input type="text" placeholder="Scout verified student talent or campus nodes..." class="w-full h-12 bg-white/60 border border-slate-100 rounded-2xl px-6 pl-14 text-xs font-bold focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all">
                            <div class="absolute left-5 top-3.5 text-slate-300 group-focus-within:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-8 pl-8 border-l border-slate-100 ml-8">
                        <button class="relative w-12 h-12 glass rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            <span class="absolute top-3.5 right-3.5 w-2 h-2 bg-primary rounded-full ring-2 ring-white"></span>
                        </button>
                        
                        <div class="text-right hidden sm:block">
                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-widest leading-none">Verified Partner</p>
                            <p class="text-[8px] font-black text-primary uppercase tracking-[0.2em] mt-1">{{ date('l, d M') }}</p>
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-y-auto px-12 py-10 relative">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
