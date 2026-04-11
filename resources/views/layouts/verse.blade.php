<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', $title ?? config('app.name'))</title>
        <meta name="description" content="@yield('meta_description', 'Explore college campuses, verified notes, and faculty reviews in the MyCollegeVerse academic multiverse.')">
        @yield('meta')
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#2563EB',
                            secondary: '#0F172A',
                            accent: '#6366F1',
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
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
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .sidebar-link-active {
                background: #EEF2FF;
                color: #2563EB;
            }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            
            ::-webkit-scrollbar { width: 5px; height: 5px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-50 lg:overflow-hidden">
        <div class="flex h-screen w-full relative">
            {{ $slot }}

            <!-- Mobile Bottom Nav -->
            <div class="lg:hidden fixed bottom-0 left-0 right-0 h-16 bg-white border-t border-slate-200 flex items-center justify-around px-2 z-50">
                @foreach([
                    ['icon' => '🏠', 'label' => 'Home', 'route' => 'dashboard'],
                    ['icon' => '🏛️', 'label' => 'Colleges', 'route' => 'colleges.index'],
                    ['icon' => '📄', 'label' => 'Archive', 'route' => 'notes.index'],
                    ['icon' => '🤝', 'label' => 'Verse', 'route' => 'community.index']
                ] as $item)
                <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                   class="flex flex-col items-center gap-0.5 transition-all duration-300 {{ request()->routeIs($item['route']) ? 'text-primary' : 'text-slate-400' }}">
                    <span class="text-xl">{{ $item['icon'] }}</span>
                    <span class="text-[9px] font-bold uppercase tracking-tight">{{ $item['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </body>
</html>
