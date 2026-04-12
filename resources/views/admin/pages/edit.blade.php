<x-admin-layout>
    <x-slot name="title">Recalibrate Page | Admin Control Tower</x-slot>

    <div class="max-w-4xl space-y-8">
        <div>
            <h1 class="text-2xl font-black text-admin-dark tracking-tight">Recalibrate Page Node</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Editing: {{ $page->title }}</p>
        </div>

        <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title Node -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Page Title</label>
                    <input type="text" name="title" value="{{ old('title', $page->title) }}" required class="w-full h-12 bg-white border border-admin-border rounded-2xl px-6 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    @error('title') <p class="text-red-500 text-[10px] italic">{{ $message }}</p> @enderror
                </div>

                <!-- Slug Node -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Slug Node</label>
                    <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" required class="w-full h-12 bg-white border border-admin-border rounded-2xl px-6 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                    @error('slug') <p class="text-red-500 text-[10px] italic">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Meta Node -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Meta Description (SEO)</label>
                <input type="text" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}" class="w-full h-12 bg-white border border-admin-border rounded-2xl px-6 text-xs font-bold focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all">
                @error('meta_description') <p class="text-red-500 text-[10px] italic">{{ $message }}</p> @enderror
            </div>

            <!-- Content Terminal -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Content Terminal</label>
                <textarea name="content" rows="15" required class="w-full bg-white border border-admin-border rounded-3xl p-8 text-xs font-medium focus:ring-4 focus:ring-admin-primary/5 focus:border-admin-primary transition-all leading-relaxed custom-scrollbar">{{ old('content', $page->content) }}</textarea>
                @error('content') <p class="text-red-500 text-[10px] italic">{{ $message }}</p> @enderror
            </div>

            <!-- Activation Node -->
            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $page->is_active ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-admin-primary"></div>
                </label>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Node Activation Status</span>
            </div>

            <div class="pt-6 flex gap-4">
                <button type="submit" class="bg-admin-primary text-white px-10 py-4 rounded-2xl font-black text-xs shadow-xl shadow-admin-primary/25 hover:scale-105 transition-transform flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    SYNC RECALIBRATIONS
                </button>
                <a href="{{ route('admin.pages.index') }}" class="bg-slate-100 text-slate-500 px-10 py-4 rounded-2xl font-black text-xs hover:bg-slate-200 transition-colors">CANCEL</a>
            </div>
        </form>
    </div>
</x-admin-layout>
