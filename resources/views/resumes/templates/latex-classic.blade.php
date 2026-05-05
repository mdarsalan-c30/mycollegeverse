<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resume->title }} | MyCollegeVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'EB Garamond', serif; color: #000; line-height: 1.4; -webkit-print-color-adjust: exact; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .resume-container { box-shadow: none !important; margin: 0 !important; width: 100% !important; padding: 0.4in !important; }
            @page { size: letter; margin: 0.4in; }
        }
        .resume-container { max-width: 850px; margin: 20px auto; background: white; padding: 0.6in; box-shadow: 0 0 30px rgba(0,0,0,0.08); min-height: 11in; }
        .section-title { border-bottom: 1.2px solid #000; font-weight: bold; text-transform: uppercase; font-size: 15px; margin-top: 18px; margin-bottom: 6px; padding-bottom: 2px; letter-spacing: 0.05em; }
        .item-row { display: flex; justify-content: space-between; align-items: baseline; font-weight: bold; font-size: 14.5px; margin-top: 2px; }
        .bullet-list { list-style-type: disc; margin-left: 1.4rem; margin-top: 4px; }
        .bullet-item { font-size: 13.5px; margin-bottom: 3px; text-align: justify; line-height: 1.5; }
        .header-text { font-size: 12.5px; line-height: 1.4; text-align: right; }
        .body-text { font-size: 13.5px; text-align: justify; line-height: 1.5; }
        a { text-decoration: none; color: inherit; }
        strong, b { font-weight: bold; }
    </style>
</head>
<body class="bg-slate-100">
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 no-print">
        <div class="bg-slate-900 text-white px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-6">
            <button onclick="window.print()" class="bg-white text-slate-900 px-8 py-2 rounded-xl font-black text-xs uppercase tracking-widest hover:scale-105 transition-transform">Print / Save PDF</button>
            <button onclick="copyShareLink()" class="bg-primary text-white px-6 py-2 rounded-xl font-black text-xs uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                Copy Link
            </button>
            <button onclick="window.location.href='{{ route('resumes.index') }}'" class="text-xs font-bold text-slate-400 hover:text-white uppercase tracking-widest">Back</button>
        </div>
    </div>

    <script>
        function copyShareLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                alert('🚀 Identity Manifest Link Copied!');
            });
        }
    </script>

    <div class="resume-container">
        @php
            $data = $resume->data;
            $isRawLatex = isset($data['raw_latex']);
        @endphp

        @if($isRawLatex)
            @php
                $raw = $data['raw_latex'];
                $raw = preg_replace('/%.*$/m', '', $raw); 
                preg_match_all('/\\\\newcommand\{\\\\([^}]+)\}\{([^}]+)\}/', $raw, $commands);
                $vars = [];
                if(isset($commands[1])) { foreach($commands[1] as $i => $key) { $vars[$key] = $commands[2][$i]; } }
                foreach($vars as $key => $val) { $raw = str_replace('\\'.$key, $val, $raw); }

                preg_match('/\\\\huge \\\\textbf\{([^}]+)\}/', $raw, $nameM);
                preg_match('/\\\\small ([^}]+)\}/', $raw, $roleM);
                $name = $nameM[1] ?? ($vars['name'] ?? $resume->title);
                $role = $roleM[1] ?? 'Professional Profile';
                
                $contactLines = [];
                if (preg_match('/([^\\\\n\r\t{}&]+, India)/', $raw, $loc)) $contactLines[] = trim($loc[1]);
                if (isset($vars['email'])) $contactLines[] = '<a href="mailto:'.$vars['email'].'" class="underline">'.$vars['email'].'</a>';
                if (isset($vars['phone'])) $contactLines[] = '+91-'.$vars['phone'];
                if (preg_match('/\\\\href\{([^}]+)\}\{LinkedIn\}/', $raw, $li)) $contactLines[] = '<a href="'.$li[1].'" target="_blank" class="underline font-bold">LinkedIn</a>';

                preg_match_all('/\\\\section\{([^}]+)\}([\s\S]*?)(?=\\\\section|\\\\end\{document\})/', $raw, $sections);
            @endphp

            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-[38px] font-bold tracking-tight leading-none mb-1">{{ $name }}</h1>
                    <p class="text-[14.5px] font-bold text-slate-800 uppercase tracking-wide">{{ $role }}</p>
                </div>
                <div class="header-text font-medium mt-2">
                    @foreach($contactLines as $line)
                        <p>{!! $line !!}</p>
                    @endforeach
                </div>
            </div>

            @foreach($sections[1] as $index => $title)
                <div class="mb-5">
                    <div class="section-title">{{ $title }}</div>
                    <div class="body-text">
                        @php
                            $content = trim($sections[2][$index]);
                            $content = preg_replace('/\\\\resumeSubheading\s*\{([^}]+)\}\s*\{([^}]+)\}/', '<div class="item-row"><span>$1</span><span>$2</span></div>', $content);
                            $content = preg_replace('/\\\\textbf\{([^}]+)\}/', '<strong>$1</strong>', $content);
                            if (strpos($content, '\\item') !== false) {
                                preg_match_all('/\\\\item\s+([\s\S]*?)(?=\\\\item|\\\\end\{itemize\})/', $content, $items);
                                echo '<ul class="bullet-list">';
                                foreach($items[1] as $item) {
                                    $it_rich = preg_replace('/\\\\textbf\s*\{([^}]+)\}/', '<strong>$1</strong>', $item);
                                    $it_rich = preg_replace('/\\\\[a-zA-Z]+|[{}]|&/', '', $it_rich);
                                    if(trim($it_rich)) echo '<li class="bullet-item">'.trim($it_rich).'</li>';
                                }
                                echo '</ul>';
                            } else {
                                $cleaned = preg_replace('/\\\\[a-zA-Z]+\{[^}]*\}|\\\\[a-zA-Z]+|[{}]|&|\\\\/', ' ', $content);
                                echo '<p class="mt-1">'.trim($cleaned).'</p>';
                            }
                        @endphp
                    </div>
                </div>
            @endforeach
        @else
            <!-- GUIDED MODE RENDER -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-[38px] font-bold tracking-tight leading-none mb-1">{{ $data['personal']['name'] ?? 'Your Name' }}</h1>
                    <p class="text-[14.5px] font-bold text-slate-800 uppercase tracking-wide">{{ $data['personal']['role'] ?? 'Professional Profile' }}</p>
                </div>
                <div class="header-text font-medium mt-2">
                    <p>{{ $data['personal']['location'] ?? 'Your City, India' }}</p>
                    <p><a href="mailto:{{ $data['personal']['email'] ?? '' }}" class="underline">{{ $data['personal']['email'] ?? '' }}</a></p>
                    <p>+91-{{ $data['personal']['phone'] ?? '' }}</p>
                </div>
            </div>

            @if(!empty($data['personal']['summary']))
            <div class="mb-5">
                <div class="section-title">Professional Summary</div>
                <div class="body-text">{{ $data['personal']['summary'] }}</div>
            </div>
            @endif

            @if(!empty($data['education']))
            <div class="mb-5">
                <div class="section-title">Education</div>
                @foreach($data['education'] as $edu)
                <div class="item-row">
                    <span>{{ $edu['institution'] }} — {{ $edu['degree'] }}</span>
                    <span>{{ $edu['year'] }}</span>
                </div>
                @endforeach
            </div>
            @endif

            @if(!empty($data['projects']))
            <div class="mb-5">
                <div class="section-title">Projects</div>
                @foreach($data['projects'] as $proj)
                <div class="mb-3">
                    <div class="item-row">
                        <span>{{ $proj['title'] }}</span>
                        <span><a href="{{ $proj['link'] }}" class="underline text-xs">{{ $proj['link'] }}</a></span>
                    </div>
                    <div class="body-text mt-1 italic">{{ $proj['description'] }}</div>
                </div>
                @endforeach
            </div>
            @endif

            @if(!empty($data['skills']))
            <div class="mb-5">
                <div class="section-title">Skills</div>
                <div class="body-text">
                    @if(is_array($data['skills']))
                        {{ implode(', ', $data['skills']) }}
                    @else
                        {{ $data['skills'] }}
                    @endif
                </div>
            </div>
            @endif
        @endif
    </div>
</body>
</html>
