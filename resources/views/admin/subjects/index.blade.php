<x-admin-layout>
    <div class="space-y-8" x-data="{ openCreate: false }">
        <!-- Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-admin-secondary tracking-tight">Subject Hub</h2>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Mapping knowledge nodes to specific semesters</p>
            </div>
            
            <button type="button" @click="openCreate = true" class="px-6 py-4 bg-admin-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-admin-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                Initialize Subject
            </button>
        </div>

        <!-- Subject Registry Table -->
        <div class="bg-white border border-admin-border rounded-[2.5rem] overflow-hidden shadow-sm shadow-slate-100">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-admin-border">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Subject Node</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Academic Path</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Semester</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 italic">
                    @forelse($subjects as $subject)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <p class="text-sm font-black text-admin-dark">{{ $subject->name }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-4 py-1.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-full">
                                {{ $subject->course->name ?? 'Unmapped' }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-xs font-black text-admin-primary">Semester {{ $subject->semester }}</p>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="inline" onsubmit="return confirm('Purge this subject node?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-3 text-red-500 hover:bg-red-500/10 transition-all rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.3em] italic">No subject nodes initialized.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $subjects->links() }}
        </div>

        <!-- Create Modal -->
        <div x-show="openCreate" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-admin-secondary/40 backdrop-blur-md px-4"
             x-cloak>
            <div @click.away="openCreate = false" class="bg-white w-full max-w-sm rounded-[3rem] shadow-2xl p-10 border border-slate-100 flex flex-col italic">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-admin-secondary">New Subject</h3>
                    <button type="button" @click="openCreate = false" class="text-slate-300 hover:text-slate-600">✕</button>
                </div>

                <form action="{{ route('admin.subjects.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="text" name="name" required placeholder="Subject Name" class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 font-bold">
                    
                    <select name="course_id" required class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 font-bold text-slate-700">
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>

                    <select name="semester" required class="w-full h-14 bg-slate-50 border-none rounded-2xl px-6 font-bold text-slate-700">
                        <option value="">Select Semester</option>
                        @for($i=1; $i<=10; $i++)
                            <option value="{{ $i }}">Semester {{ $i }}</option>
                        @endfor
                    </select>

                    <button type="submit" class="w-full py-5 bg-admin-primary text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-admin-primary/20">Establish Node</button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
