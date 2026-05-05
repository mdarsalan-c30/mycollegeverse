<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resume->title }} | MyCollegeVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'EB Garamond', serif; color: #000; line-height: 1.25; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .resume-container { box-shadow: none !important; margin: 0 !important; width: 100% !important; padding: 0.3in !important; }
            @page { size: letter; margin: 0.3in; }
        }
        .resume-container { max-width: 850px; margin: 20px auto; background: white; padding: 0.5in; box-shadow: 0 0 20px rgba(0,0,0,0.05); }
        .section-title { border-bottom: 1px solid #000; font-weight: bold; text-transform: uppercase; font-size: 13px; margin-top: 14px; margin-bottom: 5px; padding-bottom: 1px; }
        .item-row { display: flex; justify-content: space-between; align-items: baseline; font-weight: bold; font-size: 12.5px; margin-top: 2px; }
        .bullet-list { list-style-type: disc; margin-left: 1.2rem; margin-top: 2px; }
        .bullet-item { font-size: 11px; margin-bottom: 1px; text-align: justify; }
        .header-text { font-size: 10.5px; line-height: 1.4; text-align: right; }
        a { text-decoration: none; color: inherit; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body class="bg-slate-50">
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 no-print">
        <div class="bg-slate-900 text-white px-6 py-4 rounded-3xl shadow-2xl flex items-center gap-6">
            <button onclick="window.print()" class="bg-white text-slate-900 px-6 py-2 rounded-xl font-black text-xs uppercase tracking-widest">Print / Save PDF</button>
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
                $raw = preg_replace('/%.*$/m', '', $raw); // Strip comments
                
                // 1. Extract Custom Commands (\newcommand)
                preg_match_all('/\\\\newcommand\{\\\\([^}]+)\}\{([^}]+)\}/', $raw, $commands);
                $vars = [];
                if(isset($commands[1])) {
                    foreach($commands[1] as $i => $key) { $vars[$key] = $commands[2][$i]; }
                }

                // Replace vars in the whole raw string
                foreach($vars as $key => $val) {
                    $raw = str_replace('\\'.$key, $val, $raw);
                }

                // 2. Extract Name & Role (from the header block)
                preg_match('/\\\\huge \\\\textbf\{([^}]+)\}/', $raw, $nameM);
                preg_match('/\\\\small ([^}]+)\}/', $raw, $roleM);
                $name = $nameM[1] ?? ($vars['name'] ?? $resume->title);
                $role = $roleM[1] ?? 'Student';

                // 3. Extract Contact Lines (Noida, Email, Phone, LinkedIn)
                $contactLines = [];
                if (preg_match('/([^\\\\n\r\t{}&]+, India)/', $raw, $loc)) $contactLines[] = trim($loc[1]);
                if (isset($vars['email'])) $contactLines[] = '<a href="mailto:'.$vars['email'].'">'.$vars['email'].'</a>';
                if (isset($vars['phone'])) $contactLines[] = '+91-'.$vars['phone'];
                if (preg_match('/\\\\href\{([^}]+)\}\{LinkedIn\}/', $raw, $li)) $contactLines[] = '<a href="'.$li[1].'" target="_blank">LinkedIn</a>';

                // 4. Extract sections
                preg_match_all('/\\\\section\{([^}]+)\}([\s\S]*?)(?=\\\\section|\\\\end\{document\})/', $raw, $sections);
            @endphp

            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">{{ $name }}</h1>
                    <p class="text-[12.5px] font-bold text-slate-700 uppercase">{{ $role }}</p>
                </div>
                <div class="header-text font-medium">
                    @foreach($contactLines as $line)
                        <p>{!! $line !!}</p>
                    @endforeach
                </div>
            </div>

            @foreach($sections[1] as $index => $title)
                <div class="mb-4">
                    <div class="section-title">{{ $title }}</div>
                    <div class="text-[11.5px]">
                        @php
                            $content = trim($sections[2][$index]);
                            
                            // Cleanup tabular/tabularx
                            $content = preg_replace('/\\\\begin\{(tabular|tabularx)\}[\s\S]*?\\\\end\{\1\}/', ' ', $content);
                            
                            // Parse subheadings
                            $content = preg_replace('/\\\\resumeSubheading\s*\{([^}]+)\}\s*\{([^}]+)\}/', '<div class="item-row"><span>$1</span><span>$2</span></div>', $content);
                            
                            // Parse itemize
                            if (strpos($content, '\\item') !== false) {
                                preg_match_all('/\\\\item\s+([^\n\\\\%]+)/', $content, $items);
                                echo '<ul class="bullet-list">';
                                foreach($items[1] as $item) {
                                    $it = trim(preg_replace('/[\\{}]/', '', $item));
                                    if($it) echo '<li class="bullet-item">'.$it.'</li>';
                                }
                                echo '</ul>';
                            } else {
                                $cleaned = preg_replace('/\\\\[a-zA-Z]+\{[^}]*\}|\\\\[a-zA-Z]+|[{}]|&|\\\\/', ' ', $content);
                                echo '<p class="text-justify leading-snug">'.trim($cleaned).'</p>';
                            }
                        @endphp
                    </div>
                </div>
            @endforeach
        @else
            <!-- Guided mode ... -->
             <div class="flex justify-between items-start mb-6">
                <div><h1 class="text-3xl font-bold">{{ $data['personal']['name'] }}</h1><p class="text-sm">{{ $data['personal']['role'] ?? 'Bachelor of Technology' }}</p></div>
                <div class="text-right header-text font-medium"><p>{{ $data['personal']['location'] ?? 'Noida, India' }}</p><p>{{ $data['personal']['email'] }}</p><p>+91-{{ $data['personal']['phone'] }}</p></div>
            </div>
            @if($data['personal']['summary'])<div class="mb-4"><div class="section-title">Professional Summary</div><p class="text-[11.5px] text-justify leading-snug">{{ $data['personal']['summary'] }}</p></div>@endif
            <div class="mb-4"><div class="section-title">Education</div>@foreach($data['education'] as $edu)<div class="item-row"><span>{{ $edu['degree'] }}</span><span>{{ $edu['year'] }}</span></div><div class="item-subrow"><span>{{ $edu['institution'] }}</span></div>@endforeach</div>
        @endif
    </div>
</body>
</html>
