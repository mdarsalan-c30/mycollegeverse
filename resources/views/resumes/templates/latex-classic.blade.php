<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resume->title }} | MyCollegeVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .latex-font { font-family: 'Libre+Baskerville', serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-shadow-none { box-shadow: none !important; }
            @page { margin: 1cm; }
        }
        .section-line {
            border-bottom: 1.5px solid #000;
            margin-bottom: 8px;
            margin-top: 4px;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen pb-20">
    <!-- Floating Toolbar -->
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 no-print">
        <div class="bg-slate-900 text-white px-6 py-4 rounded-3xl shadow-2xl flex items-center gap-6 backdrop-blur-md bg-opacity-90">
            <button onclick="window.print()" class="bg-white text-slate-900 px-6 py-2 rounded-xl font-black text-xs uppercase tracking-widest transition-all hover:bg-slate-100">Print / PDF</button>
            <button onclick="window.location.href='{{ route('resumes.index') }}'" class="text-xs font-bold text-slate-400 hover:text-white uppercase tracking-widest">Back to Vault</button>
        </div>
    </div>

    <!-- Resume Page -->
    <main class="max-w-[850px] mx-auto bg-white my-12 p-16 shadow-xl print-shadow-none relative text-[#000]">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold uppercase tracking-tight mb-2">{{ $resume->data['personal']['name'] }}</h1>
            <div class="flex justify-center flex-wrap gap-x-4 text-[11px] font-medium italic">
                <span>{{ $resume->data['personal']['location'] }}</span>
                @if($resume->data['personal']['phone']) <span>• {{ $resume->data['personal']['phone'] }}</span> @endif
                <span>• {{ $resume->data['personal']['email'] }}</span>
            </div>
            <div class="flex justify-center gap-4 mt-2 text-[11px] font-bold">
                 @if(isset($resume->data['personal']['website']) && $resume->data['personal']['website'])
                    <a href="{{ $resume->data['personal']['website'] }}" class="underline">{{ $resume->data['personal']['website'] }}</a>
                 @endif
            </div>
        </div>

        <!-- Summary -->
        @if($resume->data['personal']['summary'])
        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-wider">Summary</h2>
            <div class="section-line"></div>
            <p class="text-[12px] leading-relaxed">{{ $resume->data['personal']['summary'] }}</p>
        </div>
        @endif

        <!-- Education -->
        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-wider">Education</h2>
            <div class="section-line"></div>
            <div class="space-y-4">
                @foreach($resume->data['education'] as $edu)
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-[12px] font-bold">{{ $edu['institution'] }}</span>
                        <p class="text-[11px] italic">{{ $edu['degree'] }}</p>
                    </div>
                    <span class="text-[11px] font-bold">{{ $edu['year'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Skills -->
        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-wider">Technical Skills</h2>
            <div class="section-line"></div>
            <div class="text-[12px] leading-relaxed">
                <span class="font-bold">Languages & Tools:</span> {{ implode(', ', $resume->data['skills']) }}
            </div>
        </div>

        <!-- Experience -->
        @if(count($resume->data['experience']) > 0)
        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-wider">Experience</h2>
            <div class="section-line"></div>
            <div class="space-y-4">
                @foreach($resume->data['experience'] as $exp)
                <div>
                    <div class="flex justify-between items-center">
                        <span class="text-[12px] font-bold">{{ $exp['company'] }}</span>
                        <span class="text-[11px] font-bold">{{ $exp['duration'] }}</span>
                    </div>
                    <p class="text-[11px] italic font-medium">{{ $exp['role'] }}</p>
                    @if(isset($exp['description']))
                        <p class="text-[11px] mt-1 text-slate-700">{{ $exp['description'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Projects -->
        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-wider">Projects</h2>
            <div class="section-line"></div>
            <div class="space-y-4">
                @foreach($resume->data['projects'] as $proj)
                <div>
                    <div class="flex justify-between items-center">
                        <span class="text-[12px] font-bold">{{ $proj['title'] }}</span>
                        @if(isset($proj['link']) && $proj['link'])
                        <a href="{{ $proj['link'] }}" class="text-[10px] underline italic">{{ $proj['link'] }}</a>
                        @endif
                    </div>
                    <p class="text-[11px] mt-1">{{ $proj['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 pt-8 border-t border-slate-50 text-center no-print">
            <p class="text-[10px] text-slate-300 font-bold uppercase tracking-[0.2em]">Generated via MyCollegeVerse Professional Vault</p>
        </div>
    </main>
</body>
</html>
