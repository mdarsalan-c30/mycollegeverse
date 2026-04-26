@php $layout = Auth::user()->role === 'recruiter' ? 'recruiter' : 'app'; @endphp
<x-dynamic-component :component="$layout.'-layout'">
    <div class="max-w-7xl mx-auto h-[calc(100vh-10rem)] flex lg:gap-6 relative" 
         x-data="{ 
            mobileChatOpen: {{ $receiver ? 'true' : 'false' }},
            contactRole: 'all'
         }">
        <!-- Chat Sidebar -->
        <div class="w-full lg:w-80 glass lg:rounded-[2.5rem] flex flex-col overflow-hidden border-white/50 shadow-sm transition-all duration-300"
             :class="mobileChatOpen ? 'hidden lg:flex' : 'flex'">
            <div class="p-6 border-b border-slate-100 bg-white/30">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-secondary">Verse Contacts</h3>
                    <span class="bg-primary/10 text-primary text-[10px] px-2 py-1 rounded-lg font-black uppercase tracking-widest">Active Nodes</span>
                </div>

                <!-- Role Filter Tabs -->
                <div class="flex bg-slate-100 p-1 rounded-xl mb-4">
                    <button @click="contactRole = 'all'" :class="contactRole === 'all' ? 'bg-white shadow-sm text-primary' : 'text-slate-400'" class="flex-1 py-1.5 text-[9px] font-black uppercase tracking-widest transition-all rounded-lg">All</button>
                    <button @click="contactRole = 'student'" :class="contactRole === 'student' ? 'bg-white shadow-sm text-primary' : 'text-slate-400'" class="flex-1 py-1.5 text-[9px] font-black uppercase tracking-widest transition-all rounded-lg">Students</button>
                    <button @click="contactRole = 'recruiter'" :class="contactRole === 'recruiter' ? 'bg-white shadow-sm text-primary' : 'text-slate-400'" class="flex-1 py-1.5 text-[9px] font-black uppercase tracking-widest transition-all rounded-lg">Recruiters</button>
                </div>

                <div class="relative">
                    <input type="text" placeholder="Search contacts..." class="w-full h-10 bg-white/50 border border-slate-100 rounded-xl px-10 focus:ring-primary/20 text-xs font-medium">
                    <svg class="absolute left-3.5 top-3 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2 no-scrollbar">
                @foreach($users as $user)
                    <a href="{{ route('chat.index', $user->username) }}" 
                       @click="mobileChatOpen = true"
                       x-show="contactRole === 'all' || contactRole === '{{ $user->role }}'"
                       x-transition:enter="transition ease-out duration-300"
                       x-transition:enter-start="opacity-0 translate-y-2"
                       x-transition:enter-end="opacity-100 translate-y-0"
                       class="flex items-center gap-3 p-3 rounded-2xl hover:bg-white transition-all cursor-pointer group {{ ($receiver && $receiver->id == $user->id) ? 'bg-white shadow-sm ring-1 ring-primary/5' : '' }}">
                        <div class="relative flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="w-10 h-10 rounded-xl shadow-sm"/>
                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-extrabold text-slate-800 truncate">{{ $user->name }}</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-tighter {{ $user->role === 'recruiter' ? 'bg-slate-900 text-white' : 'bg-primary/10 text-primary' }}">
                                    {{ $user->role === 'recruiter' ? 'Recruiter' : 'Student' }}
                                </span>
                                <p class="text-[9px] text-slate-400 truncate font-bold uppercase tracking-tighter">Verse Node</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Chat Window -->
        <div class="flex-1 glass lg:rounded-[2.5rem] flex flex-col overflow-hidden border-white shadow-glass transition-all duration-300"
             :class="mobileChatOpen ? 'flex' : 'hidden lg:flex'">
            @if($receiver)
            <!-- Header -->
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-white/20">
                <div class="flex items-center gap-4">
                    <!-- Mobile Back Button -->
                    <button @click="mobileChatOpen = false" class="lg:hidden w-10 h-10 flex items-center justify-center bg-slate-100 rounded-xl active:scale-90 transition-transform">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <a href="{{ route('profile.show', $receiver->username) }}" class="flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($receiver->name) }}&background=random" class="w-10 h-10 rounded-xl shadow-sm hover:scale-110 transition-transform"/>
                    </a>
                    <div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('profile.show', $receiver->username) }}" class="font-extrabold text-slate-800 text-sm leading-none hover:text-primary transition-colors">{{ $receiver->name }}</a>
                            <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest {{ $receiver->role === 'recruiter' ? 'bg-slate-900 text-white' : 'bg-primary/10 text-primary' }}">
                                {{ $receiver->role === 'recruiter' ? 'Recruiter' : 'Student' }}
                            </span>
                        </div>
                        <p class="text-[9px] font-black text-green-500 uppercase tracking-widest mt-1">Authenticated Verse Connection</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="w-10 h-10 flex items-center justify-center bg-primary/5 text-primary rounded-xl hover:bg-primary/10 transition-colors">🏛️</button>
                    <button class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:text-slate-600 transition-colors">⚙️</button>
                </div>
            </div>

            <!-- Messages -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-6 md:p-8 space-y-6 no-scrollbar">
                @foreach($messages as $msg)
                    <div class="flex gap-3 items-end {{ $msg->sender_id == Auth::id() ? 'justify-end' : '' }}">
                        @if($msg->sender_id != Auth::id())
                        <a href="{{ route('profile.show', $receiver->username) }}" class="flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($receiver->name) }}&background=random" class="w-6 h-6 rounded-lg hover:ring-2 ring-primary/20 transition-all"/>
                        </a>
                        @endif
                        <div class="max-w-[75%] md:max-w-md {{ $msg->sender_id == Auth::id() ? 'bg-primary text-white rounded-br-none' : 'bg-white text-slate-700 rounded-bl-none' }} px-4 py-3 rounded-2xl shadow-sm text-xs font-medium leading-relaxed">
                            {!! \App\Helpers\ChatFormatter::format($msg->message) !!}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input -->
            <div class="p-6 bg-white/30 border-t border-slate-100">
                <form id="chat-form" class="flex flex-col gap-3" x-data="{ hasImage: false, preview: null }" @chat-sent.window="hasImage = false; preview = null">
                    <input type="hidden" id="receiver_id" value="{{ $receiver->id }}">
                    
                    <div x-show="hasImage" class="relative w-20 h-20 mb-2" x-cloak>
                        <img :src="preview" class="w-full h-full object-cover rounded-xl border-2 border-primary shadow-lg">
                        <button type="button" @click="hasImage = false; preview = null; document.getElementById('chat-image').value = ''" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition-colors">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                        </button>
                    </div>

                    <div class="flex gap-3">
                        <label class="bg-slate-100 text-slate-500 w-12 h-12 rounded-xl flex items-center justify-center cursor-pointer hover:bg-slate-200 transition-all active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <input type="file" id="chat-image" class="hidden" accept="image/*" @change="hasImage = true; preview = URL.createObjectURL($event.target.files[0])">
                        </label>
                        <input type="text" id="message-input" autocomplete="off" placeholder="Broadcast to {{ explode(' ', $receiver->name)[0] }}..." class="flex-1 h-12 bg-white/80 border border-slate-100 rounded-xl px-5 focus:ring-primary/20 focus:border-primary text-xs font-medium shadow-sm">
                        <button type="submit" class="bg-primary text-white px-6 h-12 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary/20 active:scale-95 hover:scale-105 transition-all">
                            Send
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="flex-1 flex items-center justify-center flex-col text-slate-400 italic p-12 text-center">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center text-4xl mb-6 animate-bounce-subtle">🕊️</div>
                <h4 class="text-slate-800 font-black uppercase tracking-widest text-sm mb-2">No Active Connection</h4>
                <p class="text-xs font-bold leading-relaxed max-w-xs">Select a contact from your Verse directory to initiate a high-fidelity academic broadcast.</p>
                <button @click="mobileChatOpen = false" class="lg:hidden mt-8 px-8 py-4 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-primary/20">Open Directory</button>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Format chat messages: **bold** → <strong>, URLs → clickable links, \n → <br>
        function formatChatMsg(text) {
            if (!text) return '';
            // Escape HTML first
            let t = text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            // **bold** → <strong>
            t = t.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
            // URLs → clickable links
            t = t.replace(/(https?:\/\/[^\s<]+)/g, '<a href="$1" target="_blank" class="underline opacity-80 hover:opacity-100 break-all">$1</a>');
            // Newlines → <br>
            t = t.replace(/\n/g, '<br>');
            return t;
        }

        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const chatMessages = document.getElementById('chat-messages');
        const receiverId = document.getElementById('receiver_id')?.value;

        if (chatForm) {
            chatForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const text = messageInput.value;
                const rID = document.getElementById('receiver_id')?.value;
                const imageFile = document.getElementById('chat-image').files[0];
                
                if ((!text && !imageFile) || !rID) return;

                const formData = new FormData();
                formData.append('receiver_id', rID);
                formData.append('message', text);
                if (imageFile) formData.append('image', imageFile);
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    messageInput.value = '';
                    document.getElementById('chat-image').value = '';
                    chatForm.dispatchEvent(new CustomEvent('chat-sent'));
                    window.fetchMessages && window.fetchMessages();
                }
            });

            chatForm.addEventListener('chat-sent', () => {
                // This is a bridge to Alpine.js
            });

            // Exposed to window so onclick in dynamic HTML can access it
            window.deleteMessage = window.deleteMessage || async function(id) {
                if (!confirm('Delete this image?')) return;
                const res = await fetch(`/chat/message/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.status === 'deleted' || res.ok) {
                    window.fetchMessages && window.fetchMessages();
                } else {
                    alert('Could not delete. ' + (data.error || ''));
                }
            };

            window.fetchMessages = async function fetchMessages() {
                const rID = document.getElementById('receiver_id')?.value;
                if (!rID) return;
                const res = await fetch(`/chat/fetch/{{ $receiver->username ?? '' }}`);
                const data = await res.json();
                
                chatMessages.innerHTML = data.map(msg => {
                    const isMine = msg.sender_id == {{ Auth::id() }};
                    const hasImage = msg.type === 'image' && msg.image_path;

                    const avatarHtml = !isMine
                        ? `<a href="/profile/{{ $receiver->username ?? '' }}"><img src="{{ $receiver->profile_photo_url }}" class="w-6 h-6 rounded-lg shadow-sm border border-white flex-shrink-0"/></a>`
                        : '';

                    const imageHtml = hasImage
                        ? `<div class="relative" onmouseenter="this.querySelector('.del-btn') && (this.querySelector('.del-btn').style.opacity='1')" onmouseleave="this.querySelector('.del-btn') && (this.querySelector('.del-btn').style.opacity='0')">
                               <img src="https://ik.imagekit.io/studycubsfranchise${msg.image_path}?tr=w-600,q-80" 
                                    class="rounded-xl mb-1 w-full shadow-sm cursor-pointer" style="max-width:100%">
                               ${isMine ? '<button class="del-btn" onclick="deleteMessage(' + msg.id + ')" title="Delete image" style="position:absolute;top:8px;right:8px;background:rgba(239,68,68,0.85);color:white;border:none;border-radius:8px;padding:6px;cursor:pointer;opacity:0;transition:opacity 0.2s;line-height:1;"><svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg></button>' : ''}
                           </div>`
                        : '';

                    const textHtml = msg.message ? `<div>${formatChatMsg(msg.message)}</div>` : '';

                    return `<div class="flex gap-3 items-end ${isMine ? 'justify-end' : ''}">
                        ${avatarHtml}
                        <div class="max-w-[75%] md:max-w-md ${isMine ? 'bg-primary text-white rounded-br-none' : 'bg-white text-slate-700 rounded-bl-none'} px-4 py-3 rounded-2xl shadow-sm text-xs font-medium leading-relaxed">
                            ${imageHtml}${textHtml}
                        </div>
                    </div>`;
                }).join('');

                chatMessages.scrollTop = chatMessages.scrollHeight;
            };

            setInterval(window.fetchMessages, 4000);
            window.fetchMessages();
        }
    </script>
</x-app-layout>
