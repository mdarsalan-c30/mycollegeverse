<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', $title ?? config('app.name'))</title>
        <meta name="description" content="@yield('meta_description', 'Explore college campuses, verified notes, and faculty reviews in the MyCollegeVerse academic multiverse.')">
        @yield('meta')
        
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
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5TTR79WQ"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
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

        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-8 right-8 z-[100] bg-white border border-green-100 shadow-2xl p-6 rounded-3xl flex items-center gap-4 animate-bounce-subtle">
            <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Success</p>
                <p class="text-xs font-bold text-slate-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" class="fixed top-8 right-8 z-[100] bg-white border border-red-100 shadow-2xl p-6 rounded-3xl flex items-center gap-4">
            <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Protocol Denied</p>
                <p class="text-xs font-bold text-slate-700">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if(session('info'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" class="fixed top-8 right-8 z-[100] bg-white border border-blue-100 shadow-2xl p-6 rounded-3xl flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Observation Log</p>
                <p class="text-xs font-bold text-slate-700">{{ session('info') }}</p>
            </div>
        </div>
        @endif
    </body>
</html>
