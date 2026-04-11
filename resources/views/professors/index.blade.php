<x-app-layout>
    @section('title', 'Faculty Directory - Review Professors | MyCollegeVerse')
    @section('meta_description', 'Search and review professors from top colleges. Get honest feedback on teaching styles, grading, and course difficulty.')
    <div x-data="{ showProfModal: false }" class="space-y-10 pb-20">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-4xl font-extrabold text-secondary mb-2">Professor Ratings</h1>
                <p class="text-slate-500 font-medium italic">Honest student reviews helping you choose the right mentors.</p>
            </div>
            @if($myPendingRequest)
                <div class="bg-primary/10 text-primary px-8 py-4 rounded-[1.5rem] font-bold border border-primary/20">
                    ⏳ Request Pending: {{ $myPendingRequest->professor_name }}
                </div>
            @else
                <button @click="showProfModal = true" class="bg-primary text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                    Request Your Professor
                </button>
            @endif
        </div>

        {{-- Professor Request Modal --}}
        <div x-show="showProfModal" x-transition.opacity
             class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
             @click.self="showProfModal = false"
             style="display:none">

            <div x-show="showProfModal" x-transition
                 class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8 relative text-slate-900"
                 @click.stop>

                {{-- Close --}}
                <button @click="showProfModal = false" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <div class="mb-6">
                    <h3 class="text-xl font-black text-slate-900">🎓 Request a Professor</h3>
                    <p class="text-xs text-slate-400 font-bold mt-1">Can't find your professor? submit their details and we'll add them.</p>
                </div>

                <form action="{{ route('professors.request') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Professor Name *</label>
                        <input type="text" name="professor_name" required placeholder="e.g. Dr. John Doe"
                               class="w-full h-11 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 placeholder-slate-300" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Department *</label>
                        <input type="text" name="department" required placeholder="e.g. Computer Science"
                               class="w-full h-11 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 placeholder-slate-300" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">College Name *</label>
                        @auth
                            <input type="text" name="college_name" required value="{{ Auth::user()->college->name ?? '' }}" placeholder="e.g. VIT Vellore"
                                   class="w-full h-11 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 placeholder-slate-300" />
                        @else
                            <input type="text" name="college_name" required placeholder="e.g. VIT Vellore"
                                   class="w-full h-11 bg-slate-50 border border-slate-100 rounded-xl px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 placeholder-slate-300" />
                        @endauth
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Additional Notes <span class="text-slate-300">(optional)</span></label>
                        <textarea name="message" rows="3" placeholder="Tell us more about this professor..."
                                  class="w-full bg-slate-50 border border-slate-100 rounded-xl p-4 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 resize-none placeholder-slate-300"></textarea>
                    </div>

                    <button type="submit"
                            class="w-full bg-primary text-white h-12 rounded-xl font-black text-sm shadow-lg shadow-primary/20 hover:bg-primary/90 hover:scale-[1.01] active:scale-[0.99] transition-all">
                        Submit Request 🚀
                    </button>
                </form>
            </div>
        </div>

        <!-- Search & Filter -->
        <div x-data="{ 
            search: '',
            matches(prof) {
                if (!this.search) return true;
                const s = this.search.toLowerCase();
                return prof.name.toLowerCase().includes(s) || 
                       prof.department.toLowerCase().includes(s) ||
                       prof.college.toLowerCase().includes(s);
            }
        }" class="space-y-10">
            <div class="glass p-4 rounded-[2.5rem] shadow-sm border-white/40 flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[300px] relative">
                    <input type="text" x-model="search" @keydown.enter="$el.blur()" placeholder="Search by name, subject, or college..." class="w-full h-14 bg-white/50 border border-slate-100 rounded-2xl px-12 focus:ring-primary/20 focus:border-primary text-sm font-medium">
                    <svg class="absolute left-4 top-4.5 h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Professor Cards -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($professors as $prof)
                <div x-show="matches({ 
                         name: '{{ addslashes($prof->name) }}',
                         department: '{{ addslashes($prof->department) }}',
                         college: '{{ addslashes($prof->college->name) }}'
                     })"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="glass p-8 rounded-[3rem] shadow-glass border-white hover:shadow-xl transition-all group overflow-hidden relative">
                    @auth
                        @if($prof->college_id == Auth::user()->college_id)
                        <div class="absolute top-0 right-0">
                            <div class="bg-primary text-white text-[9px] font-black uppercase px-4 py-1.5 rounded-bl-2xl shadow-sm tracking-widest">
                                Your College
                            </div>
                        </div>
                        @endif
                    @endauth
                    
                    <div class="flex items-center gap-6 mb-8">
                        <img src="{{ $prof->profile_pic ?? 'https://ui-avatars.com/api/?name='.urlencode($prof->name).'&background=primary&color=fff' }}" class="w-16 h-16 rounded-2xl shadow-xl shadow-primary/10" alt="{{ $prof->name }}" />
                        <div>
                            <h4 class="text-xl font-extrabold text-slate-900 group-hover:text-primary transition-colors">{{ $prof->name }}</h4>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $prof->department }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        @php 
                            $avg = $prof->reviews->avg('rating');
                            $count = $prof->reviews->count();
                        @endphp
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-slate-500">Reviews</span>
                            <span class="text-primary font-black">{{ $count }} Students</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-slate-500">Popularity</span>
                            <span class="text-amber-600 font-black">Trending in Campus</span>
                        </div>
                        <div class="flex items-center gap-2 pt-2">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $avg ? 'text-amber-400 fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M10 1l2.6 6.3h6.6l-5.3 4.1 2 6.6-5.9-4.5-5.9 4.5 2-6.6-5.3-4.1h6.6z"/></svg>
                            @endfor
                            <span class="ml-2 text-xs font-black text-slate-800">{{ number_format($avg, 1) }} / 5.0</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-400 tracking-wide">{{ $prof->college->name }}</p>
                        <a href="{{ route('professors.show', $prof->id) }}" class="text-primary font-bold text-sm hover:underline">Rate & Review</a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center glass rounded-[3rem]">
                    <p class="text-slate-400 font-bold italic">No professors listed for your criteria yet.</p>
                </div>
                @endforelse

                <!-- No Results State -->
                <div x-show="!$el.parentElement.querySelector('div[x-show]:not([style*=\'display: none\'])')" 
                     class="col-span-full py-12 text-center glass rounded-[3rem]" 
                     style="display: none;">
                    <p class="text-slate-400 font-bold italic">No professors found matching your search.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
