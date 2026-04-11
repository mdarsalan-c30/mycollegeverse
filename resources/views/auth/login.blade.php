<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome Back — Login to MyCollegeVerse</title>

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
                    colors: { primary: '#3B82F6', secondary: '#6177A5', surface: '#F8FAFC' },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                }
            }
        }
    </script>

    <style>
        .glass { background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.4); }
        .hero-pattern { background-color: #ffffff; background-image: radial-gradient(#3B82F61a 1px, transparent 1px); background-size: 40px 40px; }
        .gradient-bg { background: linear-gradient(135deg, #3B82F6 0%, #6177A5 100%); }
    </style>
</head>
<body class="font-sans antialiased text-slate-900 bg-surface hero-pattern min-h-screen flex items-center justify-center p-6">

    <div class="max-w-5xl w-full grid lg:grid-cols-2 glass rounded-[3rem] shadow-2xl overflow-hidden border-white">
        <!-- Brand Side -->
        <div class="hidden lg:flex flex-col justify-between p-16 gradient-bg text-white relative">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-12">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-xl shadow-black/10">
                        <span class="text-primary font-black text-2xl">M</span>
                    </div>
                    <span class="font-black text-2xl tracking-tight">MyCollegeVerse</span>
                </div>
                <h1 class="text-5xl font-black leading-tight mb-6">Welcome back to the Verse.</h1>
                <p class="text-white/80 text-lg font-medium leading-relaxed max-w-sm">
                    Re-enter your institutional portal and continue where you left off.
                </p>
            </div>

            <!-- Testimonial snippet -->
            <div class="relative z-10 glass bg-white/10 p-8 rounded-[2rem] border-white/20">
                <p class="italic text-sm font-bold mb-4">"The quickest way to catch up on what I missed. Love the clean search!"</p>
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=James+Wilson&background=random" class="w-10 h-10 rounded-xl border border-white/20" />
                    <div><p class="text-xs font-black">James Wilson</p><p class="text-[10px] opacity-70">Student, IIT Bombay</p></div>
                </div>
            </div>

            <!-- Absolute decorative circle -->
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Form Side -->
        <div class="p-8 md:p-16 bg-white/40 flex flex-col justify-center">
            <div class="max-w-sm mx-auto w-full">
                <div class="mb-10">
                    <h2 class="text-3xl font-black text-secondary mb-2">Sign In</h2>
                    <p class="text-slate-500 font-bold">Access your academic multiverse.</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 font-bold text-sm text-green-600 bg-green-50 p-4 rounded-xl border border-green-100 italic">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email Address -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@college.edu" 
                               class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                        @error('email') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center pl-1">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-black text-primary hover:underline">Forgot password?</a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                               class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                        @error('password') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-2 pl-1">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20">
                        <label for="remember_me" class="text-xs font-bold text-slate-500">Stay logged in for next session</label>
                    </div>

                    <div class="pt-2 gap-4 flex flex-col">
                        <button type="submit" class="w-full h-14 bg-primary text-white rounded-[1.5rem] font-black shadow-xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all text-lg tracking-tight">
                            Enter the Verse
                        </button>
                        
                        <p class="text-center text-sm font-bold text-slate-500 mt-4">
                            New here? <a href="{{ route('register') }}" class="text-primary hover:underline">Create an Account</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
