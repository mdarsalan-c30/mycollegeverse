<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Help Center | MyCollegeVerse — Academic Intelligence Base</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.3); }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-900 antialiased">
        <div class="max-w-4xl mx-auto px-6 py-20">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-black mb-4">Command Center: <span class="text-blue-600">Help & FAQs</span></h1>
                <p class="text-slate-500 font-medium">Synchronize your understanding of the student OS.</p>
            </div>

            <div class="space-y-6" x-data="{ active: 0 }">
                {{-- Question 1 --}}
                <div class="glass p-8 rounded-3xl border-slate-200">
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-4">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">01</span>
                        What is MyCollegeVerse?
                    </h3>
                    <p class="text-slate-500 leading-relaxed pl-12 italic">MyCollegeVerse is an Academic Identity Platform and "Student OS" designed to organize college resources, peer interaction, and career pipelines in one high-performance multiverse.</p>
                </div>

                {{-- Question 2 --}}
                <div class="glass p-8 rounded-3xl border-slate-200">
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-4">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">02</span>
                        How do I share my notes?
                    </h3>
                    <p class="text-slate-500 leading-relaxed pl-12 italic">Enter your Dashboard and click "Upload Note". Once verified by our community nodes, your notes will earn you Karma points and visibility in the multiverse.</p>
                </div>

                {{-- Question 3 --}}
                <div class="glass p-8 rounded-3xl border-slate-200">
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-4">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">03</span>
                        Is my data secure?
                    </h3>
                    <p class="text-slate-500 leading-relaxed pl-12 italic">Yes. We use industry-standard encryption and high-performance database nodes to ensure your academic identity is protected at all times.</p>
                </div>
            </div>

            <div class="mt-16 p-10 glass rounded-[2.5rem] border-blue-100 text-center">
                <p class="text-slate-500 font-bold mb-4">Still need intelligence?</p>
                <a href="mailto:mycollegeverse@gmail.com" class="text-blue-600 font-black text-xl hover:underline">mycollegeverse@gmail.com</a>
            </div>

            <div class="mt-12 text-center">
                <a href="/" class="text-slate-400 font-bold hover:text-blue-600 transition-colors uppercase tracking-widest text-xs">Return to Home</a>
            </div>
        </div>
    </body>
</html>
