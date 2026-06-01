<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            Import Dokumen Cerdas <span class="text-3xl">📄</span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-2xl flex items-start gap-3 text-sm font-medium shadow-sm">
                    <svg class="w-6 h-6 shrink-0 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <h4 class="font-bold text-green-800 mb-1">Berhasil Diproses!</h4>
                        <p class="text-green-600">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Simulasi Auto-Download --}}
            @if(session('download'))
                <script>
                    setTimeout(() => {
                        alert("Simulasi: File {{ session('download') }} mulai diunduh ke komputer Anda.");
                    }, 500);
                </script>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-4 rounded-2xl flex items-start gap-3 text-sm font-medium shadow-sm">
                    <svg class="w-6 h-6 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                {{-- AI Import Card --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <div class="text-center max-w-xl mx-auto mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Biarkan AI Membaca Dokumenmu</h3>
                        <p class="text-gray-500 text-sm">Unggah silabus, buku materi, atau instruksi tugas. AI akan memecahnya menjadi jadwal, tugas, dan ringkasan yang mudah dipahami.</p>
                    </div>

                    <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        
                        {{-- Drag & Drop Zone --}}
                        <div class="relative group cursor-pointer">
                            <input type="file" name="document" id="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf,.doc,.docx,.txt" required onchange="updateFileName(this)">
                            
                            <div id="dropZone" class="w-full border-2 border-dashed border-accent/20 rounded-3xl p-12 text-center bg-accent/5 group-hover:bg-accent/10 group-hover:border-accent/40 transition-all duration-300">
                                <div class="w-20 h-20 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-6 text-4xl group-hover:scale-110 transition-transform duration-300">
                                    📥
                                </div>
                                <h4 class="text-lg font-bold text-primary mb-2" id="fileNameDisplay">Tarik file ke sini, atau klik untuk memilih</h4>
                                <p class="text-sm text-primary/60 font-medium">Maksimal 10 MB. Mendukung format: .pdf, .docx, .txt</p>
                            </div>
                        </div>

                        {{-- Action Selection --}}
                        <div class="mt-10">
                            <h4 class="font-bold text-gray-800 mb-4 text-lg">Pilih Tindakan AI:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                {{-- Option 1 --}}
                                <label class="relative flex cursor-pointer rounded-2xl border border-gray-200 bg-white p-4 shadow-sm focus:outline-none hover:bg-gray-50 hover:border-accent/30 transition-all">
                                    <input type="checkbox" name="actions[]" value="schedule" class="peer sr-only" />
                                    <div class="absolute -inset-px rounded-2xl border-2 border-transparent peer-checked:border-accent pointer-events-none" aria-hidden="true"></div>
                                    <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-gray-300 bg-white peer-checked:border-transparent peer-checked:bg-accent">
                                        <svg class="h-4 w-4 text-white opacity-0 peer-checked:opacity-100" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                    </span>
                                    <span class="ml-4 flex flex-col">
                                        <span class="block text-sm font-bold text-gray-900">Ekstrak ke Kalender</span>
                                        <span class="block text-xs text-gray-500 mt-1">AI akan mencari tanggal penting (ujian/silabus) dan menambahkannya ke Kalender.</span>
                                    </span>
                                </label>

                                {{-- Option 2 --}}
                                <label class="relative flex cursor-pointer rounded-2xl border border-gray-200 bg-white p-4 shadow-sm focus:outline-none hover:bg-gray-50 hover:border-accent/30 transition-all">
                                    <input type="checkbox" name="actions[]" value="tasks" class="peer sr-only" checked />
                                    <div class="absolute -inset-px rounded-2xl border-2 border-transparent peer-checked:border-accent pointer-events-none" aria-hidden="true"></div>
                                    <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-gray-300 bg-white peer-checked:border-transparent peer-checked:bg-accent">
                                        <svg class="h-4 w-4 text-white opacity-0 peer-checked:opacity-100" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                    </span>
                                    <span class="ml-4 flex flex-col">
                                        <span class="block text-sm font-bold text-gray-900">Pecah Menjadi Tasks</span>
                                        <span class="block text-xs text-gray-500 mt-1">Mengubah instruksi tugas panjang menjadi sub-tasks kecil di papan Kanban.</span>
                                    </span>
                                </label>

                                {{-- Option 3 --}}
                                <label class="relative flex cursor-pointer rounded-2xl border border-gray-200 bg-white p-4 shadow-sm focus:outline-none hover:bg-gray-50 hover:border-accent/30 transition-all">
                                    <input type="checkbox" name="actions[]" value="summary" class="peer sr-only" />
                                    <div class="absolute -inset-px rounded-2xl border-2 border-transparent peer-checked:border-accent pointer-events-none" aria-hidden="true"></div>
                                    <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-gray-300 bg-white peer-checked:border-transparent peer-checked:bg-accent">
                                        <svg class="h-4 w-4 text-white opacity-0 peer-checked:opacity-100" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                    </span>
                                    <span class="ml-4 flex flex-col">
                                        <span class="block text-sm font-bold text-gray-900">Buat Ringkasan & Kuis</span>
                                        <span class="block text-xs text-gray-500 mt-1">Buat rangkuman materi dan latihan soal otomatis dari isi dokumen.</span>
                                    </span>
                                </label>

                                {{-- Option 4 --}}
                                <label class="relative flex cursor-pointer rounded-2xl border border-gray-200 bg-white p-4 shadow-sm focus:outline-none hover:bg-gray-50 hover:border-accent/30 transition-all">
                                    <input type="checkbox" name="actions[]" value="chat" class="peer sr-only" />
                                    <div class="absolute -inset-px rounded-2xl border-2 border-transparent peer-checked:border-accent pointer-events-none" aria-hidden="true"></div>
                                    <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-gray-300 bg-white peer-checked:border-transparent peer-checked:bg-accent">
                                        <svg class="h-4 w-4 text-white opacity-0 peer-checked:opacity-100" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                    </span>
                                    <span class="ml-4 flex flex-col">
                                        <span class="block text-sm font-bold text-gray-900">Hubungkan ke Chat AI</span>
                                        <span class="block text-xs text-gray-500 mt-1">Memungkinkan Anda melakukan tanya jawab langsung seputar isi buku/dokumen.</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                            <button type="submit" id="submitBtn" style="background-color: #B91C1C;" class="hover:opacity-95 text-white px-8 py-4 rounded-xl font-bold shadow-lg transition-all flex items-center gap-2 mx-auto border-b-4 border-[#450A0A] disabled:opacity-50 disabled:cursor-not-allowed">
                                🚀 Mulai Analisa AI
                            </button>
                        </div>
                    </form>
                </div>
                </div>

                {{-- Word to PDF Converter Card --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <div class="text-center max-w-xl mx-auto mb-8">
                            <div class="flex items-center justify-center gap-4 mb-4">
                                <div class="w-16 h-16 bg-accent/5 rounded-2xl flex items-center justify-center text-3xl shadow-inner border border-accent/20">
                                    📝
                                </div>
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center text-3xl shadow-inner border border-accent/30">
                                    📕
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Ubah Word ke PDF Cepat</h3>
                            <p class="text-gray-500 text-sm">Sangat cocok untuk mengumpulkan tugas kuliah atau sekolah. Format dokumen Anda akan tetap rapi.</p>
                        </div>

                        <form action="{{ route('converter.process') }}" method="POST" enctype="multipart/form-data" id="convertForm">
                            @csrf
                            
                            {{-- Drag & Drop Zone --}}
                            <div class="relative group cursor-pointer">
                                <input type="file" name="document" id="fileInput2" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".doc,.docx" required onchange="updateFileName2(this)">
                                
                                <div id="dropZone2" class="w-full border-2 border-dashed border-accent/20 rounded-3xl p-12 text-center bg-accent/5 group-hover:bg-accent/10 group-hover:border-accent/40 transition-all duration-300">
                                    <div class="w-20 h-20 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-6 text-4xl group-hover:scale-110 transition-transform duration-300">
                                        📄
                                    </div>
                                    <h4 class="text-lg font-bold text-primary mb-2" id="fileNameDisplay2">Tarik file Word (.docx) ke sini, atau klik</h4>
                                    <p class="text-sm text-primary/60 font-medium">Maksimal 10 MB.</p>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                                <button type="submit" style="background-color: #B91C1C;" class="hover:opacity-95 text-white px-8 py-4 rounded-xl font-bold shadow-lg transition-all flex items-center gap-2 mx-auto border-b-4 border-[#450A0A]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Konversi & Download PDF
                                </button>
                                <p class="text-xs text-gray-400 mt-4">Proses konversi aman dan cepat.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PRESET MODAL: Word to PDF Conversion Progress -->
    <div id="converter-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden transform scale-95 transition-all duration-300 relative" id="converter-modal-card">
            
            <!-- Close Button (for success/error states) -->
            <button onclick="closeConverterModal()" id="converter-modal-close-btn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="p-8 text-center space-y-6">
                <!-- State 1: LOADING (Default active) -->
                <div id="converter-state-loading" class="space-y-6">
                    <div class="relative w-24 h-24 flex items-center justify-center mx-auto">
                        <div class="absolute inset-0 rounded-full border-4 border-red-100 dark:border-gray-700"></div>
                        <div style="border-top-color: #B91C1C;" class="absolute inset-0 rounded-full border-4 border-transparent animate-spin"></div>
                        <span class="text-4xl animate-bounce">⚙️</span>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Sedang Mengonversi...</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Harap tunggu. Sistem sedang mengolah halaman, font, dan elemen tabel dokumen Word Anda.</p>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden relative">
                        <div class="bg-red-600 h-full absolute inset-y-0 left-0 rounded-full animate-[loading-bar_1.8s_infinite_linear]" style="width: 40%;"></div>
                    </div>
                </div>

                <!-- State 2: SUCCESS -->
                <div id="converter-state-success" class="space-y-6 hidden">
                    <div class="w-20 h-20 bg-green-50 dark:bg-green-900/30 text-green-500 rounded-full flex items-center justify-center mx-auto text-4xl shadow-inner border border-green-100 dark:border-green-800">
                        ✨
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Konversi Berhasil!</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Dokumen PDF Anda telah selesai dirangkai dengan sempurna.</p>
                    </div>
                    
                    <div class="bg-green-50/50 dark:bg-green-950/20 p-4 rounded-2xl border border-green-100 dark:border-green-900/30">
                        <p class="text-xs text-gray-400 block mb-1">Nama File PDF:</p>
                        <p id="success-file-name" class="font-bold text-sm text-green-700 dark:text-green-400 truncate">document.pdf</p>
                    </div>

                    <div class="space-y-3">
                        <a id="success-download-link" href="#" style="background-color: #B91C1C;" class="w-full py-4 text-white font-bold text-md rounded-2xl shadow-lg hover:opacity-95 transition-all border-b-4 border-[#991B1B] flex items-center justify-center gap-2">
                            📥 Download PDF Sekarang
                        </a>
                        <button onclick="closeConverterModal()" class="text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition mt-2 block mx-auto">
                            Tutup
                        </button>
                    </div>
                </div>

                <!-- State 3: ERROR -->
                <div id="converter-state-error" class="space-y-6 hidden">
                    <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 text-red-500 rounded-full flex items-center justify-center mx-auto text-4xl shadow-inner border border-red-100 dark:border-red-800">
                        ❌
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Konversi Gagal</h3>
                        <p id="error-message-text" class="text-red-600 dark:text-red-400 text-sm">Terjadi kesalahan sistem saat memproses dokumen.</p>
                    </div>
                    <div class="pt-2">
                        <button onclick="closeConverterModal()" style="background-color: #B91C1C;" class="w-full py-3.5 text-white font-bold text-md rounded-2xl shadow-lg hover:opacity-95 transition-all border-b-4 border-[#991B1B]">
                            Tutup & Coba Lagi
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        @keyframes loading-bar {
            0% { left: -40%; }
            100% { left: 100%; }
        }
    </style>

    <script>
        // Scripts for AI Import
        function updateFileName(input) {
            const display = document.getElementById('fileNameDisplay');
            if (input.files && input.files.length > 0) {
                const fileName = input.files[0].name;
                display.innerHTML = `<span class="text-accent font-bold">📄 ${fileName}</span><br><span class="text-sm font-normal mt-1">Siap diproses!</span>`;
            } else {
                display.innerHTML = `Tarik file ke sini, atau klik untuk memilih`;
            }
        }

        // Scripts for Converter
        function updateFileName2(input) {
            const display = document.getElementById('fileNameDisplay2');
            if (input.files && input.files.length > 0) {
                const fileName = input.files[0].name;
                display.innerHTML = `<span class="text-accent font-bold">📝 ${fileName}</span><br><span class="text-sm font-normal mt-1">Siap dikonversi!</span>`;
            } else {
                display.innerHTML = `Tarik file Word (.docx) ke sini, atau klik`;
            }
        }

        // Add simple drag & drop visual feedback
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');

        const dropZone2 = document.getElementById('dropZone2');
        const fileInput2 = document.getElementById('fileInput2');

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Setup Drag and Drop Event Listeners for both Card Inputs
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileInput.addEventListener(eventName, preventDefaults, false);
            fileInput2.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight inputs on drag enter
        ['dragenter', 'dragover'].forEach(eventName => {
            fileInput.addEventListener(eventName, () => dropZone.classList.add('bg-accent/10', 'border-accent'), false);
            fileInput2.addEventListener(eventName, () => dropZone2.classList.add('bg-accent/10', 'border-accent'), false);
        });

        // Unhighlight inputs on drag leave
        ['dragleave', 'drop'].forEach(eventName => {
            fileInput.addEventListener(eventName, () => dropZone.classList.remove('bg-accent/10', 'border-accent'), false);
            fileInput2.addEventListener(eventName, () => dropZone2.classList.remove('bg-accent/10', 'border-accent'), false);
        });

        // Setup AJAX Submit Event Listener for Word to PDF Converter
        document.getElementById('convertForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const fileInputEl = document.getElementById('fileInput2');
            if (!fileInputEl.files || fileInputEl.files.length === 0) {
                alert('Silakan pilih file Word (.docx) terlebih dahulu.');
                return;
            }

            // Reset modal states to loading
            document.getElementById('converter-state-loading').classList.remove('hidden');
            document.getElementById('converter-state-success').classList.add('hidden');
            document.getElementById('converter-state-error').classList.add('hidden');
            document.getElementById('converter-modal-close-btn').classList.add('hidden');

            // Open Modal
            const modal = document.getElementById('converter-modal');
            const card = document.getElementById('converter-modal-card');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 10);

            // Prepare Form Data
            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route("converter.process") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'Gagal mengonversi file. Format tidak didukung atau file rusak.');
                }

                // Show Success state in modal
                document.getElementById('converter-state-loading').classList.add('hidden');
                document.getElementById('converter-state-success').classList.remove('hidden');
                document.getElementById('converter-modal-close-btn').classList.remove('hidden');
                
                document.getElementById('success-file-name').innerText = data.file_name;
                
                const dlLink = document.getElementById('success-download-link');
                dlLink.href = data.download_url;
                dlLink.setAttribute('download', data.file_name);

                // Auto Trigger Download
                const tempAnchor = document.createElement('a');
                tempAnchor.href = data.download_url;
                tempAnchor.setAttribute('download', data.file_name);
                document.body.appendChild(tempAnchor);
                tempAnchor.click();
                document.body.removeChild(tempAnchor);

            } catch (err) {
                // Show Error state in modal
                document.getElementById('converter-state-loading').classList.add('hidden');
                document.getElementById('converter-state-error').classList.remove('hidden');
                document.getElementById('converter-modal-close-btn').classList.remove('hidden');
                document.getElementById('error-message-text').innerText = err.message;
            }
        });

        function closeConverterModal() {
            const modal = document.getElementById('converter-modal');
            const card = document.getElementById('converter-modal-card');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.replace('flex', 'hidden');
            }, 150);
        }
    </script>
</x-app-layout>
