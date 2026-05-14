<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            Pengaturan Aplikasi <span class="text-3xl">⚙️</span>
        </h2>
    </x-slot>

    <div class="py-8" x-data="{ activeTab: 'ai' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-2xl flex items-start gap-3 text-sm font-medium shadow-sm">
                    <svg class="w-6 h-6 shrink-0 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <h4 class="font-bold text-green-800 mb-1">Berhasil!</h4>
                        <p class="text-green-600">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                {{-- Sidebar Navigation (Tabs) --}}
                <div class="w-full lg:w-1/4">
                    <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex flex-col gap-2 sticky top-8">
                        
                        <button @click="activeTab = 'ai'" :class="{'bg-accent/5 text-accent font-bold': activeTab === 'ai', 'text-gray-600 hover:bg-gray-50': activeTab !== 'ai'}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all text-left w-full">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-lg" :class="{'bg-accent/10': activeTab === 'ai', 'bg-gray-100': activeTab !== 'ai'}">🤖</div>
                            <span>Pengaturan AI</span>
                        </button>
                        
                        <button @click="activeTab = 'appearance'" :class="{'bg-accent/5 text-accent font-bold': activeTab === 'appearance', 'text-gray-600 hover:bg-gray-50': activeTab !== 'appearance'}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all text-left w-full">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-lg" :class="{'bg-accent/10': activeTab === 'appearance', 'bg-gray-100': activeTab !== 'appearance'}">🎨</div>
                            <span>Tampilan</span>
                        </button>
                        
                        <button @click="activeTab = 'notifications'" :class="{'bg-accent/5 text-accent font-bold': activeTab === 'notifications', 'text-gray-600 hover:bg-gray-50': activeTab !== 'notifications'}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all text-left w-full">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-lg" :class="{'bg-accent/10': activeTab === 'notifications', 'bg-gray-100': activeTab !== 'notifications'}">🔔</div>
                            <span>Notifikasi</span>
                        </button>
                        
                        <button @click="activeTab = 'integrations'" :class="{'bg-accent/5 text-accent font-bold': activeTab === 'integrations', 'text-gray-600 hover:bg-gray-50': activeTab !== 'integrations'}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all text-left w-full">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-lg" :class="{'bg-accent/10': activeTab === 'integrations', 'bg-gray-100': activeTab !== 'integrations'}">🔗</div>
                            <span>Integrasi</span>
                        </button>
                        
                    </div>
                </div>

                {{-- Content Area --}}
                <div class="w-full lg:w-3/4">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        {{-- AI Tab --}}
                        <div x-show="activeTab === 'ai'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white p-8 shadow-sm border border-gray-100 rounded-3xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-50 -mr-20 -mt-20 pointer-events-none"></div>
                            
                            <div class="relative z-10">
                                <div class="mb-8 border-b border-gray-100 pb-6">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Personalisasi Kepribadian AI</h3>
                                    <p class="text-sm text-gray-500">Atur bagaimana asisten AI Sinau berinteraksi dengan Anda.</p>
                                </div>
                                
                                <div class="space-y-8 max-w-2xl">
                                    {{-- Gaya Bahasa --}}
                                    <div>
                                        <label class="block font-bold text-gray-700 mb-3">Gaya Bahasa AI (Tone)</label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <label class="relative flex cursor-pointer rounded-2xl border border-gray-200 bg-white p-4 shadow-sm hover:border-accent/30 transition-all">
                                                <input type="radio" name="ai_tone" value="casual" class="peer sr-only" checked />
                                                <div class="absolute -inset-px rounded-2xl border-2 border-transparent peer-checked:border-accent pointer-events-none" aria-hidden="true"></div>
                                                <span class="flex items-center gap-3">
                                                    <span class="text-2xl">😎</span>
                                                    <span class="flex flex-col">
                                                        <span class="block text-sm font-bold text-gray-900">Kasual & Santai</span>
                                                        <span class="block text-xs text-gray-500 mt-1">Gaya santai seperti teman, sering menggunakan emoji.</span>
                                                    </span>
                                                </span>
                                            </label>
                                            
                                            <label class="relative flex cursor-pointer rounded-2xl border border-gray-200 bg-white p-4 shadow-sm hover:border-accent/30 transition-all">
                                                <input type="radio" name="ai_tone" value="formal" class="peer sr-only" />
                                                <div class="absolute -inset-px rounded-2xl border-2 border-transparent peer-checked:border-accent pointer-events-none" aria-hidden="true"></div>
                                                <span class="flex items-center gap-3">
                                                    <span class="text-2xl">👨‍🏫</span>
                                                    <span class="flex flex-col">
                                                        <span class="block text-sm font-bold text-gray-900">Formal Akademis</span>
                                                        <span class="block text-xs text-gray-500 mt-1">Bahasa baku dan rapi seperti dosen pengajar.</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Level Pendidikan --}}
                                    <div>
                                        <label class="block font-bold text-gray-700 mb-3">Tingkat Kesulitan Penjelasan</label>
                                        <select name="education_level" class="w-full border-gray-300 focus:border-accent focus:ring-accent rounded-xl shadow-sm py-3 px-4">
                                            <option value="smp">Materi SMP (Mudah & Mendasar)</option>
                                            <option value="sma" selected>Materi SMA (Menengah)</option>
                                            <option value="kuliah">Materi Kuliah / Mahasiswa (Mendalam & Analitis)</option>
                                        </select>
                                        <p class="text-xs text-gray-500 mt-2">AI akan menyesuaikan kosakata (vocabulary) saat menjelaskan rangkuman atau menjawab pertanyaan.</p>
                                    </div>
                                    
                                    {{-- Format Rangkuman --}}
                                    <div class="flex items-center justify-between py-4 border-t border-gray-50">
                                        <div>
                                            <div class="font-bold text-gray-700">Gunakan Format Poin-Poin (Bullet Points)</div>
                                            <div class="text-sm text-gray-500">AI akan mengutamakan list singkat daripada paragraf panjang.</div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="bullet_points" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-accent/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="pt-6">
                                        <button type="submit" class="bg-accent hover:bg-primary text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-accent/20 transition-all">
                                            Simpan Pengaturan AI
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Appearance Tab --}}
                        <div x-show="activeTab === 'appearance'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white p-8 shadow-sm border border-gray-100 rounded-3xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-accent/5 rounded-full blur-3xl opacity-50 -mr-20 -mt-20 pointer-events-none"></div>
                            
                            <div class="relative z-10">
                                <div class="mb-8 border-b border-gray-100 pb-6">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tampilan Aplikasi</h3>
                                    <p class="text-sm text-gray-500">Sesuaikan Sinau agar nyaman di mata Anda.</p>
                                </div>
                                
                                <div class="space-y-8 max-w-2xl">
                                    {{-- Tema --}}
                                    <div>
                                        <label class="block font-bold text-gray-700 mb-3">Tema Dasar (Theme)</label>
                                        <div class="grid grid-cols-3 gap-4">
                                            <label class="relative flex flex-col items-center cursor-pointer p-4 rounded-2xl border-2 border-accent bg-gray-50">
                                                <input type="radio" name="theme" value="light" class="sr-only" checked />
                                                <span class="text-3xl mb-2">☀️</span>
                                                <span class="text-sm font-bold text-gray-900">Light</span>
                                            </label>
                                            <label class="relative flex flex-col items-center cursor-pointer p-4 rounded-2xl border border-gray-200 bg-gray-800 opacity-60">
                                                <input type="radio" name="theme" value="dark" class="sr-only" disabled />
                                                <span class="text-3xl mb-2">🌙</span>
                                                <span class="text-sm font-bold text-white">Dark</span>
                                                <span class="text-[10px] text-gray-400 mt-1">Segera Datang</span>
                                            </label>
                                            <label class="relative flex flex-col items-center cursor-pointer p-4 rounded-2xl border border-gray-200 bg-white opacity-60">
                                                <input type="radio" name="theme" value="system" class="sr-only" disabled />
                                                <span class="text-3xl mb-2">💻</span>
                                                <span class="text-sm font-bold text-gray-900">System</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    {{-- Ukuran Teks --}}
                                    <div>
                                        <label class="block font-bold text-gray-700 mb-3">Ukuran Teks</label>
                                        <select name="font_size" class="w-full border-gray-300 focus:border-accent focus:ring-accent rounded-xl shadow-sm py-3 px-4">
                                            <option value="small">Kecil</option>
                                            <option value="normal" selected>Normal (Default)</option>
                                            <option value="large">Besar (Mudah dibaca)</option>
                                        </select>
                                    </div>

                                    <div class="pt-6">
                                        <button type="submit" class="bg-accent hover:bg-primary text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-accent/20 transition-all">
                                            Simpan Tampilan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Notifications Tab --}}
                        <div x-show="activeTab === 'notifications'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white p-8 shadow-sm border border-gray-100 rounded-3xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-orange-50 rounded-full blur-3xl opacity-50 -mr-20 -mt-20 pointer-events-none"></div>
                            
                            <div class="relative z-10">
                                <div class="mb-8 border-b border-gray-100 pb-6">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Notifikasi & Pengingat</h3>
                                    <p class="text-sm text-gray-500">Jangan sampai terlewat tugas penting.</p>
                                </div>
                                
                                <div class="space-y-6 max-w-2xl">
                                    
                                    <div class="flex items-start justify-between py-4 border-b border-gray-50">
                                        <div>
                                            <div class="font-bold text-gray-700">Pengingat H-1 Deadline Tugas</div>
                                            <div class="text-sm text-gray-500 mt-1">Kirim email pengingat 24 jam sebelum tugas jatuh tempo.</div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer mt-1">
                                            <input type="checkbox" name="notif_deadline" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-accent/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent"></div>
                                        </label>
                                    </div>

                                    <div class="flex items-start justify-between py-4 border-b border-gray-50">
                                        <div>
                                            <div class="font-bold text-gray-700">Email Digest Mingguan</div>
                                            <div class="text-sm text-gray-500 mt-1">Laporan singkat performa belajar dikirim tiap Minggu malam.</div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer mt-1">
                                            <input type="checkbox" name="notif_weekly" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-accent/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent"></div>
                                        </label>
                                    </div>

                                    <div class="flex items-start justify-between py-4 border-b border-gray-50">
                                        <div>
                                            <div class="font-bold text-gray-700">Notifikasi Jadwal Belajar Harian</div>
                                            <div class="text-sm text-gray-500 mt-1">Push notifikasi browser setiap jam 19:00.</div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer mt-1">
                                            <input type="checkbox" name="notif_daily" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-accent/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent"></div>
                                        </label>
                                    </div>

                                    <div class="pt-6">
                                        <button type="submit" class="bg-accent hover:bg-primary text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-accent/20 transition-all">
                                            Simpan Notifikasi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Integrations Tab --}}
                        <div x-show="activeTab === 'integrations'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white p-8 shadow-sm border border-gray-100 rounded-3xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-green-50 rounded-full blur-3xl opacity-50 -mr-20 -mt-20 pointer-events-none"></div>
                            
                            <div class="relative z-10">
                                <div class="mb-8 border-b border-gray-100 pb-6">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Integrasi Aplikasi</h3>
                                    <p class="text-sm text-gray-500">Hubungkan Sinau dengan alat produktivitas Anda yang lain.</p>
                                </div>
                                
                                <div class="space-y-6 max-w-2xl">
                                    
                                    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-2xl">📅</div>
                                            <div>
                                                <h4 class="font-bold text-gray-800">Google Calendar</h4>
                                                <p class="text-xs text-gray-500">Sinkronkan tugas dan jadwal ujian otomatis.</p>
                                            </div>
                                        </div>
                                        <button type="button" class="border-2 border-accent text-accent hover:bg-accent/5 font-bold py-2 px-4 rounded-xl transition-all whitespace-nowrap">
                                            Hubungkan Akun
                                        </button>
                                    </div>

                                    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-2xl">📓</div>
                                            <div>
                                                <h4 class="font-bold text-gray-800">Notion Export</h4>
                                                <p class="text-xs text-gray-500">Kirim rangkuman PDF ke workspace Notion.</p>
                                            </div>
                                        </div>
                                        <button type="button" class="border border-gray-300 text-gray-400 bg-gray-100 font-bold py-2 px-4 rounded-xl cursor-not-allowed whitespace-nowrap">
                                            Segera Datang
                                        </button>
                                    </div>

                                    <div class="mt-8 pt-8 border-t border-gray-100">
                                        <h4 class="font-bold text-gray-800 mb-2">Manajemen Data</h4>
                                        <p class="text-xs text-gray-500 mb-4">Unduh seluruh data personal Anda sebagai file eksternal.</p>
                                        <button type="button" class="text-accent font-bold text-sm hover:underline flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Download Data Account (JSON)
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
