<x-recruiter-layout>
<div class="space-y-10" x-data="{
    selected: [],
    showBulkModal: false,
    bulkAction: '',
    bulkMessage: '',
    interviewLink: '',
    interviewDate: '',
    allIds: {{ $applications->pluck('id')->toJson() }},

    toggleAll(checked) {
        this.selected = checked ? [...this.allIds] : [];
    },
    isAllSelected() {
        return this.allIds.length > 0 && this.selected.length === this.allIds.length;
    },
    openBulk(action) {
        if (this.selected.length === 0) { alert('Pehle candidates select karo!'); return; }
        this.bulkAction = action;
        this.showBulkModal = true;
    }
}">

    {{-- Floating Bulk Action Bar --}}
    <div x-show="selected.length > 0" x-transition
         class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 bg-slate-900 text-white px-8 py-4 rounded-[2rem] shadow-2xl shadow-black/30 flex items-center gap-6 border border-white/10">
        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400" x-text="selected.length + ' Candidates Selected'"></span>
        <div class="w-px h-6 bg-white/20"></div>
        <button @click="openBulk('message')" class="h-9 px-5 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">
            📨 Bulk Message
        </button>
        <button @click="openBulk('interview')" class="h-9 px-5 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">
            🔗 Send Interview Link
        </button>
        <button @click="openBulk('shortlist')" class="h-9 px-5 bg-amber-400 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">
            ⭐ Shortlist All
        </button>
        <button @click="openBulk('reject')" class="h-9 px-5 bg-rose-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">
            ❌ Reject All
        </button>
        <button @click="selected = []" class="text-slate-400 hover:text-white transition-colors text-xs">✕ Clear</button>
    </div>

    {{-- Bulk Action Modal --}}
    <div x-show="showBulkModal" x-transition class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-6">
        <div @click.outside="showBulkModal = false" class="bg-white rounded-[2.5rem] p-10 w-full max-w-lg shadow-2xl space-y-6">
            <div>
                <h2 class="text-2xl font-black text-slate-800 mb-1" x-text="bulkAction === 'interview' ? '🔗 Send Interview Invite' : bulkAction === 'message' ? '📨 Bulk Message' : bulkAction === 'shortlist' ? '⭐ Shortlist Candidates' : '❌ Bulk Reject'"></h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="'Sending to ' + selected.length + ' candidates'"></p>
            </div>

            <form id="bulkForm" action="{{ route('recruiter.bulk.action') }}" method="POST">
                @csrf
                <input type="hidden" name="action" :value="bulkAction">
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="application_ids[]" :value="id">
                </template>

                {{-- Message field --}}
                <div x-show="bulkAction === 'message' || bulkAction === 'interview'" class="space-y-4">
                    <div x-show="bulkAction === 'interview'">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Interview / Meeting Link</label>
                        <input type="url" name="interview_link" x-model="interviewLink"
                               placeholder="https://meet.google.com/xxx-xxxx"
                               class="w-full h-12 bg-slate-50 border border-slate-200 rounded-2xl px-5 text-sm font-bold">
                    </div>
                    <div x-show="bulkAction === 'interview'">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Interview Date & Time</label>
                        <input type="datetime-local" name="interview_date" x-model="interviewDate"
                               class="w-full h-12 bg-slate-50 border border-slate-200 rounded-2xl px-5 text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                            <span x-show="bulkAction === 'message'">Your Message</span>
                            <span x-show="bulkAction === 'interview'">Additional Note (Optional)</span>
                        </label>
                        <textarea name="message" x-model="bulkMessage" rows="4"
                                  placeholder="Hi {name}, we would like to..."
                                  class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold resize-none"></textarea>
                        <p class="text-[9px] text-slate-400 mt-1">💡 Use <code class="bg-slate-100 px-1 rounded">{name}</code> to auto-personalize for each candidate</p>
                    </div>
                </div>

                {{-- Confirm for shortlist/reject --}}
                <div x-show="bulkAction === 'shortlist' || bulkAction === 'reject'" class="bg-slate-50 rounded-2xl p-6 text-center">
                    <p class="font-black text-slate-700 text-sm" x-text="'Are you sure you want to ' + (bulkAction === 'shortlist' ? 'shortlist' : 'reject') + ' all ' + selected.length + ' selected candidates?'"></p>
                    <p class="text-[10px] text-slate-400 mt-2">Each candidate will receive an automatic notification via Nexus Chat.</p>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 h-12 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all">
                        🚀 Send Signal
                    </button>
                    <button type="button" @click="showBulkModal = false" class="h-12 px-6 bg-slate-100 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 px-10 pt-10">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 mb-2">
                <a href="{{ route('recruiter.dashboard') }}" class="text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-widest">Dashboard</a>
                <span class="text-slate-300">/</span>
                <span class="text-[10px] font-black text-primary uppercase tracking-widest">Applicant Bench</span>
            </nav>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $job->title }}</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $applications->count() }} Candidates — <span class="text-primary" x-text="selected.length > 0 ? selected.length + ' Selected' : 'None Selected'"></span></p>
        </div>

        {{-- Filter Shortcuts --}}
        <div class="flex gap-2">
            <button @click="selected = {{ $applications->where('status','pending')->pluck('id')->toJson() }}"
                    class="h-9 px-5 bg-amber-50 text-amber-600 border border-amber-200 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-100 transition-all">
                Select Pending ({{ $applications->where('status','pending')->count() }})
            </button>
            <button @click="selected = {{ $applications->where('status','reviewed')->pluck('id')->toJson() }}"
                    class="h-9 px-5 bg-blue-50 text-blue-600 border border-blue-200 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-100 transition-all">
                Select Reviewed ({{ $applications->where('status','reviewed')->count() }})
            </button>
        </div>
    </div>

    <div class="px-10 pb-10">
        <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-slate-100">
            <div class="overflow-visible pb-72">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-slate-100 italic">
                            <th class="pb-6 w-12">
                                <input type="checkbox" @change="toggleAll($event.target.checked)" :checked="isAllSelected()"
                                       class="w-4 h-4 rounded accent-primary cursor-pointer">
                            </th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Candidate</th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">College</th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">ARS Score</th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Brief</th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($applications as $app)
                        <tr x-data="{ showBrief: false }"
                            :class="selected.includes({{ $app->id }}) ? 'bg-primary/5' : 'hover:bg-slate-50/50'"
                            class="transition-colors group">

                            {{-- Checkbox --}}
                            <td class="py-6">
                                <input type="checkbox" :value="{{ $app->id }}" x-model="selected"
                                       class="w-4 h-4 rounded accent-primary cursor-pointer">
                            </td>

                            {{-- Candidate --}}
                            <td class="py-6">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $app->student->profile_photo_url }}" class="w-12 h-12 rounded-2xl object-cover shadow-sm"/>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 leading-none">{{ $app->student->name }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1">@{{ $app->student->username }}</p>
                                        @if($app->student->career_role)
                                            <span class="text-[8px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-bold mt-1 inline-block">{{ $app->student->career_role }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- College --}}
                            <td class="py-6 text-center">
                                <span class="px-3 py-1.5 bg-white border border-slate-100 rounded-lg text-[10px] font-bold text-slate-500 uppercase">
                                    {{ $app->student->college->name ?? 'External' }}
                                </span>
                            </td>

                            {{-- ARS Score --}}
                            <td class="py-6 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-lg font-black text-primary">{{ $app->student->ars_score }}</span>
                                    <span class="text-[8px] text-slate-400 font-bold uppercase">ARS</span>
                                </div>
                            </td>

                            {{-- Brief --}}
                            <td class="py-6 max-w-xs text-center">
                                <button @click="showBrief = !showBrief" class="text-[10px] font-black text-primary uppercase tracking-widest flex items-center justify-center gap-2 hover:underline w-full">
                                    View Brief
                                    <svg :class="showBrief ? 'rotate-180' : ''" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="showBrief" x-collapse x-cloak class="mt-4 space-y-3 text-left bg-slate-50 p-4 rounded-2xl">
                                    <div>
                                        <p class="text-[8px] font-black text-slate-400 uppercase mb-1">About</p>
                                        <p class="text-[11px] font-medium text-slate-600 leading-relaxed">{{ $app->about_me }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Why Hire</p>
                                        <p class="text-[11px] font-medium text-slate-600 leading-relaxed">{{ $app->why_hire }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="py-6 text-center">
                                <span class="px-3 py-1.5 text-[9px] font-black rounded-lg uppercase tracking-widest border
                                    {{ $app->status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : 
                                       ($app->status === 'rejected' ? 'bg-red-50 text-red-600 border-red-100' : 
                                       ($app->status === 'shortlisted' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' :
                                       'bg-blue-50 text-blue-600 border-blue-100')) }}">
                                    {{ $app->status }}
                                </span>
                            </td>

                            {{-- Individual Actions --}}
                            <td class="py-6 text-right">
                                <div class="flex items-center justify-end gap-2" x-data="{ openMenu: false }">
                                    <a href="{{ explode('/-/', $app->resume_shared_link)[0] }}" target="_blank"
                                       class="h-10 px-4 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl flex items-center gap-2 hover:scale-105 transition-all">
                                        📄 CV
                                    </a>
                                    <div class="relative">
                                        <button @click="openMenu = !openMenu" class="h-10 w-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-slate-100 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                        </button>
                                        <div x-show="openMenu" @click.away="openMenu = false" class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-2xl border border-slate-100 p-2 z-50">
                                            <form action="{{ route('recruiter.applications.status', $app->id) }}" method="POST">
                                                @csrf
                                                <button name="status" value="shortlisted" class="w-full text-left px-4 py-2 text-[9px] font-black text-green-600 uppercase tracking-widest hover:bg-green-50 rounded-xl">⭐ Shortlist</button>
                                                <button name="status" value="reviewed" class="w-full text-left px-4 py-2 text-[9px] font-black text-slate-600 uppercase tracking-widest hover:bg-slate-50 rounded-xl">👁 Mark Reviewed</button>
                                                <button name="status" value="rejected" class="w-full text-left px-4 py-2 text-[9px] font-black text-red-600 uppercase tracking-widest hover:bg-red-50 rounded-xl">❌ Decline</button>
                                            </form>
                                            <div class="h-px bg-slate-50 my-2"></div>
                                            <a href="{{ route('chat.index', $app->student->username) }}" class="block px-4 py-2 text-[9px] font-black text-primary uppercase tracking-widest hover:bg-primary/5 rounded-xl">💬 Direct Chat</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-20 text-center">
                                <div class="text-4xl mb-4 opacity-20">📡</div>
                                <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">No Applicants Yet</h4>
                                <p class="text-xs font-bold text-slate-300 mt-2 italic">No candidates have applied for this role yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-recruiter-layout>
