<div x-data="{ open: false, isDragging: false }" 
     id="chatbot-container"
     class="fixed bottom-6 right-6 z-50 transition-none">
    
    <!-- Standard Circular Chat Button -->
    <button id="chatbot-button"
            @click="if(!isDragging) open = !open" 
            style="background-color: #B91C1C; box-shadow: 0 4px 12px rgba(185,28,28,0.4);"
            class="flex h-16 w-16 items-center justify-center rounded-full text-white transition-all duration-300 hover:scale-110 active:scale-95 group cursor-grab active:cursor-grabbing border-none">
        <!-- Chat Icon (Closed) -->
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <!-- Close Icon (Open) -->
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Classic Chat Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-10"
         class="absolute bottom-20 right-0 w-[350px] sm:w-[380px] bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.15)] border border-gray-100 overflow-hidden flex flex-col origin-bottom-right">
        
        <!-- Standard Header -->
        <div style="background-color: #B91C1C;" class="p-4 text-white flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center overflow-hidden">
                    <img src="/3d_mascot_new.png" class="h-8 w-8 object-cover" alt="AI">
                </div>
                <div>
                    <h3 class="font-bold text-sm">Sinau AI Assistant</h3>
                    <p class="text-[10px] text-white/70">Online</p>
                </div>
            </div>
            <button @click="open = false" class="text-white/60 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="h-[400px] overflow-y-auto p-4 space-y-4 bg-gray-50" id="chat-messages">
            <!-- Bot Message -->
            <div class="flex gap-2">
                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                    <img src="/3d_mascot_new.png" class="w-full h-full object-cover">
                </div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 text-sm text-gray-700 max-w-[85%]">
                    Halo! Ada yang bisa saya bantu terkait belajar hari ini?
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-gray-100">
            <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                <input type="text" 
                       placeholder="Ketik pesan..." 
                       class="flex-1 rounded-full border-gray-200 bg-gray-50 py-2 px-4 text-sm focus:ring-1 focus:ring-accent focus:border-accent outline-none"
                       id="chat-input">
                <button type="submit" 
                        style="color: #B91C1C;"
                        class="p-2 hover:bg-gray-50 rounded-full transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
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
        offset = { x: container.offsetLeft - e.clientX, y: container.offsetTop - e.clientY };
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
            localStorage.setItem('chatbot-pos', JSON.stringify({ left: container.style.left, top: container.style.top }));
        }
        isMouseDown = false;
        setTimeout(() => { if (Alpine.$data(container)) Alpine.$data(container).isDragging = false; }, 100);
    });
})();

function sendMessage() {
    const input = document.getElementById('chat-input');
    const messages = document.getElementById('chat-messages');
    if (input.value.trim() === '') return;

    const userMsg = document.createElement('div');
    userMsg.className = 'flex justify-end';
    userMsg.innerHTML = `
        <div style="background-color: #B91C1C;" class="rounded-2xl rounded-tr-none p-3 text-sm text-white shadow-sm max-w-[85%]">
            ${input.value}
        </div>
    `;
    messages.appendChild(userMsg);
    
    const text = input.value;
    input.value = '';
    messages.scrollTop = messages.scrollHeight;

    setTimeout(() => {
        const botMsg = document.createElement('div');
        botMsg.className = 'flex gap-2';
        botMsg.innerHTML = `
            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                <img src="/3d_mascot_new.png" class="w-full h-full object-cover">
            </div>
            <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 text-sm text-gray-700 max-w-[85%]">
                ${text}? Saya sedang memprosesnya...
            </div>
        `;
        messages.appendChild(botMsg);
        messages.scrollTop = messages.scrollHeight;
    }, 1000);
}
</script>
