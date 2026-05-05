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
            <button onclick="window.location.href='{{ route('resumes.index') }}'" class="text-xs font-bold text-slate-400 hover:text-white uppercase tracking-widest">Back</button>
        </div>
    </div>

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
                $role = $roleM[1] ?? 'Student';
                
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
        @endif
    </div>
</body>
</html>
