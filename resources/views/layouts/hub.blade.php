<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Academic Hub | MyCollegeVerse')</title>
        <meta name="description" content="@yield('meta_description', 'The ultimate repository for syllabi, college guides, and academic notices.')">
        <meta name="keywords" content="@yield('meta_keywords', 'syllabus, university guide, academic notice, college resources')">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        @stack('structured-data')

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
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
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .hero-pattern {
                background-color: #ffffff;
                background-image: radial-gradient(#3b82f61a 1px, transparent 1px);
                background-size: 30px 30px;
            }
            .gradient-text {
                background: linear-gradient(135deg, #3B82F6 0%, #6366F1 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
        </style>
        @stack('head')
    </head>
    <body class="antialiased bg-slate-50 text-slate-900 hero-pattern min-h-screen">
        <!-- Navigation (Top Nav) -->
        <nav class="fixed top-0 w-full z-50 px-6 py-4">
            <div class="max-w-7xl mx-auto glass rounded-[2rem] px-8 py-3 flex justify-between items-center shadow-xl">
                <div class="flex items-center gap-10">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
                            <span class="text-white font-bold text-xl">M</span>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-secondary">Verse Hub</span>
                    </a>
                    
                    <div class="hidden lg:flex items-center gap-8 font-black text-[10px] uppercase tracking-widest text-slate-400">
                        <a href="{{ route('guides.index') }}" class="{{ request()->routeIs('guides.*') ? 'text-primary' : 'hover:text-primary' }} transition-colors">Academic Hub</a>
                        <a href="{{ route('notes.index') }}" class="hover:text-primary transition-colors">Notes Repo</a>
                        <a href="/blog" class="hover:text-primary transition-colors">Editorial</a>
                        <a href="{{ route('jobs.index') }}" class="hover:text-primary transition-colors">Careers</a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-primary transition-all">Dashboard</a>
                        <a href="{{ route('guides.create') }}" class="bg-slate-900 text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:scale-105 transition-transform">Manifest Node</a>
                    @else
                        <a href="{{ route('login') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-primary transition-all">Login</a>
                        <a href="{{ route('register') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary/25 hover:scale-105 transition-all">Join Verse</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="pt-32 min-h-screen">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <!-- Premium Footer -->
        <footer class="bg-white border-t border-slate-100 pt-20 pb-12 mt-20 px-8">
            <div class="max-w-7xl mx-auto text-center">
                <div class="mb-10">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl">🏛️</div>
                    <h2 class="text-xl font-black text-slate-900 uppercase tracking-widest mb-2">Academic Multiverse Node</h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Protocol v1.0 • Verified Academic Repository</p>
                </div>
                
                <div class="pt-8 border-t border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">© 2026 MyCollegeVerse. Organising the Academic Multiverse.</p>
                    <div class="flex gap-6 text-[9px] font-black uppercase tracking-widest text-slate-400">
                        <a href="/privacy-policy" class="hover:text-primary transition-colors">Privacy</a>
                        <a href="/terms-of-service" class="hover:text-primary transition-colors">Terms</a>
                        <a href="mailto:mycollegeverse@gmail.com" class="hover:text-primary transition-colors">Contact Node</a>
                    </div>
                </div>
            </div>
        </footer>
        @stack('scripts')
    </body>
</html>
