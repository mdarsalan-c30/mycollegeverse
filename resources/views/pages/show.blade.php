<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $page->title }} — MyCollegeVerse</title>
        <meta name="description" content="{{ $page->meta_description }}">

        <!-- Multiverse Branding 💎 -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/mcv/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/mcv/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/mcv/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('assets/mcv/site.webmanifest') }}">

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
            .hero-pattern {
                background-color: #ffffff;
                background-image: radial-gradient(#3b82f61a 1px, transparent 1px);
                background-size: 30px 30px;
            }
            .prose h1, .prose h2, .prose h3 { color: #1e293b; font-weight: 800; margin-top: 2rem; }
            .prose p { color: #475569; line-height: 1.8; margin-bottom: 1.5rem; }
            .prose ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.5rem; color: #475569; }
        </style>
    </head>
    <body class="antialiased bg-slate-50 text-slate-900 hero-pattern min-h-screen" x-data="{ mobileMenu: false }">
        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50 px-4 md:px-6 py-4">
            <div class="max-w-7xl mx-auto glass rounded-2xl px-4 md:px-6 py-3 flex justify-between items-center shadow-sm">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('assets/mcv/mycollegeverse.png') }}" class="h-10 md:h-14 w-auto" alt="MyCollegeVerse">
                </a>
                
                <div class="hidden md:flex items-center gap-8 font-medium text-slate-600">
                    <a href="{{ route('notes.index') }}" class="hover:text-primary transition-colors">Browse Notes</a>
                    <a href="{{ route('community.index') }}" class="hover:text-primary transition-colors">Community Hub</a>
                    @guest
                        <a href="{{ route('login') }}" class="text-slate-600 font-medium hover:text-primary">Login</a>
                        <a href="{{ route('register') }}" class="bg-primary text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg shadow-primary/25 hover:scale-105 transition-transform">Get Started</a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="text-primary font-bold">Dashboard</a>
                    @endguest
                </div>

                <!-- Mobile Toggle -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-slate-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
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
                    <img src="{{ asset('assets/mcv/mycollegeverse.png') }}" class="h-10 w-auto" alt="MCV">
                    <button @click="mobileMenu = false" class="p-2 text-slate-400 hover:text-primary">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-6">
                    <a href="{{ route('notes.index') }}" class="block py-4 text-slate-600 font-bold text-lg border-b border-slate-50">Browse NotesHub</a>
                    <a href="{{ route('community.index') }}" class="block py-4 text-slate-600 font-bold text-lg border-b border-slate-50">Community Verse</a>
                    @guest
                        <a href="{{ route('login') }}" class="block py-4 text-slate-600 font-bold">Sign In</a>
                        <a href="{{ route('register') }}" class="block w-full text-center py-5 bg-primary text-white font-bold rounded-2xl">Create Identity</a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="block py-4 text-primary font-bold">Enter Dashboard</a>
                    @endguest
                </div>
            </div>
        </nav>

        <main class="pt-32 pb-20 px-4 md:px-6">
            <div class="max-w-4xl mx-auto">
                <header class="mb-12 text-center">
                    <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight mb-4">{{ $page->title }}</h1>
                    <div class="w-20 h-1.5 bg-primary mx-auto rounded-full"></div>
                </header>

                <div class="glass p-8 md:p-12 rounded-[2.5rem] shadow-xl border-white/50 prose max-w-none">
                    {!! nl2br($page->content) !!}
                </div>
            </div>
        </main>

        <footer class="max-w-7xl mx-auto px-6 py-10 border-t border-slate-200 text-center">
            <p class="text-slate-500 text-sm font-medium">© 2026 MyCollegeVerse. Built for Students, by Students.</p>
        </footer>
    </body>
</html>
