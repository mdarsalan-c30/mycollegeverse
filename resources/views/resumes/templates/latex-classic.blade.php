<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resume->title }} | MyCollegeVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'EB Garamond', serif; color: #000; line-height: 1.3; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .resume-container { box-shadow: none !important; margin: 0 !important; width: 100% !important; padding: 0.4in !important; }
            @page { size: letter; margin: 0.4in; }
        }
        .resume-container { max-width: 850px; margin: 30px auto; background: white; padding: 0.6in; box-shadow: 0 0 20px rgba(0,0,0,0.05); }
        .section-title { border-bottom: 1.1px solid #000; font-weight: bold; text-transform: uppercase; font-size: 13.5px; margin-top: 15px; margin-bottom: 8px; padding-bottom: 1.5px; }
        .item-row { display: flex; justify-content: space-between; align-items: baseline; font-weight: bold; font-size: 13px; }
        .item-subrow { display: flex; justify-content: space-between; align-items: baseline; font-size: 12px; font-style: italic; }
        .bullet-list { list-style-type: disc; margin-left: 1.3rem; margin-top: 3px; }
        .bullet-item { font-size: 11.5px; margin-bottom: 2px; text-align: justify; line-height: 1.4; }
        .header-text { font-size: 11px; line-height: 1.5; }
        a { text-decoration: none; color: #1e40af; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body class="bg-slate-50">
    <!-- Toolbar -->
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
                
                // Parse Name & Degree
                preg_match('/\\\\huge \\\\textbf\{([^}]+)\}/', $raw, $nameMatch);
                preg_match('/\\\\small ([^}]+)\}/', $raw, $degreeMatch);
                
                // Parse Links & Contact
                $contactLines = [];
                // Location
                if (preg_match('/([^\\\\n\r\t{}&]+, India)/', $raw, $loc)) $contactLines[] = trim($loc[1]);
                // Email
                if (preg_match('/\\\\email\{([^}]+)\}/', $raw, $email)) {
                    $contactLines[] = '<a href="mailto:'.$email[1].'">'.$email[1].'</a>';
                } elseif (preg_match('/mailto:([^}]+)/', $raw, $email)) {
                    $contactLines[] = '<a href="mailto:'.$email[1].'">'.$email[1].'</a>';
                }
                // Phone
                if (preg_match('/\\\\phone[^}]*\{([^}]+)\}/', $raw, $phone)) $contactLines[] = "+91-" . trim($phone[1]);
                // LinkedIn / Social
                if (preg_match('/\\\\href\{([^}]+)\}\{LinkedIn\}/', $raw, $li)) {
                    $contactLines[] = '<a href="'.$li[1].'" target="_blank">LinkedIn</a>';
                } elseif (strpos($raw, 'LinkedIn') !== false) {
                    $contactLines[] = '<a href="#">LinkedIn</a>';
                }
            @endphp

            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight">{{ $nameMatch[1] ?? $resume->title }}</h1>
                    <p class="text-sm font-medium text-slate-600 uppercase tracking-wide">{{ $degreeMatch[1] ?? '' }}</p>
                </div>
                <div class="text-right header-text font-medium">
                    @foreach($contactLines as $line)
                        <p>{!! $line !!}</p>
                    @endforeach
                </div>
            </div>

            @php
                preg_match_all('/\\\\section\{([^}]+)\}([\s\S]*?)(?=\\\\section|\\\\end\{document\})/', $raw, $sections);
            @endphp

            @foreach($sections[1] as $index => $title)
                <div class="mb-4">
                    <div class="section-title">{{ $title }}</div>
                    <div class="text-[12px]">
                        @php
                            $content = trim($sections[2][$index]);
                            
                            // 1. Handle resumeSubheading WITH FLEXIBILITY (Handle spaces)
                            $content = preg_replace('/\\\\resumeSubheading\s*\{([^}]+)\}\s*\{([^}]+)\}/', '<div class="item-row"><span>$1</span><span>$2</span></div>', $content);
                            
                            // 2. Handle Bold & Italics
                            $content = preg_replace('/\\\\textbf\{([^}]+)\}/', '<strong>$1</strong>', $content);
                            $content = preg_replace('/\\\\textit\{([^}]+)\}/', '<em>$1</em>', $content);
                            
                            // 3. Handle href links in content
                            $content = preg_replace('/\\\\href\{([^}]+)\}\{([^}]+)\}/', '<a href="$1" target="_blank">$2</a>', $content);

                            // 4. Handle Lists (itemize)
                            if (strpos($content, '\\item') !== false) {
                                preg_match_all('/\\\\item\s+([^\n\\\\\\%]+)/', $content, $items);
                                echo '<ul class="bullet-list">';
                                foreach($items[1] as $item) {
                                    $it = trim(preg_replace('/[\\{}]/', '', $item));
                                    if($it) echo '<li class="bullet-item">'.$it.'</li>';
                                }
                                echo '</ul>';
                            } else {
                                // Strip remaining LaTeX junk
                                $cleanedText = preg_replace('/\\\\[a-zA-Z]+\{[^}]*\}|\\\\[a-zA-Z]+|[{}]|&/', ' ', $content);
                                echo '<p class="text-justify leading-snug">'.trim($cleanedText).'</p>';
                            }
                        @endphp
                    </div>
                </div>
            @endforeach
        @else
             <!-- Guided view ... -->
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
