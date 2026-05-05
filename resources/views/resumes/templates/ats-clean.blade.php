<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resume->title }} | MyCollegeVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-shadow-none { box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <!-- Floating Toolbar -->
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 no-print">
        <div class="bg-slate-900 text-white px-6 py-4 rounded-3xl shadow-2xl flex items-center gap-6 backdrop-blur-md bg-opacity-90">
            <div class="flex items-center gap-2 pr-6 border-r border-slate-700">
                <span class="text-xs font-black uppercase tracking-widest text-slate-400">Public Link</span>
                <button onclick="copyLink()" class="bg-slate-800 hover:bg-slate-700 p-2 rounded-lg transition-all" title="Copy Link">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                </button>
            </div>
            <button onclick="window.print()" class="bg-primary hover:bg-primary-dark px-6 py-2 rounded-xl font-black text-xs uppercase tracking-widest transition-all">Download PDF</button>
        </div>
    </div>

    <!-- Resume Page -->
    <main class="max-w-[850px] mx-auto bg-white my-12 p-16 shadow-2xl print-shadow-none relative">
        <!-- Verified Badge (ARS Score) -->
        @if($resume->user && $resume->user->ars_score > 0)
        <div class="absolute top-8 right-8 flex flex-col items-end">
            <div class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.64.304 1.24.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                Verified Verse Profile
            </div>
            <div class="mt-1 text-[9px] font-bold text-slate-400 uppercase tracking-tighter">ARS Score: {{ $resume->user->ars_score }}/100</div>
        </div>
        @endif

        <div class="space-y-10">
            <!-- Header -->
            <div class="border-b-4 border-slate-900 pb-8">
                <h1 class="text-5xl font-black text-slate-900 uppercase tracking-tighter">{{ $resume->data['personal']['name'] }}</h1>
                <p class="text-xl font-bold text-primary mt-2 uppercase tracking-widest">{{ $resume->data['personal']['role'] }}</p>
                
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-6 text-xs font-bold text-slate-500 uppercase tracking-widest">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        {{ $resume->data['personal']['email'] }}
                    </span>
                    @if($resume->data['personal']['phone'])
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        {{ $resume->data['personal']['phone'] }}
                    </span>
                    @endif
                    @if($resume->data['personal']['location'])
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        {{ $resume->data['personal']['location'] }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Summary -->
            <section>
                <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Professional Identity</h2>
                <p class="text-sm text-slate-700 leading-relaxed font-medium">{{ $resume->data['personal']['summary'] }}</p>
            </section>

            <!-- Skills -->
            <section>
                <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Knowledge Stack</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($resume->data['skills'] as $skill)
                    <span class="bg-slate-900 text-white px-3 py-1 rounded text-[11px] font-black tracking-widest uppercase">{{ $skill }}</span>
                    @endforeach
                </div>
            </section>

            <!-- Projects -->
            <section>
                <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Proof of Work (Evidence)</h2>
                <div class="space-y-8">
                    @foreach($resume->data['projects'] as $proj)
                    <div>
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-base font-black text-slate-900 uppercase tracking-tight">{{ $proj['title'] }}</h3>
                                @if(isset($proj['link']) && $proj['link'])
                                <a href="{{ $proj['link'] }}" target="_blank" class="text-[10px] font-bold text-primary uppercase tracking-widest mt-1 inline-block hover:underline">Verify Evidence ↗</a>
                                @endif
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 mt-3 leading-relaxed font-medium">{{ $proj['description'] }}</p>
                    </div>
                    @endforeach
                </div>
            </section>

            <!-- Education -->
            <section>
                <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Academic Background</h2>
                <div class="space-y-6">
                    @foreach($resume->data['education'] as $edu)
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-black text-slate-900 uppercase">{{ $edu['institution'] }}</h3>
                            <p class="text-xs font-bold text-slate-500 uppercase mt-1">{{ $edu['degree'] }}</p>
                        </div>
                        <span class="text-xs font-black text-slate-900">{{ $edu['year'] }}</span>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>

        <!-- Footer -->
        <div class="mt-20 pt-8 border-t border-slate-100 flex justify-between items-center no-print">
            <div class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">Manifested via MyCollegeVerse</div>
            <div class="text-[10px] font-bold text-slate-400">Scan for Proof ▣</div>
        </div>
    </main>

    <script>
        function copyLink() {
            navigator.clipboard.writeText(window.location.href);
            alert('Shareable link captured in your clipboard! 🌌');
        }
    </script>
</body>
</html>
