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

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileInput.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileInput.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileInput.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('bg-accent/10', 'border-accent');
        }

        function unhighlight(e) {
            dropZone.classList.remove('bg-accent/10', 'border-accent');
        }
    </script>
</x-app-layout>
