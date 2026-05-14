<div x-data="{ open: false, isDragging: false }" 
     id="chatbot-container"
     class="fixed bottom-6 right-6 z-50 transition-none">
    
    <!-- Chatbot Button -->
    <button id="chatbot-button"
            @click="if(!isDragging) open = !open" 
            class="flex h-16 w-16 items-center justify-center rounded-full bg-accent text-white shadow-2xl shadow-accent/40 hover:scale-110 active:scale-95 transition-transform duration-300 group cursor-grab active:cursor-grabbing">
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Chat Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="absolute bottom-24 right-0 w-[350px] sm:w-[400px] overflow-hidden rounded-3xl bg-white/90 backdrop-blur-2xl border border-white/20 shadow-[0_20px_50px_rgba(0,0,0,0.2)] origin-bottom-right">
        
        <!-- Header -->
        <div class="bg-accent p-6 text-white shadow-md">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-full bg-white p-0.5 shadow-inner flex items-center justify-center overflow-hidden border-2 border-white/30">
                    <img src="{{ asset('images/bot-avatar.png') }}" class="h-full w-full object-contain" alt="Si-Nau Mascot">
                </div>
                <div>
                    <h3 class="font-black text-xl leading-tight text-white tracking-tight">Si-Nau:</h3>
                    <p class="text-xs text-white/80 flex items-center gap-1.5 font-medium">
                        <span class="h-2 w-2 rounded-full bg-green-400 animate-pulse shadow-[0_0_8px_rgba(74,222,128,0.8)]"></span>
                        Cerdas & Selalu Membantu
                    </p>
                </div>
            </div>
        </div>

        <!-- Chat Messages Area -->
        <div class="h-[400px] overflow-y-auto p-6 space-y-4 bg-slate-50/50" id="chat-messages">
            <!-- Bot Message -->
            <div class="flex gap-3">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-white flex items-center justify-center border border-slate-100 overflow-hidden shadow-sm">
                    <img src="{{ asset('images/bot-avatar.png') }}" class="h-full w-full object-contain" alt="S">
                </div>
                <div class="rounded-2xl rounded-tl-none bg-white p-4 text-sm text-slate-700 shadow-sm border border-slate-100 max-w-[80%] leading-relaxed">
                    Halo! Nama saya **Si-Nau:**. Ada yang bisa saya bantu terkait tugas atau materi belajarmu hari ini?
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-slate-100">
            <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input type="text" 
                           placeholder="Ketik pesan..." 
                           class="w-full rounded-2xl border-none bg-slate-100 py-3 px-4 text-sm focus:ring-2 focus:ring-accent/50 transition-all"
                           id="chat-input">
                </div>
                <button type="submit" 
                        class="flex-shrink-0 flex items-center justify-center rounded-xl bg-accent p-3 text-white hover:bg-red-600 transition-colors shadow-lg shadow-accent/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
            <p class="mt-3 text-center text-[10px] text-slate-400 font-medium tracking-tight uppercase">Powered by Sinau AI Intelligence</p>
        </div>
    </div>
</div>

<script>
// Draggable logic with persistence
(function() {
    const container = document.getElementById('chatbot-container');
    const button = document.getElementById('chatbot-button');
    let isMouseDown = false;
    let offset = { x: 0, y: 0 };
    let startPos = { x: 0, y: 0 };

    // Load saved position
    const savedPos = localStorage.getItem('chatbot-pos');
    if (savedPos) {
        const { left, top } = JSON.parse(savedPos);
        container.style.left = left;
        container.style.top = top;
        container.style.bottom = 'auto';
        container.style.right = 'auto';
    }

    button.addEventListener('mousedown', (e) => {
        isMouseDown = true;
        offset = {
            x: container.offsetLeft - e.clientX,
            y: container.offsetTop - e.clientY
        };
        startPos = { x: e.clientX, y: e.clientY };
        
        container.style.transition = 'none';
        
        const alpineData = Alpine.$data(container);
        if (alpineData) alpineData.isDragging = false;
    });

    document.addEventListener('mousemove', (e) => {
        if (!isMouseDown) return;
        
        const dx = e.clientX - startPos.x;
        const dy = e.clientY - startPos.y;
        
        if (Math.abs(dx) > 5 || Math.abs(dy) > 5) {
            const alpineData = Alpine.$data(container);
            if (alpineData) alpineData.isDragging = true;
        }

        if (Alpine.$data(container).isDragging) {
            let left = e.clientX + offset.x;
            let top = e.clientY + offset.y;
            
            const padding = 20;
            left = Math.max(padding, Math.min(window.innerWidth - container.offsetWidth - padding, left));
            top = Math.max(padding, Math.min(window.innerHeight - container.offsetHeight - padding, top));

            container.style.left = left + 'px';
            container.style.top = top + 'px';
            container.style.bottom = 'auto';
            container.style.right = 'auto';
        }
    });

    document.addEventListener('mouseup', () => {
        if (isMouseDown && Alpine.$data(container).isDragging) {
            // Save position
            localStorage.setItem('chatbot-pos', JSON.stringify({
                left: container.style.left,
                top: container.style.top
            }));
        }

        isMouseDown = false;
        setTimeout(() => {
            if (Alpine.$data(container)) {
                Alpine.$data(container).isDragging = false;
            }
        }, 100);
    });
})();

function sendMessage() {
    const input = document.getElementById('chat-input');
    const messages = document.getElementById('chat-messages');
    
    if (input.value.trim() === '') return;

    // User Message
    const userMsg = document.createElement('div');
    userMsg.className = 'flex justify-end';
    userMsg.innerHTML = `
        <div class="rounded-2xl rounded-tr-none bg-accent p-3 text-sm text-white shadow-md shadow-accent/20 max-w-[80%]">
            ${input.value}
        </div>
    `;
    messages.appendChild(userMsg);
    
    const text = input.value;
    input.value = '';
    messages.scrollTop = messages.scrollHeight;

    // Fake Bot Response after delay
    setTimeout(() => {
        const botMsg = document.createElement('div');
        botMsg.className = 'flex gap-3';
        botMsg.innerHTML = `
            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-white flex items-center justify-center border border-slate-100 overflow-hidden shadow-sm">
                <img src="/images/bot-avatar.png" class="h-full w-full object-contain">
            </div>
            <div class="rounded-2xl rounded-tl-none bg-white p-4 text-sm text-slate-700 shadow-sm border border-slate-100 max-w-[80%] leading-relaxed">
                Halo, saya **Si-Nau:**. Sedang memproses pertanyaanmu: "${text}"... <br><br> (Hubungkan ke API AI untuk jawaban nyata).
            </div>
        `;
        messages.appendChild(botMsg);
        messages.scrollTop = messages.scrollHeight;
    }, 1000);
}
</script>
