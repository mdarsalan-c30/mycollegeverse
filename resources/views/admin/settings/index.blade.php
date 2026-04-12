<x-admin-layout>
    <div class="space-y-8">
        <!-- Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary tracking-tight">System Command Cluster</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Orchestrate global thresholds and auto-moderation rules</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="px-6 py-3 bg-white border border-admin-border rounded-2xl shadow-sm flex items-center gap-4">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Core Engine: <span class="text-indigo-600">OPTIMIZED</span></span>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8 italic">
            @csrf
            <!-- Platform Identity -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white border border-admin-border rounded-[2.5rem] p-10 space-y-8 shadow-sm">
                    <div class="flex items-center gap-4 border-b border-slate-50 pb-6">
                        <div class="w-12 h-12 bg-admin-primary/10 text-admin-primary rounded-2xl flex items-center justify-center text-xl">🌐</div>
                        <div>
                            <h3 class="text-lg font-black text-admin-secondary leading-none">Platform Identity</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Global metadata nodes</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Registry Name</label>
                            <input type="text" name="site_name" value="{{ $settings['site_name'] }}" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Footer Signature</label>
                            <input type="text" name="footer_text" value="{{ $settings['footer_text'] }}" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Moderation Control -->
                <div class="bg-white border border-admin-border rounded-[2.5rem] p-10 space-y-8 shadow-sm">
                    <div class="flex items-center gap-4 border-b border-slate-50 pb-6">
                        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center text-xl">🛡️</div>
                        <div>
                            <h3 class="text-lg font-black text-admin-secondary leading-none">Security Thresholds</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Auto-moderation & Safeguards</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Max File Node Size (MB)</label>
                                <input type="number" name="max_file_size" value="{{ $settings['max_file_size'] }}" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Auto-Hide Threshold (Reports)</label>
                                <input type="number" name="auto_hide_reports" value="{{ $settings['auto_hide_reports'] }}" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 transition-all">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Flagged Keyword Array (Social Safety)</label>
                            <textarea name="flagged_keywords" rows="3" class="w-full bg-slate-50 border-none rounded-[2rem] px-8 py-6 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 transition-all italic">{{ $settings['flagged_keywords'] }}</textarea>
                            <p class="text-[9px] font-bold text-slate-300 ml-4 uppercase tracking-tighter">Enter keywords separated by commas. Surfaced in the Chat Monitor terminal.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Panel -->
            <div class="space-y-8">
                <div class="bg-admin-dark rounded-[2.5rem] p-10 text-white space-y-6 shadow-xl shadow-slate-200">
                    <h3 class="text-xl font-black tracking-tight leading-none">Command Hub</h3>
                    <p class="text-xs font-bold text-slate-400 leading-relaxed italic">Synchronize all global meta-data nodes across the multiverse. Warning: Changes are immediate.</p>
                    
                    <button type="submit" class="w-full py-5 bg-white text-admin-dark rounded-2xl text-[11px] font-black uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-lg">
                        Synchronize Engine
                    </button>
                    
                    <div class="pt-6 border-t border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <p class="text-[9px] font-black uppercase tracking-widest opacity-60">Authentication Authority Verified</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-admin-border rounded-[2.5rem] p-8 space-y-4 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">System Diagnostics</p>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-[11px] font-bold">
                            <span class="text-slate-500">Node Status</span>
                            <span class="text-green-500">Operational</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] font-bold">
                            <span class="text-slate-500">Security Layers</span>
                            <span class="text-indigo-600">TLS 1.3 | AES-256</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
