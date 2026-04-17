<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $note->title }} - Academic Export</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none; }
            body { background: white !important; }
            .glass { border: none !important; box-shadow: none !important; }
        }
        .ai-notes-display h2 {
            border-left: 6px solid #6366f1;
            padding-left: 1.25rem;
            margin-top: 3.5rem;
            margin-bottom: 1.5rem;
            font-weight: 900;
            font-size: 1.875rem;
        }
        .ai-notes-display h3 {
            font-weight: 800;
            font-size: 1.5rem;
            margin-top: 2.5rem;
            margin-bottom: 1.25rem;
        }
        .info-box {
            background: #f0f7ff;
            border: 1px solid #bae6fd;
            border-radius: 1rem;
            padding: 2rem;
            margin: 2rem 0;
        }
        .exam-tip {
            background: #fffbeb;
            border: 2px dashed #f59e0b;
            border-radius: 1rem;
            padding: 2rem;
            margin: 2rem 0;
        }
        .mermaid {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
        }
        table { width: 100%; border-collapse: collapse; margin: 2rem 0; }
        th, td { border: 1px solid #e2e8f0; padding: 1rem; text-align: left; }
        th { background: #f1f5f9; }
    </style>
</head>
<body class="bg-slate-50 p-8 md:p-20">
    <div class="max-w-4xl mx-auto bg-white p-12 md:p-20 shadow-2xl rounded-[3rem] glass relative overflow-hidden">
        
        <div class="no-print absolute top-8 right-8">
            <button onclick="window.print()" class="bg-indigo-600 text-white px-8 py-3 rounded-full font-black text-xs uppercase tracking-widest shadow-lg hover:bg-indigo-700 transition-all">
                🖨️ Save as PDF
            </button>
        </div>

        <header class="border-b-4 border-slate-900 pb-10 mb-10">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 leading-tight">{{ $note->title }}</h1>
                    <p class="text-indigo-600 font-bold mt-2 uppercase tracking-widest text-sm">{{ $note->subject->name ?? 'Academic Asset' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Authorized By</p>
                    <p class="font-black text-slate-800 uppercase tracking-tighter">MyCollegeVerse AI</p>
                </div>
            </div>
        </header>

        <article class="ai-notes-display prose prose-slate max-w-none">
            {!! $note->ai_content !!}
        </article>

        <footer class="mt-20 pt-10 border-t border-slate-100 text-center">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">© {{ date('Y') }} MyCollegeVerse - Knowledge Infrastructure</p>
        </footer>
    </div>

    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
        mermaid.initialize({ startOnLoad: true, theme: 'neutral' });
    </script>
</body>
</html>
