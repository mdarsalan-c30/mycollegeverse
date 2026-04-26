<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Career Hub — Verse Jobs</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Tailwind CDN with Custom Config -->
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
        </style>
    </head>
    <body class="antialiased bg-slate-50 text-slate-900 hero-pattern min-h-screen">
        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50 px-6 py-4">
            <div class="max-w-7xl mx-auto glass rounded-2xl px-6 py-3 flex justify-between items-center shadow-sm">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
                        <span class="text-white font-bold text-xl">M</span>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-secondary">Verse Careers</span>
                </a>
                
                <div class="hidden md:flex items-center gap-8 font-medium text-slate-600">
                    <a href="{{ route('notes.index') }}" class="hover:text-primary transition-colors">Notes</a>
                    <a href="{{ route('community.index') }}" class="hover:text-primary transition-colors">Community</a>
                    <a href="{{ route('jobs.index') }}" class="text-primary font-bold">Jobs Hub</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg hover:scale-105 transition-transform">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 font-medium hover:text-primary">Login</a>
                        <a href="{{ route('register') }}" class="bg-primary text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg shadow-primary/25 hover:scale-105 transition-transform">Join Verse</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="pt-32 pb-20 px-6">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="mb-16">
                    <h1 class="text-5xl font-black text-slate-900 tracking-tight mb-4">Discovery Board</h1>
                    <p class="text-lg font-medium text-slate-500 max-w-2xl">Broadcast your academic leverage. Explore curated opportunities from corporate nodes verified on the Verse network.</p>
                </div>

                <!-- Search & Filters -->
                <div class="space-y-6 mb-12">
                    <!-- Search Bar -->
                    <form action="{{ route('jobs.index') }}" method="GET" class="relative max-w-2xl">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by job title or description..." 
                               class="w-full h-16 bg-white border border-slate-200 rounded-[1.5rem] px-14 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-sm font-medium shadow-sm">
                        <svg class="absolute left-5 top-5.5 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        @if(request('filter'))
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                        @endif
                        <button type="submit" class="absolute right-3 top-3 px-6 h-10 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">Search</button>
                    </form>

                    <!-- Filters -->
                    <div class="flex flex-wrap gap-4">
                         <a href="{{ route('jobs.index', array_merge(request()->query(), ['filter' => null])) }}" 
                            class="{{ !request('filter') ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'glass text-slate-600 hover:bg-white' }} px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all">
                            All Streams
                         </a>
                         <a href="{{ route('jobs.index', array_merge(request()->query(), ['filter' => 'remote'])) }}" 
                            class="{{ request('filter') === 'remote' ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'glass text-slate-600 hover:bg-white' }} px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all">
                            Remote Only
                         </a>
                         <a href="{{ route('jobs.index', array_merge(request()->query(), ['filter' => 'internship'])) }}" 
                            class="{{ request('filter') === 'internship' ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'glass text-slate-600 hover:bg-white' }} px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all">
                            Internships
                         </a>
                         <a href="{{ route('jobs.index', array_merge(request()->query(), ['filter' => 'fulltime'])) }}" 
                            class="{{ request('filter') === 'fulltime' ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'glass text-slate-600 hover:bg-white' }} px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all">
                            Full-Time
                         </a>
                    </div>
                </div>

                <!-- Jobs Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($jobs as $job)
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all group relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[5rem] -mr-16 -mt-16 group-hover:scale-110 transition-transform"></div>
                        
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-8">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-2xl shadow-inner group-hover:bg-primary/10 transition-colors">
                                    @if($job->type === 'Internship') 🎓 @else 💼 @endif
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black rounded-lg uppercase tracking-widest">{{ $job->type }}</span>
                                    @if($job->target_college_id)
                                    <div class="mt-2 flex items-center justify-end gap-1">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                        <span class="text-[8px] font-black text-amber-600 uppercase tracking-tighter">Campus Targeted</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-4 mb-10">
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 group-hover:text-primary transition-colors leading-tight">{{ $job->title }}</h3>
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-1">{{ $job->recruiter->company_name ?? 'Corporation Unknown' }}</p>
                                </div>
                                
                                <div class="flex flex-wrap gap-2">
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-50 px-3 py-1 rounded-full">{{ $job->location ?? 'Global Node' }}</span>
                                    @if($job->salary_range)
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-50 px-3 py-1 rounded-full">{{ $job->salary_range }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-8 border-t border-slate-50">
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $job->created_at->diffForHumans() }}</span>
                                <a href="{{ route('jobs.show', $job) }}" class="px-8 h-12 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-black/10 hover:scale-105 transition-all flex items-center justify-center">
                                    Inspect Role
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-32 flex flex-col items-center justify-center text-center">
                        <div class="text-6xl mb-6 opacity-20">🔭</div>
                        <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-2">Signal Silence</h3>
                        <p class="text-slate-500 max-w-sm">No career nodes are currently broadcasting matching roles. Check back shortly as corporate nodes initialize.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-16">
                    {{ $jobs->links() }}
                </div>
            </div>
        </main>

        <footer class="py-20 border-t border-slate-100 mt-20 bg-white/50">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Powered by Verse OS — Professional Protocol</p>
            </div>
        </footer>
    </body>
</html>
