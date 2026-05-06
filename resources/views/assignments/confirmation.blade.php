<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Submission Received | MCV Assess</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 font-['Plus_Jakarta_Sans'] flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full bg-white rounded-[3rem] border border-slate-100 shadow-2xl p-12 text-center space-y-8 animate-in fade-in zoom-in duration-500">
        <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-[2rem] flex items-center justify-center text-4xl mx-auto ring-8 ring-emerald-50/50">
            ✅
        </div>
        
        <div class="space-y-2">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Signal Received!</h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Your work node has been manifested</p>
        </div>

        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 space-y-3">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Submission Identity</p>
            <p class="text-xs font-black text-slate-900 break-all">{{ strtoupper($submission->id . '-' . bin2hex(random_bytes(4))) }}</p>
        </div>

        <div class="text-left space-y-4 pt-4">
            <div class="flex gap-4">
                <span class="w-6 h-6 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-[10px] font-bold">1</span>
                <p class="text-xs font-bold text-slate-600">Recruiter has been notified of your submission.</p>
            </div>
            <div class="flex gap-4">
                <span class="w-6 h-6 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-[10px] font-bold">2</span>
                <p class="text-xs font-bold text-slate-600">Review status will be updated within 48-72 hours.</p>
            </div>
        </div>

        <div class="pt-6">
            <a href="/" class="inline-flex h-12 px-10 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest items-center hover:bg-blue-600 transition-all">
                Return to Verse Hub
            </a>
        </div>
    </div>
</body>
</html>
