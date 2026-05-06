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
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                    ✨ Chat Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Chat Container --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden flex flex-col"
                 style="height: 70vh;">

                {{-- Messages Area --}}
                <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4">

                    @if($histories->isEmpty())
                        <div class="flex flex-col items-center justify-center h-full text-center">
                            <div class="text-6xl mb-4">🎓</div>
                            <h3 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-2">Halo, {{ Auth::user()->name }}!</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-md">
                                Saya <strong>Sinau AI</strong>, asisten belajar pintarmu. Tanyakan apa saja — matematika, sains, bahasa, sejarah, atau topik apapun!
                            </p>
                            <div class="mt-6 grid grid-cols-2 gap-3 w-full max-w-lg">
                                @foreach([
                                    '📐 Jelaskan teorema Pythagoras dengan contoh',
                                    '🧪 Apa itu fotosintesis? Jelaskan step by step',
                                    '📝 Bantu saya buat esai tentang lingkungan',
                                    '🔢 Cara menyelesaikan persamaan kuadrat?'
                                ] as $suggestion)
                                <button onclick="sendSuggestion(this)"
                                        data-text="{{ $suggestion }}"
                                        class="suggestion-btn text-left p-3 bg-indigo-50 dark:bg-gray-700 hover:bg-indigo-100 dark:hover:bg-gray-600 rounded-xl text-sm text-indigo-700 dark:text-indigo-300 transition border border-indigo-200 dark:border-gray-600">
                                    {{ $suggestion }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        @foreach($histories as $chat)
                            @if($chat->role === 'user')
                                <div class="flex justify-end">
                                    <div class="max-w-[75%] bg-indigo-600 text-white rounded-2xl rounded-tr-sm px-4 py-3 shadow">
                                        <p class="text-sm leading-relaxed">{{ $chat->message }}</p>
                                        <span class="text-xs text-indigo-200 mt-1 block text-right">{{ $chat->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="flex justify-start gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm">🤖</div>
                                    <div class="max-w-[75%] bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-2xl rounded-tl-sm px-4 py-3 shadow">
                                        <div class="text-sm leading-relaxed prose prose-sm dark:prose-invert max-w-none ai-response">{{ $chat->message }}</div>
                                        <span class="text-xs text-gray-400 mt-1 block">{{ $chat->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    {{-- Typing indicator --}}
                    <div id="typing-indicator" class="hidden flex justify-start gap-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm">🤖</div>
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-2xl rounded-tl-sm px-4 py-3 shadow">
                            <div class="flex gap-1 items-center h-5">
                                <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                                <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                                <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Input Area --}}
                <div class="border-t border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-900">
                    <form id="chat-form" class="flex gap-3 items-end">
                        @csrf
                        <div class="flex-1 relative">
                            <textarea
                                id="message-input"
                                name="message"
                                rows="1"
                                placeholder="Tanyakan sesuatu kepada Sinau AI..."
                                class="w-full resize-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 px-4 py-3 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                style="max-height: 120px;"
                            ></textarea>
                        </div>
                        <button type="submit" id="send-btn"
                                class="flex-shrink-0 w-12 h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl flex items-center justify-center transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
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
                        <div class="max-w-[75%] bg-indigo-600 text-white rounded-2xl rounded-tr-sm px-4 py-3 shadow">
                            <p class="text-sm leading-relaxed">${escapeHtml(text)}</p>
                            <span class="text-xs text-indigo-200 mt-1 block text-right">${now}</span>
                        </div>
                    </div>
                `);
            } else {
                chatMessages.insertAdjacentHTML('beforeend', `
                    <div class="flex justify-start gap-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm">🤖</div>
                        <div class="max-w-[75%] bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-2xl rounded-tl-sm px-4 py-3 shadow">
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
