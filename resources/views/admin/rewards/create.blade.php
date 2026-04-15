<x-admin-layout>
    @section('title', 'Manifest Perk | Control Tower')

    <div class="max-w-4xl mx-auto space-y-10">
        <!-- Header Node -->
        <div>
            <h1 class="text-4xl font-black text-secondary tracking-tight">Manifest Perk</h1>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-2 italic">Inject a New Reward Asset into the Multiverse Pool</p>
        </div>

        <form action="{{ route('admin.rewards.store') }}" method="POST" class="space-y-8">
            @csrf

            <div class="glass p-10 rounded-[2.5rem] border-white/60 shadow-sm space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Title -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Perk Title</label>
                        <input type="text" name="title" required value="{{ old('title') }}" 
                            class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-secondary focus:ring-2 focus:ring-admin-primary/20 placeholder:text-slate-300 transition-all"
                            placeholder="e.g. Master React in 30 Days (Udemy)">
                    </div>

                    <!-- Karma Required -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Point Threshold (KP)</label>
                        <input type="number" name="karma_required" required value="{{ old('karma_required', 500) }}"
                            class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-secondary focus:ring-2 focus:ring-admin-primary/20 transition-all">
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Insight & Details</label>
                    <textarea name="description" required rows="3"
                        class="w-full bg-slate-50 border-none rounded-3xl px-6 py-4 text-sm font-bold text-secondary focus:ring-2 focus:ring-admin-primary/20 placeholder:text-slate-300 transition-all"
                        placeholder="What are they getting? Why is this valuable?">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Max Usage -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Usage Limit (Stock)</label>
                        <input type="number" name="max_usage" required value="{{ old('max_usage', 100) }}"
                            class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-secondary focus:ring-2 focus:ring-admin-primary/20 transition-all">
                    </div>

                    <!-- Expiry at -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Expeditions Expiry (Optional)</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                            class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-secondary focus:ring-2 focus:ring-admin-primary/20 transition-all">
                    </div>
                </div>

                <!-- Claim Link -->
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Redemption Nexus URL (Udemy Coupon Link)</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </span>
                        <input type="url" name="claim_link" required value="{{ old('claim_link') }}"
                            class="w-full bg-slate-50 border-none rounded-2xl pl-14 pr-6 py-4 text-sm font-bold text-secondary focus:ring-2 focus:ring-admin-primary/20 placeholder:text-slate-300 transition-all"
                            placeholder="https://www.udemy.com/course/.../?couponCode=XYZ">
                    </div>
                    <p class="text-[9px] font-bold text-rose-400 ml-1 mt-1 uppercase tracking-wider">Visible only AFTER a successful Karma transaction.</p>
                </div>

                <!-- Status Checkbox -->
                <div class="flex items-center gap-3 ml-1">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                        class="w-5 h-5 rounded-lg border-slate-200 text-admin-primary focus:ring-admin-primary/20 transition-all">
                    <label for="is_active" class="text-xs font-black text-slate-500 uppercase tracking-widest">Activate Perk Immediately</label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.rewards.index') }}" class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-secondary transition-colors">Abort Mission</a>
                <button type="submit" class="px-12 py-4 bg-admin-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-admin-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Initialize Redistribution
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
