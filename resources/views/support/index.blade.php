<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            Pusat Bantuan <span class="text-3xl">🆘</span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Success Message for Form --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-2xl flex items-start gap-3 text-sm font-medium shadow-sm">
                    <svg class="w-6 h-6 shrink-0 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <h4 class="font-bold text-green-800 mb-1">Berhasil Dikirim!</h4>
                        <p class="text-green-600">{{ session('success') }}</p>
                    </div>
                </div>
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

            {{-- Search Header --}}
            <div style="background: linear-gradient(135deg, #450A0A 0%, #B91C1C 100%);" class="rounded-3xl p-10 md:p-14 shadow-xl flex flex-col items-center text-center relative overflow-hidden border-b-8 border-[#450A0A]">
                <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
                <h3 class="text-3xl md:text-4xl font-black text-white mb-4 relative z-10">Halo! Ada yang bisa kami bantu?</h3>
                <p class="text-white/80 mb-8 relative z-10 max-w-lg font-medium">Temukan jawaban atas pertanyaan Anda, atau hubungi asisten cerdas kami.</p>
                
                <div class="relative w-full max-w-2xl z-10">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" class="w-full pl-12 pr-4 py-4 rounded-2xl border-none shadow-lg focus:ring-4 focus:ring-accent/50 text-gray-800 text-lg" placeholder="Cari panduan (Cth: cara ubah Word ke PDF)...">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- FAQ Section (Left Column) --}}
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pertanyaan Populer (FAQ)
                    </h3>
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-50">
                        @foreach($faqs as $index => $faq)
                        <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }" class="transition-all duration-200">
                            <button @click="open = !open" class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none hover:bg-gray-50 transition-colors">
                                <span class="font-bold text-gray-800 pr-4" :class="{'text-accent': open}">{{ $faq['question'] }}</span>
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 shrink-0" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-collapse>
                                <div class="px-6 pb-5 pt-1 text-gray-500 text-sm leading-relaxed border-t border-gray-50 bg-gray-50/50">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Contact Options (Right Column) --}}
                <div class="lg:col-span-1 space-y-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Kontak Cepat
                    </h3>

                    {{-- AI Bot --}}
                    <button class="w-full bg-gradient-to-br from-accent/5 to-white rounded-3xl p-6 shadow-sm border border-accent/10 text-left hover:shadow-md hover:border-accent/30 transition-all group relative overflow-hidden">
                        <div class="absolute -right-4 -bottom-4 text-6xl opacity-10 group-hover:scale-110 transition-transform">🤖</div>
                        <div class="w-12 h-12 bg-accent/10 text-accent rounded-full flex items-center justify-center text-xl mb-4 shadow-inner">🤖</div>
                        <h4 class="font-bold text-primary text-lg mb-1">Tanya AI Support</h4>
                        <p class="text-xs text-accent/70 font-medium">Respons instan untuk pertanyaan seputar penggunaan aplikasi.</p>
                    </button>

                    {{-- Community --}}
                    <a href="#" class="block w-full bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-accent/30 transition-all group">
                        <div class="w-12 h-12 bg-accent/5 text-accent rounded-full flex items-center justify-center text-xl mb-4 shadow-inner">💬</div>
                        <h4 class="font-bold text-gray-800 text-lg mb-1">Gabung Komunitas</h4>
                        <p class="text-xs text-gray-500">Diskusi dan bagikan prompt AI terbaik dengan ribuan pelajar lainnya via Discord.</p>
                    </a>

                    {{-- Server Status --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <h4 class="font-bold text-gray-800 mb-4 text-sm">Status Server AI</h4>
                        
                        @if($serverStatus === 'normal')
                        <div class="flex items-center gap-3 bg-green-50 px-4 py-3 rounded-xl border border-green-100">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            <div>
                                <div class="text-sm font-bold text-green-800">Sistem Berjalan Normal</div>
                                <div class="text-xs text-green-600">Semua layanan responsif.</div>
                            </div>
                        </div>
                        @elseif($serverStatus === 'busy')
                        <div class="flex items-center gap-3 bg-yellow-50 px-4 py-3 rounded-xl border border-yellow-100">
                            <span class="relative flex h-3 w-3">
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                            </span>
                            <div>
                                <div class="text-sm font-bold text-yellow-800">Server Sibuk</div>
                                <div class="text-xs text-yellow-600">Proses mungkin butuh waktu lama.</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Ticketing Form (Bottom Area) --}}
            <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100 relative overflow-hidden mt-8">
                <div class="absolute top-0 right-0 w-64 h-64 bg-accent/5 rounded-full blur-3xl opacity-50 -mr-20 -mt-20 pointer-events-none"></div>
                <div class="relative z-10 max-w-3xl mx-auto">
                    <div class="text-center mb-10">
                        <div class="w-16 h-16 bg-accent/10 text-accent rounded-full flex items-center justify-center text-3xl mx-auto mb-4">🐛</div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2 text-primary">Masih Belum Menemukan Jawaban?</h3>
                        <p class="text-sm text-gray-500">Kirim tiket laporan bug atau kendala pembayaran ke tim teknisi manusia kami.</p>
                    </div>

                    <form action="{{ route('support.process') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block font-bold text-gray-700 mb-2 text-sm">Topik Kendala</label>
                                <select name="category" class="w-full border-gray-300 focus:border-accent focus:ring-accent rounded-xl shadow-sm py-3 px-4 bg-gray-50" required>
                                    <option value="" disabled selected>Pilih Kategori...</option>
                                    <option value="bug">Laporan Bug / Error Web</option>
                                    <option value="billing">Masalah Pembayaran / Koin</option>
                                    <option value="ai">AI Memberikan Jawaban Salah</option>
                                    <option value="feature">Saran Fitur Baru</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-gray-700 mb-2 text-sm">Alamat Email Balasan</label>
                                <input type="email" value="{{ Auth::user()->email }}" class="w-full border-gray-300 rounded-xl shadow-sm py-3 px-4 bg-gray-100 text-gray-500 cursor-not-allowed" readonly>
                                <p class="text-[10px] text-gray-400 mt-1">Kami akan membalas ke email yang terdaftar ini.</p>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 mb-2 text-sm">Detail Pesan / Kendala</label>
                            <textarea name="message" rows="4" class="w-full border-gray-300 focus:border-accent focus:ring-accent rounded-xl shadow-sm py-3 px-4 bg-gray-50" placeholder="Jelaskan secara detail masalah yang Anda alami..." required></textarea>
                        </div>

                        <div class="text-center pt-2">
                            <button type="submit" style="background-color: #B91C1C;" class="hover:opacity-95 text-white font-bold py-4 px-10 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 w-full md:w-auto mx-auto border-b-4 border-[#450A0A]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Kirim Laporan ke Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
