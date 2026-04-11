<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Recruiter Login — VerseOS Pipeline</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .corporate-gradient {
                background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            }
        </style>
    </head>
    <body class="bg-[#F8FAFC] antialiased">
        <div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
            <!-- Background Decorations -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-3xl -mr-64 -mt-64"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-500/5 rounded-full blur-3xl -ml-64 -mb-64"></div>

            <div class="w-full max-w-md relative">
                <div class="text-center mb-10">
                    <div class="inline-flex items-center gap-2 mb-6">
                        <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white shadow-lg">
                            <span class="font-black text-lg">P</span>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-slate-900 italic">Pipeline <span class="text-blue-600 not-italic">OS</span></span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900">Partner Access</h1>
                    <p class="text-slate-400 text-sm font-bold mt-2 uppercase tracking-widest">Sign in to your corporate talent dashboard</p>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 p-10 border border-white">
                    <form method="POST" action="{{ route('recruiter.login.store') }}" class="space-y-6">
                        @csrf

                        @foreach ($errors->all() as $error)
                            <div class="p-4 bg-red-50 rounded-2xl border border-red-100 mb-4">
                                <p class="text-xs font-bold text-red-600">{{ $error }}</p>
                            </div>
                        @endforeach

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Official Email</label>
                            <input type="email" name="email" required autofocus placeholder="name@company.com" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-600/20 transition-all">
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center ml-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password</label>
                                <a href="#" class="text-[9px] font-black text-blue-600 uppercase tracking-widest">Forgot?</a>
                            </div>
                            <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-sm font-bold focus:ring-2 focus:ring-blue-600/20 transition-all">
                        </div>

                        <div class="flex items-center gap-3 ml-1">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded-lg border-slate-200 text-blue-600 focus:ring-blue-500">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Keep me signed in</span>
                        </div>

                        <button type="submit" class="w-full h-14 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-slate-900/20 hover:bg-black hover:scale-[1.02] active:scale-95 transition-all">
                            Access Pipeline
                        </button>
                    </form>
                </div>

                <p class="text-center mt-10">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">New Partner?</span>
                    <a href="{{ route('recruiter.register') }}" class="text-xs font-black text-blue-600 uppercase tracking-widest hover:underline ml-2">Request Integration</a>
                </p>
            </div>
        </div>
    </body>
</html>
