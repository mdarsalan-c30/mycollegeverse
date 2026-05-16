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
                    <div class="flex gap-4">
                        <button x-show="!isWrappingUp" @click="startWrapUp()" class="bg-amber-500 text-white px-10 py-5 rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-2xl shadow-amber-500/20 hover:scale-105 active:scale-95 transition-all">
                            Wrap Up
                        </button>
                        <button @click="endInterview()" class="bg-rose-500 text-white px-10 py-5 rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-2xl shadow-rose-500/20 hover:scale-105 active:scale-95 transition-all">
                            Terminate Session
                        </button>
                    </div>
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
                        <div @click="viewPastReport({{ $session->score ?? 0 }}, `{{ addslashes($session->feedback ?? 'No report generated for this session.') }}`)" 
                             class="p-4 bg-white/30 rounded-2xl border border-white/40 flex items-center justify-between cursor-pointer hover:bg-primary/5 hover:scale-[1.02] active:scale-95 transition-all group">
                            <div>
                                <p class="text-xs font-black text-slate-800 group-hover:text-primary transition-colors">{{ $session->role }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tight">{{ $session->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="bg-primary/5 text-primary text-[9px] px-2 py-1 rounded-lg font-black group-hover:bg-primary group-hover:text-white transition-all">{{ $session->score ? round($session->score) : 'N/A' }}</span>
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

        <!-- AI Performance Report Modal -->
        <div x-show="showReportModal" class="fixed inset-0 z-[110] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-2xl" x-cloak x-transition>
            <div class="glass w-full max-w-2xl rounded-[3.5rem] border-white/40 overflow-hidden relative" @click.away="!isGeneratingReport && (showReportModal = false)">
                <div class="p-12 space-y-8">
                    <div class="text-center space-y-2">
                        <div class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-primary/20">
                            Neural Analysis Complete
                        </div>
                        <h3 class="text-4xl font-black text-secondary tracking-tighter">Performance <span class="text-primary">Intel</span></h3>
                    </div>

                    <div class="flex flex-col md:flex-row items-center gap-12 py-6">
                        <!-- Score Orb -->
                        <div class="relative group">
                            <div class="absolute inset-0 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
                            <div class="w-40 h-40 rounded-full border-8 border-primary/10 flex items-center justify-center relative bg-white/40 backdrop-blur-xl">
                                <div class="text-center">
                                    <span class="text-5xl font-black text-primary" x-text="reportScore"></span>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Score</p>
                                </div>
                                <svg class="absolute inset-0 w-full h-full -rotate-90">
                                    <circle class="text-slate-100" stroke-width="8" stroke="currentColor" fill="transparent" r="72" cx="80" cy="80"/>
                                    <circle class="text-primary transition-all duration-1000" stroke-width="8" :stroke-dasharray="2 * Math.PI * 72" :stroke-dashoffset="2 * Math.PI * 72 * (1 - reportScore / 100)" stroke-linecap="round" stroke="currentColor" fill="transparent" r="72" cx="80" cy="80"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Feedback Text -->
                        <div class="flex-1 space-y-4">
                            <div class="bg-white/30 rounded-[2rem] p-8 border border-white/60 max-h-[300px] overflow-y-auto custom-scrollbar">
                                <p class="text-sm font-medium text-slate-600 leading-relaxed italic" x-text="reportFeedback"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button @click="showReportModal = false; location.reload();" class="flex-1 py-5 bg-secondary text-white rounded-3xl font-black text-xs uppercase tracking-widest shadow-xl shadow-secondary/20 hover:scale-105 transition-all">
                            Close & Sync
                        </button>
                    </div>
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
                showReportModal: false,
                isGeneratingReport: false,
                isWrappingUp: false,
                wrapUpCounter: 0,
                reportScore: 0,
                reportFeedback: '',
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
                    const options = { 
                        mimeType: 'audio/webm;codecs=opus',
                        audioBitsPerSecond: 128000 
                    };
                    if (!MediaRecorder.isTypeSupported(options.mimeType)) {
                        console.warn('Opus not supported, falling back to default');
                        delete options.mimeType;
                    }
                    this.mediaRecorder = new MediaRecorder(stream, options);
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
                        const audioBlob = new Blob(this.audioChunks, { type: this.mediaRecorder.mimeType });
                        const lastAiMsg = this.transcript.filter(e => e.role === 'Assistant').pop()?.text || "";
                        const formData = new FormData();
                        formData.append('audio', audioBlob);
                        formData.append('context', lastAiMsg);
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

                startWrapUp() {
                    this.isWrappingUp = true;
                    this.wrapUpCounter = 2; // AI will ask 2 more questions
                    const msg = "I would like to wrap up this interview session.";
                    this.transcript.push({ role: 'You', text: msg });
                    this.processThinking(msg);
                },

                async processThinking(userText) {
                    this.isThinking = true;
                    this.statusMessage = "Analyzing response...";
                    
                    try {
                        const res = await fetch('{{ route("interview.think") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ 
                                session_id: this.currentSessionId, 
                                message: userText,
                                wrap_up: this.isWrappingUp
                            })
                        });
                        
                        const data = await res.json();
                        this.isThinking = false;
                        
                        if (data.status === 'success') {
                            this.transcript.push({ role: 'Assistant', text: data.message });
                            
                            // Check if we should end automatically
                            if (this.isWrappingUp) {
                                if (this.wrapUpCounter > 0) {
                                    this.wrapUpCounter--;
                                    await this.speak(data.message);
                                } else {
                                    await this.speak(data.message);
                                    // Automatic end after last word
                                    setTimeout(() => this.generateReportAutomated(), 1000);
                                }
                            } else {
                                await this.speak(data.message);
                            }
                        } else {
                            alert(data.message || "Unknown Brain Error");
                            this.statusMessage = "Brain Module Error.";
                        }
                    } catch (e) {
                        this.isThinking = false;
                        alert("Connection Error: " + e.message);
                    }
                },

                async generateReportAutomated() {
                    this.isGeneratingReport = true;
                    this.statusMessage = "Compiling Neuro-Assessment Report...";
                    try {
                        const res = await fetch('{{ route("interview.report") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ session_id: this.currentSessionId })
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            this.reportScore = data.score;
                            this.reportFeedback = data.feedback;
                            this.showReportModal = true;
                            this.statusMessage = "Analysis Complete.";
                        } else {
                            this.statusMessage = "Report Gen Failed: " + data.message;
                            alert("Intelligence Report Error: " + data.message);
                        }
                    } catch (e) {
                        console.error("Auto-report failed", e);
                        this.statusMessage = "Neural Analytics Offline.";
                        alert("Network Error during analysis. Please check your connection.");
                    } finally {
                        this.isGeneratingReport = false;
                    }
                },

                async speak(text) {
                    try {
                        const res = await fetch('{{ route("interview.speak") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ text })
                        });
                        
                        const data = await res.json();
                        if (data.status === 'success') {
                            const chunks = data.audios && data.audios.length > 0 ? data.audios : (data.audio_base64 ? [data.audio_base64] : []);
                            
                            if (chunks.length === 0) {
                                this.statusMessage = "AI response ready (Audio missing). Please respond.";
                                return;
                            }

                            // Sequential Playback for ultra-low latency feel
                            for (let i = 0; i < chunks.length; i++) {
                                await new Promise((resolve) => {
                                    const audio = new Audio("data:audio/wav;base64," + chunks[i]);
                                    audio.onplay = () => {
                                        this.isSpeaking = true;
                                        this.statusMessage = "The Interviewer is Speaking...";
                                    };
                                    audio.onended = resolve;
                                    audio.onerror = () => {
                                        console.error("Audio chunk failed");
                                        resolve();
                                    };
                                    audio.play().catch(err => {
                                        console.error("Playback blocked:", err);
                                        resolve();
                                    });
                                });
                            }
                            
                            this.isSpeaking = false;
                            this.statusMessage = "The Interviewer is Listening...";
                        } else {
                            this.statusMessage = "Audio failed (Voice Module Error).";
                            console.error(data.message);
                        }
                    } catch (e) {
                        this.statusMessage = "Audio system offline.";
                        console.error(e);
                    }
                },

                async endInterview() {
                    if (confirm('Are you sure you want to terminate this session and generate your Intelligence Report?')) {
                        this.isGeneratingReport = true;
                        this.statusMessage = "Compiling Intelligence Report. Please wait...";
                        
                        try {
                            const res = await fetch('{{ route("interview.report") }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: JSON.stringify({ session_id: this.currentSessionId })
                            });
                            
                            const data = await res.json();
                            if (data.status === 'success') {
                                this.reportScore = data.score;
                                this.reportFeedback = data.feedback;
                                this.showReportModal = true;
                            } else {
                                alert(data.message);
                                location.reload();
                            }
                        } catch (e) {
                            alert("Report Generation Error: " + e.message);
                            location.reload();
                        } finally {
                            this.isGeneratingReport = false;
                        }
                    }
                },

                viewPastReport(score, feedback) {
                    this.reportScore = score;
                    this.reportFeedback = feedback;
                    this.showReportModal = true;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
