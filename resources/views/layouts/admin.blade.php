<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Control Tower | MyCollegeVerse Admin')</title>

        <!-- Multiverse Branding 💎 -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('mcv/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('mcv/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('mcv/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('mcv/site.webmanifest') }}">
        <link rel="shortcut icon" href="{{ asset('mcv/favicon.ico') }}">

        <!-- Analytics Infrastructure 🛡️ -->
        <!-- We keep the tracking code even in admin to monitor system performance -->
        @include('partials.tracking')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            admin: {
                                primary: '#3B82F6',
                                secondary: '#1E293B',
                                border: '#E2E8F0',
                                surface: '#F8FAFC',
                                dark: '#0F172A',
                            }
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
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .sidebar-link-active {
                background: rgba(59, 130, 246, 0.08);
                color: #3B82F6;
                border-left: 4px solid #3B82F6;
            }
            /* Professional Scrollbar */
            .custom-scrollbar::-webkit-scrollbar { width: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { 
                background: #CBD5E1; 
                border-radius: 20px;
                border: 1px solid white;
            }
            .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #94A3B8; }
            
            /* Indicate more content */
            .nav-mask {
                mask-image: linear-gradient(to bottom, black 85%, transparent 100%);
                -webkit-mask-image: linear-gradient(to bottom, black 85%, transparent 100%);
            }
        </style>
        @stack('head')
    </head>
    <body class="h-full font-sans antialiased text-admin-dark bg-admin-surface overflow-hidden">
        <div class="flex h-screen w-full relative">
            <!-- Admin Sidebar (The Command Nexus) -->
            <aside class="hidden lg:flex flex-col w-72 h-full bg-white border-r border-admin-border relative z-40">
                <div class="px-8 py-8 shrink-0">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/mcv/mycollegeverse.png') }}" class="h-16 md:h-20 w-auto" alt="MyCollegeVerse — Admin Terminal">
                        <span class="sr-only">AdminVerse Control Tower</span>
                    </div>
                </div>

                <nav class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar nav-mask italic pb-10">
                    @php
                        $adminLinks = [
                            ['label' => 'Overview', 'route' => 'admin.dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                            ['label' => 'Citizens', 'route' => 'admin.users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                            ['label' => 'Knowledge', 'route' => 'admin.notes', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['label' => 'Verse Feed', 'route' => 'admin.community', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                            ['label' => 'Chat Monitor', 'route' => 'admin.chat', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                            ['label' => 'Campus Hubs', 'route' => 'admin.colleges', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                            ['label' => 'Academic Paths', 'route' => 'admin.courses.index', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['label' => 'Subject Nodes', 'route' => 'admin.subjects.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                            ['label' => 'Faculty Registry', 'route' => 'admin.professors', 'icon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
                            ['label' => 'Feedback Hub', 'route' => 'admin.reviews', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                            ['label' => 'Resolution Center', 'route' => 'admin.reports', 'icon' => 'M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9'],
                            ['label' => 'Analytics', 'route' => 'admin.analytics', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                            ['label' => 'System Command', 'route' => 'admin.settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                            ['label' => 'Editorial Hub', 'route' => 'admin.blogs.index', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['label' => 'Rewards Hub', 'route' => 'admin.rewards.index', 'icon' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7'],
                            ['label' => 'Startup Hub', 'route' => 'admin.startup.index', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                            ['label' => 'Pages Hub', 'route' => 'admin.pages.index', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                            ['label' => 'Command Admins', 'route' => 'admin.admins', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                        ];
                    @endphp

                    @foreach($adminLinks as $link)
                        <a href="{{ Route::has($link['route']) ? route($link['route']) : '#' }}" class="flex items-center gap-4 px-6 py-4 rounded-r-2xl font-bold transition-all {{ request()->routeIs($link['route']) ? 'sidebar-link-active' : 'text-slate-400 hover:text-admin-primary hover:bg-admin-primary/5' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                            </svg>
                            <span class="text-[11px] uppercase tracking-widest">{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="px-6 py-6 border-t border-slate-50 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-admin-secondary text-white flex items-center justify-center font-black text-xs">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[9px] font-black text-admin-dark truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[7px] font-bold text-admin-primary uppercase tracking-[0.2em] opacity-80">Master Authority</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-slate-300 hover:text-red-500 transition-colors" title="Close Terminal">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Control Node -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-admin-surface">
                <!-- Admin Header -->
                <header class="h-24 glass flex items-center justify-between px-10 shrink-0 z-30 shadow-sm border-b border-admin-border/50">
                    <div class="flex-1 max-w-2xl">
                        <div class="relative group">
                            <input type="text" placeholder="Search across all campus nodes, users, and assets..." class="w-full h-12 bg-white/60 border border-admin-border rounded-2xl px-6 pl-14 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                            <div class="absolute left-5 top-3.5 text-slate-300 group-focus-within:text-admin-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 divide-x divide-slate-100 italic">
                        <x-verse-signal />
                        
                        <div class="flex items-center gap-4 pl-6 first:pl-0">
                            <p class="text-[8px] font-black text-admin-dark/40 uppercase tracking-[0.2em] text-right">System Status: <br> <span class="text-green-500">Node Active</span></p>
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse shadow-lg shadow-green-500/50"></div>
                        </div>
                    </div>
                </header>

                <!-- Command Portal -->
                <main class="flex-1 overflow-y-auto px-10 py-10 custom-scrollbar relative">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
