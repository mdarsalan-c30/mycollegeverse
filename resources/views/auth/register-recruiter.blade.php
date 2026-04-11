<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recruiter Access — VerseOS Pipeline</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#3B82F6', secondary: '#1E293B', surface: '#F8FAFC' },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                }
            }
        }
    </script>

    <style>
        .glass { background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.4); }
        .hero-pattern { background-color: #0f172a; background-image: radial-gradient(#ffffff1a 1px, transparent 1px); background-size: 40px 40px; }
        .corporate-gradient { background: linear-gradient(135deg, #1E293B 0%, #334155 100%); }
    </style>
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-950 hero-pattern min-h-screen flex items-center justify-center p-6">

    <div class="max-w-6xl w-full grid lg:grid-cols-2 glass rounded-[3rem] shadow-2xl overflow-hidden border-white/20">
        <!-- Brand Side -->
        <div class="hidden lg:flex flex-col justify-between p-16 corporate-gradient text-white relative">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-12">
                    <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center shadow-xl shadow-primary/20">
                        <span class="text-white font-black text-2xl">V</span>
                    </div>
                    <span class="font-black text-2xl tracking-tight italic">VerseOS <span class="text-primary not-italic">Pipeline</span></span>
                </div>
                <h1 class="text-5xl font-black leading-tight mb-6">Source from the Verse.</h1>
                <p class="text-slate-400 text-lg font-medium leading-relaxed max-w-sm">
                    Connect with high-performing students based on verified academic reputation and contributions.
                </p>
            </div>

            <!-- Stats section -->
            <div class="relative z-10 grid grid-cols-2 gap-6 mt-12">
                <div class="glass bg-white/5 p-6 rounded-[2rem] border-white/10">
                    <p class="text-3xl font-black text-primary mb-1">90+</p>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-tight">ARS Talent Threshold</p>
                </div>
                <div class="glass bg-white/5 p-6 rounded-[2rem] border-white/10">
                    <p class="text-3xl font-black text-white mb-1">100%</p>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-tight">Vetted Contributions</p>
                </div>
            </div>

            <!-- Absolute decorative circle -->
            <div class="absolute -top-20 -right-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Form Side -->
        <div class="p-8 md:p-16 bg-white/95">
            <div class="mb-10">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-3xl font-black text-slate-900 leading-tight">Recruiter Access</h2>
                    <span class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Pipeline Only</span>
                </div>
                <p class="text-slate-500 font-bold mb-8 italic text-sm">Official company credentials required for verification.</p>
                
                <form method="POST" action="{{ route('recruiter.register.store') }}" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Talent Acquisition Partner" class="w-full h-12 bg-surface border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                        @error('name') <p class="text-red-500 text-[10px] font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Company Work Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@company.com" class="w-full h-12 bg-surface border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                        @error('email') <p class="text-red-500 text-[10px] font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Company Name</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" required placeholder="Acme Inc." class="w-full h-12 bg-surface border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Company Website</label>
                            <input type="url" name="company_website" value="{{ old('company_website') }}" required placeholder="https://acme.co" class="w-full h-12 bg-surface border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Password</label>
                            <input type="password" name="password" required autocomplete="new-password" class="w-full h-12 bg-surface border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Confirm</label>
                            <input type="password" name="password_confirmation" required class="w-full h-12 bg-surface border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="pt-4 space-y-4">
                        <button type="submit" class="w-full h-14 bg-slate-900 text-white rounded-[1.5rem] font-black shadow-xl shadow-black/30 hover:bg-black active:scale-95 transition-all text-lg tracking-tight">
                            Access Pipeline
                        </button>
                        
                        <p class="text-center text-sm font-bold text-slate-500">
                            Looking for students? <a href="{{ route('login') }}" class="text-primary hover:underline">Sign In</a> or <a href="{{ route('register') }}" class="text-slate-700 hover:underline">Student Signup</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
