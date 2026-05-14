<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                🤖 Chat dengan Sinau AI
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('chat.history') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-700 text-white text-sm rounded-lg hover:bg-gray-600 transition">
                    📜 Riwayat
                </a>
                <a href="{{ route('chat.new') }}"
                   class="inline-flex items-center px-4 py-2 bg-accent text-white text-sm rounded-lg hover:bg-primary transition shadow-md">
                    ✨ Chat Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Chat Container 3D --}}
            <div style="background-color: white; box-shadow: 0 15px 0 #e2e8f0, 0 30px 40px -10px rgba(0, 0, 0, 0.1);" 
                 class="dark:bg-gray-800 rounded-[2rem] overflow-hidden flex flex-col border border-gray-200 dark:border-gray-700"
                 style="height: 70vh;">

                {{-- Messages Area --}}
                <div id="chat-messages" class="flex-1 overflow-y-auto p-8 space-y-6 bg-gray-50/30">

                    @if($histories->isEmpty())
                        <div class="flex flex-col items-center justify-center h-full text-center p-6">
                            <div class="w-24 h-24 bg-accent/5 rounded-[2rem] flex items-center justify-center text-6xl mb-6 shadow-inner border border-accent/10">🎓</div>
                            <h3 class="text-2xl font-black text-gray-800 dark:text-gray-200 mb-3 tracking-tight">Halo, {{ Auth::user()->name }}!</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-md font-medium">
                                Saya <span class="text-accent font-bold italic">Sinau AI</span>, asisten belajar pintarmu. Mari kita mulai petualangan belajarmu hari ini!
                            </p>
                            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-4 w-full max-w-xl">
                                @foreach([
                                    '📐 Jelaskan teorema Pythagoras',
                                    '🧪 Proses fotosintesis step by step',
                                    '📝 Ide esai tentang lingkungan',
                                    '🔢 Cara hitung persamaan kuadrat'
                                ] as $suggestion)
                                <button onclick="sendSuggestion(this)"
                                        data-text="{{ $suggestion }}"
                                        style="box-shadow: 0 4px 0 #f1f5f9;"
                                        class="suggestion-btn text-left p-4 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-2xl text-sm font-bold text-gray-700 dark:text-gray-200 transition-all border border-gray-200 dark:border-gray-600 active:translate-y-[2px] active:shadow-none">
                                    {{ $suggestion }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        @foreach($histories as $chat)
                            @if($chat->role === 'user')
                                <div class="flex justify-end">
                                    <div style="background-color: #B91C1C; box-shadow: 0 4px 0 #450A0A;" class="max-w-[80%] text-white rounded-2xl rounded-tr-sm px-5 py-4 shadow-xl">
                                        <p class="text-sm font-bold leading-relaxed">{{ $chat->message }}</p>
                                        <span class="text-[10px] text-white/50 mt-2 block text-right font-black">{{ $chat->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="flex justify-start gap-4">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-[#450A0A] to-[#B91C1C] rounded-2xl flex items-center justify-center text-white text-lg shadow-lg border-t border-white/20">🤖</div>
                                    <div class="max-w-[80%] bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-2xl rounded-tl-sm px-5 py-4 shadow-lg border border-gray-100 dark:border-gray-600">
                                        <div class="text-sm font-medium leading-relaxed prose prose-sm dark:prose-invert max-w-none ai-response">{{ $chat->message }}</div>
                                        <span class="text-[10px] text-gray-400 mt-2 block font-bold uppercase tracking-wider">{{ $chat->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    {{-- Typing indicator --}}
                    <div id="typing-indicator" class="hidden flex justify-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-[#450A0A] to-[#B91C1C] rounded-2xl flex items-center justify-center text-white text-lg shadow-lg border-t border-white/20">🤖</div>
                        <div class="bg-white dark:bg-gray-700 rounded-2xl rounded-tl-sm px-5 py-4 shadow-lg border border-gray-100 dark:border-gray-600">
                            <div class="flex gap-1.5 items-center h-6">
                                <span class="w-2.5 h-2.5 bg-accent rounded-full animate-bounce" style="animation-delay:0ms"></span>
                                <span class="w-2.5 h-2.5 bg-accent rounded-full animate-bounce" style="animation-delay:150ms"></span>
                                <span class="w-2.5 h-2.5 bg-accent rounded-full animate-bounce" style="animation-delay:300ms"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Input Area 3D --}}
                <div class="border-t border-gray-100 dark:border-gray-700 p-6 bg-white dark:bg-gray-900">
                    <form id="chat-form" class="flex gap-4 items-end max-w-4xl mx-auto">
                        @csrf
                        <div class="flex-1 relative">
                            <textarea
                                id="message-input"
                                name="message"
                                rows="1"
                                placeholder="Tanyakan apa saja kepada Sinau AI..."
                                class="w-full resize-none rounded-2xl border-2 border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 text-gray-800 dark:text-gray-100 px-5 py-4 pr-12 text-base font-medium focus:outline-none focus:border-accent/30 focus:ring-4 focus:ring-accent/10 transition-all shadow-inner"
                                style="max-height: 150px;"
                            ></textarea>
                        </div>
                        <button type="submit" id="send-btn"
                                style="background-color: #B91C1C; box-shadow: 0 4px 0 #450A0A;"
                                class="flex-shrink-0 w-14 h-14 text-white rounded-2xl flex items-center justify-center transition-all shadow-xl disabled:opacity-50 disabled:cursor-not-allowed hover:translate-y-[1px] hover:shadow-lg active:translate-y-[4px] active:shadow-none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                            </svg>
                        </button>
                    </form>
                    <p class="text-xs text-gray-400 mt-2 text-center">Sinau AI bisa salah. Selalu verifikasi informasi penting.</p>
                </div>
            </div>

        </div>
    </div>

    <style>
        .ai-response { white-space: pre-wrap; word-break: break-word; }
        textarea { overflow-y: hidden; }
    </style>

    <script>
        const chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const sendBtn = document.getElementById('send-btn');
        const typingIndicator = document.getElementById('typing-indicator');

        // Auto scroll
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        scrollToBottom();

        // Auto resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Enter to send (Shift+Enter = new line)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });

        function sendSuggestion(btn) {
            messageInput.value = btn.dataset.text;
            chatForm.dispatchEvent(new Event('submit'));
        }

        function appendMessage(role, text) {
            const now = new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});

            if (role === 'user') {
                chatMessages.insertAdjacentHTML('beforeend', `
                    <div class="flex justify-end">
                        <div class="max-w-[75%] bg-accent text-white rounded-2xl rounded-tr-sm px-4 py-3 shadow-md">
                            <p class="text-sm leading-relaxed">${escapeHtml(text)}</p>
                            <span class="text-xs text-white/60 mt-1 block text-right">${now}</span>
                        </div>
                    </div>
                `);
            } else {
                chatMessages.insertAdjacentHTML('beforeend', `
                    <div class="flex justify-start gap-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm border border-accent/20">🤖</div>
                        <div class="max-w-[75%] bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-2xl rounded-tl-sm px-4 py-3 shadow-md">
                            <div class="text-sm leading-relaxed ai-response">${escapeHtml(text)}</div>
                            <span class="text-xs text-gray-400 mt-1 block">${now}</span>
                        </div>
                    </div>
                `);
            }
            scrollToBottom();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            // Clear empty state suggestion buttons
            const emptyState = chatMessages.querySelector('.flex.flex-col.items-center');
            if (emptyState) emptyState.remove();

            appendMessage('user', message);
            messageInput.value = '';
            messageInput.style.height = 'auto';
            sendBtn.disabled = true;

            // Show typing
            typingIndicator.classList.remove('hidden');
            scrollToBottom();

            try {
                const csrfToken = document.querySelector('input[name="_token"]').value;
                const response = await fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();
                typingIndicator.classList.add('hidden');

                if (data.error) {
                    appendMessage('model', '❌ ' + data.error);
                } else {
                    appendMessage('model', data.message);
                }
            } catch (err) {
                typingIndicator.classList.add('hidden');
                appendMessage('model', '❌ Gagal terhubung ke server. Coba lagi.');
            } finally {
                sendBtn.disabled = false;
                messageInput.focus();
            }
        });
    </script>
</x-app-layout>
