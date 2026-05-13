<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404: Knowledge Void | MyCollegeVerse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .bg-gradient { background: radial-gradient(circle at top right, #e0e7ff 0%, transparent 40%), radial-gradient(circle at bottom left, #f0f9ff 0%, transparent 40%); }
    </style>
</head>
<body class="bg-gradient min-h-screen flex items-center justify-center p-6 overflow-hidden">
    
    <!-- Floating Orbs 🛸 -->
    <div class="absolute top-20 left-20 w-64 h-64 bg-indigo-200/30 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-20 right-20 w-96 h-96 bg-blue-100/40 rounded-full blur-3xl"></div>

    <div class="max-w-2xl w-full glass rounded-[3.5rem] p-12 md:p-20 text-center shadow-2xl relative z-10">
        <div class="inline-block px-6 py-2 bg-indigo-100 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-[0.3em] mb-8">
            Error Code: 404
        </div>
        
        <h1 class="text-6xl md:text-8xl font-black text-slate-900 mb-6 tracking-tighter">
            Lost in <span class="text-indigo-600">The Void.</span>
        </h1>
        
        <p class="text-lg text-slate-500 font-medium mb-12 leading-relaxed">
            Bhai, ye content multiverse ke kisi aur kone mein nikal gaya. Jo aap dhund rahe hain wo abhi available nahi hai.
        </p>

        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ url('/') }}" class="bg-indigo-600 text-white px-10 py-5 rounded-3xl font-black shadow-xl shadow-indigo-200 hover:scale-105 transition-all flex items-center gap-3">
                <span>🏠</span> Return to Base
            </a>
            <a href="{{ route('notes.index') }}" class="bg-white text-slate-900 border border-slate-200 px-10 py-5 rounded-3xl font-black hover:bg-slate-50 transition-all flex items-center gap-3">
                <span>📚</span> Explore Notes
            </a>
        </div>

        <!-- System Message -->
        <div class="mt-16 pt-8 border-t border-slate-100">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">MyCollegeVerse • Academic OS v2.0</p>
        </div>
    </div>

</body>
</html>
