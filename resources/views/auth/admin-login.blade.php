<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Master Authority — Admin Terminal | MyCollegeVerse</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        primary: '#1E293B', 
                        accent: '#3B82F6', 
                        surface: '#F8FAFC' 
                    },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                }
            }
        }
    </script>

    <style>
        .glass { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.5); }
        .admin-gradient { background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); }
        .grid-pattern { background-image: radial-gradient(#3b82f61a 1px, transparent 1px); background-size: 30px 30px; }
    </style>
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-100 grid-pattern min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full glass rounded-[3rem] shadow-2xl overflow-hidden border-white/50 p-12 relative overflow-hidden">
        <!-- Decorative Glow -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-accent/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-accent/20 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <div class="flex flex-col items-center text-center mb-10">
                <div class="w-16 h-16 admin-gradient rounded-2xl flex items-center justify-center shadow-2xl mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Master Authority</h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">Admin Control Tower Login</p>
            </div>

            <!-- Error Notification -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-[10px] font-bold italic line-clamp-2">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-6">
                @csrf
                
                <!-- Email Node -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Authority ID (Email)</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                           class="w-full h-12 bg-white/50 border border-slate-200 rounded-2xl px-5 text-xs font-bold focus:ring-4 focus:ring-accent/5 focus:border-accent transition-all">
                </div>

                <!-- Password Node -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Access Protocol (Password)</label>
                    <input type="password" name="password" required autocomplete="current-password"
                           class="w-full h-12 bg-white/50 border border-slate-200 rounded-2xl px-5 text-xs font-bold focus:ring-4 focus:ring-accent/5 focus:border-accent transition-all">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full h-14 admin-gradient text-white rounded-2xl font-black shadow-2xl shadow-slate-900/40 hover:scale-[1.02] active:scale-95 transition-all text-xs uppercase tracking-[0.2em]">
                        Initialize Terminal
                    </button>
                    <a href="/" class="block text-center mt-6 text-[10px] font-black text-slate-400 hover:text-accent tracking-widest transition-colors uppercase">Return to Public Verse</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
