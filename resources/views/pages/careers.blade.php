<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Careers | MyCollegeVerse — Build the Future of Education</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.3); }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-900 antialiased">
        <div class="max-w-7xl mx-auto px-6 py-20">
            <div class="text-center mb-20">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-blue-600 border border-blue-200 font-bold text-xs uppercase tracking-widest mb-6">
                    Join the Multiverse
                </div>
                <h1 class="text-5xl font-black text-slate-900 mb-6">Build the <span class="text-blue-600">Student OS</span> of India.</h1>
                <p class="text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed">
                    We're building the first high-performance academic identity platform. Join our mission to organize the college multiverse.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 mb-20">
                <div class="glass p-10 rounded-[2.5rem] border-blue-100">
                    <h3 class="text-2xl font-black mb-4">Engineering & Design</h3>
                    <p class="text-slate-500 mb-6 italic">Building high-fidelity nodes for students.</p>
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 mb-4 opacity-70">
                        <p class="font-bold text-slate-400">Frontend Architect (Next.js/Tailwind)</p>
                        <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest">Opening Soon</p>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 opacity-70">
                        <p class="font-bold text-slate-400">Product Designer (Premium UI/UX)</p>
                        <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest">Opening Soon</p>
                    </div>
                </div>

                <div class="glass p-10 rounded-[2.5rem] border-blue-100">
                    <h3 class="text-2xl font-black mb-4">Community & Operations</h3>
                    <p class="text-slate-500 mb-6 italic">Manifesting campus hubs nationwide.</p>
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 mb-4 opacity-70">
                        <p class="font-bold text-slate-400">Campus Ambassadors (Internship)</p>
                        <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest">Opening Soon</p>
                    </div>
                    <div class="p-6 bg-white rounded-2xl border border-blue-200 shadow-sm">
                        <p class="font-black text-blue-600">Editorial Visionaries</p>
                        <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest leading-loose">Accepting Portfolios — Send to mycollegeverse@gmail.com</p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="/" class="text-slate-400 font-bold hover:text-blue-600 transition-colors uppercase tracking-widest text-xs">Return to Dashboard</a>
            </div>
        </div>
    </body>
</html>
