<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resume->title }} | MyCollegeVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'EB Garamond', serif; color: #000; line-height: 1.2; -webkit-print-color-adjust: exact; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .resume-container { box-shadow: none !important; margin: 0 !important; width: 100% !important; padding: 0.3in !important; }
            @page { size: letter; margin: 0.3in; }
        }
        .resume-container { max-width: 850px; margin: 15px auto; background: white; padding: 0.4in; box-shadow: 0 0 20px rgba(0,0,0,0.05); min-height: 11in; }
        .section-title { border-bottom: 0.8px solid #000; font-weight: bold; text-transform: uppercase; font-size: 12.5px; margin-top: 12px; margin-bottom: 4px; padding-bottom: 1px; letter-spacing: 0.05em; }
        .item-row { display: flex; justify-content: space-between; align-items: baseline; font-weight: bold; font-size: 12.5px; margin-top: 1px; }
        .bullet-list { list-style-type: disc; margin-left: 1.1rem; margin-top: 1px; }
        .bullet-item { font-size: 11px; margin-bottom: 0.5px; text-align: justify; }
        .header-text { font-size: 10.5px; line-height: 1.3; text-align: right; }
        a { text-decoration: none; color: inherit; }
        strong, b { font-weight: bold; }
    </style>
</head>
<body class="bg-slate-100">
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 no-print">
        <div class="bg-slate-900 text-white px-6 py-4 rounded-3xl shadow-2xl flex items-center gap-6">
            <button onclick="window.print()" class="bg-white text-slate-900 px-6 py-2 rounded-xl font-black text-xs uppercase">Print / Save PDF</button>
            <button onclick="window.location.href='{{ route('resumes.index') }}'" class="text-xs font-bold text-slate-400 hover:text-white uppercase">Back</button>
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
                
                // Extract Variables (\newcommand)
                preg_match_all('/\\\\newcommand\{\\\\([^}]+)\}\{([^}]+)\}/', $raw, $commands);
                $vars = [];
                if(isset($commands[1])) {
                    foreach($commands[1] as $i => $key) { $vars[$key] = $commands[2][$i]; }
                }
                foreach($vars as $key => $val) { $raw = str_replace('\\'.$key, $val, $raw); }

                // Header Info
                preg_match('/\\\\huge \\\\textbf\{([^}]+)\}/', $raw, $nameM);
                preg_match('/\\\\small ([^}]+)\}/', $raw, $roleM);
                $name = $nameM[1] ?? ($vars['name'] ?? $resume->title);
                $role = $roleM[1] ?? 'Student';
                
                // Contact Details
                $contactLines = [];
                if (preg_match('/([^\\\\n\r\t{}&]+, India)/', $raw, $loc)) $contactLines[] = trim($loc[1]);
                if (isset($vars['email'])) $contactLines[] = '<a href="mailto:'.$vars['email'].'" class="underline">'.$vars['email'].'</a>';
                if (isset($vars['phone'])) $contactLines[] = '+91-'.$vars['phone'];
                if (preg_match('/\\\\href\{([^}]+)\}\{LinkedIn\}/', $raw, $li)) $contactLines[] = '<a href="'.$li[1].'" target="_blank" class="underline font-bold">LinkedIn</a>';

                // Parse Sections
                preg_match_all('/\\\\section\{([^}]+)\}([\s\S]*?)(?=\\\\section|\\\\end\{document\})/', $raw, $sections);
            @endphp

            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-[32px] font-bold tracking-tight leading-tight">{{ $name }}</h1>
                    <p class="text-[12.5px] font-bold text-slate-800">{{ $role }}</p>
                </div>
                <div class="header-text font-medium mt-1">
                    @foreach($contactLines as $line)
                        <p>{!! $line !!}</p>
                    @endforeach
                </div>
            </div>

            @foreach($sections[1] as $index => $title)
                <div class="mb-3">
                    <div class="section-title">{{ $title }}</div>
                    <div class="text-[11.5px]">
                        @php
                            $content = trim($sections[2][$index]);
                            
                            // Specific Overleaf-style Subheadings
                            $content = preg_replace('/\\\\resumeSubheading\s*\{([^}]+)\}\s*\{([^}]+)\}/', '<div class="item-row"><span>$1</span><span>$2</span></div>', $content);
                            
                            // Bold formatting inside items
                            $content = preg_replace('/\\\\textbf\{([^}]+)\}/', '<strong>$1</strong>', $content);

                            // Improved Itemize Parser (Handles nested tags)
                            if (strpos($content, '\\item') !== false) {
                                // Extract all items including their content with tags
                                preg_match_all('/\\\\item\s+([\s\S]*?)(?=\\\\item|\\\\end\{itemize\})/', $content, $items);
                                echo '<ul class="bullet-list">';
                                foreach($items[1] as $item) {
                                    $it = trim(preg_replace('/\\\\[a-zA-Z]+|[{}]|&|\\\\/', ' ', $item));
                                    // Special handling for bold which we already replaced
                                    $it_rich = trim(str_replace(['\\', '{', '}', '&'], ' ', $item));
                                    // Re-apply bold if stripped incorrectly
                                    $it_rich = preg_replace('/\\\\textbf\s*\{([^}]+)\}/', '<strong>$1</strong>', $item);
                                    $it_rich = preg_replace('/\\\\[a-zA-Z]+|[{}]|&/', '', $it_rich);
                                    
                                    if(trim($it_rich)) echo '<li class="bullet-item">'.trim($it_rich).'</li>';
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
            <!-- Guided Mode ... -->
             <div class="flex justify-between items-start mb-4">
                <div><h1 class="text-3xl font-bold">{{ $data['personal']['name'] }}</h1><p class="text-sm">{{ $data['personal']['role'] ?? 'Bachelor of Technology' }}</p></div>
                <div class="text-right header-text font-medium"><p>{{ $data['personal']['location'] ?? 'Noida, India' }}</p><p>{{ $data['personal']['email'] }}</p><p>+91-{{ $data['personal']['phone'] }}</p></div>
            </div>
            <div class="mb-4"><div class="section-title">Education</div>@foreach($data['education'] as $edu)<div class="item-row"><span>{{ $edu['degree'] }}</span><span>{{ $edu['year'] }}</span></div><div class="item-subrow"><span>{{ $edu['institution'] }}</span></div>@endforeach</div>
        @endif
    </div>
</body>
</html>
