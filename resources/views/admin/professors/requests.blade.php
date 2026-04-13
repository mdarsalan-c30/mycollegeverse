<x-admin-layout>
    @section('title', 'Faculty Moderation Queue | Admin')
    
    <div x-data="{ 
        showApproveModal: false, 
        selectedRequest: null,
        collegeId: '',
        profName: '',
        profDept: '',
        openApprove(req) {
            this.selectedRequest = req;
            this.profName = req.professor_name;
            this.profDept = req.department;
            // Default to user's college if available
            this.collegeId = req.user && req.user.college_id ? req.user.college_id : '';
            this.showApproveModal = true;
        }
    }" class="space-y-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary">Faculty Requests</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Moderating student-suggested academic nodes</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.professors') }}" class="px-6 py-3 bg-white border border-admin-border rounded-xl shadow-sm text-[10px] font-black text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-colors">
                    Back to Registry
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest">
                {{ session('success') }}
            </div>
        @endif

        <!-- Requests Grid -->
        <div class="bg-white border border-admin-border rounded-[2.5rem] overflow-hidden shadow-xl shadow-slate-200/40">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-admin-border">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Requester</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Professor Details</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Proposed Institution</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 italic">
                    @forelse($requests as $req)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <img src="{{ $req->user->profile_photo_url }}" class="w-10 h-10 rounded-xl shadow-sm border border-white" alt="">
                                <div>
                                    <p class="text-xs font-black text-admin-dark">{{ $req->user->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $req->user->college->name ?? 'External Citizen' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div>
                                <p class="text-sm font-black text-admin-secondary">{{ $req->professor_name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $req->department }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <span class="text-slate-300">🏢</span>
                                <p class="text-[11px] font-bold text-slate-500">{{ $req->college_name }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-xs font-black uppercase">
                            <span class="px-3 py-1 rounded-full text-[9px] {{ $req->status == 'pending' ? 'bg-amber-500/10 text-amber-600' : ($req->status == 'approved' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600') }}">
                                {{ $req->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            @if($req->status == 'pending')
                            <div class="flex items-center justify-end gap-2">
                                <button @click="openApprove({{ json_encode($req) }})" class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                </button>
                                <form action="{{ route('admin.professors.reject', $req->id) }}" method="POST" onsubmit="return confirm('Banish this request?')">
                                    @csrf
                                    <button class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </form>
                            </div>
                            @else
                                <span class="text-[9px] font-bold text-slate-300 uppercase italic">Immutable State</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <p class="text-slate-300 font-bold uppercase tracking-[0.3em] text-[10px]">Registry Quiescent (No Pending Requests)</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-8 py-6 bg-slate-50/30 border-t border-slate-50">
                {{ $requests->links() }}
            </div>
        </div>

        <!-- Approval Modal (Alpine.js Powered) -->
        <div x-show="showApproveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl p-10 relative overflow-hidden" @click.away="showApproveModal = false">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full -mr-32 -mt-32"></div>
                
                <div class="relative z-10 space-y-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-black text-admin-secondary italic">Node Validation</h3>
                        <button @click="showApproveModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <form :action="'{{ url('/mcv-admin/professors/requests') }}/' + (selectedRequest ? selectedRequest.id : '') + '/approve'" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Professor Name</label>
                            <input type="text" name="name" x-model="profName" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500/20">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Department</label>
                            <input type="text" name="department" x-model="profDept" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500/20">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mapped Institution (College)</label>
                            <select name="college_id" x-model="collegeId" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500/20 appearance-none">
                                <option value="">Select Target Institution...</option>
                                @foreach($colleges as $college)
                                <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-[9px] text-slate-400 font-medium italic mt-1">Student suggested: <span class="text-admin-primary" x-text="selectedRequest ? selectedRequest.college_name : ''"></span></p>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-admin-secondary text-white h-16 rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-admin-secondary/20 hover:bg-admin-dark">
                                Establish Faculty Node ⚡
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
