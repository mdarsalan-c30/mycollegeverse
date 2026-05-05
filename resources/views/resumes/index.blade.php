<x-app-layout>
    @section('title', 'My Resume Vault | Student Career OS')
    @section('meta_description', 'Manage your professional identities and proof-of-work resumes on MyCollegeVerse.')

    <div class="min-h-screen bg-slate-50 py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase">My Resumes <span class="text-primary">/ Verse Vault</span></h1>
                    <p class="text-slate-500 font-bold mt-2">Manage your career manifestos and proof-of-work identities.</p>
                </div>
                <a href="{{ route('resumes.create') }}" class="bg-primary text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-primary-dark transition-all shadow-xl shadow-primary/20 text-center">
                    + Create New Identity
                </a>
            </div>

            @if($resumes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($resumes as $resume)
                <div class="bg-white rounded-3xl p-8 border border-slate-200 hover:border-primary/30 transition-all group relative overflow-hidden shadow-sm hover:shadow-xl">
                    <div class="absolute top-0 left-0 w-1 h-full bg-primary transform -translate-x-full group-hover:translate-x-0 transition-transform"></div>
                    
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400 group-hover:text-primary group-hover:bg-primary/10 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $resume->views_count }} Views</span>
                        </div>
                    </div>

                    <h3 class="text-xl font-black text-slate-800 mb-2 truncate">{{ $resume->title }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8">Manifested {{ $resume->created_at->diffForHumans() }}</p>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('resumes.show', $resume->slug) }}" class="flex-1 bg-slate-900 text-white py-3 rounded-xl font-black text-center text-xs uppercase tracking-widest hover:bg-slate-800 transition-colors">View</a>
                        <form action="{{ route('resumes.destroy', $resume->id) }}" method="POST" onsubmit="return confirm('Archive this identity forever?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-slate-100 text-slate-400 p-3 rounded-xl hover:text-rose-500 hover:bg-rose-50 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-[40px] p-20 border-4 border-dashed border-slate-200 text-center">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">No Identities Detected</h2>
                <p class="text-slate-400 font-bold mt-2 max-w-md mx-auto">Your career manifestos will appear here. Start building your first professional identity today.</p>
                <a href="{{ route('resumes.create') }}" class="inline-block mt-10 bg-primary text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-primary/20">Forge My First Resume</a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
