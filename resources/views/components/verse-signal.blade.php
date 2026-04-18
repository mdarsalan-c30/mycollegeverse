<div class="flex items-center gap-4" x-data="{ 
    isOpen: false, 
    loading: false, 
    notifications: [], 
    unreadCount: {{ auth()->check() ? auth()->user()->unreadNotifications->count() : 0 }},
    async fetchNotifications() {
        if (this.notifications.length > 0 && !this.loading) return;
        this.loading = true;
        try {
            const res = await fetch('{{ route('notifications.index') }}');
            const data = await res.json();
            if (data.status === 'success') {
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            }
        } catch (e) { console.error('Signal Hub Failure', e); }
        this.loading = false;
    },
    async markAsRead(id, url) {
        try {
            await fetch(`/api/notifications/${id}/read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            window.location.href = url;
        } catch (e) { window.location.href = url; }
    },
    async markAllRead() {
        try {
            await fetch('{{ route('notifications.mark-all-read') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            this.unreadCount = 0;
            this.notifications.forEach(n => n.read_at = new Date());
        } catch (e) { console.error('Clear Signals Failed', e); }
    }
}">
    <div class="relative">
        <button @click="isOpen = !isOpen; if(isOpen) fetchNotifications()" 
                class="w-12 h-12 flex items-center justify-center glass rounded-xl text-slate-500 hover:text-primary transition-all relative"
                :class="isOpen ? 'bg-slate-50 text-primary' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <template x-if="unreadCount > 0">
                <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse"></span>
            </template>
        </button>

        {{-- Dropdown Menu --}}
        <div x-show="isOpen" 
             @click.away="isOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="absolute right-0 mt-3 w-80 sm:w-96 glass bg-white/95 backdrop-blur-xl rounded-[2.5rem] border border-slate-100 shadow-2xl overflow-hidden z-[100]"
             x-cloak>
            
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Verse Signals</h4>
                <button @click="markAllRead()" class="text-[9px] font-black text-primary hover:underline uppercase tracking-widest">Clear All</button>
            </div>

            <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                <template x-if="loading">
                    <div class="p-12 text-center space-y-4">
                        <div class="w-8 h-8 border-4 border-primary/20 border-t-primary rounded-full animate-spin mx-auto"></div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Decrypting Signals...</p>
                    </div>
                </template>

                <template x-if="!loading && notifications.length === 0">
                    <div class="p-12 text-center space-y-3">
                        <div class="text-3xl opacity-30">📡</div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">Multiverse clear. <br>No incoming academic signals detected.</p>
                    </div>
                </template>

                <div class="divide-y divide-slate-50">
                    <template x-for="n in notifications" :key="n.id">
                        <div @click="markAsRead(n.id, n.data.action_url)" 
                             class="p-6 hover:bg-slate-50 transition-all cursor-pointer group relative"
                             :class="!n.read_at ? 'bg-primary/[0.02]' : ''">
                            
                            <div class="flex gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-xl group-hover:scale-110 transition-transform shrink-0"
                                     x-text="n.data.icon || '⚡'">
                                </div>
                                <div class="space-y-1 min-w-0">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-primary" x-text="n.data.title"></p>
                                    <p class="text-xs font-bold text-slate-700 leading-tight" x-text="n.data.message"></p>
                                    <div class="flex items-center gap-3 pt-1">
                                        <p class="text-[9px] font-bold text-slate-400" x-text="n.created_at"></p>
                                        <template x-if="n.data.due_date">
                                            <span class="text-[9px] font-black text-rose-500 uppercase tracking-tight" x-text="'Due: ' + n.data.due_date"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            
                            <template x-if="!n.read_at">
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-primary rounded-full"></div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-50 text-center">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Signal Protocol v1.0 • End-to-End Latency: Minimal</p>
            </div>
        </div>
    </div>
</div>
