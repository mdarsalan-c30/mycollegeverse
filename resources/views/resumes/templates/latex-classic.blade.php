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
        .section-title { border-bottom: 1px solid #000; font-weight: bold; text-transform: uppercase; font-size: 14px; margin-top: 15px; margin-bottom: 8px; padding-bottom: 2px; }
        .item-row { display: flex; justify-content: space-between; align-items: baseline; font-weight: bold; font-size: 13px; }
        .item-subrow { display: flex; justify-content: space-between; align-items: baseline; font-size: 12px; font-style: italic; }
        .bullet-list { list-style-type: disc; margin-left: 1.2rem; margin-top: 3px; }
        .bullet-item { font-size: 11.5px; margin-bottom: 1px; text-align: justify; }
        .header-text { font-size: 11px; }
        a { text-decoration: none; color: inherit; }
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
            <!-- PARSED LATEX VIEW -->
            <div id="latex-parsed-content">
                @php
                    $raw = $data['raw_latex'];
                    // Strip comments
                    $raw = preg_replace('/%.*$/m', '', $raw);
                    
                    // Parse Header (Very manual but targeted to the format)
                    preg_match('/\\\\huge \\\\textbf\{([^}]+)\}/', $raw, $nameMatch);
                    preg_match('/\\\\small ([^}]+)\}/', $raw, $degreeMatch);
                    
                    // Contact Info
                    $contactLines = [];
                    if (preg_match('/([^\\\\n]+, India)/', $raw, $loc)) $contactLines[] = trim($loc[1]);
                    if (preg_match('/mailto:([^}]+)/', $raw, $email)) $contactLines[] = trim($email[1]);
                    if (preg_match('/\\\\phone\}\{([^}]+)\}/', $raw, $phone)) $contactLines[] = "+91-" . trim($phone[1]);
                    if (preg_match('/\\\\href\{[^}]+\}\{LinkedIn\}/', $raw)) $contactLines[] = "LinkedIn";
                @endphp

                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold">{{ $nameMatch[1] ?? $resume->title }}</h1>
                        <p class="text-sm">{{ $degreeMatch[1] ?? '' }}</p>
                    </div>
                    <div class="text-right header-text">
                        @foreach($contactLines as $line)
                            <p>{{ $line }}</p>
                        @endforeach
                    </div>
                </div>

                @php
                    // Parse Sections
                    preg_match_all('/\\\\section\{([^}]+)\}([\s\S]*?)(?=\\\\section|\\\\end\{document\})/', $raw, $sections);
                @endphp

                @foreach($sections[1] as $index => $title)
                    <div class="mb-4">
                        <div class="section-title">{{ $title }}</div>
                        <div class="text-[12px]">
                            @php
                                $content = trim($sections[2][$index]);
                                // Replace bold
                                $content = preg_replace('/\\\\textbf\{([^}]+)\}/', '<strong>$1</strong>', $content);
                                // Handle subheadings
                                $content = preg_replace('/\\\\resumeSubheading\{([^}]+)\}\{([^}]+)\}/', '<div class="item-row"><span>$1</span><span>$2</span></div>', $content);
                                // Handle lists
                                if (strpos($content, '\\begin{itemize}') !== false) {
                                    preg_match_all('/\\\\item\s+([^\n]+)/', $content, $items);
                                    echo '<ul class="bullet-list">';
                                    foreach($items[1] as $item) {
                                        echo '<li class="bullet-item">'.trim($item).'</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '<p class="text-justify leading-snug">'.$content.'</p>';
                                }
                            @endphp
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- STANDARD GUIDED VIEW -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold">{{ $data['personal']['name'] }}</h1>
                    <p class="text-sm">{{ $data['personal']['role'] ?? 'Bachelor of Technology' }}</p>
                </div>
                <div class="text-right header-text">
                    <p>{{ $data['personal']['location'] ?? 'Noida, India' }}</p>
                    <p>{{ $data['personal']['email'] }}</p>
                    <p>+91-{{ $data['personal']['phone'] }}</p>
                </div>
            </div>

            @if($data['personal']['summary'])
            <div class="mb-4">
                <div class="section-title">Professional Summary</div>
                <p class="text-[11.5px] text-justify leading-snug">{{ $data['personal']['summary'] }}</p>
            </div>
            @endif

            <div class="mb-4">
                <div class="section-title">Education</div>
                @foreach($data['education'] as $edu)
                <div class="item-row"><span>{{ $edu['degree'] }}</span><span>{{ $edu['year'] }}</span></div>
                <div class="item-subrow"><span>{{ $edu['institution'] }}</span></div>
                @endforeach
            </div>

            @if(count($data['experience'] ?? []) > 0)
            <div class="mb-4">
                <div class="section-title">Experience</div>
                @foreach($data['experience'] as $exp)
                <div class="item-row"><span>{{ $exp['role'] }} — {{ $exp['company'] }}</span><span>{{ $exp['duration'] ?? '' }}</span></div>
                @endforeach
            </div>
            @endif

            <div class="mb-4">
                <div class="section-title">Projects</div>
                @foreach($data['projects'] as $proj)
                <div class="item-row"><span>{{ $proj['title'] }}</span></div>
                <p class="text-[11px] mt-1">{{ $proj['description'] }}</p>
                @endforeach
            </div>

            <div class="mb-4">
                <div class="section-title">Skills</div>
                <p class="text-[11.5px]"><span class="font-bold">Languages & Tools:</span> {{ implode(', ', $data['skills']) }}</p>
            </div>
        @endif
    </div>
</body>
</html>
