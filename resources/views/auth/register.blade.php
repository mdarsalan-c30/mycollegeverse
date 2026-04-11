<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join MyCollegeVerse — Create Your Identity</title>

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

    <div class="max-w-6xl w-full grid lg:grid-cols-2 glass rounded-[3rem] shadow-2xl overflow-hidden border-white">
        <!-- Brand Side -->
        <div class="hidden lg:flex flex-col justify-between p-16 gradient-bg text-white relative">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-12">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-xl shadow-black/10">
                        <span class="text-primary font-black text-2xl">M</span>
                    </div>
                    <span class="font-black text-2xl tracking-tight">MyCollegeVerse</span>
                </div>
                <h1 class="text-5xl font-black leading-tight mb-6">Build your academic <br/>identity today.</h1>
                <p class="text-white/80 text-lg font-medium leading-relaxed max-w-sm">
                    Join thousands of students and get access to structural notes, peer insights, and verified reviews.
                </p>
            </div>

            <!-- Testimonial snippet -->
            <div class="relative z-10 glass bg-white/10 p-8 rounded-[2rem] border-white/20">
                <p class="italic text-sm font-bold mb-4">"MCV changed the way I prepare for my semester finals. The community notes are a goldmine!"</p>
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Sarah+Chen&background=random" class="w-10 h-10 rounded-xl border border-white/20" />
                    <div><p class="text-xs font-black">Sarah Chen</p><p class="text-[10px] opacity-70">Student, BITS Pilani</p></div>
                </div>
            </div>

            <!-- Absolute decorative circle -->
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Form Side -->
        <div class="p-8 md:p-16 bg-white/40">
            <div class="mb-10">
                <h2 class="text-3xl font-black text-secondary mb-2">Create Account</h2>
                <p class="text-slate-500 font-bold mb-8">Personalize your dashboard in seconds.</p>
                
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Alex Rivera" class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                            @error('name') <p class="text-red-500 text-[10px] font-bold">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Personal Email -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Personal Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="alex@gmail.com" class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                            @error('email') <p class="text-red-500 text-[10px] font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- College Email -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">College Email</label>
                            <input type="email" name="college_email" value="{{ old('college_email') }}" required placeholder="alex@iitb.ac.in" class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                            @error('college_email') <p class="text-red-500 text-[10px] font-bold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Mobile Number -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Mobile Number</label>
                            <input type="text" name="mobile" value="{{ old('mobile') }}" required placeholder="+91 98765 43210" class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                            @error('mobile') <p class="text-red-500 text-[10px] font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- College Dropdown -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Select College</label>
                            <select name="college_id" required class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm appearance-none">
                                <option value="" disabled selected>Choose institutional...</option>
                                @foreach($colleges as $college)
                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                            @error('college_id') <p class="text-red-500 text-[10px] font-bold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Academic Year -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Current Year</label>
                            <select name="year" required class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm appearance-none">
                                <option value="" disabled selected>Current year...</option>
                                <option>1st Year</option>
                                <option>2nd Year</option>
                                <option>3rd Year</option>
                                <option>Final Year</option>
                                <option>Post Graduate</option>
                            </select>
                            @error('year') <p class="text-red-500 text-[10px] font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Password</label>
                            <input type="password" name="password" required autocomplete="new-password" class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-5 text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="pt-4 space-y-4">
                        <button type="submit" class="w-full h-14 bg-primary text-white rounded-[1.5rem] font-black shadow-xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all text-lg tracking-tight">
                            Create My Identity
                        </button>
                        
                        <p class="text-center text-sm font-bold text-slate-500">
                            Already have an account? <a href="{{ route('login') }}" class="text-primary hover:underline">Sign In</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
