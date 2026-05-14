<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            Langganan & Tagihan <span class="text-3xl">💳</span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-2xl flex items-start gap-3 text-sm font-medium shadow-sm">
                    <svg class="w-6 h-6 shrink-0 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <h4 class="font-bold text-green-800 mb-1">Pembayaran Sukses!</h4>
                        <p class="text-green-600">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Status Bar (Current Plan) --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
                {{-- Decorative background --}}
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-accent/5 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
                
                <div class="relative z-10 flex-1 w-full">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Paket Saat Ini</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-3xl font-black text-gray-800">{{ $currentPlan }}</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">Active</span>
                    </div>
                    
                    <div class="max-w-md">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-medium text-gray-600">Sisa Kuota Tanya AI Hari Ini</span>
                            <span class="font-bold text-accent">{{ $aiQuotaLeft }} / {{ $aiQuotaTotal }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-3 mb-2 overflow-hidden">
                            <div class="bg-accent h-3 rounded-full transition-all duration-1000" style="width: {{ ($aiQuotaLeft / $aiQuotaTotal) * 100 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500">Kuota gratis akan direset setiap pukul 00:00. Upgrade ke Pro untuk tanya sepuasnya!</p>
                    </div>
                </div>
                
                <div class="relative z-10 shrink-0 w-full md:w-auto flex flex-col gap-3">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-2xl">🪙</div>
                        <div>
                            <div class="text-sm font-semibold text-yellow-800">Saldo Koin AI</div>
                            <div class="text-2xl font-black text-yellow-600">0</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing Tiers --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Basic Plan --}}
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col relative opacity-80 hover:opacity-100 transition-opacity">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">🌱 Basic</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black text-gray-900">Gratis</span>
                            <span class="text-gray-500 font-medium">/ selamanya</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-3">Cocok untuk mencoba fitur dasar Sinau.</p>
                    </div>
                    
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-start gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Akses Tasks & Kalender Pintar</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Limit Chat AI 10x per hari</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Tanpa fitur Import Cerdas (PDF)</span>
                        </li>
                    </ul>
                    
                    <button disabled class="w-full py-3 rounded-xl font-bold bg-gray-100 text-gray-400 cursor-not-allowed">
                        Paket Saat Ini
                    </button>
                </div>

                {{-- Pro Plan (Highlighted) --}}
                <div style="background: linear-gradient(135deg, #450A0A 0%, #B91C1C 100%);" class="rounded-3xl p-8 shadow-xl border-2 border-[#450A0A] flex flex-col relative transform md:-translate-y-4 text-white">
                    <div style="background-color: #B91C1C;" class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white px-4 py-1 rounded-full text-xs font-bold tracking-wide uppercase border border-[#450A0A]">
                        Most Popular (Pelajar)
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-white mb-2">🚀 Pro Student</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-xl text-white/50 line-through mr-1">Rp 49k</span>
                            <span class="text-4xl font-black text-white">Rp 29k</span>
                            <span class="text-white/70 font-medium">/ bln</span>
                        </div>
                        <p class="text-sm text-white/80 mt-3">Untuk pelajar yang ingin produktivitas maksimal.</p>
                    </div>
                    
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-start gap-3 text-sm text-white/90 font-medium">
                            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Chat AI Tanpa Batas (Unlimited)</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-white/90 font-medium">
                            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Akses Fitur Import Data (PDF/Word)</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-white/90 font-medium">
                            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Alat Konversi Word ke PDF</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-white/90 font-medium">
                            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Prioritas Server AI (Lebih Cepat)</span>
                        </li>
                    </ul>
                    
                    <form action="{{ route('billing.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="package" value="pro">
                        <button type="submit" style="background-color: #B91C1C;" class="w-full py-3 rounded-xl font-bold text-white shadow-lg hover:opacity-90 transition-all flex justify-center items-center gap-2 border-b-4 border-[#450A0A]">
                            Mulai Berlangganan Pro
                        </button>
                    </form>
                </div>

                {{-- Genius Plan --}}
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col relative hover:shadow-md transition-shadow">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">🌟 Genius</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black text-gray-900">Rp 89k</span>
                            <span class="text-gray-500 font-medium">/ bln</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-3">Akses penuh ke model AI paling cerdas untuk riset berat.</p>
                    </div>
                    
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-start gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Semua fitur di paket Pro</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-gray-600 font-bold">
                            <svg class="w-5 h-5 text-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span class="text-accent">Akses AI GPT-4 / Claude Opus</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Auto-Quiz Generator Unlimited</span>
                        </li>
                    </ul>
                    
                    <form action="{{ route('billing.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="package" value="genius">
                        <button type="submit" class="w-full py-3 rounded-xl font-bold border-2 border-gray-200 text-gray-700 hover:border-gray-800 hover:text-gray-900 transition-all">
                            Pilih Paket Genius
                        </button>
                    </form>
                </div>
                
            </div>



        </div>
    </div>
</x-app-layout>
