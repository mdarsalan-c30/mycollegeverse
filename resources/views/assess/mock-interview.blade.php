<x-app-layout>
    @section('title', 'Verse Interview AI - High-Fidelity Mock Interviews | MyCollegeVerse')
    @section('meta_description', 'Practice for your dream job with our ultra-fast AI interviewer powered by Groq and Sarvam AI.')

    <div class="max-w-7xl mx-auto px-6 py-12" x-data="interviewManager()">
        <!-- Header Node -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-16">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-primary/20">
                    🎙️ Verse Assessment Engine
                </div>
                <h1 class="text-5xl font-black text-secondary tracking-tighter">Mock Interview <span class="text-primary">AI</span></h1>
                <p class="text-slate-500 font-medium italic">High-fidelity simulations for the digital elite.</p>
            </div>
            
            <div class="flex gap-4">
                <template x-if="!isInterviewing">
                    <button @click="startNewInterview()" class="bg-secondary text-white px-10 py-5 rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-2xl shadow-secondary/20 hover:scale-105 active:scale-95 transition-all">
                        Launch New Session
                    </button>
                </template>
                <template x-if="isInterviewing">
                    <button @click="endInterview()" class="bg-rose-500 text-white px-10 py-5 rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-2xl shadow-rose-500/20 hover:scale-105 active:scale-95 transition-all">
                        Terminate Session
                    </button>
                </template>
            </div>
        </div>

        <div class="grid lg:grid-cols-12 gap-12">
            <!-- Left: Interview Astral Room -->
            <div class="lg:col-span-8">
                <div class="glass min-h-[600px] rounded-[3.5rem] border-white/60 shadow-glass overflow-hidden flex flex-col relative">
                    <!-- AI Avatar Sphere -->
                    <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                        <div class="relative mb-12">
                            <!-- Outer Pulse -->
                            <div class="absolute inset-0 bg-primary/20 rounded-full animate-ping scale-150 opacity-20" :class="isThinking || isSpeaking || isListening ? 'opacity-40' : 'hidden'"></div>
                            
                            <!-- Main Core -->
                            <div class="w-48 h-48 bg-gradient-to-br from-primary via-indigo-600 to-purple-600 rounded-full flex items-center justify-center shadow-2xl relative z-10 overflow-hidden group">
                                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
                                <svg x-show="!isSpeaking" class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                </svg>
                                <!-- Waveform during speaking -->
                                <div x-show="isSpeaking" class="flex items-center gap-1.5">
                                    <div class="w-1.5 h-12 bg-white rounded-full animate-wave-1"></div>
                                    <div class="w-1.5 h-16 bg-white rounded-full animate-wave-2"></div>
                                    <div class="w-1.5 h-12 bg-white rounded-full animate-wave-3"></div>
                                    <div class="w-1.5 h-8 bg-white rounded-full animate-wave-1"></div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6 max-w-md">
                            <h3 class="text-2xl font-black text-secondary uppercase tracking-tight" 
                                x-text="isListening ? 'Interviewer is Listening...' : 
                                        (isThinking ? 'Analyzing Response...' : 
                                        (isSpeaking ? 'AI is Responding...' : 
                                        (isInterviewing ? 'Session Active' : 'Awaiting Deployment')))"></h3>
                            <p class="text-slate-500 font-bold leading-relaxed text-sm" x-text="statusMessage"></p>
                        </div>
                    </div>

                    <!-- Audio Controls / Status Bar -->
                    <div class="p-8 bg-white/40 border-t border-white/60 flex flex-col gap-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-3 h-3 rounded-full" :class="isListening ? 'bg-green-500 animate-pulse' : 'bg-slate-300'"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400" x-text="isListening ? 'Mic Active' : 'Mic Off'"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Powered by Verse Intelligence</span>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Text Input Fallback -->
                            <div class="flex-1 relative group" x-show="isInterviewing">
                                <input type="text" 
                                       x-model="manualInput" 
                                       @keyup.enter="sendManualInput()"
                                       placeholder="Type your response here if mic is unavailable..." 
                                       class="w-full bg-white/50 border-white/60 rounded-2xl px-6 py-4 text-xs font-bold text-slate-700 placeholder:text-slate-300 focus:ring-2 focus:ring-primary/20 transition-all outline-none">
                                <button @click="sendManualInput()" class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-primary hover:scale-110 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                                </button>
                            </div>

                            <div class="flex gap-4">
                                <button x-show="isInterviewing && !isThinking && !isSpeaking" 
                                        @click="toggleMic()"
                                        :class="isListening ? 'bg-rose-500 shadow-rose-500/20' : 'bg-primary shadow-primary/20'"
                                        class="flex-shrink-0 group flex items-center gap-3 px-8 py-4 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all">
                                    <span x-text="isListening ? 'Stop & Send' : 'Start Speaking'"></span>
                                    <svg class="w-4 h-4" :class="isListening ? 'animate-pulse' : ''" fill="currentColor" viewBox="0 0 20 20"><path d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Session Records & Logs -->
            <div class="lg:col-span-4 space-y-8">
                <div class="glass p-8 rounded-[3rem] border-white/60 shadow-glass max-h-[400px] overflow-y-auto no-scrollbar">
                    <h4 class="text-sm font-black text-secondary uppercase tracking-widest mb-6">Live Transcript</h4>
                    <div class="space-y-4">
                        <template x-for="(entry, index) in transcript" :key="index">
                            <div class="space-y-2">
                                <p class="text-[9px] font-black text-primary uppercase" x-text="entry.role"></p>
                                <p class="text-xs font-medium text-slate-600 leading-relaxed" x-text="entry.text"></p>
                            </div>
                        </template>
                        <div x-show="transcript.length === 0" class="text-center py-10">
                            <p class="text-slate-300 text-[10px] font-bold uppercase tracking-widest italic">Transcript will manifest here...</p>
                        </div>
                    </div>
                </div>

                <div class="glass p-8 rounded-[3rem] border-white/60 shadow-glass">
                    <h4 class="text-sm font-black text-secondary uppercase tracking-widest mb-6">History</h4>
                    <div class="space-y-4">
                        @forelse($sessions as $session)
                        <div class="p-4 bg-white/30 rounded-2xl border border-white/40 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-black text-slate-800">{{ $session->role }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tight">{{ $session->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="bg-primary/5 text-primary text-[9px] px-2 py-1 rounded-lg font-black">{{ $session->score ?? 'N/A' }}</span>
                        </div>
                        @empty
                        <p class="text-center text-slate-400 text-xs italic py-4">No previous missions found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Selection Modal -->
        <div x-show="showRoleModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-xl" x-cloak>
            <div class="glass w-full max-w-lg rounded-[3rem] p-12 text-center space-y-8" @click.away="showRoleModal = false">
                <h3 class="text-3xl font-black text-secondary tracking-tighter">Choose Your <span class="text-primary">Destiny</span></h3>
                <div class="grid grid-cols-2 gap-4">
                    <template x-for="role in roles">
                        <button @click="selectRole(role)" class="p-6 bg-white/40 border border-white rounded-[2rem] hover:bg-primary hover:text-white transition-all text-xs font-black uppercase tracking-widest text-slate-600 shadow-sm">
                            <span x-text="role"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <style>
        .glass { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); }
        .shadow-glass { box-shadow: 0 40px 100px rgba(0, 0, 0, 0.03); }
        
        @keyframes wave {
            0%, 100% { height: 20px; }
            50% { height: 64px; }
        }
        .animate-wave-1 { animation: wave 1s infinite ease-in-out; }
        .animate-wave-2 { animation: wave 0.8s infinite ease-in-out 0.2s; }
        .animate-wave-3 { animation: wave 1.2s infinite ease-in-out 0.4s; }
        
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-bounce-subtle { animation: bounce-subtle 4s infinite ease-in-out; }
    </style>

    @push('scripts')
    <script>
        function interviewManager() {
            return {
                isInterviewing: false,
                isListening: false,
                isThinking: false,
                isSpeaking: false,
                showRoleModal: false,
                currentSessionId: null,
                statusMessage: 'Ready to benchmark your intelligence. Select a role to begin.',
                manualInput: '',
                roles: ['Frontend Developer', 'Backend Architect', 'Marketing Head', 'UI/UX Designer', 'Product Manager', 'Data Scientist'],
                transcript: [],
                mediaRecorder: null,
                audioChunks: [],

                async startNewInterview() {
                    this.showRoleModal = true;
                },

                async selectRole(role) {
                    this.showRoleModal = false;
                    this.statusMessage = `Initializing neural connection for ${role}...`;
                    
                    const res = await fetch('{{ route("interview.start") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ role })
                    });
                    
                    const data = await res.json();
                    if (data.status === 'success') {
                        this.currentSessionId = data.session_id;
                        this.isInterviewing = true;
                        
                        // Professional Initiation
                        const introText = `Hello! I am your lead interviewer for the ${role} position. Today, I'll be assessing your technical skills and cultural fit. To start, could you please introduce yourself and tell me about your most significant project?`;
                        this.triggerAIGreeting(introText);
                    }
                },

                async sendManualInput() {
                    if (!this.manualInput.trim() || this.isThinking || this.isSpeaking) return;
                    const text = this.manualInput.trim();
                    this.manualInput = '';
                    this.transcript.push({ role: 'You', text });
                    this.processThinking(text);
                },

                async triggerAIGreeting(text) {
                    this.transcript.push({ role: 'Assistant', text });
                    this.statusMessage = "AI is speaking...";
                    this.speak(text);
                },

                toggleMic() {
                    if (this.isListening) {
                        this.stopListening();
                    } else {
                        this.startListening();
                    }
                },

                async startListening() {
                    if (!navigator.mediaDevices) return;
                    this.isListening = true;
                    this.statusMessage = "Recording your response...";
                    
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    this.mediaRecorder = new MediaRecorder(stream);
                    this.audioChunks = [];
                    
                    this.mediaRecorder.ondataavailable = (e) => this.audioChunks.push(e.data);
                    this.mediaRecorder.start();
                },

                async stopListening() {
                    if (!this.isListening || !this.mediaRecorder) return;
                    this.isListening = false;
                    
                    if (this.mediaRecorder.state !== 'inactive') {
                        this.mediaRecorder.stop();
                    }
                    this.mediaRecorder.onstop = async () => {
                        this.statusMessage = "Transcribing speech...";
                        const audioBlob = new Blob(this.audioChunks, { type: 'audio/wav' });
                        const formData = new FormData();
                        formData.append('audio', audioBlob);
                        formData.append('_token', '{{ csrf_token() }}');

                        const res = await fetch('{{ route("interview.transcribe") }}', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const data = await res.json();
                        if (data.status === 'error') {
                            alert(data.message);
                            this.statusMessage = "STT Error. Please try typing.";
                            return;
                        }
                        
                        const userText = data.transcript || data.text;
                        
                        if (userText) {
                            this.transcript.push({ role: 'You', text: userText });
                            this.processThinking(userText);
                        } else {
                            this.statusMessage = "Couldn't catch that. Please try holding the button again.";
                        }
                    };
                },

                async processThinking(userText) {
                    this.isThinking = true;
                    this.statusMessage = "Analyzing response...";
                    
                    try {
                        const res = await fetch('{{ route("interview.think") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ session_id: this.currentSessionId, message: userText })
                        });
                        
                        const data = await res.json();
                        this.isThinking = false;
                        
                        if (data.status === 'success') {
                            this.transcript.push({ role: 'Assistant', text: data.message });
                            this.speak(data.message);
                        } else {
                            alert(data.message || "Unknown Brain Error");
                            this.statusMessage = "Brain Module Error.";
                        }
                    } catch (e) {
                        this.isThinking = false;
                        alert("Connection Error: " + e.message);
                    }
                },

                async speak(text) {
                    this.isSpeaking = true;
                    this.statusMessage = "AI is speaking...";
                    
                    try {
                        const res = await fetch('{{ route("interview.speak") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ text })
                        });
                        
                        const data = await res.json();
                        if (data.status === 'error') {
                            console.error(data.message);
                            this.statusMessage = "Audio failed (Voice Module Error).";
                            this.isSpeaking = false;
                            return;
                        }

                        if (data.audio_base64) {
                            const audio = new Audio("data:audio/wav;base64," + data.audio_base64);
                            audio.onended = () => {
                                this.isSpeaking = false;
                                this.statusMessage = "Awaiting your response. Hold the mic button to speak.";
                            };
                            audio.play();
                        } else {
                            this.isSpeaking = false;
                            this.statusMessage = "AI response ready (Audio missing). Please respond.";
                        }
                    } catch (e) {
                        this.isSpeaking = false;
                        console.error("Voice Connection Error:", e);
                    }
                },

                endInterview() {
                    if (confirm('Are you sure you want to terminate this high-fidelity session?')) {
                        location.reload();
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
