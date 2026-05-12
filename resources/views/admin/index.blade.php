<x-admin-layout>
    <div class="space-y-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary">Systems Overview</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Real-time multiverse engagement pulse</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="px-6 py-3 bg-white border border-admin-border rounded-2xl shadow-sm flex items-center gap-4">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Master Node: <span class="text-admin-dark">ACTIVE</span></span>
                </div>
            </div>
        </div>

        <!-- Metric Grid (Editorial & Knowledge Flux) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Citizens -->
            <div class="glass p-8 rounded-[2rem] border-white/50 shadow-xl shadow-slate-200/50 group hover:bg-white transition-all">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-blue-500/10 text-admin-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Citizens</p>
                <h3 class="text-4xl font-black text-admin-secondary mt-1">{{ number_format($stats['total_users']) }}</h3>
            </div>

            <!-- Editorial Nodes (Blogs) -->
            <div class="glass p-8 rounded-[2rem] border-white/50 shadow-xl shadow-slate-200/50 group hover:bg-white transition-all">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-emerald-500/10 text-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Editorial Nodes</p>
                <h3 class="text-4xl font-black text-admin-secondary mt-1">{{ number_format($stats['total_blogs']) }}</h3>
            </div>

            <!-- Academic Intel (Guides) -->
            <div class="glass p-8 rounded-[2rem] border-white/50 shadow-xl shadow-slate-200/50 group hover:bg-white transition-all">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-indigo-500/10 text-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Academic Intel Hub</p>
                <h3 class="text-4xl font-black text-admin-secondary mt-1">{{ number_format($stats['total_guides']) }}</h3>
            </div>

            <!-- Knowledge Assets (Notes) -->
            <div class="glass p-8 rounded-[2rem] border-white/50 shadow-xl shadow-slate-200/50 group hover:bg-white transition-all">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-amber-500/10 text-amber-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Knowledge Assets</p>
                <h3 class="text-4xl font-black text-admin-secondary mt-1">{{ number_format($stats['total_notes']) }}</h3>
            </div>

            <!-- Pending Reviews (New 💎) -->
            <div class="glass p-8 rounded-[2rem] border-admin-primary/20 bg-admin-primary/5 shadow-xl shadow-admin-primary/5 group hover:bg-white transition-all">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-admin-primary/10 text-admin-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-admin-primary text-white text-[8px] font-black rounded-full animate-bounce">PENDING</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Feedback Verification</p>
                <h3 class="text-4xl font-black text-admin-secondary mt-1">{{ number_format($stats['pending_reviews']) }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Latest Asset Submissions -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-extrabold text-admin-secondary">Recent Asset Flux</h3>
                    <a href="{{ route('admin.notes') }}" class="text-[10px] font-black text-admin-primary uppercase tracking-widest hover:underline">Moderation Queue</a>
                </div>
                
                <div class="bg-white border border-admin-border rounded-[2.5rem] overflow-hidden shadow-sm">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-admin-border">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Asset Title</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Node</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 italic">
                            @foreach($latestNotes as $note)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400">
                                            📄
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-admin-dark truncate">{{ $note->title }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">By {{ optional($note->user)->name ?? 'Unknown Identity' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-[10px] font-bold text-slate-500 uppercase">{{ optional($note->college)->name ?? 'Global Verse' }}</td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter {{ $note->is_verified ? 'bg-green-500/10 text-green-600' : 'bg-yellow-500/10 text-yellow-600' }}">
                                        {{ $note->is_verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('admin.notes', ['search' => $note->title]) }}" class="p-2 text-slate-400 hover:text-admin-primary transition-all rounded-xl inline-block" title="Deep Scan Asset">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Latest Institutional Feedback 💎 -->
                <div class="flex items-center justify-between mt-12">
                    <h3 class="text-xl font-extrabold text-admin-secondary">Institutional Feedback Flux</h3>
                    <a href="{{ route('admin.reviews') }}" class="text-[10px] font-black text-admin-primary uppercase tracking-widest hover:underline">Verification Hub</a>
                </div>

                <div class="bg-white border border-admin-border rounded-[2.5rem] overflow-hidden shadow-sm">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-admin-border">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">User</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Target</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 italic">
                            @foreach($latestReviews as $review)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-8 py-5">
                                    <p class="text-xs font-black text-admin-dark">{{ optional($review->user)->name }}</p>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase">{{ $review->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="px-8 py-5 text-[10px] font-bold text-slate-500 uppercase">{{ optional($review->college)->name }}</td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter {{ $review->status == 'approved' ? 'bg-green-500/10 text-green-600' : 'bg-amber-500/10 text-amber-600' }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('admin.reviews', ['type' => 'college']) }}" class="p-2 text-slate-400 hover:text-admin-primary transition-all rounded-xl inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Security Alerts -->
            <div class="space-y-6 italic">
                <h3 class="text-xl font-extrabold text-admin-secondary">Sentinel Alerts</h3>
                <div class="space-y-4">
                    @forelse($latestReports as $report)
                    <div class="glass p-6 rounded-3xl border-red-500/5 hover:border-red-500/20 transition-all group shadow-sm">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 bg-red-100/50 rounded-xl flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform">
                                ⚠️
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-[10px] font-black text-red-600 uppercase tracking-widest">Flagged Content</p>
                                    <span class="text-[8px] font-bold text-slate-400">{{ $report->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs font-bold text-admin-dark leading-tight">{{ $report->reason }}</p>
                                <p class="text-[9px] font-medium text-slate-400 mt-2 uppercase">By Citizen: {{ $report->reporter->name ?? 'Sentinel' }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-10 text-center glass rounded-3xl border-dashed border-slate-200">
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">Zero Threats Detected</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
