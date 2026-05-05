<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resume->title }} | MyCollegeVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* LaTeX Computer Modern Feel */
        @import url('https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700;1,800&display=swap');
        
        body { 
            font-family: 'EB Garamond', serif; 
            color: #000;
            line-height: 1.2;
        }
        
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .resume-container { 
                box-shadow: none !important; 
                margin: 0 !important; 
                padding: 0.5in !important;
                width: 100% !important;
                max-width: none !important;
            }
            @page { 
                size: letter;
                margin: 0.5in; 
            }
        }

        .resume-container {
            max-width: 850px;
            margin: 40px auto;
            background: white;
            padding: 0.6in;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .section-title {
            border-bottom: 1px solid #000;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            margin-top: 12px;
            margin-bottom: 6px;
            padding-bottom: 2px;
        }

        .subheading-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            font-size: 13px;
            font-weight: bold;
        }

        .subheading-subtext {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            font-size: 12px;
            font-style: italic;
            margin-top: 1px;
        }

        .bullet-list {
            list-style-type: disc;
            margin-left: 1.5rem;
            margin-top: 4px;
            margin-bottom: 8px;
        }

        .bullet-item {
            font-size: 12px;
            margin-bottom: 2px;
            text-align: justify;
        }

        a { text-decoration: none; color: inherit; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body class="bg-slate-50">
    <!-- Toolbar -->
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 no-print">
        <div class="bg-slate-900 text-white px-6 py-4 rounded-3xl shadow-2xl flex items-center gap-6 backdrop-blur-md bg-opacity-90">
            <button onclick="window.print()" class="bg-white text-slate-900 px-6 py-2 rounded-xl font-black text-xs uppercase tracking-widest transition-all hover:bg-slate-100">Print / Save PDF</button>
            <button onclick="window.location.href='{{ route('resumes.index') }}'" class="text-xs font-bold text-slate-400 hover:text-white uppercase tracking-widest">Back</button>
        </div>
    </div>

    <div class="resume-container">
        <!-- HEADER (Exact LaTeX Format) -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-4xl font-bold tracking-tight">{{ $resume->data['personal']['name'] }}</h1>
                <p class="text-sm font-medium mt-1">{{ $resume->data['personal']['role'] ?? 'Bachelor of Technology' }}</p>
            </div>
            <div class="text-right text-[12px]">
                <p>{{ $resume->data['personal']['location'] ?? 'Noida, India' }}</p>
                <p><a href="mailto:{{ $resume->data['personal']['email'] }}">{{ $resume->data['personal']['email'] }}</a></p>
                <p>+91-{{ $resume->data['personal']['phone'] }}</p>
                @if(isset($resume->data['personal']['website']))
                <p><a href="{{ $resume->data['personal']['website'] }}">LinkedIn / Portfolio</a></p>
                @endif
            </div>
        </div>

        <!-- SUMMARY -->
        @if($resume->data['personal']['summary'])
        <div class="mb-4">
            <div class="section-title">Professional Summary</div>
            <p class="text-[12px] text-justify leading-snug">
                {{ $resume->data['personal']['summary'] }}
            </p>
        </div>
        @endif

        <!-- EDUCATION -->
        <div class="mb-4">
            <div class="section-title">Education</div>
            <div class="space-y-2">
                @foreach($resume->data['education'] as $edu)
                <div>
                    <div class="subheading-row">
                        <span>{{ $edu['degree'] }}</span>
                        <span>{{ $edu['year'] }}</span>
                    </div>
                    <div class="subheading-subtext">
                        <span>{{ $edu['institution'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- EXPERIENCE -->
        @if(count($resume->data['experience']) > 0)
        <div class="mb-4">
            <div class="section-title">Professional Experience</div>
            <div class="space-y-4">
                @foreach($resume->data['experience'] as $exp)
                <div>
                    <div class="subheading-row">
                        <span>{{ $exp['role'] }} — {{ $exp['company'] }}</span>
                        <span>{{ $exp['duration'] }}</span>
                    </div>
                    @if(isset($exp['description']))
                    <ul class="bullet-list">
                        @foreach(explode("\n", $exp['description']) as $bullet)
                            @if(trim($bullet))
                            <li class="bullet-item">{{ ltrim(trim($bullet), '-•') }}</li>
                            @endif
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- PROJECTS -->
        @if(count($resume->data['projects']) > 0)
        <div class="mb-4">
            <div class="section-title">Projects</div>
            <div class="space-y-3">
                @foreach($resume->data['projects'] as $proj)
                <div>
                    <div class="subheading-row">
                        <span>{{ $proj['title'] }}</span>
                        @if(isset($proj['link']) && $proj['link'])
                        <span class="text-[10px] italic font-normal">{{ $proj['link'] }}</span>
                        @endif
                    </div>
                    @if(isset($proj['description']))
                    <ul class="bullet-list">
                        @foreach(explode("\n", $proj['description']) as $bullet)
                            @if(trim($bullet))
                            <li class="bullet-item">{{ ltrim(trim($bullet), '-•') }}</li>
                            @endif
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- SKILLS -->
        @if(count($resume->data['skills']) > 0)
        <div class="mb-4">
            <div class="section-title">Skills</div>
            <div class="text-[12px] leading-relaxed">
                <span class="font-bold">Languages & Technologies:</span> {{ implode(', ', $resume->data['skills']) }}
            </div>
        </div>
        @endif

        <!-- Footer (Optional / Hidden in Print) -->
        <div class="mt-12 pt-4 border-t border-slate-50 text-center no-print">
            <p class="text-[9px] text-slate-300 font-bold uppercase tracking-[0.3em]">Generated via MyCollegeVerse Professional</p>
        </div>
    </div>
</body>
</html>
