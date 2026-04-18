<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-6">
        <!-- Header -->
        <div class="text-center space-y-4 mb-12">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Manifest Your Talent 🛡️</h1>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] italic">Synchronizing your professional artifacts with the Multiverse</p>
        </div>

        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="{ 
            coverPreview: null,
            loading: false,
            handleCover(e) {
                const file = e.target.files[0];
                if (file) this.coverPreview = URL.createObjectURL(file);
            }
        }" @submit="loading = true">
            @csrf

            <!-- Core Identity -->
            <div class="glass p-10 rounded-[3rem] border-white shadow-glass space-y-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Manifest Title</label>
                        <input type="text" name="title" required placeholder="e.g. Zomato Valuation Report" value="{{ old('title') }}"
                               class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-primary/10 outline-none transition-all">
                        @error('title') <p class="text-[10px] font-bold text-rose-500 px-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Academic Stream</label>
                        <select name="stream" required class="w-full bg-slate-50 border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-primary/10 outline-none transition-all">
                            @foreach($streams as $stream)
                                <option value="{{ $stream }}" {{ old('stream') == $stream ? 'selected' : '' }}>{{ $stream }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Impact Summary (Description)</label>
                    <textarea name="description" required rows="4" placeholder="Explain the depth, methodology, and results of this PoW..."
                              class="w-full bg-slate-50 border-slate-100 rounded-[2rem] px-6 py-5 text-sm font-bold focus:ring-4 focus:ring-primary/10 outline-none transition-all">{{ old('description') }}</textarea>
                    @error('description') <p class="text-[10px] font-bold text-rose-500 px-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Artifact Manifestation -->
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Documentation Upload -->
                <div class="glass p-10 rounded-[3rem] border-white shadow-glass group hover:border-primary/20 transition-all">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">📄</div>
                        <div>
                            <h4 class="text-sm font-black text-slate-800">Primary Artifact</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">PDF, DOC, PPT or ZIP (Max 20MB)</p>
                        </div>
                        <input type="file" name="file" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer"/>
                        @error('file') <p class="text-[10px] font-bold text-rose-500 px-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Portfolio Cover Upload -->
                <div class="glass p-10 rounded-[3rem] border-white shadow-glass group hover:border-primary/20 transition-all relative overflow-hidden">
                    <div class="space-y-4 relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">🖼️</div>
                        <div>
                            <h4 class="text-sm font-black text-slate-800">Portfolio Cover</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Mandatory for High-Fidelity Gallery</p>
                        </div>
                        <input type="file" name="cover_image" required @change="handleCover" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100 cursor-pointer"/>
                        @error('cover_image') <p class="text-[10px] font-bold text-rose-500 px-2">{{ $message }}</p> @enderror
                    </div>
                    
                    <template x-if="coverPreview">
                        <div class="absolute inset-0 z-0">
                            <img :src="coverPreview" class="w-full h-full object-cover opacity-10 blur-sm"/>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Action -->
            <div class="pt-6">
                <button type="submit" :disabled="loading"
                        class="w-full h-20 bg-slate-900 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-sm shadow-2xl shadow-slate-900/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-4">
                    <template x-if="!loading">
                        <span class="flex items-center gap-4">Initialize Manifestation ⚡</span>
                    </template>
                    <template x-if="loading">
                        <span class="flex items-center gap-4">Synchronizing with Verse... <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                    </template>
                </button>
                <p class="text-center text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-8">Manifesting quality artifacts rewards you with +50 ARS Visibility Score</p>
            </div>
        </form>
    </div>
</x-app-layout>
