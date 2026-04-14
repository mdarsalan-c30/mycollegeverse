<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scroll-behavior:smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Editorial Hub | MyCollegeVerse')</title>
        <meta name="description" content="@yield('meta_description', 'Deep academic insights and campus strategy from the MyCollegeVerse editorial team.')">
        <link rel="canonical" href="{{ url()->current() }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#3B82F6',
                            secondary: '#1E293B',
                            article: '#334155',
                        },
                        fontFamily: {
                            sans: ['Plus Jakarta Sans', 'sans-serif'],
                        },
                    }
                }
            }
        </script>

        <style>
            .reading-container { max-width: 800px; margin-left: auto; margin-right: auto; }
            .nav-blur {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
            }
            .blog-content { font-size: 1.125rem; line-height: 1.8; color: #334155; }
            .blog-content h1, .blog-content h2, .blog-content h3 { color: #0f172a; font-weight: 800; margin-top: 2rem; margin-bottom: 1rem; line-height: 1.2; }
            .blog-content p { margin-bottom: 1.5rem; }
            .blog-content ul, .blog-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
            .blog-content li { margin-bottom: 0.5rem; }
            .blog-content blockquote { border-left: 4px solid #3B82F6; padding-left: 1.5rem; font-style: italic; color: #475569; margin: 2rem 0; }
        </style>
        @stack('head')
    </head>
    <body class="font-sans antialiased bg-white text-slate-900 border-t-4 border-primary">
        <!-- Minimal Header Node -->
        <nav class="sticky top-0 z-50 nav-blur border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('assets/mcv/mycollegeverse.png') }}" class="h-12 w-auto group-hover:scale-105 transition-transform" alt="MyCollegeVerse">
                    <span class="font-black text-xl tracking-tighter text-secondary hidden sm:block">Editorial</span>
                </a>

                <div class="flex items-center gap-8">
                    <a href="/blog" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors">All Articles</a>
                    <a href="/notes" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors">Study Notes</a>
                    @auth
                        <a href="/dashboard" class="px-6 py-2.5 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-black transition-all">Go to Dash</a>
                    @else
                        <a href="/login" class="px-6 py-2.5 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">Get Started</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Reading Node -->
        <main class="min-h-screen">
            {{ $slot }}
        </main>

        <!-- Minimal Footer -->
        <footer class="bg-slate-50 border-t border-slate-100 py-20">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <img src="{{ asset('assets/mcv/mycollegeverse.png') }}" class="h-10 w-auto mx-auto mb-8 opacity-50 grayscale" alt="MyCollegeVerse">
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em]">&copy; {{ date('Y') }} MyCollegeVerse Multiverse. All rights reserved.</p>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
