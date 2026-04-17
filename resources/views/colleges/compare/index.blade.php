<x-verse-layout>
    @section('title', 'Compare Colleges Side-by-Side | MyCollegeVerse')

    <div class="space-y-16 pb-32">
        <!-- Hero Picker -->
        <div class="bg-primary rounded-[4rem] p-12 md:p-24 text-center text-white relative overflow-hidden shadow-2xl">
            <div class="relative z-10 max-w-3xl mx-auto space-y-8">
                <h1 class="text-4xl md:text-7xl font-black leading-tight tracking-tighter">
                    Which one is <br><span class="text-primary-100 italic">the winner?</span>
                </h1>
                <p class="text-primary-100/70 text-lg md:text-xl font-medium leading-relaxed">
                    Pick 2 or 3 colleges and see the cold, hard data side-by-side. Verified packages, faculty ratings, and real student outcomes.
                </p>

                <form action="{{ route('compare.redirect') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid md:grid-cols-3 gap-4" x-data="{ count: 2 }">
                        @foreach(range(1, 3) as $i)
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em]">Node {{ $i }}</label>
                            <select name="colleges[]" class="w-full bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl py-4 px-6 text-white text-sm outline-none focus:ring-2 focus:ring-white">
                                <option value="" class="bg-slate-900">Search institutional node...</option>
                                @foreach(\App\Models\College::all() as $college)
                                <option value="{{ $college->slug }}" @if(request('c1') == $college->slug && $i == 1) selected @endif class="bg-slate-900">{{ $college->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-white text-primary px-16 py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl hover:scale-105 transition-all">
                        Launch Battle Mode ⚔️
                    </button>
                </form>
            </div>
            
            <div class="absolute -left-20 -top-20 w-96 h-96 bg-white/5 rounded-full blur-[100px]"></div>
        </div>

        <!-- Trending Battles -->
        <div class="space-y-10">
            <div class="text-center md:text-left space-y-2">
                <h3 class="text-2xl md:text-4xl font-black text-slate-900 tracking-tight">Trending Institutional Duel</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Most compared pairs this week</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($trendingComparisons as $pair)
                <a href="{{ route('compare.show', $pair['slug']) }}" class="group bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-primary/10 hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="relative z-10 flex flex-col items-center gap-6">
                        <div class="flex items-center -space-x-4">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl border-4 border-white flex items-center justify-center text-3xl shadow-lg">🏛️</div>
                            <div class="w-10 h-10 bg-primary text-white rounded-full border-4 border-white flex items-center justify-center text-[10px] font-black italic relative z-10 shadow-lg">VS</div>
                            <div class="w-16 h-16 bg-slate-100 rounded-2xl border-4 border-white flex items-center justify-center text-3xl shadow-lg">🏛️</div>
                        </div>
                        <h4 class="text-xl font-black text-slate-900 group-hover:text-primary transition-colors text-center">{{ $pair['label'] }}</h4>
                        <span class="text-[8px] font-black text-primary uppercase tracking-[0.3em] bg-primary/5 px-4 py-2 rounded-full">Analyze Duel</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- FAQ Node -->
        <div class="bg-slate-50 rounded-[4rem] p-12 md:p-20 grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <h3 class="text-3xl font-black text-slate-900 leading-tight">Better logic for a <br><span class="text-primary italic">Better Future.</span></h3>
                <p class="text-slate-500 font-medium leading-relaxed italic">
                    Why look at separate brochures when you can compare reality side-by-side? Our engine uses peer-verified packages and community insights to give you the truth.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                    <p class="text-2xl font-black text-primary">100%</p>
                    <p class="text-[9px] font-black text-slate-400 uppercase mt-1">Peer Verified</p>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                    <p class="text-2xl font-black text-indigo-600">30k+</p>
                    <p class="text-[9px] font-black text-slate-400 uppercase mt-1">Analysis Runs</p>
                </div>
            </div>
        </div>
    </div>
</x-verse-layout>
